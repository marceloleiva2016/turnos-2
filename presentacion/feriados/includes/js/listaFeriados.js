function mostrarDialogo(paginaVista, paginaFuncion)
{
    $("#dialog:ui-dialog").dialog("destroy");
    $("#dialog-message").css('visibility',"visible");
    $("#dialogFer").load("includes/forms/" + paginaVista,function(){
        $("#dialogFer" ).dialog({
            modal: true,
            width: 400,
            title: $("#divPrincipal").attr('title'),
            buttons: {
                "Aceptar": function(){
                    frmOk = validar();
                    if(frmOk) {
                        $.ajax({
                            data: $("#formFeriado").serialize(),
                            type: "POST",
                            dataType: "json",
                            url: "includes/ajaxFunctions/"+paginaFuncion,
                            success: function(data) {
                                $('#formFeriado').get(0).reset();
                                $("#jqVerFer").trigger("reloadGrid"); 
                                alert(data.message);
                                
                            }
                        });
                        $(this).dialog("close");
                    }
                },
                "Cerrar":function() {
                    $(this).dialog("close");
                }
            }
        });
    });
}

$(document).ready(function(){

    $("#jqVerFer").jqGrid({
        url:'includes/ajaxFunctions/verFeriados.php', 
        mtype: "POST",
        datatype: "json",
        colNames:['Nro','Fecha', 'Afectado',''],
        colModel:[ 
            {name:'nro', index:'et.id',width:'50%',align:"center",fixed:true, search:false},
            {name:'fecha', index:'et.fecha',width:'100%',align:"center",fixed:true, search:false},
            {name:'afectado', index:'et.afectado',width:'200%',align:"center",fixed:true, search:false},
            {name: 'myac', width: '40%', fixed: true, sortable: false, resize: false, formatter: 'actions', search: false,
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
        caption:"Feriados",
        rowNum:10, 
        rowList:[10,20,30,50],
        pager: '#jqFerFoot',
        sortname: 'et.id',
        sortorder: "asc",
        editurl :'includes/ajaxFunctions/borrarFeriado.php',
        width: '100%',
        height: '100%'
    });


    $('#jqVerFer').jqGrid('navGrid', '#jqFerFoot', {
        edit:false,
        add:false,
        del:false,
        trash:false,
        search:false
    });
    
    $("#nuevoFeriado").click(function(event){
        event.preventDefault(event);
        mostrarDialogo("nuevoFeriado.php", "cargarFeriado.php");
    });
});