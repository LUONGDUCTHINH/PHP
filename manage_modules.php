<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

include 'database.php';

// Fetch all modules
$stmt = $pdo->query("SELECT * FROM modules");
$modules = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<html lang="en"><head>
    <meta charset="UTF-8">
    <title>Manage Modules</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&amp;display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }
        h1 {
            color: #4a4a4a;
            text-align: center;
            margin-bottom: 30px;
            font-weight: 600;
        }
        .btn {
            padding: 12px 20px;
            font-size: 16px;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: block;
            width: 93%;
            text-align: center;
            text-decoration: none;
            margin-bottom: 20px;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .module-list {
            list-style-type: none;
            padding: 0;
        }
        .module-list li {
            margin-bottom: 10px;
        }
        .module-list a {
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .module-list a.edit {
            color: #4a4a4a;
        }
        .module-list a.edit:hover {
            color: #87ceeb; /* Sky Blue */
            cursor: pointer;
        }
        .module-list a.delete {
            color: #f44336; /* Red */
        }
        .module-list a.delete:hover {
            color: #ff7961; /* Lighter Red */
            cursor: pointer;
        }
        .message {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
        }
        .message.success {
            background-color: #e8f5e9;
            color: #4caf50;
        }
        .message.error {
            background-color: #ffebee;
            color: #f44336;
        }
    </style>
    <script>
        function confirmDelete(url) {
            if (confirm('Are you sure you want to delete this module?')) {
                window.location.href = url;
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Manage Modules</h1>
        <a href="add_module.php" class="btn">Add New Module</a>
                <ul class="module-list">
        
        <a href="admin_dashboard.php" class="btn">Back to Admin Dashboard</a>
    </div>


</body></html>
