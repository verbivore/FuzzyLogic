#*****************************************
# Create the poker database, users and tables
#*****************************************
# mysql -h localhost -u root --local-infile=1 -p --verbose
# or SOURCE pokcreate.sql 
# or \. pokcreate.sql
#
# SHOW VARIABLES LIKE 'version';
SELECT VERSION(), CURRENT_DATE;
SELECT USER();
SHOW GRANTS FOR 'root'@'localhost';
SHOW DATABASES;

#*****************************************
SELECT '       * Creating poker database' AS 'STATUS:***********************';
DROP SCHEMA IF EXISTS poker;
CREATE DATABASE poker;
USE poker;
SELECT DATABASE();
SHOW TABLES;

SELECT '       * Creating poker users' AS 'STATUS:***********************';
SELECT user FROM mysql.user;
DROP USER 'juik'@'localhost';
CREATE USER 'juik'@'localhost' IDENTIFIED BY 'kiuj';
GRANT ALL PRIVILEGES ON poker.* TO 'juik'@'localhost';
GRANT ALL PRIVILEGES ON poker.* TO 'dave'@'localhost';
SHOW GRANTS FOR 'juik'@'localhost';

#*****************************************
SELECT '       * Creating members table' AS 'STATUS:***********************';
CREATE TABLE members (
  member_id 	SMALLINT(2) 	UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT UNIQUE,
  nickname 	VARCHAR(20) 	NOT NULL UNIQUE, 
  name_last 	VARCHAR(20) 	NOT NULL, 
  name_first 	VARCHAR(20) 	NOT NULL,
  status 	ENUM('A','X') 	COMMENT 'Active, X=inactive',
  email 	VARCHAR(50) 	NOT NULL UNIQUE, 
  phone 	VARCHAR(20) 	NOT NULL UNIQUE, 
  stamp 	TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL
) COMMENT 'member data (by effective date).'
;
SHOW WARNINGS;
DESCRIBE members;

SELECT '       * Loading members table' AS 'STATUS:***********************';
LOAD DATA LOCAL INFILE 'data/members.txt' 
  INTO TABLE members
#  FIELDS TERMINATED BY ','
  SET stamp = CURRENT_TIMESTAMP
  ;
SHOW WARNINGS;
SELECT COUNT(*) AS "Rows Loaded"
  FROM members;


#*****************************************
SELECT '       * Creating games table' AS 'STATUS:***********************';
CREATE TABLE games (
  game_id 	SMALLINT(2) 	UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT UNIQUE,
  game_date 	DATE 	NOT NULL 	COMMENT 'Start of effective period.', 
  member_snack 	SMALLINT(2) 	UNSIGNED,
  member_host 	SMALLINT(2) 	UNSIGNED,
  member_gear 	SMALLINT(2) 	UNSIGNED,
  member_caller SMALLINT(2) 	UNSIGNED,
  stamp 	TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP 
)
; 
SHOW WARNINGS;
DESCRIBE games;
SELECT '       * Loading games table' AS 'STATUS:***********************';
LOAD DATA LOCAL INFILE 'data/games.txt' 
  INTO TABLE games
  SET stamp = CURRENT_TIMESTAMP
  ;
SHOW WARNINGS;
SELECT COUNT(*) AS "Rows Loaded"
  FROM games;

#*****************************************
SELECT '       * Creating seats table' AS 'STATUS:***********************';
CREATE TABLE seats (
  game_id 	SMALLINT(2) 	UNSIGNED NOT NULL,
  member_id 	SMALLINT(2) 	UNSIGNED NOT NULL,
  response 	ENUM('I','Y','N','M','F') COMMENT 'Invited, Yes, No, Maybe, Flaked',
  note_member 	VARCHAR(255) 	NOT NULL COMMENT 'member comments',
  note_master 	VARCHAR(255) 	NOT NULL COMMENT 'master comments',
  stamp 	TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
  PRIMARY KEY (game_id, member_id)
) COMMENT 'Rate modifier for a particular seat done for a particular game.'
;
SHOW WARNINGS;
DESCRIBE seats;
SELECT '       * Loading seats table' AS 'STATUS:***********************';
LOAD DATA LOCAL INFILE 'data/seats.txt' 
  INTO TABLE seats
  SET stamp = CURRENT_TIMESTAMP
  ;
SHOW WARNINGS;
SELECT COUNT(*) AS "Rows Loaded"
  FROM seats;


SHOW TABLES;
#*****************************************
SELECT '       * Done' AS 'STATUS:***********************';
