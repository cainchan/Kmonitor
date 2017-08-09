#coding:utf-8
from distutils.core import setup
import py2exe
# ops = {"py2exe":{
# 	"includes":"urllib2,urllib",
# }}
setup(version = "0.0.1",
	description = "Monitor Miner",
	name = "Kmonitor",
	service=["Kmonitor"],
	# options=ops,
)
