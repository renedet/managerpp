#!/usr/bin/python

import os

# Verifica se existe result.mat(criado na fase03)
# se result.mat existir entao cria true.file
# true.file encerra a execução

if os.path.isfile('result.mat'):
	file('true.file','a+')
