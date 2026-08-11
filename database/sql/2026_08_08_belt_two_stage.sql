-- =====================================================================
--  Belt Production - 08 Aug 2026  (RUN THIS FIRST)
--  The two-stage belt flow: roll formula, roll production, roll register,
--  belt cutting. Raw SQL for migrations 2026_08_08_100001 .. 100018, for
--  servers where `php artisan migrate` cannot be run.
--
--  Run this file BEFORE 2026_08_10_belt_production.sql. That file alters
--  the tables created here, which is why it failed with
--  "Table 'roll_formula_mst' doesn't exist".
--
--  Requires the July belt masters to already exist: niwar_codes,
--  niwar_type_materials, niwar_size_charts, belt_costings,
--  buckle_formula_mst(_item), belt_production(_material).
--
--  Four of the eighteen migrations add something and a later one removes
--  it again (niwar_codes.roll_product_id, niwar_type_materials.parent_id).
--  Only the net effect is written here - the end state is identical, with
--  fewer statements to fail on. All eighteen are still registered at the
--  end so `artisan migrate` skips them.
--
--  Take a backup first.
-- =====================================================================

-- ---------------------------------------------------------------------
--  CHECK FIRST - run this on its own and read the answer.
--  Every count should be 0 on a database that has not had 08 Aug applied.
--  If some are 1 and some 0 the database is half-migrated: stop and say
--  which, rather than running this file.
-- ---------------------------------------------------------------------
-- SELECT
--   (SELECT COUNT(*) FROM information_schema.tables WHERE table_schema=DATABASE() AND table_name='belt_roll_production')  AS belt_roll_production,
--   (SELECT COUNT(*) FROM information_schema.tables WHERE table_schema=DATABASE() AND table_name='belt_rolls')            AS belt_rolls,
--   (SELECT COUNT(*) FROM information_schema.tables WHERE table_schema=DATABASE() AND table_name='belt_cutting')          AS belt_cutting,
--   (SELECT COUNT(*) FROM information_schema.tables WHERE table_schema=DATABASE() AND table_name='roll_formula_mst')      AS roll_formula_mst,
--   (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name='purchase_requirement' AND column_name='module') AS pr_module;


START TRANSACTION;


