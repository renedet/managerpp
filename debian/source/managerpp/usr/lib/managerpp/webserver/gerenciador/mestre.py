#!/usr/bin/python

import subprocess
import shlex
import string
import time
import itertools
import os
import urllib
import json
import urllib2
import types
from pprint import pprint
from threading import Thread
from escravo import GerenciadorEscravo

# pertence ao no Mestre
class ProcessoDesc:
    id = 0
    init_time = ''
    status = 'wait' # wait, running, killed, finished
    
    def __init__(self, id=0, init_time='',status='wait'):
        self.id = id
        self.init_time = init_time
        self.status = status # wait, running, killed, finished

# pertence ao no Mestre
class Escravo:
    id = 0
    ip = "0.0.0.0"    
    lista_processo = []
    # pp eh o max de processos q este no suporta
    pp = 1
    live = False
    thread = None
    
    def __init__(self, id=0, ip='0.0.0.0',pp=1):
        self.id = id
        self.ip = ip
        self.pp = pp
        self.live = True
        #self.processando = False

    """ { 
        processo: [
            { 
                id=0, 
                init_time=2011-03-13 20:01:32,
                status = 'runing'
            },
            {
            ...
            }
        ]
        }
    """

    def getProcessos(self, json):
        # analisar json
        # self.lista_processo.append(new ProcessoDesc(id,init_time,status))
        print json
    
    def getAlive(self,):
		if isinstance(self.thread,types.NoneType):
			return False
		else:
			return self.thread.is_alive()


