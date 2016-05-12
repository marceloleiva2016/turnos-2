$(document).ready(function(){

    $("#jgVerProf").jqGrid({ 
        url:'includes/ajaxFunctions/verProfesional.php', 
        mtype: "POST",
        datatype: "json",
        colNames:['idprofesional','nombre','apellido','nacional','provincial','email','telefono','idusuario','habilitado',''],
        colModel:[ 
            {name:'idprofesional', index:'pro.idprofesional',width:'100%',align:"left",fixed:true,editable:true},
            {name:'nombre', index:'pro.nombre',width:'100%',align:"center",fixed:true,editable:true},
            {name:'apellido', index:'pro.apellido',width:'100%',align:"left",fixed:true, editable:true},
            {name:'matricula_n', index:'pro.matricula_nacional',width:'100%',align:"left",fixed:true, editable:true},
            {name:'matricula_p', index:'pro.matricula_provincial',width:'100%',align:"left",fixed:true, editable:true},
            {name:'email', index:'pro.email',width:'100%',align:"left",fixed:true, editable:true},
            {name:'telefono', index:'pro.telefono',width:'100%',align:"left",fixed:true, editable:true},
            {name:'idusuario', index:'pro.idusuario',width:'100%',align:"left",fixed:true, editable:true},
            {name:'habilitado', index:'pro.habilitado',width:'100%',align:"left",fixed:true, editable:true},
            {name: 'myac', width: '40%', fixed: true, sortable: false, resize: false, formatter: 'actions', search: false,
                formatoptions: 
                {
                    keys: true,
                    delbutton: false,
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
        sortname: 'pro.idprofesional',
        sortorder: "desc",
        editurl :'includes/ajaxFunctions/modificarProfesional.php',
        width: '100%',
        height: '100%',
        gridComplete: function()
        { 
            /*var ids = jQuery("#jgVerProf").jqGrid('getDataIDs'); 
            for(var i=0;i < ids.length;i++)
            { 
                var cl = ids[i];
                be = "<input style='height:22px;width:35px;' class='button-secondary' type='button' value='VER' onclick=\"javascript:detalleReclamo('"+cl+"');\" />";
                be = be + "<input style='height:22px;width:70px;' class='button-secondary' type='button' value='RECLAMOS' onclick=\"javascript:logReclamos('"+cl+"');\" />";
                be = be + "<input style='height:22px;width:35px;' class='button-secondary' type='button' value='MAIL' onclick=\"javascript:mailConcatenado('"+cl+"');\" />";
                jQuery("#jgVerProf").jqGrid('setRowData',ids[i],{act:be});
            }*/
        }
    });


    $('#jgVerProf').jqGrid('navGrid', '#jqProffoot', {
        edit:false,
        add:false,
        del:false,
        trash:false,
        search:false
    });

     jQuery("#jgVerProf").jqGrid('filterToolbar', {
        stringResult: true, 
        searchOnEnter: false, 
        defaultSearch : "cn"
    }); 


});