CREATE TABLE IF NOT EXISTS `devices` (
    `did` INT NOT NULL AUTO_INCREMENT,
    `device_name` VARCHAR(20),
    `function` VARCHAR(5),
    `assigned_site` INT,
    `mac_address` VARCHAR(17),
    `installer` VARCHAR(20),
    `date_installed` date,
    `img` INT,
	 UNIQUE KEY (`mac_address`),
   	 PRIMARY KEY (`did`)
);