# pertence ao no Escravo
class GerenciadorMestre:
    
    id = 0
    ip = None
    port = None
    flagProcesso = False
    itera_count = 0
    escravo_count = 0
    lista_escravo = []
    nfs_path = None
    #base_init = ''
    parametros = ''
    
    Gprocess_count = 0
    
    process_count = 0   # contador de processos instanciados, zera quando inicializa processamento
    total_pro = 0   # valor setado na inicializacao do mestre(interface web)
    
    fase01 = ''
    fase02 = ''
    fase03 = ''
    
    execFase = 0 # fase01 -> 1, fase02 -> 2, fase03 -> 3, fim -> 4
    
    threadProcessamento = None
    
    '''
    def __init__(self, nfs_path='', ip='0.0.0.0', port=8080):
        self.nfs_path = nfs_path
        self.ip = ip
        self.port = port        
        # inicializar webservice?
        # contactar servidor principal?
    '''
    
    '''    
    def getProcessosStatus(self):
        """
            verificar lista_processo
            pegar status de cada processo
            e montar json
        """
        json = []
        json.append('{ processo: [')
        i=0
        
        for processo in self.lista_processo:
            if i==1:
                json.append(',')
            json.append('{ id:'+str(processo.processo.pid)+',')
            json.append('init_time:'+time.strftime("%Y-%m-%d %H:%M:%S")+',')
            if processo.processo.poll() == None:
                json.append("status: 'runing'")
            else:
                json.append("status: 'finished'")
            i=1
            json.append('}')
        json.append(']}')

        return "".join(json)

    def getProcesso(self, id=0):
        return lista_processo[id]

    def setProcesso(self, cmd=''):
        self.lista_processo.append(Processo(self.process_count+1, '','wait',cmd))
        #inicializa processo
        #print cmd

    def startProcessos(self):
        for processo in self.lista_processo:
            print 'Iniciando processo: '+str(processo.id)
            processo.executar()
    '''
    
    def getTotalProcessos(self,x):
        res = len(self.parametros)
        if res > x:
            return x
        else:
            return res
        

    def MyHttpRequest(self,url,wait_result=True):
        #esse metodo precisa garantir que a solicitacao tera uma resposta!
        response=''
        if url.find('http://')>=0:
            url = urllib2.Request(url)
            try:
                response = urllib2.urlopen(url)
                if(wait_result):
                    response = response.read()
                else:
                    response.close()
                return response
            except urllib2.HTTPError:
                return ''
            except urllib2.URLError:
                return ''
        else:
            return ''
    
    def getSetup(self):
        if isinstance(self.nfs_path,types.NoneType) and isinstance(self.ip,types.NoneType) and isinstance(self.port,types.NoneType):
            return True
        return False
        
    def setSetup(self, nfs_path='', ip='0.0.0.0', port=8080):
        self.nfs_path = nfs_path
        self.ip = ip
        #print 'setSetup '+str(port)
        self.port = port
    
    def getEscravoProcessando(self):
		for escravo in self.lista_escravo:
			if escravo.getAlive():
				return True
		# se passar por todos e nao encontrar nenhum processando
		return False
    
    def getStatusProcesso(self):
        pass
    
    def analisa(self,query=''):
        #myvars = {'metodo':None, 'cmd':None, 'id':None, 'nfs_path':None, 'num_process':None, 'server_ip':None}
        myvars = {}
        #myvars = {'status': 'false'}
        
        '''
        ge = GerenciadorEscravo()
        print 'ge.getSetup() '+str(ge.getSetup())
        '''
        
        query = query.replace('%20',' ')
        query = query.replace('%27',"'")
        
        query = string.split(query, '?')
        if(len(query)>1):            
            if(query[0]=='/managerpp/ajax'):
                if(query[1]!=None):
                    query = string.split(query[1], '&')
                    print query
                    for vars in query:
                        aux = string.split(vars, '=')
                        myvars[aux[0]] = aux[1]
                        print myvars
        if myvars != None:
            if myvars.get('metodo',False):
                print 'Metodo: '+str(myvars.get('metodo'))
                '''
                if myvars.get('metodo') == 'setprocesso':
                    if myvars.get('pp',False):                        
                        for i in range(0, int(myvars.get('pp'))):
                            print i
                            if myvars.get('cmd',False):
                                self.setProcesso(myvars['cmd'])
                                myvars['status'] = 'true'
                            else:
                                myvars['status'] = 'false'                        
                    else:
                        if myvars.get('cmd',False):
                            self.setProcesso(myvars['cmd'])
                            myvars['status'] = 'true'
                elif myvars.get('metodo') == 'getprocessosstatus':
                    self.getProcessosStatus()
                elif myvars.get('metodo') == 'startprocessos':
                    self.startProcessos()
                elif myvars.get('metodo') == 'getprocesso':
                    if myvars.get('id',False):
                        self.getProcesso(myvars['id'])                
                '''
                if myvars.get('metodo') == 'escravos':
                    #jsonvars = {escravos:[{id:1,ip:'10.0.0.1',pp:10},{id:2,ip:'10.0.0.2',pp:11}]}
                    myjson = []
                    myjson.append('{ "escravos": [')
                    i=0
                    
                    print 'length(self.lista_escravo)'+str(len(self.lista_escravo))
                    
                    for escravo in self.lista_escravo:
                        #print processo
                        if i==1:
                            myjson.append(',')
                        print 'id: '+str(escravo.id)
                        myjson.append('{ "id":'+str(escravo.id)+',')
                        print 'ip: '+str(escravo.ip)
                        myjson.append('"ip":"'+escravo.ip+'",')
                        print 'pp: '+str(escravo.pp)
                        myjson.append('"pp": "'+str(escravo.pp)+'"')
                        i=1
                        myjson.append('}')
                    myjson.append(']}')

                    return "".join(myjson)
                    pass
                elif myvars.get('metodo') == 'status':
                    # verificar no existentes
                    # 0 no == status -> false
                    if len(self.lista_escravo)>0:
                        # Falta testar se nos ativos
                        myvars["status"] = 'true'
                        myvars["count_no"] = len(self.lista_escravo)
                    else:
                        myvars["status"] = 'false'
                        #self.ip, self.port = self.client_address
                        myvars["ip"] = self.ip
                        print str(self.port)
                        myvars["port"] = str(self.port)
                elif myvars.get('metodo') == 'initstatus':
                    if self.flagProcesso:
                        if not self.threadProcessamento.is_alive():
                            self.flagProcesso = False
                            myvars["processando"] = 'false'
                            myvars["path_ini"] = 'false'
                            myvars["status"] = 'false'
                        else:
                            myvars["fase01"] = self.fase01
                            myvars["fase02"] = self.fase02
                            myvars["fase03"] = self.fase03
                            #myvars["total_pro"] = self.total_pro
                            #myvars["base_init"] = self.base_init
                            myvars["path_ini"] = 'true'
                            myvars["nfs_path"] = len(self.nfs_path)
                            myvars["processando"] = 'true'
                            myvars["status"] = 'true'
                    else:
                        print 'Status do processamento'
                        print self.threadProcessamento
                        print self.flagProcesso
                        '''myvars["nfs_path"] = len(self.nfs_path)'''
                        myvars["nfs_path"] = os.getcwd()
                        myvars["processando"] = 'false'
                        myvars["path_ini"] = 'true'
                        myvars["status"] = 'false'
                elif myvars.get('metodo') == 'startprocessos':
                    if not self.flagProcesso:
                        if self.escravo_count>0:
                            #definindo fases
                            if myvars.get('fase01',False):
                                self.fase01 = myvars.get('fase01')
                            if myvars.get('fase02',False):
                                self.fase02 = myvars.get('fase02')
                            if myvars.get('fase03',False):
                                self.fase03 = myvars.get('fase03')
                            #if myvars.get('total_pro',False):
                            #    self.total_pro = int(myvars.get('total_pro'))
                            #    self.process_count = 0
                            #    self.itera_count = 0
                            #if myvars.get('base_init',False):
                            #    self.base_init = myvars.get('base_init')
                            if myvars.get('parametros',False):
                                #print 'PARAMETROS'
                                #print myvars.get('parametros').encode("utf-8")
                                #print myvars.get('parametros')
                                #print urllib.unquote(myvars.get('parametros').encode("utf-8"))
                                #raw_input('Aperte ENTER para prosseguir: ')
                                self.parametros = json.loads(urllib.unquote(myvars.get('parametros').encode("utf-8")))
                                p=[]
                                for j in self.parametros:
								    p.append(self.parametros.get(json.dumps(j).replace('"','')))
                                self.parametros = list(itertools.product(*p))
                                self.total_pro = len(self.parametros)
                                self.process_count = 0
                                self.itera_count = 0
                            
                            self.threadProcessamento=Thread( target=controleDeFluxo, args = (self,) )
                            self.threadProcessamento.start()
                            print 'Thread iniciada'
                            print self.threadProcessamento.is_alive()
                            
                            #definir valor da flag
                            self.flagProcesso = True
                            
                            myvars["status"] = 'true'
                        else:
                            #nao tem processo para iniciar
                            myvars["status"] = 'false'
                    else:
                        print 'processando'
                        myvars["status"] = 'false'
                elif myvars.get('metodo') == 'add_no':
                    pp=1
                    if myvars.get('pp',False):
                        pp = myvars.get('pp')
                    if myvars.get('ip',False):
                        #verificar se esse ip ja foi cadastrado
                        nalista = False
                        for escravo in self.lista_escravo:
                            if escravo.ip == myvars.get('ip'):
                                nalista = True
                        if not nalista:
                            self.escravo_count = self.escravo_count+int(pp)
                            self.lista_escravo.append(Escravo(self.escravo_count, myvars.get('ip'), int(pp)))
                elif myvars.get('metodo') == 'list_nfs_files':
                    print self.nfs_path
                    if len(self.nfs_path) >0:
                        i=0
                        files=[]
                        dirList=os.listdir(self.nfs_path)
                        for fname in dirList:
                            if os.path.isfile(self.nfs_path+'/'+fname):
                                if i==1:
                                    files.append(',')
                                files.append('{"file":"'+fname+'"}')
                                i=1
                        #myvars["files"] = '[{"file":"file_exec"},{"file":"file_exec2"}]';
                        myvars["files"] = '['+''.join(files)+']';
                        print myvars["files"]
                    if myvars.get('_dc',False):
                        del myvars["_dc"]
                    if myvars.get('metodo',False):
                        del myvars["metodo"]
                    # {"files":[{"file":"/home/renedet/nfspath/file_exec"},{"file":"/home/renedet/nfspath/file_exec2"}]}                    
                elif myvars.get('metodo') == 'break_process':
                    '''
                    if not isinstance(self.threadProcessamento, types.NoneType):
                        if self.threadProcessamento.isAlive():
                            self.threadProcessamento
                    '''
                    pass
                elif myvars.get('metodo') == 'setup':
                    if myvars.get('nfs_path',False):                        
                        if os.path.isdir(urllib.unquote(myvars['nfs_path'].encode("utf-8"))):
                            self.nfs_path = urllib.unquote(myvars['nfs_path'].encode("utf-8"))
                            myvars['nfs_path'] = 'true'
                            print self.nfs_path
                        else:
                            myvars['nfs_path'] = 'false'
                    if myvars.get('num_process',False):
                        self.max_active_process = myvars['num_process']
                        #Falta verificar quantos processos suporta, agora e como fazer isso?
                    myvars["status"] = 'true'
        return myvars

