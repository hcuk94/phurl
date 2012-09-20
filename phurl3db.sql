CREATE TABLE IF NOT EXISTS `phurl_options` (
  `option` text NOT NULL,
  `value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `phurl_options` (`option`, `value`) VALUES
('shortcode_type', 'r'),
('site_url', 'http://example.org'),
('site_title', 'Lorem Ipsum URL Shortener'),
('site_slogan', 'URLs made shorter, URLs made simpler.'),
('theme_path', 'includes/themes/default/'),
('phurl_version', '3.0');

CREATE TABLE IF NOT EXISTS `phurl_settings` (
  `last_number` bigint(20) unsigned NOT NULL DEFAULT '0',
  KEY `last_number` (`last_number`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `phurl_settings` (`last_number`) VALUES
(1);

CREATE TABLE IF NOT EXISTS `phurl_stats` (
  `alias` text NOT NULL,
  `country` text NOT NULL,
  `clicks` int(2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `phurl_stats` (`alias`, `country`, `clicks`) VALUES
('a', 'GB', 1);

CREATE TABLE IF NOT EXISTS `phurl_urls` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `code` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `alias` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ip` text NOT NULL,
  `user` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `alias` (`alias`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

INSERT INTO `phurl_urls` (`id`, `url`, `code`, `alias`, `date_added`, `ip`, `user`) VALUES
(1, 0x687474703a2f2f7777772e706875726c70726f6a6563742e6f72672f, 'a', 'phurl', '2012-05-29 12:25:00', '127.0.0.1', '');

CREATE TABLE IF NOT EXISTS `phurl_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uname` varchar(45) NOT NULL,
  `fname` varchar(45) NOT NULL,
  `lname` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `password` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
