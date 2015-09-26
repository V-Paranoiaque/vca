import subprocess
import json
import shutil
import os
import time

class Vps:
    
    def __init__(self, id):
        self._id = id
        self.loadavg = 0
        
        #Default value
        #self.physpages
        #self.swappages
        #self.diskspace
        self.diskspace_current = 0
        #self.diskinodes
        self.quotatime = 0
        self.cpuunits = 1000
        self.ostemplate = ''
        #self.origin_sample
        self.hostname = ''
        self.cpus = 1
        self.cpulimit = 100
        self.ip = ''
        self.onboot = 1
        
        #Other var
        self.ram         = 0
        self.ram_current = 0
    
    def loadConf(self):
        conf = open("/etc/sysconfig/vz-scripts/"+self._id+".conf", "r")
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
                subprocess.call('vzctl set '+str(self._id)+' --hostname "'+str(val)+'" --save', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
            elif index == 'onboot':
                if int(val) == 1:
                    subprocess.call('vzctl set '+str(self._id)+' --onboot yes --save', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
                else:
                    subprocess.call('vzctl set '+str(self._id)+' --onboot no --save', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
            elif index == 'ipv4':
                subprocess.call('vzctl set '+str(self._id)+' --ipdel all --save', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
                subprocess.call('vzctl set '+str(self._id)+' --ipadd '+str(val)+' --save', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
            elif index == 'ram':
                if int(val) == 0:
                    subprocess.call('vzctl set '+str(self._id)+' --vmguarpages unlimited --save', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
                    subprocess.call('vzctl set '+str(self._id)+' --oomguarpages unlimited --save', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
                    subprocess.call('vzctl set '+str(self._id)+' --privvmpages unlimited:unlimited --save', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
                else:
                    subprocess.call('vzctl set '+str(self._id)+' --vmguarpages '+str(val)+'M --save', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
                    subprocess.call('vzctl set '+str(self._id)+' --oomguarpages '+str(val)+'M --save', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
                    subprocess.call('vzctl set '+str(self._id)+' --privvmpages '+str(val)+'M:'+str(val)+'M --save', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
            elif index == 'swap':
                if int(val) == 0:
                    subprocess.call('vzctl set '+str(self._id)+' --swappages 0:unlimited --save', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
                else:
                    subprocess.call('vzctl set '+str(self._id)+' --swappages 0:'+str(int(val)*4)+'M --save', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
            elif index == 'diskspace':
                subprocess.call('vzctl set '+str(self._id)+' --diskspace '+str(val)+'M:'+str(val)+'M --save', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
            elif index == 'diskinodes':
                subprocess.call('vzctl set '+str(self._id)+' --diskinodes '+str(val)+':'+str(int(int(val)*1.1))+' --save', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
            elif index == 'cpus':
                subprocess.call('vzctl set '+str(self._id)+' --cpus '+str(val)+' --save', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
            elif index == 'cpulimit':
                subprocess.call('vzctl set '+str(self._id)+' --cpulimit '+str(val)+' --save', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
            elif index == 'cpuunits':
                subprocess.call('vzctl set '+str(self._id)+' --cpuunits '+str(val)+' --save', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
    
    def start(self):
        subprocess.Popen('vzctl start '+str(self._id)+' --wait', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)

    def stop(self):
        subprocess.call('vzctl stop '+str(self._id)+' --fast', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
    
    def restart(self):
        subprocess.Popen('vzctl restart '+str(self._id)+' --wait', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
    
    def destroy(self):
        subprocess.call('vzctl delete '+str(self._id), shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
            
    def password(self, password):
        subprocess.Popen('vzctl set '+str(self._id)+' --userpasswd root:'+password, shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
    
    def cmd(self, cmd):
        p = subprocess.Popen('vzctl exec '+str(self._id)+' '+str(cmd), shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
        text = ''
        for line in p.stdout.readlines():
            text += line.decode()
        
        return json.dumps(text)

    def reinstall(self, ostpl):
        shutil.copyfile('/etc/vz/conf/'+str(self._id)+'.conf', '/etc/vz/conf/ve-'+str(self._id)+'.conf-sample')
        self.stop()
        self.destroy()
        subprocess.call('vzctl create '+str(self._id)+' --ostemplate '+ostpl+' --config '+str(self._id), shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
        os.remove('/etc/vz/conf/ve-'+str(self._id)+'.conf-sample')
    
    def move(self, destination):
        subprocess.Popen('vzmigrate '+destination+' '+str(self._id), shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
    
    def backupAdd(self):
        #Sys var
        vzprivate = "/vz/private/"+self._id
        vzbackup = "/vz/dump"
        vzwork = "/vz/work"
        backup = 've-dump.'+self._id+'.'+str(int(time.time()))+'.tar.gz'
        
        if not os.path.exists(vzbackup):
            os.mkdir(vzbackup)
        if not os.path.exists(vzwork):
            os.mkdir(vzwork)
        
        #Snapshot
        subprocess.call('vzctl snapshot '+self._id, shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
        
        #Save information
        if not os.path.exists(vzprivate+'/dump/'):
            os.mkdir(vzprivate+'/dump/')
        shutil.copyfile('/etc/vz/conf/'+self._id+'.conf', vzprivate+'/dump/ve.conf')
        
        #Compress
        subprocess.call('cd '+vzprivate+' && tar czvf '+vzwork+'/'+backup+' .', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
        
        #Clean and move backup
        shutil.rmtree(vzprivate+'/dump/')
        shutil.move(vzwork+'/'+backup, vzbackup)
        self.snapshotClean()
        
        return backup
    
    def backupDelete(self, name):
        if os.path.isfile('/vz/dump/ve-dump.'+self._id+'.'+name+'.tar'):
            os.remove('/vz/dump/ve-dump.'+self._id+'.'+name+'.tar')
        elif os.path.isfile('/vz/dump/ve-dump.'+self._id+'.'+name+'.tar.gz'):
            os.remove('/vz/dump/ve-dump.'+self._id+'.'+name+'.tar.gz')
        
    def backupList(self):
        backup=[]
        begin = 've-dump.'+self._id+'.'
        for root, dirnames, files in os.walk('/vz/dump/'):
            for i in files:
                if i.startswith(begin):
                    backup.append(i)
        return json.dumps(backup)
    
    def backupRestore(self, name):
        #Sys var
        vzprivate = "/vz/private/"+self._id
        vzbackup = "/vz/dump"
        
        #Tar
        if os.path.isfile('/vz/dump/ve-dump.'+self._id+'.'+name+'.tar'):
            method = 'tar'
        #TGZ
        elif os.path.isfile('/vz/dump/ve-dump.'+self._id+'.'+name+'.tar.gz'):
            method = 'tgz'
        else:
            return ''
        
        #Make repertory
        shutil.rmtree(vzprivate)
        os.mkdir(vzprivate)
        
        #Restore
        
        if method == 'tar':
            subprocess.call('cd '+vzprivate+' && tar xvf '+vzbackup+'/ve-dump.'+self._id+'.'+name+'.tar ./', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
        elif method == 'tgz':
            subprocess.call('cd '+vzprivate+' && tar xzvf '+vzbackup+'/ve-dump.'+self._id+'.'+name+'.tar.gz ./', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
        
        shutil.copyfile(vzprivate+'/dump/ve.conf', '/etc/vz/conf/'+self._id+'.conf')
        
        #Clean
        self.snapshotClean()        
        shutil.rmtree(vzprivate+'/dump')
    
    def backupDropbox(self, access_token, password):
        import dropbox
        
        vzbackup = "/vz/dump/"
        file = self.backupAdd()
        
        if password != '':
            new_file = file+'.openssl'
            subprocess.call('openssl aes-256-cbc -in '+vzbackup+file+' -out '+vzbackup+new_file+' -k '+password, shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
            os.remove(vzbackup+file)
            file = new_file
        
        client = dropbox.client.DropboxClient(access_token)
        print('linked account: ', client.account_info())
        f = open(vzbackup+file, 'rb')
        response = client.put_file('/'+file, f)
        
        os.remove(vzbackup+file)
        
        return response
    
    def snapshotClean(self):
        list = subprocess.Popen('vzctl snapshot-list '+self._id+' -H -o UUID', shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
        for line in list.stdout.readlines():
            bkp = line.decode()
            subprocess.call('vzctl snapshot-delete '+self._id+' --id '+bkp[1:-1], shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
        
        