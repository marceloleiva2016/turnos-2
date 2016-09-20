<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'turneroDatabaseLinker.class.php';
include_once datos.'usuarioDatabaseLinker.class.php';
include_once datos.'consultorioDatabaseLinker.class.php';
include_once negocio.'turnero.class.php';
include_once negocio.'usuario.class.php';

session_start();

if(!isset($_SESSION['usuario']))
{
    //echo "WHOOPSS, No se encontro ningun usuario registrado";
    header("Location: ../index.php?logout=1");
}

$dbTurnero = new TurneroDatabaseLinker();
$dbConsultorio = new ConsultorioDatabaseLinker();

$usuario = $_SESSION['usuario'];

$data = unserialize($usuario);
/*fin de agregado usuario*/

//Valido lo requerido
if($_REQUEST['action']=="new"){
    $id = $dbTurnero->crearTurnero($data->getId());    
} elseif($_REQUEST['action']=="edit") {
    $id = $_REQUEST['idturnero'];
} else {
    echo "no se registro accion";
    die();
}

$consultorios = $dbConsultorio->getConsultoriosConFiltro("");
?>
<script type="text/javascript">

var idturnero = <?=$id?>;

$(document).ready(function(){

    $("#jqVerConsultoriosEnTurnero").jqGrid({
        url:'includes/ajaxFunctions/jsonConsultoriosEnTurnero.php?idturnero='+idturnero, 
        mtype: "POST",
        datatype: "json",
        colNames:['Nro','Tipo','Profesional','Subespecialidad','Especialidad',''],
        colModel:[ 
            {name:'id', index:'id',width:'50%',align:"left",fixed:true,editable:false},
            {name:'tipo', index:'idtipoconsultorio',width:'160%',align:"left",fixed:true,editable:false},
            {name:'profesional', index:'idprofesional',width:'160%',align:"left",fixed:true,editable:false},
            {name:'subespecialidad', index:'idsubespecialidad',width:'160%',align:"left",fixed:true,editable:false},
            {name:'especialidad', index:'idespecialidad',width:'160%',align:"left",fixed:true,editable:false},
            {name:'myac', width: '50%', fixed: true, sortable: false, resize: false, formatter: 'actions', search: false,
                formatoptions:
                {
                    keys: true,
                    delbutton: true,
                    editbutton: false,
                    onError: function(_, xhr) {
                        alert(xhr.responseText);
                    }
                }
            }
        ],
        rowNum:true,
        viewrecords: true,
        altRows : true,
        rowNum:20, 
        rowList:[10,20,30,50],
        pager: '#jqTurFooter',
        sortname: 'id',
        sortorder: "desc",
        editurl :'includes/ajaxFunctions/jsonEliminarConsultorioEnTurnero.php?idturnero='+idturnero,
        width: 800,
        height: 100
    });

    $('#jqVerConsultoriosEnTurnero').jqGrid('navGrid', '#jqTurFooter', {
        edit:false,
        add:false,
        del:false,
        trash:false,
        search:false
    });

    $('#agregarConsultorio').click(function(event){
        event.preventDefault();
        $.ajax({
            data: $("#formAgrCons").serialize(),
            type: "POST",
            dataType: "json",
            url: "includes/ajaxFunctions/ajaxAgregarConsultorioAturnero.php?idturnero="+idturnero,
            success: function(data)
            {
                //NOTIFICACION
                // create the notification
                var notification = new NotificationFx({
                    message : '<span class="icon icon-message"></span><p>'+data.message+'</p>',
                    layout : 'attached',
                    effect : 'bouncyflip',
                    type : 'notice'
                });
                // show the notification
                notification.show();
                //NOTIFICACION
                if(data.result)
                {
                    $('#jqVerConsultoriosEnTurnero').trigger("reloadGrid");
                }
            }
        });
    });

    $('#consFiltrar').click(function(event){
        event.preventDefault();
        var det = $('#det_busq').val();
        $('#idcomponente option').remove();
        $.ajax({
            type:'post',
            dataType:'json',
            url:'includes/ajaxFunctions/jsonComponentes.php',
            data:{Det:det},
            success: function(json)
            {
                componentes = json;
                cargarOptionsComponentes($('#idcomponente'),componentes);
            }
        });
    });

    $('#compNuevo').click(function(event){
        event.preventDefault();
        window.open('../componente/');
    });

    $('#somedialog-close').click(function(event){
        $('#jqTurneros').trigger("reloadGrid");
    });

    $('#somedialog2-close').click(function(event){
        $('#jqTurneros').trigger("reloadGrid");
    });

});
</script>
<!--Titulo-->
<h2>
    <strong>Turnero Nro <?=$id;?></strong>
</h2>
<hr>
<h1>
    <strong>Agregar Consultorios</strong>
</h1>
<br>
<form id="formFiltrcomponente">
    <input name="det_busq" id="det_busq" placeholder="Ej: Prof, Especialidad">
    <button name="consFiltrar" id="consFiltrar" class="button-secondary" ><span class="icon icon-search"></span></button><br>
</form>
<form id="formAgrCons">
    <label>Consultorio</label><br>
    <select id="idconsultorio" name="idconsultorio">
        <?php
        for ($i=0; $i < count($consultorios); $i++) { 
            echo "<option value=".$consultorios[$i]['id']." >".$consultorios[$i]['especialidad']." ".$consultorios[$i]['subespecialidad']." ".$consultorios[$i]['profesional']."(".$consultorios[$i]['tipo_consultorio'].")</option>";
        }
        ?>
    </select>
    <button name="agregarConsultorio" id="agregarConsultorio" class="button-secondary" ><span class="icon icon-edit"> </span> </button><br/>
</form>
<div align="center">
    <table id="jqVerConsultoriosEnTurnero"></table>
    <div id="jqTurFooter"></div>
</div>