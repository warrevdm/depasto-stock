-- Levering / startstock brouwer op basis van Bestelling brouwer 19052026(Dranken)
-- Kolom 'Besteld' werd geïnterpreteerd als aantal verpakkingen/bakken/dozen, behalve waar pack_size = 1.
-- Voer eerst database/brouwer_products_seed.sql uit, daarna dit bestand.
-- Dit boekt de bestelling als stock_movements met movement_type = 'delivery'.

START TRANSACTION;

-- Tout Bien 50l: besteld 8 x verpakking 1 = 8
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 8, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'Tout Bien 50l' LIMIT 1;

-- TRIPEL D'ANVERS 20L: besteld 4 x verpakking 1 = 4
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 4, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'TRIPEL D''ANVERS 20L' LIMIT 1;

-- LIEFMANS FRUITESSE 20L: besteld 8 x verpakking 1 = 8
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 8, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'LIEFMANS FRUITESSE 20L' LIMIT 1;

-- Bolleke 20L Keg: besteld 2 x verpakking 1 = 2
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 2, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'Bolleke 20L Keg' LIMIT 1;

-- DUVEL 24x33cl: besteld 6 x verpakking 24 = 144
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 144, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'DUVEL 24x33cl' LIMIT 1;

-- DUVEL 666 24x33cl: besteld 2 x verpakking 24 = 48
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 48, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'DUVEL 666 24x33cl' LIMIT 1;

-- VEDETT EXTRA BLOND 24x33cl: besteld 2 x verpakking 24 = 48
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 48, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'VEDETT EXTRA BLOND 24x33cl' LIMIT 1;

-- WESTMALLE DUBBEL 24x33cl: besteld 2 x verpakking 24 = 48
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 48, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'WESTMALLE DUBBEL 24x33cl' LIMIT 1;

-- WESTMALLE TRIPEL 24x33cl: besteld 2 x verpakking 24 = 48
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 48, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'WESTMALLE TRIPEL 24x33cl' LIMIT 1;

-- Chouffe 24x25cl: besteld 2 x verpakking 24 = 48
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 48, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'Chouffe 24x25cl' LIMIT 1;

-- Salitos Ice: besteld 15 x verpakking 1 = 15
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 15, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'Salitos Ice' LIMIT 1;

-- Salitos Blue: besteld 15 x verpakking 1 = 15
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 15, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'Salitos Blue' LIMIT 1;

-- Salitos Pink: besteld 5 x verpakking 1 = 5
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 5, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'Salitos Pink' LIMIT 1;

-- VEDETT EXTRA 0,0% 24x33cl: besteld 1 x verpakking 24 = 24
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 24, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'VEDETT EXTRA 0,0% 24x33cl' LIMIT 1;

-- LIEFMANS FRUITESSE 0,0% ALCOHOLVRIJ 24x25cl: besteld 1 x verpakking 24 = 24
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 24, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'LIEFMANS FRUITESSE 0,0% ALCOHOLVRIJ 24x25cl' LIMIT 1;

-- LA CHOUFFE 0,0% 24x33cl: besteld 1 x verpakking 24 = 24
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 24, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'LA CHOUFFE 0,0% 24x33cl' LIMIT 1;

-- CHAUDFONTAINE BRUIS 24x25cl: besteld 6 x verpakking 24 = 144
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 144, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'CHAUDFONTAINE BRUIS 24x25cl' LIMIT 1;

-- CHAUDFONTAINE PLAT 24x25cl: besteld 6 x verpakking 24 = 144
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 144, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'CHAUDFONTAINE PLAT 24x25cl' LIMIT 1;

-- CHAUDFONTAINE PLAT 20x50CL: besteld 4 x verpakking 20 = 80
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 80, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'CHAUDFONTAINE PLAT 20x50CL' LIMIT 1;

-- CHAUDFONTAINE BRUIS 20x50CL: besteld 4 x verpakking 20 = 80
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 80, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'CHAUDFONTAINE BRUIS 20x50CL' LIMIT 1;

-- FUZE SPARKLING BLACK TEA 24x20cl: besteld 8 x verpakking 24 = 192
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 192, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'FUZE SPARKLING BLACK TEA 24x20cl' LIMIT 1;

-- FUZE GREEN MANGO CHAMOMILE 24x20cl: besteld 8 x verpakking 24 = 192
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 192, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'FUZE GREEN MANGO CHAMOMILE 24x20cl' LIMIT 1;

-- FANTA ORANGE 24x20cl: besteld 4 x verpakking 24 = 96
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 96, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'FANTA ORANGE 24x20cl' LIMIT 1;

-- FEVER TREE MEDITERRANEAN 24x20cl OW: besteld 4 x verpakking 24 = 96
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 96, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'FEVER TREE MEDITERRANEAN 24x20cl OW' LIMIT 1;

-- FEVER TREE GINGER ALE 24x20cl OW: besteld 4 x verpakking 24 = 96
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 96, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'FEVER TREE GINGER ALE 24x20cl OW' LIMIT 1;

-- COCA COLA ZERO 24x20cl: besteld 10 x verpakking 24 = 240
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 240, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'COCA COLA ZERO 24x20cl' LIMIT 1;

-- COCA COLA 24x20cl: besteld 10 x verpakking 24 = 240
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 240, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'COCA COLA 24x20cl' LIMIT 1;

-- Almdudler: besteld 2 x verpakking 1 = 2
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 2, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'Almdudler' LIMIT 1;

-- Red Bull: besteld 10 x verpakking 24 = 240
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 240, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'Red Bull' LIMIT 1;

-- Proviant BIO Rabarber: besteld 1 x verpakking 1 = 1
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 1, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'Proviant BIO Rabarber' LIMIT 1;

