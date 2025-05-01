-- SQL script to simplify 'order' table by removing redundant address fields

ALTER TABLE `order`
DROP COLUMN IF EXISTS `address_deli`,
DROP COLUMN IF EXISTS `address_id`;
