@echo off

:: ��װwindows����
echo ���ڰ�װ�������Ժ�...
Kmonitor.exe -install

:: ���÷����Զ�����
echo ������������...
sc config Kmonitor start= AUTO

:: ��������
sc start Kmonitor

echo ���������ɹ�, �����������...
pause