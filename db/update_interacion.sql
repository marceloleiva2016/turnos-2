#Creacion de tablas cama y sector

CREATE TABLE `cama` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nro_cama` int(11) DEFAULT NULL,
  `idsector` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `idusuario` int(11) DEFAULT NULL,
  `habilitado` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `cama_internacion` (
  `idcama` int(11) NOT NULL,
  `idinternacion` int(11) NOT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `idusuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`idcama`,`idinternacion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `sector` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `detalle` varchar(255) DEFAULT NULL,
  `idespecialidad` varchar(45) DEFAULT NULL,
  `idusuario` int(11) DEFAULT NULL,
  `habilitado` tinyint(1) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `internacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipodoc` int(11) DEFAULT NULL,
  `nrodoc` int(11) DEFAULT NULL,
  `idatencion_predecesora` int(11) DEFAULT NULL,
  `motivo_ingreso` longtext,
  `iddiagnostico_ingreso` int(11) DEFAULT NULL,
  `idobra_social` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `idusuario` int(11) DEFAULT NULL,
  `habilitado` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `internacion_log_cama` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idinternacion` int(11) DEFAULT NULL,
  `idcama` int(11) DEFAULT NULL,
  `idusuario` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `habilitado` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#Actualizacion permisos para internacion
INSERT INTO `turnos`.`permiso` (`idpermiso`, `detalle`, `entidad`) VALUES ('ABM_CAMA', 'Alta, Baja y Modificacion de Camas Internacion', 'TODOS');
INSERT INTO `turnos`.`permiso` (`idpermiso`, `detalle`, `entidad`) VALUES ('ABM_SECTOR', 'Alta, Baja y Modificacion de Sectores Internacion', 'TODOS');
INSERT INTO `turnos`.`permiso` (`idpermiso`, `detalle`, `entidad`) VALUES ('INTERNACION', 'Visualizar Internacion', 'TODOS');

#Permisos de pantalla
INSERT INTO `turnos`.`permiso` (`idpermiso`, `detalle`, `entidad`) VALUES ('VER_TURNEROS', 'Ver Pantallas de llamados', 'TODOS');
INSERT INTO `turnos`.`permiso` (`idpermiso`, `detalle`, `entidad`) VALUES ('EDITAR_TURNEROS', 'Editar Pantallas de llamados', 'TODOS');
INSERT INTO `turnos`.`permiso` (`idpermiso`, `detalle`, `entidad`) VALUES ('ELIMINAR_TURNERO', 'Eliminar Pantallas de llamados', 'TODOS');
INSERT INTO `turnos`.`permiso` (`idpermiso`, `detalle`, `entidad`) VALUES ('NUEVO_TURNERO', 'Alta Pantalla de llamados', 'TODOS');

