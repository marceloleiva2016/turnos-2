function mostrarFormulario(sel){
    $("#id").val(sel);
    $("#formSeleccionarInternacion").attr('action',"formulario.php");
    $("#formSeleccionarInternacion").submit();
}

$(document).ready(function(){

    $("#jgVerTurnosAtendidos").jqGrid({
        url:'includes/ajaxFunctions/jsonTurnos.php?anio='+anio+'&mes='+mes,
        mtype: "POST",
        datatype: "json",
        colNames:['Nro' ,'Tipo Atencion' ,'Fecha' ,'Hora' ,'Subespedialidad' ,'Profesional' ,'Estado Turno' ,'TipoDoc' ,'NroDoc', 'Nombre', 'Sexo', 'Fecha Nac', 'Partido', 'Obra Social'],
        colModel:[
            {name:'id', index:'t.id', width:'20%', align:'left', fixed:true, editable:false },
            {name:'tipo_atencion', index:'ta.detalle', width:'80%', align:'left', fixed:true, editable:false },
            {name:'fecha', index:'t.fecha', width:'80%', align:'left', fixed:true, editable:false },
            {name:'hora', index:'t.hora', width:'60%', align:'left', fixed:true, editable:false },
            {name:'subespecialidad', index:'s.detalle', width:'120%', align:'left', fixed:true, editable:false },
            {name:'profesional', index:'prof.apellido', width:'110%', align:'left', fixed:true, editable:false },
            {name:'estado', index:'et.detalle', width:'100%', align:'left', fixed:true, editable:false },
            {name:'tipodoc', index:'td.detalle_corto', width:'50%', align:'left', fixed:true, editable:false },
            {name:'nrodoc', index:'p.nrodoc', width:'80%', align:'left', fixed:true, editable:false },
            {name:'nombre', index:'p.apellido', width:'110%', align:'left', fixed:true, editable:false },
            {name:'sexo', index:'p.sexo', width:'30%', align:'left', fixed:true, editable:false },
            {name:'fecha_nacimiento', index:'p.fecha_nacimiento', width:'70%', align:'left', fixed:true, editable:false },
            {name:'partido', index:'vpa.descripcion', width:'90%', align:'left', fixed:true, editable:false },
            {name:'obra_social', index:'o.detalle', width:'200%', align:'left', fixed:true, editable:false }
        ],
        rowNum: true,
        viewrecords: true,
        altRows: true,
        caption: "<a href='includes/ajaxFunctions/turnosToExcel.php' >Exportar Excel</a>",
        rowNum: 20,
        rowList: [10,20,30,50],
        pager: '#jgVerTurnosAtendidosFoot',
        sortname: 'id',
        sortorder: "desc",
        width: '100%',
        height: '100%'
    });

    $('#jgVerTurnosAtendidos').jqGrid('navGrid', '#jgVerTurnosAtendidosFoot', {
        edit:false,
        add:false,
        del:false,
        trash:false,
        search:false
    });

    $("#jgVerTurnosAtendidos").jqGrid('filterToolbar', {
        stringResult: true,
        searchOnEnter: false,
        defaultSearch : "cn"
    });
});