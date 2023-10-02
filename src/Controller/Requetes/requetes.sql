select *
from inter i
join user u
where u.client = 'HELP1FO'
and u.id = i.technicien_id;

UPDATE inter SET salaire = 70
WHERE date BETWEEN '2023-10-01' AND '2023-10-31'
AND technicien_id = 4
OR technicien_id = 5
OR technicien_id = 8
;
