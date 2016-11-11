
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