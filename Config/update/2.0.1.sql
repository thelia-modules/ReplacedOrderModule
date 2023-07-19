
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- replaced_module
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `replaced_module`;

CREATE TABLE `replaced_module`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(255),
    `title` VARCHAR(255),
    `new_module` VARCHAR(255),
    `created_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
