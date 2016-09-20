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
                                    $(this).dialog("close");
                                    if(data.show) {
                                        //NOTIFICACION
                                        // create the notification
                                        var notification = new NotificationFx({
                                            message : '<span class="icon2 icon-message"></span><p>'+data.mensaje+'</p>',
                                            layout : 'attached',
                                            effect : 'bouncyflip',
                                            type : 'notice'
                                        });
                                        // show the notification
                                        notification.show();
                                        //NOTIFICACION
                                    }
                                    //relodeo la tabla
                                } else {
                                    //NOTIFICACION
                                    // create the notification
                                    var notification = new NotificationFx({
                                        message : '<span class="icon2 icon-message"></span><p>'+data.mensaje+'</p>',
                                        layout : 'attached',
                                        effect : 'bouncyflip',
                                        type : 'notice'
                                    });
                                    // show the notification
                                    notification.show();
                                    //NOTIFICACION
                                }
                                
                            }
                        });
                        
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
        colNames:['Nro','Nombre','Apellido','Mat Nacional','Mat Provincial','Email','Telefono','Usuario','Accion'],
        colModel:[ 
            {name:'idprofesional', index:'p.idprofesional',width:'30%',align:"left",fixed:true,editable:false},
            {name:'nombre', index:'p.nombre',width:'100%',align:"left",fixed:true,editable:true},
            {name:'apellido', index:'p.apellido',width:'100%',align:"left",fixed:true, editable:true},
            {name:'matricula_n', index:'p.matricula_nacional',width:'100%',align:"left",fixed:true, editable:true},
            {name:'matricula_p', index:'p.matricula_provincial',width:'100%',align:"left",fixed:true, editable:true},
            {name:'email', index:'p.email',width:'100%',align:"left",fixed:true, editable:true},
            {name:'telefono', index:'p.telefono',width:'100%',align:"left",fixed:true, editable:true},
            {name:'idusuario', index:'p.idusuario',width:'100%',align:"left",fixed:true,search: false, editable:true, edittype:"select",
                editoptions:{
                    value: usuariosLista
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
        caption:"Profesionales",
        rowNum:20, 
        rowList:[10,20,30,50],
        pager: '#jqProffoot',
        sortname: 'p.idprofesional',
        sortorder: "desc",
        editurl :'includes/ajaxFunctions/modificarProfesional.php',
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