<?php
require_once __DIR__ . '/../includes/db.php';

$errors = [];
$success = null;

$suppliersStmt = $pdo->query("SELECT id, name FROM suppliers WHERE active = 1 ORDER BY name");
$suppliers = $suppliersStmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $supplierId = $_POST['supplier_id'] !== '' ? (int) $_POST['supplier_id'] : null;
    $unit = trim($_POST['unit'] ?? 'stuk');
    $packSize = (float) ($_POST['pack_size'] ?? 1);
    $minStock = (float) ($_POST['min_stock'] ?? 0);
    $targetStock = (float) ($_POST['target_stock'] ?? 0);
    $purchasePrice = (float) ($_POST['purchase_price'] ?? 0);
    $posCode = trim($_POST['pos_code'] ?? '');

    if ($name === '') {
        $errors[] = 'Productnaam is verplicht.';
    }

    if ($packSize <= 0) {
        $errors[] = 'Verpakkingsgrootte moet groter zijn dan 0.';
    }

    if ($targetStock < $minStock) {
        $errors[] = 'Gewenste stock mag niet lager zijn dan minimumstock.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare("
            INSERT INTO products
            (name, category, supplier_id, unit, pack_size, min_stock, target_stock, purchase_price, pos_code, active)
            VALUES
            (:name, :category, :supplier_id, :unit, :pack_size, :min_stock, :target_stock, :purchase_price, :pos_code, 1)
        ");

        $stmt->execute([
            ':name' => $name,
            ':category' => $category,
            ':supplier_id' => $supplierId,
            ':unit' => $unit,
            ':pack_size' => $packSize,
            ':min_stock' => $minStock,
            ':target_stock' => $targetStock,
            ':purchase_price' => $purchasePrice,
            ':pos_code' => $posCode,
        ]);

        $success = 'Product toegevoegd.';
    }
}

$productsStmt = $pdo->query("
    SELECT 
        p.id,
        p.name,
        p.category,
        p.unit,
        p.pack_size,
        p.min_stock,
        p.target_stock,
        p.purchase_price,
        p.pos_code,
        s.name AS supplier_name
    FROM products p
    LEFT JOIN suppliers s ON p.supplier_id = s.id
    WHERE p.active = 1
    ORDER BY p.category, p.name
");
$products = $productsStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Producten - De Pasto Stock</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <main class="container">
        <a class="back-link" href="dashboard.php">← Terug naar dashboard</a>

        <h1>Producten beheren</h1>

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
            <h2>Nieuw product toevoegen</h2>

            <form method="post" class="form-grid">
                <label>
                    Productnaam
                    <input type="text" name="name" required placeholder="Bijv. Coca-Cola 20cl">
                </label>

                <label>
                    Categorie
                    <input type="text" name="category" placeholder="Bijv. Frisdrank">
                </label>

                <label>
                    Leverancier
                    <select name="supplier_id">
                        <option value="">Geen leverancier</option>
                        <?php foreach ($suppliers as $supplier): ?>
                            <option value="<?= htmlspecialchars($supplier['id']) ?>">
                                <?= htmlspecialchars($supplier['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>

                <label>
                    Eenheid
                    <select name="unit">
                        <option value="stuk">stuk</option>
                        <option value="bak">bak</option>
                        <option value="kg">kg</option>
                        <option value="liter">liter</option>
                        <option value="portie">portie</option>
                        <option value="doos">doos</option>
                    </select>
                </label>

                <label>
                    Verpakkingsgrootte
                    <input type="number" step="0.01" name="pack_size" value="1" required>
                </label>

                <label>
                    Minimumstock
                    <input type="number" step="0.01" name="min_stock" value="0">
                </label>

                <label>
                    Gewenste stock
                    <input type="number" step="0.01" name="target_stock" value="0">
                </label>

                <label>
                    Aankoopprijs
                    <input type="number" step="0.01" name="purchase_price" value="0">
                </label>

                <label>
                    Kassa-code / POS-code
                    <input type="text" name="pos_code" placeholder="Bijv. cola_zero">
                </label>

                <button type="submit" class="button form-button">Product toevoegen</button>
            </form>
        </section>

        <section class="panel">
            <h2>Actieve producten</h2>

            <table class="stock-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Categorie</th>
                        <th>Leverancier</th>
                        <th>Eenheid</th>
                        <th>Verpakking</th>
                        <th>Min.</th>
                        <th>Gewenst</th>
                        <th>Aankoop</th>
                        <th>POS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= htmlspecialchars($product['name']) ?></td>
                            <td><?= htmlspecialchars($product['category']) ?></td>
                            <td><?= htmlspecialchars($product['supplier_name'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($product['unit']) ?></td>
                            <td><?= htmlspecialchars(number_format((float) $product['pack_size'], 2, ',', '.')) ?></td>
                            <td><?= htmlspecialchars(number_format((float) $product['min_stock'], 2, ',', '.')) ?></td>
                            <td><?= htmlspecialchars(number_format((float) $product['target_stock'], 2, ',', '.')) ?></td>
                            <td>€ <?= htmlspecialchars(number_format((float) $product['purchase_price'], 2, ',', '.')) ?></td>
                            <td><?= htmlspecialchars($product['pos_code'] ?? '') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
