function cargarSubesp(formulario)
{
    $.ajax({
        data: formulario,
        type: "POST",
        dataType: "json",
        url: "includes/ajaxFunctions/cargarSubespecialidad.php",
        beforeSend:function()
        {
            $('#formSubespecialidad').hide();
            $('#loader').show();
        },
        success: function(data)
        {
            $('#formSubespecialidad').show();
            $('#loader').hide();
            if(data.ret)
            {
                $('#formSubespecialidad').get(0).reset();
                alert(data.message);
            }
            else
            {
                alert(data.message);
            }
        }
    });
}

function validar()
{
    if($('#detalle').val()=='')
    {
        alert("Debe ingresar el detalle para la subespecialidad.");
        return false;
    }
    if($('#idespecialidad').val()=='')
    {
        alert("Debe ingresar la especialidad relacionada.");
        return false;
    }
    
    return true;
}


$(document).ready(function(){

    $('#btnAgregarSubespecialidad').click(function(event){
        event.preventDefault(event);
        var validado = validar();
        if(validado)
        {
            formulario = $('#formSubespecialidad').serialize();
            cargarSubesp(formulario);
        }
    });
    
    $("#jgVerSubesp").jqGrid({ 
        url:'includes/ajaxFunctions/verSubespecialidad.php', 
        mtype: "POST",
        datatype: "json",
        colNames:['id','detalle','idespecialidad','habilitado','usuario',''],
        colModel:[ 
            {name:'id', index:'sub.id',width:'100%',align:"left",fixed:true,editable:false},
            {name:'detalle', index:'sub.detalle',width:'100%',align:"center",fixed:true,editable:true},
            {name:'idespecialidad', index:'sub.idespecialidad',width:'100%',align:"left",fixed:true, editable:true},
            {name:'habilitado', index:'sub.habilitado',width:'100%',align:"left",fixed:true, editable:true},
            {name:'idusuario', index:'sub.idusuario',width:'100%',align:"left",fixed:true, editable:false},
            {name: 'myac', width: '40%', fixed: true, sortable: false, resize: false, formatter: 'actions', search: false,
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
        pager: '#jqSubespfoot',
        sortname: 'sub.id',
        sortorder: "desc",
        editurl :'includes/ajaxFunctions/modificarSubespecialidad.php',
        width: '100%',
        height: '100%',
        gridComplete: function()
        { 
            /*var ids = jQuery("#jgVerSubesp").jqGrid('getDataIDs'); 
            for(var i=0;i < ids.length;i++)
            { 
                var cl = ids[i];
                be = "<input style='height:22px;width:35px;' class='button-secondary' type='button' value='VER' onclick=\"javascript:detalleReclamo('"+cl+"');\" />";
                be = be + "<input style='height:22px;width:70px;' class='button-secondary' type='button' value='RECLAMOS' onclick=\"javascript:logReclamos('"+cl+"');\" />";
                be = be + "<input style='height:22px;width:35px;' class='button-secondary' type='button' value='MAIL' onclick=\"javascript:mailConcatenado('"+cl+"');\" />";
                jQuery("#jgVerSubesp").jqGrid('setRowData',ids[i],{act:be});
            }*/
        }
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

});