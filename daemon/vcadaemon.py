import multiprocessing
import socket
import subprocess
import json
import time
import os.path
import base64
import hashlib
import configparser
from Crypto.Cipher import AES
from Crypto import Random
from server import Server 
from vps import Vps 

vcakey = ''
localserver = Server()

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
    def __init__(self):
        import logging
        self.logger = logging.getLogger("server")
    
    def start(self):
        if os.path.isfile('vca.cfg'):
            global vcakey
            os.chmod('vca.cfg', 0o400)
            config = configparser.ConfigParser()
            config.read("vca.cfg")
            vcakey = hashlib.md5(config.get('DEFAULT', 'key').encode()).hexdigest()
            self.port = int(config.get('DEFAULT', 'port'))
            self.host = config.get('DEFAULT', 'host')
            
            self.logger.debug("listening")
            self.socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
            self.socket.bind((self.host, self.port))
            self.socket.listen(1)
            
            while True:
                conn, address = self.socket.accept()
                self.logger.debug("Got connection")
                process = multiprocessing.Process(target=handle, args=(conn, address))
                process.daemon = True
                process.start()
                self.logger.debug("Started process %r", process)

def vcaAction(action, serverDest, para):
    global localserver
    
    # Server And VPS
    if action == 'VpsList':
        return localserver.VpsList()
    elif action == 'create':
        if serverDest != 0:
            localserver.VpsNew(serverDest, para['os'], para['name'])
            vps = Vps(serverDest)
            vps.modConf(para)
    elif action == 'modConf':
        if int(serverDest) != 0:
            vps = Vps(serverDest)
            vps.modConf(para)
    elif action == 'start':
        if int(serverDest) != 0:
            vps = Vps(serverDest)
            vps.start()
    elif action == 'stop':
        if int(serverDest) != 0:
            vps = Vps(serverDest)
            vps.stop()
    elif action == 'restart':
        if int(serverDest) == 0:
            localserver.restart()
        else:
            vps = Vps(serverDest)
            vps.restart()
    elif action == 'delete':
        if int(serverDest) > 0:
            localserver.VpsDelete(serverDest)
    elif action == 'clone':
        if int(serverDest) > 0 and int(para['dest']) > 0 :
            localserver.VpsClone(serverDest, para['dest'], para['ip'], para['hostname'])
    elif action == 'password':
        if int(serverDest) > 0 :
            vps = Vps(serverDest)
            vps.password(para)
    elif action == 'cmd':
        if int(serverDest) > 0 :
            vps = Vps(serverDest)
            return vps.cmd(para)
        return ''
    #Templates
    elif action == 'reinstall':
        if int(serverDest) > 0 :
            vps = Vps(serverDest)
            vps.reinstall(para)
    elif action == 'templateList':
        if int(serverDest) == 0:
            return localserver.templateList()
    elif action == 'templateRename':
        if int(serverDest) == 0:
            localserver.templateRename(para['old'], para['new'])
    elif action == 'templateAdd':
        if int(serverDest) == 0:
            localserver.templateAdd(para)
    elif action == 'templateDelete':
        if int(serverDest) == 0 and para != '':
            localserver.templateDelete(para)
    elif action == 'backupAdd':
        if int(serverDest) > 0 :
            vps = Vps(serverDest)
            vps.backupAdd()
    elif action == 'backupDelete':
        if int(serverDest) > 0 and int(para) > 0:
            vps = Vps(serverDest)
            vps.backupDelete(para)
    elif action == 'backupList':
        if int(serverDest) > 0 :
            vps = Vps(serverDest)
            return vps.backupList()
        else:
            return localserver.backupList()
    elif action == 'backupRestore':
        if int(serverDest) > 0 and int(para) > 0:
            pid  = str(subprocess.call('vzlist -H -ao numproc '+serverDest, shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT))
            pid  = pid.strip()
            vps = Vps(serverDest)
            if pid != '-':
                vps.stop()
            vps.backupRestore(para)
            if pid != '-':
                vps.start()
    else:
        return 'Nothing : '+action
    return 'Nothing to return'

if __name__ == "__main__":
    import logging
    logging.basicConfig(level=logging.DEBUG)
    vcaserver = VcaServer()
    try:
        logging.info("Listening")
        vcaserver.start()
    except:
        logging.exception("Unexpected exception")
    finally:
        logging.info("Shutting down")
        for process in multiprocessing.active_children():
            logging.info("Shutting down process %r", process)
            process.terminate()
            process.join()
    logging.info("All done")
