<?php
require_once __DIR__ . '/includes/auth.php';

$error   = '';
$success = '';

/* ---------- Helper: unique slug ---------- */
function make_unique_slug($pdo, $title) {
    $baseSlug = strtolower(trim(preg_replace('/[^A-Za-z0-9]+/', '-', $title), '-'));
    if ($baseSlug === '') $baseSlug = 'event';
    $slug = $baseSlug;
    $i = 1;
    $chk = $pdo->prepare("SELECT id FROM gallery_events WHERE slug = ?");
    while (true) {
        $chk->execute([$slug]);
        if (!$chk->fetch()) break;
        $slug = $baseSlug . '-' . (++$i);
    }
    return $slug;
}

/* ---------- Helper: human readable upload error ---------- */
function upload_error_message($code) {
    switch ($code) {
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            return 'File size limit (php.ini) se badi hai.';
        case UPLOAD_ERR_PARTIAL:
            return 'File poori upload nahi hui (partial upload).';
        case UPLOAD_ERR_NO_FILE:
            return 'Koi file select nahi hui.';
        case UPLOAD_ERR_NO_TMP_DIR:
            return 'Server par temp folder missing hai.';
        case UPLOAD_ERR_CANT_WRITE:
            return 'Server disk par likh nahi paya.';
        case UPLOAD_ERR_EXTENSION:
            return 'Ek PHP extension ne upload rok diya.';
        default:
            return 'Unknown upload error (code ' . $code . ').';
    }
}

