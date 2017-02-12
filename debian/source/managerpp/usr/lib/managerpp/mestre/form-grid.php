<?php
    require_once $_SERVER["DOCUMENT_ROOT"].'/licitacoes/sicap-lo'.'/verificador.php';

	session_start();

	$teste = new Verificador($_SESSION['ug'],'','',$_SESSION['cpf'], $_SESSION['cargo']);
	if($teste->verContextoUG() && $teste->getPermissaoPagina($_SERVER["REQUEST_URI"])){
		$permissaoedicao = $teste->getPermissaoEdicao($_SERVER["REQUEST_URI"]);	
?>

function addZeros(score,digits) {
	if(score.length >= digits) { return score; }
		return addZeros("0"+score,digits);
}

var hideMask = function () {
	Ext.get('loading').remove();
	Ext.fly('loading-mask').fadeOut({
		remove:true,
		//callback : firebugWarning
	});
};

Ext.onReady(function(){
	
	Ext.QuickTips.init();
	
	var ajax = '../ajax.php';
	
	var numlinhas = 10;
	
	//Pegando o tipo para listar
	var tipo = Ext.get("tipo").dom.innerHTML;
	
	switch(tipo){
	case 'L':
	  nome = 'Licitação';
	  break;
	case 'D':
	  nome = 'Dispensa/Inexigibilidade';
	  break;
	case 'R':
	  nome = 'Adesão ao Registro de Preco';
	  break;
	default:
	  break;
	}

    // example of custom renderer function
    function change(val){
        if(val > 0){
            return '<span style="color:green;">' + val + '</span>';
        }else if(val < 0){
            return '<span style="color:red;">' + val + '</span>';
        }
        return val;
    }

    // example of custom renderer function
    function pctChange(val){
        if(val > 0){
            return '<span style="color:green;">' + val + '%</span>';
        }else if(val < 0){
            return '<span style="color:red;">' + val + '%</span>';
        }
        return val;
    }
        
	var rowidEditalLicitacao=0;	        
        
    var recordFields = [    				
		{ name : 'id',				mapping : 'id'},
		{ name : 'processo',		mapping : 'processo'},
        { name : 'procedlic',		mapping : 'procedlic'},
        { name : 'modalidade',		mapping : 'modalidade'},
        { name : 'data_abertura',	mapping : 'data_abertura', type: 'date', dateFormat: 'Y-m-d'},
        { name : 'horario',			mapping : 'horario'},
        { name : 'valorestimado',	mapping : 'valorestimado', type: 'float'}                 
   	];

//  Store Processos
//	var recordFields = ['processo','procedlic','modalidade','data_abertura','horario','valorestimado'];

    var jstore = new Ext.data.Store({
		autoDestroy: true,		
		proxy: new Ext.data.HttpProxy(
		{
            url: ajax, 
            method: 'POST',
        }), 		
		reader: new Ext.data.JsonReader(
		{
			root: 'records',
			totalProperty: 'totalCount'       
		},
		recordFields
		),		
		baseParams : {metodo:'processo_2a_fase',tipo:tipo, opcao:'ver'}
//		sortInfo:{field: 'id', direction: "ASC"}		
    });

	jstore.load({params:{start:0,limit:numlinhas}});	
	
//  Grid Processos
    var grid = new Ext.grid.GridPanel({
        store: jstore,
        //disableSelection:true,
        //singleSelect: true,
        selModel : new Ext.grid.RowSelectionModel({singleSelect : true}),
        
		cm: new Ext.grid.ColumnModel({
			defaults: {
				width: 20,
				sortable: true
			},
			columns: [				
			{id:'id', hidden:true, dataIndex: 'id'},		
            {id:'processo',header: "Processo", width: 100, sortable: true, dataIndex: 'processo'},
            {header: "Procedimento Licitatório", width: 140, sortable: true, dataIndex: 'procedlic'},
            {id:'modalidade',header: "Modalidade", width: 75, sortable: true, dataIndex: 'modalidade'},
            {header: "Data Abertura", width: 85, sortable: true, renderer: Ext.util.Format.dateRenderer('d/m/Y'), dataIndex: 'data_abertura'},
            {header: "Horário", width: 60, sortable: true, dataIndex: 'horario'},
            {id: 'valorestimado', header: "Valor Estimado", align:"right", width: 100, sortable: true, renderer: Ext.util.Format.numberRenderer('0.000,00/i'), dataIndex: 'valorestimado'}
			]
		}),
        
        autoExpandColumn: 'valorestimado',autoExpandColumn: 'modalidade',
        height:300,
        width:600,
        title:'Processos de '+nome+' Assinados',
        bbar: new Ext.PagingToolbar({			
            store: jstore,
            pageSize: numlinhas,
            displayInfo: true,
			emptyMsg: "Não existem registros no Banco de Dados",
			displayMsg: 'Mostrando {0} - {1} de {2} resultado(s)'
        })
    });


	grid.getSelectionModel().on('rowselect', function(selModel, rowIdx, r){
		//Limpando campos desnecessários para nova instância
		$('#dados-adicionais').html('');
		$('#dados-adicionais-obra').html('');
		
		rowidEditalLicitacao = r.id;
		
//		Store Obra - - - - - - - - - - - - - - - - - - - - - -

		storeObra = new Ext.data.Store({
			autoDestroy: true,		
			proxy: new Ext.data.HttpProxy(
			{
				url: ajax, 
				method: 'POST',
			}), 		
			reader: new Ext.data.JsonReader(
			{
				root: 'records',
				totalProperty: 'totalCount'       
			},
			[	{ name: 'id'},
				{ name: 'cgcundadr'},
				{ name: 'numprsadm'},
				{ name: 'anoprsadm'},
				{ name: 'tippcd'},
				{ name: 'codtippcd'},
				{ name: 'codctgoba'},
				{ name: 'codtipoba'},
				{ name: 'codmdaoba'},
				{ name: 'codrgm'},
				{ name: 'numctt'},
				{ name: 'anoctt'},
				{ name: 'vlroba'},
				{ name: 'valorobra'},
				{ name: 'przexc'},		
				{ name: 'datinioba'},
				{ name: 'desoba'},
				{ name: 'cgcctd'},
				{ name: 'nomctd'},
				{ name: 'lgr'},
				{ name: 'cpl'},
				{ name: 'bai'},
				{ name: 'cid'},
				{ name: 'ufd'},
				{ name: 'cep'},
				{ name: 'nomenhexc'},
				{ name: 'nomenhfcl'},
				{ name: 'nombempub'},
				{ name: 'execucao'},
				{ name: 'coordenadas'},
				{ name: 'longitude'},
				{ name: 'datordem'},
				{ name: 'regcrea'},
				{ name: 'vistocrea'},
				{ name: 'nommdaoba'},
				{ name: 'nomctg'},
				{ name: 'des_subtipo'},
				{ name: 'assinatura'}
			]
			),		
			baseParams : {metodo:'obra', id: r.data.id, opcao:'ver'}
		});

		storeObra.load();

//		Grid Obra - - - - - - - - - - - - - - - -

		var rowidObra = 0;

		var gridObra = new Ext.grid.GridPanel({
			store: storeObra,
			selModel : new Ext.grid.RowSelectionModel({singleSelect : true}),
	        height:300,
			width:600,
			title:'Obra',
			cm: new Ext.grid.ColumnModel({
				defaults: {
					width: 20,
					sortable: true
				},
				columns: [														
				    {id:'id', hidden:true, dataIndex: 'id'},		
				    {header: "Ano", width: 40, sortable: true, dataIndex: 'anoctt'},
				    {header: "Tipo", width: 90, sortable: true, dataIndex: 'nomctg'},
   				    {header: "Subtipo", width: 140, sortable: true, dataIndex: 'des_subtipo'},
				    {header: "Modalidade", width: 110, sortable: true, dataIndex: 'nommdaoba'},
				    {header: "Valor", width: 75, sortable: true, align:"right", renderer: Ext.util.Format.numberRenderer('0.000,00/i'), dataIndex: 'valorobra'},
				    {header: "Início", width: 65, sortable: true, dataIndex: 'datinioba'},
				    {header: "Assinatura", width: 65, sortable: true, dataIndex: 'assinatura'}
				]
			}),
<?php if($permissaoedicao){ ?>
			bbar: [{
					text: '<img alt="Novo" src="../../includes/imagens/add.png">',
					handler: function() {
						//helpWindow.show();
						
					var form = new ObraUi();
					
					form.addButton('Salvar', function(){
						if(Ext.getCmp('obra').getForm().isValid()){
							Ext.getCmp('obra').getForm().submit({
								url : ajax,
								waitTitle: 'Aguarde',
								waitMsg: 'Salvando registro...',
								params:{
									opcao:'incluir',
									metodo:'obra',
									id_editallicitacao: rowidEditalLicitacao
									//stacad: Ext.getCmp('stacad').getValue(),
								},
								success: function(a, b){
									storeObra.load();
									Ext.Msg.alert('Sucesso');
								},
								failure: function(a, b){
									Ext.Msg.alert('Falha', b.result.errors);
								}
							});
						}else{
							Ext.Msg.alert('Aviso','Preencha os campos corretamente');
						}
					}, form);					
 
					var winco = new Ext.Window({
							 id:'formloadsubmit-win'
							,title: 'Obra'
							,layout:'fit'
							,width:430
							,height:650
							,closable:true
							,modal: true
							,border:false
							,items:	form
						});
					 
					    winco.setPosition(25,25);
						winco.show();
						
//						-22.906014°
//						$.mask.definitions['~']='[+-]';
						$('#coordenadas').mask("99.999999°");
						$('#longitude').mask("99.999999°");
						
						$('#anoctt').mask("9999");
						$('#cep').mask("99999-999");
						$("#vlroba").maskMoney({symbol: "R$", decimal: ",", thousands: "."});
						
/*						$('#cgcctd').mask("999999999-99");
						
						$('#numctt').mask("9999999999");
						$('#anoctt').mask("9999");
						
						$('#numcttori').mask("9999999999");
						$('#anocttori').mask("9999");
						
						$('#ctgeco').mask("9");
						$('#grpdsp').mask("9");
						$('#mdaivt').mask("99");
						$('#eledsp').mask("99");
						$('#subite').mask("99");
						
						$("#vlrctt").maskMoney({symbol: "R$", decimal: ",", thousands: "."});*/
						
					}
				},
				{
					text: '<img alt="Excluir" src="../../includes/imagens/delete.png">',
					handler: function() {
						if(rowidObra){
							Ext.MessageBox.confirm('Confirmação', 'Deseja realmete excluir este item?', function(btn){
								if('yes'===btn){
									$.post(ajax,
										{ 	
											opcao:'excluir',
											metodo:'obra',
											id: rowidObra,
											id_editallicitacao: rowidEditalLicitacao
										},
										function(result){											
											if(eval(result.success)){
												rowidObra=0;
												storeObra.load();		
												$('#dados-adicionais-obra').html('');
											}else{
												Ext.Msg.alert('Falha', result.errors);
											}											
										}, 
								   "json");
								}
								}
						   );
						}
					}
				},
				{
					text: '<img alt="Editar" src="../../includes/imagens/page_white_edit.png">',
					handler: function() {
						//helpWindow.show();
					if(rowidObra){
					var form = new ObraUi();
					
					form.addButton('Atualizar', function(){
						if(Ext.getCmp('obra').getForm().isValid()){
							Ext.getCmp('obra').getForm().submit({
								url : ajax,
								waitTitle: 'Aguarde',
								waitMsg: 'Salvando registro...',
								params:{
									opcao:'atualizar',
									metodo:'obra',
									id_editallicitacao: rowidEditalLicitacao,
									id: rowidObra
									//stacad: Ext.getCmp('stacad').getValue(),
								},
								success: function(a, b){
									storeObra.load();
									Ext.Msg.alert('Sucesso');
								},
								failure: function(a, b){
									Ext.Msg.alert('Falha', b.result.errors);
								}
							});
						}else{
							Ext.Msg.alert('Aviso','Preencha os campos corretamente');
						}
					}, form);
					
					form.getForm().loadRecord(gridObra.getSelectionModel().getSelected());
 
					var winco = new Ext.Window({
							 id:'formloadsubmit-win'
							,title: 'Obra'
							,layout:'fit'
							,width:430
							,height:650
							,closable:true
							,modal: true
							,border:false
							,items:	form
						});
					 
					    winco.setPosition(25,25);
						winco.show();
						
//						-22.906014°
//						$.mask.definitions['~']='[+-]';
						$('#coordenadas').mask("99.999999°");
						$('#longitude').mask("99.999999°");
						
						$('#anoctt').mask("9999");
						$('#cep').mask("99999-999");
						$("#vlroba").maskMoney({symbol: "R$", decimal: ",", thousands: "."});
						
/*						$('#cgcctd').mask("999999999-99");
						
						$('#numctt').mask("9999999999");
						$('#anoctt').mask("9999");
						
						$('#numcttori').mask("9999999999");
						$('#anocttori').mask("9999");
						
						$('#ctgeco').mask("9");
						$('#grpdsp').mask("9");
						$('#mdaivt').mask("99");
						$('#eledsp').mask("99");
						$('#subite').mask("99");
						
						$("#vlrctt").maskMoney({symbol: "R$", decimal: ",", thousands: "."});*/
						
					}
				}
				}]
<?php } ?>
		});

       	gridObra.getSelectionModel().on('rowselect', function(selModel, rowIdx, r) {
		//alert(r.data.id);
		
			rowidObra = r.data.id;
		    $('#dados-adicionais-obra').html('');

//		Store Termo Aditivo - - - - - - - - - - - - - - - - - - - - - - - -

			storeMedicao = new Ext.data.Store({
				autoDestroy: true,		
				proxy: new Ext.data.HttpProxy(
				{
					url: ajax, 
					method: 'POST',
				}), 		
				reader: new Ext.data.JsonReader(
				{
					root: 'records',
					totalProperty: 'totalCount'       
				},
				[	{ name: 'id'}, 
					{ name: 'id_obra'}, 
					{ name: 'cgcundadr'}, 
					{ name: 'numprsadm'}, 
					{ name: 'anoprsadm'},
					{ name: 'tippcd'},
					{ name: 'nummdr'},
					{ name: 'numctt'},
					{ name: 'tipteradiapm'},
					{ name: 'vlr'},
					{ name: 'vlradi'},
					{ name: 'datinimdr'},
					{ name: 'datfimmdr'},
					{ name: 'assinatura'}
					]
				),		
				baseParams : {metodo:'medicao', id: r.data.id, opcao:'ver'}
			});

			storeMedicao.load();

//		Grid Medicao - - - - - - - - - - - - - - - - - - - - - - - -	

			var rowidMedicao = 0;
		
			var gridMedicao = new Ext.grid.GridPanel({
				store: storeMedicao,
				//disableSelection:true,
				//singleSelect: true,
				selModel : new Ext.grid.RowSelectionModel({singleSelect : true}),
				
				cm: new Ext.grid.ColumnModel({
					defaults: {
						width: 20,
						sortable: true
					},
					columns: [																
					{id:'id', hidden:true, dataIndex: 'id'},		
					{header: "Número", width: 120, sortable: true, dataIndex: 'nummdr'},
					{header: "Data", width: 120, sortable: true, dataIndex: 'datinimdr'},
					{header: "Data", width: 120, sortable: true, dataIndex: 'datfimmdr'},
					//{header: "Tipo", width: 140, sortable: true, dataIndex: 'tipteradiapm'},
					{header: "Valor Aditivado", width: 90, align:"right", sortable: true, renderer: Ext.util.Format.numberRenderer('0.000,00/i'), dataIndex: 'vlradi'},
					{header: "Assinatura", width: 65, sortable: true, dataIndex: 'assinatura'}
					],
					
					autoExpandColumn: 'vlradi',
				}),
				
				//autoExpandColumn: 'valorestimado',autoExpandColumn: 'modalidade',
<?php if($permissaoedicao){ ?>
				bbar: //new Ext.PagingToolbar({
					/*buttons:*/ [{
						text: '<img alt="Novo" src="../../includes/imagens/add.png">',
						handler: function() {
							//helpWindow.show();

						var form = new MedicaoUi();
						
						form.addButton('Salvar', function(){
							if(Ext.getCmp('medicao').getForm().isValid()){
								Ext.getCmp('medicao').getForm().submit({
									url : ajax,
									waitTitle: 'Aguarde',
									waitMsg: 'Salvando registro...',
									params:{
										opcao:'incluir',
										metodo:'medicao',
										id_obra: r.data.id,
										id_editallicitacao: rowidEditalLicitacao
										//stacad: Ext.getCmp('stacad').getValue(),
									},
									success: function(a, b){
										storeMedicao.load();
										Ext.Msg.alert('Sucesso');
									},
									failure: function(a, b){
										Ext.Msg.alert('Falha', b.result.errors);
									}
								});
							}else{
								Ext.Msg.alert('Aviso','Preencha os campos corretamente');
							}
						}, form);						

						var wincd = new Ext.Window({
								 id:'formloadsubmit-win'
								,title: 'Medição'
								,layout:'fit'
								,width:500
								,height:220
								,closable:true
								,modal: true
								,border:false
								,items:	form
	///							,items:{id:'formloadsubmit-form', xtype:'exampleform'}
							});
							
							wincd.setPosition(25,550);
							wincd.show();
							//Ext.getCmp('dados_emp').hide();
							//Ext.getCmp('dados_pes').hide();
							
							$("#vlr").maskMoney({symbol: "R$", decimal: ",", thousands: "."});
							
	/*						$("#ctgeco").mask("9");
							$("#grpdsp").mask("9");
							$("#mdaivt").mask("99");
							$("#eledsp").mask("99");					*/
						}
					},
					{
						text: '<img alt="Excluir" src="../../includes/imagens/delete.png">',
						handler: function() {
							if(rowidMedicao){
								Ext.MessageBox.confirm('Confirmação', 'Deseja realmete excluir este item?', function(btn){
									if('yes'===btn){
										$.post(ajax,
											{ 	
												opcao:'excluir',
												metodo:'medicao',
												id_obra: r.data.id,
												id: rowidMedicao,
												id_editallicitacao: rowidEditalLicitacao
											},
											function(result){
												rowidMedicao=0;
												storeMedicao.load();
											}, 
									   "json");
									}
									}
							   );
							}
						}
					},
					{
						text: '<img alt="Editar" src="../../includes/imagens/page_white_edit.png">',
						handler: function() {
							//helpWindow.show();

						if(rowidMedicao){

						var form = new MedicaoUi();
						
						form.addButton('Atualizar', function(){
							if(Ext.getCmp('medicao').getForm().isValid()){
								Ext.getCmp('medicao').getForm().submit({
									url : ajax,
									waitTitle: 'Aguarde',
									waitMsg: 'Salvando registro...',
									params:{
										opcao:'atualizar',
										metodo:'medicao',
										id_obra: r.data.id,
										id_editallicitacao: rowidEditalLicitacao,
										id: rowidMedicao
										//stacad: Ext.getCmp('stacad').getValue(),
									},
									success: function(a, b){
										storeMedicao.load();
										Ext.Msg.alert('Sucesso');
									},
									failure: function(a, b){
										Ext.Msg.alert('Falha', b.result.errors);
									}
								});
							}else{
								Ext.Msg.alert('Aviso','Preencha os campos corretamente');
							}
						}, form);
						
						form.getForm().loadRecord(gridMedicao.getSelectionModel().getSelected());		

						var wincd = new Ext.Window({
								 id:'formloadsubmit-win'
								,title: 'Medição'
								,layout:'fit'
								,width:500
								,height:220
								,closable:true
								,modal: true
								,border:false
								,items:	form
	///							,items:{id:'formloadsubmit-form', xtype:'exampleform'}
							});
							
							wincd.setPosition(25,550);
							wincd.show();
							//Ext.getCmp('dados_emp').hide();
							//Ext.getCmp('dados_pes').hide();
							
							$("#vlr").maskMoney({symbol: "R$", decimal: ",", thousands: "."});
							
	/*						$("#ctgeco").mask("9");
							$("#grpdsp").mask("9");
							$("#mdaivt").mask("99");
							$("#eledsp").mask("99");					*/
						}
					}
					}]
<?php } ?>
			});
			
			gridMedicao.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
			   rowidMedicao = r.id;
			});			

//		Store Engenheiro - - - - - - - - - - - - - - - - - - - - - - - -

			storeEngenheiro = new Ext.data.Store({
				autoDestroy: true,		
				proxy: new Ext.data.HttpProxy(
				{
					url: ajax, 
					method: 'POST',
				}), 		
				reader: new Ext.data.JsonReader(
				{
					root: 'records',
					totalProperty: 'totalCount'       
				},  
				
				[	{ name: 'id'}, 
					{ name: 'id_obra'}, 
					{ name: 'cgcundadr'}, 
					{ name: 'numprsadm'}, 
					{ name: 'anoprsadm'},
					{ name: 'tippcd'},
					{ name: 'numenh'},
					{ name: 'numctt'},
					{ name: 'nomenh'},
					{ name: 'tipo'},
					{ name: 'tipo_desc'},
					{ name: 'regcrea'},
					{ name: 'vistocrea'}]
				),		
				baseParams : {metodo:'engenheiro', id: r.data.id, opcao:'ver'}
			});

			storeEngenheiro.load();

//		Grid Engenheiro - - - - - - - - - - - - - - - - - - - - - - - -	
		
			var rowidEngenheiro = 0;
		
			var gridEngenheiro = new Ext.grid.GridPanel({
				store: storeEngenheiro,
				//disableSelection:true,
				//singleSelect: true,
				selModel : new Ext.grid.RowSelectionModel({singleSelect : true}),
				
				cm: new Ext.grid.ColumnModel({
					defaults: {
						width: 20,
						sortable: true
					},
					
					columns: [																
					{id:'id', hidden:true, dataIndex: 'id'},		
					{header: "Tipo", width: 90, sortable: true, dataIndex: 'tipo_desc'},
					{header: "Nome", width: 250, sortable: true, dataIndex: 'nomenh'},					
					{header: "Registro CREA", width: 90, sortable: true, dataIndex: 'regcrea'},
					{header: "Visto CREA", width: 90, sortable: true, dataIndex: 'vistocrea'},
//					{header: "Nome", width: 90, sortable: true, dataIndex: 'nomenh'},
//					{id:'Ano',header: "Ano", width: 40, sortable: true, dataIndex: 'numctt'},
//					{header: "Data", width: 120, sortable: true, dataIndex: 'dat'},
//					{header: "Modalidade", width: 70, sortable: true, dataIndex: 'codmda'},
//					{header: "Tipo", width: 140, sortable: true, dataIndex: 'tipteradiapm'},
//					{header: "Valor Aditivado", width: 90, align:"right", sortable: true, renderer: Ext.util.Format.numberRenderer('0.000,00/i'), dataIndex: 'vlradi'},
//					{header: "Prazo Aditivado", width: 90, sortable: true, dataIndex: 'przadi'}				
					],

					autoExpandColumn: 'vlradi'
				}),				
<?php if($permissaoedicao){ ?>
				bbar: //new Ext.PagingToolbar({
					/*buttons:*/ [{
						text: '<img alt="Novo" src="../../includes/imagens/add.png">',
						handler: function() {
							//helpWindow.show();

						var form = new EngenheiroUi();
						
						form.addButton('Salvar', function(){
							if(Ext.getCmp('engenheiro').getForm().isValid()){
								Ext.getCmp('engenheiro').getForm().submit({
									url : ajax,
									waitTitle: 'Aguarde',
									waitMsg: 'Salvando registro...',
									params:{
										opcao:'incluir',
										metodo:'engenheiro',
										id_obra: r.data.id,
										id_editallicitacao: rowidEditalLicitacao
										//stacad: Ext.getCmp('stacad').getValue(),
									},
									success: function(a, b){
										storeEngenheiro.load();
										Ext.Msg.alert('Sucesso');
									},
									failure: function(a, b){
										Ext.Msg.alert('Falha', b.result.errors);
									}
								});
							}else{
								Ext.Msg.alert('Aviso','Preencha os campos corretamente');
							}
						}, form);

						var wina = new Ext.Window({
								 id:'formloadsubmit-win'
								,title: 'Engenheiro'
								,layout:'fit'
								,width:500
								,height:240
								,closable:true
								,modal: true
								,border:false
								,items:	form
	///							,items:{id:'formloadsubmit-form', xtype:'exampleform'}
							});
						 
							wina.setPosition(25,550);
							wina.show();
						
							//Ext.getCmp('termo_aditivo_container').hide();
							
							//			formulário
							/*Ext.getCmp('termo_aditivo').getForm().setValues({
								tipteradiapm: 'P',
								codmda:'A'
							});*/
							
							//Ext.getCmp('termo_aditivo').getForm().remove('tipteradiapm');
							//Ext.getCmp('termo_aditivo').getForm().remove('tipteradiapm_id');
							
							//$("#vlradi").maskMoney({symbol: "R$", decimal: ",", thousands: "."});
							
							
	/*						$("#ctgeco").mask("9");
							$("#grpdsp").mask("9");
							$("#mdaivt").mask("99");
							$("#eledsp").mask("99");					*/
						}
					},
					{
						text: '<img alt="Excluir" src="../../includes/imagens/delete.png">',
						handler: function() {
							if(rowidEngenheiro){
								Ext.MessageBox.confirm('Confirmação', 'Deseja realmete excluir este item?', function(btn){
									if('yes'===btn){
										$.post(ajax,
											{ 	
												opcao:'excluir',
												metodo:'engenheiro',
												id_obra: r.data.id,
												id: rowidEngenheiro,
												id_editallicitacao: rowidEditalLicitacao
											},
											function(result){
												rowidEngenheiro=0;
												storeEngenheiro.load();
											}, 
									   "json");
									}
									}
							   );
							}
						}
					},
					{
						text: '<img alt="Editar" src="../../includes/imagens/page_white_edit.png">',
						handler: function() {
							//helpWindow.show();

						if(rowidEngenheiro){
						var form = new EngenheiroUi();
						
						form.addButton('Atualizar', function(){
							if(Ext.getCmp('engenheiro').getForm().isValid()){
								Ext.getCmp('engenheiro').getForm().submit({
									url : ajax,
									waitTitle: 'Aguarde',
									waitMsg: 'Salvando registro...',
									params:{
										opcao:'atualizar',
										metodo:'engenheiro',
										id_obra: r.data.id,
										id_editallicitacao: rowidEditalLicitacao,
										id: rowidEngenheiro
										//stacad: Ext.getCmp('stacad').getValue(),
									},
									success: function(a, b){
										storeEngenheiro.load();
										Ext.Msg.alert('Sucesso');
									},
									failure: function(a, b){
										Ext.Msg.alert('Falha', b.result.errors);
									}
								});
							}else{
								Ext.Msg.alert('Aviso','Preencha os campos corretamente');
							}
						}, form);
						
						form.getForm().loadRecord(gridEngenheiro.getSelectionModel().getSelected());

						var wina = new Ext.Window({
								 id:'formloadsubmit-win'
								,title: 'Engenheiro'
								,layout:'fit'
								,width:500
								,height:240
								,closable:true
								,modal: true
								,border:false
								,items:	form
	///							,items:{id:'formloadsubmit-form', xtype:'exampleform'}
							});
						 
							wina.setPosition(25,550);
							wina.show();
						
							//Ext.getCmp('termo_aditivo_container').hide();
							
							//			formulário
							/*Ext.getCmp('termo_aditivo').getForm().setValues({
								tipteradiapm: 'P',
								codmda:'A'
							});*/
							
							//Ext.getCmp('termo_aditivo').getForm().remove('tipteradiapm');
							//Ext.getCmp('termo_aditivo').getForm().remove('tipteradiapm_id');
							
							//$("#vlradi").maskMoney({symbol: "R$", decimal: ",", thousands: "."});
							
							
	/*						$("#ctgeco").mask("9");
							$("#grpdsp").mask("9");
							$("#mdaivt").mask("99");
							$("#eledsp").mask("99");					*/
						}
					}
					}]
<?php } ?>
			});
			
			gridEngenheiro.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
			   rowidEngenheiro = r.id;
			});				

//		Store OrdemServico - - - - - - - - - - - - - - - - - - - - - - - -

			storeOrdemServico = new Ext.data.Store({
				autoDestroy: true,		
				proxy: new Ext.data.HttpProxy(
				{
					url: ajax, 
					method: 'POST',
				}), 		
				reader: new Ext.data.JsonReader(
				{
					root: 'records',
					totalProperty: 'totalCount'       
				},  
				[	{ name: 'id'}, 
					{ name: 'id_obra'}, 
					{ name: 'cgcundadr'}, 
					{ name: 'numprsadm'}, 
					{ name: 'anoprsadm'},
					{ name: 'tippcd'},
					{ name: 'numordsvc'},
					{ name: 'numctt'},
					{ name: 'dattertcbpvr'},
					{ name: 'dattertcbdfn'},
					{ name: 'tip'},
					{ name: 'tipo_desc'},
					{ name: 'datiniprl'},
					{ name: 'datpbc'},
					{ name: 'desjus'},
					{ name: 'datinimdr'},
					{ name: 'datfimmdr'}
				]
				),		
				baseParams : {metodo:'ordemservico', id: r.data.id, opcao:'ver'}
			});

			storeOrdemServico.load();

//		Grid OrdemServico - - - - - - - - - - - - - - - - - - - - - - - -	
		
			var rowidOrdemServico = 0;
		
			var gridOrdemServico = new Ext.grid.GridPanel({
				store: storeOrdemServico,
				//disableSelection:true,
				//singleSelect: true,
				selModel : new Ext.grid.RowSelectionModel({singleSelect : true}),
				
				cm: new Ext.grid.ColumnModel({
					defaults: {
						width: 20,
						sortable: true
					},
					
					columns: [																
					{id:'id', hidden:true, dataIndex: 'id'},		
					{header: "Número", width: 90, sortable: true, dataIndex: 'numordsvc'},
					{header: "Tipo", width: 90, sortable: true, dataIndex: 'tipo_desc'},
					{header: "Data", width: 90, sortable: true, dataIndex: 'datiniprl'},
					{header: "Previsão de Conclusão", width: 140, sortable: true, dataIndex: 'dattertcbpvr'},
//					{header: "Nome", width: 90, sortable: true, dataIndex: 'nomenh'},
//					{id:'Ano',header: "Ano", width: 40, sortable: true, dataIndex: 'numctt'},
//					{header: "Data", width: 120, sortable: true, dataIndex: 'dat'},
//					{header: "Modalidade", width: 70, sortable: true, dataIndex: 'codmda'},
//					{header: "Tipo", width: 140, sortable: true, dataIndex: 'tipteradiapm'},
//					{header: "Valor Aditivado", width: 90, align:"right", sortable: true, renderer: Ext.util.Format.numberRenderer('0.000,00/i'), dataIndex: 'vlradi'},
//					{header: "Prazo Aditivado", width: 90, sortable: true, dataIndex: 'przadi'}				
					],

					autoExpandColumn: 'vlradi'
				}),				
<?php if($permissaoedicao){ ?>
				bbar: //new Ext.PagingToolbar({
					/*buttons:*/ [{
						text: '<img alt="Novo" src="../../includes/imagens/add.png">',
						handler: function() {
							//helpWindow.show();

						var form = new OrdemServicoUi();
						
						form.addButton('Salvar', function(){
							if(Ext.getCmp('ordemservico').getForm().isValid()){
								Ext.getCmp('ordemservico').getForm().submit({
									url : ajax,
									waitTitle: 'Aguarde',
									waitMsg: 'Salvando registro...',
									params:{
										opcao:'incluir',
										metodo:'ordemservico',
										id_obra: r.data.id,
										id_editallicitacao: rowidEditalLicitacao
									},
									success: function(a, b){
										storeOrdemServico.load();
										Ext.Msg.alert('Sucesso');
									},
									failure: function(a, b){
										Ext.Msg.alert('Falha', b.result.errors);
									}
								});
							}else{
								Ext.Msg.alert('Aviso','Preencha os campos corretamente');
							}
						}, form);

						var wina = new Ext.Window({
								 id:'formloadsubmit-win'
								,title: 'Situação da Obra'
								,layout:'fit'
								,width:500
								,height:180
								,closable:true
								,modal: true
								,border:false
								,items:	form
	///							,items:{id:'formloadsubmit-form', xtype:'exampleform'}
							});
						 
							wina.setPosition(25,550);
							wina.show();
						
//							Ext.getCmp('termo_aditivo_container').hide();
							
							//			formulário
	/*						Ext.getCmp('termo_aditivo').getForm().setValues({
								tipteradiapm: 'P',
								codmda:'A'
							});*/
							
							//Ext.getCmp('termo_aditivo').getForm().remove('tipteradiapm');
							//Ext.getCmp('termo_aditivo').getForm().remove('tipteradiapm_id');
							
						//	$("#vlradi").maskMoney({symbol: "R$", decimal: ",", thousands: "."});
							
							
	/*						$("#ctgeco").mask("9");
							$("#grpdsp").mask("9");
							$("#mdaivt").mask("99");
							$("#eledsp").mask("99");					*/
						}
					},
					{
						text: '<img alt="Excluir" src="../../includes/imagens/delete.png">',
						handler: function() {
							if(rowidOrdemServico){
								Ext.MessageBox.confirm('Confirmação', 'Deseja realmete excluir este item?', function(btn){
									if('yes'===btn){
										$.post(ajax,
											{ 	
												opcao:'excluir',
												metodo:'ordemservico',
												id_obra: r.data.id,
												id: rowidOrdemServico,
												id_editallicitacao: rowidEditalLicitacao
											},
											function(result){
												rowidOrdemServico=0;
												storeOrdemServico.load();
											}, 
									   "json");
									}
									}
							   );
							}
						}
					},
					{
						text: '<img alt="Editar" src="../../includes/imagens/page_white_edit.png">',
						handler: function() {
						if(rowidOrdemServico){
						var form = new OrdemServicoUi();
						
						form.addButton('Atualizar', function(){
							if(Ext.getCmp('ordemservico').getForm().isValid()){
								Ext.getCmp('ordemservico').getForm().submit({
									url : ajax,
									waitTitle: 'Aguarde',
									waitMsg: 'Salvando registro...',
									params:{
										opcao:'atualizar',
										metodo:'ordemservico',
										id_obra: r.data.id,
										id_editallicitacao: rowidEditalLicitacao,
										id: rowidOrdemServico
									},
									success: function(a, b){
										storeOrdemServico.load();
										Ext.Msg.alert('Sucesso');
									},
									failure: function(a, b){
										Ext.Msg.alert('Falha', b.result.errors);
									}
								});
							}else{
								Ext.Msg.alert('Aviso','Preencha os campos corretamente');
							}
						}, form);
						
						form.getForm().loadRecord(gridOrdemServico.getSelectionModel().getSelected());

						var wina = new Ext.Window({
								 id:'formloadsubmit-win'
								,title: 'Situação da Obra'
								,layout:'fit'
								,width:500
								,height:180
								,closable:true
								,modal: true
								,border:false
								,items:	form
	///							,items:{id:'formloadsubmit-form', xtype:'exampleform'}
							});
						 
							wina.setPosition(25,550);
							wina.show();
						
//							Ext.getCmp('termo_aditivo_container').hide();
							
							//			formulário
	/*						Ext.getCmp('termo_aditivo').getForm().setValues({
								tipteradiapm: 'P',
								codmda:'A'
							});*/
							
							//Ext.getCmp('termo_aditivo').getForm().remove('tipteradiapm');
							//Ext.getCmp('termo_aditivo').getForm().remove('tipteradiapm_id');
							
						//	$("#vlradi").maskMoney({symbol: "R$", decimal: ",", thousands: "."});
							
							
	/*						$("#ctgeco").mask("9");
							$("#grpdsp").mask("9");
							$("#mdaivt").mask("99");
							$("#eledsp").mask("99");					*/
						}
					}
					}]
<?php } ?>
			});
			
			gridOrdemServico.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
			   rowidOrdemServico = r.id;
			});				

//		Store MedicaoPrevista - - - - - - - - - - - - - - - - - - - - - - - -

			storeMedicaoPrevista = new Ext.data.Store({
				autoDestroy: true,		
				proxy: new Ext.data.HttpProxy(
				{
					url: ajax, 
					method: 'POST',
				}), 		
				reader: new Ext.data.JsonReader(
				{
					root: 'records',
					totalProperty: 'totalCount'       
				},  

/*
id
,id_obra
,cgcundadr
,numprsadm
,anoprsadm
,tippcd
,nummdr
,numctt
,datprv
,vlrprv
,prz
*/
				[	{ name: 'id'}, 
					{ name: 'id_obra'}, 
					{ name: 'cgcundadr'}, 
					{ name: 'numprsadm'}, 
					{ name: 'anoprsadm'},
					{ name: 'tippcd'},
					{ name: 'nummdr'},
					{ name: 'numctt'},
					{ name: 'datprv'},
					{ name: 'vlrprv'},
					{ name: 'prz'}
				]
				),		
				baseParams : {metodo:'medicaoprevista', id: r.data.id, opcao:'ver'}
			});

			storeMedicaoPrevista.load();

//		Grid MedicaoPrevista - - - - - - - - - - - - - - - - - - - - - - - -	
		
			var rowidMedicaoPrevista = 0;
		
			var gridMedicaoPrevista = new Ext.grid.GridPanel({
				store: storeMedicaoPrevista,
				//disableSelection:true,
				//singleSelect: true,
				selModel : new Ext.grid.RowSelectionModel({singleSelect : true}),
				
				cm: new Ext.grid.ColumnModel({
					defaults: {
						width: 20,
						sortable: true
					},
					
					columns: [																
					{id:'id', hidden:true, dataIndex: 'id'},		
					{header: "Número", width: 90, sortable: true, dataIndex: 'nummdr'},
					{header: "Prazo(dias)", width: 90, sortable: true, dataIndex: 'prz'},
					{header: "Valor", width: 90, sortable: true, dataIndex: 'vlrprv'},
//					{header: "Nome", width: 90, sortable: true, dataIndex: 'nomenh'},
//					{id:'Ano',header: "Ano", width: 40, sortable: true, dataIndex: 'numctt'},
//					{header: "Data", width: 120, sortable: true, dataIndex: 'dat'},
//					{header: "Modalidade", width: 70, sortable: true, dataIndex: 'codmda'},
//					{header: "Tipo", width: 140, sortable: true, dataIndex: 'tipteradiapm'},
//					{header: "Valor Aditivado", width: 90, align:"right", sortable: true, renderer: Ext.util.Format.numberRenderer('0.000,00/i'), dataIndex: 'vlradi'},
//					{header: "Prazo Aditivado", width: 90, sortable: true, dataIndex: 'przadi'}				
					],

					autoExpandColumn: 'vlradi'
				}),				
<?php if($permissaoedicao){ ?>
				bbar: //new Ext.PagingToolbar({
					/*buttons:*/ [{
						text: '<img alt="Novo" src="../../includes/imagens/add.png">',
						handler: function() {
							//helpWindow.show();

						var form = new MedicaoPrevistaUi();
						
						form.addButton('Salvar', function(){
							if(Ext.getCmp('medicaoprevista').getForm().isValid()){
								Ext.getCmp('medicaoprevista').getForm().submit({
									url : ajax,
									waitTitle: 'Aguarde',
									waitMsg: 'Salvando registro...',
									params:{
										opcao:'incluir',
										metodo:'medicaoprevista',
										id_obra: r.data.id,
										id_editallicitacao: rowidEditalLicitacao,										
										//stacad: Ext.getCmp('stacad').getValue(),
									},
									success: function(a, b){
										storeMedicaoPrevista.load();
										Ext.Msg.alert('Sucesso');
									},
									failure: function(a, b){
										Ext.Msg.alert('Falha', b.result.errors);
									}
								});
							}else{
								Ext.Msg.alert('Aviso','Preencha os campos corretamente');
							}
						}, form);

						var wina = new Ext.Window({
								 id:'formloadsubmit-win'
								,title: 'Cronograma'
								,layout:'fit'
								,width:450
								,height:180
								,closable:true
								,modal: true
								,border:false
								,items:	form
	///							,items:{id:'formloadsubmit-form', xtype:'exampleform'}
							});
						 
							wina.setPosition(25,550);
							wina.show();
						
							//Ext.getCmp('termo_aditivo_container').hide();
							
							//			formulário
							/*Ext.getCmp('termo_aditivo').getForm().setValues({
								tipteradiapm: 'P',
								codmda:'A'
							});*/
							
							//Ext.getCmp('termo_aditivo').getForm().remove('tipteradiapm');
							//Ext.getCmp('termo_aditivo').getForm().remove('tipteradiapm_id');
							
							$("#vlrprv").maskMoney({symbol: "R$", decimal: ",", thousands: "."});
							
							
	/*						$("#ctgeco").mask("9");
							$("#grpdsp").mask("9");
							$("#mdaivt").mask("99");
							$("#eledsp").mask("99");					*/
						}
					},
					{
						text: '<img alt="Excluir" src="../../includes/imagens/delete.png">',
						handler: function() {
							if(rowidMedicaoPrevista){
								Ext.MessageBox.confirm('Confirmação', 'Deseja realmete excluir este item?', function(btn){
									if('yes'===btn){
										$.post(ajax,
											{ 	
												opcao:'excluir',
												metodo:'medicaoprevista',
												id_obra: r.data.id,
												id: rowidMedicaoPrevista,
												id_editallicitacao: rowidEditalLicitacao
											},
											function(result){
												rowidMedicaoPrevista=0;
												storeMedicaoPrevista.load();
											}, 
									   "json");
									}
									}
							   );
							}
						}
					},
					{
						text: '<img alt="Editar" src="../../includes/imagens/page_white_edit.png">',
						handler: function() {
							//helpWindow.show();
						if(rowidMedicaoPrevista){
						var form = new MedicaoPrevistaUi();
						
						form.addButton('Atualizar', function(){
							if(Ext.getCmp('medicaoprevista').getForm().isValid()){
								Ext.getCmp('medicaoprevista').getForm().submit({
									url : ajax,
									waitTitle: 'Aguarde',
									waitMsg: 'Salvando registro...',
									params:{
										opcao:'atualizar',
										metodo:'medicaoprevista',
										id_obra: r.data.id,
										id_editallicitacao: rowidEditalLicitacao,
										id: rowidMedicaoPrevista
										//stacad: Ext.getCmp('stacad').getValue(),
									},
									success: function(a, b){
										storeMedicaoPrevista.load();
										Ext.Msg.alert('Sucesso');
									},
									failure: function(a, b){
										Ext.Msg.alert('Falha', b.result.errors);
									}
								});
							}else{
								Ext.Msg.alert('Aviso','Preencha os campos corretamente');
							}
						}, form);
						
						form.getForm().loadRecord(gridMedicaoPrevista.getSelectionModel().getSelected());

						var wina = new Ext.Window({
								 id:'formloadsubmit-win'
								,title: 'Cronograma'
								,layout:'fit'
								,width:450
								,height:180
								,closable:true
								,modal: true
								,border:false
								,items:	form
	///							,items:{id:'formloadsubmit-form', xtype:'exampleform'}
							});
						 
							wina.setPosition(25,550);
							wina.show();
						
							$("#vlrprv").maskMoney({symbol: "R$", decimal: ",", thousands: "."});
						
							//Ext.getCmp('termo_aditivo_container').hide();
							
							//			formulário
							/*Ext.getCmp('termo_aditivo').getForm().setValues({
								tipteradiapm: 'P',
								codmda:'A'
							});*/
							
							//Ext.getCmp('termo_aditivo').getForm().remove('tipteradiapm');
							//Ext.getCmp('termo_aditivo').getForm().remove('tipteradiapm_id');
														
							
							
	/*						$("#ctgeco").mask("9");
							$("#grpdsp").mask("9");
							$("#mdaivt").mask("99");
							$("#eledsp").mask("99");					*/
						
					}
				}
					}]
<?php } ?>
			});
			
			gridMedicaoPrevista.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
			   rowidMedicaoPrevista = r.id;
			});				

			var tabs2 = new Ext.TabPanel({
				//renderTo: dados-adicionais,
				activeTab: 0,
				width:600,
				height:250,
				plain:true,
				defaults:{autoScroll: true},
				items:[{
						title: 'Responsáveis Engenheiros Arquitetos<img alt="Obrigatório" src="../../includes/imagens/asterisk_yellow.png">',
						layout: 'fit',
						items: gridEngenheiro
					},{
						title: 'Cronograma<img alt="Obrigatório" src="../../includes/imagens/asterisk_yellow.png">',
						layout: 'fit',
						items: gridMedicaoPrevista
					},{
						title: 'Medição<img alt="Obrigatório" src="../../includes/imagens/asterisk_yellow.png">',
						layout: 'fit',
						items: gridMedicao
					},{
						title: 'Situação da Obra<img alt="Obrigatório" src="../../includes/imagens/asterisk_yellow.png">',
						layout: 'fit',
						items: gridOrdemServico
					}
				]
			});    
			
			tabs2.render('dados-adicionais-obra');

		});
			
		
/*		var tabs = new Ext.TabPanel({
			//renderTo: dados-adicionais,
			activeTab: 0,
			width:600,
			height:250,
			plain:true,
			defaults:{autoScroll: true},
			items:[{
					//itemId: 'tab1',
					title: 'Contrato<img alt="Obrigatório" src="../../includes/imagens/asterisk_yellow.png">',
					layout: 'fit',
					items: gridObra
				}
			]
		});    */
		
		//tabs.render('dados-adicionais');	
		gridObra.render('dados-adicionais');	
			
	});

    grid.render('grid-example');
        

	$('.x-tbar-page-first').click( function(){
		$('#dados-adicionais').html('');
		$('#dados-adicionais-obra').html('');
	});	
	$('.x-tbar-page-prev').click( function(){
		$('#dados-adicionais').html('');	
		$('#dados-adicionais-obra').html('');
	});	
	$('.x-tbar-page-next').click( function(){
		$('#dados-adicionais').html('');	
		$('#dados-adicionais-obra').html('');
	});	
	$('.x-tbar-page-last').click( function(){
		$('#dados-adicionais').html('');	
		$('#dados-adicionais-obra').html('');
	});	    
    
    hideMask.defer(250);
    
});

<?php
	}
?>