-- Proviant BIO citroen gember: besteld 1 x verpakking 1 = 1
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 1, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'Proviant BIO citroen gember' LIMIT 1;

-- Appelsap fairtrade: besteld 1 x verpakking 1 = 1
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 1, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'Appelsap fairtrade' LIMIT 1;

-- Fruitsap fairtrade: besteld 1 x verpakking 1 = 1
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 1, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'Fruitsap fairtrade' LIMIT 1;

-- Pompelmoessap fairtrade: besteld 1 x verpakking 1 = 1
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 1, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'Pompelmoessap fairtrade' LIMIT 1;

-- Fristi x 24: besteld 1 x verpakking 24 = 24
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 24, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'Fristi x 24' LIMIT 1;

-- Cecemel X 24: besteld 1 x verpakking 24 = 24
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 24, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'Cecemel X 24' LIMIT 1;

-- CAVA BARRIO 75cl x 6 flessen: besteld 16 x verpakking 6 = 96
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 96, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'CAVA BARRIO 75cl x 6 flessen' LIMIT 1;

-- BUITENGEWOON WHITE BLOSSEM 75cl x 6 flessen: besteld 4 x verpakking 6 = 24
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 24, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'BUITENGEWOON WHITE BLOSSEM 75cl x 6 flessen' LIMIT 1;

-- BUITENGEWOON THE TRILOGY SHIRAZ 75cl x 6 flessen: besteld 2 x verpakking 6 = 12
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 12, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'BUITENGEWOON THE TRILOGY SHIRAZ 75cl x 6 flessen' LIMIT 1;

-- BUITENGEWOON ROSE 75cl x 6 flessen: besteld 4 x verpakking 6 = 24
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 24, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'BUITENGEWOON ROSE 75cl x 6 flessen' LIMIT 1;

-- VACHE D'AUTOMNE ROUGE 75cl x 6 flessen: besteld 2 x verpakking 6 = 12
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 12, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'VACHE D''AUTOMNE ROUGE 75cl x 6 flessen' LIMIT 1;

-- VACHE D'AUTOMNE BLANC 75cl x 6 flessen: besteld 8 x verpakking 6 = 48
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 48, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'VACHE D''AUTOMNE BLANC 75cl x 6 flessen' LIMIT 1;

-- VACHE D'AUTOMME ROSE 75cl x 6 flessen: besteld 8 x verpakking 6 = 48
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 48, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'VACHE D''AUTOMME ROSE 75cl x 6 flessen' LIMIT 1;

-- Zoete wijn? x 6 flessen: besteld 2 x verpakking 6 = 12
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 12, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'Zoete wijn? x 6 flessen' LIMIT 1;

-- COPPERHEAD 50CL x 6 flessen: besteld 1 x verpakking 6 = 6
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 6, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'COPPERHEAD 50CL x 6 flessen' LIMIT 1;

-- GIN MARE 70cl x 6 flessen: besteld 1 x verpakking 6 = 6
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 6, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'GIN MARE 70cl  x 6 flessen' LIMIT 1;

-- COPPERHEAD NON-ALCOHOLISCHE SPIRIT 50CL: besteld 1 x verpakking 1 = 1
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 1, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'COPPERHEAD NON-ALCOHOLISCHE SPIRIT 50CL' LIMIT 1;

-- Kraken rum x 6 flessen: besteld 1 x verpakking 6 = 6
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 6, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'Kraken rum x 6 flessen' LIMIT 1;

-- Bacardi spiced x 6 flessen: besteld 2 x verpakking 6 = 12
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 12, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'Bacardi spiced x 6 flessen' LIMIT 1;

-- Vodka Basic Absolut x 6 flessen: besteld 2 x verpakking 6 = 12
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 12, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'Vodka Basic Absolut x 6 flessen' LIMIT 1;

-- Vodka Trojka x 6 flessen: besteld 1 x verpakking 6 = 6
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 6, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'Vodka Trojka x 6 flessen' LIMIT 1;

-- Whisky Red Label x 6 flessen: besteld 1 x verpakking 6 = 6
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 6, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'Whisky Red Label x 6 flessen' LIMIT 1;

-- Martini Witte x 6 flessen: besteld 1 x verpakking 6 = 6
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 6, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'Martini Witte x 6 flessen' LIMIT 1;

-- Martini Rode x 6 flessen: besteld 1 x verpakking 6 = 6
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 6, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'Martini Rode x 6 flessen' LIMIT 1;

-- Limoncello x 6 flessen: besteld 1 x verpakking 6 = 6
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 6, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'Limoncello x 6 flessen' LIMIT 1;

-- Pissang x 6 flessen: besteld 1 x verpakking 6 = 6
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 6, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'Pissang x 6 flessen' LIMIT 1;

-- Porto x 6 flessen: besteld 1 x verpakking 6 = 6
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 6, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'Porto x 6 flessen' LIMIT 1;

-- Licor 43 x 6 flessen: besteld 1 x verpakking 6 = 6
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 6, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'Licor 43 x 6 flessen' LIMIT 1;

-- Passoa x 6 flessen: besteld 1 x verpakking 6 = 6
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 6, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'Passoa x 6 flessen' LIMIT 1;

-- Aperol x 6 flessen: besteld 1 x verpakking 6 = 6
INSERT INTO stock_movements (product_id, movement_type, quantity, reference, source, movement_date, created_by)
SELECT id, 'delivery', 6, 'Bestelling brouwer 19052026', 'brouwer_excel', '2026-05-19', 1
FROM products WHERE name = 'Aperol x 6 flessen' LIMIT 1;

COMMIT;
