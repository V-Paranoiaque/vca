CREATE TABLE IF NOT EXISTS `uservca` (
  `user_id` int(10) unsigned NOT NULL,
  `user_name` varchar(63) NOT NULL,
  `user_mail` varchar(63) NOT NULL,
  `user_rank` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `user_password` varchar(128) NOT NULL,
  `user_token` varchar(128) NOT NULL,
  `user_language` varchar(15) NOT NULL DEFAULT 'en_GB',
  `user_created` bigint(20) unsigned NOT NULL DEFAULT '0',
  `user_activity` bigint(20) unsigned NOT NULL DEFAULT '0',
  `user_bkppass` varchar(64) NOT NULL DEFAULT '',
  `user_dropbox` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `uservca` (`user_name`, `user_rank`, `user_password`) VALUES
('Admin', 1, '90a3d5e5e8bd5fd4acfaf86b304fa4e4bb7172238a19df25737a71e9e260201fbc6a19a0eee10f84d1e9ab443ee9aaabd9d4a1b5d2bb4c1813767adfdb144250');

ALTER TABLE `uservca`
ADD PRIMARY KEY (`user_id`);

ALTER TABLE `uservca`
MODIFY `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT;

