-- Cleanup dubbele producten
-- Gebruik dit wanneer brouwer_products_seed.sql meerdere keren werd uitgevoerd.
-- Dit script:
-- 1. verplaatst stock_movements/order_lines/pos_mapping van dubbele producten naar het eerste product
-- 2. verwijdert exacte dubbele producten met dezelfde naam
-- 3. zet enkele oorspronkelijke testproducten inactief als de echte brouwersvariant bestaat
-- 4. voegt een unieke index toe op productnaam, zodat exacte dubbels niet opnieuw kunnen ontstaan

START TRANSACTION;

-- 1. Exacte dubbele producten samenvoegen op productnaam
CREATE TEMPORARY TABLE product_keep AS
SELECT
    name,
    MIN(id) AS keep_id
FROM products
GROUP BY name
HAVING COUNT(*) > 1;

-- Stockbewegingen verplaatsen naar het product dat behouden blijft
UPDATE stock_movements sm
JOIN products p ON sm.product_id = p.id
JOIN product_keep pk ON p.name = pk.name
SET sm.product_id = pk.keep_id
WHERE p.id <> pk.keep_id;

-- Bestellijnen verplaatsen naar het product dat behouden blijft
UPDATE order_lines ol
JOIN products p ON ol.product_id = p.id
JOIN product_keep pk ON p.name = pk.name
SET ol.product_id = pk.keep_id
WHERE p.id <> pk.keep_id;

-- POS-mapping verplaatsen naar het product dat behouden blijft
UPDATE pos_mapping pm
JOIN products p ON pm.product_id = p.id
JOIN product_keep pk ON p.name = pk.name
SET pm.product_id = pk.keep_id
WHERE p.id <> pk.keep_id;

-- Dubbele productrecords verwijderen
DELETE p
FROM products p
JOIN product_keep pk ON p.name = pk.name
WHERE p.id <> pk.keep_id;

-- 2. Oorspronkelijke testproducten inactief zetten wanneer de echte brouwersproducten bestaan
UPDATE products
SET active = 0
WHERE name = 'Coca-Cola 20cl'
  AND EXISTS (SELECT 1 FROM (SELECT id FROM products WHERE name = 'COCA COLA 24x20cl') AS x);

UPDATE products
SET active = 0
WHERE name = 'Coca-Cola Zero 20cl'
  AND EXISTS (SELECT 1 FROM (SELECT id FROM products WHERE name = 'COCA COLA ZERO 24x20cl') AS x);

UPDATE products
SET active = 0
WHERE name = 'Fuze Tea Peach'
  AND EXISTS (SELECT 1 FROM (SELECT id FROM products WHERE name IN ('FUZE SPARKLING BLACK TEA 24x20cl', 'FUZE GREEN MANGO CHAMOMILE 24x20cl')) AS x);

UPDATE products
SET active = 0
WHERE name = 'Duvel 33cl'
  AND EXISTS (SELECT 1 FROM (SELECT id FROM products WHERE name = 'DUVEL 24x33cl') AS x);

COMMIT;

-- 3. Unieke index toevoegen zodat exacte productnamen niet opnieuw dubbel kunnen ontstaan
-- Dit deel staat bewust na COMMIT: als de index al bestaat, kan enkel dit deel fout geven zonder de cleanup terug te draaien.
SET @index_exists = (
    SELECT COUNT(*)
    FROM INFORMATION_SCHEMA.STATISTICS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'products'
      AND INDEX_NAME = 'unique_product_name'
);

SET @sql = IF(
    @index_exists = 0,
    'ALTER TABLE products ADD UNIQUE KEY unique_product_name (name)',
    'SELECT "unique_product_name bestaat al" AS message'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 4. Controle: toon eventuele resterende exacte dubbels
SELECT name, COUNT(*) AS aantal
FROM products
GROUP BY name
HAVING COUNT(*) > 1;