CREATE TABLE `form_internacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipodoc` int(11) DEFAULT NULL,
  `nrodoc` int(11) DEFAULT NULL,
  `idatencion` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `idusuario` int(11) DEFAULT NULL,
  `habilitado` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `form_internacion_egreso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idform_internacion` int(11) DEFAULT NULL,
  `idtipo_egreso_internacion` varchar(45) DEFAULT NULL,
  `iddiagnostico` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `iduser` int(11) DEFAULT NULL,
  `habilitado` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `form_internacion_observaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idform_internacion` int(11) DEFAULT NULL,
  `idtipo_observacion` int(11) DEFAULT NULL,
  `detalle` longtext,
  `fecha_ingreso` datetime DEFAULT NULL,
  `iduser` int(11) DEFAULT NULL,
  `habilitado` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `form_internacion_tipo_egreso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `detalle` varchar(45) DEFAULT NULL,
  `habilitado` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `form_internacion_tipo_laboratorio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `form_internacion_tipo_observacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `detalle` varchar(45) DEFAULT NULL,
  `iduser` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `habilitado` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `form_internacion_lista_laboratorio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `descripcion` text,
  `favorito` int(11) DEFAULT NULL,
  `idusuario` int(11) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `tipo_laboratorio` int(11) NOT NULL,
  `es_numerico` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_UNIQUE` (`nombre`,`tipo_laboratorio`),
  KEY `fk_hca_lista_laboratorio_hca_tipo_laboratorio1` (`tipo_laboratorio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `form_internacion_laboratorios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_internacion_lista_laboratorio_id` int(11) NOT NULL,
  `form_internacion_id` int(11) NOT NULL,
  `value` decimal(10,2) DEFAULT NULL,
  `idusuario` int(11) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `text_value` text,
  `estado` varchar(20) NOT NULL DEFAULT 'ACTIVO',
  `usuariom` int(11) DEFAULT NULL,
  `predecesor` int(11) DEFAULT NULL,
  `comentario` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hca_lista_laboratorio_id_UNIQUE` (`form_internacion_lista_laboratorio_id`,`form_internacion_id`),
  KEY `fk_hca_lista_laboratorio_has_hca_hca1` (`form_internacion_id`),
  KEY `fk_hca_lista_laboratorio_has_hca_hca_lista_laboratorio` (`form_internacion_lista_laboratorio_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `formulario` (`id`,`nombre`,`descripcion`,`ubicacion`,`esDefault`,`nivel`,`icono`,`fecha_creacion`,`idusuario`,`habilitado`) VALUES (3,'INTERNACION','Tiene los movimientos de cama, cambios de evolucion, etc','/internacion/',1,'PRINCIPAL','icon-edit','2016-03-10 10:02:39',0,1);

INSERT INTO `form_internacion_tipo_laboratorio` (`id`,`descripcion`,`idusuario`,`fecha_creacion`) VALUES (1,'Sangre',0,'2012-10-25 16:08:53');
INSERT INTO `form_internacion_tipo_laboratorio` (`id`,`descripcion`,`idusuario`,`fecha_creacion`) VALUES (2,'Orina',0,'2012-10-25 16:08:57');

INSERT INTO `form_internacion_tipo_egreso` (`id`,`detalle`,`habilitado`) VALUES (1,'DOMICILIO',1);
INSERT INTO `form_internacion_tipo_egreso` (`id`,`detalle`,`habilitado`) VALUES (2,'INTERNACION',1);
INSERT INTO `form_internacion_tipo_egreso` (`id`,`detalle`,`habilitado`) VALUES (3,'OBITO',1);
INSERT INTO `form_internacion_tipo_egreso` (`id`,`detalle`,`habilitado`) VALUES (4,'FUGA',1);
INSERT INTO `form_internacion_tipo_egreso` (`id`,`detalle`,`habilitado`) VALUES (5,'DERIVACION',1);

INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (1,'Hto','hematocrito',1,0,'2012-10-25 16:11:13',1,1);
INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (2,'G. Blancos','Leucocitos',2,0,'2012-10-25 16:32:58',1,1);
INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (3,'Glucemia','Glucemia',3,0,'2012-10-25 16:33:12',1,1);
INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (4,'Urea','Urea',4,0,'2012-10-25 16:33:17',1,1);
INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (5,'Creat','Creatinina',5,0,'2012-10-25 16:33:20',1,1);
INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (6,'Na','Sodio',6,0,'2012-10-25 16:33:22',1,1);
INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (7,'K','Potasio',7,0,'2012-10-25 16:33:25',1,1);
INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (8,'Lactico','Acido Lactico',8,0,'2012-10-25 16:33:28',1,1);
INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (9,'Got','Transaminasa Glutaminico oxalacetica',9,0,'2012-10-25 16:33:33',1,1);
INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (10,'Gpt','Transaminasa Glutaminico piruvica',10,0,'2012-10-25 16:33:36',1,1);
INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (11,'Amilasa','Amilasa',11,0,'2012-10-25 16:33:39',1,1);
INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (12,'Bt','Bilirrubina total',12,0,'2012-10-25 16:33:42',1,1);
INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (13,'Bd','Bilirrubina Directa',13,0,'2012-10-25 16:33:46',1,1);
INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (14,'Fal','Fosfatasa alcalina',14,0,'2012-10-25 16:33:49',1,1);
INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (15,'T Prot %','Porcentaje de proteina t',15,0,'2012-10-25 16:33:53',1,1);
INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (16,'Aptt seg','Tromboplastina parcial activada',16,0,'2012-10-25 16:33:57',1,1);
INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (17,'Ph','Acides',17,0,'2012-10-25 16:33:59',1,1);
INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (18,'Co2','Monoxido de Carbono',18,0,'2012-10-25 16:34:02',1,1);
INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (19,'HcO3','Bicarbonato',20,0,'2012-10-25 16:34:04',1,1);
INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (20,'Po2','Saturacion de Oxigeno',21,0,'2012-10-25 16:40:59',1,1);
INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (21,'SarO2','SarO2',22,0,'2012-10-25 16:41:03',1,1);
INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (22,'Plaq','Plaquetas',23,0,'2012-10-25 16:41:06',1,1);
INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (23,'Cpk','Creatina Fosfoquinasa',24,0,'2012-10-25 16:41:12',1,1);
INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (24,'Exc Base','Bicarbonato',19,0,'2012-10-29 12:03:40',1,1);
INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (25,'Densidad','Densidad',2,0,'2012-10-29 12:38:43',2,1);
INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (26,'Hb','Hemoglobinuria',3,0,'2012-10-29 12:38:46',2,1);
INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (27,'Ph','Acides',1,0,'2012-10-29 13:03:39',2,1);
INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (28,'Cetonuria','Cetonuria',4,0,'2012-10-29 13:03:43',2,1);
INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (29,'Glucosuria','Glucosuria',5,0,'2012-10-29 13:03:48',2,1);
INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (30,'Sedimento','Sedimento',7,0,'2012-10-29 13:03:51',2,0);
INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (31,'Proteinuria','Proteinuria',6,0,'2012-10-29 13:04:31',2,1);
INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (32,'Trop. Cuantitativo','Troponina',25,0,'2013-04-12 15:31:34',1,1);
INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (33,'Trop. Cualitativo','Troponina',26,0,'2013-04-12 15:31:34',1,0);
INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (34,'CPKMB','Creatina Quinasa MB',27,0,'2013-04-12 15:31:34',1,1);
INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (35,'Cua. Tiempo','Cuagudograma Tiempo',28,0,'2013-04-12 15:31:34',1,1);
INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (36,'Cua. Concentración','Cuagudograma Concentración',29,0,'2013-04-12 15:31:34',1,1);
INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (37,'Cua. KPTT','Cuagudograma KPTT',30,0,'2013-04-12 15:31:34',1,1);
INSERT INTO `form_internacion_lista_laboratorio` (`id`,`nombre`,`descripcion`,`favorito`,`idusuario`,`fecha_creacion`,`tipo_laboratorio`,`es_numerico`) VALUES (38,'Cua. RIN','Cuagudograma RIN',31,0,'2013-04-12 15:31:34',1,1);

INSERT INTO `form_internacion_tipo_observacion` (`id`,`detalle`,`iduser`,`fecha_creacion`,`habilitado`) VALUES (1,'Observaciones/Tratamientos',0,'2016-04-20 09:34:46',1);
INSERT INTO `form_internacion_tipo_observacion` (`id`,`detalle`,`iduser`,`fecha_creacion`,`habilitado`) VALUES (2,'Interconsultas',0,'2016-10-11 11:53:21',1);
INSERT INTO `form_internacion_tipo_observacion` (`id`,`detalle`,`iduser`,`fecha_creacion`,`habilitado`) VALUES (3,'Pendientes',0,'2016-10-11 11:53:21',1);

INSERT INTO `tipo_atencion_formulario` (`id`,`idtipo_atencion`,`idformulario`,`fecha_creacion`,`iduser`,`habilitado`) VALUES (4,3,3,'2016-10-05 16:10:59',0,1);