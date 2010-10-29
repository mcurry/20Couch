ALTER TABLE `items` ADD `forward_origin_id` INT NULL DEFAULT NULL AFTER `origin_id`;
UPDATE `settings` SET `value` = "0.7" WHERE `key` = "version";