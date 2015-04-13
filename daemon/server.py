import subprocess
import re
import os
import json
import shutil
import copy
import time
import urllib.request
from vps import Vps 

class Server:

    def VpsList(self):
        VpsList = list()
        p = subprocess.Popen('vzlist -H -ao ctid,numproc,status,ip,hostname,laverage,diskspace,physpages', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
        for line in p.stdout.readlines():
                vps = re.sub( '\s+', ' ', line.decode()).strip()
                vpsInfo = vps.split()
                vpsCurrent = Vps(vpsInfo[0])
                if vpsInfo[1] == '-':
                    vpsCurrent.nproc = 0
                else:
                    vpsCurrent.nproc = vpsInfo[1]
                vpsCurrent.ip = vpsInfo[3]
                vpsCurrent.hostname = vpsInfo[4]
                vpsCurrent.loadavg = vpsInfo[5]
                vpsCurrent.diskspace_current = vpsInfo[6]
                
                if vpsInfo[7] == '-':
                    vpsCurrent.ram_current = 0
                else:
                    vpsCurrent.ram_current = int(vpsInfo[7])*4
                
                vpsCurrent.loadConf()
                VpsList.append(vpsCurrent)

        return json.dumps(VpsList, default=encodeVps)
    
    def VpsNew(self, newId, os, hostname):
        vps = Vps(newId)
        subprocess.call('vzctl create '+str(vps._id)+' --ostemplate '+os+' --hostname '+hostname, shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
    
    def VpsClone(self, oldId, newId, ip, hostname):
        para = {"ipv4": ip, "name": hostname}
        
        oldVps = Vps(oldId)
        oldVps.stop()
        newVps = copy.copy(oldVps)
        newVps._id = newId
        
        os.mkdir('/vz/root/'+str(newVps._id))
        os.mkdir('/vz/private/'+str(newVps._id))
        shutil.copyfile('/etc/vz/conf/'+str(oldVps._id)+'.conf', '/etc/vz/conf/'+str(newVps._id)+'.conf')
        
        subprocess.call('pushd /vz/private/'+str(oldVps._id)+'; tar c --numeric-owner * | tar x --numeric-owner -C /vz/private/'+str(newVps._id)+'; popd', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
        newVps.modConf(para)
        
        oldVps.start()
        newVps.start()
        
    def VpsDelete(self, newId):
        vps = Vps(newId)
        vps.stop()
        vps.destroy()
    
    def templateCreate(self, id, name):
        subprocess.Popen('cd /vz/private/'+id+'/ && tar czf /vz/template/cache/'+name+'.tar.gz .', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
    
    def templateList(self):
        tpl=[]  
        for files in os.walk('/vz/template/cache/'):  
            for i in files:
                tpl.append(i)

        return json.dumps(tpl)
    
    def templateRename(self, oldName, newName):
        shutil.move('/vz/template/cache/'+oldName+'.tar.gz', '/vz/template/cache/'+newName+'.tar.gz')
    
    def templateAdd(self, name):
        g = urllib.request.urlopen('http://download.openvz.org/template/precreated/'+name)
        with open('/vz/template/cache/'+name, 'b+w') as f:
            f.write(g.read())
    
    def templateDelete(self, name):
        os.remove('/vz/template/cache/'+name+'.tar.gz')
        
    def templateDownload(self):
        vps = Vps(newId)
    
    def vpsExec(self, id, command):
        vps = Vps(id)
        p = subprocess.Popen('vzctl exec '+vps._id+' '+command, shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)

        text = ''
        for line in p.stdout.readlines():
            text += line
        
        return text
    
    def restart(self):
        subprocess.Popen('reboot', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
        
    def backupList(self):
        backup=[]
        begin = 've-dump.'
        for root, dirnames, files in os.walk('/vz/dump/'):
            for i in files:
                if i.startswith(begin):
                    backup.append(i)
        return json.dumps(backup)
    
    def avScan(self):
        errorList = list()
        scanTesult = subprocess.Popen('clamscan -ri /vz/root/', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
        for line in scanTesult.stdout.readlines():
            current = re.sub( '\s+', ' ', line.decode()).strip()
            if current.startswith('/vz/root/'):
                errorList.append(current[9:])
        return json.dumps(errorList)
    
def encodeVps(obj):
    if isinstance(obj, Vps):
        return obj.__dict__
    return obj
