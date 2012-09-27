--
-- Database: `phurl3`
--
--
-- Dump from 13:43 27/09/2012 by Martyn Watton
--

-- --------------------------------------------------------

--
-- Table structure for table `phurl_api`
--

CREATE TABLE IF NOT EXISTS `phurl_api` (
  `apiKey` varchar(32) NOT NULL,
  `time` int(11) NOT NULL,
  `remain` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `phurl_options`
--

CREATE TABLE IF NOT EXISTS `phurl_options` (
  `option` text NOT NULL,
  `value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phurl_options`
--

INSERT INTO `phurl_options` (`option`, `value`) VALUES
('shortcode_type', 'r'),
('site_url', 'http://phurl3.lo'),
('site_title', 'phurl 3.0'),
('site_slogan', 'URLs made shorter, URLs made simpler.'),
('theme_path', 'includes/themes/default/'),
('phurl_version', '3.0'),
('api_limit', '250'),
('phurl_numericalversion', '300');

-- --------------------------------------------------------

--
-- Table structure for table `phurl_session`
--

CREATE TABLE IF NOT EXISTS `phurl_session` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session` varchar(64) NOT NULL,
  `uId` int(11) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `time` int(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `phurl_settings`
--

CREATE TABLE IF NOT EXISTS `phurl_settings` (
  `last_number` bigint(20) unsigned NOT NULL DEFAULT '0',
  KEY `last_number` (`last_number`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phurl_settings`
--

INSERT INTO `phurl_settings` (`last_number`) VALUES
(1);

-- --------------------------------------------------------

--
-- Table structure for table `phurl_stats`
--

CREATE TABLE IF NOT EXISTS `phurl_stats` (
  `alias` text NOT NULL,
  `country` text NOT NULL,
  `clicks` int(2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phurl_stats`
--

INSERT INTO `phurl_stats` (`alias`, `country`, `clicks`) VALUES
('a', 'GB', 1);

-- --------------------------------------------------------

--
-- Table structure for table `phurl_urls`
--

CREATE TABLE IF NOT EXISTS `phurl_urls` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `code` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `alias` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ip` text NOT NULL,
  `api` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `alias` (`alias`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `phurl_urls`
--

INSERT INTO `phurl_urls` (`id`, `url`, `code`, `alias`, `date_added`, `ip`, `api`) VALUES
(1, 'http://www.phurlproject.org/', 'a', 'phurl', '2012-05-29 12:25:00', '127.0.0.1', '20GigZMpBL32vIaY');

-- --------------------------------------------------------

--
-- Table structure for table `phurl_users`
--

CREATE TABLE IF NOT EXISTS `phurl_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uname` varchar(45) NOT NULL,
  `fname` varchar(45) NOT NULL,
  `lname` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `password` varchar(64) NOT NULL,
  `apiKey` varchar(32) NOT NULL,
  `type` enum('n','a') NOT NULL,
  `suspended` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `phurl_users`
--

INSERT INTO `phurl_users` (`id`, `uname`, `fname`, `lname`, `email`, `password`, `apiKey`, `type`, `suspended`) VALUES
(1, '', '', '', '', '', '20GigZMpBL32vIaY', 'a', '0');

