function mostrarDialogo(paginaVista, paginaFuncion)
{
    $("#dialog:ui-dialog").dialog("destroy");
    $("#dialog-message").css('visibility',"visible");
    $("#dialogEsp").load("includes/forms/" + paginaVista,function() {
        $("#dialogEsp" ).dialog({
            modal: true,
            width: $("#divPrincipal").width()+100,
            title: $("#divPrincipal").attr('title'),
            buttons: {
                "Aceptar": function(){
                    frmOk = validar();
                    if(frmOk) {
                        $.ajax({
                            data: $("#formEspecialidad").serialize(),
                            type: "POST",
                            dataType: "json",
                            url: "includes/ajaxFunctions/"+paginaFuncion,
                            success: function(data) {
                                if(data.result) {
                                    $('#formEspecialidad').get(0).reset();
                                    $("#jqVerEsp").trigger("reloadGrid"); 
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

$(document).ready(function(){

    $("#jqVerEsp").jqGrid({ 
        url:'includes/ajaxFunctions/verEspecialidad.php', 
        mtype: "POST",
        datatype: "json",
        colNames:['Nro','Nombre'],
        colModel:[ 
            {name:'idespecialidad', index:'idespecialidad',width:'50%',align:"left",fixed:true,editable:true},
            {name:'nombre', index:'detalle',width:'200%',align:"left",fixed:true,editable:true}
        ],
        rowNum:true,
        viewrecords: true,
        altRows : true,
        caption:"Especialidades",
        rowNum:20, 
        rowList:[10,20,30,50],
        pager: '#jqEspFoot',
        sortname: 'idespecialidad',
        sortorder: "desc",
        width: '100%',
        height: '100%'
    });


    $('#jqVerEsp').jqGrid('navGrid', '#jqEspFoot', {
        edit:false,
        add:false,
        del:false,
        trash:false,
        search:false
    });

    $("#jqVerEsp").jqGrid('filterToolbar', {
        stringResult: true,
        searchOnEnter: false,
        defaultSearch : "cn"
    });

    $("#nuevaEspecialidad").click(function(event){
        event.preventDefault(event);
        mostrarDialogo("nuevaEspecialidad.php", "cargarEspecialidad.php");
    });
});