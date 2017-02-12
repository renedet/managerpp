
import time
import BaseHTTPServer
import urlparse
import string
import os
import gerenciador.mestre as me
import socket
import json
import types


HOST_NAME = '127.0.0.1' # !!!REMEMBER TO CHANGE THIS!!!
PORT_NUMBER = 8080 # Maybe set this to 9000.
httpd = ''

class MyHandler(BaseHTTPServer.BaseHTTPRequestHandler):

    #gm = me.GerenciadorMestre(1,socket.gethostbyname(socket.gethostname()))
    gm = me.GerenciadorMestre()

    def resposta(self,s):
        
        if self.gm.getSetup():
            global HOST_NAME
            global PORT_NUMBER
            
            print 'resposta '+HOST_NAME+':'+str(PORT_NUMBER)
            #HOST_NAME = self.server.server_name.replace(".local",'')
            #HOST_NAME = self.server.server_ip
            PORT_NUMBER = self.server.server_port
            print 'resposta '+HOST_NAME+':'+str(PORT_NUMBER)
            self.gm.setSetup('',HOST_NAME,PORT_NUMBER)
                
        self.send_response(200)
        self.end_headers()
        parsed_path = urlparse.urlparse(self.path)
        
        # pasta 'site' === 'www' do servidor
        if(parsed_path.path.find('/mestre/')>=0 or parsed_path.path.find('/ext/')>=0):
            #self.wfile.write(parsed_path.path)
            if(os.path.isfile(os.getcwd()+parsed_path.path)):
                f = open(os.getcwd()+parsed_path.path, 'r')
                self.wfile.write(f.read())
            else:
                self.wfile.write(os.path.isfile(parsed_path.path))
                self.wfile.write(os.getcwd())
        else:
            #carrega dados conforme parametros
            result = str(self.gm.analisa(self.path))
            #print result
            result = result.replace("'",'"')
            result = str(result.replace('"true"','true'))
            result = str(result.replace('"false"','false'))
            result = result.replace('"[','[')
            result = result.replace(']"',']')
            print 'HttpRequest_'+result
            self.wfile.write(str(result))
            #self.wfile.write(self.path)
            #self.wfile.write(parsed_path.path.find('site'))
        return        

    def do_POST(self):
        self.resposta(self)
        
    def do_GET(self):        
        self.resposta(self)
        
def start(hostname=HOST_NAME,port=PORT_NUMBER):
    
    global PORT_NUMBER
    global HOST_NAME
    
    HOST_NAME = hostname
    PORT_NUMBER = port
    server_class = BaseHTTPServer.HTTPServer
    httpd = server_class((hostname, port), MyHandler)
    print time.asctime(), "Servidor Iniciou - %s:%s" % (hostname, port)
    print 'firefox http://'+hostname+':'+str(port)+'/managerpp/mestre/index.xhtml'
    #os.system('firefox http://'+hostname+':'+str(port)+'/mestre/index.xhtml')
    try:
        httpd.serve_forever()
    except KeyboardInterrupt:
        pass    

def close():
    global PORT_NUMBER
    global HOST_NAME
    if httpd:
        httpd.server_close()
        print time.asctime(), "Servidor Parou - %s:%s" % (HOST_NAME, PORT_NUMBER)
