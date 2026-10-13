<?php
require_once __DIR__ . '/../lib/posts.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);

if ($id > 0 && find_post($id)) {
    delete_post($id);
    header('Location: index.php?status=deleted');
    exit;
}

header('Location: index.php');
exit;
