<?php

    $conn = new mysqli('localhost', 'root', '');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $conn->query("CREATE DATABASE IF NOT EXISTS book_store");

    $conn->select_db('book_store');

    $conn->query("CREATE TABLE IF NOT EXISTS books (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        author VARCHAR(255) NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        description TEXT,
        borrowed_by_name VARCHAR(255) DEFAULT NULL
    )");

    if (isset($_POST['add_book'])) {
        $title = $_POST['title'];
        $author = $_POST['author'];
        $price = $_POST['price'];
        $description = $_POST['description'];

        $stmt = $conn->prepare("INSERT INTO books (title, author, price, description) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $title, $author, $price, $description);
        $stmt->execute();
        header('Location: catalogue.php');
        exit();
    }

    if (isset($_GET['delete'])) {
        $book_id = $_GET['delete'];
        $conn->query("DELETE FROM books WHERE id = $book_id");
        header('Location: catalogue.php');
        exit();
    }

    if (isset($_POST['borrow_book'])) {
        $book_id = $_POST['book_id'];
        $borrower_name = $_POST['borrower_name'];

        $stmt = $conn->prepare("UPDATE books SET borrowed_by_name = ? WHERE id = ?");
        $stmt->bind_param("si", $borrower_name, $book_id);
        $stmt->execute();
        header('Location: catalogue.php');
        exit();
    }

    if (isset($_GET['return'])) {
        $book_id = $_GET['return'];
        $conn->query("UPDATE books SET borrowed_by_name = NULL WHERE id = $book_id");
        header('Location: catalogue.php');
        exit();
    }

    $result = $conn->query("SELECT * FROM books");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Books Catalogue</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f4f4f4;
        }

        h1 {
            text-align: center;
        }

        form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        form input, form button {
            padding: 8px;
            font-size: 14px;
            flex: 1;
        }

        .books {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .book {
            background: white;
            border: 1px solid #ddd;
            padding: 10px;
            width: 280px;
        }

        .book h3 {
            margin: 0 0 5px;
        }

        .actions {
            margin-top: 10px;
        }

        .btn {
            padding: 5px 10px;
            font-size: 13px;
            border: none;
            border-radius: 4px;
        }

        .btn-borrow { background-color: #28a745; color: white; }
        .btn-return { background-color: #ffc107; color: black; }
        .btn-delete { background-color: #dc3545; color: white; }
        .btn-add { background-color: #007bff; color: white; }
    </style>
</head>
<body>

    <h1>📚 Books Catalogue</h1>

    <h3>Add New Book</h3>
    <form method="POST">
        <input type="text" name="title" placeholder="Title" required>
        <input type="text" name="author" placeholder="Author" required>
        <input type="number" step="0.01" name="price" placeholder="Price" required>
        <input type="text" name="description" placeholder="Description">
        <button type="submit" name="add_book" class="btn btn-add">Add Book</button>
    </form>

    <div class="books">
        <?php while($book = $result->fetch_assoc()): ?>
        <div class="book">
            <h3><?php echo $book['title']; ?></h3>
            <p>Author: <?php echo $book['author']; ?></p>
            <p>Description: <?php echo $book['description']; ?></p>
            <p>Price: $<?php echo $book['price']; ?></p>

            <?php if ($book['borrowed_by_name']): ?>
                <p class="borrowed">Borrowed by: <?php echo $book['borrowed_by_name']; ?></p>
                <a href="catalogue.php?return=<?php echo $book['id']; ?>" class="btn btn-return">Return Book</a>
            <?php else: ?>
                <p class="available">Available</p>
                <form method="POST">
                    <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                    <input type="text" name="borrower_name" placeholder="Name" required>
                    <button type="submit" name="borrow_book" class="btn btn-borrow">Borrow</button>
                </form>
            <?php endif; ?>

            <a href="catalogue.php?delete=<?php echo $book['id']; ?>" class="btn btn-delete" onclick="return confirm('Delete this book?')">Delete</a>
        </div>
        <?php endwhile; ?>
    </div>


</body>
</html>
