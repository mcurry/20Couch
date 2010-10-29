ALTER TABLE `plugins`
	CHANGE `description` `description` VARCHAR( 255 ) NULL ,
	CHANGE `config` `config` TEXT NULL ,
	CHANGE `active` `active` TINYINT( 4 ) NOT NULL DEFAULT '0';
ALTER TABLE `plugins` ADD `author` VARCHAR( 50 ) NULL DEFAULT NULL AFTER `description` ;
ALTER TABLE `plugins` ADD `link` VARCHAR( 255 ) NULL DEFAULT NULL AFTER `author` ;
UPDATE `settings` SET `value` = "1.06" WHERE `key` = "version";