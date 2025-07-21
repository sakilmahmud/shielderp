-- New table for storing GST states and union territories
CREATE TABLE `states` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state_name` varchar(100) NOT NULL,
  `state_code` varchar(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Pre-populating the states table with official GST codes
INSERT INTO `states` (`id`, `state_name`, `state_code`) VALUES
(1, 'Jammu and Kashmir', '01'),
(2, 'Himachal Pradesh', '02'),
(3, 'Punjab', '03'),
(4, 'Chandigarh', '04'),
(5, 'Uttarakhand', '05'),
(6, 'Haryana', '06'),
(7, 'Delhi', '07'),
(8, 'Rajasthan', '08'),
(9, 'Uttar Pradesh', '09'),
(10, 'Bihar', '10'),
(11, 'Sikkim', '11'),
(12, 'Arunachal Pradesh', '12'),
(13, 'Nagaland', '13'),
(14, 'Manipur', '14'),
(15, 'Mizoram', '15'),
(16, 'Tripura', '16'),
(17, 'Meghalaya', '17'),
(18, 'Assam', '18'),
(19, 'West Bengal', '19'),
(20, 'Jharkhand', '20'),
(21, 'Odisha', '21'),
(22, 'Chhattisgarh', '22'),
(23, 'Madhya Pradesh', '23'),
(24, 'Gujarat', '24'),
(25, 'Daman and Diu', '26'),
(26, 'Dadra and Nagar Haveli', '26'),
(27, 'Maharashtra', '27'),
(28, 'Andhra Pradesh', '37'),
(29, 'Karnataka', '29'),
(30, 'Goa', '30'),
(31, 'Lakshadweep', '31'),
(32, 'Kerala', '32'),
(33, 'Tamil Nadu', '33'),
(34, 'Puducherry', '34'),
(35, 'Andaman and Nicobar Islands', '35'),
(36, 'Telangana', '36'),
(37, 'Andhra Pradesh (New)', '37'),
(97, 'Other Territory', '97');

-- Add state_id to customers table for Place of Supply
ALTER TABLE `customers`
ADD COLUMN `state_id` INT(11) NULL AFTER `address`,
ADD CONSTRAINT `fk_customers_state` FOREIGN KEY (`state_id`) REFERENCES `states`(`id`);

-- Add state_id to suppliers table for Place of Supply (for ITC)
ALTER TABLE `suppliers`
ADD COLUMN `state_id` INT(11) NULL AFTER `address`,
ADD CONSTRAINT `fk_suppliers_state` FOREIGN KEY (`state_id`) REFERENCES `states`(`id`);

-- Add fields to invoices table for GSTR-1 reporting
ALTER TABLE `invoices`
ADD COLUMN `supply_type` VARCHAR(10) DEFAULT 'B2B' AFTER `is_gst`,
ADD COLUMN `is_reverse_charge` TINYINT(1) DEFAULT 0 AFTER `supply_type`;

-- Add cess_amount to invoice_details for products that attract cess
ALTER TABLE `invoice_details`
ADD COLUMN `cess_amount` DECIMAL(10, 2) DEFAULT 0.00 AFTER `gst_amount`;

COMMIT;