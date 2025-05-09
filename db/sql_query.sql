ALTER TABLE `invoice_details` ADD `product_descriptions` TEXT NULL DEFAULT NULL AFTER `product_id`; 

/*02-05-2025*/
ALTER TABLE `stock_management` ADD `created_by` INT NOT NULL DEFAULT '1' AFTER `batch_no`; 

/*10-05-2025*/
ALTER TABLE `purchase_orders` ADD `due_date` DATE NULL DEFAULT NULL AFTER `total_amount`; 
ALTER TABLE `products` ADD `low_stock_alert` INT NOT NULL DEFAULT '5' AFTER `product_type_id`; 
ALTER TABLE `purchase_orders` ADD `round_off` DECIMAL(9,2) NOT NULL AFTER `total_gst`; 