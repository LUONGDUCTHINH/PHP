<?php
// Include your database connection
include 'database.php';

// Check if ID is set in the URL
if (isset($_GET['id'])) {
    $post_id = intval($_GET['id']);
    
    // Fetch post details along with module information
    $stmt = $pdo->prepare("
        SELECT posts.title, posts.content, posts.created_at, posts.image, modules.name AS module_name
        FROM posts
        LEFT JOIN modules ON posts.module_id = modules.id
        WHERE posts.id = :id
    ");
    $stmt->execute(['id' => $post_id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        echo "<p>Post not found!</p>";
        exit;
    }
} else {
    echo "<p>Invalid post ID!</p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
    <link href="style/manage.css" rel="stylesheet">
    <style>
body {
    font-family: 'Poppins', sans-serif;
    background-color: #23242a;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    color: white;
}

.container {
    background-color: #28292d;
    border-radius: 8px;
    padding: 40px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 800px;
    text-align: center;
}

h1 {
    color: #45f3ff;
    font-weight: 600;
    margin-bottom: 20px;
}

.post-content {
    text-align: left;
    margin-top: 20px;
}

.post-content h2 {
    color: #45f3ff;
    font-size: 24px;
    margin-bottom: 10px;
}

.post-content p {
    color: #ccc;
    font-size: 16px;
    line-height: 1.6;
}

.post-content img {
    width: 100%;
    max-width: 600px;
    border-radius: 8px;
    margin-top: 20px;
}

.btn {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 20px;
    background-color: #45f3ff;
    color: white;
    border-radius: 4px;
    font-weight: 600;
    font-size: 15px;
    text-decoration: none;
    transition: background-color 0.3s;
}

.btn:hover {
    background-color: #33d1d9;
}
</style>

</head>
<body>
<div class="container">
    <h1> Post Title: <?php echo htmlspecialchars($post['title']); ?></h1>
    <p><strong>Module:</strong> <?php echo htmlspecialchars($post['module_name']); ?></p>
    <p> Post Content: <?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
    <p><small>Posted on: <?php echo $post['created_at']; ?></small></p>
    <a href="manage_posts.php" class="btn">Back to Manage Posts</a>
</div>
</body>
</html>
