#Creacion de tablas cama y sector



#Actualizacion permisos para internacion

INSERT INTO `turnos`.`permiso` (`idpermiso`, `detalle`, `entidad`) VALUES ('ABM_CAMA', 'Alta, Baja y Modificacion de Camas Internacion', 'TODOS');
INSERT INTO `turnos`.`permiso` (`idpermiso`, `detalle`, `entidad`) VALUES ('ABM_SECTOR', 'Alta, Baja y Modificacion de Sectores Internacion', 'TODOS');
INSERT INTO `turnos`.`permiso` (`idpermiso`, `detalle`, `entidad`) VALUES ('INTERNACION', 'Visualizar Internacion', 'TODOS');

#Permisos de pantalla

INSERT INTO `turnos`.`permiso` (`idpermiso`, `detalle`, `entidad`) VALUES ('VER_TURNEROS', 'Ver Pantallas de llamados', 'TODOS');
INSERT INTO `turnos`.`permiso` (`idpermiso`, `detalle`, `entidad`) VALUES ('EDITAR_TURNEROS', 'Editar Pantallas de llamados', 'TODOS');
INSERT INTO `turnos`.`permiso` (`idpermiso`, `detalle`, `entidad`) VALUES ('ELIMINAR_TURNERO', 'Eliminar Pantallas de llamados', 'TODOS');
INSERT INTO `turnos`.`permiso` (`idpermiso`, `detalle`, `entidad`) VALUES ('NUEVO_TURNERO', 'Alta Pantalla de llamados', 'TODOS');