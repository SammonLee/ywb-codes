
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

#-----------------------------------------------------------------------------
#-- jobeet_category
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `jobeet_category`;


CREATE TABLE `jobeet_category`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255)  NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `jobeet_category_U_1` (`name`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- jobeet_job
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `jobeet_job`;


CREATE TABLE `jobeet_job`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`category_id` INTEGER  NOT NULL,
	`type` VARCHAR(255),
	`company` VARCHAR(255)  NOT NULL,
	`logo` VARCHAR(255),
	`url` VARCHAR(255),
	`position` VARCHAR(255)  NOT NULL,
	`location` VARCHAR(255)  NOT NULL,
	`description` TEXT  NOT NULL,
	`how_to_apply` TEXT  NOT NULL,
	`token` VARCHAR(255)  NOT NULL,
	`is_public` TINYINT default 1 NOT NULL,
	`is_activated` TINYINT default 0 NOT NULL,
	`email` VARCHAR(255)  NOT NULL,
	`expires_at` DATETIME  NOT NULL,
	`created_at` DATETIME,
	`updated_at` DATETIME,
	PRIMARY KEY (`id`),
	UNIQUE KEY `jobeet_job_U_1` (`token`),
	INDEX `jobeet_job_FI_1` (`category_id`),
	CONSTRAINT `jobeet_job_FK_1`
		FOREIGN KEY (`category_id`)
		REFERENCES `jobeet_category` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- jobeet_affiliate
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `jobeet_affiliate`;


CREATE TABLE `jobeet_affiliate`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`url` VARCHAR(255)  NOT NULL,
	`email` VARCHAR(255)  NOT NULL,
	`token` VARCHAR(255)  NOT NULL,
	`is_active` TINYINT default 0 NOT NULL,
	`created_at` DATETIME,
	PRIMARY KEY (`id`),
	UNIQUE KEY `jobeet_affiliate_U_1` (`email`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- jobeet_category_affiliate
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `jobeet_category_affiliate`;


CREATE TABLE `jobeet_category_affiliate`
(
	`category_id` INTEGER  NOT NULL,
	`affiliate_id` INTEGER  NOT NULL,
	PRIMARY KEY (`category_id`,`affiliate_id`),
	CONSTRAINT `jobeet_category_affiliate_FK_1`
		FOREIGN KEY (`category_id`)
		REFERENCES `jobeet_category` (`id`)
		ON DELETE CASCADE,
	INDEX `jobeet_category_affiliate_FI_2` (`affiliate_id`),
	CONSTRAINT `jobeet_category_affiliate_FK_2`
		FOREIGN KEY (`affiliate_id`)
		REFERENCES `jobeet_affiliate` (`id`)
		ON DELETE CASCADE
)Type=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
