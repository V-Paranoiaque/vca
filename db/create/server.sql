CREATE TABLE IF NOT EXISTS `server` (
`server_id` int(10) unsigned NOT NULL,
  `server_name` varchar(63) NOT NULL,
  `server_address` varchar(63) NOT NULL,
  `server_port` smallint(5) unsigned NOT NULL DEFAULT '10000',
  `server_description` text NOT NULL,
  `server_key` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `server`
ADD PRIMARY KEY (`server_id`);

ALTER TABLE `server`
MODIFY `server_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
