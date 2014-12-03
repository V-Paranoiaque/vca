import subprocess
import json
import shutil
import os
import time
import uuid

class Vps:
    
    def __init__(self, id):
        self.id = id
        #self.loadavg
        
        #Default value
        #self.physpages
        #self.swappages
        #self.diskspace
        self.diskspace_current = 0
        #self.diskinodes
        self.quotatime = 0
        self.cpuunits = 1000
        #self.ostemplate
        #self.origin_sample
        #self.hostname
        self.cpus = 1
        self.cpulimit = 100
        #self.ip
        self.onboot = 1
        
        #Other var
        self.ram         = 0
        self.ram_current = 0

    def setId(self, id):
        self.id = id

    def setIp(self, ip):
        self.ip = ip
    
    def setLoadavg(self, val):
        self.loadavg = val
    
    def setHostname(self, name):
        self.hostname = name
    
    def setNProc(self, nproc):
        if nproc == '-':
            self.nproc = 0
        else:
            self.nproc = nproc
    
    def setOstemplate(self, os):
        self.ostemplate = os
        
    def setDiskspace_current(self, val):
        self.diskspace_current = val
    
    def loadConf(self):
        conf = open("/etc/sysconfig/vz-scripts/"+self.id+".conf", "r")
        content = conf.read()
        content = content.replace('"','')
        lines = content.split('\n')
        
        for line in enumerate(lines):
            if line[1] != '' and line[1][0] != '#':
                line = line[1].split('=')
                
                if line[0] == 'PHYSPAGES':
                    self.physpages = line[1][2:]
                elif line[0] == 'SWAPPAGES':
                    self.swappages = line[1][2:]
                    if self.swappages[-1] == 'M':
                        self.swappages = int(self.swappages[:-1])*1024
                    elif self.swappages[-1] == 'G':
                        self.swappages = float(self.swappages[:-1])*1024*1024
                elif line[0] == 'DISKSPACE':
                    var = line[1].split(':')
                    if len(var) == 1 : 
                        self.diskspace = line[1]
                    else:
                        self.diskspace = var[1]
                    
                    if self.diskspace[-1] == 'M':
                        self.diskspace = int(self.diskspace[:-1])*1024
                    elif self.diskspace[-1] == 'G':
                        self.diskspace = int(float(self.diskspace[:-1])*1024*1024)
                    
                elif line[0] == 'DISKINODES':
                    self.diskinodes = line[1]
                elif line[0] == 'QUOTATIME':
                    self.quotatime = line[1]
                elif line[0] == 'CPUUNITS':
                    self.cpuunits = line[1]
                elif line[0] == 'OSTEMPLATE':
                    self.ostemplate = line[1]
                elif line[0] == 'ORIGIN_SAMPLE':
                    self.origin_sample = line[1]
                elif line[0] == 'HOSTNAME':
                    self.hostname = line[1]
                elif line[0] == 'CPUS':
                    self.cpus = line[1]
                elif line[0] == 'CPULIMIT':
                    self.cpulimit = line[1]
                elif line[0] == 'IP_ADDRESS':
                    self.ip = line[1]
                elif line[0] == 'ONBOOT':
                    if line[1] == 'no':
                        self.onboot = 0
                    else:
                        self.onboot = 1
                elif line[0] == 'PRIVVMPAGES':
                    var = line[1].split(':')
                    if var[-1] == 'unlimited':
                        self.ram = 0
                    else:
                        self.ram = (int(var[-1])/256)
                elif line[0] == 'MEMINFO':
                    var = line[1].split(':')
                    if var[0] == 'page':
                        self.ram = (int(var[1])/256)
        
        conf.close()
    
    def modConf(self, para):
        for (index, val) in para.items():
            if index == 'name':
                subprocess.Popen('vzctl set '+str(self.id)+' --hostname "'+str(val)+'" --save', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
            elif index == 'onboot':
                if int(val) == 1:
                    subprocess.Popen('vzctl set '+str(self.id)+' --onboot yes --save', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
                else:
                    subprocess.Popen('vzctl set '+str(self.id)+' --onboot no --save', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
            elif index == 'ipv4':
                subprocess.call('vzctl set '+str(self.id)+' --ipdel all --save', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
                subprocess.call('vzctl set '+str(self.id)+' --ipadd '+str(val)+' --save', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
            elif index == 'ram':
                if int(val) == 0:
                    subprocess.Popen('vzctl set '+str(self.id)+' --vmguarpages unlimited --save', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
                    subprocess.Popen('vzctl set '+str(self.id)+' --oomguarpages unlimited --save', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
                    subprocess.Popen('vzctl set '+str(self.id)+' --privvmpages unlimited:unlimited --save', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
                else:
                    subprocess.Popen('vzctl set '+str(self.id)+' --vmguarpages '+str(val)+'M --save', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
                    subprocess.Popen('vzctl set '+str(self.id)+' --oomguarpages '+str(val)+'M --save', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
                    subprocess.Popen('vzctl set '+str(self.id)+' --privvmpages '+str(val)+'M:'+str(val)+'M --save', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
            elif index == 'swap':
                if int(val) == 0:
                    subprocess.Popen('vzctl set '+str(self.id)+' --swappages 0:unlimited --save', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
                else:
                    subprocess.Popen('vzctl set '+str(self.id)+' --swappages 0:'+str(int(val)*4)+'M --save', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
            elif index == 'diskspace':
                subprocess.Popen('vzctl set '+str(self.id)+' --diskspace '+str(val)+'M:'+str(val)+'M --save', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
            elif index == 'diskinodes':
                subprocess.Popen('vzctl set '+str(self.id)+' --diskinodes '+str(val)+':'+str(int(int(val)*1.1))+' --save', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
            elif index == 'cpus':
                subprocess.Popen('vzctl set '+str(self.id)+' --cpus '+str(val)+' --save', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
            elif index == 'cpulimit':
                subprocess.Popen('vzctl set '+str(self.id)+' --cpulimit '+str(val)+' --save', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
            elif index == 'cpuunits':
                subprocess.Popen('vzctl set '+str(self.id)+' --cpuunits '+str(val)+' --save', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
    
    def setConf(self):
        conf = open("/etc/sysconfig/vz-scripts/"+self.id+".conf", "w+")
        
        #RAM
        fichier.write("PRIVVMPAGES=\"0:"+self.ram*256+"\"")
        fichier.write("MEMINFO=\"privvmpages:1\"")
        fichier.write("SWAPPAGES=\"0:"+self.swappages+"\"")
        
        #DISK
        fichier.write("DISKSPACE=\"0:"+self.diskspace+"\"")
        fichier.write("DISKINODES=\""+self.diskinodes+":"+round(self.diskinodes*1.1)+"\"")
        fichier.write("QUOTATIME=\""+self.quotatime+"\"")
        
        fichier.write("CPUUNITS=\""+self.cpuunits+"\"")
        
        #VE directory
        fichier.write("VE_ROOT=\"/vz/root/$VEID\"")
        fichier.write("VE_PRIVATE=\"/vz/private/$VEID\"")
        
        fichier.write("OSTEMPLATE=\""+self.ostemplate+"\"")
        fichier.write("ORIGIN_SAMPLE=\""+self.origin_sample+"\"")
        
        fichier.write("HOSTNAME=\""+self.hostname+"\"")
                
        fichier.write("CPUS=\""+self.cpus+"\"")
        fichier.write("IP_ADDRESS=\""+self.ip+"\"")
        
        if self.onboot == 0:
            fichier.write("ONBOOT=\"no\"")
        
        fichier.write("CPULIMIT=\""+self.cpulimit+"\"")
                        
        conf.close()

    def start(self):
        subprocess.Popen('vzctl start '+str(self.id)+' --wait', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)

    def stop(self):
        subprocess.Popen('vzctl stop '+str(self.id)+' --fast', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
    
    def restart(self):
        subprocess.Popen('vzctl restart '+str(self.id)+' --wait', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
    
    def destroy(self):
        subprocess.call('vzctl delete '+str(self.id), shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
            
    def password(self, password):
        subprocess.Popen('vzctl set '+str(self.id)+' --userpasswd root:'+password, shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
    
    def cmd(self, cmd):
        p = subprocess.Popen('vzctl exec '+str(self.id)+' '+str(cmd), shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
        text = ''
        for line in p.stdout.readlines():
            text += line.decode()
        
        return json.dumps(text)

    def reinstall(self, ostpl):
        shutil.copyfile('/etc/vz/conf/'+str(self.id)+'.conf', '/etc/vz/conf/ve-'+str(self.id)+'.conf-sample')
        self.stop()
        self.destroy()
        subprocess.call('vzctl create '+str(self.id)+' --ostemplate '+ostpl+' --config '+str(self.id), shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
        os.remove('/etc/vz/conf/ve-'+str(self.id)+'.conf-sample')
    
    def backupAdd(self):
        time = str(int(time.time()))
        name = "ve-dump."+self.id+"."+time
        path = "/vz/dump/"+name
        random = str(uuid.uuid4())
        os.mkdir(path)
        subprocess.Popen('cp -r /vz/private/'+self.id+'/root.hdd/* '+path+'/', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
        subprocess.Popen('tar cf '+path+'.tar '+path, shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
        subprocess.Popen('rm -rf '+path, shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
    
    def backupDelete(self, name):
        if os.path.isfile('/vz/dump/ve-dump.'+self.id+'.'+name+'.tar'):
            os.remove('/vz/dump/ve-dump.'+self.id+'.'+name+'.tar')
        
    def backupList(self):
        backup=[]
        begin = 've-dump.'+self.id+'.'
        for root, dirnames, files in os.walk('/vz/dump/'):
            for i in files:
                if i.endswith(".tar") and i.startswith(begin):
                    backup.append(i)
        return json.dumps(backup)
    
    def backupRestore(self, name):
        if os.path.isfile('/vz/dump/ve-dump.'+self.id+'.'+name+'.tar'):
            subprocess.call('rm -rf /vz/private/'+self.id+'/root.hdd/*', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
            subprocess.call('tar -xf /vz/dump/ve-dump.'+self.id+'.'+name+'.tar -C /vz/dump/', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
            subprocess.call('cp -r /vz/dump/vz/dump/ve-dump.'+self.id+'.'+name+'/ /vz/private/'+self.id+'/root.hdd/', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
            subprocess.call('rm -rf /vz/dump/vz/dump/ve-dump.'+self.id+'.'+name, shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
