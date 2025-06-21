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