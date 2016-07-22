DROP TABLE IF EXISTS `pais`;

CREATE TABLE IF NOT EXISTS `pais` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) NOT NULL DEFAULT '',
  `nacionalidad` varchar(100) NOT NULL DEFAULT '',
  `idresapro` int(11) NOT NULL,
  `idcontinente` int(11) NOT NULL,
  `idref` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (127,'MARRUECOS',1,'MARROQUÍ',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (143,'SUDAFRICA',2,'SUDAFRICANA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (197,'OTROS - AFRICA',3,'',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (200,'ARGENTINA',4,'ARGENTINA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (202,'BOLIVIA',5,'BOLIVIANA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (203,'BRASIL',6,'BRASILEÑA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (204,'CANADA',7,'CANADIENSE',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (205,'COLOMBIA',8,'COLOMBIANA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (206,'COSTA RICA',9,'COSTARRICENSA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (207,'CUBA',10,'CUBANA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (208,'CHILE',11,'CHILENA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (209,'REPUBLICA DOMINICANA',12,'DOMINICANA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (210,'ECUADOR',13,'ECUATORIANA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (211,'EL SALVADOR',14,'SALVADOREÑA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (212,'ESTADOS UNIDOS',15,'ESTADOUNIDENSE',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (213,'GUATEMALA',16,'GUATEMALTECA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (214,'GUAYANA',17,'GUYANESA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (215,'HAITI',18,'HAITIANA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (216,'HONDURAS',19,'HONDUREÑA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (217,'JAMAICA',20,'JAMAICANA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (218,'MEXICO',21,'MEXICANA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (219,'NICARAGUA',22,'NICARAGÜENSE',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (220,'PANAMA',23,'PANAMEÑA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (221,'PARAGUAY',24,'PARAGUAYA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (222,'PERU',25,'PERUANA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (223,'PUERTO RICO',26,'PUERTORRIQUEÑA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (224,'TRINIDAD Y TOBAGO',27,'TRINITENSE',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (225,'URUGUAY',28,'URUGUAYA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (226,'VENEZUELA',29,'VENEZOLANA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (229,'GUAYANA FRANCESA',30,'GUAYANESA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (232,'SURINAM',31,'SURINAMESA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (297,'OTROS - AMERICA',32,'',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (302,'ARABIA SAUDITA',33,'SAUDÍ',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (310,'CHINA',34,'CHINA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (315,'INDIA',35,'INDIA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (317,'IRAK',36,'IRAQUÍ',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (318,'IRAN',37,'IRANÍ',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (319,'ISRAEL',38,'ISRAELÍ',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (320,'JAPON',39,'JAPONESA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (325,'LIBANO',40,'LIBANESA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (334,'SIRIA',41,'SIRIA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (397,'OTROS - ASIA',42,'',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (401,'ALBANIA',43,'ALBANESA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (405,'AUSTRIA',44,'AUSTRIACA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (406,'BELGICA',45,'BELGA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (408,'CHECOSLOVAQUIA',46,'CHECA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (409,'DINAMARCA',47,'DANESA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (410,'ESPAÑA',48,'ESPAÑOLA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (411,'FINLANDIA',49,'FINLANDESA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (412,'FRANCIA',50,'FRANCESA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (413,'GRECIA',51,'GRIEGA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (414,'HUNGRIA',52,'HÚNGARA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (415,'IRLANDA',53,'IRLANDESA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (416,'ISLANDIA',54,'ISLANDESA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (417,'ITALIA',55,'ITALIANA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (419,'LUXEMBURGO',56,'LUXEMBURGUESA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (422,'NORUEGA',57,'NORUEGA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (423,'HOLANDA',58,'NEERLANDESA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (424,'POLONIA',59,'POLACA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (425,'PORTUGAL',60,'PORTUGUESA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (426,'INGLATERRA',61,'BRITÁNICA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (427,'RUMANIA',62,'RUMANA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (429,'SUECIA',63,'SUECA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (430,'SUIZA',64,'SUIZA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (432,'YUGOSLAVIA',65,'SERBIA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (436,'TURQUIA',66,'TURCA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (438,'ALEMANIA',67,'ALEMANA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (497,'OTROS - EUROPA',68,'',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (501,'AUSTRALIA',69,'AUSTRALIANA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (504,'NUEVA ZELANDA',70,'NEOZELANDESA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (597,'OTROS - OCEANIA',71,'',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (601,'RUSIA',72,'RUSA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (999,'DESCONOCIDO',74,'',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (602,'UCRANIA',73,'UCRANIANA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (603,'LAOS',81,'LAOSIANA',0,NOW());
insert into pais (idresapro, descripcion, idref,nacionalidad, idusuario, fecha_creacion) values (604,'LETONIA',1,'MARROQUÍ',0,NOW());
