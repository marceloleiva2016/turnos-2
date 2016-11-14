<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'usuarioDatabaseLinker.class.php';
include_once datos.'centroDatabaseLinker.class.php';
include_once conexion.'conectionData.php';

session_start();

$obj = new UsuarioDatabaseLinker();

$centDB = new CentroDatabaseLinker();

$entidad = $_POST['entidad'];

$permisos = $obj->traerPermisos($entidad);

$centros = $centDB->getCentros(Business);

?>
<script type="text/javascript">

	function validar()
	{
		if(document.forms["agregarUsuarioform"]["nombre"].value=="" )
		{
			alert("Debe Ingresar el nombre completo");
			return false;
		}
		if(document.forms["agregarUsuarioform"]["detalle"].value=="" )
		{
			alert("Debe Ingresar el nombre de usuario");
			return false;
		}
		if(document.forms["agregarUsuarioform"]["contrasena"].value=="" )
		{
			alert("Debe Ingresar la contraseña");
			return false;
		}
		if(document.forms["agregarUsuarioform"]["contra2"].value=="" )
		{
			alert("Debe Reingresar la contraseña");
			return false;
		}
		
		var acceso= document.getElementsByName('accesos[]');
		var hasChecked=false;
		for(var i=0; i< acceso.length; i++)
		{
			if(acceso[i].checked)
			{
				hasChecked=true;
				break;
			}
		}
		if(hasChecked==false)
		{
			alert("Debe ingresar al menos una pantalla de acceso");
			return false;
		}

        var accesoCentros= document.getElementsByName('accesosCentros[]');
        var hasChecked=false;
        for(var i=0; i< accesoCentros.length; i++)
        {
            if(accesoCentros[i].checked)
            {
                hasChecked=true;
                break;
            }
        }
        if(hasChecked==false)
        {
            alert("Debe seleccionar al menos un centro donde va a operar el usuario");
            return false;
        }

		return true;
	}

	$("#guardarUser").click(function(event){
 		event.preventDefault();
 		if(validar())
 		{
            $.ajax({
              	data: $('#agregarUsuarioform').serialize(),
              	type: "POST",
              	dataType: "json",
              	url: "includes/ajaxFunctions/ingresarUsuario.php",
             	success: function(data)
              	{
            		alert(data.message);
            		if(data.ret)
            		{
            			$('#agregarUsuarioform').get(0).reset();	
            		}
              	}
            });
        }

    }); 

</script>

<form id="agregarUsuarioform">

	<a>Nombre Completo: </a><input class="input"  name="nombre" placeholder="Apellido y nombre"></br>

	<a>Alias de usuario: </a><input class="input" name="detalle" placeholder="Identificacion de Usuario"></br>

	<a>Contrase&ntilde;a: </a><input class="input" type="password" name="contrasena" placeholder="Password"></br>

	<a>Vuelva a ingresar: </a><input class="input" type="password" name="contra2" placeholder="Reingresar"></br>

	<a>Este Usuario puede tener permiso a</a></br>

    <div class="inputsScroll">

	    <?php
	    for ($i=0; $i < count($permisos); $i++) 
	    { 
    		echo "<input type='checkbox' name='accesos[]' value=".$permisos[$i]['idpermiso'].">".$permisos[$i]['detalle']."</br>";
    	}
    	?>

    </div><br>

    <a>En los siguientes centros...</a></br>

    <div class="inputsScroll">
        <?php
        for ($i=0; $i < count($centros); $i++) 
        { 
            echo "<input type='checkbox' name='accesosCentros[]' value=".$centros[$i]->getId().">".$centros[$i]->getDetalle()."</br>";
        }
        ?>

    </div>

	<input type="hidden" name="entidad" value='<?php echo $entidad ?>' >

	<input class='button-secondary' type="submit" id="guardarUser" value="Guardar" >

</form>