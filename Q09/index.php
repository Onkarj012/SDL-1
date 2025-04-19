<?php
// DB Connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "tolltax";

// Create DB
$conn = new mysqli($host, $user, $pass);
$conn->query("CREATE DATABASE IF NOT EXISTS $db");
$conn->select_db($db);

// Create Table
$conn->query("CREATE TABLE IF NOT EXISTS toll_entries (
  id INT AUTO_INCREMENT PRIMARY KEY,
  vehicle_no VARCHAR(20),
  vehicle_type VARCHAR(20),
  toll_fee FLOAT,
  entry_time DATETIME
)");

// Toll rates
$toll_rates = [
  "Bike" => 20,
  "Car" => 50,
  "Truck" => 100,
  "Bus" => 80
];

// Handle Form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $vehicle_no = $_POST["vehicle_no"];
  $vehicle_type = $_POST["vehicle_type"];
  $toll_fee = $toll_rates[$vehicle_type];
  $entry_time = date("Y-m-d H:i:s");

  $stmt = $conn->prepare("INSERT INTO toll_entries (vehicle_no, vehicle_type, toll_fee, entry_time) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("ssds", $vehicle_no, $vehicle_type, $toll_fee, $entry_time);
  $stmt->execute();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Toll Tax Management</title>
  <style>
    body { font-family: Arial; padding: 30px; background: #f2f2f2; }
    form, table { background: white; padding: 20px; border-radius: 8px; margin-bottom: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    input, select, button { padding: 10px; margin: 8px 0; width: 100%; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
    th { background-color: #333; color: white; }
  </style>
</head>
<body>

  <h2>Add Vehicle Entry</h2>
  <form method="POST">
    <input type="text" name="vehicle_no" placeholder="Vehicle Number" required>
    <select name="vehicle_type" required>
      <option value="">Select Vehicle Type</option>
      <option value="Bike">Bike - ₹20</option>
      <option value="Car">Car - ₹50</option>
      <option value="Truck">Truck - ₹100</option>
      <option value="Bus">Bus - ₹80</option>
    </select>
    <button type="submit">Submit Entry</button>
  </form>

  <h2>All Toll Entries</h2>
  <table>
    <tr>
      <th>ID</th>
      <th>Vehicle No</th>
      <th>Type</th>
      <th>Toll Fee</th>
      <th>Date & Time</th>
    </tr>
    <?php
      $res = $conn->query("SELECT * FROM toll_entries ORDER BY id DESC");
      while ($row = $res->fetch_assoc()) {
        echo "<tr>
          <td>{$row['id']}</td>
          <td>{$row['vehicle_no']}</td>
          <td>{$row['vehicle_type']}</td>
          <td>₹{$row['toll_fee']}</td>
          <td>{$row['entry_time']}</td>
        </tr>";
      }
    ?>
  </table>

</body>
</html>