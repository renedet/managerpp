#!/usr/bin/python

import subprocess
import shlex
import string
import time
import os
import urllib
import json
import urllib2
import types
import multiprocessing

# YYYY-MM-DD HH:MM:SS



# pertence ao no Escravo
class Processo:
    id = 0
    init_time = ''
    status = 'wait' # wait, runing, killed, finished
    comando = ''
    processo = None
    
    def __init__(self, id=0, status='wait',comando = ''):
        self.id = id
        self.status = status # wait, runing, killed, finished
        self.comando = comando # wait, runing, killed, finished
    
    def executar(self):
        #inicializa processo comando
        print 'Tentando executar processo: '+self.comando
        #self.processo = subprocess.Popen(shlex.split(self.comando), stdout=subprocess.PIPE, stderr=subprocess.PIPE)
        #if not isinstance(self.nfs_path, types.NoneType):
        #cmd = shlex.split(self.comando)
        #self.processo = subprocess.Popen(cmd, stdout=subprocess.PIPE, stderr=subprocess.STDOUT,shell=False)
        print 'PASTA PROCESSO'
        subprocess.Popen('pwd', shell=True, executable='/bin/bash')
        #self.processo = subprocess.Popen(self.comando, shell=True)
        self.processo = subprocess.Popen(self.comando, shell=True, executable='/bin/bash')
        self.init_time = time.strftime("%Y-%m-%d %H:%M:%S")
        self.status = 'running'
        print 'Executando processo: '+self.comando
        print 'PID: '+str(self.processo.pid)
        return True
        
    def setStatus(self, status = 'wait'):
        #define status do processo
        print status
    
    def getStatus(self):
        #falta verificar status pelo pid do processo
        if not isinstance(self.processo,types.NoneType):
            if not isinstance(self.processo.pid,types.NoneType):
                #   poll() => None running | poll() => 0 finished
                if not isinstance(self.processo.poll(),types.NoneType):
                    self.status = 'finished'
                else:
                    self.status = 'running'
        return self.status

