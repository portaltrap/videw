SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `ad-settings`;
CREATE TABLE IF NOT EXISTS `ad-settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `top_ad` text NOT NULL,
  `bottom_ad` text NOT NULL,
  `pop_ad` text NOT NULL,
  `top_ad_status` tinyint(1) NOT NULL DEFAULT 1,
  `bottom_ad_status` tinyint(1) NOT NULL DEFAULT 1,
  `pop_ad_status` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

INSERT INTO `ad-settings` (`id`, `top_ad`, `bottom_ad`, `pop_ad`, `top_ad_status`, `bottom_ad_status`, `pop_ad_status`) VALUES
(1, '&lt;img src=&quot;https://via.placeholder.com/728x90?text=XLScripts.com&quot;&gt;', '&lt;img src=&quot;https://via.placeholder.com/728x90?text=XLScripts.com&quot;&gt;', '', 0, 0, 0);

DROP TABLE IF EXISTS `admin-users`;
CREATE TABLE IF NOT EXISTS `admin-users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` text NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `analytics-settings`;
CREATE TABLE IF NOT EXISTS `analytics-settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

INSERT INTO `analytics-settings` (`id`, `code`) VALUES
(1, '');

DROP TABLE IF EXISTS `end-users`;
CREATE TABLE IF NOT EXISTS `end-users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` text NOT NULL,
  `password` varchar(255) NOT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT 0,
  `privacy` tinyint(1) NOT NULL DEFAULT 0,
  `google` tinyint(1) NOT NULL DEFAULT 0,
  `facebook` tinyint(1) NOT NULL DEFAULT 0,
  `register_date` datetime NOT NULL DEFAULT current_timestamp(),
  `avatar` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `end-users-password-reset`;
CREATE TABLE IF NOT EXISTS `end-users-password-reset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `end_user_id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `end-users-verification`;
CREATE TABLE IF NOT EXISTS `end-users-verification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `end_user_id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `general-settings`;
CREATE TABLE IF NOT EXISTS `general-settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `keywords` text NOT NULL,
  `logo` text NOT NULL,
  `favicon` text NOT NULL,
  `checksum` text NOT NULL,
  `enable_registration` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

INSERT INTO `general-settings` (`id`, `title`, `description`, `keywords`, `logo`, `favicon`, `checksum`, `enable_registration`) VALUES
(1, 'Vizmo - Simple Video Hosting Script', 'Fast & Free Video Hosting Service', 'video,upload,anonymous,free,videoupload', 'logo.svg', 'favicon.png', '', 1);

DROP TABLE IF EXISTS `meta-tags-settings`;
CREATE TABLE IF NOT EXISTS `meta-tags-settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meta_tags` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

INSERT INTO `meta-tags-settings` (`id`, `meta_tags`) VALUES
(1, '');

DROP TABLE IF EXISTS `pages`;
CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `permalink` text NOT NULL,
  `content` text NOT NULL,
  `position` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `page_order` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

INSERT INTO `pages` (`id`, `title`, `permalink`, `content`, `position`, `status`, `page_order`) VALUES
(2, 'Privacy Policy', 'privacy-policy', '&lt;p&gt;The Privacy Policy Page.&lt;/p&gt;', 2, 1, 1),
(4, 'Terms &amp; Conditions', 'terms-conditions', '&lt;p&gt;The Terms and Conditions Page.&lt;/p&gt;', 2, 1, 0);

DROP TABLE IF EXISTS `recaptcha-settings`;
CREATE TABLE IF NOT EXISTS `recaptcha-settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `site_key` text NOT NULL,
  `secret_key` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

INSERT INTO `recaptcha-settings` (`id`, `status`, `site_key`, `secret_key`) VALUES
(1, 0, '', '');

DROP TABLE IF EXISTS `s3-settings`;
CREATE TABLE IF NOT EXISTS `s3-settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `s3_access` varchar(255) NOT NULL,
  `s3_secret` varchar(255) NOT NULL,
  `s3_bucket` varchar(255) NOT NULL,
  `s3_region` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

INSERT INTO `s3-settings` (`id`, `status`, `s3_access`, `s3_secret`, `s3_bucket`, `s3_region`) VALUES
(1, 0, '', '', '', '');

DROP TABLE IF EXISTS `scripts-settings`;
CREATE TABLE IF NOT EXISTS `scripts-settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `header` text NOT NULL,
  `footer` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

INSERT INTO `scripts-settings` (`id`, `header`, `footer`) VALUES
(1, '', '');

DROP TABLE IF EXISTS `smtp-settings`;
CREATE TABLE IF NOT EXISTS `smtp-settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `host` text NOT NULL,
  `port` text NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `email` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

INSERT INTO `smtp-settings` (`id`, `host`, `port`, `username`, `password`, `status`, `email`) VALUES
(1, '', '', '', '', 0, 'nexthon@live.com');

DROP TABLE IF EXISTS `social-keys-settings`;
CREATE TABLE IF NOT EXISTS `social-keys-settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `google_public` varchar(255) NOT NULL,
  `google_secret` varchar(255) NOT NULL,
  `facebook_public` varchar(255) NOT NULL,
  `facebook_secret` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

INSERT INTO `social-keys-settings` (`id`, `google_public`, `google_secret`, `facebook_public`, `facebook_secret`) VALUES
(1, '', '', '', '');

DROP TABLE IF EXISTS `themes-settings`;
CREATE TABLE IF NOT EXISTS `themes-settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `theme` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

INSERT INTO `themes-settings` (`id`, `theme`) VALUES
(1, 'neo_red');

DROP TABLE IF EXISTS `upload-settings`;
CREATE TABLE IF NOT EXISTS `upload-settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `max_size_kb` bigint(20) NOT NULL,
  `chunk_size` bigint(20) NOT NULL,
  `mime_types` varchar(255) NOT NULL,
  `can_user_delete` tinyint(1) NOT NULL DEFAULT 1,
  `enable_api` tinyint(1) NOT NULL DEFAULT 0,
  `enable_popup` tinyint(1) NOT NULL DEFAULT 0,
  `seconds_to_wait` int(11) NOT NULL DEFAULT 10,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

INSERT INTO `upload-settings` (`id`, `max_size_kb`, `chunk_size`, `mime_types`, `can_user_delete`, `enable_api`, `enable_popup`, `seconds_to_wait`) VALUES
(1, 20971, 2048, 'mp4,avi,flv', 1, 0, 0, 10);

DROP TABLE IF EXISTS `user-uploads`;
CREATE TABLE IF NOT EXISTS `user-uploads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug_id` varchar(255) NOT NULL,
  `user` int(11) DEFAULT NULL,
  `filename` varchar(255) NOT NULL,
  `imgname` varchar(255) NOT NULL,
  `upload_date` datetime NOT NULL DEFAULT current_timestamp(),
  `s3` tinyint(1) NOT NULL DEFAULT 0,
  `enabled` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=161 DEFAULT CHARSET=utf8mb4;
COMMIT;