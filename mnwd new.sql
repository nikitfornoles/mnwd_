CREATE DATABASE `mnwd`;
/*
DROP DATABASE `mnwd`;
SHOW databases;
USE `mnwd`;
*/

/*
CREATE TABLE IF NOT EXISTS `images` (
	`id` int(11) UNIQUE NOT NULL AUTO_INCREMENT,
	`image` varchar(200) NOT NULL,
	PRIMARY KEY (`id`);
);
*/

CREATE TABLE IF NOT EXISTS `user` (
	`userid` int(11) UNIQUE NOT NULL AUTO_INCREMENT,
	`firstname` varchar(50) DEFAULT NULL,
	`lastname` varchar(50) DEFAULT NULL,
	`usertype` varchar(15) NOT NULL,
	`username` varchar(30) UNIQUE DEFAULT NULL,
	`password` varchar(35) DEFAULT NULL,
	`email` varchar(45) UNIQUE DEFAULT NULL,
	`registered` tinyint(1) NOT NULL,
	`admin_announcement` tinyint(1),
	`admin_incidentreport` tinyint(1),
	`admin_billinfo` tinyint(1),
	PRIMARY KEY (`userid`)
 );

INSERT INTO `user` VALUES
(1, 'Eric', 'Zantua', 'admin', 'admin0', 'd33a63e99f64cdd8f17a380ce7c1c79c', 'mnwdtest@gmail.com', 1, 1, 1, 1),
(2, 'Juan', 'Dela Cruz', 'concessionaire', NULL, NULL, NULL, 0, 0, 0, 0),
(3, 'Amy', 'Winehouse', 'concessionaire', NULL, NULL, NULL, 0, 0, 0, 0),
(4, 'Audrey', 'Kitching', 'concessionaire', NULL, NULL, NULL, 0, 0, 0, 0),
(5, 'Byper', 'Buendia', 'concessionaire', NULL, NULL, NULL, 0, 0, 0, 0);


CREATE TABLE IF NOT EXISTS `announcement` (
	`announcementid` int(11) UNIQUE NOT NULL AUTO_INCREMENT,
	`announcement` longblob NOT NULL,
	`isimage` tinyint(1) NOT NULL,
	`imgname` varchar(50),
	`date` date NOT NULL,
	`userid` int(11) NOT NULL,
	FOREIGN KEY (`userid`) REFERENCES `user` (`userid`),
	PRIMARY KEY (`announcementid`)
);

CREATE TABLE IF NOT EXISTS `account_classification` (
	`classcode` varchar(3) UNIQUE NOT NULL,
	`classification` varchar(20) DEFAULT NULL,
	PRIMARY KEY (`classcode`)
);

INSERT INTO `account_classification` VALUES 
('res', 'Residential'),
('com', 'Commercial'),
('coA', 'Commercial A'),
('coB', 'Commercial B'),
('coC', 'Commercial C'),
('bul', 'Bulk/Wholesale');

CREATE TABLE IF NOT EXISTS `consumption_range` (
	`rangeid` int(11) UNIQUE NOT NULL AUTO_INCREMENT,
	`min` int(11) UNIQUE,
	`max` int (11) UNIQUE,
	PRIMARY KEY (`rangeid`)
);

INSERT INTO `consumption_range` VALUES 
(1, 11, 20),
(2, 21, 30),
(3, 31, 40),
(4, 41, 50),
(5, 51, 2147483647);

CREATE TABLE IF NOT EXISTS `water_rate` (
	`rateid` int(11) UNIQUE NOT NULL AUTO_INCREMENT,
	`classcode` varchar(3) NOT NULL,
	`rangeid` int(11) NOT NULL,
	`rate` double(4,2) NOT NULL,
	FOREIGN KEY (`classcode`) REFERENCES `account_classification` (`classcode`),
	FOREIGN KEY (`rangeid`) REFERENCES `consumption_range` (`rangeid`),
	PRIMARY KEY (`rateid`)
);

INSERT INTO `water_rate` VALUES
(1, 'res', 1, 14.40),
(2, 'res', 2, 15.50),
(3, 'res', 3, 16.60),
(4, 'res', 4, 17.70),
(5, 'res', 5, 18.95),
(6, 'com', 1, 28.80),
(7, 'com', 2, 31.00),
(8, 'com', 3, 33.20),
(9, 'com', 4, 35.40),
(10, 'com', 5, 37.90),
(11, 'coA', 1, 25.20),
(12, 'coA', 2, 27.10),
(13, 'coA', 3, 29.05),
(14, 'coA', 4, 30.95),
(15, 'coA', 5, 33.15),
(16, 'coB', 1, 21.60),
(17, 'coB', 2, 23.25),
(18, 'coB', 3, 24.90),
(19, 'coB', 4, 26.55),
(20, 'coB', 5, 28.40),
(21, 'coC', 1, 18.00),
(22, 'coC', 2, 19.35),
(23, 'coC', 3, 20.75),
(24, 'coC', 4, 22.10),
(25, 'coC', 5, 23.65),
(26, 'bul', 1, 43.20),
(27, 'bul', 2, 46.50),
(28, 'bul', 3, 49.80),
(29, 'bul', 4, 53.10),
(30, 'bul', 5, 56.85);

