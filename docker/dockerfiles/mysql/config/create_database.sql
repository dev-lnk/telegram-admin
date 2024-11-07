CREATE DATABASE IF NOT EXISTS `telegram_admin`;
CREATE DATABASE IF NOT EXISTS `telegram_admin_test`;

CREATE USER IF NOT EXISTS 'telegram_admin'@'%' IDENTIFIED BY '12345';

GRANT ALL PRIVILEGES ON `telegram_admin`.* TO 'telegram_admin'@'%';
GRANT ALL PRIVILEGES ON `telegram_admin_test`.* TO 'telegram_admin'@'%';

GRANT SELECT  ON `information\_schema`.* TO 'telegram_admin'@'%';
FLUSH PRIVILEGES;