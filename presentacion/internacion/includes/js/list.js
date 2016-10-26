function mostrarFormulario(sel){
    $("#id").val(sel);
    $("#formSeleccionarInternacion").attr('action',"formulario.php");
    $("#formSeleccionarInternacion").submit();
}

$(document).ready(function(){

    $("#jgVerInternaciones").jqGrid({
        url:'includes/ajaxFunctions/jsonInternaciones.php?anio='+anio+'&mes='+mes,
        mtype: "POST",
        datatype: "json",
        colNames:['Nro' ,'TipoDoc' ,'NroDoc', 'Nombre', 'Ingreso', 'Motivo', 'Sector', 'Diagnostico', 'Obra Social', ''],
        colModel:[ 
            {name:'nro', index:'id',width:'50%',align:"left",fixed:true,editable:false },
            {name:'tipodoc', index:'td.id',width:'50%',align:"left",fixed:true,editable:false },
            {name:'nrodoc', index:'i.nrodoc',width:'70%',align:"left",fixed:true,editable:false },
            {name:'nombre', index:'p.nombre',width:'100%',align:"left",fixed:true,editable:false },
            {name:'fecha_creacion', index:'i.fecha_creacion',width:'100%',align:"left",fixed:true,editable:false },
            {name:'motivo_ingreso', index:'i.motivo_ingreso',width:'150%',align:"left",fixed:true,editable:false },
            {name:'sector', index:'s.detalle',width:'100%',align:"left",fixed:true,editable:false },
            {name:'diagnostico', index:'d.descripcion',width:'150%',align:"left",fixed:true, editable:false},
            {name:'obra_social', index:'o.detalle',width:'150%',align:"left",fixed:true, editable:false },
            {name:'act',index:'act', width:'50%', sortable:false,align:"center",search:false, fixed:true,editable:false}
        ],
        rowNum: true,
        viewrecords: true,
        altRows: true,
        caption: "Internaciones",
        rowNum: 20,
        rowList: [10,20,30,50],
        pager: '#jgVerInternacionesFoot',
        sortname: 'id',
        sortorder: "desc",
        width: '100%',
        height: '100%',
        gridComplete: function()
        {
            var ids = jQuery("#jgVerInternaciones").jqGrid('getDataIDs');
            for(var i=0;i < ids.length;i++)
            {
                var cl = ids[i];
                be = "<input class='button-secondary2' value='ver' type='button' onclick=\"javascript:mostrarFormulario('"+cl+"');\" />";
                jQuery("#jgVerInternaciones").jqGrid('setRowData',ids[i],{act:be});
            }
        }
    });

    $('#jgVerInternaciones').jqGrid('navGrid', '#jgVerInternacionesFoot', {
        edit:false,
        add:false,
        del:false,
        trash:false,
        search:false
    });

    $("#jgVerInternaciones").jqGrid('filterToolbar', {
        stringResult: true,
        searchOnEnter: false,
        defaultSearch : "cn"
    });
});