# pertence ao no Escravo
class GerenciadorEscravo:
    
    id = 0
    ip = None
    port = None
    process_count = 0
    max_active_process = 0
    lista_processo = []
    nfs_path = None
    
    '''
    def __init__(self, id=0, ip='0.0.0.0'):
        self.id = id
        self.ip = ip
        # contactar servidor principal?
    '''
    
    def getProcessosStatus(self):
        """
            verificar lista_processo
            pegar status de cada processo
            e montar json
        """
        myjson = []
        myjson.append('{ "nfs_path": "'+os.getcwd()+'",')
        myjson.append('"processadores": "'+str(multiprocessing.cpu_count())+'",')
        myjson.append('"processo": [')
        i=0
        
        for processo in self.lista_processo:
            #print processo
            if i==1:
                myjson.append(',')
            myjson.append('{ "id":'+str(processo.id)+',')
            if not isinstance(processo.processo,types.NoneType):
                myjson.append('"pid":'+str(processo.processo.pid)+',')
            myjson.append('"init_time":"'+processo.init_time+'",')
            myjson.append('"status": "'+processo.getStatus()+'",')
            myjson.append('"cmd": "'+processo.comando+'"')
            i=1
            myjson.append('}')
        myjson.append(']}')

        return "".join(myjson)

    def getProcesso(self, id=0):
        return lista_processo[id]

    def setProcesso(self, cmd='',id=0):
        if not isinstance(self.nfs_path, types.NoneType):
            executavel = cmd.split(' ')[0]
            self.process_count+=1
            self.lista_processo.append(Processo(id, 'wait',cmd))
            return True
            # verifica se executavel existe
            """
            if os.path.isfile(self.nfs_path+'/'+executavel):
                self.process_count+=1
                #self.lista_processo.append(Processo(self.process_count, 'wait',self.nfs_path+'/'+cmd))
                self.lista_processo.append(Processo(id, 'wait',cmd))
                return True
                #inicializa processo
                #print cmd
            else:
                return False
            """
        else:
            return False

    def startProcessos(self):
        processos_ini = 0
        for processo in self.lista_processo:
            if processo.status == 'wait':
                print 'Iniciando processo: '+str(processo.id)
                if processo.executar():
                    processos_ini+=1
        return processos_ini

    def MyHttpRequest(self,url,wait_result=True):
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
        
    def setSetup(self, nfs_path='', ip='0.0.0.0', port=8081):
        self.nfs_path = nfs_path
        self.ip = ip        
        self.port = port
    
    def analisa(self,query=''):
        #myvars = {'metodo':None, 'cmd':None, 'id':None, 'nfs_path':None, 'num_process':None, 'server_ip':None}
        myvars = {'status': 'false'}
        query = query.replace('%20',' ')
        query = query.replace('%27',"'")
        
        query = string.split(query, '?')
        if(len(query)>1):
            if(query[0]=='/managerpp/ajax'):
                if(query[1]!=None):
                    query = string.split(query[1], '&')
                    for vars in query:
                        aux = string.split(vars, '=')
                        myvars[aux[0]] = aux[1]
        if myvars != None:
            if myvars.get('metodo',False):
                if myvars.get('metodo') == 'setprocesso':
                    if myvars.get('pp',False):
                        for i in range(1, int(myvars.get('pp'))+1):
                            #print i
                            if myvars.get('cmd',False):
                                if myvars.get('idprocess',False):
                                    id = int(myvars['idprocess'])+i-1
                                    if self.setProcesso(myvars['cmd']+' '+str(id),id):
                                        myvars['status'] = 'true'
                                    else:
                                        myvars['status'] = 'false'
                                else:
                                    myvars['status'] = 'false'
                            else:
                                myvars['status'] = 'false'
                    else:
                        if myvars.get('cmd',False):
                            if myvars.get('idprocess',False):
                                if self.setProcesso(myvars['cmd'],myvars['idprocess']):
                                    myvars['status'] = 'true'
                                else:
                                    myvars['status'] = 'false'
                            else:
                                myvars['status'] = 'false'
                elif myvars.get('metodo') == 'getprocessosstatus':
                    myvars = self.getProcessosStatus()
                elif myvars.get('metodo') == 'startprocessos':
                    if self.startProcessos()>0:
                        myvars['status'] = 'true'
                    else:
                        myvars['status'] = 'false'
                elif myvars.get('metodo') == 'getprocesso':
                    if myvars.get('id',False):
                        self.getProcesso(myvars['id'])
                elif myvars.get('metodo') == 'setup':
                    if myvars.get('nfs_path',False):
                        #print urllib.unquote(myvars['nfs_path'].encode("utf-8"))
                        if os.path.isdir(urllib.unquote(myvars['nfs_path'].encode("utf-8"))):
                            self.nfs_path = urllib.unquote(myvars['nfs_path'].encode("utf-8"))
                            print 'self.nfs_path '+self.nfs_path
                            myvars['nfs_path'] = 'true'
                        else:
                            myvars['nfs_path'] = 'false'
                    if myvars.get('num_process',False):
                        self.max_active_process = myvars['num_process']
                    if myvars.get('server_ip',False):
                        url = urllib.unquote(myvars.get('server_ip')+'/managerpp/ajax?pp='+str(self.max_active_process)+'&metodo=add_no&ip='+self.ip+':'+str(self.port))
                        if url.find('http://')>=0:
                            print url
                            aux = self.MyHttpRequest(''.join(url),True)
                            if(len(aux)):
                                myvars['server_ip'] = 'true'
                            else:
                                myvars['server_ip'] = 'false'
                            '''
                            url = urllib2.Request(url)
                            try:
                                response = urllib2.urlopen(url)
                                response = response.read()
                            except urllib2.HTTPError:
                                response = ''
                            except urllib2.URLError:
                                response = ''
                            if(len(response)):
                                myvars['server_ip'] = 'true'
                            else:
                                myvars['server_ip'] = 'false'
                            '''
                        else:
                            myvars['server_ip'] = 'false'
        return myvars
