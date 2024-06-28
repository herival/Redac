CREATE TABLE menage (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, user VARCHAR(255) NOT NULL, validateur VARCHAR(255) DEFAULT NULL, date_validation DATETIME DEFAULT NULL, validation TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
ALTER TABLE user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\';

UPDATE `user` SET `roles` = '[\"ROLE_TECH\",\"ROLE_ADMIN\",\"ROLE_MENAGE\"]' WHERE `user`.`id` = 3;
UPDATE `user` SET `roles` = '[\"ROLE_TECH\"]' WHERE `user`.`id` != 3;
UPDATE `user` SET `roles` = '[\"ROLE_MENAGE\"]' WHERE `user`.`id` >= 13;