<?php
session_start();

// Simple Products List
$products = [
  1 => ["name" => "T-shirt", "price" => 20],
  2 => ["name" => "Sneakers", "price" => 50],
  3 => ["name" => "Backpack", "price" => 30]
];

// Handle Add One
if (isset($_GET['add'])) {
  $id = $_GET['add'];
  $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
  header("Location: ?");
  exit();
}

// Handle Remove One
if (isset($_GET['removeOne'])) {
  $id = $_GET['removeOne'];
  if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]--;
    if ($_SESSION['cart'][$id] <= 0) {
      unset($_SESSION['cart'][$id]);
    }
  }
  header("Location: ?");
  exit();
}

// Handle Remove Entire Item
if (isset($_GET['removeAll'])) {
  $id = $_GET['removeAll'];
  unset($_SESSION['cart'][$id]);
  header("Location: ?");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Simple Shop</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f9f9f9;
      padding: 20px;
    }
    h1, h2 {
      color: #333;
    }
    .products, .cart {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }
    .card {
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 15px;
      width: 200px;
    }
    .btn {
      padding: 6px 12px;
      text-decoration: none;
      color: #fff;
      background: #28a745;
      border-radius: 4px;
      font-size: 14px;
      margin-right: 5px;
    }
    .btn-warning {
      background: #ffc107;
      color: #000;
    }
    .btn-danger {
      background: #dc3545;
    }
    .btn-sm {
      font-size: 12px;
      padding: 4px 8px;
    }
    ul {
      list-style: none;
      padding: 0;
    }
    li {
      background: #fff;
      padding: 10px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .alert {
      background: #e7f3fe;
      padding: 10px;
      border: 1px solid #b3d8ff;
      color: #31708f;
      border-radius: 5px;
    }
  </style>
</head>
<body>

  <h1>🛍️ Simple Shop</h1>

  <h2>Products</h2>
  <div class="products">
    <?php foreach ($products as $id => $p): ?>
      <div class="card">
        <h3><?php echo $p['name']; ?></h3>
        <p>Price: $<?php echo $p['price']; ?></p>
        <a href="?add=<?php echo $id; ?>" class="btn">Add to Cart</a>
      </div>
    <?php endforeach; ?>
  </div>

  <hr>

  <h2>🛒 Your Cart</h2>
  <?php if (!empty($_SESSION['cart'])): ?>
    <ul class="cart">
      <?php $total = 0; ?>
      <?php foreach ($_SESSION['cart'] as $id => $qty): ?>
        <li>
          <span><?php echo $products[$id]['name']; ?> (x<?php echo $qty; ?>)</span>
          <div>
            <a href="?add=<?php echo $id; ?>" class="btn btn-sm">+</a>
            <a href="?removeOne=<?php echo $id; ?>" class="btn btn-warning btn-sm">-</a>
            <a href="?removeAll=<?php echo $id; ?>" class="btn btn-danger btn-sm">Remove All</a>
            <strong>$<?php echo $products[$id]['price'] * $qty; ?></strong>
          </div>
        </li>
        <?php $total += $products[$id]['price'] * $qty; ?>
      <?php endforeach; ?>
    </ul>
    <h3>Total: $<?php echo $total; ?></h3>
  <?php else: ?>
    <div class="alert">Your cart is empty.</div>
  <?php endif; ?>

</body>
</html>
