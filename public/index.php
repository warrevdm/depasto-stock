<?php
require_once __DIR__ . '/../includes/db.php';

$stmt = $pdo->query("SELECT COUNT(*) AS total_products FROM products");
$result = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>De Pasto Stock</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <main class="container">
        <h1>De Pasto Stocksysteem</h1>
        <p class="success">Databaseverbinding werkt.</p>
        <p>Aantal producten: <?= htmlspecialchars($result['total_products']) ?></p>

        <a class="button" href="dashboard.php">Naar dashboard</a>
    </main>
</body>
</html>