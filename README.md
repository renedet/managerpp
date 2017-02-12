# managerpp - Gerenciador de Processos Distribuídos
=================================================

O projeto deste software surgiu pela necessidade de melhorar o tempo de 
processamento de um algoritmo, desenvolvido em Matlab, o algoritmo tinha muitas 
iterações que demoravam muito. Com esta ideia foi desenvolvido este sistema distribuído e
pré-configurado visando reduzir o [`tempo de implantação`](https://github.com/renedet/managerpp#tempo-de-implantação) e processamento de dados.

## Download
Debian jessie [managerpp](https://github.com/renedet/managerpp/blob/master/debian/bin/jessie/managerpp_0.83.0713.deb)
  Para instalar:
    dpkg -i managerpp_0.83.0713.deb

## Arquitetura

[`Mestre Escravo`](http://charm.cs.uiuc.edu/research/masterSlave)

## Trabalhos Relacionados

### API - Application Programming Interface
[`Open MPI`](https://www.open-mpi.org/)
[`Gearman`](http://gearman.org/)

### Software
[`LoadLeveler`](http://www-03.ibm.com/systems/power/software/loadleveler/) - IBM
[`OpenPBS`](http://www.mcs.anl.gov/research/projects/openpbs/) - Nasa

## Dependências

Python 2.7 (obrigatório)
NFS (opcional)
grml (opcional)

## Outro software incluso [será alterado Ext JS]

Ext JS Library 3.4.0
  Ext JS is licensed under the terms of the Open Source GPL 3.0 license. 

jQuery JavaScript Library v1.4.4
  Dual licensed under the MIT or GPL Version 2 licenses.
  http://jquery.org/license

## Requisitos de hardware

* Mínimo de uso de memória RAM 512MB
* Interface de rede
* Processador com 2 núcleos ou mais
* Placa-mãe com interface USB
* Placa-mãe com inicialização da BIOS pela interface USB
* 1 Pendrive 2GB

## Configurando nó Mestre

### Diretório compartilhado em rede NFS:
    mkdir p /home/user/nfs
    sudo chown nobody:nogroup /home/user/nfs
    sudo nano /etc/exports
    /tmp/nfs *(rw,no_subtree_check,async)
    /tmp/nfs *(rw,sync,fsid=0)
    sudo exportfs a
    sudo /etc/init.d/nfskernelserver restart

### Instanciar Mestre:
    cd /home/user/nfs
    managerpp 172.16.6.99 8080 server
    http://172.16.6.99:8080/managerpp/mestre/index.xhtml

## Configurando nó Escravo
### Diretório compartilhado em rede NFS:
    mkdir p /home/user/nfs
    sudo mount 172.16.6.99:/home/user/nfs /home/user/nfs
## Instanciar Escravo:
    cd /home/user/nfs
    managerpp 172.16.4.111 8080 client
    http://172.16.4.111:8081/managerpp/escravo/index.xhtml


## Tempo de implantação

Se pré-instalado com distribuição [Grml](https://grml.org/) com boot para memória RAM.
