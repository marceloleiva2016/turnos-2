$(document).ready(function(){
/*
    $("#jgVerAtencion").jqGrid({ 
        url:'includes/ajaxFunctions/verTurnosConfirmados.php', 
        mtype: "POST",
        datatype: "json",
        colNames:['TipoDoc', 'NroDoc','Paciente','Tipo Turno','Ingreso',''],
        colModel:[ 

            {name:'TipoDoc', index:'t.tipodoc',width:'50%',align:"left",fixed:true,editable:true},
            {name:'NroDoc', index:'t.nrodoc',width:'70%',align:"center",fixed:true,editable:true},
            {name:'Paciente', index:'p.nombre',width:'250%',align:"center",fixed:true,editable:true},
            {name:'tipo_turno', index:'ct.detalle',width:'100%',align:"center",fixed:true,editable:true},
            {name:'ingreso', index:'t.fecha_creacion',width:'100%',align:"center",fixed:true,editable:true},
            {name:'act',index:'act', width:'100%', sortable:false,align:"center",search:false, fixed:true,editable:true}
        ],
        rowNum:true,
        viewrecords: true,
        altRows : true,
        caption:"Lista turnos En Espera",
        rowNum:20, 
        rowList:[10,20,30,50],
        pager: '#jgVerAtencionfoot',
        sortname: 't.fecha_creacion',
        sortorder: "desc",
        width: '100%',
        height: '100%',
        gridComplete: function()
        { 
            var ids = jQuery("#jgVerAtencion").jqGrid('getDataIDs'); 
            for(var i=0;i < ids.length;i++)
            { 
                var cl = ids[i];
                be = "<input style='height:22px;width:100px;' type='button' value='ATENDER' onclick=\"javascript:mostrarFormulario('"+cl+"');\" />";
                $("#jgVerAtencion").jqGrid('setRowData',ids[i],{act:be});
            }
        }
    });


    $('#jgVerAtencion').jqGrid('navGrid', '#jgVerAtencionfoot', {
        edit:false,
        add:false,
        del:false,
        trash:false,
        search:false
    });

    $("#jgVerAtencion").jqGrid('filterToolbar', {
        stringResult: true, 
        searchOnEnter: false, 
        defaultSearch : "cn"
    });
*/
});

function mostrarFormulario(sel)
{
    $("#id").val(sel);
    $("#frmSeleccionarPaciente").attr('action',"formulario.php");
    $("#frmSeleccionarPaciente").submit();
}