CREATE TABLE menage (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, user VARCHAR(255) NOT NULL, validateur VARCHAR(255) DEFAULT NULL, date_validation DATETIME DEFAULT NULL, validation TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
ALTER TABLE user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\';

UPDATE `user` SET `roles` = '[\"ROLE_TECH\",\"ROLE_ADMIN\",\"ROLE_MENAGE\"]' WHERE `user`.`id` = 3;
UPDATE `user` SET `roles` = '[\"ROLE_TECH\"]' WHERE `user`.`id` != 3;
UPDATE `user` SET `roles` = '[\"ROLE_MENAGE\"]' WHERE `user`.`id` >= 13;

DELETE FROM inter WHERE `inter`.`technicien_id` > 11;


SELECT * FROM `inter` WHERE `date` BETWEEN '2024-07-01 09:26:43.000000' AND '2024-07-31 09:26:43.000000' AND `inter`.`technicien_id` <= 13  AND DAYOFWEEK(`date`) != 7;

-- update 
UPDATE `inter` SET `presence` = 1 WHERE `date` BETWEEN '2024-07-01 09:26:43.000000' AND '2024-07-31 09:26:43.000000' AND `inter`.`technicien_id` <= 13  AND DAYOFWEEK(`date`) != 7;


SELECT *
FROM `inter`
WHERE DAYOFWEEK(`date`) = 2;

DNPZF9ZTN72N
1305520198802E

07194689