@echo off
rem �رջ���
echo ��ӭʹ�������滻���̹���
mshta vbscript:msgbox("�鿴������վ��������վ�����Ƿ����쳣��",36,"��һ��")(window.close)
mshta vbscript:msgbox("�뱸�ݱ����ļ�",36,"�ڶ���")(window.close)

rem ����"�ӻ�������������"
setlocal EnableDelayedExpansion

for /f "delims=" %%a in ('mshta "%~f0"') do set "f=%%a"
echo;ѡ����ļ�����%f%
set b=localback
set c=%f%%b%
echo %c%
echo "%%c"
ren "%%f" "%%c"
md %f%
pause&exit /b
-->

<script>
var Shell = new ActiveXObject("Shell.Application");
var Folder = Shell.BrowseForFolder(0, "��ѡ�񱾵���Ŀ�����ļ���", 8); //��ʼĿ¼Ϊ��0����
if (Folder != null) {
    Folder = Folder.items();
    Folder = Folder.item();
    Folder = Folder.Path;
    new ActiveXObject('Scripting.FileSystemObject').GetStandardStream(1).Write(Folder);
}
close();
</script>