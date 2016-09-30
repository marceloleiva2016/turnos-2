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

CREATE TABLE `internacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idatencion_predecesora` int(11) DEFAULT NULL,
  `motivo_ingreso` longtext,
  `ididiagnostico_ingreso` int(11) DEFAULT NULL,
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