CREATE TABLE IF NOT EXISTS `meter_size` (
	`sizeid` int(11) UNIQUE NOT NULL AUTO_INCREMENT,
	`size` double(4,2),
	PRIMARY KEY (`sizeid`)
);

INSERT INTO `meter_size` VALUES 
(1, 0.5),
(2, 0.75),
(3, 1),
(4, 1.5),
(5, 2),
(6, 3),
(7, 4),
(8, 6),
(9, 8),
(10, 10);

CREATE TABLE IF NOT EXISTS `min_charge` (
	`chargeid` int(11) AUTO_INCREMENT,
	`classcode` varchar(30) NOT NULL,
	`sizeid` int(11) NOT NULL,
	`mincharge` double(8,2),
	FOREIGN KEY (`classcode`) REFERENCES `account_classification` (`classcode`),
	FOREIGN KEY (`sizeid`) REFERENCES `meter_size` (`sizeid`),
	PRIMARY KEY (`chargeid`)
);

INSERT INTO `min_charge` VALUES
(1, 'res', 1, 125),
(2, 'res', 2, 200),
(3, 'res', 3, 400),
(4, 'res', 4, 1000),
(5, 'res', 5, 2500),
(6, 'res', 6, 4500),
(7, 'res', 7, 9000),
(8, 'res', 8, 15000),
(9, 'res', 9, 24000),
(10, 'res', 10, 34500),
(11, 'com', 1, 250),
(12, 'com', 2, 400),
(13, 'com', 3, 800),
(14, 'com', 4, 2000),
(15, 'com', 5, 5000),
(16, 'com', 6, 9000),
(17, 'com', 7, 18000),
(18, 'com', 8, 30000),
(19, 'com', 9, 48000),
(20, 'com', 10, 69000),
(21, 'coA', 1, 218.75),
(22, 'coA', 2, 350),
(23, 'coA', 3, 700),
(24, 'coA', 4, 1750),
(25, 'coA', 5, 4375),
(26, 'coA', 6, 7875),
(27, 'coA', 7, 15750),
(28, 'coA', 8, 26250),
(29, 'coA', 9, 42000),
(30, 'coA', 10, 60375),
(31, 'coB', 1, 187.50),
(32, 'coB', 2, 300),
(33, 'coB', 3, 600),
(34, 'coB', 4, 1500),
(35, 'coB', 5, 3750),
(36, 'coB', 6, 6750),
(37, 'coB', 7, 13500),
(38, 'coB', 8, 22500),
(39, 'coB', 9, 36000),
(40, 'coB', 10, 51750),
(41, 'coC', 1, 156.25),
(42, 'coC', 2, 250),
(43, 'coC', 3, 500),
(44, 'coC', 4, 1250),
(45, 'coC', 5, 3125),
(46, 'coC', 6, 5625),
(47, 'coC', 7, 11250),
(48, 'coC', 8, 18750),
(49, 'coC', 9, 30000),
(50, 'coC', 10, 43125),
(51, 'bul', 1, 375),
(52, 'bul', 2, 600),
(53, 'bul', 3, 1200),
(54, 'bul', 4, 3000),
(55, 'bul', 5, 7500),
(56, 'bul', 6, 13500),
(57, 'bul', 7, 27000),
(58, 'bul', 8, 45000),
(59, 'bul', 9, 72000),
(60, 'bul', 10, 103500);

CREATE TABLE IF NOT EXISTS `account` (
	`accountid` int(11) UNIQUE NOT NULL AUTO_INCREMENT,
	`accountno` varchar(12) DEFAULT NULL,
	`address` varchar(80) DEFAULT NULL,
	`meterno` varchar(12) DEFAULT NULL,
	`sizeid` int (11) DEFAULT NULL,
	`status` tinyint(1) DEFAULT NULL,
	`discounted` tinyint(1) DEFAULT NULL,
	`discountrate` double(4,2) DEFAULT NULL,
	`seniorcitizen` tinyint(1) DEFAULT NULL,
	`classcode` varchar(3) DEFAULT NULL,
	`userid` int(11) NOT NULL,
	`activated` tinyint(1) NOT NULL,
	FOREIGN KEY (`sizeid`) REFERENCES `meter_size` (`sizeid`),
	FOREIGN KEY (`classcode`) REFERENCES `account_classification` (`classcode`),
	FOREIGN KEY (`userid`) REFERENCES `user` (`userid`),
	PRIMARY KEY (`accountid`)
);

