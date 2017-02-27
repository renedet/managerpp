## Gerenciar Escravo (interface gráfica)

1. Ao acessar o endereço `http://20.12.2.101:8081/managerpp/escravo/index.xhtml` gerado na [instância do nó escravo](https://github.com/renedet/managerpp/tree/master/Tutorial#instanciar-escravo), por linha de comando, temos a tela inicial abaixo:
![Interface Gráfica do Escravo - compartilhamento NFS](https://raw.githubusercontent.com/renedet/managerpp/master/Tutorial/imagens/img9.png)
No campo **Pasta NFS**, deve ser informado o diretorio onde foi montado o compartilhamento NFS.
Já no campo **Número de Processos** defina o total de processos que serão executados ao mesmo tempo (em paralelo), a informação inicial foi recuperada do sistema, baseado no número de nucleos do processador.
O **IP do Servidor** pode ser verificada na [interface gráfica do mestre](https://github.com/renedet/managerpp/blob/master/Tutorial/mestre.md), item 3.
**Obs:** Caso não necessite usar o compartilhamento NFS, coloque o caminho do diretório onde o escravo foi inicializado.

2. Ao Preencher os campos clique em **OK**
![Interface Gráfica do Escravo](https://raw.githubusercontent.com/renedet/managerpp/master/Tutorial/imagens/img10.png)

3. O aviso abaixo será informado caso tudo ocorra corretamente.
![Interface Gráfica do Escravo](https://raw.githubusercontent.com/renedet/managerpp/master/Tutorial/imagens/img11.png)

Quando terminar de configurar todos os nós escravos retorne para [interface do nó mestre](https://github.com/renedet/managerpp/blob/master/Tutorial/mestre.md#pausa-para-configurar-nós-escravos) para iniciar o processamento dos dados.
