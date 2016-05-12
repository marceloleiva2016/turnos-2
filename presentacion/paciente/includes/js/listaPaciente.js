$(document).ready(function () {
        
    $("#jgVerPac").jqGrid(
    { 
        url:'includes/ajaxFunctions/jsonVerPacientes.php', 
        mtype: "POST",
        datatype: "json",
        colNames:['TipoDoc','NroDoc','Nombre','Apellido','Sexo',''],
        colModel:[ 
            {name:'tipodoc', index:'',width:'100%',align:"left",fixed:true,editable:true},
            {name:'norodoc', index:'',width:'100%',align:"center",fixed:true,editable:true},
            {name:'apellido', index:'pro.apellido',width:'100%',align:"left",fixed:true, editable:true},
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
        pager: '#jqPacfoot',
        sortname: 'pro.idprofesional',
        sortorder: "desc",
        editurl :'includes/ajaxFunctions/modificarProfesional.php',
        width: 1150,
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