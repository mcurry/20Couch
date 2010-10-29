CREATE TABLE IF NOT EXISTS `plugins` (
	`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`name` VARCHAR( 50 ) NOT NULL,
	`description` VARCHAR( 255 ) NOT NULL,
	`version` VARCHAR( 10 ) NOT NULL,
	`path` VARCHAR( 50 ) NOT NULL,
	`config` TEXT NOT NULL,
	`active` TINYINT NOT NULL,
	`created` DATETIME NOT NULL,
	`modified` DATETIME NOT NULL
);
UPDATE `settings` SET `value` = "1.05" WHERE `key` = "version";