<?php
require_once __DIR__ . '/../includes/db.php';

$errors = [];
$success = null;
$selectedCategory = $_GET['category'] ?? ($_POST['category'] ?? '');

function parseDecimalValue($value): ?float
{
    $value = str_replace(',', '.', trim((string) $value));

    if ($value === '') {
        return null;
    }

    return (float) $value;
}

function formatStockWithPackaging(float $stock, float $packSize, string $unit): string
{
    if ($packSize > 1) {
        $fullPacks = floor($stock / $packSize);
        $looseUnits = $stock - ($fullPacks * $packSize);

        return number_format($fullPacks, 0, ',', '.') . ' verp. + ' .
            number_format($looseUnits, 2, ',', '.') . ' ' . $unit;
    }

    return number_format($stock, 2, ',', '.') . ' ' . $unit;
}

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
    $packCounts = $_POST['pack_counts'] ?? [];
    $looseCounts = $_POST['loose_counts'] ?? [];
    $directCounts = $_POST['direct_counts'] ?? [];
    $expectedStocks = $_POST['expected_stock'] ?? [];
    $packSizes = $_POST['pack_size'] ?? [];

    if (!$movementDate) {
        $errors[] = 'Kies een datum.';
    }

    $corrections = [];

    foreach ($expectedStocks as $productId => $expectedStockValue) {
        $productId = (int) $productId;
        $expectedStock = (float) $expectedStockValue;
        $packSize = isset($packSizes[$productId]) ? (float) $packSizes[$productId] : 1;
        $packSize = $packSize > 0 ? $packSize : 1;

        if ($packSize > 1) {
            $packCount = parseDecimalValue($packCounts[$productId] ?? '');
            $looseCount = parseDecimalValue($looseCounts[$productId] ?? '');

            if ($packCount === null && $looseCount === null) {
                continue;
            }

            $countedStock = (($packCount ?? 0) * $packSize) + ($looseCount ?? 0);
        } else {
            $directCount = parseDecimalValue($directCounts[$productId] ?? '');

            if ($directCount === null) {
                continue;
            }

            $countedStock = $directCount;
        }

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
        p.pack_size,
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
    GROUP BY p.id, p.name, p.category, p.unit, p.pack_size, p.min_stock, p.target_stock
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
        <p class="muted">Tel producten met een verpakkingsgrootte per volle bak/doos/verpakking en losse stuks. Voorbeeld: Duvel met verpakkingsgrootte 24 → 1 volle bak + 5 losse stuks = 29 stuks.</p>

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

                    <table class="stock-table count-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Categorie</th>
                                <th>Verwacht</th>
                                <th>Volle verpakkingen</th>
                                <th>Losse stuks</th>
                                <th>Eenheid</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <?php
                                    $currentStock = (float) $product['current_stock'];
                                    $packSize = (float) $product['pack_size'];
                                    $packSize = $packSize > 0 ? $packSize : 1;
                                ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($product['name']) ?></strong>
                                        <?php if ($packSize > 1): ?>
                                            <span class="small-note">1 verpakking = <?= htmlspecialchars(number_format($packSize, 0, ',', '.')) ?> <?= htmlspecialchars($product['unit']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($product['category']) ?></td>
                                    <td>
                                        <?= htmlspecialchars(formatStockWithPackaging($currentStock, $packSize, $product['unit'])) ?>
                                        <input type="hidden" name="expected_stock[<?= htmlspecialchars($product['id']) ?>]" value="<?= htmlspecialchars((string) $currentStock) ?>">
                                        <input type="hidden" name="pack_size[<?= htmlspecialchars($product['id']) ?>]" value="<?= htmlspecialchars((string) $packSize) ?>">
                                    </td>

                                    <?php if ($packSize > 1): ?>
                                        <td>
                                            <input
                                                type="number"
                                                step="1"
                                                min="0"
                                                name="pack_counts[<?= htmlspecialchars($product['id']) ?>]"
                                                placeholder="0"
                                                class="count-input"
                                            >
                                        </td>
                                        <td>
                                            <input
                                                type="number"
                                                step="0.01"
                                                min="0"
                                                name="loose_counts[<?= htmlspecialchars($product['id']) ?>]"
                                                placeholder="0"
                                                class="count-input"
                                            >
                                        </td>
                                    <?php else: ?>
                                        <td colspan="2">
                                            <input
                                                type="number"
                                                step="0.01"
                                                min="0"
                                                name="direct_counts[<?= htmlspecialchars($product['id']) ?>]"
                                                placeholder="Geteld totaal"
                                                class="count-input"
                                            >
                                        </td>
                                    <?php endif; ?>

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
