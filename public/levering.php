<?php
require_once __DIR__ . '/../includes/db.php';

$errors = [];
$success = null;
$selectedSupplierId = isset($_GET['supplier_id']) ? (int) $_GET['supplier_id'] : 0;

$suppliersStmt = $pdo->query("SELECT id, name FROM suppliers WHERE active = 1 ORDER BY name");
$suppliers = $suppliersStmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supplierId = (int) ($_POST['supplier_id'] ?? 0);
    $movementDate = $_POST['movement_date'] ?? date('Y-m-d');
    $reference = trim($_POST['reference'] ?? 'Levering');
    $quantities = $_POST['quantities'] ?? [];

    if ($supplierId <= 0) {
        $errors[] = 'Kies een leverancier.';
    }

    if (!$movementDate) {
        $errors[] = 'Kies een datum.';
    }

    $linesToInsert = [];

    foreach ($quantities as $productId => $quantity) {
        $productId = (int) $productId;
        $quantity = str_replace(',', '.', trim((string) $quantity));
        $quantity = (float) $quantity;

        if ($quantity > 0) {
            $linesToInsert[] = [
                'product_id' => $productId,
                'quantity' => $quantity,
            ];
        }
    }

    if (!$linesToInsert) {
        $errors[] = 'Vul minstens één geleverd aantal in.';
    }

    if (!$errors) {
        $pdo->beginTransaction();

        try {
            $stmt = $pdo->prepare("
                INSERT INTO stock_movements
                (product_id, movement_type, quantity, reference, source, movement_date, created_by)
                VALUES
                (:product_id, 'delivery', :quantity, :reference, 'manual_delivery', :movement_date, 1)
            ");

            foreach ($linesToInsert as $line) {
                $stmt->execute([
                    ':product_id' => $line['product_id'],
                    ':quantity' => $line['quantity'],
                    ':reference' => $reference,
                    ':movement_date' => $movementDate,
                ]);
            }

            $pdo->commit();
            $success = count($linesToInsert) . ' leveringslijnen toegevoegd.';
            $selectedSupplierId = $supplierId;
        } catch (Exception $e) {
            $pdo->rollBack();
            $errors[] = 'Levering kon niet worden opgeslagen: ' . $e->getMessage();
        }
    } else {
        $selectedSupplierId = $supplierId;
    }
}

$products = [];

if ($selectedSupplierId > 0) {
    $productsStmt = $pdo->prepare("
        SELECT 
            p.id,
            p.name,
            p.category,
            p.unit,
            p.pack_size,
            COALESCE(SUM(sm.quantity), 0) AS current_stock
        FROM products p
        LEFT JOIN stock_movements sm ON p.id = sm.product_id
        WHERE p.active = 1
          AND p.supplier_id = :supplier_id
        GROUP BY p.id, p.name, p.category, p.unit, p.pack_size
        ORDER BY p.category, p.name
    ");

    $productsStmt->execute([':supplier_id' => $selectedSupplierId]);
    $products = $productsStmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Levering ingeven - De Pasto Stock</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <main class="container">
        <a class="back-link" href="dashboard.php">← Terug naar dashboard</a>

        <h1>Levering ingeven</h1>

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
            <h2>1. Kies leverancier</h2>

            <form method="get" class="form-grid">
                <label>
                    Leverancier
                    <select name="supplier_id" required>
                        <option value="">Kies leverancier</option>
                        <?php foreach ($suppliers as $supplier): ?>
                            <option value="<?= htmlspecialchars($supplier['id']) ?>" <?= $selectedSupplierId === (int) $supplier['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($supplier['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>

                <button type="submit" class="button form-button">Producten tonen</button>
            </form>
        </section>

        <?php if ($selectedSupplierId > 0): ?>
            <section class="panel">
                <h2>2. Vul levering in</h2>

                <?php if (!$products): ?>
                    <p>Geen actieve producten gevonden voor deze leverancier.</p>
                <?php else: ?>
                    <form method="post">
                        <input type="hidden" name="supplier_id" value="<?= htmlspecialchars($selectedSupplierId) ?>">

                        <div class="form-grid">
                            <label>
                                Datum levering
                                <input type="date" name="movement_date" value="<?= htmlspecialchars(date('Y-m-d')) ?>" required>
                            </label>

                            <label>
                                Referentie / leverbon
                                <input type="text" name="reference" placeholder="Bijv. Leverbon Coca-Cola" value="Levering">
                            </label>
                        </div>

                        <table class="stock-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Categorie</th>
                                    <th>Huidige stock</th>
                                    <th>Verpakking</th>
                                    <th>Geleverd aantal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($product['name']) ?></td>
                                        <td><?= htmlspecialchars($product['category']) ?></td>
                                        <td>
                                            <?= htmlspecialchars(number_format((float) $product['current_stock'], 2, ',', '.')) ?>
                                            <?= htmlspecialchars($product['unit']) ?>
                                        </td>
                                        <td><?= htmlspecialchars(number_format((float) $product['pack_size'], 2, ',', '.')) ?></td>
                                        <td>
                                            <input
                                                type="number"
                                                step="0.01"
                                                min="0"
                                                name="quantities[<?= htmlspecialchars($product['id']) ?>]"
                                                placeholder="0"
                                            >
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <button type="submit" class="button">Levering opslaan</button>
                    </form>
                <?php endif; ?>
            </section>
        <?php endif; ?>
    </main>
</body>
</html>
