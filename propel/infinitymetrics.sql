-- Generated by SQL Maestro for MySQL. Release date 6/10/2008
-- 12/8/2008 2:43:08 PM
-- ----------------------------------
-- Alias: im at localhost
-- Database name: im
-- Host: localhost
-- Port number: 3306
-- User name: root
-- Server: 5.0.67-community-nt
-- Session ID: 3073
-- Character set: utf8
-- Collation: utf8_bin


SET FOREIGN_KEY_CHECKS=0;

DROP DATABASE IF EXISTS `im`;

CREATE DATABASE `im`
  CHARACTER SET `utf8`
  COLLATE `utf8_bin`;

USE `im`;

/* Tables */
DROP TABLE IF EXISTS `channel`;

CREATE TABLE `channel` (
  `channel_id`       varchar(10) NOT NULL,
  `project_jn_name`  varchar(50) NOT NULL,
  `title`            varchar(255),
  `category`         enum ('COMMIT','DOCUMENTATION','FORUM','ISSUE','MAILING_LIST') NOT NULL,
  PRIMARY KEY (`channel_id`, `project_jn_name`)
) ENGINE = InnoDB;

DROP TABLE IF EXISTS `custom_event`;

CREATE TABLE `custom_event` (
  `custom_event_id`  int(10) UNSIGNED AUTO_INCREMENT NOT NULL,
  `project_jn_name`  varchar(50) NOT NULL,
  `title`            varchar(64) NOT NULL,
  `date`             date NOT NULL,
  `state`            enum ('OPEN','RESOLVED') NOT NULL DEFAULT 'OPEN',
  PRIMARY KEY (`custom_event_id`)
) ENGINE = InnoDB;

DROP TABLE IF EXISTS `custom_event_entry`;

CREATE TABLE `custom_event_entry` (
  `entry_id`         int(10) UNSIGNED AUTO_INCREMENT NOT NULL,
  `custom_event_id`  int(10) UNSIGNED NOT NULL,
  `notes`            varchar(255) NOT NULL,
  `date`             timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`entry_id`)
) ENGINE = InnoDB;

DROP TABLE IF EXISTS `event`;

CREATE TABLE `event` (
  `channel_id`       varchar(10) NOT NULL,
  `event_id`         varchar(30) NOT NULL,
  `jn_username`      varchar(32) NOT NULL,
  `date`             date NOT NULL,
  `project_jn_name`  varchar(50) NOT NULL,
  PRIMARY KEY (`channel_id`, `event_id`, `project_jn_name`)
) ENGINE = InnoDB;

DROP TABLE IF EXISTS `institution`;

CREATE TABLE `institution` (
  `institution_id`  smallint(5) UNSIGNED AUTO_INCREMENT NOT NULL,
  `name`            varchar(255) NOT NULL,
  `abbreviation`    varchar(16) NOT NULL,
  `city`            varchar(255) NOT NULL,
  `state_province`  varchar(255) NOT NULL,
  `country`         varchar(255) NOT NULL,
  PRIMARY KEY (`institution_id`)
) ENGINE = InnoDB;

DROP TABLE IF EXISTS `project`;

CREATE TABLE `project` (
  `project_jn_name`         varchar(50) NOT NULL,
  `parent_project_jn_name`  varchar(50),
  `summary`                 varchar(255),
  PRIMARY KEY (`project_jn_name`)
) ENGINE = InnoDB;

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `user_id`      int(10) UNSIGNED AUTO_INCREMENT NOT NULL,
  `jn_username`  varchar(32) NOT NULL,
  `jn_password`  varchar(32) NOT NULL,
  `first_name`   varchar(50) NOT NULL,
  `last_name`    varchar(50) NOT NULL,
  `email`        varchar(255) NOT NULL,
  `type`         enum ('STUDENT','INSTRUCTOR','JAVANET') NOT NULL DEFAULT 'JAVANET',
  PRIMARY KEY (`user_id`)
) ENGINE = InnoDB;

