ALTER TABLE `invoice_details` ADD `product_descriptions` TEXT NULL DEFAULT NULL AFTER `product_id`; 

/*02-05-2025*/
ALTER TABLE `stock_management` ADD `created_by` INT NOT NULL DEFAULT '1' AFTER `batch_no`; 

/*10-05-2025*/
ALTER TABLE `purchase_orders` ADD `due_date` DATE NULL DEFAULT NULL AFTER `total_amount`; 
ALTER TABLE `products` ADD `low_stock_alert` INT NOT NULL DEFAULT '5' AFTER `product_type_id`; 
ALTER TABLE `purchase_orders` ADD `round_off` DECIMAL(9,2) NOT NULL AFTER `total_gst`; 
ALTER TABLE `products` CHANGE `product_type_id` `product_type_id` INT(11) NOT NULL DEFAULT '1'; 
ALTER TABLE `products` ADD `unit_id` INT NOT NULL DEFAULT '1' AFTER `low_stock_alert`, ADD `cgst` DECIMAL(9,2) NOT NULL DEFAULT '9' AFTER `unit_id`, ADD `sgst` DECIMAL(9,2) NOT NULL DEFAULT '9' AFTER `cgst`;
ALTER TABLE `invoices` DROP `gst`;
ALTER TABLE `invoice_details` DROP `gst_rate`;
ALTER TABLE `invoice_details` ADD `cgst` DECIMAL(9,2) NOT NULL DEFAULT '9' AFTER `discount`, ADD `sgst` DECIMAL(9,2) NOT NULL DEFAULT '9' AFTER `cgst`;
ALTER TABLE `invoice_details` ADD `hsn_code` VARCHAR(50) NULL DEFAULT NULL AFTER `invoice_date`; 

/*11062025*/
ALTER TABLE `payment_methods` CHANGE `current_balance` `type` TINYINT(1) NULL DEFAULT '1' COMMENT '1=Cash,2=Bank,3=Wallet'; 

/**22062025*/
ALTER TABLE `customers` ADD `photo` VARCHAR(255) NULL AFTER `gst_number`;

/**25062025*/
CREATE TABLE `hsn_codes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `hsn_code` VARCHAR(10) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `gst_rate` DECIMAL(5,2) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_hsn` (`hsn_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `products`
  DROP COLUMN `cgst`,
  DROP COLUMN `sgst`,
  ADD COLUMN `hsn_code_id` INT DEFAULT NULL AFTER `hsn_code`,
  ADD CONSTRAINT `fk_products_hsn` FOREIGN KEY (`hsn_code_id`) REFERENCES `hsn_codes` (`id`) ON DELETE SET NULL;

ALTER TABLE `invoices`
  ADD COLUMN `adjustment` DECIMAL(10,2) DEFAULT 0 AFTER `total_discount`;

ALTER TABLE `invoice_details`
  ADD COLUMN `taxable_value` DECIMAL(10,2) DEFAULT 0 AFTER `discount`,
  ADD COLUMN `hsn_code_id` INT DEFAULT NULL AFTER `hsn_code`,
  ADD CONSTRAINT `fk_invoice_details_hsn` FOREIGN KEY (`hsn_code_id`) REFERENCES `hsn_codes` (`id`) ON DELETE SET NULL;

ALTER TABLE `purchase_order_products`
  ADD COLUMN `taxable_value` DECIMAL(10,2) DEFAULT 0 AFTER `discount`,
  ADD COLUMN `hsn_code_id` INT DEFAULT NULL AFTER `product_id`,
  ADD CONSTRAINT `fk_purchase_order_products_hsn` FOREIGN KEY (`hsn_code_id`) REFERENCES `hsn_codes` (`id`) ON DELETE SET NULL;

CREATE TABLE `gst_json_exports` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `gst_type` ENUM('GSTR1', 'GSTR3B') NOT NULL,
  `period` VARCHAR(7) NOT NULL COMMENT 'Format: YYYY-MM or FY',
  `json_data` JSON NOT NULL,
  `generated_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `credit_notes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `invoice_id` INT NOT NULL,
  `note_number` VARCHAR(30) NOT NULL,
  `note_date` DATE NOT NULL,
  `reason` VARCHAR(255) DEFAULT NULL,
  `total_taxable` DECIMAL(10,2) NOT NULL,
  `total_gst` DECIMAL(10,2) NOT NULL,
  `total_amount` DECIMAL(10,2) NOT NULL,
  `created_by` INT DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`invoice_id`) REFERENCES `invoices`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `credit_note_items` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `credit_note_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `quantity` INT NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `discount` DECIMAL(10,2) DEFAULT 0,
  `taxable_value` DECIMAL(10,2) NOT NULL,
  `cgst` DECIMAL(5,2) NOT NULL,
  `sgst` DECIMAL(5,2) NOT NULL,
  `gst_amount` DECIMAL(10,2) NOT NULL,
  `total_price` DECIMAL(10,2) NOT NULL,
  `hsn_code_id` INT DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`credit_note_id`) REFERENCES `credit_notes`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`),
  FOREIGN KEY (`hsn_code_id`) REFERENCES `hsn_codes`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `debit_notes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `invoice_id` INT NOT NULL,
  `note_number` VARCHAR(30) NOT NULL,
  `note_date` DATE NOT NULL,
  `reason` VARCHAR(255) DEFAULT NULL,
  `total_taxable` DECIMAL(10,2) NOT NULL,
  `total_gst` DECIMAL(10,2) NOT NULL,
  `total_amount` DECIMAL(10,2) NOT NULL,
  `created_by` INT DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`invoice_id`) REFERENCES `invoices`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `debit_note_items` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `debit_note_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `quantity` INT NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `discount` DECIMAL(10,2) DEFAULT 0,
  `taxable_value` DECIMAL(10,2) NOT NULL,
  `cgst` DECIMAL(5,2) NOT NULL,
  `sgst` DECIMAL(5,2) NOT NULL,
  `gst_amount` DECIMAL(10,2) NOT NULL,
  `total_price` DECIMAL(10,2) NOT NULL,
  `hsn_code_id` INT DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`debit_note_id`) REFERENCES `debit_notes`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`),
  FOREIGN KEY (`hsn_code_id`) REFERENCES `hsn_codes`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
