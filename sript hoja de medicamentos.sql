delete from salud.registromedicamentos where cedula='25721393' and autoid='269' and almacenppal='FARMACIA' AND usuariocre='dvalencia'
and fechacre='2012-11-02' and hora='8'

insert into salud.registromedicamentos (compania,almacenppal,numservicio,cedula,autoid,usuariocre,fechacre,hora,cantidad,tipo,numorden,idescritura) values ('Clinica San Jose','FARMACIA','52','25721393','419','dvalencia','2012-11-13','20','1','P','6','15');

update salud.registromedicamentos set cantidad='1'where cedula='25721393' and autoid='418' and almacenppal='FARMACIA'


--update salud.registromedicamentos set numservicio='358' where cedula='94533059' and fechacre>='2012-11-21'

--update salud.plantillamedicamentos set numservicio='358' where cedpaciente='94533059' and fechaformula>='2012-11-21'

update salud.ordenesmedicas set numservicio='358' where cedula='94533059' and fecha>='2012-11-21'

--delete from salud.registromedicamentos where cedula='1114825123' and autoid='233' and almacenppal='FARMACIA' AND usuariocre='jbedoya' and hora='21'