DROP TABLE IF EXISTS `user_x_institution`;

CREATE TABLE `user_x_institution` (
  `user_id`         int(10) UNSIGNED NOT NULL,
  `institution_id`  smallint(5) UNSIGNED NOT NULL,
  `identification`  varchar(36),
  PRIMARY KEY (`user_id`, `institution_id`)
) ENGINE = InnoDB;

DROP TABLE IF EXISTS `user_x_project`;

CREATE TABLE `user_x_project` (
  `jn_username`      varchar(32) NOT NULL,
  `project_jn_name`  varchar(50) NOT NULL,
  `is_owner`         tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`jn_username`, `project_jn_name`)
) ENGINE = InnoDB;

DROP TABLE IF EXISTS `workspace`;

CREATE TABLE `workspace` (
  `workspace_id`     mediumint(8) UNSIGNED AUTO_INCREMENT NOT NULL,
  `user_id`          int(10) UNSIGNED NOT NULL,
  `state`            enum ('NEW','ACTIVE','PAUSED','INACTIVE') NOT NULL DEFAULT 'NEW',
  `title`            varchar(64) NOT NULL,
  `description`      varchar(255),
  `project_jn_name`  varchar(50) NOT NULL,
  PRIMARY KEY (`workspace_id`)
) ENGINE = InnoDB;

DROP TABLE IF EXISTS `workspace_share`;

CREATE TABLE `workspace_share` (
  `workspace_id`  mediumint(8) UNSIGNED NOT NULL,
  `user_id`       int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`workspace_id`, `user_id`)
) ENGINE = InnoDB;

/* Indexes */
CREATE INDEX `idx_channel_category`
  ON `channel`
  (`category`);

CREATE INDEX `idx_fk_channel_project_jn_name`
  ON `channel`
  (`project_jn_name`);

CREATE INDEX `idx_custom_event_date`
  ON `custom_event`
  (`date`);

CREATE INDEX `idx_custom_event_state`
  ON `custom_event`
  (`state`);

CREATE INDEX `idx_fk_custom_event_project_jn_name`
  ON `custom_event`
  (`project_jn_name`);

CREATE INDEX `idx_custom_event_entry_date`
  ON `custom_event_entry`
  (`date`);

CREATE INDEX `idx_fk_entry_custom_event_id`
  ON `custom_event_entry`
  (`custom_event_id`);

CREATE INDEX `idx_creator_username`
  ON `event`
  (`jn_username`);

CREATE INDEX `idx_publication_date`
  ON `event`
  (`date`);

CREATE INDEX `id_fk_event_all_keys`
  ON `event`
  (`channel_id`, `project_jn_name`);

CREATE INDEX `idx_institution_abbreviation`
  ON `institution`
  (`abbreviation`);

CREATE INDEX `idx_institution_city`
  ON `institution`
  (`city`);

CREATE INDEX `idx_institution_country`
  ON `institution`
  (`country`);

CREATE INDEX `idx_institution_name`
  ON `institution`
  (`name`);

CREATE INDEX `idx_institution_state_province`
  ON `institution`
  (`state_province`);

CREATE INDEX `idx_project_parent_project_jn_name`
  ON `project`
  (`parent_project_jn_name`);

CREATE INDEX `idx_user_email`
  ON `user`
  (`email`);

CREATE INDEX `idx_user_type`
  ON `user`
  (`type`);

CREATE UNIQUE INDEX `username`
  ON `user`
  (`jn_username`);

CREATE INDEX `idx_user_x_institution_identification`
  ON `user_x_institution`
  (`identification`);

CREATE INDEX `idx_user_x_institution_institution_id`
  ON `user_x_institution`
  (`institution_id`);

CREATE INDEX `idx_fk_project_x_project_jn_name`
  ON `user_x_project`
  (`project_jn_name`);