-- ---------------------------------------------------------------------
-- 100003 / 100016  Stage A: a batch of niwar woven into rolls of a chosen
--   length. Size does not exist here - the roll is cut to size later.
--   roll_formula_id is folded in from 100016.
-- ---------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `belt_roll_production` (
  `id`              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `batch_no`        VARCHAR(255)    NULL,
  `roll_formula_id` BIGINT UNSIGNED NULL,
  `niwar_code_id`   BIGINT UNSIGNED NOT NULL,
  `roll_product_id` INT             NOT NULL,
  `roll_length_mtr` DOUBLE          NOT NULL,
  `no_of_rolls`     INT             NOT NULL,
  `planned_mtr`     DOUBLE          NOT NULL,
  `produced_mtr`    DOUBLE          NULL,
  `wastage_mtr`     DOUBLE          NULL,
  `customer`        INT             NULL,
  `status`          VARCHAR(255)    NOT NULL DEFAULT 'N',
  `timestamp`       VARCHAR(255)    NULL,
  `user_id`         BIGINT          NULL,
  `created_at`      TIMESTAMP       NULL,
  `updated_at`      TIMESTAMP       NULL,
  KEY `belt_roll_production_niwar_code_id_index`   (`niwar_code_id`),
  KEY `belt_roll_production_roll_formula_id_index` (`roll_formula_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE 'utf8mb4_unicode_ci';


-- ---------------------------------------------------------------------
-- 100004  Dhaga consumed by a batch, frozen at batch creation so a later
--   edit to the niwar rate cannot rewrite history.
-- ---------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `belt_roll_production_material` (
  `id`                      BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `belt_roll_production_id` BIGINT UNSIGNED NOT NULL,
  `material`                BIGINT          NOT NULL,
  `required_qty`            DOUBLE          NOT NULL,
  `avalible_stock`          DOUBLE          NULL,
  `timestamp`               VARCHAR(255)    NULL,
  `user_id`                 BIGINT          NULL,
  `created_at`              TIMESTAMP       NULL,
  `updated_at`              TIMESTAMP       NULL,
  KEY `belt_roll_production_material_belt_roll_production_id_index` (`belt_roll_production_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE 'utf8mb4_unicode_ci';


-- ---------------------------------------------------------------------
-- 100005  The roll register - one row per physical roll. stock_status
--   knows the total meters but cannot tell a 70 mtr roll from a 50 mtr
--   one, nor how much is left on each.
-- ---------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `belt_rolls` (
  `id`                      BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `roll_no`                 VARCHAR(255)    NOT NULL,
  `belt_roll_production_id` BIGINT UNSIGNED NOT NULL,
  `niwar_code_id`           BIGINT UNSIGNED NOT NULL,
  `roll_product_id`         INT             NOT NULL,
  `length_mtr`              DOUBLE          NOT NULL,
  `remaining_mtr`           DOUBLE          NOT NULL,
  `scrap_mtr`               DOUBLE          NULL,
  `status`                  VARCHAR(255)    NOT NULL DEFAULT 'open',
  `user_id`                 BIGINT          NULL,
  `created_at`              TIMESTAMP       NULL,
  `updated_at`              TIMESTAMP       NULL,
  UNIQUE KEY `belt_rolls_roll_no_unique`               (`roll_no`),
  KEY        `belt_rolls_belt_roll_production_id_index`(`belt_roll_production_id`),
  KEY        `belt_rolls_niwar_code_id_index`          (`niwar_code_id`),
  KEY        `belt_rolls_status_index`                 (`status`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE 'utf8mb4_unicode_ci';


-- ---------------------------------------------------------------------
-- 100006 / 100007 / 100008  Stage B: one entry cuts exactly one roll into
--   any number of sizes, then fits them. Kept 1:1 with the roll so every
--   finished belt traces back to a single roll_no.
-- ---------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `belt_cutting` (
  `id`               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `cutting_no`       VARCHAR(255)    NULL,
  `roll_id`          BIGINT UNSIGNED NOT NULL,
  `customer`         INT             NULL,
  `total_pieces`     INT             NULL,
  `total_meter_used` DOUBLE          NULL,
  `balance_mtr`      DOUBLE          NULL,
  `status`           VARCHAR(255)    NOT NULL DEFAULT 'Y',
  `timestamp`        VARCHAR(255)    NULL,
  `user_id`          BIGINT          NULL,
  `created_at`       TIMESTAMP       NULL,
  `updated_at`       TIMESTAMP       NULL,
  KEY `belt_cutting_roll_id_index` (`roll_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE 'utf8mb4_unicode_ci';

CREATE TABLE IF NOT EXISTS `belt_cutting_item` (
  `id`              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `belt_cutting_id` BIGINT UNSIGNED NOT NULL,
  `belt_product`    INT             NOT NULL,
  `size`            VARCHAR(255)    NULL,
  `pieces`          INT             NOT NULL,
  `meter_per_piece` DOUBLE          NOT NULL,
  `total_meter`     DOUBLE          NOT NULL,
  `created_at`      TIMESTAMP       NULL,
  `updated_at`      TIMESTAMP       NULL,
  KEY `belt_cutting_item_belt_cutting_id_index` (`belt_cutting_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE 'utf8mb4_unicode_ci';

CREATE TABLE IF NOT EXISTS `belt_cutting_material` (
  `id`                   BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `belt_cutting_id`      BIGINT UNSIGNED NOT NULL,
  `belt_cutting_item_id` BIGINT UNSIGNED NULL,
  `material`             BIGINT          NOT NULL,
  `required_qty`         DOUBLE          NOT NULL,
  `avalible_stock`       DOUBLE          NULL,
  `timestamp`            VARCHAR(255)    NULL,
  `user_id`              BIGINT          NULL,
  `created_at`           TIMESTAMP       NULL,
  `updated_at`           TIMESTAMP       NULL,
  KEY `belt_cutting_material_belt_cutting_id_index`      (`belt_cutting_id`),
  KEY `belt_cutting_material_belt_cutting_item_id_index` (`belt_cutting_item_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE 'utf8mb4_unicode_ci';


-- ---------------------------------------------------------------------
-- 100013 / 100014  The roll formula: how one meter of a size-less semi
--   product is made. Every category's rows must add up to exactly that
--   category's gm_per_meter on the niwar code.
-- ---------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `roll_formula_mst` (
  `id`            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `product`       INT             NOT NULL,
  `niwar_code_id` BIGINT UNSIGNED NOT NULL,
  `created_at`    TIMESTAMP       NULL,
  `updated_at`    TIMESTAMP       NULL,
  UNIQUE KEY `roll_formula_mst_product_unique`      (`product`),
  KEY        `roll_formula_mst_niwar_code_id_index` (`niwar_code_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE 'utf8mb4_unicode_ci';

CREATE TABLE IF NOT EXISTS `roll_formula_mst_item` (
  `id`                     BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `roll_formula_id`        BIGINT UNSIGNED NOT NULL,
  `niwar_type_material_id` BIGINT UNSIGNED NOT NULL,
  `material`               BIGINT          NOT NULL,
  `gm_per_meter`           DECIMAL(10,4)   NOT NULL,
  `created_at`             TIMESTAMP       NULL,
  `updated_at`             TIMESTAMP       NULL,
  KEY `roll_formula_mst_item_roll_formula_id_index`        (`roll_formula_id`),
  KEY `roll_formula_mst_item_niwar_type_material_id_index` (`niwar_type_material_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE 'utf8mb4_unicode_ci';


-- ---------------------------------------------------------------------
-- 100015  Roll production raises purchase requests the same way sock
--   production does, so they need telling apart on the purchase side.
--   Everything that exists today came from sock production.
-- ---------------------------------------------------------------------

ALTER TABLE `purchase_requirement`
  ADD `module` VARCHAR(255) NOT NULL DEFAULT 'socks' AFTER `order_no`;

UPDATE `purchase_requirement` SET `module` = 'socks';


-- ---------------------------------------------------------------------
-- 100009  The Meter unit and the Niwar Roll category, which Roll Formula
--   uses when it creates a semi product on the fly.
--
--   The per-niwar "NIWAR ROLL x" products that this migration also created
--   are deliberately not recreated here: 100017 immediately dropped the
--   column that pointed at them, and a roll formula now makes its own semi
--   product. Recreating them would only add orphans.
-- ---------------------------------------------------------------------

INSERT INTO `uom` (`uom_name`, `website_id`, `user_id`)
SELECT 'Meter', 1, 1
WHERE NOT EXISTS (SELECT 1 FROM `uom` WHERE LOWER(`uom_name`) IN ('meter', 'mtr'));

INSERT INTO `category` (`category_name`, `slug`, `website_id`, `user_id`)
SELECT 'Niwar Roll', 'niwar-roll', 1, 1
WHERE NOT EXISTS (SELECT 1 FROM `category` WHERE `category_name` = 'Niwar Roll');


-- ---------------------------------------------------------------------
-- 100010 / 100012  Belt formulas used to carry two things that are not
--   size-dependent at all: the auto-calculated dhaga rows and the split of
--   a group niwar material across real dhaga. Both belong to roll
--   production now, so they come out of the belt formula.
--
--   (100002 added niwar_type_materials.parent_id and 100012 dropped it
--   again, so the column is not created here at all - only the deletion
--   below is a lasting change.)
-- ---------------------------------------------------------------------

DELETE FROM `buckle_formula_mst_item`
 WHERE `is_auto` = 1 OR `niwar_type_material_id` IS NOT NULL;


-- ---------------------------------------------------------------------
-- 100011 / 100018  Permissions the menu's @can() checks reference, granted
--   to the roles that already hold the equivalent belt permissions.
-- ---------------------------------------------------------------------

INSERT INTO `permissions` (`name`, `guard_name`, `created_at`, `updated_at`)
SELECT v.`name`, 'web', NOW(), NOW()
FROM (
    SELECT 'belt_roll_production_view'   AS `name`
    UNION ALL SELECT 'belt_roll_production_create'
    UNION ALL SELECT 'belt_cutting_view'
    UNION ALL SELECT 'belt_cutting_create'
    UNION ALL SELECT 'roll_formula_view'
    UNION ALL SELECT 'roll_formula_create'
    UNION ALL SELECT 'roll_formula_update'
    UNION ALL SELECT 'roll_formula_delete'
) v
WHERE NOT EXISTS (
    SELECT 1 FROM `permissions` p WHERE p.`name` = v.`name` AND p.`guard_name` = 'web'
);

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`)
SELECT p.`id`, r.`id`
FROM `permissions` p
JOIN `roles` r ON r.`name` IN ('Super Admin', 'Admin', 'OFFICE')
WHERE p.`guard_name` = 'web'
  AND p.`name` IN (
      'belt_roll_production_view', 'belt_roll_production_create',
      'belt_cutting_view', 'belt_cutting_create',
      'roll_formula_view', 'roll_formula_create',
      'roll_formula_update', 'roll_formula_delete')
  AND NOT EXISTS (
      SELECT 1 FROM `role_has_permissions` rp
      WHERE rp.`permission_id` = p.`id` AND rp.`role_id` = r.`id`
  );


-- ---------------------------------------------------------------------
--  Register all eighteen as applied, so `artisan migrate` skips them.
--  100001, 100002, 100017 are listed although nothing above corresponds to
--  them: their add-then-drop nets to no change, and the names still have
--  to be marked done.
-- ---------------------------------------------------------------------

INSERT INTO `migrations` (`migration`, `batch`)
SELECT v.`migration`, (SELECT COALESCE(MAX(`batch`), 0) + 1 FROM `migrations` m2)
FROM (
    SELECT '2026_08_08_100001_add_roll_product_id_to_niwar_codes_table'            AS `migration`
    UNION ALL SELECT '2026_08_08_100002_add_parent_id_to_niwar_type_materials_table'
    UNION ALL SELECT '2026_08_08_100003_create_belt_roll_production_table'
    UNION ALL SELECT '2026_08_08_100004_create_belt_roll_production_material_table'
    UNION ALL SELECT '2026_08_08_100005_create_belt_rolls_table'
    UNION ALL SELECT '2026_08_08_100006_create_belt_cutting_table'
    UNION ALL SELECT '2026_08_08_100007_create_belt_cutting_item_table'
    UNION ALL SELECT '2026_08_08_100008_create_belt_cutting_material_table'
    UNION ALL SELECT '2026_08_08_100009_seed_roll_products_for_niwar_codes'
    UNION ALL SELECT '2026_08_08_100010_move_niwar_group_split_out_of_belt_formula'
    UNION ALL SELECT '2026_08_08_100011_seed_roll_production_and_cutting_permissions'
    UNION ALL SELECT '2026_08_08_100012_remove_niwar_group_children'
    UNION ALL SELECT '2026_08_08_100013_create_roll_formula_mst_table'
    UNION ALL SELECT '2026_08_08_100014_create_roll_formula_mst_item_table'
    UNION ALL SELECT '2026_08_08_100015_add_module_to_purchase_requirement_table'
    UNION ALL SELECT '2026_08_08_100016_add_roll_formula_id_to_belt_roll_production_table'
    UNION ALL SELECT '2026_08_08_100017_drop_roll_product_id_from_niwar_codes_table'
    UNION ALL SELECT '2026_08_08_100018_seed_roll_formula_permissions'
) v
WHERE NOT EXISTS (
    SELECT 1 FROM `migrations` m WHERE m.`migration` = v.`migration`
);


COMMIT;

-- =====================================================================
--  Next: run 2026_08_10_belt_production.sql, then hit
--  /admin/clear-permission-cache once so the new permissions appear.
-- =====================================================================
