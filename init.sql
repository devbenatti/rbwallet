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

INSERT INTO onepuchman.`user`
(id, document, email, name, password)
VALUES(100, '39363850099', 'riquinho_rico@gmail.com', 'Riquinho Rico', '123');

INSERT INTO onepuchman.`user`
(id, document, email, name, password)
VALUES(101, '76868053000190', 'tibia_store@gmail.com', 'Tibia Store', '123');

INSERT INTO onepuchman.`user`
(id, document, email, name, password)
VALUES(102, '79451144072', 'xablau_testador@gmail.com', 'Xablau Testador', '123');

INSERT INTO onepuchman.wallet
(id, balance, `user`)
VALUES('d014577b-845d-47ae-8039-010c9ae316c6', 99999.00, 100);

INSERT INTO onepuchman.wallet
(id, balance, `user`)
VALUES('11a6fb03-9a28-4db5-917b-617c60b2b2bb', 0.00, 101);

INSERT INTO onepuchman.wallet
(id, balance, `user`)
VALUES('6d9e75e0-6312-42b9-a0d5-58629532b46a', 1.99, 102);
