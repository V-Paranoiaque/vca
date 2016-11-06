CREATE TABLE IF NOT EXISTS `vps` (
  `vps_id` int(10) unsigned NOT NULL,
  `vps_name` varchar(63) NOT NULL,
  `vps_ipv4` varchar(15) NOT NULL,
  `vps_description` text NOT NULL,
  `vps_owner` int(10) unsigned NOT NULL,
  `vps_protected` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `server_id` int(10) unsigned NOT NULL DEFAULT '0',
  `last_maj` bigint(20) unsigned NOT NULL DEFAULT '0',
  `vps_cpulimit` int(10) unsigned NOT NULL,
  `vps_cpus` int(10) unsigned NOT NULL,
  `vps_cpuunits` int(10) unsigned NOT NULL,
  `ostemplate` varchar(63) NOT NULL,
  `origin_sample` varchar(63) NOT NULL,
  `onboot` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `quotatime` int(10) unsigned NOT NULL,
  `diskspace` bigint(20) unsigned NOT NULL,
  `ram` bigint(20) unsigned NOT NULL,
  `ram_current` int(10) unsigned NOT NULL DEFAULT '0',
  `swap` int(10) unsigned NOT NULL DEFAULT '0',
  `diskinodes` int(10) unsigned NOT NULL,
  `nproc` int(10) unsigned NOT NULL,
  `loadavg` varchar(63) NOT NULL,
  `diskspace_current` bigint(20) unsigned NOT NULL DEFAULT '0',
  `backup_limit` tinyint(3) unsigned NOT NULL,
  `mod_fuse` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `mod_tun` tinyint(3) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `vps`
ADD PRIMARY KEY (`vps_id`),
ADD KEY `vps_owner` (`vps_owner`),
ADD KEY `server_id` (`server_id`);

ALTER TABLE `vps`
MODIFY `vps_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
