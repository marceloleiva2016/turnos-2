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
                            data: $("#formCama").serialize(),
                            type: "POST",
                            dataType: "json",
                            url: "includes/ajaxFunctions/"+paginaFuncion,
                            success: function(data) {
                                if(data.ret) {
                                    $('#formCama').get(0).reset();
                                    $('#jqVerCamas').trigger("reloadGrid");
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

    function checkvalid(value, colname)
    {
        var gr = jQuery("#jqVerCamas").getGridParam('selarrrow');

        if(gr=='')
        {
            var flag = false;
            var allRowsInGrid = $("#jqVerCamas").getGridParam("records");

            var ids = jQuery("#jqVerCamas").jqGrid('getDataIDs');
            for(var i=0;i < ids.length;i++)
            {
                var rowId = $("#jqVerCamas").getRowData(ids[i]);
                var detId = rowId['nro_cama'];
                console.log(detId);
                if(detId == value)
                {
                    flag = true;
                }
            }

            if(flag == true)
                return [false, "El numero de cama ya existe"];
            else
                return [true,""];
        }
        else
        {
            return [true,""]; 
        }
    }

    $("#jqVerCamas").jqGrid({ 
        url:'includes/ajaxFunctions/verCamas.php', 
        mtype: "POST",
        datatype: "json",
        colNames:['Nro Cama','Sector',''],
        colModel:[ 
            {name:'nro_cama', index:'nro_cama',width:'250%',align:"center",fixed:true,editable:true,
                editrules: {
                    custom: true,
                    custom_func: checkvalid
                }
            },
            {name:'idsector', index:'idsector',width:'200%',align:"left",fixed:true,search: true, editable:true, edittype:"select",
                searchoptions :{
                    value: ":TODOS;"+sectoresLista
                },
                stype: "select",
                editoptions :{
                    value: sectoresLista
                }
            },
            {name: 'myac', width: '50%', fixed: true, sortable: false, resize: false, formatter: 'actions', search: false,
                formatoptions: {
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
        pager: '#jqCamasFooter',
        sortname: 'id',
        sortorder: "desc",
        editurl :'includes/ajaxFunctions/modificarCama.php',
        width: '100%',
        height: '100%'
    });

    $('#jqVerCamas').jqGrid('navGrid', '#jqCamasFooter', {
        edit:false,
        add:false,
        del:false,
        trash:false,
        search:false
    });

     jQuery("#jqVerCamas").jqGrid('filterToolbar', {
        stringResult: true, 
        searchOnEnter: false, 
        defaultSearch : "cn"
    }); 

    $("#nuevaCama").click(function(event){
        event.preventDefault(event);
        mostrarDialogo("nuevaCama.php", "cargarCama.php");
    });
});