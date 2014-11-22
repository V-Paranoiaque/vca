import multiprocessing
import socket
import subprocess
import json
import time
import os.path
import base64
import hashlib
from Crypto.Cipher import AES
from Crypto import Random
from server import Server 
from vps import Vps 

vcakey = ''

def handle(connection, address):
    import logging
    global vcakey
    logging.basicConfig(level=logging.DEBUG)
    logger = logging.getLogger("process-%r" % (address,))
    try:
        logger.debug("Connected %r at %r", connection, address)
        while True:
            data = connection.recv(1024)
            if data == "" or data.decode('utf-8') == "":
                logger.debug("Socket closed remotely")
                break
            logger.debug("Received data %r", data)
            
            data = json.loads(data.decode('utf-8'))
            
            if data['iv']:
                AES.key_size=128
                crypt_object=AES.new(key=vcakey,mode=AES.MODE_CBC,IV=base64.b64decode(data['iv']))
                decrypted=crypt_object.decrypt(base64.b64decode(data['data']))
    
                logger.debug("Received data %r", decrypted.decode('utf-8').strip('\x00'))
                logger.debug("Sent data1")
                
                do = json.loads(decrypted.decode('utf-8').strip('\x00'))
                answer = (16) * ' '
                answer = answer + vcaAction(do['action'], do['server'], do['para'])
                answer = answer + (16 - len(answer) % 16) * ' '
                answer = base64.b64encode(crypt_object.encrypt(answer))
                connection.send(bytes(answer))
            connection.send(bytes('close', 'UTF-8'))
        logger.debug("Sent data2")
    except:
        logger.exception("Problem handling request")
    finally:
        logger.debug("Closing socket")
        connection.close()

class VcaServer(object):
    def __init__(self, hostname, port):
        import logging
        self.logger = logging.getLogger("server")
        self.hostname = hostname
        self.port = port

    def start(self):
        if os.path.isfile('vcakey.conf'):
            global vcakey
            file = open('vcakey.conf','r')
            vcakey = file.readline();
            vcakey = vcakey.replace('\n', '')
            vcakey = hashlib.md5(vcakey.encode()).hexdigest()
            file.close()
        
        self.logger.debug("listening")
        self.socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        self.socket.bind((self.hostname, self.port))
        self.socket.listen(1)

        while True:
            conn, address = self.socket.accept()
            self.logger.debug("Got connection")
            process = multiprocessing.Process(target=handle, args=(conn, address))
            process.daemon = True
            process.start()
            self.logger.debug("Started process %r", process)

def vcaAction(action, serverDest, para):
    
    # Server And VPS
    if action == 'VpsList':
        return Server.VpsList()
    elif action == 'create':
        if serverDest != 0:
            server = Server()
            server.VpsNew(serverDest, para['os'], para['name'])
            vps = Vps(serverDest)
            vps.modConf(para)
    elif action == 'setConf':
        if int(serverDest) != 0:
            server = Vps(serverDest)
            server.modConf(para)
    elif action == 'start':
        if int(serverDest) != 0:
            server = Vps(serverDest)
            server.start()
    elif action == 'stop':
        if int(serverDest) != 0:
            server = Vps(serverDest)
            server.stop()
    elif action == 'restart':
        if int(serverDest) == 0:
            server = Server()
        else:
            server = Vps(serverDest)
        server.restart()
    elif action == 'delete':
        if int(serverDest) > 0:
            server = Server()
            server.VpsDelete(serverDest)
    elif action == 'clone':
        if int(serverDest) > 0 and int(para['dest']) > 0 :
            server = Server()
            server.VpsClone(serverDest, para['dest'], para['ip'], para['hostname'])
    elif action == 'password':
        if int(serverDest) > 0 :
            server = Vps(serverDest)
            server.password(para)
    elif action == 'cmd':
        if int(serverDest) > 0 :
            server = Vps(serverDest)
            return server.cmd(para)
        return ''
    #Templates
    elif action == 'reinstall':
        if int(serverDest) > 0 :
            server = Vps(serverDest)
            server.reinstall(para)
    elif action == 'templateList':
        if int(serverDest) == 0:
            return Server.templateList()
    else:
        return 'Nothing : '+action
    return 'Nothing to return'

if __name__ == "__main__":
    import logging
    logging.basicConfig(level=logging.DEBUG)
    server = VcaServer("0.0.0.0", 10000)
    try:
        logging.info("Listening")
        server.start()
    except:
        logging.exception("Unexpected exception")
    finally:
        logging.info("Shutting down")
        for process in multiprocessing.active_children():
            logging.info("Shutting down process %r", process)
            process.terminate()
            process.join()
    logging.info("All done")
