CREATE TABLE IF NOT EXISTS `server` (
`server_id` int(10) unsigned NOT NULL,
  `server_name` varchar(63) NOT NULL,
  `server_address` varchar(63) NOT NULL,
  `server_description` text NOT NULL,
  `server_key` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `server`
ADD PRIMARY KEY (`server_id`);

