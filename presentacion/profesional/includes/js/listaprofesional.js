function mostrarDialogo(paginaVista, paginaFuncion)
{
    $("#dialog:ui-dialog").dialog("destroy");
    $("#dialog-message").css('visibility',"visible");
    $("#dialogProf").load("includes/forms/" + paginaVista,function() {
        $("#dialogProf" ).dialog({
            modal: true,
            width: $("#divPrincipal").width()+100,
            title: $("#divPrincipal").attr('title'),
            buttons: {
                "Aceptar": function(){
                    frmOk = validar();
                    if(frmOk) {
                        $.ajax({
                            data: $("#formProfesional").serialize(),
                            type: "POST",
                            dataType: "json",
                            url: "includes/ajaxFunctions/"+paginaFuncion,
                            success: function(data) {
                                if(data.result) {
                                    $('#formProfesional').get(0).reset();
                                    $("#jgVerProf").trigger("reloadGrid"); 
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

    $("#jgVerProf").jqGrid({ 
        url:'includes/ajaxFunctions/verProfesional.php', 
        mtype: "POST",
        datatype: "json",
        colNames:['Nro','Nombre','Apellido','Mat Nacional','Mat Provincial','Email','Telefono','Usuario'],
        colModel:[ 
            {name:'idprofesional', index:'pro.idprofesional',width:'30%',align:"left",fixed:true,editable:true},
            {name:'nombre', index:'pro.nombre',width:'100%',align:"left",fixed:true,editable:true},
            {name:'apellido', index:'pro.apellido',width:'100%',align:"left",fixed:true, editable:true},
            {name:'matricula_n', index:'pro.matricula_nacional',width:'100%',align:"left",fixed:true, editable:true},
            {name:'matricula_p', index:'pro.matricula_provincial',width:'100%',align:"left",fixed:true, editable:true},
            {name:'email', index:'pro.email',width:'100%',align:"left",fixed:true, editable:true},
            {name:'telefono', index:'pro.telefono',width:'100%',align:"left",fixed:true, editable:true},
            {name:'idusuario', index:'pro.idusuario',width:'100%',align:"left",fixed:true, editable:true}
        ],
        rowNum:true,
        viewrecords: true,
        altRows : true,
        caption:"Profesionales",
        rowNum:20, 
        rowList:[10,20,30,50],
        pager: '#jqProffoot',
        sortname: 'pro.idprofesional',
        sortorder: "desc",
        width: '100%',
        height: '100%'
    });


    $('#jgVerProf').jqGrid('navGrid', '#jqProffoot', {
        edit:false,
        add:false,
        del:false,
        trash:false,
        search:false
    });

    $("#jgVerProf").jqGrid('filterToolbar', {
        stringResult: true,
        searchOnEnter: false,
        defaultSearch : "cn"
    });

    $("#nuevoProfesional").click(function(event){
        event.preventDefault(event);
        mostrarDialogo("nuevoProfesional.php", "cargarProfesional.php");
    });
});