/* ---------- Handle Upload (choose existing event OR create new) ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'upload_photos') {

    $eventChoice = $_POST['event_id'] ?? '';
    $eventId     = 0;

    if ($eventChoice === 'new') {
        $newTitle = trim($_POST['new_event_title'] ?? '');
        $newDate  = $_POST['new_event_date'] ?: null;

        if ($newTitle === '') {
            $error = 'Naya event banane ke liye title required hai.';
        } else {
            $slug = make_unique_slug($pdo, $newTitle);
            $ins = $pdo->prepare("INSERT INTO gallery_events (title, slug, event_date, status) VALUES (?, ?, ?, 'active')");
            $ins->execute([$newTitle, $slug, $newDate]);
            $eventId = (int)$pdo->lastInsertId();
        }
    } else {
        $eventId = (int)$eventChoice;
        $chk = $pdo->prepare("SELECT id FROM gallery_events WHERE id = ?");
        $chk->execute([$eventId]);
        if (!$chk->fetch()) {
            $error = 'Selected event nahi mila. Dubara try karo.';
            $eventId = 0;
        }
    }

    if (!$error && $eventId > 0) {
        if (empty($_FILES['photos']) || empty($_FILES['photos']['name'][0])) {
            $error = 'Koi photo select nahi ki.';
        } else {
            // Make sure upload folder exists and is writable
            if (!is_dir(UPLOAD_DIR)) {
                @mkdir(UPLOAD_DIR, 0755, true);
            }
            if (!is_dir(UPLOAD_DIR) || !is_writable(UPLOAD_DIR)) {
                $error = 'Upload folder (assets/gallery/) likhne layak nahi hai. Server par folder permissions 755 (ya 775) set karo.';
            } else {
                $allowedExt  = ['jpg', 'jpeg', 'png', 'webp'];
                $allowedMime = ['image/jpeg', 'image/png', 'image/webp'];
                $maxSize     = 5 * 1024 * 1024;

                $uploaded = 0;
                $failures = [];
                $insert = $pdo->prepare("INSERT INTO gallery_photos (event_id, image_path, caption) VALUES (?, ?, ?)");

                foreach ($_FILES['photos']['name'] as $i => $name) {
                    if ($name === '') continue;

                    $errCode = $_FILES['photos']['error'][$i];
                    if ($errCode !== UPLOAD_ERR_OK) {
                        $failures[] = htmlspecialchars($name) . ': ' . upload_error_message($errCode);
                        continue;
                    }

                    $tmpPath = $_FILES['photos']['tmp_name'][$i];
                    $size    = $_FILES['photos']['size'][$i];
                    $ext     = strtolower(pathinfo($name, PATHINFO_EXTENSION));

                    if (!in_array($ext, $allowedExt, true)) {
                        $failures[] = htmlspecialchars($name) . ': sirf jpg/png/webp allowed hai.';
                        continue;
                    }
                    if ($size > $maxSize) {
                        $failures[] = htmlspecialchars($name) . ': 5MB se badi hai.';
                        continue;
                    }

                    // Verify it's actually a valid image (prevents corrupt/fake files
                    // from being saved and then showing broken on the frontend).
                    $imgInfo = @getimagesize($tmpPath);
                    if ($imgInfo === false || !in_array($imgInfo['mime'], $allowedMime, true)) {
                        $failures[] = htmlspecialchars($name) . ': valid image file nahi hai (corrupt ya galat format).';
                        continue;
                    }

                    $fileName = 'photo_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                    $destPath = UPLOAD_DIR . $fileName;

                    if (!move_uploaded_file($tmpPath, $destPath)) {
                        $failures[] = htmlspecialchars($name) . ': server par save nahi hui (permissions/disk space check karo).';
                        continue;
                    }

                    @chmod($destPath, 0644);
                    $insert->execute([$eventId, $fileName, null]);
                    $uploaded++;
                }

                if ($uploaded > 0) {
                    $success = "$uploaded photo(s) successfully upload ho gayi.";
                }
                if (!empty($failures)) {
                    $error = 'Kuch photos upload nahi ho payi: ' . implode(' | ', $failures);
                }
                if ($uploaded === 0 && empty($failures)) {
                    $error = 'Koi valid photo upload nahi hui.';
                }

                if ($uploaded > 0) {
                    header('Location: gallery.php?uploaded=' . $uploaded . '&event=' . $eventId . ($error ? '&had_errors=1' : ''));
                    // Stash failures in session briefly so redirect can still show them
                    if (!empty($failures)) {
                        $_SESSION['gallery_upload_failures'] = $failures;
                    }
                    exit;
                }
            }
        }
    }
}

if (!empty($_SESSION['gallery_upload_failures'])) {
    $error = 'Kuch photos upload nahi ho payi: ' . implode(' | ', $_SESSION['gallery_upload_failures']);
    unset($_SESSION['gallery_upload_failures']);
}
if (isset($_GET['uploaded'])) {
    $success = (int)$_GET['uploaded'] . ' photo(s) successfully upload ho gayi.';
}

/* ---------- Handle Delete Photo ---------- */
if (isset($_GET['delete_photo'])) {
    $photoId = (int)$_GET['delete_photo'];
    $stmt = $pdo->prepare("SELECT image_path FROM gallery_photos WHERE id = ?");
    $stmt->execute([$photoId]);
    $photo = $stmt->fetch();
    if ($photo) {
        $path = UPLOAD_DIR . $photo['image_path'];
        if (file_exists($path)) unlink($path);
        $pdo->prepare("DELETE FROM gallery_photos WHERE id = ?")->execute([$photoId]);
    }
    header('Location: gallery.php?deleted=1' . (isset($_GET['event']) ? '&event=' . (int)$_GET['event'] : ''));
    exit;
}

/* ---------- Handle Delete Event ---------- */
if (isset($_GET['delete_event'])) {
    $id = (int)$_GET['delete_event'];

    $stmt = $pdo->prepare("SELECT image_path FROM gallery_photos WHERE event_id = ?");
    $stmt->execute([$id]);
    foreach ($stmt->fetchAll() as $photo) {
        $path = UPLOAD_DIR . $photo['image_path'];
        if (file_exists($path)) unlink($path);
    }

    $stmt = $pdo->prepare("SELECT cover_image FROM gallery_events WHERE id = ?");
    $stmt->execute([$id]);
    $ev = $stmt->fetch();
    if ($ev && $ev['cover_image'] && file_exists(UPLOAD_DIR . $ev['cover_image'])) {
        unlink(UPLOAD_DIR . $ev['cover_image']);
    }

    $pdo->prepare("DELETE FROM gallery_events WHERE id = ?")->execute([$id]);
    header('Location: gallery.php?event_deleted=1');
    exit;
}

