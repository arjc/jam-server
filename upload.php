
<?php

require_once __DIR__ . '/config.php';

function stop($message, $code = 400) {
    http_response_code($code);
    echo '<h2>' . htmlspecialchars($message) . '</h2>';
    echo '<a href="/admin.php">Return to Admin Panel</a>';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    stop('Method not allowed.', 405);
}

// Password verification on EVERY upload
$password = $_POST['password'] ?? '';

if (!password_verify($password, ADMIN_PASSWORD_HASH)) {
    stop('Incorrect administrator password.', 403);
}

// Validate upload directory
if (!is_dir(UPLOAD_DIR) || !is_writable(UPLOAD_DIR)) {
    stop('Upload directory unavailable.', 500);
}

if (!isset($_FILES['files'])) {
    stop('No files selected.');
}

$uploaded = 0;
$failed = 0;

foreach ($_FILES['files']['name'] as $i => $originalName) {

    if ($_FILES['files']['error'][$i] !== UPLOAD_ERR_OK) {
        $failed++;
        continue;
    }

    if ($_FILES['files']['size'][$i] > MAX_FILE_SIZE) {
        $failed++;
        continue;
    }

    $originalName = basename($originalName);

    $safeName = preg_replace(
        '/[^a-zA-Z0-9._-]/',
        '_',
        $originalName
    );

    if (!$safeName || $safeName === '.' || $safeName === '..') {
        $failed++;
        continue;
    }

    // Random prefix prevents overwriting existing files
    $filename = bin2hex(random_bytes(8)) . '_' . $safeName;

    if (move_uploaded_file(
        $_FILES['files']['tmp_name'][$i],
        UPLOAD_DIR . $filename
    )) {
        $uploaded++;
    } else {
        $failed++;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Result</title>
</head>
<body>

<h1>Jambuvan Upload Results</h1>

<p>Successfully uploaded: <?= $uploaded ?></p>
<p>Failed uploads: <?= $failed ?></p>

<p><a href="/admin.php">Upload more files</a></p>
<p><a href="/">Return to homepage</a></p>

</body>
</html>
