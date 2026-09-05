CREATE TABLE IF NOT EXISTS `users` (
  `usrid` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(255) NOT NULL,
  `emailadd` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `acccount` varchar(30) NOT NULL,
  PRIMARY KEY (`usrid`),
  UNIQUE KEY `emailadd` (`emailadd`)
);

INSERT INTO `users` (`usrid`, `fullname`, `emailadd`, `username`, `password`,`acccount`) VALUES
(1, 'Website Admin Account', 'admin@victoryfreewifi.site', 'McJim', 'McJim654123', 'Administrator'),
(2, 'Team Leaders Account', 'team@victoryfreewifi.site', 'Leaders', 'R3str1ct3d@1991', 'Team Leaders');

CREATE TABLE IF NOT EXISTS `validity` (
  `validity` date DEFAULT NULL
);

INSERT INTO `validity` (`validity`) VALUES
('2025-06-20');
