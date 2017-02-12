#!/usr/bin/python

import socket
import sys
import random
from webserver import server_mestre as server

def getipaddrs():
    try:
        hostname = socket.gethostname()
        result = socket.getaddrinfo(hostname, None, 0, socket.SOCK_STREAM)
        return [x[4][0] for x in result]
    except Interrupt:
        pass    
    return None 

if __name__=="__main__":
    
    server_name = '127.0.0.1'
    #port = 8080+random.randrange(100) + 1
    port = 8080
    if len(sys.argv)>1:
        if sys.argv[1]!=None:
            server_name = sys.argv[1]
            if len(sys.argv)>2:
                if sys.argv[2]!=None:
                    port = int(sys.argv[2])
    else:
        #server_name = socket.gethostbyname(socket.gethostname())
        server_name = getipaddrs()
    
    '''    
    print ''.join(server_name)
    print port
    '''
    server.start(''.join(server_name),port)
    
