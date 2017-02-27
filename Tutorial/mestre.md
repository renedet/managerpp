## Gerenciar Mestre (interface gráfica)

1 - Ao acessar o endereço http://20.12.2.101:8080/managerpp/mestre/index.xhtml gerado na instancia do no mestre, por linha de comando, temos a tela inicial abaixo:
![Interface Gráfica do Mestre - compartilhamento NFS](https://raw.githubusercontent.com/renedet/managerpp/master/Tutorial/imagens/img1.png)

No campo Pasta NFS, deve ser informado o diretorio onde foi montado o compartilhamento NFS.

Obs: Caso não necessite usar o compartilhamento NFS, coloque o caminho do diretório onde o mestre foi instanciado.

2 - Ao definir o diretório NFS a tela abaixo será exibida mostrando a imagem "Servidor sem nós!" indicando que o mestre não tem escravos.

3 - Na próxima tela temos uma visão geral do fluxo de funcionamento do mestre com as seguintes caixas:
Para "Verifica Status" temos o endereço que deve ser informado para o escravo.
Em "Analisa resultado" temos a seleção do script que será executado na FASE 1, veja o item 4.
Na parte central da execução em paralelo, caixa "Executa Processamento dos Dados", temos um campo para configurar o processo principal e os parametros adicionais da chamada, mais no item 5.
E por ultimo na caixa "Compila resultados" é feito uma verificação para ver se tudo foi executado corretamente, verifique item 6 para mais detalhes.


4 - Na caixa "Analisa resultado", FASE 1, temos a seleção do script que será executado este script tem uma execução básica para controle do sistema, mas pode ser configurado pelo usuário para inclusão de alguma verificação adicional.

5 - Na caixa "Executa Processamento dos Dados", FASE 2, temos um campo para configurar o processo principal, este processo pode ser um executável ou script por linha de comando.
Os parametros adicionais da chamada podem ser configurados clicando no botão "Definir parâmetros".

6 - Na caixa "Compila resultados" é definido o script que analisa se tudo foi executado corretamente, este script tem uma analise básica, mas pode ser configurado para adicinar outras verificações.
