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

Na arquitetura [`Mestre Escravo`](http://charm.cs.uiuc.edu/research/masterSlave)
o Mestre gerencia as atividades que serão executadas, e distribui os 
processos para os escravos executarem o processamento das atividades.
![`Diagrama Mestre Escravo do managerpp`](https://raw.githubusercontent.com/renedet/managerpp/master/imagens/mestre_escravo.png)

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

## Tutorial de Configuração

### [Mestre](https://github.com/renedet/managerpp/tree/master/Tutorial#configurando-nó-mestre)

### [Escravo](https://github.com/renedet/managerpp/tree/master/Tutorial#configurando-nó-escravo)


## Tempo de implantação

Se pré-instalado com distribuição [Grml](https://grml.org/) com boot para memória RAM, 5 minutos por nó.
