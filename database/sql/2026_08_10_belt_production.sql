-- =====================================================================
--  Belt Production - 10 Aug 2026
--  Raw SQL for the nine migrations of that date, for servers where
--  `php artisan migrate` cannot be run.
--
--  Target: MySQL 8.0 (JSON_ARRAYAGG / JSON_OBJECT are used once, in step 1).
--
--  Run the whole file once, in order, on a database that already has the
--  08 Aug belt tables (belt_roll_production, belt_rolls, belt_cutting*,
--  roll_formula_mst*). Take a backup first.
--
--  The last section registers all nine in `migrations`, so a later
--  `artisan migrate` does not try to apply them a second time.
-- =====================================================================

START TRANSACTION;


-- ---------------------------------------------------------------------
-- 1. 2026_08_10_100201_add_versioning_to_roll_formula_mst_table
--    A roll formula gets edited and the old copy used to be overwritten,
--    so a batch woven months ago claimed today's recipe. Every saved state
--    is now frozen into roll_formula_revision and the batch records which
--    version it used.
-- ---------------------------------------------------------------------

ALTER TABLE `roll_formula_mst`
  ADD `version`        INT UNSIGNED  NOT NULL DEFAULT '1'        AFTER `niwar_code_id`,
  ADD `status`         VARCHAR(255)  NOT NULL DEFAULT 'active'   AFTER `version`,
  ADD `effective_date` DATE          NULL                        AFTER `status`,
  ADD `notes`          TEXT          NULL                        AFTER `effective_date`,
  ADD `user_id`        BIGINT        NULL                        AFTER `notes`;

CREATE TABLE `roll_formula_revision` (
  `id`              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `roll_formula_id` BIGINT UNSIGNED NOT NULL,
  `version`         INT UNSIGNED    NOT NULL,
  `niwar_code_id`   BIGINT UNSIGNED NOT NULL,
  `items`           TEXT            NOT NULL,
  `effective_date`  DATE            NULL,
  `notes`           TEXT            NULL,
  `user_id`         BIGINT          NULL,
  `created_at`      TIMESTAMP       NULL,
  `updated_at`      TIMESTAMP       NULL
) DEFAULT CHARACTER SET utf8mb4 COLLATE 'utf8mb4_unicode_ci';

ALTER TABLE `roll_formula_revision`
  ADD UNIQUE `roll_formula_revision_roll_formula_id_version_unique` (`roll_formula_id`, `version`);

UPDATE `roll_formula_mst` SET `version` = 1, `status` = 'active';

-- Every formula that already exists becomes its own version 1, so nothing
-- is left without a revision to point at.
INSERT INTO `roll_formula_revision`
    (`roll_formula_id`, `version`, `niwar_code_id`, `items`, `effective_date`, `notes`, `user_id`, `created_at`, `updated_at`)
SELECT
    f.`id`,
    1,
    f.`niwar_code_id`,
    COALESCE((
        SELECT JSON_ARRAYAGG(JSON_OBJECT(
                   'niwar_type_material_id', i.`niwar_type_material_id`,
                   'material',               i.`material`,
                   'gm_per_meter',           CAST(i.`gm_per_meter` AS DOUBLE)))
        FROM `roll_formula_mst_item` i
        WHERE i.`roll_formula_id` = f.`id`
    ), JSON_ARRAY()),
    NULL,
    'Version 1 recorded when formula versioning was introduced.',
    NULL,
    f.`created_at`,
    NOW()
FROM `roll_formula_mst` f;


-- ---------------------------------------------------------------------
-- 2. 2026_08_10_100202_add_actual_consumption_to_belt_roll_production
--    Which formula version a batch was woven on, what the floor really
--    consumed as opposed to what was planned, and who completed or
--    cancelled it. Material rows freeze the gm/meter and the stock-unit
--    factor they were issued at, so a reversal puts back exactly what was
--    taken.
-- ---------------------------------------------------------------------

ALTER TABLE `belt_roll_production`
  ADD `roll_formula_version` INT UNSIGNED NULL        AFTER `roll_formula_id`,
  ADD `completed_by`         BIGINT       NULL        AFTER `status`,
  ADD `completed_at`         DATETIME     NULL        AFTER `completed_by`,
  ADD `cancelled_by`         BIGINT       NULL        AFTER `completed_at`,
  ADD `cancelled_at`         DATETIME     NULL        AFTER `cancelled_by`,
  ADD `cancel_reason`        VARCHAR(255) NULL        AFTER `cancelled_at`;

