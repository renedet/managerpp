
aux = 0;

function verStatus(){
    
    var store_escravo = new Ext.data.JsonStore({
        autoDestroy: true,
        id: 'store_escravo',
        proxy: new Ext.data.HttpProxy({ 
            method: 'GET', 
            url: '../ajax',
            disableCaching:false
        }),
        //method:'GET',
        //url: 'store_escravo.json',//criar funcao getescravos
        //url: '../ajax',//criar funcao getescravos
        storeId: 'store_escravo',
        root: 'escravos',
        idProperty: 'id',
        fields: [
            { name : 'id',    mapping : 'id' },
            { name : 'ip',    mapping : 'ip' },
            { name : 'pp',    mapping : 'pp' }
        ],
        baseParams: { metodo:'escravos'}
    });
    
    store_escravo.load();

    var grid = new Ext.grid.GridPanel({
        store: store_escravo,
        selModel : new Ext.grid.RowSelectionModel({singleSelect : true}),
        columns: [
            {header: 'id', width: 170, sortable: true, dataIndex: 'id'},
            {header: 'ip', width: 150, sortable: true, dataIndex: 'ip'},
            {header: 'pp', width: 50, sortable: true, dataIndex: 'pp'}
        ],
        //title: 'Nós Escravos',
        height:230,
        width:590
    });
        
    grid.getSelectionModel().on('rowselect', function(selModel, rowIdx, r){
        alert(3)
    });

    var win = new Ext.Window({
            id: 'winteste',
            title: 'Nós Escravos',
            layout:'fit',
            width:510,
            height:150,
            closeAction:'close', 
            //plain: true,
            modal: true,
            items: grid,
            border: false

    });
    win.show();
    
}

function botaoParametros(){
/*
{
   "par1":[
      "value1",
      "value2"
   ],
   "par2":[
      "value1",
      "value2"
   ]
}

{"par1":["value1","value2"],"par2":["value1","value2"]}

*/

    var defaultval = '{"par1":["value1","value2"],"par2":["value1","value2"]}';
    
    if($('#parametros').val().length>0){
        defaultval = $('#parametros').val();
    }
    
    var textarea = new Ext.form.TextArea({id:'textareaparametros',value:defaultval});
    
    var win = new Ext.Window({
            id: 'winteste',
            title: 'Parâmetros',
            layout:'fit',
            width:510,
            height:150,
            closeAction:'close', 
            //plain: true,
            modal: true,
            items: textarea,
            //border: false

    });
    win.on("close", function() {
        $('#parametros').val($('#textareaparametros').val())
        }
    );
    win.show();
}

function getStatus(){
    
    $.get("../ajax", { metodo:"status" },
        function(data){
            //var aux = $.parseJSON(data);
            if(data.status){
                $('#status_server').html('Nós conectados:<br/>'+data.count_no)
                if (Ext.getCmp('botaoiniciar') == undefined) {
                    var myButton = new Ext.Button({
                        id: 'botaoiniciar',
                        text    : 'Iniciar Processamento',
                        handler : function() {
							/*
                            $.get("../ajax", { 
                                    metodo:"startprocessos",
                                    fase01:$('#fase01').val(),
                                    fase02:$('#fase02').val(),
                                    fase03:$('#fase03').val(),
                                    parametros:decodeURI($('#parametros').val())
                                    //total_pro:$('#total_pro').val(),
                                    //base_init:$('#base_init').val()
                                },
                                function(data){
                                    //var aux = jQuery.parseJSON(data);
                                    if(data.status){
                                        Ext.getCmp('botaoiniciar').disable();
                                        Ext.getCmp('combofase01').disable();
                                        Ext.getCmp('combofase02').disable();
                                        Ext.getCmp('combofase03').disable();
                                        Ext.getCmp('botao_par').disable();
                                        //Ext.getCmp('fieldtotal_pro').disable();
                                        //Ext.getCmp('combobase_init').disable();
                                        window.location.reload()
                                    }else{
                                        alert('Alguma coisa está errada!'+data)
                                    }
                            },'json');*/
                            
                            
                            $.ajax({
								type: "GET",
								url: "../ajax",
								contentType: "application/json; charset=utf-8",
								dataType: "json",
								data: "metodo=startprocessos"+
                                    "&fase01="+$('#fase01').val()+
                                    "&fase02="+$('#fase02').val()+
                                    "&fase03="+$('#fase03').val()+
                                    "&parametros="+$('#parametros').val(),
								success: function(json) {
									if(data.status){
										Ext.getCmp('botaoiniciar').disable();
                                        Ext.getCmp('combofase01').disable();
                                        Ext.getCmp('combofase02').disable();
                                        Ext.getCmp('combofase03').disable();
                                        Ext.getCmp('botao_par').disable();
                                        window.location.reload()
                                    }else{
										alert('Alguma coisa está errada!'+data)
									}
								},
								error: function (xhr, textStatus, errorThrown) {
									alert('Alguma coisa está errada! '+xhr.responseText)
								}
							});
                            
                        },
                        applyTo: 'panel_status'
                    });
                }
                if (Ext.getCmp('botaoteste') == undefined) {
                    var myButton = new Ext.Button({
                        id: 'botaoteste',
                        text    : 'Verificar',
                        handler : function() {
                            getStatus();
                        },
                        applyTo: 'panel_status'
                    });
                }                
            }else{
                alert('Servidor sem nós!');
                $('#status_server').html('Configure os nós no endereço:<br/>http://'+data.ip+':'+data.port+'<br/>')
                if (Ext.getCmp('botaoteste') == undefined) {
                    var myButton = new Ext.Button({
                        id: 'botaoteste',
                        text    : 'Verificar',
                        handler : function() {
                            getStatus();
                        },
                        applyTo: 'panel_status'
                    });
                }
            }
    }, "json");
    
    
    $('#status_server').html('<img src="blue-loading.gif">')
}

