CREATE TABLE IF NOT EXISTS `%%PREFIX%%items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provider_id` int(11) NOT NULL,
  `origin_id` int(11) NOT NULL,
	`forward_origin_id` int(20) DEFAULT NULL,
  `provider_key` bigint(20) DEFAULT NULL,
  `message` tinyint(1) NOT NULL DEFAULT '0',
  `read` tinyint(1) NOT NULL DEFAULT '0',
  `text` text NOT NULL,
  `link` text NOT NULL,
  `reply_user_provider_key` bigint(20) DEFAULT NULL,
  `reply_status_provider_key` bigint(20) DEFAULT NULL,
  `client` text,
  `posted` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `provider_id` (`provider_id`,`provider_key`)
);

CREATE TABLE IF NOT EXISTS `%%PREFIX%%origins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provider_id` int(11) NOT NULL,
  `provider_key` bigint(20) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `profile` text,
  `origin_link` varchar(255) DEFAULT NULL,
  `follower` tinyint(1) NOT NULL DEFAULT '0',
  `following` tinyint(1) NOT NULL DEFAULT '0',
  `follower_count` int(11) NOT NULL DEFAULT '0',
  `following_count` int(11) NOT NULL DEFAULT '0',
  `update_count` int(11) NOT NULL DEFAULT '0',
  `avatar` text NOT NULL,
  `link` text NOT NULL,
  `item_count` int(11) NOT NULL DEFAULT '0',
  `muted` tinyint(1) DEFAULT '0',
  `muted_until` datetime DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `provider_key` (`provider_key`)
);

CREATE TABLE IF NOT EXISTS `%%PREFIX%%providers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_id` int(11) NOT NULL,
  `provider_key` bigint(20) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `keyword` varchar(50) DEFAULT NULL,
  `update_frequency` int(11) NOT NULL DEFAULT '300',
  `access_token` varchar(255) DEFAULT NULL,
  `access_token_secret` varchar(255) DEFAULT NULL,
  `item_count` int(11) NOT NULL DEFAULT '0',
  `last_item_provider_key` bigint(20) DEFAULT NULL,
  `last_message_provider_key` bigint(20) DEFAULT NULL,
  `last_updated` datetime DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `%%PREFIX%%settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(50) NOT NULL,
  `value` varchar(255) NOT NULL,
  `editable` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
);

CREATE TABLE IF NOT EXISTS `%%PREFIX%%users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(40) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `%%PREFIX%%plugins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `author` varchar(50) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `version` varchar(10) NOT NULL,
  `path` varchar(50) NOT NULL,
  `config` text,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
	PRIMARY KEY (`id`)
);

INSERT INTO `%%PREFIX%%settings` (`id`, `key`, `value`, `editable`) VALUES
	(1, 'language', 'en', 0),
	(2, 'retweet_method', 'api', 1),
	(3, 'show_remaining_requests', 0, 1),
	(4, 'version', '1.12', 0),
	(5, 'registration_key', '', 1);