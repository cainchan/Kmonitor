# Kmonitor
monitor Miner

## Server
```
git clone https://github.com/ikaychen/Kmonitor.git
cd Kmonitor/Server
composer install
php artisan key:generate
chmod 755 -R *
chmod 777 -R storage bootstrap/cache
cp .env.example .env
vim .env
```
## Client on windows
```
install python2.7 py2exe pywin32
git clone https://github.com/ikaychen/Kmonitor.git
cd Kmonitor/Client
make.bat
copy config.ini devcon.exe installservice.bat removeservice.bat dist
move dist kmonitor
```