ALTER TABLE `belt_roll_production_material`
  ADD `gm_per_meter`  DECIMAL(12,4) NULL              AFTER `material`,
  ADD `category_name` VARCHAR(255)  NULL              AFTER `gm_per_meter`,
  ADD `actual_qty`    DOUBLE        NULL              AFTER `required_qty`,
  ADD `stock_factor`  DOUBLE        NOT NULL DEFAULT '1' AFTER `actual_qty`;

-- Existing batches were all woven on version 1, at the planned quantity,
-- and were completed by whoever created them.
UPDATE `belt_roll_production`
   SET `roll_formula_version` = 1
 WHERE `roll_formula_id` IS NOT NULL;

UPDATE `belt_roll_production`
   SET `completed_by` = `user_id`,
       `completed_at` = `updated_at`
 WHERE `status` = 'Y';

UPDATE `belt_roll_production_material`
   SET `actual_qty` = `required_qty`;

-- Formula quantities for KG-tracked materials are written in grams while
-- stock is held in KG, so those rows carry a factor of 1000.
UPDATE `belt_roll_production_material` m
  JOIN `product` p ON p.`id` = m.`material`
  JOIN `uom`     u ON u.`id` = p.`uom`
   SET m.`stock_factor` = 1000
 WHERE UPPER(u.`uom_name`) = 'KG';

-- The gm/meter each row was issued at, recovered from the quantity and the
-- meters it was planned against.
UPDATE `belt_roll_production_material` m
  JOIN `belt_roll_production` b ON b.`id` = m.`belt_roll_production_id`
   SET m.`gm_per_meter` = ROUND(m.`required_qty` / b.`planned_mtr`, 4)
 WHERE b.`planned_mtr` > 0;

-- Two batches sharing a batch number would make the stock ledger ambiguous.
ALTER TABLE `belt_roll_production`
  ADD UNIQUE `belt_roll_production_batch_no_unique` (`batch_no`);


-- ---------------------------------------------------------------------
-- 3. 2026_08_10_100203_add_wastage_and_reversal_to_belt_cutting
--    Trim loss had to hide inside the roll balance, and every piece cut
--    was assumed good. wastage_mtr records the trim, rejected_pieces the
--    pieces that failed - they eat roll meters and fitting but never reach
--    finished goods.
-- ---------------------------------------------------------------------

ALTER TABLE `belt_cutting`
  ADD `wastage_mtr`           DOUBLE       NOT NULL DEFAULT '0' AFTER `total_meter_used`,
  ADD `total_rejected_pieces` INT          NOT NULL DEFAULT '0' AFTER `total_pieces`,
  ADD `cancelled_by`          BIGINT       NULL                 AFTER `status`,
  ADD `cancelled_at`          DATETIME     NULL                 AFTER `cancelled_by`,
  ADD `cancel_reason`         VARCHAR(255) NULL                 AFTER `cancelled_at`;

ALTER TABLE `belt_cutting_item`
  ADD `rejected_pieces` INT NOT NULL DEFAULT '0' AFTER `pieces`;

ALTER TABLE `belt_cutting_material`
  ADD `stock_factor` DOUBLE NOT NULL DEFAULT '1' AFTER `required_qty`;

UPDATE `belt_cutting_material` m
  JOIN `product` p ON p.`id` = m.`material`
  JOIN `uom`     u ON u.`id` = p.`uom`
   SET m.`stock_factor` = 1000
 WHERE UPPER(u.`uom_name`) = 'KG';

ALTER TABLE `belt_cutting`
  ADD UNIQUE `belt_cutting_cutting_no_unique` (`cutting_no`);


-- ---------------------------------------------------------------------
-- 4. 2026_08_10_100204_add_indexes_to_belt_production_tables
--    The consumption and cutting reports group by material and by belt
--    product, and the roll register filters by roll product - all
--    unindexed until now.
-- ---------------------------------------------------------------------

ALTER TABLE `belt_roll_production_material` ADD INDEX `belt_roll_production_material_material_index` (`material`);
ALTER TABLE `belt_cutting_material`         ADD INDEX `belt_cutting_material_material_index` (`material`);
ALTER TABLE `belt_cutting_item`             ADD INDEX `belt_cutting_item_belt_product_index` (`belt_product`);
ALTER TABLE `belt_rolls`                    ADD INDEX `belt_rolls_roll_product_id_index` (`roll_product_id`);
ALTER TABLE `belt_roll_production`          ADD INDEX `belt_roll_production_status_index` (`status`);
ALTER TABLE `belt_roll_production`          ADD INDEX `belt_roll_production_roll_product_id_index` (`roll_product_id`);