def controleEscravo(gerenciadormestre,escravo,processo,idprocesso):
	erro = True
	#url = 'http://'+escravo.ip+'/managerpp/ajax?metodo=setprocesso&idprocess='+str(idprocesso)+'&cmd=./'+urllib.quote(gerenciadormestre.fase02+' '+str(gerenciadormestre.itera_count)+' '+' '.join(processo))
	url = 'http://'+escravo.ip+'/managerpp/ajax?metodo=setprocesso&idprocess='+str(idprocesso)+'&cmd='+urllib.quote(gerenciadormestre.fase02+' '+' '.join(processo))
	print 'URL metodo=setprocesso'
	print url
	#url = urllib.unquote(url)
	auxjson = gerenciadormestre.MyHttpRequest(''.join(url),True)
	print auxjson
	if 0<len(auxjson):
		resultado = json.loads(auxjson)
		print resultado.get('status')
		if resultado.get('status'):
			url = urllib.unquote('http://'+escravo.ip+'/managerpp/ajax?metodo=startprocessos')
			print url
			gerenciadormestre.MyHttpRequest(''.join(url),False)
			erro = False
			teste = True
			# verificando se terminou de processar ou se esta ativo
			while teste:
				time.sleep(1)
				url = urllib.unquote('http://'+escravo.ip+'/managerpp/ajax?metodo=getprocessosstatus')
				auxjson = gerenciadormestre.MyHttpRequest(''.join(url),True)
				print 'verificando se processamento terminou'
				if len(auxjson)==0:
					time.sleep(2)
					auxjson = gerenciadormestre.MyHttpRequest(''.join(url),True)
					if len(auxjson)==0:
						# devolvendo processo para lista
						gerenciadormestre.lista_escravo.append(processo)
						# marcando escravo como inativo
						escravo.live=False
						#escravo.processando=False
						teste = False
						erro = True
						break;
				if len(auxjson)>0:
					processos = json.loads(auxjson)
					processos = processos.get('processo')
					p=0
					for pro in processos:
						print pro.get('status')
						if 'finished'!=pro.get('status'):
							p+=1
					if p==0:
						# todos os processos enceraram
						teste = False
						#escravo.processando=False
	if erro:
		# devolvendo processo para lista
		gerenciadormestre.lista_escravo.append(processo)
		# marcando escravo como inativo
		escravo.live=False
	

