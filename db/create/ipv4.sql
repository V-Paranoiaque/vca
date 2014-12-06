CREATE TABLE IF NOT EXISTS `ipv4` (
  `ip` varchar(15) NOT NULL,
  `ip_owner` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `ipv4`
ADD UNIQUE KEY `ip` (`ip`),
ADD KEY `ip_owner` (`ip_owner`);