CREATE INDEX `idx_user_x_project_is_owner`
  ON `user_x_project`
  (`is_owner`);

CREATE INDEX `idx_fk_workspace_project_jn_name`
  ON `workspace`
  (`project_jn_name`);

CREATE INDEX `idx_fk_worspace_user_id`
  ON `workspace`
  (`user_id`);

CREATE INDEX `idx_state`
  ON `workspace`
  (`state`);

CREATE INDEX `idx_fk_share_user_id`
  ON `workspace_share`
  (`user_id`);

/* Foreign Keys */
ALTER TABLE `channel`
  ADD CONSTRAINT `idx_fk_channel_project_jn_name`
  FOREIGN KEY (`project_jn_name`)
    REFERENCES `project`(`project_jn_name`)
    ON DELETE CASCADE
    ON UPDATE RESTRICT;

ALTER TABLE `custom_event`
  ADD CONSTRAINT `idx_fk_custom_event_project_jn_name`
  FOREIGN KEY (`project_jn_name`)
    REFERENCES `project`(`project_jn_name`)
    ON DELETE CASCADE
    ON UPDATE RESTRICT;

ALTER TABLE `custom_event_entry`
  ADD CONSTRAINT `idx_fk_entry_custom_event_id`
  FOREIGN KEY (`custom_event_id`)
    REFERENCES `custom_event`(`custom_event_id`)
    ON DELETE CASCADE
    ON UPDATE RESTRICT;

ALTER TABLE `event`
  ADD CONSTRAINT `id_fk_event_all_keys`
  FOREIGN KEY (`channel_id`, `project_jn_name`)
    REFERENCES `channel`(`channel_id`, `project_jn_name`)
    ON DELETE CASCADE
    ON UPDATE RESTRICT;

ALTER TABLE `user_x_institution`
  ADD CONSTRAINT `idx_user_x_institution_institution_id`
  FOREIGN KEY (`institution_id`)
    REFERENCES `institution`(`institution_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE;

ALTER TABLE `user_x_institution`
  ADD CONSTRAINT `idx_user_x_institution_user_id`
  FOREIGN KEY (`user_id`)
    REFERENCES `user`(`user_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE;

ALTER TABLE `user_x_project`
  ADD CONSTRAINT `idx_fk_user_x_project_jn_username`
  FOREIGN KEY (`jn_username`)
    REFERENCES `user`(`jn_username`)
    ON DELETE CASCADE
    ON UPDATE RESTRICT;

ALTER TABLE `user_x_project`
  ADD CONSTRAINT `idx_fk_user_x_project_project_jn_name`
  FOREIGN KEY (`project_jn_name`)
    REFERENCES `project`(`project_jn_name`)
    ON DELETE CASCADE
    ON UPDATE RESTRICT;

ALTER TABLE `workspace`
  ADD CONSTRAINT `idx_fk_workspace_project_jn_name`
  FOREIGN KEY (`project_jn_name`)
    REFERENCES `project`(`project_jn_name`)
    ON DELETE CASCADE
    ON UPDATE RESTRICT;

ALTER TABLE `workspace`
  ADD CONSTRAINT `idx_fk_worspace_user_id`
  FOREIGN KEY (`user_id`)
    REFERENCES `user`(`user_id`)
    ON DELETE CASCADE
    ON UPDATE RESTRICT;

ALTER TABLE `workspace_share`
  ADD CONSTRAINT `idx_fk_share_user_id`
  FOREIGN KEY (`user_id`)
    REFERENCES `user`(`user_id`)
    ON DELETE CASCADE
    ON UPDATE RESTRICT;

ALTER TABLE `workspace_share`
  ADD CONSTRAINT `idx_fk_share_workspace_id`
  FOREIGN KEY (`workspace_id`)
    REFERENCES `workspace`(`workspace_id`)
    ON DELETE CASCADE
    ON UPDATE RESTRICT;