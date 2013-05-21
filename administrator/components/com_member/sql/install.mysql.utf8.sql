CREATE TABLE IF NOT EXISTS `#__akeebasubs_subscriptions` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`akeebasubs_subscription_id` BIGINT(20)  NOT NULL ,
`user_id` BIGINT(20)  NOT NULL ,
`credit_invoice_number` VARCHAR(255)  NOT NULL ,
`publish_up` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`publish_down` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`amount` FLOAT NOT NULL ,
`transactions` INT(11)  NOT NULL ,
`credit_paid` VARCHAR(255)  NOT NULL ,
`created_on` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`payment_data` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

