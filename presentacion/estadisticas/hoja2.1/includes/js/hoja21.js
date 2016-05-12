$(document).ready(function(){

    $("#jqVerEst21").jqGrid({ 
        url:'includes/ajaxFunctions/ajaxDatosHoja21.php?mes='+mes+'&ano='+ano,
        mtype: "POST",
        datatype: "json",
        colNames:['Especialidad', '-1 M', '-1 F','1-4 M', '1-4 F', '5-9 M', '5-9 F', '10-14 M', '10-14 F', '15-19 M', '15-19 F', '20-34 M','20-34 F', '35-49 M', '35-49 F', '50-64 M', '50-64 F', '65+ M', '65+ F', 'TOTAL'],
        colModel:[ 

            {name:'nombre', index:'nombre',width:'150%',align:"left",fixed:true},
            {name:'lista.0.M', index:'lista.0.M',width:'40%',align:"center",fixed:true},
            {name:'lista.0.F', index:'lista.0.F',width:'40%',align:"center",fixed:true},
            {name:'lista.1.M', index:'lista.1.M',width:'40%',align:"center",fixed:true},
            {name:'lista.1.F', index:'lista.1.F',width:'40%',align:"center",fixed:true},
            {name:'lista.2.M', index:'lista.2.M',width:'40%',align:"center",fixed:true},
            {name:'lista.2.F', index:'lista.2.F',width:'40%',align:"center",fixed:true},
            {name:'lista.3.M', index:'lista.3.M',width:'40%',align:"center",fixed:true},
            {name:'lista.3.F', index:'lista.3.F',width:'40%',align:"center",fixed:true},
            {name:'lista.4.M', index:'lista.4.M',width:'40%',align:"center",fixed:true},
            {name:'lista.4.F', index:'lista.4.F',width:'40%',align:"center",fixed:true},
            {name:'lista.5.M', index:'lista.5.M',width:'40%',align:"center",fixed:true},
            {name:'lista.5.F', index:'lista.5.F',width:'40%',align:"center",fixed:true},
            {name:'lista.6.M', index:'lista.6.M',width:'40%',align:"center",fixed:true},
            {name:'lista.6.F', index:'lista.6.F',width:'40%',align:"center",fixed:true},
            {name:'lista.7.M', index:'lista.7.M',width:'40%',align:"center",fixed:true},
            {name:'lista.7.F', index:'lista.7.F',width:'40%',align:"center",fixed:true},
            {name:'lista.8.M', index:'lista.8.M',width:'40%',align:"center",fixed:true},
            {name:'lista.8.F', index:'lista.8.F',width:'40%',align:"center",fixed:true},
            {name:'cantidad', index:'cantidad',width:'50%',align:"center",fixed:true},
        ],

        rowTotals: true,
        colTotals: true,
        rowNum:true,
        viewrecords: true,
        altRows : true,
        caption:"RESUMEN MENSUAL DE PACIENTES ATENDIDOS",
        rowNum:20, 
        rowList:[10,20,30,50],
        pager: '#jgVerAtencionfoot',
        sortname: '',
        sortorder: "desc",
        width: '100%',
        height: '100%'
    });

    $('#jqVerEst21').jqGrid('navGrid', '#jqVerEst21foot', {
        edit:false,
        add:false,
        del:false,
        trash:false,
        search:false
    });

});