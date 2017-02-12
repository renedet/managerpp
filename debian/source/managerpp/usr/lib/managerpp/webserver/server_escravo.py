
import time
import BaseHTTPServer
import urlparse
import string
import os
import gerenciador.escravo as es
import socket
import json
import types


HOST_NAME = '127.0.0.1' # !!!REMEMBER TO CHANGE THIS!!!
PORT_NUMBER = 8080 # Maybe set this to 9000.
httpd = ''

class MyHandler(BaseHTTPServer.BaseHTTPRequestHandler):        

    #ge = es.GerenciadorEscravo(1,socket.gethostbyname(socket.gethostname()))
    ge = es.GerenciadorEscravo()

    def resposta(self,s):
        
        if self.ge.getSetup():
            global HOST_NAME
            global PORT_NUMBER
            
            print 'resposta '+HOST_NAME+':'+str(PORT_NUMBER)
            #HOST_NAME = self.server.server_name.replace(".local",'')
            #HOST_NAME = self.server.server_ip
            PORT_NUMBER = self.server.server_port
            #print 'resposta '+HOST_NAME+':'+str(PORT_NUMBER)
            #quit()
            self.ge.setSetup('',HOST_NAME,PORT_NUMBER)
        
        self.send_response(200)        
        self.send_header('Date', self.date_time_string())
        self.send_header('Pragma','no-cache')
        #self.send_header('Content-Type','application/json; charset="utf-8"') 
        #self.send_header('Connection', 'close')
        #self.end_headers()
        parsed_path = urlparse.urlparse(self.path)
        
        # pasta 'site' === 'www' do servidor
        if(parsed_path.path.find('/escravo/')>=0 or parsed_path.path.find('/ext/')>=0):
            self.end_headers()
            #self.wfile.write(parsed_path.path)
            if(os.path.isfile(os.getcwd()+parsed_path.path)):
                f = open(os.getcwd()+parsed_path.path, 'r')
                self.wfile.write(f.read())
            else:
                self.wfile.write(os.path.isfile(parsed_path.path))
                self.wfile.write(os.getcwd())
        else:
            self.send_header('Content-Type','application/json; charset="utf-8"') 
            self.send_header('Connection', 'close')
            self.end_headers()
            #carrega dados conforme parametros
            result = str(self.ge.analisa(self.path))
            #print result
            result = result.replace("'",'"')
            #print result
            result = str(result.replace('"true"','true'))
            #result = str(result.replace("'true'",'true'))
            #print result
            result = str(result.replace('"false"','false'))
            #result = str(result.replace("'false'",'false'))
            print 'PEGA NAVEGADOR: '+result
            self.wfile.write(str(result))
            #self.wfile.close()
            self.wfile.flush()
            self.wfile.close()
            #self.handle_one_request()
            #self.connection.close()
            #self.wfile.write(self.path)
            #self.wfile.write(parsed_path.path.find('site'))
        return        

    def do_POST(self):
        self.resposta(self)
        return
        
    def do_GET(self):
        self.resposta(self)
        return
        
def start(hostname=HOST_NAME,port=PORT_NUMBER):
    global PORT_NUMBER
    global HOST_NAME
    PORT_NUMBER = port
    HOST_NAME = hostname
    server_class = BaseHTTPServer.HTTPServer
    httpd = server_class((hostname, port), MyHandler)
    print time.asctime(), "Servidor Iniciou - %s:%s" % (hostname, port)
    print 'firefox http://'+hostname+':'+str(port)+'/managerpp/escravo/index.xhtml'
    #os.system('firefox http://'+hostname+':'+str(port)+'/escravo/index.xhtml')
    try:
        httpd.serve_forever()
    except KeyboardInterrupt:
        pass    

def close():
    global HOST_NAME
    global PORT_NUMBER
    if httpd:
        httpd.server_close()
        print time.asctime(), "Servidor Parou - %s:%s" % (HOST_NAME, PORT_NUMBER)