function getInitStatus(){
    
    $.get("../ajax", { metodo:"initstatus" },
        function(data){
            //var aux = $.parseJSON(data);
            if(data.status){
                $('#fase01').val(data.fase01)
                $('#fase02').val(data.fase02)
                $('#fase03').val(data.fase03)
                //$('#base_init').val(data.base_init)
                //$('#total_pro').val(data.total_pro)
                
                if(Ext.getCmp('botaoiniciar')!=undefined){
                    Ext.getCmp('botaoiniciar').disable();
                }
                Ext.getCmp('combofase01').disable();
                Ext.getCmp('combofase02').disable();
                Ext.getCmp('combofase03').disable();
                Ext.getCmp('botao_par').disable();
                //Ext.getCmp('fieldtotal_pro').disable();
                //Ext.getCmp('combobase_init').disable(); 

                $('#status_server').html('Servidor Processando...<br/><img src="blue-loading.gif">')
                //colocar verificador a cada x segundos aqui!
                if (Ext.getCmp('verstatus') == undefined) {
                    var myButton = new Ext.Button({
                        id: 'verstatus',
                        text    : 'Verificar andamento',
                        handler : function() {
                            verStatus()
                        },
                        applyTo: 'panel_status'
                    });
                }
            }else{
                if(data.path_ini){
                    formNFS(data.nfs_path)
                }else{
                    getStatus()
                }
            }
    }, "json");
}

function formNFS(nfs_path){
    
    var painel = {
          xtype : "panel",
          width : 500,
          height : 300,
          border:false,
          defaults:{
                bodyStyle:'padding:10px'
          },
          items : [    {
              xtype : "form",
              //height : 300,
              width : 500,
              border:false,
              items : [        {
                  xtype : "textfield",
                  id : "nfs_path",
                  name : "nfs_path",
                  value : nfs_path,
                  fieldLabel : "Pasta NFS",
                  width : 375
              },        /*{
                  xtype : "numberfield",
                  id : "num_process",
                  name : "num_process",
                  value : 10,
                  fieldLabel : "Número de Processos"
              },        {
                  xtype : "textfield",
                  id : "server_ip",
                  name : "server_ip",
                  value : "http://www.google.com",
                  fieldLabel : "IP do Servidor"
              },*/        {
                  xtype : "panel",
                  border: false,
                  html: '<div id="msgpanel"></div>'
              }],
              buttons: [{
                  text: 'OK',
                  handler: function(){
                        $.get("../ajax", { metodo:"setup", nfs_path: $("#nfs_path").val(), num_process: $("#num_process").val()/*, server_ip: $("#server_ip").val()*/ },
                            function(data){
                                //var aux = jQuery.parseJSON(data);
                                if(!data.nfs_path){
                                    alert("Diretório invalido: " + $("#nfs_path").val())
                                    $('#msgpanel').html('')
                                }/*
                                if(!aux.server_ip){
                                    alert("Servidor não responde: " + $("#server_ip").val())
                                    $('#msgpanel').html('')
                                }*/
                                else if(data.nfs_path){
                                    $('#msgpanel').html('<div class="highlight">Pasta NFS encontrada!<br/>Número de processos definido<br/>Servidor respondeu!<br/></div>')
                                    getStatus();                                    
                                    win.close();
                                }
                        }, "json");
                  }
              }]              
          }],
          title : ""
    }
    
    var win = new Ext.Window({
        //applyTo:'aba-win',
        id: 'winteste',
        title: 'Configurações do Nó Mestre',
        layout:'fit',
        width:510,
        height:150,
        closeAction:'close', 
        //plain: true,
        modal: true,
        items: painel

    });
    //win.setPosition(100,25)
    win.show();
    
}


