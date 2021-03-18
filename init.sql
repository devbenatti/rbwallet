CREATE USER IF NOT EXISTS 'saitama'@'%' IDENTIFIED BY 'xablau';
GRANT USAGE ON * . * TO 'saitama'@'%';
GRANT SELECT, INSERT, UPDATE, DELETE ON onepuchman.* TO 'saitama'@'%';

CREATE DATABASE IF NOT EXISTS onepuchman;

USE onepuchman;

DROP TABLE IF EXISTS user;

CREATE TABLE user (
  `id` 				INT(11)			NOT NULL AUTO_INCREMENT,
  `document` 			VARCHAR(14)				NOT NULL UNIQUE,
  `email` 				VARCHAR(50)			NOT NULL UNIQUE,
  `name` 	VARCHAR(100)			NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `updated_at` TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW(),
  `created_at` TIMESTAMP NOT NULL DEFAULT NOW(),
  PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS wallet;

CREATE TABLE wallet (
  `id` 				VARCHAR(36)			NOT NULL,
  `balance` 			DECIMAL(15,2)				NOT NULL,
  `user` 				INT(11)			NOT NULL,
  `updated_at` TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW(),
  `created_at` TIMESTAMP NOT NULL DEFAULT NOW(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user`) REFERENCES user (`id`)
);

DROP TABLE IF EXISTS transaction_status;

CREATE TABLE transaction_status (
  `id` 				INT(11)			NOT NULL AUTO_INCREMENT,
  `description` 			VARCHAR(100)				NOT NULL,
  `updated_at` TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW(),
  `created_at` TIMESTAMP NOT NULL DEFAULT NOW(),
  PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS transaction;

CREATE TABLE transaction (
  `code` 				VARCHAR(36)			NOT NULL,
  `amount` 			DECIMAL(15,2)				NOT NULL,
  `status` 				INT(11)			NOT NULL,
  `failed_reason`    VARCHAR(50) NULL DEFAULT '',
  `payer`            INT(11)         NOT NULL,
  `payee`            INT(11)         NOT NULL,
  `updated_at` TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW(),
  `created_at` TIMESTAMP NOT NULL DEFAULT NOW(),
  PRIMARY KEY (`code`),
  FOREIGN KEY (`payer`) REFERENCES user (`id`),
  FOREIGN KEY (`payee`) REFERENCES user (`id`),
  FOREIGN KEY (`status`) REFERENCES transaction_status (`id`)
);

DROP TABLE IF EXISTS notification_retry;

CREATE TABLE notification_retry (
  `id` 				INT(11)			NOT NULL AUTO_INCREMENT,
  `transaction` 			VARCHAR(36)			NOT NULL,
  `updated_at` TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW(),
  `created_at` TIMESTAMP NOT NULL DEFAULT NOW(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`transaction`) REFERENCES transaction (`code`)
);

INSERT INTO onepuchman.transaction_status
(id, description)
VALUES(1, 'PROCESSING');
INSERT INTO onepuchman.transaction_status
(id, description)
VALUES(2, 'SUCESS');
INSERT INTO onepuchman.transaction_status
(id, description)
VALUES(3, 'FAILED');
