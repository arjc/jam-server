
<?php
require_once __DIR__ . '/config.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Jambuvan Admin Panel</title>
</head>
<body>

<h1>Jambuvan Admin Panel</h1>
<hr>

<h2>Administrator Authentication</h2>

<p>Admin password is required for every upload.</p>

<form action="/upload.php" method="POST"
      enctype="multipart/form-data">

    <p>
        <label>Admin Password:</label><br>
        <input type="password" name="password" required>
    </p>

    <p>
        <label>Select files:</label><br>
        <input type="file" name="files[]" multiple required>
    </p>

    <button type="submit">Authenticate & Upload</button>

</form>

<hr>

<p>Access restricted to server administrator.</p>
<p><a href="/">Return to homepage</a></p>

</body>
</html>
