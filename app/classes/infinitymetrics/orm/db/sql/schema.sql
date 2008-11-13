
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

#-----------------------------------------------------------------------------
#-- channel
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `channel`;


CREATE TABLE `channel`
(
	`channel_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`project_jn_name` VARCHAR(50)  NOT NULL,
	`channel_name` VARCHAR(30)  NOT NULL,
	`title` VARCHAR(255),
	`category` CHAR  NOT NULL,
	PRIMARY KEY (`channel_id`,`project_jn_name`,`channel_name`),
	KEY `idx_channel_category`(`category`),
	KEY `idx_fk_channel_project_jn_name`(`project_jn_name`),
	CONSTRAINT `channel_FK_1`
		FOREIGN KEY (`project_jn_name`)
		REFERENCES `project` (`project_jn_name`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- dispute
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `dispute`;


CREATE TABLE `dispute`
(
	`dispute_id` SMALLINT  NOT NULL AUTO_INCREMENT,
	`title` VARCHAR(64)  NOT NULL,
	`date` DATE  NOT NULL,
	`project_jn_name` VARCHAR(50)  NOT NULL,
	PRIMARY KEY (`dispute_id`),
	KEY `idx_fk_dispute_project_jn_name`(`project_jn_name`),
	CONSTRAINT `dispute_FK_1`
		FOREIGN KEY (`project_jn_name`)
		REFERENCES `project` (`project_jn_name`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- dispute_entry
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `dispute_entry`;


CREATE TABLE `dispute_entry`
(
	`entry_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`notes` VARCHAR(255)  NOT NULL,
	`dispute_id` SMALLINT  NOT NULL,
	`date` DATETIME default 'CURRENT_TIMESTAMP' NOT NULL,
	PRIMARY KEY (`entry_id`),
	KEY `idx_fk_entry_dispute_id`(`dispute_id`),
	CONSTRAINT `dispute_entry_FK_1`
		FOREIGN KEY (`dispute_id`)
		REFERENCES `dispute` (`dispute_id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- event
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `event`;


CREATE TABLE `event`
(
	`channel_id` INTEGER  NOT NULL,
	`event_id` VARCHAR(30)  NOT NULL,
	`jn_username` VARCHAR(32)  NOT NULL,
	`date` DATETIME default 'CURRENT_TIMESTAMP' NOT NULL,
	PRIMARY KEY (`channel_id`,`event_id`),
	KEY `idx_creator_username`(`jn_username`),
	KEY `idx_publication_date`(`date`),
	CONSTRAINT `event_FK_1`
		FOREIGN KEY (`channel_id`)
		REFERENCES `channel` (`channel_id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE,
	CONSTRAINT `event_FK_2`
		FOREIGN KEY (`jn_username`)
		REFERENCES `user` (`jn_username`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- institution
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `institution`;


CREATE TABLE `institution`
(
	`institution_id` SMALLINT  NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255)  NOT NULL,
	`abbreviation` VARCHAR(16)  NOT NULL,
	`city` VARCHAR(255)  NOT NULL,
	`state_province` VARCHAR(255)  NOT NULL,
	`country` VARCHAR(255)  NOT NULL,
	PRIMARY KEY (`institution_id`)
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- instructors
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `instructors`;


CREATE TABLE `instructors`
(
	`user_id` INTEGER  NOT NULL,
	`institution_id` SMALLINT  NOT NULL,
	PRIMARY KEY (`user_id`),
	KEY `idx_fk_institution`(`institution_id`),
	CONSTRAINT `instructors_FK_1`
		FOREIGN KEY (`institution_id`)
		REFERENCES `institution` (`institution_id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE,
	CONSTRAINT `instructors_FK_2`
		FOREIGN KEY (`user_id`)
		REFERENCES `user` (`user_id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- project
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `project`;


CREATE TABLE `project`
(
	`project_jn_name` VARCHAR(50)  NOT NULL,
	`summary` VARCHAR(64),
	PRIMARY KEY (`project_jn_name`)
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- student
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `student`;


CREATE TABLE `student`
(
	`user_id` INTEGER  NOT NULL,
	`institution_id` SMALLINT  NOT NULL,
	PRIMARY KEY (`user_id`),
	KEY `idx_fk_student_institution_id`(`institution_id`),
	CONSTRAINT `student_FK_1`
		FOREIGN KEY (`institution_id`)
		REFERENCES `institution` (`institution_id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- student_x_project
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `student_x_project`;


CREATE TABLE `student_x_project`
(
	`user_id` INTEGER  NOT NULL,
	`project_jn_name` VARCHAR(50)  NOT NULL,
	`is_leader` TINYINT default 0 NOT NULL,
	PRIMARY KEY (`user_id`,`project_jn_name`),
	KEY `idx_fk_project_x_project_jn_name`(`project_jn_name`),
	CONSTRAINT `student_x_project_FK_1`
		FOREIGN KEY (`project_jn_name`)
		REFERENCES `project` (`project_jn_name`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE,
	CONSTRAINT `student_x_project_FK_2`
		FOREIGN KEY (`user_id`)
		REFERENCES `student` (`user_id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- user
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `user`;


CREATE TABLE `user`
(
	`user_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`jn_username` VARCHAR(32)  NOT NULL,
	`jn_password` VARCHAR(32)  NOT NULL,
	`first_name` VARCHAR(50)  NOT NULL,
	`last_name` VARCHAR(50)  NOT NULL,
	`email` VARCHAR(255)  NOT NULL,
	`type` CHAR,
	PRIMARY KEY (`user_id`),
	UNIQUE KEY `username` (`jn_username`)
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- workpace_x_project
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `workpace_x_project`;


CREATE TABLE `workpace_x_project`
(
	`workspace_id` SMALLINT  NOT NULL,
	`project_jn_name` VARCHAR(50)  NOT NULL,
	`summary` VARCHAR(64),
	PRIMARY KEY (`workspace_id`,`project_jn_name`),
	KEY `idx_fk_workspace_x_project_jn_name`(`project_jn_name`),
	CONSTRAINT `workpace_x_project_FK_1`
		FOREIGN KEY (`project_jn_name`)
		REFERENCES `project` (`project_jn_name`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE,
	CONSTRAINT `workpace_x_project_FK_2`
		FOREIGN KEY (`workspace_id`)
		REFERENCES `workspace` (`workspace_id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- workspace
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `workspace`;


CREATE TABLE `workspace`
(
	`workspace_id` SMALLINT  NOT NULL AUTO_INCREMENT,
	`state` CHAR default 'NEW' NOT NULL,
	`user_id` INTEGER  NOT NULL,
	`title` VARCHAR(64)  NOT NULL,
	`description` VARCHAR(255),
	PRIMARY KEY (`workspace_id`),
	KEY `idx_fk_worspace_user_id`(`user_id`),
	KEY `idx_state`(`state`),
	CONSTRAINT `workspace_FK_1`
		FOREIGN KEY (`user_id`)
		REFERENCES `user` (`user_id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- workspace_share
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `workspace_share`;


CREATE TABLE `workspace_share`
(
	`workspace_id` SMALLINT  NOT NULL,
	`user_id` INTEGER  NOT NULL,
	PRIMARY KEY (`workspace_id`,`user_id`),
	KEY `idx_fk_share_user_id`(`user_id`),
	CONSTRAINT `workspace_share_FK_1`
		FOREIGN KEY (`user_id`)
		REFERENCES `user` (`user_id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE,
	CONSTRAINT `workspace_share_FK_2`
		FOREIGN KEY (`workspace_id`)
		REFERENCES `workspace` (`workspace_id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE
)Type=MyISAM;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
