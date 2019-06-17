echo 开机自启动程序将在40秒后开始!!
ping -n 40 127.0.0.1

echo 启动数据库
cd C:\Program Files\PremiumSoft\Navicat for MySQL
start navicat.exe

ping -n 10 127.0.0.1

echo 启动wamp
cd C:\wamp
start wampmanager.exe

ping -n 10 127.0.0.1

echo 启动火狐
cd C:\Program Files\Mozilla Firefox
start firefox.exe

ping -n 10 127.0.0.1

echo 启动PHPSTORM
cd C:\Program Files\JetBrains\PhpStorm 2018.3.4\bin
start phpstorm64.exe
	
cmd