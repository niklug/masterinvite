CREATE TABLE IF NOT EXISTS `#__member_banners` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`name` VARCHAR(255)  NOT NULL ,
`language` VARCHAR(255)  NOT NULL ,
`width` INT(11)  NOT NULL ,
`height` INT(11)  NOT NULL ,
`filename` VARCHAR(255)  NOT NULL ,
`hints` INT(11)  NOT NULL ,
`preview` VARCHAR(255)  NOT NULL ,
`code` TEXT NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT '1',
`created` DATETIME NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

