ALTER TABLE `settings` CHANGE `key` `key` VARCHAR( 50 ) NOT NULL;
UPDATE `settings` SET `value` = "1.11" WHERE `key` = "version";