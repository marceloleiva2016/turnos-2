
CREATE TABLE `atencion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipodoc` int(11) DEFAULT NULL,
  `nrodoc` int(11) DEFAULT NULL,
  `idprofesional` int(11) DEFAULT NULL,
  `idsubespecialidad` int(11) DEFAULT NULL,
  `idturno` int(11) DEFAULT NULL,
  `idtipo_atencion` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `idusuario` int(11) DEFAULT NULL,
  `habilitado` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `atencion_formulario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idatencion` int(11) DEFAULT NULL,
  `idformulario` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `iduser` int(11) DEFAULT NULL,
  `habilitado` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `form_demanda` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipodoc` int(11) DEFAULT NULL,
  `nrodoc` int(11) DEFAULT NULL,
  `idatencion` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `idusuario` int(11) DEFAULT NULL,
  `habilitado` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `form_demanda_observaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iddemanda` int(11) DEFAULT NULL,
  `idtipo_observacion` int(11) DEFAULT NULL,
  `detalle` longtext,
  `fecha_ingreso` datetime DEFAULT NULL,
  `iduser` int(11) DEFAULT NULL,
  `habilitado` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `formulario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` text,
  `descripcion` longtext,
  `ubicacion` varchar(45) DEFAULT NULL,
  `esDefault` tinyint(1) DEFAULT NULL,
  `nivel` enum('PRINCIPAL','SECUNDARIO') DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `idusuario` int(11) DEFAULT NULL,
  `habilitado` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `formulario` (`id`,`nombre`,`descripcion`,`ubicacion`,`esDefault`,`nivel`,`fecha_creacion`,`idusuario`,`habilitado`) VALUES (1,'DEMANDA','Formulario Utilizado para la entidad de demanda','/demanda/',1,'PRINCIPAL','2016-03-02 11:33:42',0,1);
INSERT INTO `formulario` (`id`,`nombre`,`descripcion`,`ubicacion`,`esDefault`,`nivel`,`fecha_creacion`,`idusuario`,`habilitado`) VALUES (2,'EPICRISIS','Se Utiliza para la salida de la internacion','/epicrisis/',0,'SECUNDARIO','2016-03-08 12:59:26',0,1);
INSERT INTO `formulario` (`id`,`nombre`,`descripcion`,`ubicacion`,`esDefault`,`nivel`,`fecha_creacion`,`idusuario`,`habilitado`) VALUES (3,'INTERNACION','Tiene los movimientos de cama, cambios de evolucion, etc','/internacion/',1,'PRINCIPAL','2016-03-10 10:02:39',0,1);

CREATE TABLE `formulario_especialidad` (
  `idformulario` int(11) NOT NULL,
  `idespecialidad` int(11) NOT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `idusuario` int(11) DEFAULT NULL,
  `habilitado` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`idformulario`,`idespecialidad`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `tipo_atencion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `detalle` varchar(45) DEFAULT NULL,
  `idusuario` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `tipo_atencion_formulario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idtipo_atencion` int(11) DEFAULT NULL,
  `idformulario` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `iduser` int(11) DEFAULT NULL,
  `habilitado` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `tipo_consultorio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `detalle` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `tipo_turno_tipo_atencion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idtipo_turno` int(11) DEFAULT NULL,
  `idtipo_atencion` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `turno` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipodoc` int(11) DEFAULT NULL,
  `nrodoc` int(11) DEFAULT NULL,
  `idtipo_atencion` int(11) DEFAULT NULL,
  `idestado_turno` int(11) DEFAULT NULL,
  `idconsultorio` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `idusuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `tipo_consultorio` (`id`,`detalle`) VALUES (1,'DEMANDA');
INSERT INTO `tipo_consultorio` (`id`,`detalle`) VALUES (2,'CONSULTORIO');

INSERT INTO `tipo_atencion` (`id`,`detalle`,`idusuario`,`fecha_creacion`) VALUES (1,'DEMANDA',0,'2016-03-10 09:24:00');
INSERT INTO `tipo_atencion` (`id`,`detalle`,`idusuario`,`fecha_creacion`) VALUES (2,'CONSULTORIO',0,'2016-03-10 09:24:00');
INSERT INTO `tipo_atencion` (`id`,`detalle`,`idusuario`,`fecha_creacion`) VALUES (3,'INTERNACION',0,'2016-03-10 09:24:00');