-- ---------------------------------------------------------------------
-- 5. 2026_08_10_100205_seed_belt_report_and_reversal_permissions
--    Cancelling reverses stock, so it is a separate permission from
--    creating. Idempotent: re-running inserts nothing new.
-- ---------------------------------------------------------------------

INSERT INTO `permissions` (`name`, `guard_name`, `created_at`, `updated_at`)
SELECT v.`name`, 'web', NOW(), NOW()
FROM (
    SELECT 'belt_roll_production_cancel'            AS `name`
    UNION ALL SELECT 'belt_cutting_cancel'
    UNION ALL SELECT 'roll_production_report_view'
    UNION ALL SELECT 'roll_material_consumption_report_view'
    UNION ALL SELECT 'roll_stock_report_view'
    UNION ALL SELECT 'belt_cutting_report_view'
) v
WHERE NOT EXISTS (
    SELECT 1 FROM `permissions` p WHERE p.`name` = v.`name` AND p.`guard_name` = 'web'
);

-- Granted to the roles that already hold the equivalent belt permissions.
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`)
SELECT p.`id`, r.`id`
FROM `permissions` p
JOIN `roles` r ON r.`name` IN ('Super Admin', 'Admin', 'OFFICE')
WHERE p.`guard_name` = 'web'
  AND p.`name` IN (
      'belt_roll_production_cancel',
      'belt_cutting_cancel',
      'roll_production_report_view',
      'roll_material_consumption_report_view',
      'roll_stock_report_view',
      'belt_cutting_report_view')
  AND NOT EXISTS (
      SELECT 1 FROM `role_has_permissions` rp
      WHERE rp.`permission_id` = p.`id` AND rp.`role_id` = r.`id`
  );


-- ---------------------------------------------------------------------
-- 6. 2026_08_10_100206_add_roll_formula_id_to_buckle_formula_mst_table
--    Which semi product a finished belt is cut from. Superseded in day-to-
--    day use by roll_formula_mst.belt_product_id (step 8), but kept: it is
--    still read as a fallback and holds existing links.
-- ---------------------------------------------------------------------

ALTER TABLE `buckle_formula_mst`
  ADD `roll_formula_id` BIGINT UNSIGNED NULL AFTER `belt_costing_id`;

ALTER TABLE `buckle_formula_mst`
  ADD INDEX `buckle_formula_mst_roll_formula_id_index` (`roll_formula_id`);

-- Where a niwar code has exactly one semi product the link is not a guess -
-- there is nothing else the belt could be cut from. Anything less certain
-- is left NULL for a human to set.
UPDATE `buckle_formula_mst` f
  JOIN `belt_costings` bc ON bc.`id` = f.`belt_costing_id`
  JOIN (
      SELECT `niwar_code_id`, MIN(`id`) AS `roll_formula_id`
      FROM `roll_formula_mst`
      GROUP BY `niwar_code_id`
      HAVING COUNT(*) = 1
  ) single ON single.`niwar_code_id` = bc.`niwar_id`
   SET f.`roll_formula_id` = single.`roll_formula_id`
 WHERE f.`roll_formula_id` IS NULL;


-- ---------------------------------------------------------------------
-- 7. 2026_08_10_100207_add_fitting_products_to_belt_costings_table
--    Belt costing said how much bukkal, kadi and panni a belt takes, but
--    only as rates - there was no product behind any of them, so nothing
--    could leave stock. Superseded by the list in step 9; these columns
--    stay because step 9 migrates from them.
-- ---------------------------------------------------------------------

ALTER TABLE `belt_costings`
  ADD `bukkal_product_id` INT    NULL                  AFTER `bukkal_id`,
  ADD `bukkal_qty`        DOUBLE NOT NULL DEFAULT '1'  AFTER `bukkal_product_id`,
  ADD `kadi_product_id`   INT    NULL                  AFTER `kadi_qty`,
  ADD `panni_product_id`  INT    NULL                  AFTER `panni_packing`;


-- ---------------------------------------------------------------------
-- 8. 2026_08_10_100208_add_belt_product_to_roll_formula_mst_table
--    Which finished belt this roll is woven to become. The size on the
--    product picked is deliberately ignored - a roll has no size - what
--    the pick is for is the product identity, so cutting then offers
--    exactly that product's own size range.
-- ---------------------------------------------------------------------

ALTER TABLE `roll_formula_mst`
  ADD `belt_product_id` INT NULL AFTER `product`;

ALTER TABLE `roll_formula_mst`
  ADD INDEX `roll_formula_mst_belt_product_id_index` (`belt_product_id`);


-- ---------------------------------------------------------------------
-- 9. 2026_08_10_100209_create_belt_costing_fitting_table
--    Everything a belt takes besides its niwar, as a list rather than a
--    fixed set of columns - bukkal, kadi, slider, rivet, packaging.
-- ---------------------------------------------------------------------

CREATE TABLE `belt_costing_fitting` (
  `id`               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `belt_costing_id`  BIGINT UNSIGNED NOT NULL,
  `label`            VARCHAR(255)    NULL,
  `product`          INT             NOT NULL,
  `qty`              DOUBLE          NOT NULL DEFAULT '1',
  `created_at`       TIMESTAMP       NULL,
  `updated_at`       TIMESTAMP       NULL
) DEFAULT CHARACTER SET utf8mb4 COLLATE 'utf8mb4_unicode_ci';

ALTER TABLE `belt_costing_fitting` ADD INDEX `belt_costing_fitting_belt_costing_id_index` (`belt_costing_id`);
ALTER TABLE `belt_costing_fitting` ADD INDEX `belt_costing_fitting_product_index` (`product`);

-- Whatever the three columns from step 7 already name becomes the first rows.
INSERT INTO `belt_costing_fitting` (`belt_costing_id`, `label`, `product`, `qty`, `created_at`, `updated_at`)
SELECT `id`, 'Bukkal', `bukkal_product_id`, COALESCE(`bukkal_qty`, 1), NOW(), NOW()
FROM `belt_costings`
WHERE `bukkal_product_id` IS NOT NULL AND COALESCE(`bukkal_qty`, 1) > 0;

INSERT INTO `belt_costing_fitting` (`belt_costing_id`, `label`, `product`, `qty`, `created_at`, `updated_at`)
SELECT `id`, 'Kadi', `kadi_product_id`, COALESCE(`kadi_qty`, 0), NOW(), NOW()
FROM `belt_costings`
WHERE `kadi_product_id` IS NOT NULL AND COALESCE(`kadi_qty`, 0) > 0;

INSERT INTO `belt_costing_fitting` (`belt_costing_id`, `label`, `product`, `qty`, `created_at`, `updated_at`)
SELECT `id`, 'Panni', `panni_product_id`, COALESCE(`panni_packing`, 0), NOW(), NOW()
FROM `belt_costings`
WHERE `panni_product_id` IS NOT NULL AND COALESCE(`panni_packing`, 0) > 0;


-- ---------------------------------------------------------------------
-- 10. Register all nine as applied, so `artisan migrate` skips them.
--     Batch number continues from whatever the database is already on.
-- ---------------------------------------------------------------------

INSERT INTO `migrations` (`migration`, `batch`)
SELECT v.`migration`, (SELECT COALESCE(MAX(`batch`), 0) + 1 FROM `migrations` m2)
FROM (
    SELECT '2026_08_10_100201_add_versioning_to_roll_formula_mst_table'          AS `migration`
    UNION ALL SELECT '2026_08_10_100202_add_actual_consumption_to_belt_roll_production'
    UNION ALL SELECT '2026_08_10_100203_add_wastage_and_reversal_to_belt_cutting'
    UNION ALL SELECT '2026_08_10_100204_add_indexes_to_belt_production_tables'
    UNION ALL SELECT '2026_08_10_100205_seed_belt_report_and_reversal_permissions'
    UNION ALL SELECT '2026_08_10_100206_add_roll_formula_id_to_buckle_formula_mst_table'
    UNION ALL SELECT '2026_08_10_100207_add_fitting_products_to_belt_costings_table'
    UNION ALL SELECT '2026_08_10_100208_add_belt_product_to_roll_formula_mst_table'
    UNION ALL SELECT '2026_08_10_100209_create_belt_costing_fitting_table'
) v
WHERE NOT EXISTS (
    SELECT 1 FROM `migrations` m WHERE m.`migration` = v.`migration`
);


COMMIT;

-- =====================================================================
--  After running: hit /admin/clear-permission-cache once. Spatie caches
--  permissions for 24h and only clears itself on Eloquent writes, so the
--  six new permissions inserted above stay invisible to the menu until
--  that cache is dropped.
-- =====================================================================
