<?php
// This page has been merged into gallery.php.
$eventId = (int)($_GET['event_id'] ?? 0);
header('Location: gallery.php' . ($eventId ? '?event=' . $eventId : ''));
exit;
