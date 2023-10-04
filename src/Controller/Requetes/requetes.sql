select *
from inter i
join user u
where u.client = 'HELP1FO'
and u.id = i.technicien_id;

UPDATE inter SET salaire = 70
WHERE date BETWEEN '2023-09-01' AND '2023-09-30'
AND technicien_id = 4
OR technicien_id = 5
OR technicien_id = 8
;
