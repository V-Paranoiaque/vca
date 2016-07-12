CREATE TABLE IF NOT EXISTS `configuration` (
  `conf_index` varchar(32) NOT NULL,
  `conf_value` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `configuration` (`conf_index`, `conf_value`) VALUES
('domain_key', 'domain_key'),
('key_size', '8'),
('key_period', '60');

ALTER TABLE `configuration`
ADD PRIMARY KEY (`conf_index`);
