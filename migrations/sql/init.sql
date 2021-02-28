CREATE DATABASE IF NOT EXISTS `dashboard`;
CREATE TABLE IF NOT EXISTS `dashboard`.`order` (
    `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `customer_id` INT(10) UNSIGNED NOT NULL,
    `purchase_date` DATE NOT NULL,
    `country` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `device` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;
CREATE TABLE IF NOT EXISTS `dashboard`.`order_item` (
    `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `order_id` INT(10) UNSIGNED NOT NULL,
    `ean` VARCHAR(20) NOT NULL,
    `quantity` INT(10) NOT NULL,
    `price` DOUBLE NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY (`ean`)
) ENGINE = InnoDB;
CREATE TABLE `dashboard`.`customer` (
    `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `first_name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci,
    `last_name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci,
    `email` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;
ALTER TABLE `dashboard`.`order`
  ADD CONSTRAINT `fk_customer_id` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE `dashboard`.`order_item`
  ADD CONSTRAINT `fk_order_id` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;