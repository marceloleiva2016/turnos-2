function mostrarDialogo(paginaVista, paginaFuncion)
{
    $("#dialog:ui-dialog").dialog("destroy");
    $("#dialog-message").css('visibility',"visible");
    $("#dialogSect").load("includes/forms/" + paginaVista,function() {
        $("#dialogSect" ).dialog({
            modal: true,
            width: 700,
            title: $("#divPrincipal").attr('title'),
            buttons: {
                "Aceptar": function(){
                    frmOk = validar();
                    if(frmOk) {
                        $.ajax({
                            data: $("#formSector").serialize(),
                            type: "POST",
                            dataType: "json",
                            url: "includes/ajaxFunctions/"+paginaFuncion,
                            success: function(data) {
                                if(data.ret) {
                                    $('#formSector').get(0).reset();
                                    $('#jqVerSector').trigger("reloadGrid");
                                    //relodeo la tabla
                                }
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

$(document).ready(function() {

    $("#jqVerSector").jqGrid({ 
        url:'includes/ajaxFunctions/verSectores.php', 
        mtype: "POST",
        datatype: "json",
        colNames:['Nro','Detalle','Especialidad',''],
        colModel:[ 
            {name:'id', index:'id',width:'80%',align:"left",fixed:true,editable:false},
            {name:'detalle', index:'detalle',width:'250%',align:"center",fixed:true,editable:true},
            {name:'idespecialidad', index:'especialidad',width:'200%',align:"left",fixed:true,search: false, editable:true, edittype:"select",
                editoptions:{
                    value: especialidadesLista
                }
            },
            {name: 'myac', width: '50%', fixed: true, sortable: false, resize: false, formatter: 'actions', search: false,
                formatoptions: 
                {
                    keys: true,
                    delbutton: true,
                    editbutton: true,
                    onError: function(_, xhr) {
                        alert(xhr.responseText);
                    }
                }
            }
        ],
        rowNum:true,
        viewrecords: true,
        altRows : true,
        caption:"Sectores",
        rowNum:20, 
        rowList:[10,20,30,50],
        pager: '#jqSectFooter',
        sortname: 'id',
        sortorder: "desc",
        editurl :'includes/ajaxFunctions/modificarSector.php',
        width: '100%',
        height: '100%'
    });

    $('#jqVerSector').jqGrid('navGrid', '#jqSectFooter', {
        edit:false,
        add:false,
        del:false,
        trash:false,
        search:false
    });

     jQuery("#jqVerSector").jqGrid('filterToolbar', {
        stringResult: true, 
        searchOnEnter: false, 
        defaultSearch : "cn"
    }); 

    $("#nuevoSector").click(function(event){
        event.preventDefault(event);
        mostrarDialogo("nuevoSector.php", "cargarSector.php");
    });
});