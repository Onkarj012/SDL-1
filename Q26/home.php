<?php
    session_start();

    $conn = new mysqli('localhost', 'root', '');
    $conn->query("CREATE DATABASE IF NOT EXISTS facebook_clone");
    $conn->select_db("facebook_clone");

    $conn->query("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL
    )");

    $conn->query("CREATE TABLE IF NOT EXISTS posts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        content TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");

    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }

    if (isset($_POST['content'])) {
        $content = $_POST['content'];
        $uid = $_SESSION['user_id'];

        $stmt = $conn->prepare("INSERT INTO posts (user_id, content) VALUES (?, ?)");
        $stmt->bind_param("is", $uid, $content);
        $stmt->execute();
        header('Location: home.php');
        exit();
    }

    $result = $conn->query("
        SELECT posts.content, posts.created_at, users.username 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        ORDER BY posts.created_at DESC
    ");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        h1, h2 {
            margin: 20px 0 10px;
        }
        textarea {
            width: 100%;
            padding: 10px;
            resize: vertical;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 8px 14px;
            margin-top: 5px;
            cursor: pointer;
            border-radius: 4px;
        }
        .logout-btn {
            background: #dc3545;
        }
        .post {
            background: white;
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 6px;
        }
        .post-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .post-content {
            white-space: pre-wrap;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>News Feed</h1>
            <a href="logout.php"><button class="logout-btn">Logout</button></a>
        </div>

        <h2>Post Something</h2>
        <form method="POST">
            <textarea name="content" placeholder="What's on your mind?" required></textarea>
            <button type="submit">Post</button>
        </form>

        <h2>All Posts</h2>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="post">
                <div class="post-header">
                    <span><?php echo htmlspecialchars($row['username']); ?></span>
                    <small><?php echo $row['created_at']; ?></small>
                </div>
                <div class="post-content">
                    <?php echo nl2br(htmlspecialchars($row['content'])); ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>