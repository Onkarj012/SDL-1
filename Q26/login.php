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

    if (isset($_POST['username'], $_POST['password'])) {
        $user = $_POST['username'];
        $pass = $_POST['password'];

        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows) {
            $stmt->bind_result($id, $hashed);
            $stmt->fetch();
            
            if (password_verify($pass, $hashed)) {
                $_SESSION['user_id'] = $id;
                header('Location: home.php');
                exit();
            }
        }
        $error = "Invalid login.";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            display: flex;
            height: 100vh;
            align-items: center;
            justify-content: center;
        }
        .box {
            background: white;
            padding: 25px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 6px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        input, button {
            width: 100%;
            padding: 8px;
            margin: 6px 0;
            box-sizing: border-box;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
        .link {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="box">
        <form method="POST">
            <h2>Login</h2>
            <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
            <input name="username" placeholder="Username" required>
            <input name="password" type="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="link">
            <a href="register.php">No account? Register</a>
        </div>
    </div>
</body>
</html>