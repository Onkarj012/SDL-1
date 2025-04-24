<?php
session_start();

// 1. CONNECT TO DATABASE AND CREATE DB IF NOT EXISTS
$conn = new mysqli('localhost', 'root', '');
$conn->query("CREATE DATABASE IF NOT EXISTS grocery_store");
$conn->select_db('grocery_store');

// 2. CREATE `products` TABLE IF NOT EXISTS
$conn->query("CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  stock INT DEFAULT 0
)");

// 3. INSERT SAMPLE DATA IF TABLE IS EMPTY
$check = $conn->query("SELECT COUNT(*) AS count FROM products")->fetch_assoc();
if ($check['count'] == 0) {
  $conn->query("INSERT INTO products (name, price, stock) VALUES
    ('Apple', 0.99, 20),
    ('Banana', 0.59, 25),
    ('Carrot', 0.39, 30)
  ");
}

// 4. ADD TO CART
if (isset($_GET['add'])) {
  $id = (int)$_GET['add'];
  $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
  header("Location: ".$_SERVER['PHP_SELF']); exit;
}

// 5. REMOVE FROM CART
if (isset($_GET['remove'])) {
  $id = (int)$_GET['remove'];
  if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]--;
    if ($_SESSION['cart'][$id] <= 0) unset($_SESSION['cart'][$id]);
  }
  header("Location: ".$_SERVER['PHP_SELF']); exit;
}

// 6. CLEAR CART
if (isset($_GET['clear'])) {
  unset($_SESSION['cart']);
  header("Location: ".$_SERVER['PHP_SELF']); exit;
}

// 7. GET ALL PRODUCTS FROM DATABASE
$products = [];
$result = $conn->query("SELECT * FROM products");
while ($row = $result->fetch_assoc()) {
  $products[$row['id']] = $row;
}
$self = htmlspecialchars($_SERVER['PHP_SELF']);
?>

<!-- HTML PART BELOW -->
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Simple Grocery Store</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 20px;
      background: #f0f2f5;
      color: #333;
    }

    h1, h2, h3 {
      color: #2c3e50;
    }

    .products, .cart {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin-top: 20px;
    }

    .card, li {
      background: white;
      border: 1px solid #ddd;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.05);
      padding: 15px;
      width: 220px;
      transition: transform 0.2s ease;
    }

    .card:hover {
      transform: translateY(-4px);
    }

    .btn {
      display: inline-block;
      margin-top: 10px;
      padding: 8px 12px;
      background: #28a745;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      font-weight: bold;
      font-size: 14px;
      transition: background 0.2s ease;
    }

    .btn:hover {
      background: #218838;
    }

    .btn-sm {
      padding: 5px 8px;
      font-size: 12px;
    }

    .btn-danger {
      background: #dc3545;
    }

    .btn-danger:hover {
      background: #c82333;
    }

    .btn-warning {
      background: #ffc107;
      color: black;
    }

    .btn-warning:hover {
      background: #e0a800;
    }

    ul {
      list-style: none;
      padding: 0;
      margin-top: 20px;
    }

    li {
      width: 100%;
      max-width: 600px;
      margin-bottom: 10px;
    }

    .item-actions {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .item-actions strong {
      color: #2c3e50;
    }

    h4, h3 {
      margin-top: 10px;
    }
</style>

</head>
<body>

<h1>🥦 Grocery Store</h1>

<!-- 8. PRODUCT LISTING -->
<h2>Products</h2>
<div class="products">
  <?php foreach ($products as $id => $p): ?>
    <div class="card">
      <strong><?= htmlspecialchars($p['name']) ?></strong><br>
      Price: $<?= $p['price'] ?><br>
      Stock: <?= $p['stock'] ?><br>
      <a href="<?= $self ?>?add=<?= $id ?>" class="btn">Add to Cart</a>
    </div>
  <?php endforeach; ?>
</div>

<hr>

<!-- 9. CART -->
<h2>🛒 Your Cart</h2>
<a href="<?= $self ?>?clear=1" class="btn btn-danger">Clear Cart</a>

<?php if (!empty($_SESSION['cart'])): ?>
  <ul class="cart">
    <?php $total = 0; ?>
    <?php foreach ($_SESSION['cart'] as $id => $qty): ?>
      <?php if (isset($products[$id])): ?>
        <?php
          $p = $products[$id];
          $subtotal = $p['price'] * $qty;
          $total += $subtotal;
        ?>
        <li>
          <strong><?= $p['name'] ?></strong> (x<?= $qty ?>)<br>
          <a href="<?= $self ?>?add=<?= $id ?>" class="btn btn-sm">+</a>
          <a href="<?= $self ?>?remove=<?= $id ?>" class="btn btn-warning btn-sm">-</a>
          &nbsp; <strong>$<?= number_format($subtotal, 2) ?></strong>
        </li>
      <?php endif; ?>
    <?php endforeach; ?>
  </ul>
  <h3>Total: $<?= number_format($total, 2) ?></h3>
<?php else: ?>
  <p>Your cart is empty.</p>
<?php endif; ?>

</body>
</html>
