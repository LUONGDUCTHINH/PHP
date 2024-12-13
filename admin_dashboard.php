<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    // Redirect to login page if the admin is not logged in
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="style/admin.css" rel="stylesheet">
</head>
<body>
      <div class="bg"></div>
        <div class="star-field">
        <div class="layer"></div>
        <div class="layer"></div>
        <div class="layer"></div>   
    </div>

<h1>Welcome to Admin Dashboard</h1>
<p>Hello, <?php echo $_SESSION['admin_username']; ?>!</p>

<div class="dashboard-container">
    <div class="dashboard-box">
        <h2>Manage Posts</h2>
        <a href="manage_posts.php">View and Manage Posts</a>
    </div>
    <div class="dashboard-box">
        <h2>Manage Users</h2>
        <a href="manage_users.php">View and Manage Users</a>
    </div>
    <div class="dashboard-box">
        <h2>Manage Modules</h2>
        <a href="manage_modules.php">View and Manage Modules</a>
    </div>
</div>

<a href="login.php" class="logout-btn">Logout</a>

</body>
</html>
