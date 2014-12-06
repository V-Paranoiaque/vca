CREATE TABLE IF NOT EXISTS `request_topic` (
`request_topic_id` int(10) unsigned NOT NULL,
  `request_topic_title` varchar(255) NOT NULL,
  `request_topic_created` int(10) unsigned NOT NULL,
  `request_topic_author` int(10) unsigned NOT NULL,
  `request_topic_resolved` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `request_topic`
ADD PRIMARY KEY (`request_topic_id`),
ADD KEY `request_topic_author` (`request_topic_author`),
ADD KEY `request_topic_resolved` (`request_topic_resolved`),
ADD KEY `request_topic_created` (`request_topic_created`);

