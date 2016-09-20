function cargarOptionsComponentes(combo, datos)
{
    for(i=0; i<datos.length; i++)
    {
        combo.append("<option value='"+datos[i].idcomponente+"'>"+ datos[i].componente +" ("+ datos[i].tipo_componente +")</option>");
    }
}

function verTurnero(id)
{
  $("#dialog_subcontent2").load("includes/forms/nuevoTurnero.php",{action:"edit",idturnero:id});
  $("#verTurnero").click();
}

$(document).ready(function() {

  $('#btnNuevoTurnero').click(function(){
    $("#dialog_subcontent").load("includes/forms/nuevoTurnero.php?action=new");
  });

  $("#jqTurneros").jqGrid({
    url:'includes/ajaxFunctions/ajaxListaTurneros.php',
    mtype: "POST",
    datatype: "json",
    colNames:['Nro','Nro Consultorios','',''],
    colModel:[
      {name:'id', index:'id',width:'50%',align:"center",fixed:true, editable:false, search: false },
      {name:'consultorios', index:'consultorios',width:'300%',align:"left",fixed:true, editable:false, search: false },
      {name:'act',index:'act', width:'50%', sortable:false,align:"center",search:false, fixed:true,editable:false},
      {name:'myac', width:45, fixed:true, sortable:false, resize:false, formatter:'actions',search:false, formatoptions:{keys:true,"delbutton":true,"editbutton":false}}
      ], 
        rowNum: true,
        viewrecords: true,
        altRows: true,
        rowNum: 20,
        rowList: [10,20,30,50],
        pager: '#jqTurnerosFoot',
        sortname: 'id',
        sortorder: "desc",
        editurl :'includes/ajaxFunctions/ajaxEliminarTurnero.php',
        width: '350%',
        height: '50%',
        gridComplete: function()
        {
            var ids = jQuery("#jqTurneros").jqGrid('getDataIDs');
            for(var i=0;i < ids.length;i++)
            {
                var cl = ids[i];
                be = "<input class='button-secondary' type='button' onclick=\"javascript:verTurnero('"+cl+"');\" value='Editar'/>";
                jQuery("#jqTurneros").jqGrid('setRowData',ids[i],{act:be});
            }
        }
  });

  $('#jqTurneros').jqGrid('navGrid', '#jqTurnerosFoot', {
    edit:false,
    add:false,
    del:false,
    trash:false,
    search:false
  });

});