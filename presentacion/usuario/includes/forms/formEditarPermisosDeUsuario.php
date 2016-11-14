<?php
include_once '../../../../namespacesAdress.php';
include_once conexion.'conectionData.php';
include_once datos.'usuarioDatabaseLinker.class.php';

$obj = new UsuarioDatabaseLinker();

$acceso = $obj->traerPermisosDelUsuario($_REQUEST['idusuario'],$_POST['entidad']);

$accesoCentros = $obj->traerPermisosCentrosUsuario($_REQUEST['idusuario'], Business);

?>

<div class="post" id="wrapper">
	
	<div id="page">

		<form id="editarPermisosUsuarioform">
                    
            <input type="hidden" name="idusuario" value="<?=$_REQUEST['idusuario']?>" />

			<a>Este Usuario puede tener acceso a ...</a></br>
            <div class="inputsScroll">

			<?php
			for ($i = 0; $i < count($acceso); $i++) 
            { 
                $idpermiso = $acceso[$i]['idpermiso'];
                $checked = $acceso[$i]['tiene'] ? "checked='checked'" : "";
                $detalle = $acceso[$i]['detalle'];
                
                echo "<input type='checkbox' name='accesos[]' value='".$idpermiso."' ".$checked." />";
                echo $detalle;
                echo '</br>';
			}
			?>

            </div><br>

            <a>En los siguientes centros...</a></br>

            <div class="inputsScroll">

                <?php
                for ($x = 0; $x < count($accesoCentros); $x++) 
                { 
                    $idcentro = $accesoCentros[$x]['idcentro'];
                    $checked = $accesoCentros[$x]['tiene'] ? "checked='checked'" : "";
                    $detalle = $accesoCentros[$x]['detalle'];
                    
                    echo "<input type='checkbox' name='accesosCentros[]' value='".$idcentro."' ".$checked." />";
                    echo $detalle;
                    echo '</br>';
                }
                ?>

            </div>

		</form>

	</div>

</div>