INSERT INTO `account` VALUES 
(1, '486-12-621', NULL, '94044810', 1, 1, 0, 0.1, 0, 'res', 2, 0),
(2, '486-12-624', NULL, '94044815', 1, 1, 0, 0.1, 0, 'res', 3, 0),
(3, '486-12-629', NULL, '94044819', 1, 1, 0, 0.1, 0, 'res', 4, 0),
(4, '486-12-618', NULL, '94044450', 1, 1, 0, 0.1, 0, 'res', 5, 0),
(5, '184-12-633', NULL, '09031157', 1, 1, 0, 0.1, 0, 'res', 3, 0),
(6, '184-12-649', NULL, '09031349', 1, 1, 0, 0.1, 0, 'res', 4, 0),
(7, '184-12-655', NULL, '09031875', 1, 1, 0, 0.1, 0, 'res', 4, 0);

UPDATE `account` SET `address` = 'PH1 Villa Grande Homes Subdivision, Concepcion Grande, Naga City' WHERE `account`.`accountid` = 1;
UPDATE `account` SET `address` = 'PH2 Villa Grande Homes Subdivision, Concepcion Grande, Naga City' WHERE `account`.`accountid` = 2;
UPDATE `account` SET `address` = 'PH3 Villa Grande Homes Subdivision, Concepcion Grande, Naga City' WHERE `account`.`accountid` = 3;
UPDATE `account` SET `address` = 'PH4 Villa Grande Homes Subdivision, Concepcion Grande, Naga City' WHERE `account`.`accountid` = 4;
UPDATE `account` SET `address` = 'Ateneo Avenue, Bagumbayan Sur, Naga City' WHERE `account`.`accountid` = 5;
UPDATE `account` SET `address` = 'Ateneo Avenue, Bagumbayan Sur, Naga City' WHERE `account`.`accountid` = 6;
UPDATE `account` SET `address` = 'Ateneo Avenue, Bagumbayan Sur, Naga City' WHERE `account`.`accountid` = 7;

CREATE TABLE IF NOT EXISTS `incident` (
 `incidentid` int(11) UNIQUE NOT NULL AUTO_INCREMENT,
 `incidenttype` varchar(30) NOT NULL,
  PRIMARY KEY (`incidentid`)
);

INSERT INTO `incident` VALUES 
(1, 'Leakage'),
(2, 'Illegal Disconnection'),
(3, 'Defective Meter'),
(4, 'Dirty water'),
(5, 'Others');

CREATE TABLE IF NOT EXISTS `incident_report` (
	`reportid` int(11) UNIQUE NOT NULL AUTO_INCREMENT,
	`incidentid` int(11) NOT NULL,
	`accountid` int(11) NOT NULL,
	`description` varchar(255) NOT NULL,
	`reportdate` date NOT NULL,
	FOREIGN KEY (`incidentid`) REFERENCES `incident` (`incidentid`),
	FOREIGN KEY (`accountid`) REFERENCES `account` (`accountid`),
	PRIMARY KEY (`reportid`)
);

CREATE TABLE IF NOT EXISTS `meter_reading_file` (
	`fileid` int(11) UNIQUE NOT NULL AUTO_INCREMENT,
	`filename` varchar(50) UNIQUE NOT NULL,
	`file` longblob NOT NULL,
	`userid` int(11) NOT NULL,
	FOREIGN KEY (`userid`) REFERENCES `user` (`userid`),
	PRIMARY KEY (`fileid`)
);

CREATE TABLE IF NOT EXISTS `reading` (
	`readingid` int(11) UNIQUE NOT NULL AUTO_INCREMENT,
	`accountid` int(11) NOT NULL,
	`billingdate` date NOT NULL,
	`previous_reading` int(11) NOT NULL,
	`present_reading` int(11) NOT NULL,
	`consumption` int(11) NOT NULL,
	`bill` double(13,2) NOT NULL,
	`duedate` date NOT NULL,
	`disconnection_date` date NOT NULL,
	`refno` varchar(20) NOT NULL,
	`fileid` int(11) NOT NULL,
	FOREIGN KEY (`accountid`) REFERENCES `account` (`accountid`),
	FOREIGN KEY (`fileid`) REFERENCES `meter_reading_file` (`fileid`),
	PRIMARY KEY (`readingid`)
);

/*
Sa mga Consumidores kan MNWD, hinahagad mi po tabi an saindong kooperasyon na magtipon nin tubig sa mga oras na makusog an bulos para po may magamit kita encaso mawara o magluya an bulos sa maabot na kapistahan kan satong Ina, Nuestra Senora de Penafrancia; sa Setyembre 9, 2016, Traslascion, asin sa Setyembre 16-18, 2016.

Dios Mabalos po saindo gabos.
*/