CREATE DATABASE trials;

CREATE TABLE IF NOT EXISTS `persons` (
	`id` int(11) UNIQUE NOT NULL AUTO_INCREMENT,
	`name` varchar(255) NOT NULL,
	`birthday` date,
	PRIMARY KEY(`id`)
);

//insert into `persons` values (1, 'nikit', CURDATE());
insert into `persons` values (1, 'juan', '2000-05-23');

select * from `persons`;

SELECT EXTRACT(MONTH FROM birthday) AS MONTH 
FROM PERSONS
where id = 1;

CREATE TABLE IF NOT EXISTS `readings` (
	`id` int(11) UNIQUE NOT NULL AUTO_INCREMENT,
	`billingdate` date NOT NULL,
	`prev_reading` int(11) NOT NULL,
	`curr_reading` int(11) NOT NULL,
	`cum_used`	int(11) NOT NULL,
	`duedate` date,
	`disconnection_date` date,
	`customerid` int (11) NOT NULL,
	FOREIGN KEY (`customerid`) REFERENCES `persons` (`id`),
	PRIMARY KEY(`id`)
);

insert into `readings` 
values (
	1, 
	CURDATE(), 
	0, 
	55,
	(curr_reading - prev_reading),
	DATE_ADD(CURDATE(), INTERVAL 10 DAY),
	DATE_ADD(CURDATE(), INTERVAL 15 DAY), 
	1
);

select * from readings;

insert into `readings` 
values (
	2, 
	'2017-07-22', 
	(SELECT curr_reading 
		FROM `readings`
		WHERE customerid = 1 && 
		MONTH(curr_reading) = EXTRACT(MONTH FROM (DATE_SUB(CURDATE(), INTERVAL 1 MONTH)))
	), 
	55,
	(curr_reading - prev_reading),
	DATE_ADD(CURDATE(), INTERVAL 10 DAY),
	DATE_ADD(CURDATE(), INTERVAL 15 DAY), 
	1
);