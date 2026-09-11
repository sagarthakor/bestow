-- =====================================================================
--  Outward Stock - 26 Aug 2026
--  Manual stock adjustment (the counterpart to Inward). Raw SQL for
--  migrations 2026_08_26_100000 and 2026_08_26_100001, for servers where
--  `php artisan migrate` cannot be run.
--
--  Take a backup first.
-- =====================================================================

START TRANSACTION;

-- ---------------------------------------------------------------------
-- 100000  Outward stock document header - who took what out, and why.
--   The actual balance change still goes through stock_status/stock_book,
--   same as every other stock movement; this table only keeps the
--   document trail.
-- ---------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `outward_stocks` (
  `id`            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `doc_no`        VARCHAR(255) NULL,
  `date`          DATE         NULL,
  `reason`        VARCHAR(255) NULL,
  `remark`        TEXT         NULL,
  `status`        VARCHAR(255) NOT NULL DEFAULT 'Y',
  `cancelled_by`  BIGINT       NULL,
  `cancelled_at`  TIMESTAMP    NULL DEFAULT NULL,
  `cancel_reason` VARCHAR(255) NULL,
  `user_id`       BIGINT       NULL,
  `created_at`    TIMESTAMP    NULL DEFAULT NULL,
  `updated_at`    TIMESTAMP    NULL DEFAULT NULL
) DEFAULT CHARACTER SET utf8mb4 COLLATE 'utf8mb4_unicode_ci';

-- ---------------------------------------------------------------------
-- 100001  One line per product taken out on an outward stock document.
-- ---------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `outward_stock_items` (
  `id`               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `outward_stock_id` BIGINT UNSIGNED NOT NULL,
  `product`          INT             NOT NULL,
  `qty`              DOUBLE          NOT NULL,
  `created_at`       TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`       TIMESTAMP       NULL DEFAULT NULL,
  KEY `outward_stock_items_outward_stock_id_index` (`outward_stock_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE 'utf8mb4_unicode_ci';


-- ---------------------------------------------------------------------
--  Register both as applied, so `artisan migrate` skips them.
-- ---------------------------------------------------------------------

INSERT INTO `migrations` (`migration`, `batch`)
SELECT v.`migration`, (SELECT COALESCE(MAX(`batch`), 0) + 1 FROM `migrations` m2)
FROM (
    SELECT '2026_08_26_100000_create_outward_stocks_table' AS `migration`
    UNION ALL SELECT '2026_08_26_100001_create_outward_stock_items_table'
) v
WHERE NOT EXISTS (
    SELECT 1 FROM `migrations` m WHERE m.`migration` = v.`migration`
);


COMMIT;
