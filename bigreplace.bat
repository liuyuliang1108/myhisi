@echo off
rem 关闭回显
echo 欢迎使用批量替换流程工具
mshta vbscript:msgbox("查看上线网站及本地网站功能是否有异常？",36,"第一步")(window.close)
mshta vbscript:msgbox("请备份本地文件",36,"第二步")(window.close)

rem 启用"延缓环境变量扩充"
setlocal EnableDelayedExpansion

for /f "delims=" %%a in ('mshta "%~f0"') do set "f=%%a"
echo;选择的文件夹是%f%
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
var Folder = Shell.BrowseForFolder(0, "请选择本地项目所在文件夹", 8); //起始目录为：0桌面
if (Folder != null) {
    Folder = Folder.items();
    Folder = Folder.item();
    Folder = Folder.Path;
    new ActiveXObject('Scripting.FileSystemObject').GetStandardStream(1).Write(Folder);
}
close();
</script>