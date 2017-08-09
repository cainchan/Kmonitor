@echo off

:: 安装windows服务
echo 正在安装服务，请稍候...
Kmonitor.exe -install

:: 设置服务自动启动
echo 正在启动服务...
sc config Kmonitor start= AUTO

:: 启动服务
sc start Kmonitor

echo 服务启动成功, 按任意键继续...
pause