<?php
require_once __DIR__ . '/../includes/db.php';

$stmt = $pdo->query("
    SELECT 
        p.id,
        p.name,
        p.category,
        p.unit,
        p.min_stock,
        p.target_stock,
        COALESCE(SUM(sm.quantity), 0) AS current_stock
    FROM products p
    LEFT JOIN stock_movements sm ON p.id = sm.product_id
    WHERE p.active = 1
    GROUP BY 
        p.id,
        p.name,
        p.category,
        p.unit,
        p.min_stock,
        p.target_stock
    ORDER BY p.category, p.name
");

$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Voorraad - De Pasto Stock</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <main class="container">
        <a class="back-link" href="dashboard.php">← Terug naar dashboard</a>

        <h1>Voorraad</h1>

        <table class="stock-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Categorie</th>
                    <th>Huidige stock</th>
                    <th>Minimum</th>
                    <th>Gewenst</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <?php
                        $current = (float) $product['current_stock'];
                        $min = (float) $product['min_stock'];
                        $target = (float) $product['target_stock'];

                        if ($current < $min) {
                            $status = 'Bestellen';
                            $statusClass = 'status-red';
                        } elseif ($current < ($min * 1.25)) {
                            $status = 'Bijna minimum';
                            $statusClass = 'status-orange';
                        } else {
                            $status = 'OK';
                            $statusClass = 'status-green';
                        }
                    ?>

                    <tr>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= htmlspecialchars($product['category']) ?></td>
                        <td>
                            <?= htmlspecialchars(number_format($current, 2, ',', '.')) ?>
                            <?= htmlspecialchars($product['unit']) ?>
                        </td>
                        <td><?= htmlspecialchars(number_format($min, 2, ',', '.')) ?></td>
                        <td><?= htmlspecialchars(number_format($target, 2, ',', '.')) ?></td>
                        <td>
                            <span class="status <?= $statusClass ?>">
                                <?= htmlspecialchars($status) ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>