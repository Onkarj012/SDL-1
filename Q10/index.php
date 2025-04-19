<?php
// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "pharmacy";

// Create DB if not exists
$conn = new mysqli($host, $user, $pass);
$conn->query("CREATE DATABASE IF NOT EXISTS $db");
$conn->select_db($db);

// Create medicines table
$conn->query("CREATE TABLE IF NOT EXISTS medicines (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  company VARCHAR(100),
  quantity INT,
  price FLOAT,
  expiry_date DATE
)");

// Handle form submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'];
  $company = $_POST['company'];
  $quantity = $_POST['quantity'];
  $price = $_POST['price'];
  $expiry = $_POST['expiry'];

  $stmt = $conn->prepare("INSERT INTO medicines (name, company, quantity, price, expiry_date) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("ssids", $name, $company, $quantity, $price, $expiry);
  $stmt->execute();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Pharmacy Management</title>
  <style>
    body { font-family: Arial, sans-serif; padding: 30px; background: #f9f9f9; }
    h2 { color: #2c3e50; }
    form, table { background: #fff; padding: 20px; border-radius: 8px; margin-bottom: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    input, button { padding: 10px; margin: 8px 0; width: 100%; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
    th { background: #2c3e50; color: white; }
  </style>
</head>
<body>

  <h2>Add New Medicine</h2>
  <form method="POST">
    <input type="text" name="name" placeholder="Medicine Name" required>
    <input type="text" name="company" placeholder="Company Name" required>
    <input type="number" name="quantity" placeholder="Quantity" required>
    <input type="number" step="0.01" name="price" placeholder="Price" required>
    <input type="date" name="expiry" required>
    <button type="submit">Add Medicine</button>
  </form>

  <h2>Medicine Inventory</h2>
  <table>
    <tr>
      <th>ID</th>
      <th>Medicine Name</th>
      <th>Company</th>
      <th>Quantity</th>
      <th>Price</th>
      <th>Expiry Date</th>
    </tr>
    <?php
      $result = $conn->query("SELECT * FROM medicines ORDER BY id DESC");
      while ($row = $result->fetch_assoc()) {
        echo "<tr>
          <td>{$row['id']}</td>
          <td>{$row['name']}</td>
          <td>{$row['company']}</td>
          <td>{$row['quantity']}</td>
          <td>₹{$row['price']}</td>
          <td>{$row['expiry_date']}</td>
        </tr>";
      }
    ?>
  </table>

</body>
</html>
