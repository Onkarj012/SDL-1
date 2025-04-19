<?php
// Database Connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "college_admission";

// Create DB if not exists
$conn = new mysqli($host, $user, $pass);
$conn->query("CREATE DATABASE IF NOT EXISTS $db");
$conn->select_db($db);

// Create table if not exists
$conn->query("CREATE TABLE IF NOT EXISTS students (
  id INT AUTO_INCREMENT PRIMARY KEY,
  firstname VARCHAR(100),
  lastname VARCHAR(100),
  email VARCHAR(100),
  course VARCHAR(100),
  dob DATE
)");

// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $fname = $_POST['firstname'];
  $lname = $_POST['lastname'];
  $email = $_POST['email'];
  $course = $_POST['course'];
  $dob = $_POST['dob'];

  $stmt = $conn->prepare("INSERT INTO students (firstname, lastname, email, course, dob) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssss", $fname, $lname, $email, $course, $dob);
  $stmt->execute();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>College Admission System</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
    h2 { color: teal; }
    form { background: #fff; padding: 20px; border-radius: 8px; width: 350px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    input, select, button { width: 100%; padding: 10px; margin: 10px 0; }
    table { width: 100%; border-collapse: collapse; margin-top: 40px; }
    th, td { padding: 10px; border: 1px solid #ddd; text-align: left; background: #fff; }
    th { background-color: teal; color: white; }
  </style>
</head>
<body>

  <h2>College Admission Form</h2>
  <form method="POST">
    <input type="text" name="firstname" placeholder="First Name" required />
    <input type="text" name="lastname" placeholder="Last Name" required />
    <input type="email" name="email" placeholder="Email" required />
    <select name="course" required>
      <option value="">Select Course</option>
      <option>B.Tech</option>
      <option>B.Sc</option>
      <option>B.Com</option>
    </select>
    <input type="date" name="dob" required />
    <button type="submit">Register</button>
  </form>

  <h2>Registered Students</h2>
  <table>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Course</th>
      <th>DOB</th>
    </tr>
    <?php
      $res = $conn->query("SELECT * FROM students ORDER BY id DESC");
      while ($row = $res->fetch_assoc()) {
        echo "<tr>
          <td>{$row['id']}</td>
          <td>{$row['firstname']} {$row['lastname']}</td>
          <td>{$row['email']}</td>
          <td>{$row['course']}</td>
          <td>{$row['dob']}</td>
        </tr>";
      }
    ?>
  </table>

</body>
</html>