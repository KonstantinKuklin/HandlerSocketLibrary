sudo apt-get update
sudo apt-get install mysql-server-5.5 mysql-client-5.5 libmysqlclient-dev mysql-source-5.5
git clone https://github.com/DeNA/HandlerSocket-Plugin-for-MySQL.git hs
cd hs
git checkout 1.1.1
./autogen.sh
cp /usr/src/mysql/mysql-source-5.5.tar.gz .
tar -zxf mysql-source-5.5.tar.gz
./configure --with-mysql-source=mysql-5.5/
make
sudo make install
sudo cp ../tests/travis/hs.cnf /etc/mysql/conf.d/hs.cnf
sudo service mysql restart
mysql -e "SHOW PROCESSLIST;"
mysql -e 'CREATE DATABASE IF NOT EXISTS handlersocket;'
mysql -D handlersocket < ../tests/resources/preTests.sql
cd ../