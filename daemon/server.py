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

    def VpsList():
        VpsList = list()
        p = subprocess.Popen('vzlist -H -ao ctid,numproc,status,ip,hostname,laverage,diskspace,physpages', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
        for line in p.stdout.readlines():
                vps = re.sub( '\s+', ' ', line.decode()).strip()
                vpsInfo = vps.split()
                vpsCurrent = Vps(vpsInfo[0])
                vpsCurrent.setNProc(vpsInfo[1])
                vpsCurrent.setIp(vpsInfo[3])
                vpsCurrent.setHostname(vpsInfo[4])
                vpsCurrent.setLoadavg(vpsInfo[5])
                vpsCurrent.setDiskspace_current(vpsInfo[6])
                
                if vpsInfo[7] == '-':
                    vpsCurrent.ram_current = 0
                else:
                    vpsCurrent.ram_current = int(vpsInfo[7])*4
                
                vpsCurrent.loadConf()
                VpsList.append(vpsCurrent)

        return json.dumps(VpsList, default=encodeVps)
    
    def VpsNew(self, newId, os, hostname):
        vps = Vps(newId)
        subprocess.call('vzctl create '+str(vps.id)+' --ostemplate '+os+' --hostname '+hostname, shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
    
    def VpsClone(self, oldId, newId, ip, hostname):
        para = {"ipv4": ip, "name": hostname}
        
        oldVps = Vps(oldId)
        oldVps.stop()
        newVps = copy.copy(oldVps)
        newVps.setId(newId)
        
        os.mkdir('/vz/root/'+str(newVps.id))
        os.mkdir('/vz/private/'+str(newVps.id))
        shutil.copyfile('/etc/vz/conf/'+str(oldVps.id)+'.conf', '/etc/vz/conf/'+str(newVps.id)+'.conf')
        
        subprocess.call('pushd /vz/private/'+str(oldVps.id)+'; tar c --numeric-owner * | tar x --numeric-owner -C /vz/private/'+str(newVps.id)+'; popd', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
        newVps.modConf(para)
        
        oldVps.start()
        newVps.start()
        
    def VpsDelete(self, newId):
        vps = Vps(newId)
        vps.stop()
        vps.destroy()
    
    def templateCreate(self, id, name):
        subprocess.Popen('cd /vz/private/'+id+'/ && tar czf /vz/template/cache/'+name+'.tar.gz .', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
    
    def templateList():
        tpl=[]  
        for files in os.walk('/vz/template/cache/'):  
            for i in files:
                tpl.append(i)

        return json.dumps(tpl)
    
    def templateRename(oldName, newName):
        shutil.move('/vz/template/cache/'+oldName+'.tar.gz', '/vz/template/cache/'+newName+'.tar.gz')
    
    def templateAdd(name):
        g = urllib.request.urlopen('http://download.openvz.org/template/precreated/'+name)
        with open('/vz/template/cache/'+name, 'b+w') as f:
            f.write(g.read())
    
    def templateDelete(name):
        os.remove('/vz/template/cache/'+name+'.tar.gz')
        
    def templateDownload(self):
        vps = Vps(newId)
    
    def vpsExec(self, id, command):
        vps = Vps(id)
        p = subprocess.Popen('vzctl exec '+vps.id+' '+command, shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)

        text = ''
        for line in p.stdout.readlines():
            text += line
        
        return text
    
    def restart():
        subprocess.Popen('reboot', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
        
    def backupList(self):
        backup=[]
        begin = 've-dump.'
        for root, dirnames, files in os.walk('/vz/dump/'):
            for i in files:
                if i.endswith(".tar") and i.startswith(begin):
                    backup.append(i)
        return json.dumps(backup)
    
def encodeVps(obj):
    if isinstance(obj, Vps):
        return obj.__dict__
    return obj