Ext.onReady(function(){

    Ext.QuickTips.init();    
/*
    var myForm = new Ext.form.FormPanel({
        renderTo:"analisa_resultado",
        width:140,
        //heigth:50,
        frame:true,
        items : [    {
            xtype : "combo",
            name : "combovalue",
            hiddenName : "combovalue",            
            displayField:'state',
            typeAhead: true,
            mode: 'local',
            triggerAction: 'all',
            emptyText:'Select a state...',
            selectOnFocus:true,
            
        }]
    });
*/

// simple array store
/*var store = new Ext.data.ArrayStore({
    fields: ['alpha2code','name'],
    data: [
        ["BE","Belgium"],["BR","Brazil"],["BG","Bulgaria"],["CA","Canada"],["CL","Chile"],["CY","Cyprus"],["CZ","Czech Republic"],["FI","Finland"],["FR","France"],["DE","Germany"],["HU","Hungary"],["IE","Ireland"],["IL","Israel"],["IT","Italy"],["LV","Latvia"],["LT","Lithuania"],["MX","Mexico"],["NL","Netherlands"],["NZ","New Zealand"],["NO","Norway"],["PK","Pakistan"],["PL","Poland"],["RO","Romania"],["SK","Slovakia"],["SI","Slovenia"],["ES","Spain"],["SE","Sweden"],["CH","Switzerland"],["GB","United Kingdom"]                
    ]
});*/

var store = new Ext.data.Store({
    id: 'store',
    name: 'store',
    reader: new Ext.data.JsonReader({ // {"files":[{"file":"/home/renedet/nfspath/file_exec"},{"file":"/home/renedet/nfspath/file_exec2"}]}
        fields: ['file'],
        root: 'files'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '../ajax?metodo=list_nfs_files'/*,
        method: 'GET'*/
    })
});

var comboWithTooltip1 = new Ext.form.ComboBox({
    width:130,
    store: store,
    displayField:'file',
    typeAhead: true,
    mode: 'remote',
    triggerAction: 'all',
    emptyText:'Selecione um executável...',
    selectOnFocus:true,
    id: 'combofase01',
    //value: 'fase01',
    applyTo: 'fase01'
});

var comboWithTooltip2 = new Ext.form.TextField({
    fieldLabel: 'Processo',
    width:135,
    emptyText:'Digite o nome do executável...',
    id: 'combofase02',
    applyTo: 'fase02'
});

/*
var comboWithTooltip = new Ext.form.ComboBox({
    width:135,
    store: store,
    displayField:'file',
    typeAhead: true,
    mode: 'remote',
    triggerAction: 'all',
    emptyText:'Selecione um executável...',
    selectOnFocus:true,
    id: 'combofase02',
    //value: 'fase02',
    applyTo: 'fase02',
    fieldLabel: 'Processo'
});
*/

var comboWithTooltip3 = new Ext.form.ComboBox({
    width:175,
    store: store,
    displayField:'file',
    typeAhead: true,
    mode: 'remote',
    triggerAction: 'all',
    emptyText:'Selecione um executável...',
    selectOnFocus:true,
    id: 'combofase03',
    //value: 'fase03',
    applyTo: 'fase03'
});

var myButton = new Ext.Button({
    id: 'botao_par',
    text    : 'Definir parâmetros',
    handler : function() {
        botaoParametros();
    },
    applyTo: 'botaopar'
});

/*
var comboWithTooltip = new Ext.form.NumberField({
    width:30,
    id: 'fieldtotal_pro',
    value: '10',
    applyTo: 'total_pro'
});

var comboWithTooltip = new Ext.form.ComboBox({
    width:135,
    store: store,
    displayField:'file',
    typeAhead: true,
    mode: 'remote',
    triggerAction: 'all',
    emptyText:'Selecione um executável...',
    selectOnFocus:true,
    //value: 'teste.mat',
    id: 'combobase_init',
    applyTo: 'base_init',
    fieldLabel: 'Base (Opcional)'
});
*/

new Ext.Panel({
    id:'panel_status',
    //contentEl: 'state-combo-code',
    width: 142,
    //heigth: 125,
    html: '<div id="status_server"></div>',
    defaults:{
        bodyStyle:'padding:10px'
    },
    //title: 'View code to create this combo',
/*    hideCollapseTool: true,
    titleCollapse: true,
    collapsible: true,
    collapsed: true,*/
    renderTo: 'init_status'
});

/*
    var comboFromArray = new Ext.form.ComboBox({
        store: new Ext.data.SimpleStore({
            fields: ['alpha2code','name'],
            data: [
              ["BE","Belgium"],["BR","Brazil"],["BG","Bulgaria"],["CA","Canada"],["CL","Chile"],["CY","Cyprus"],["CZ","Czech Republic"],["FI","Finland"],["FR","France"],["DE","Germany"],["HU","Hungary"],["IE","Ireland"],["IL","Israel"],["IT","Italy"],["LV","Latvia"],["LT","Lithuania"],["MX","Mexico"],["NL","Netherlands"],["NZ","New Zealand"],["NO","Norway"],["PK","Pakistan"],["PL","Poland"],["RO","Romania"],["SK","Slovakia"],["SI","Slovenia"],["ES","Spain"],["SE","Sweden"],["CH","Switzerland"],["GB","United Kingdom"]                
                  ]
        }),
        displayField:'name',
        typeAhead: true,
        triggerAction: 'all',
        emptyText:'Select a state...',
        selectOnFocus:true,
        applyTo: 'aux'
    });
*/


// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
getInitStatus()

});
