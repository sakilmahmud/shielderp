ALTER TABLE `purchase_order_products`
ADD COLUMN `cess_amount` DECIMAL(10,2) DEFAULT 0.00 AFTER `gst_amount`;

CREATE TABLE `gst_report_exports` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `report_type` VARCHAR(50) NOT NULL,
  `from_date` DATE NOT NULL,
  `to_date` DATE NOT NULL,
  `file_name` VARCHAR(255) NOT NULL,
  `file_path` VARCHAR(255) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` VARCHAR(20) NOT NULL,
  `error_message` TEXT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `invoices`
ADD COLUMN `taxable_value` DECIMAL(10,2) DEFAULT 0.00 AFTER `round_off`,
ADD COLUMN `adjustment_balance` DECIMAL(10,2) DEFAULT 0.00 AFTER `taxable_value`;

ALTER TABLE `purchase_orders`
ADD COLUMN `taxable_value` DECIMAL(10,2) DEFAULT 0.00 AFTER `round_off`,
ADD COLUMN `adjustment_balance` DECIMAL(10,2) DEFAULT 0.00 AFTER `taxable_value`;