if (isset($_GET['deleted'])) $success = 'Photo delete ho gayi.';
if (isset($_GET['event_deleted'])) $success = 'Event delete ho gaya.';
if (isset($_GET['updated'])) $success = 'Event update ho gaya.';

/* ---------- All events (for dropdown + management table) ---------- */
$events = $pdo->query("
    SELECT e.*, (SELECT COUNT(*) FROM gallery_photos p WHERE p.event_id = e.id) AS photo_count
    FROM gallery_events e
    ORDER BY e.event_date DESC, e.id DESC
")->fetchAll();

/* ---------- Filter photos by event (optional) ---------- */
$filterEvent = isset($_GET['event']) ? (int)$_GET['event'] : 0;

/* ---------- Pagination: 24 photos per page ---------- */
$perPage = 24;
$page = max(1, (int)($_GET['page'] ?? 1));

if ($filterEvent > 0) {
    $totalStmt = $pdo->prepare("SELECT COUNT(*) FROM gallery_photos WHERE event_id = ?");
    $totalStmt->execute([$filterEvent]);
} else {
    $totalStmt = $pdo->query("SELECT COUNT(*) FROM gallery_photos");
}
$total = $totalStmt->fetchColumn();
$totalPages = max(1, ceil($total / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;

if ($filterEvent > 0) {
    $stmt = $pdo->prepare("
        SELECT p.*, e.title AS event_title
        FROM gallery_photos p
        JOIN gallery_events e ON e.id = p.event_id
        WHERE p.event_id = :eid
        ORDER BY p.id DESC
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':eid', $filterEvent, PDO::PARAM_INT);
} else {
    $stmt = $pdo->prepare("
        SELECT p.*, e.title AS event_title
        FROM gallery_photos p
        JOIN gallery_events e ON e.id = p.event_id
        ORDER BY p.id DESC
        LIMIT :limit OFFSET :offset
    ");
}
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$photos = $stmt->fetchAll();

$pageTitle = 'Gallery';
require_once __DIR__ . '/includes/header.php';
?>

<?php if ($error): ?>
    <div class="alert alert-error"><?= $error ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="card">
    <h3 style="margin-top:0;color:#0f2540;"><i class="fa-solid fa-upload"></i> Upload Photos</h3>
    <form method="POST" action="/admin/gallery" enctype="multipart/form-data" id="uploadForm">
        <input type="hidden" name="action" value="upload_photos">

        <div class="form-group">
            <label>Event</label>
            <select name="event_id" class="form-control" id="eventSelect" required>
                <option value="">-- Event select karo --</option>
                <?php foreach ($events as $ev): ?>
                    <option value="<?= $ev['id'] ?>" <?= $filterEvent === (int)$ev['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($ev['title']) ?><?= $ev['event_date'] ? ' (' . date('d M Y', strtotime($ev['event_date'])) . ')' : '' ?>
                    </option>
                <?php endforeach; ?>
                <option value="new">+ Naya Event Banao</option>
            </select>
        </div>

        <div id="newEventFields" style="display:none;">
            <div style="display:grid;grid-template-columns:2fr 1fr;gap:16px;">
                <div class="form-group">
                    <label>New Event Title</label>
                    <input type="text" name="new_event_title" class="form-control" placeholder="e.g. Annual Sports Day 2026">
                </div>
                <div class="form-group">
                    <label>Event Date (optional)</label>
                    <input type="date" name="new_event_date" class="form-control">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Select Photos (multiple allowed, jpg/png/webp, max 5MB each)</label>
            <input type="file" name="photos[]" class="form-control" accept="image/*" multiple required>
        </div>

        <button type="submit" class="btn btn-primary" style="width:auto;">
            <i class="fa-solid fa-upload"></i> Upload
        </button>
    </form>
</div>

<div class="card">
    <div class="top-actions">
        <h3 style="margin:0;color:#0f2540;">
            Photos (<?= (int)$total ?><?= $filterEvent > 0 ? ' in this event' : ' total' ?>)
        </h3>
        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
            <form method="GET" action="/admin/gallery" style="display:flex;gap:8px;">
                <select name="event" class="form-control" onchange="this.form.submit()">
                    <option value="0">All Events</option>
                    <?php foreach ($events as $ev): ?>
                        <option value="<?= $ev['id'] ?>" <?= $filterEvent === (int)$ev['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($ev['title']) ?> (<?= (int)$ev['photo_count'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
    </div>

    
    <?php if (empty($photos)): ?>
        <p style="color:#8593a0;">Abhi tak koi photo upload nahi hui.</p>
    <?php else: ?>
        <div class="photo-grid">
            <?php foreach ($photos as $p): ?>
                <div class="photo-item">
                    <img src="<?= htmlspecialchars(UPLOAD_URL . $p['image_path']) ?>" alt="" loading="lazy">
                    <?php if ($filterEvent === 0): ?>
                        <div style="font-size:11px;color:#8593a0;padding:4px 2px;"><?= htmlspecialchars($p['event_title']) ?></div>
                    <?php endif; ?>
                    <form method="GET" action="/admin/gallery" onsubmit="return confirm('Ye photo delete karein?');">
                        <input type="hidden" name="event" value="<?= $filterEvent ?>">
                        <button type="submit" name="delete_photo" value="<?= $p['id'] ?>" title="Delete">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php $qs = $filterEvent > 0 ? '&event=' . $filterEvent : ''; ?>
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?><?= $qs ?>">&laquo;</a>
            <?php else: ?>
                <span class="disabled">&laquo;</span>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?><?= $qs ?>" class="<?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?><?= $qs ?>">&raquo;</a>
            <?php else: ?>
                <span class="disabled">&raquo;</span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<div class="card">
    <h3 style="margin-top:0;color:#0f2540;">All Events (<?= count($events) ?>)</h3>
    <?php if (empty($events)): ?>
        <p style="color:#8593a0;">Koi event nahi hai. Upar photo upload karte waqt naya event bana sakte ho.</p>
    <?php else: ?>
    <table class="data-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Date</th>
                <th>Photos</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($events as $ev): ?>
            <tr>
                <td><b><?= htmlspecialchars($ev['title']) ?></b></td>
                <td><?= $ev['event_date'] ? date('d M Y', strtotime($ev['event_date'])) : '-' ?></td>
                <td><?= (int)$ev['photo_count'] ?></td>
                <td><span class="badge badge-<?= $ev['status'] ?>"><?= ucfirst($ev['status']) ?></span></td>
                <td style="white-space:nowrap;">
                    <a href="gallery.php?event=<?= $ev['id'] ?>" class="btn btn-outline btn-sm">
                        <i class="fa-solid fa-images"></i> View
                    </a>
                    <a href="edit-event.php?id=<?= $ev['id'] ?>" class="btn btn-outline btn-sm">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                    <a href="gallery.php?delete_event=<?= $ev['id'] ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('Ye event aur uski saari photos delete ho jayengi. Confirm?');">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<script>
const eventSelect = document.getElementById('eventSelect');
const newEventFields = document.getElementById('newEventFields');
function toggleNewEventFields() {
    if (eventSelect.value === 'new') {
        newEventFields.style.display = 'block';
        newEventFields.querySelector('input[name="new_event_title"]').required = true;
    } else {
        newEventFields.style.display = 'none';
        newEventFields.querySelector('input[name="new_event_title"]').required = false;
    }
}
eventSelect.addEventListener('change', toggleNewEventFields);
toggleNewEventFields();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
