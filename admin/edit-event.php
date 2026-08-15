<?php
require_once __DIR__ . '/includes/auth.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM gallery_events WHERE id = ?");
$stmt->execute([$id]);
$event = $stmt->fetch();

if (!$event) {
    header('Location: gallery.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title'] ?? '');
    $eventDate   = $_POST['event_date'] ?: null;
    $description = trim($_POST['description'] ?? '');
    $status      = $_POST['status'] === 'inactive' ? 'inactive' : 'active';

    if ($title === '') {
        $error = 'Event title required hai.';
    } else {
        $coverImage = $event['cover_image'];

        if (!empty($_FILES['cover_image']['name'])) {
            $allowed = ['jpg','jpeg','png','webp'];
            $ext = strtolower(pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, $allowed) && $_FILES['cover_image']['size'] <= 5 * 1024 * 1024) {
                if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);
                $fileName = 'cover_' . time() . '_' . rand(100,999) . '.' . $ext;
                if (move_uploaded_file($_FILES['cover_image']['tmp_name'], UPLOAD_DIR . $fileName)) {
                    if ($coverImage && file_exists(UPLOAD_DIR . $coverImage)) {
                        unlink(UPLOAD_DIR . $coverImage);
                    }
                    $coverImage = $fileName;
                }
            } else {
                $error = 'Cover image sirf jpg/png/webp aur max 5MB honi chahiye.';
            }
        }

        if (!$error) {
            $upd = $pdo->prepare("
                UPDATE gallery_events
                SET title = ?, event_date = ?, description = ?, cover_image = ?, status = ?
                WHERE id = ?
            ");
            $upd->execute([$title, $eventDate, $description, $coverImage, $status, $id]);
            header('Location: gallery.php?updated=1');
            exit;
        }
    }
}

$pageTitle = 'Edit Event';
require_once __DIR__ . '/includes/header.php';
?>

<?php if ($error): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card" style="max-width:640px;">
    <h3 style="margin-top:0;color:#0f2540;">Edit Event</h3>
    <form method="POST" action="edit-event.php?id=<?= $event['id'] ?>" enctype="multipart/form-data">
        <div class="form-group">
            <label>Event Title</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($event['title']) ?>" required>
        </div>
        <div class="form-group">
            <label>Event Date</label>
            <input type="date" name="event_date" class="form-control" value="<?= htmlspecialchars($event['event_date'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($event['description'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label>Current Cover Image</label><br>
            <?php if ($event['cover_image']): ?>
                <img src="<?= htmlspecialchars(UPLOAD_URL . $event['cover_image']) ?>" style="width:120px;border-radius:8px;margin:8px 0;">
            <?php else: ?>
                <span style="color:#8593a0;">Koi cover image set nahi hai.</span>
            <?php endif; ?>
            <input type="file" name="cover_image" class="form-control" accept="image/*">
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="active" <?= $event['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= $event['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" style="width:auto;">Save Changes</button>
        <a href="gallery.php" class="btn btn-outline">Cancel</a>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
