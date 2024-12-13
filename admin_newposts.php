<?php
// Include your database connection (use PDO or your preferred method)
include 'database.php';

session_start();

// Check if the admin is logged in by verifying if 'admin_id' is set
if (!isset($_SESSION['admin_id'])) {
    // If not logged in, redirect to login page
    header("Location: admin_login.php");
    exit();
}

// Get the logged-in admin's user ID from the session
$user_id = $_SESSION['admin_id'];

// Fetch available modules from the database
$stmt = $pdo->query("SELECT id, name FROM modules");
$modules = $stmt->fetchAll();

// Add the new modules to the list of modules
$additional_modules = [
    ['id' => 'total', 'name' => 'Total'],
    ['id' => 'html', 'name' => 'HTML'],
    ['id' => 'space', 'name' => 'Space'],
    ['id' => 'python', 'name' => 'Python'],
];

// Merge the additional modules with the existing ones
$modules = array_merge($modules, $additional_modules);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $module_id = $_POST['module_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Handle image upload
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_path = 'uploads/' . $image_name;
        move_uploaded_file($image_tmp_name, $image_path);
        $image = $image_path; // Store the image path
    }

    // Insert the post into the database
    $stmt = $pdo->prepare("INSERT INTO posts (user_id, module_id, title, content, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $module_id, $title, $content, $image]);

    // Redirect to manage_posts.php after successful insertion
    header("Location: manage_posts.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Post</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&amp;display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #23242a;
            margin: 0;
            padding: 0;
            display: grid;
            place-content: center;
            height: 100vh;
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
        .form-group {
            margin-bottom: 20px;
        }
        .form-control, .form-control-file {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: none;
            outline: none;
            background-color: #3a3b3d;
            color: white;
            border-radius: 5px;
            margin-top: 8px;
        }
        .form-control:focus, .form-control-file:focus {
            background-color: black;
            box-shadow: 0 0 5px rgba(70, 243, 255, 0.5);
        }
        textarea.form-control {
            resize: vertical;
        }
        button {
            width: 100%;
            padding: 12px 20px;
            background-color: #45f3ff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 15px;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #33d1d9;
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
        }
        .btn:hover {
            background-color: #33d1d9;
        }
        .success, .error {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }
        .success {
            background-color: #e8f5e9;
            color: #4caf50;
        }
        .error {
            background-color: #ffebee;
            color: #f44336;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Add New Post</h1>
    <form method="POST" enctype="multipart/form-data">
    <div class="form-group">
            <label for="module_id">Module:</label>
            <select id="module_id" name="module_id" class="form-control" required>
                <?php foreach ($modules as $module): ?>
                    <option value="<?php echo htmlspecialchars($module['id']); ?>">
                        <?php echo htmlspecialchars($module['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="content">Content:</label>
            <textarea id="content" name="content" class="form-control" rows="6" required></textarea>
        </div>
        <div class="form-group">
            <label for="image">Image:</label>
            <input type="file" id="image" name="image" class="form-control-file">
        </div>
        <button type="submit" class="btn">Add Post</button>
    </form>
    <br>
    <a href="manage_posts.php" class="btn">Back to Manage Posts</a>
</div>
</body>
</html>