def controleDeFluxo(gerenciadormestre):
    
    # verificando se fase02 existe
    if os.path.exists(gerenciadormestre.nfs_path):
        if os.path.isdir(gerenciadormestre.nfs_path):
            while True:
                aux = True
                #iniciando fase01 (sem parametros)
                gerenciadormestre.execFase = 1
                print 'Verificando result.mat'
                # verifica se result.mat existe para executar fase01
                if os.path.isfile(gerenciadormestre.nfs_path+'/result.mat'):
                    #processar gerenciadormestre.fase01
                    print 'Iniciando fase01'
                    ge = GerenciadorEscravo()
                    ge.setSetup(gerenciadormestre.nfs_path,'',0)
                    #gerenciadormestre.process_count+=1
                    #nao conta processos internos
                    print 'Processo adicionado?'
                    # adiciona fase01 na lista para ser executado
                    print ge.setProcesso('./'+gerenciadormestre.fase01,1)
                    print 'Processo iniciado?'
                    # inicia execucao de fase01
                    print ge.startProcessos()
                    
                    print 'Lista de Processos '+str(len(ge.lista_processo)) #0!!!
                    for processo in ge.lista_processo:
                        print 'Status do processo: '+processo.status
                        #while processo.poll() < 0:
                        while isinstance(processo.processo.poll(),types.NoneType):
                            #espera x segundos para testar de novo
                            time.sleep(1)
                    # fase01 analisa result.mat para gerar true.file
                    if os.path.isfile(gerenciadormestre.nfs_path+'/true.file'):
                        print gerenciadormestre.nfs_path+'/true.file gerado!'
                        aux = False
                    else:
                        print '# '+str(gerenciadormestre.itera_count)
                else:
                    print 'Pulou fase01'
                    #time.sleep(10)
                if aux == False:
                    print 'Finalizando processamento!'
                    break
                #else:
                #    print 'Valor de aux:'
                #    print aux
                #iniciando fase02 (numiterac, filename, processo_id) processo_id e passado pelo escravo
                gerenciadormestre.execFase = 2
                #enviando processos para os nos
                #falta definir o numero de processo para cada no
                print 'Iniciando fase02'
                gerenciadormestre.itera_count+=1
                #time.sleep(10)
                idprocesso=0
                while 0 < len(gerenciadormestre.parametros) or gerenciadormestre.getEscravoProcessando():
					#print 'verificando escravo livre'
					# o problema ocorre se nao houver escravos ativos escravo.live==True
					time.sleep(2)
					for escravo in gerenciadormestre.lista_escravo:
						#time.sleep(5)
						#pprint (vars(escravo))
						if escravo.live:
							#print 'escravo processando'
							#print escravo.getAlive()
							#if False==escravo.processando:
							if not escravo.getAlive():
								#escravo.processando=True
								#print 'escravo.pp'
								print escravo.pp
								processos_dist = gerenciadormestre.getTotalProcessos(escravo.pp)
								print 'distribuindo processo'
								print processos_dist
								for i in range(1,processos_dist+1):
									# contador de processos he diferente de len(gerenciadormestre.parametros)
									idprocesso+=1
									escravo.thread=Thread( target=controleEscravo, args = (gerenciadormestre,escravo,gerenciadormestre.parametros.pop(),idprocesso,) )
									escravo.thread.start()
                """
                #while gerenciadormestre.process_count < gerenciadormestre.total_pro:
                while gerenciadormestre.process_count < len(gerenciadormestre.parametros):
                    #print 'process_count '+str(gerenciadormestre.process_count)
                    #print 'total_pro '+str(gerenciadormestre.total_pro)
                    #local_process_count = gerenciadormestre.process_count
                    # distribuindo processos para os nos
                    for escravo in gerenciadormestre.lista_escravo:
                        #gerenciadormestre.process_count+=1
                        #url = 'http://'+escravo.ip+'/ajax?metodo=setprocesso&pp='+str(escravo.pp)+'&idprocess='+str(gerenciadormestre.process_count)+'&cmd=./'+urllib.quote(gerenciadormestre.fase02+' '+str(gerenciadormestre.itera_count)+' '+gerenciadormestre.nfs_path+'/'+gerenciadormestre.base_init+' '+str(gerenciadormestre.process_count))
                        #url = 'http://'+escravo.ip+'/ajax?metodo=setprocesso&pp='+str(escravo.pp)+'&idprocess='+str(gerenciadormestre.process_count)+'&cmd=./'+urllib.quote(gerenciadormestre.fase02+' '+str(gerenciadormestre.itera_count)+' '+gerenciadormestre.nfs_path+'/'+gerenciadormestre.base_init)
                        processos_dist = gerenciadormestre.getTotalProcessos(escravo.pp)
                        #url = 'http://'+escravo.ip+'/managerpp/ajax?metodo=setprocesso&pp='+str(processos_dist)+'&idprocess='+str(1+local_process_count+gerenciadormestre.Gprocess_count)+'&cmd=./'+urllib.quote(gerenciadormestre.fase02+' '+str(gerenciadormestre.itera_count)+' '+gerenciadormestre.nfs_path+'/'+gerenciadormestre.base_init)
                        # preciso guardar estes dados para poder recuperar caso fique fora do ar
                        # falta os parametros self.parametros
                        #url = 'http://'+escravo.ip+'/managerpp/ajax?metodo=setprocesso&pp='+str(processos_dist)+'&idprocess='+str(1+local_process_count+gerenciadormestre.Gprocess_count)+'&cmd=./'+urllib.quote(gerenciadormestre.fase02+' '+str(gerenciadormestre.itera_count)+' '+str(gerenciadormestre.parametros[gerenciadormestre.itera_count]))
                        for i in range(1,processos_dist+1):
							url = 'http://'+escravo.ip+'/managerpp/ajax?metodo=setprocesso&idprocess='+str(1+local_process_count+gerenciadormestre.Gprocess_count)+'&cmd=./'+urllib.quote(gerenciadormestre.fase02+' '+str(gerenciadormestre.itera_count)+' '+str(gerenciadormestre.parametros[gerenciadormestre.itera_count]))
							print url
							gerenciadormestre.MyHttpRequest(''.join(url),False)
							url = urllib.unquote('http://'+escravo.ip+'/managerpp/ajax?metodo=getprocessosstatus')
							auxjson = gerenciadormestre.MyHttpRequest(''.join(url),True)
							#time.sleep(1)
							print auxjson
							processos = json.loads(auxjson)
							processos = processos.get('processo')
							for p in processos:
								if p.get('status',False):
									print 'OK'
                        #local_process_count = gerenciadormestre.process_count
                    
                    #solicitando para nos processarem
                    print 'Iniciando processos'
                    #time.sleep(10)
                    for escravo in gerenciadormestre.lista_escravo:
                        url = urllib.unquote('http://'+escravo.ip+'/managerpp/ajax?metodo=startprocessos')
                        print url
                        gerenciadormestre.MyHttpRequest(''.join(url),False)
                    
                    #verifica de tempos em tempos se todos os nos terminaram de processar
                    #verificador...
                    print 'Verificando se processos terminaram'
                    #time.sleep(10)
                    verificador = 1
                    while verificador > 0:
                        verificador = 0
                        # menor tempo de processamento fase02
						# 80 seg * 8 processos >= 1040 seg >= 17,33 minutos
                        time.sleep(30)
                        for escravo in gerenciadormestre.lista_escravo:
                            url = urllib.unquote('http://'+escravo.ip+'/managerpp/ajax?metodo=getprocessosstatus')
                            while True:
								auxjson = gerenciadormestre.MyHttpRequest(''.join(url),True)
								if len(auxjson)>0:
									break
								else:
									escravo.live = False
                            # se caso nao houver resposta o metodo anterior
                            # tera que distribuir o processo para outro no
                                                        
                            #print auxjson
                            processos = json.loads(auxjson)
                            processos = processos.get('processo')
                            for p in processos:
								# enquanto todos os processos nao terminar
								# de executar vai continuar verificando
                                if p.get('status') != 'finished':
                                    verificador+=1
                """
                # inicializando contator para proximo ciclo
                #gerenciadormestre.Gprocess_count+=gerenciadormestre.process_count
                #gerenciadormestre.process_count = 0
                
                #se todos os nos finalizaram executa fase03
                
                #iniciando fase03 (numiterac)
                print 'Iniciando fase03'
                #time.sleep(10)
                gerenciadormestre.execFase = 3
                #para gerar gerenciadormestre.nfs_path+'/result.mat'
                ge = GerenciadorEscravo()
                ge.setSetup(gerenciadormestre.nfs_path,'',0)
                #gerenciadormestre.process_count+=1
                print ge.setProcesso('./'+gerenciadormestre.fase03+' '+str(gerenciadormestre.itera_count),1)
                print ge.startProcessos()
                for processo in ge.lista_processo:
                    #esperando processo terminar
                    #while processo.poll() < 0:
                    while isinstance(processo.processo.poll(),types.NoneType):
                        #espera x segundos para testar de novo
                        time.sleep(1)
                
                print 'Fim do ciclo '+str(gerenciadormestre.itera_count)
                #time.sleep(10)
                #incrementa iterador
                #gerenciadormestre.itera_count+=1
        else:
            gerenciadormestre.flagProcesso = False
    else:
        gerenciadormestre.flagProcesso = False
    gerenciadormestre.flagProcesso = False
