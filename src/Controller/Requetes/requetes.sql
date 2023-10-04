select *
from inter i
join user u
where u.client = 'HELP1FO'
and u.id = i.technicien_id;

<<<<<<< HEAD
SELECT * FROM `saisie` WHERE `dateouverture` >= '2023-09-29 00:16:36' 
LIKE `client` = 'SGNOVA' 
AND 'prestation_id' = 5
;

SELECT * FROM `saisie` WHERE `dateouverture` >= '2023-09-29 00:16:36' AND `client` LIKE 'SGNOVA'

SELECT * FROM `saisie` WHERE `prestation_id` = 5 
AND `dateouverture` > '2023-09-29 00:16:36' 
AND `dateouverture` < '2023-09-29 23:16:36' 
AND `client` = 'SGNOVA'
;

# mise à jour saisie
UPDATE `saisie` set `prestation_id` = 23  
WHERE `prestation_id` = 5 
AND `dateouverture` > '2023-09-29 00:16:36' 
AND `dateouverture` < '2023-09-29 23:16:36' 
AND `client` = 'SGNOVA'
;

SELECT * FROM `saisie` WHERE `prestation` = 'PREPARATION'
AND `dateouverture` > '2023-09-29 00:16:36' 
AND `dateouverture` < '2023-09-29 23:16:36' 
AND `deposant` = 'SGNOVA'
;
=======
UPDATE inter SET salaire = 70
WHERE date BETWEEN '2023-10-01' AND '2023-10-31'
AND technicien_id = 4
OR technicien_id = 5
OR technicien_id = 8
;
>>>>>>> 655d6a411c96c8ed97e4a49703caf95c85884747
