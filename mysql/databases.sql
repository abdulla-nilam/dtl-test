# create databases
CREATE DATABASE IF NOT EXISTS `dtl_db`;

# create root user and grant rights
CREATE USER 'root'@'localhost' IDENTIFIED BY 'root';
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%';

CREATE USER 'user'@'localhost' IDENTIFIED BY 'root';
GRANT ALL PRIVILEGES ON *.* TO 'user'@'%';
GRANT ALL PRIVILEGES ON dtl_db.* TO 'user'@'%' WITH GRANT OPTION;