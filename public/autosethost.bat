
echo 当前脚本所在路径为：%~dp0   
set batpath=%~dp0
set hostpath=%batpath%

:split

for /f "tokens=1,* delims=\" %%i in ("%batpath%") do (

  

  set batpath=%%j

if "%%i"=="public" set result=%xxx%
set xxx=%%i
)
if not "%batpath%"==""  goto split
if "%result%"==""  set result=%xxx%

echo %result%
echo %hostpath%
set "hostpath=%hostpath:~,-1%"
set vhost=C:\wamp\bin\apache\apache2.4.23\conf\extra\httpd-vhosts.conf
set mhost=C:\windows\system32\drivers\etc\hosts

echo. >>%vhost%
echo ^<VirtualHost *:80^> >>%vhost%
echo	DocumentRoot "%hostpath%" >>%vhost%
echo	ServerName www.%result%.coma >>%vhost%
echo	ServerAlias %result%.coma >>%vhost%
echo	^<Directory "%hostpath%"^> >>%vhost%
echo		Options +Indexes +Includes +FollowSymLinks +MultiViews >>%vhost%
echo		AllowOverride All >>%vhost%
echo		Require local >>%vhost%
echo	^</Directory^> >>%vhost%
echo ^</VirtualHost^> >>%vhost%

echo 127.0.0.1  www.%result%.coma >>%mhost%
echo www.%result%.coma >mytmp.text
CLIP < mytmp.text
del mytmp.text
cmd
