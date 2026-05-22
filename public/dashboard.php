<?php
require_once __DIR__ . '/../includes/db.php';

$totalProducts = $pdo->query("SELECT COUNT(*) AS total FROM products WHERE active = 1")->fetch()['total'];

$lowStock = $pdo->query("
    SELECT COUNT(*) AS total
    FROM products p
    LEFT JOIN stock_movements sm ON p.id = sm.product_id
    WHERE p.active = 1
    GROUP BY p.id, p.min_stock
    HAVING COALESCE(SUM(sm.quantity), 0) < p.min_stock
")->fetchAll();

$lowStockCount = count($lowStock);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - De Pasto Stock</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <main class="container">
        <h1>Dashboard</h1>

        <div class="cards">
            <div class="card">
                <span class="card-label">Producten</span>
                <strong><?= htmlspecialchars($totalProducts) ?></strong>
            </div>

            <div class="card">
                <span class="card-label">Onder minimumstock</span>
                <strong><?= htmlspecialchars($lowStockCount) ?></strong>
            </div>
        </div>

        <nav class="menu">
            <a href="voorraad.php">Voorraad bekijken</a>
            <a href="producten.php">Producten beheren</a>
            <a href="levering.php">Levering ingeven</a>
            <a href="telling.php">Stocktelling</a>
            <a href="import-verkoop.php">Verkoop importeren</a>
        </nav>
    </main>
</body>
</html>