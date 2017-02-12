#!/usr/bin/python

import os

# Verifica se existe result.mat
# se result.mat não existir entao cria result.mat

if os.path.isfile('result.mat'):
	file('result.mat','a+')
