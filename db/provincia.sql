DROP TABLE IF EXISTS `provincia`;

CREATE TABLE IF NOT EXISTS `provincia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idresapro` int(11) NOT NULL,
  `idresapro_pais` int(11) NOT NULL,
  `descripcion` varchar(100) NOT NULL DEFAULT '',
  `idref` int(11) NOT NULL, 
  `idusuario` int(11) NOT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,127,'DESCONOCIDA',1,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,143,'DESCONOCIDA',2,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,197,'DESCONOCIDA',3,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (2,200,'CAPITAL FEDERAL',4,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (6,200,'BUENOS AIRES',5,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (10,200,'CATAMARCA',6,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (14,200,'CORDOBA',7,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (18,200,'CORRIENTES',8,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (22,200,'CHACO',9,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (26,200,'CHUBUT',10,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (30,200,'ENTRE RIOS',11,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (34,200,'FORMOSA',12,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (38,200,'JUJUY',13,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (42,200,'LA PAMPA',14,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (46,200,'LA RIOJA',15,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (50,200,'MENDOZA',16,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (54,200,'MISIONES',17,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (58,200,'NEUQUEN',18,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (62,200,'RIO NEGRO',19,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (66,200,'SALTA',20,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (70,200,'SAN JUAN',21,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (74,200,'SAN LUIS',22,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (78,200,'SANTA CRUZ',23,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (82,200,'SANTA FE',24,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (86,200,'SANTIAGO DEL ESTERO',25,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (90,200,'TUCUMAN',26,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (94,200,'TIERRA DEL FUEGO ANT. E ISLAS',27,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,200,'DESCONOCIDA',28,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,202,'DESCONOCIDA',29,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,203,'DESCONOCIDA',30,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,204,'DESCONOCIDA',31,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,205,'DESCONOCIDA',32,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,206,'DESCONOCIDA',33,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,207,'DESCONOCIDA',34,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,208,'DESCONOCIDA',35,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,209,'DESCONOCIDA',36,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,210,'DESCONOCIDA',37,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,211,'DESCONOCIDA',38,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,212,'DESCONOCIDA',39,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,213,'DESCONOCIDA',40,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,214,'DESCONOCIDA',41,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,215,'DESCONOCIDA',42,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,216,'DESCONOCIDA',43,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,217,'DESCONOCIDA',44,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,218,'DESCONOCIDA',45,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,219,'DESCONOCIDA',46,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,220,'DESCONOCIDA',47,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,221,'DESCONOCIDA',48,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,222,'DESCONOCIDA',49,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,223,'DESCONOCIDA',50,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,224,'DESCONOCIDA',51,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,225,'DESCONOCIDA',52,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,226,'DESCONOCIDA',53,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,229,'DESCONOCIDA',54,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,232,'DESCONOCIDA',55,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,297,'DESCONOCIDA',56,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,302,'DESCONOCIDA',57,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,310,'DESCONOCIDA',58,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,315,'DESCONOCIDA',59,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,317,'DESCONOCIDA',60,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,318,'DESCONOCIDA',61,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,319,'DESCONOCIDA',62,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,320,'DESCONOCIDA',63,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,325,'DESCONOCIDA',64,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,334,'DESCONOCIDA',65,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,397,'DESCONOCIDA',66,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,401,'DESCONOCIDA',67,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,405,'DESCONOCIDA',68,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,406,'DESCONOCIDA',69,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,408,'DESCONOCIDA',70,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,409,'DESCONOCIDA',71,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,410,'DESCONOCIDA',72,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,411,'DESCONOCIDA',73,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,412,'DESCONOCIDA',74,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,413,'DESCONOCIDA',75,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,414,'DESCONOCIDA',76,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,415,'DESCONOCIDA',77,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,416,'DESCONOCIDA',78,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,417,'DESCONOCIDA',79,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,419,'DESCONOCIDA',80,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,422,'DESCONOCIDA',81,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,423,'DESCONOCIDA',82,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,424,'DESCONOCIDA',83,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,425,'DESCONOCIDA',84,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,426,'DESCONOCIDA',85,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,427,'DESCONOCIDA',86,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,429,'DESCONOCIDA',87,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,430,'DESCONOCIDA',88,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,432,'DESCONOCIDA',89,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,436,'DESCONOCIDA',90,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,438,'DESCONOCIDA',91,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,497,'DESCONOCIDA',92,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,501,'DESCONOCIDA',93,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,504,'DESCONOCIDA',94,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,597,'DESCONOCIDA',95,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,601,'DESCONOCIDA',96,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,999,'DESCONOCIDA',97,0,NOW());
insert into provincia (idresapro, idresapro_pais, descripcion, idref, idusuario, fecha_creacion) values (99,602,'DESCONOCIDA',98,0,NOW());
