
UPDATE `turnos`.`estado_turno` SET `detalle`='ASIGNADO' WHERE `id`='1';
UPDATE `turnos`.`estado_turno` SET `detalle`='CONFIRMADO' WHERE `id`='2';
UPDATE `turnos`.`estado_turno` SET `detalle`='ATENDIDO' WHERE `id`='3';
INSERT INTO `turnos`.`estado_turno` (`id`, `detalle`) VALUES ('4', 'NO ASISTIDO');
INSERT INTO `turnos`.`estado_turno` (`id`, `detalle`) VALUES ('5', 'NO ATENDIDO');
INSERT INTO `turnos`.`estado_turno` (`id`, `detalle`) VALUES ('6', 'ELIMINADO');

CREATE TABLE `turnos`.`postit` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `idusuario` INT NULL,
  `descripcion` LONGTEXT NULL,
  `fecha_creacion` DATETIME NULL,
  `habilitado` TINYINT(1) NULL,
PRIMARY KEY (`id`));

#actualizar los centros de los usuarios
INSERT INTO centro_usuario  (idcentro, idusuario)
        SELECT 
            1,
            idusuario
        FROM
                usuario
        WHERE
            habilitado=true;

CREATE TABLE `centro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_centro` varchar(45) DEFAULT NULL,
  `detalle` varchar(45) DEFAULT NULL,
  `direccion` varchar(45) DEFAULT NULL,
  `telefono` varchar(45) DEFAULT NULL,
  `idempresa` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `habilitado` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `centro_usuario` (
  `idcentro` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  PRIMARY KEY (`idcentro`,`idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `configuracion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idcentro` int(11) DEFAULT NULL,
  `color` varchar(45) DEFAULT NULL,
  `nombre_logo` varchar(45) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `habilitado` tinyint(1) DEFAULT NULL,
  `idusuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `empresa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `detalle` varchar(45) DEFAULT NULL,
  `telefono` varchar(45) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `habilitado` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;