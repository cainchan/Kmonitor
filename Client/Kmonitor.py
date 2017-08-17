#coding:utf-8
# A sample service to be 'compiled' into an exe-file with py2exe.
#
# See also
#    setup.py - the distutils' setup script
#    setup.cfg - the distutils' config file for this
#    README.txt - detailed usage notes
#
# A minimal service, doing nothing else than
#    - write 'start' and 'stop' entries into the NT event log
#    - when started, waits to be stopped again.
#
import os
import sys
import time
import string
# import urllib2
import httplib
import ConfigParser
import threading
import win32serviceutil
import win32service
import win32event
import win32evtlogutil
from subprocess import Popen

class MyService(win32serviceutil.ServiceFramework):
    _svc_name_ = "Kmonitor"
    _svc_display_name_ = "Kmonitor"
    _svc_description_ = "Kmonitor"   #服务的描述

    def __init__(self, args):
        win32serviceutil.ServiceFramework.__init__(self, args)
        self.hWaitStop = win32event.CreateEvent(None, 0, 0, None)

        
    def SvcStop(self):
        self.ReportServiceStatus(win32service.SERVICE_STOP_PENDING)
        win32event.SetEvent(self.hWaitStop)

    def SvcDoRun(self):
        
        import servicemanager
        # Write a 'started' event to the event log...
        t = threading.Thread(target=pushMonitorData,args=[])
        t.start()
            


        win32evtlogutil.ReportEvent(self._svc_name_,
                                    servicemanager.PYS_SERVICE_STARTED,
                                    0, # category
                                    servicemanager.EVENTLOG_INFORMATION_TYPE,
                                    (self._svc_name_, ''))

        # wait for beeing stopped...
        win32event.WaitForSingleObject(self.hWaitStop, win32event.INFINITE)

        # and write a 'stopped' event to the event log.
        win32evtlogutil.ReportEvent(self._svc_name_,
                                    servicemanager.PYS_SERVICE_STOPPED,
                                    0, # category
                                    servicemanager.EVENTLOG_INFORMATION_TYPE,
                                    (self._svc_name_, ''))
def pushMonitorData():
    while(1):
        try:
            # read config
            cf = ConfigParser.ConfigParser()
            cf.read("c:\Kmonitor\config.ini")
            remoteEnable = cf.get("remoteconfig","enable")
            '''if int(remoteEnable):
                url = cf.get("remoteconfig","url")
                remoteconfig = urllib2.urlopen(url).read()
                file("RemoteConfig","wb").write(remoteconfig)
                cf.read("RemoteConfig")'''
            url = cf.get("config","url")
            wallet = cf.get("config","wallet")
            hostname = cf.get("config","hostname")
            sleeptime = cf.get("config","sleeptime")

            # get hostname
            if not hostname:hostname = string.lower(os.getenv("computername").split(".")[0].split("-")[1])
            # download file
            push_url = '%s/%s/%s'%(url,wallet,string.lower(hostname))
            # requests.get(push_url)
            # ret = urllib2.urlopen(push_url).read()
            httpClient = httplib.HTTPConnection('monitor.kaychen.cn', 80, timeout=30)
            httpClient.request('GET', '/api/pushMonitorData/%s/%s'%(wallet,string.lower(hostname)))
            response = httpClient.getresponse()
            file("c:\Kmonitor\log.txt","ab").write('%s Info: %s\r\n'%(time.ctime(),push_url))
            time.sleep(int(sleeptime))
        except Exception,e:
            file("c:\Kmonitor\log.txt","ab").write('%s Error: %s\r\n'%(time.ctime(),e))
            time.sleep(int(sleeptime))
    return

if __name__ == '__main__':
    # Note that this code will not be run in the 'frozen' exe-file!!!
    win32serviceutil.HandleCommandLine(MyService)
