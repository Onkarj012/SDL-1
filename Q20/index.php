<!-- 20. Create and store the cookies. -->

<?php
// If the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username']; // Get input from form
  // Set cookie for 1 minute (60 seconds)
  setcookie("user", $username, time() + 60, "/");
  header("Location: " . $_SERVER['PHP_SELF']); // Refresh to read the cookie
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Store User Input in Cookie</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      padding: 30px;
    }
    h2 {
      color: #333;
    }
    form {
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      max-width: 400px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    input[type="text"] {
      width: 100%;
      padding: 10px;
      margin-top: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    button {
      background: #007bff;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 4px;
      cursor: pointer;
    }
    button:hover {
      background: #0056b3;
    }
    .message {
      margin-top: 20px;
      padding: 15px;
      border-radius: 6px;
    }
    .success {
      background: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }
    .info {
      background: #e2e3e5;
      color: #383d41;
      border: 1px solid #d6d8db;
    }
  </style>
</head>
<body>

  <h2>Enter Your Name 🌟</h2>

  <!-- Form to get user input -->
  <form method="POST" action="">
    <label for="username">Your Name:</label>
    <input type="text" name="username" id="username" placeholder="Enter your name" required>
    <button type="submit">Save to Cookie</button>
  </form>

  <hr>

  <!-- Display cookie if it exists -->
  <?php
  if (isset($_COOKIE["user"])) {
    echo '<div class="message success">Hello, ' . htmlspecialchars($_COOKIE["user"]) . '!</div>';
  } else {
    echo '<div class="message info">No cookie set yet.</div>';
  }
  ?>

</body>
</html>