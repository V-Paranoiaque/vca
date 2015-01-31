CREATE TABLE IF NOT EXISTS `schedule` (
  `schedule_id` int(10) unsigned NOT NULL,
  `schedule_vps` int(10) unsigned NOT NULL,
  `name` varchar(63) NOT NULL,
  `minute` tinyint(3) unsigned NOT NULL,
  `hour` tinyint(3) unsigned NOT NULL,
  `dayw` int(10) unsigned NOT NULL,
  `dayn` int(10) unsigned NOT NULL,
  `month` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `schedule`
ADD PRIMARY KEY (`schedule_id`), ADD KEY `schedule_vps` (`schedule_vps`),
MODIFY `schedule_id` int(10) unsigned NOT NULL AUTO_INCREMENT;

