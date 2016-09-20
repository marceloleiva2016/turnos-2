function mostrarDialogo(paginaVista, paginaFuncion)
{
    $("#dialog:ui-dialog").dialog("destroy");
    $("#dialog-message").css('visibility',"visible");
    $("#dialogSubEsp").load("includes/forms/" + paginaVista,function() {
        $("#dialogSubEsp" ).dialog({
            modal: true,
            width: $("#divPrincipal").width()+100,
            title: $("#divPrincipal").attr('title'),
            buttons: {
                "Aceptar": function(){
                    frmOk = validar();
                    if(frmOk) {
                        $.ajax({
                            data: $("#formSubespecialidad").serialize(),
                            type: "POST",
                            dataType: "json",
                            url: "includes/ajaxFunctions/"+paginaFuncion,
                            success: function(data) {
                                if(data.ret) {
                                    $('#formSubespecialidad').get(0).reset();
                                    $('#jgVerSubesp').trigger("reloadGrid");
                                    if(data.show) {
                                        alert(data.message);
                                    }
                                    //relodeo la tabla
                                } else {
                                    alert(data.message);
                                }
                                
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

    $("#jgVerSubesp").jqGrid({ 
        url:'includes/ajaxFunctions/verSubespecialidad.php', 
        mtype: "POST",
        datatype: "json",
        colNames:['nro','detalle','especialidad',''],
        colModel:[ 
            {name:'id', index:'sub.id',width:'40%',align:"left",fixed:true,editable:false},
            {name:'detalle', index:'sub.detalle',width:'300%',align:"center",fixed:true,editable:true},
            {name:'especialidad', index:'esp.detalle',width:'100%',align:"left",fixed:true, editable:false},
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
        caption:"Subespecialidades",
        rowNum:20, 
        rowList:[10,20,30,50],
        pager: '#jqSubespfoot',
        sortname: 'sub.id',
        sortorder: "desc",
        editurl :'includes/ajaxFunctions/modificarSubespecialidad.php',
        width: '100%',
        height: '100%'
    });

    $('#jgVerSubesp').jqGrid('navGrid', '#jqSubespfoot', {
        edit:false,
        add:false,
        del:false,
        trash:false,
        search:false
    });

     jQuery("#jgVerSubesp").jqGrid('filterToolbar', {
        stringResult: true, 
        searchOnEnter: false, 
        defaultSearch : "cn"
    }); 

    $("#nuevaSubespecialidad").click(function(event){
        event.preventDefault(event);
        mostrarDialogo("nuevaSubespecialidad.php", "cargarSubespecialidad.php");
    });
});