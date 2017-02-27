# Tutorial de Configuração
=================================================

## Configurando nó Mestre
### Diretório compartilhado em rede NFS:
    mkdir p /home/user/nfs
    sudo su (Ubuntu) ou su (Debian)
    chown nobody:nogroup /home/user/nfs
    nano /etc/exports
      /home/user/nfs *(rw,no_subtree_check,async)
    exportfs -a
    /etc/init.d/nfs-kernel-server restart

### Instanciar Mestre:
    cd /home/user/nfs
    managerpp 20.12.2.101 8080 server
    http://20.12.2.101:8080/managerpp/mestre/index.xhtml

### [`Gerenciar Mestre (interface gráfica)`](https://github.com/renedet/managerpp/blob/master/Tutorial/mestre.md)

## Configurando nó Escravo
### Diretório compartilhado em rede NFS:
    mkdir p /home/user/nfs
    sudo mount 20.12.2.101:/home/user/nfs /home/user/nfs

## Instanciar Escravo:
    cd /home/user/nfs
    managerpp 20.12.2.101 8081 client
    http://20.12.2.101:8081/managerpp/escravo/index.xhtml

### [`Gerenciar Escravo (interface gráfica)`](https://github.com/renedet/managerpp/blob/master/Tutorial/escravo.md)
