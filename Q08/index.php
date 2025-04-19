<?php
// DB Connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "complaint_db";

// Create and connect DB
$conn = new mysqli($host, $user, $pass);
$conn->query("CREATE DATABASE IF NOT EXISTS $db");
$conn->select_db($db);

// Create table
$conn->query("CREATE TABLE IF NOT EXISTS complaints (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  email VARCHAR(100),
  subject VARCHAR(200),
  message TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST["name"];
  $email = $_POST["email"];
  $subject = $_POST["subject"];
  $message = $_POST["message"];

  $stmt = $conn->prepare("INSERT INTO complaints (name, email, subject, message) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("ssss", $name, $email, $subject, $message);
  $stmt->execute();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Complaint Management System</title>
  <style>
    body { font-family: Arial, sans-serif; padding: 30px; background: #f7f7f7; }
    form, table { background: #fff; padding: 20px; margin-bottom: 30px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    input, textarea, button { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
    th { background-color: #333; color: #fff; }
    h2 { color: #333; }
  </style>
</head>
<body>

  <h2>Submit a Complaint</h2>
  <form method="POST">
    <input type="text" name="name" placeholder="Your Name" required>
    <input type="email" name="email" placeholder="Your Email" required>
    <input type="text" name="subject" placeholder="Subject" required>
    <textarea name="message" rows="4" placeholder="Describe your complaint..." required></textarea>
    <button type="submit">Submit Complaint</button>
  </form>

  <h2>All Complaints</h2>
  <table>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Subject</th>
      <th>Message</th>
      <th>Date</th>
    </tr>
    <?php
      $result = $conn->query("SELECT * FROM complaints ORDER BY id DESC");
      while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>" . htmlspecialchars($row['name']) . "</td>
                <td>{$row['email']}</td>
                <td>" . htmlspecialchars($row['subject']) . "</td>
                <td>" . nl2br(htmlspecialchars($row['message'])) . "</td>
                <td>{$row['created_at']}</td>
              </tr>";
      }
    ?>
  </table>

</body>
</html>
