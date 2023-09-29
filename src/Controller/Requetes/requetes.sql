select *
from inter i
join user u
where u.client = 'HELP1FO'
and u.id = i.technicien_id;