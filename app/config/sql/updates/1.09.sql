ALTER TABLE `plugins` CHANGE `active` `active` TINYINT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE `origins` CHANGE `muted` `muted` TINYINT( 1 ) NULL DEFAULT '0';
UPDATE `settings` SET `value` = "1.09" WHERE `key` = "version";