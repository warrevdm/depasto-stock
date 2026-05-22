<?php
require_once __DIR__ . '/../includes/db.php';

$errors = [];
$success = null;
$selectedCategory = $_GET['category'] ?? ($_POST['category'] ?? '');

$categoriesStmt = $pdo->query("
    SELECT DISTINCT category
    FROM products
    WHERE active = 1
      AND category IS NOT NULL
      AND category <> ''
    ORDER BY category
");
$categories = $categoriesStmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movementDate = $_POST['movement_date'] ?? date('Y-m-d');
    $reference = trim($_POST['reference'] ?? 'Stocktelling');
    $counts = $_POST['counts'] ?? [];
    $expectedStocks = $_POST['expected_stock'] ?? [];

    if (!$movementDate) {
        $errors[] = 'Kies een datum.';
    }

    $corrections = [];

    foreach ($counts as $productId => $countValue) {
        $productId = (int) $productId;
        $countValue = str_replace(',', '.', trim((string) $countValue));

        if ($countValue === '') {
            continue;
        }

        $countedStock = (float) $countValue;
        $expectedStock = isset($expectedStocks[$productId]) ? (float) $expectedStocks[$productId] : 0;
        $difference = $countedStock - $expectedStock;

        if (abs($difference) > 0.0001) {
            $corrections[] = [
                'product_id' => $productId,
                'quantity' => $difference,
            ];
        }
    }

    if (!$corrections) {
        $errors[] = 'Er zijn geen verschillen om te corrigeren. Vul minstens één telling in die afwijkt van de huidige stock.';
    }

    if (!$errors) {
        $pdo->beginTransaction();

        try {
            $stmt = $pdo->prepare("
                INSERT INTO stock_movements
                (product_id, movement_type, quantity, reference, source, movement_date, created_by)
                VALUES
                (:product_id, 'count', :quantity, :reference, 'stock_count', :movement_date, 1)
            ");

            foreach ($corrections as $correction) {
                $stmt->execute([
                    ':product_id' => $correction['product_id'],
                    ':quantity' => $correction['quantity'],
                    ':reference' => $reference,
                    ':movement_date' => $movementDate,
                ]);
            }

            $pdo->commit();
            $success = count($corrections) . ' stockcorrectie(s) opgeslagen.';
        } catch (Exception $e) {
            $pdo->rollBack();
            $errors[] = 'Stocktelling kon niet worden opgeslagen: ' . $e->getMessage();
        }
    }
}

$sql = "
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
";

$params = [];

if ($selectedCategory !== '') {
    $sql .= " AND p.category = :category";
    $params[':category'] = $selectedCategory;
}

$sql .= "
    GROUP BY p.id, p.name, p.category, p.unit, p.min_stock, p.target_stock
    ORDER BY p.category, p.name
";

$productsStmt = $pdo->prepare($sql);
$productsStmt->execute($params);
$products = $productsStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Stocktelling - De Pasto Stock</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <main class="container">
        <a class="back-link" href="dashboard.php">← Terug naar dashboard</a>

        <h1>Stocktelling</h1>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if ($errors): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <section class="panel">
            <h2>1. Kies categorie</h2>

            <form method="get" class="form-grid">
                <label>
                    Categorie
                    <select name="category">
                        <option value="">Alle categorieën</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= htmlspecialchars($category['category']) ?>" <?= $selectedCategory === $category['category'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['category']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>

                <button type="submit" class="button form-button">Producten tonen</button>
            </form>
        </section>

        <section class="panel">
            <h2>2. Vul getelde stock in</h2>

            <?php if (!$products): ?>
                <p>Geen actieve producten gevonden.</p>
            <?php else: ?>
                <form method="post">
                    <input type="hidden" name="category" value="<?= htmlspecialchars($selectedCategory) ?>">

                    <div class="form-grid">
                        <label>
                            Datum telling
                            <input type="date" name="movement_date" value="<?= htmlspecialchars(date('Y-m-d')) ?>" required>
                        </label>

                        <label>
                            Referentie
                            <input type="text" name="reference" value="Stocktelling" placeholder="Bijv. Stocktelling bar">
                        </label>
                    </div>

                    <table class="stock-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Categorie</th>
                                <th>Verwacht</th>
                                <th>Geteld</th>
                                <th>Eenheid</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <?php $currentStock = (float) $product['current_stock']; ?>
                                <tr>
                                    <td><?= htmlspecialchars($product['name']) ?></td>
                                    <td><?= htmlspecialchars($product['category']) ?></td>
                                    <td>
                                        <?= htmlspecialchars(number_format($currentStock, 2, ',', '.')) ?>
                                        <input type="hidden" name="expected_stock[<?= htmlspecialchars($product['id']) ?>]" value="<?= htmlspecialchars((string) $currentStock) ?>">
                                    </td>
                                    <td>
                                        <input
                                            type="number"
                                            step="0.01"
                                            min="0"
                                            name="counts[<?= htmlspecialchars($product['id']) ?>]"
                                            placeholder="Geteld"
                                            class="count-input"
                                        >
                                    </td>
                                    <td><?= htmlspecialchars($product['unit']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <button type="submit" class="button">Stocktelling opslaan</button>
                </form>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
