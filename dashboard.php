<?php
session_start();
include 'database.php'; // Ensure this file contains the PDO connection

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Retrieve user data from session
$fullname = $_SESSION['fullname'];
$username = $_SESSION['username'];

// Fetch posts from the database
try {
    $stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching posts: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: "Poppins", sans-serif;
            background-color: #23242a;
            color: #fff;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        h1{
            text-align: center;
        }
        .container {
            width: 80%;
            background-color: #1c1c1c;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
        }
        p{
            text-align: center;
        }
        .welcome h1 {
            color: #45f3ff;
            margin-bottom: 10px;
        }
        .btn {
            display: inline-block;
            margin: 10px 0;
            padding: 10px 20px;
            color: #1c1c1c;
            background-color: #45f3ff;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s, color 0.3s;
        }
        .btn:hover {
            background-color: #00d2ff;
        }
        .posts {
            margin-top: 20px;
        }
        .post {
            background: #333;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.5);
        }
        .post h2 a {
            color: #45f3ff;
            text-decoration: none;
        }
        .post h2 a:hover {
            text-decoration: underline;
        }
        .post-meta {
            font-size: 14px;
            color: #aaa;
        }
        .btn-container {
            display: flex;
            justify-content: center; /* Center horizontally */
            gap: 20px; /* Space between buttons */
            margin: 20px 0; /* Vertical spacing */
            flex-wrap: wrap; /* Wrap buttons if they don't fit in one line */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="welcome">
            <h1>Welcome, <?php echo htmlspecialchars($fullname); ?>!</h1>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
        </div>
        <div class="btn-container">
        <a href="createquestion.php" class="btn">Create New Question</a>
        <a href="edit_profile.php" class="btn">Edit Profile</a>
        <a href="login.php" class="btn">Logout</a>
        <a href="mailto:luongducthinh@gmail.com" class="btn">Contact Admin</a>
        </div>
        <div class="posts">
            <h2>All Posts</h2>
            <?php if (count($posts) > 0): ?>
                <?php foreach ($posts as $post): ?>
                    <div class="post">
                        <h2><a href="viewpost.php?id=<?php echo $post['id']; ?>">
                            <?php echo htmlspecialchars($post['title']); ?>
                        </a></h2>
                        <div class="post-meta">
                            <p><strong>Posted by:</strong> <?php echo htmlspecialchars($post['username']); ?></p>
                            <p><strong>Date:</strong> <?php echo htmlspecialchars($post['created_at']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No posts available.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
