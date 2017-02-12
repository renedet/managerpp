
Ext.onReady(function(){

    Ext.QuickTips.init();

	$.get("../ajax", { metodo:"getprocessosstatus" }, 
		function(data){
			$('#nfs_path').val(data.nfs_path);
			$('#num_process').val(data.processadores);
		}
	,"json");

    var painel = {
          xtype : "panel",
          width : 300,
          height : 300,
          border:false,
          defaults:{
                bodyStyle:'padding:10px'
          },
          items : [    {
              xtype : "form",
              //height : 300,
              border:false,
              items : [        {
                  xtype : "textfield",
                  id : "nfs_path",
                  name : "nfs_path",
                  //value : "/media/84119545/posuft/artigo_matlab/ARTIGO_MATLAB/nfs",
                  fieldLabel : "Pasta NFS"
              },        {
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
              },        {
                  xtype : "panel",
                  border: false,
                  html: '<div id="msgpanel"></div>'
              }],
              buttons: [{
                  text: 'OK',
                  handler: function(){
                        $.get("../ajax", { metodo:"setup", nfs_path: $("#nfs_path").val(), num_process: $("#num_process").val(), server_ip: $("#server_ip").val() },
                            function(data){
                                //var aux = jQuery.parseJSON(data)
                                var aux = data
                                if(!aux.nfs_path){
                                    alert("Diretório invalido: " + $("#nfs_path").val())
                                    $('#msgpanel').html('')
                                }
                                if(!aux.server_ip){
                                    alert("Servidor não responde: " + $("#server_ip").val())
                                    $('#msgpanel').html('')
                                }
                                if(aux.server_ip && aux.nfs_path){
                                    $('#msgpanel').html('<div class="highlight">Pasta NFS encontrada!<br/>Número de processos definido<br/>Servidor respondeu!<br/></div>')
                                }
                        });
                  }
              }]              
          }],
          title : ""
    }
    
    var win = new Ext.Window({
        //applyTo:'aba-win',
        id: 'winteste',
        title: 'Configurações do Nó Escravo',
        layout:'fit',
        width:310,
        height:300,
        closeAction:'close', 
        //plain: true,
        modal: true,
        items: painel

    });
    //win.setPosition(100,25)
    win.show();

});
