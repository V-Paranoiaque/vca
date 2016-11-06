import multiprocessing
import socket
import subprocess
import json
import time
import base64
import hashlib
import configparser
import sys, os, time, atexit
from signal import SIGTERM
from Crypto.Cipher import AES
from Crypto import Random
from server import Server 
from vps import Vps 

vcakey = ''
localserver = Server()

def handle(connection, address):
    import logging
    global vcakey
    logging.basicConfig(level=logging.ERROR)
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

##Physical Server class
class VcaServer(object):
    
    ##Constructor
    #
     ##@param pidfile pid file
     ##@param stdin stdin
     ##@param stdout stdout
     ##@param stderr stderr
    def __init__(self, pidfile='/var/run/vcadaemon.pid', stdin='/dev/null', stdout='/dev/null', stderr='/dev/null'):
        import logging
        ##Loger
        self.logger = logging.getLogger("server")
        ##stdin
        self.stdin = stdin
        ##stdout
        self.stdout = stdout
        ##stderr
        self.stderr = stderr
        ##pid file
        self.pidfile = pidfile
   
   ##Daemonize the Daemon
    def daemonize(self):
        try:
            pid = os.fork()
            if pid > 0:
                return 0
        except OSError as e:
            sys.stderr.write("fork #1 failed: %d (%s)\n" % (e.errno, e.strerror))
            return 0
        
        sys.stdout.flush()
        sys.stderr.flush()
        si = open(self.stdin, 'rb')
        so = open(self.stdout, 'ab+')
        se = open(self.stderr, 'ab+', 0)
        os.dup2(si.fileno(), sys.stdin.fileno())
        os.dup2(so.fileno(), sys.stdout.fileno())
        os.dup2(se.fileno(), sys.stderr.fileno())

        atexit.register(self.delpid)
        pid = str(os.getpid())
        open(self.pidfile,'w+').write("%s\n" % pid)
        return 1
   
   ##Remove the Daemon's pid file
    def delpid(self):
        os.remove(self.pidfile)
    
    ##Start the Daemon
    def start(self):
        try:
            pf = open(self.pidfile,'r')
            pid = int(pf.read().strip())
            pf.close()
        except IOError:
            pid = None

        if pid:
            message = "pidfile %s already exist. vcadaemon already running?\n"
            sys.stderr.write(message % self.pidfile)
            sys.exit(1)
        
        if self.daemonize() > 0:
            self.run()
    
    ##Stop the Daemon
    def stop(self):
        try:
            pf = open(self.pidfile,'r')
            pid = int(pf.read().strip())
            pf.close()
        except IOError:
            pid = None
        if not pid:
            message = "pidfile %s does not exist. vcadaemon not running?\n"
            sys.stderr.write(message % self.pidfile)
            return

        try:
            while 1:
                os.kill(pid, SIGTERM)
                time.sleep(0.1)
        except OSError as err:
            err = str(err)
            if err.find("No such process") > 0:
                if os.path.exists(self.pidfile):
                    os.remove(self.pidfile)
            else:
                print(str(err))
                sys.exit(1)
    
    ##Restart the Daemon
    def restart(self):
        self.stop()
        self.start()

    ##Launch the Daemon main loop
    def run(self):
        if os.path.isfile('/usr/share/vca/daemon/vca.cfg'):
            global vcakey
            os.chmod('/usr/share/vca/daemon/vca.cfg', 0o400)
            config = configparser.ConfigParser()
            config.read("/usr/share/vca/daemon/vca.cfg")
            vcakey = hashlib.md5(config.get('DEFAULT', 'key').encode()).hexdigest()
            ##Daemon port
            self.port = int(config.get('DEFAULT', 'port'))
            ##Daemon host
            self.host = config.get('DEFAULT', 'host')
            
            self.logger.debug("listening")
            ##Store socket
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
    
    ##Load Tun and fuse modules
    def loadModules(self):
        tun = subprocess.Popen('lsmod | grep tun | wc -l', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
        if tun.stdout.readline().decode().strip() == '1':
            subprocess.call('modprobe tun', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
        
        fuse = subprocess.Popen('lsmod | grep fuse | wc -l', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
        if tun.stdout.readline().decode().strip() == '1':
            subprocess.call('modprobe fuse', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)

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
    elif action == 'move':
        if int(serverDest) > 0 :
            vps = Vps(serverDest)
            vps.move(para)
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
    elif action == 'backupDropbox':
        if int(serverDest) > 0 :
            vps = Vps(serverDest)
            vps.backupDropbox(para['token'], para['pass'])
    elif action == "avScan":
        return localserver.avScan()
    else:
        return 'Nothing : '+action
    return 'Nothing to return'

if __name__ == "__main__":
    import logging
    logging.basicConfig(level=logging.ERROR)
    vcaserver = VcaServer()
    try:
        logging.info("Listening")
        vcaserver.loadModules()
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
