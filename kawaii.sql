-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 24, 2021 at 07:30 PM
-- Server version: 5.7.26
-- PHP Version: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `manga_reader`
--

-- --------------------------------------------------------

--
-- Table structure for table `alerts`
--

DROP TABLE IF EXISTS `alerts`;
CREATE TABLE IF NOT EXISTS `alerts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text COLLATE utf8_persian_ci NOT NULL,
  `type` enum('article','comment') COLLATE utf8_persian_ci NOT NULL,
  `type_id` int(11) NOT NULL,
  `seen` int(255) NOT NULL DEFAULT '0' COMMENT '1 is seen | 0 is not sen ',
  `created_at` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `captcha`
--

DROP TABLE IF EXISTS `captcha`;
CREATE TABLE IF NOT EXISTS `captcha` (
  `captcha_id` bigint(13) UNSIGNED NOT NULL AUTO_INCREMENT,
  `captcha_time` int(10) UNSIGNED NOT NULL,
  `ip_address` varchar(45) COLLATE utf8_persian_ci NOT NULL,
  `word` varchar(20) COLLATE utf8_persian_ci NOT NULL,
  PRIMARY KEY (`captcha_id`),
  KEY `word` (`word`)
) ENGINE=MyISAM AUTO_INCREMENT=751 DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `created_at` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `modified_at` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chapters`
--

DROP TABLE IF EXISTS `chapters`;
CREATE TABLE IF NOT EXISTS `chapters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(255) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL,
  `status` enum('1','2') COLLATE utf8_persian_ci NOT NULL DEFAULT '1' COMMENT '1 = on | 2 = off or don''t show',
  `created_at` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `modified_at` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `article_id` (`article_id`)
) ENGINE=MyISAM AUTO_INCREMENT=56 DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `chapters`
--

INSERT INTO `chapters` (`id`, `article_id`, `name`, `status`, `created_at`, `modified_at`) VALUES
(54, 55, '1', '1', '2019-10-03 16:05:38', ''),
(53, 54, '1', '1', '2019-10-03 16:00:36', ''),
(52, 53, '1', '1', '2019-10-03 15:57:11', ''),
(51, 52, 'چپتر اول', '1', '2019-10-03 15:54:06', ''),
(50, 51, 'چپتر 1', '1', '2019-10-03 15:51:18', ''),
(49, 50, 'چپتر 2', '1', '2019-10-03 14:25:55', ''),
(48, 50, '1', '1', '2019-10-03 14:24:50', ''),
(47, 49, '2', '1', '2019-10-03 14:20:16', ''),
(46, 49, '1', '1', '2019-10-03 14:19:01', ''),
(40, 48, '1', '1', '2019-10-03 14:08:54', ''),
(39, 47, '3', '1', '2019-10-03 14:00:47', ''),
(38, 47, '2', '1', '2019-10-03 13:59:19', ''),
(37, 47, '1', '1', '2019-10-03 13:57:49', ''),
(36, 46, 'جلد 1', '1', '2019-10-03 00:45:49', ''),
(35, 45, 'چپتر 1', '1', '2019-10-02 14:09:00', '2019-10-03 00:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `chapter_stats`
--

DROP TABLE IF EXISTS `chapter_stats`;
CREATE TABLE IF NOT EXISTS `chapter_stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chapter_id` int(255) NOT NULL,
  `article_id` int(255) NOT NULL,
  `user_id` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `time` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=300 DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_reply_id` int(255) NOT NULL,
  `comment_user_id` int(255) NOT NULL,
  `comment_post_id` int(255) NOT NULL,
  `comment_text` text COLLATE utf8_persian_ci NOT NULL,
  `comment_approved` enum('yes','no') COLLATE utf8_persian_ci NOT NULL DEFAULT 'no',
  `comment_show` enum('yes','no') COLLATE utf8_persian_ci NOT NULL DEFAULT 'yes',
  `comment_ip` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `comment_date` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `comment_reply_id`, `comment_user_id`, `comment_post_id`, `comment_text`, `comment_approved`, `comment_show`, `comment_ip`, `comment_date`) VALUES
(13, 12, 11, 53, 'آزمایش پاسخ به نظرات تو در تو', 'no', 'yes', '::1', '2019-10-11 18:06:10'),
(12, 0, 17, 53, 'آزمایش بخش نظرات', 'no', 'yes', '::1', '2019-10-11 18:04:33');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8_persian_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `text` text COLLATE utf8_persian_ci NOT NULL,
  `created_at` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `modified_at` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `episodes`
--

DROP TABLE IF EXISTS `episodes`;
CREATE TABLE IF NOT EXISTS `episodes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(255) NOT NULL,
  `chapter_id` int(255) NOT NULL,
  `image_name` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `created_at` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `modified_at` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=348 DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

DROP TABLE IF EXISTS `likes`;
CREATE TABLE IF NOT EXISTS `likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(255) NOT NULL,
  `article_id` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

DROP TABLE IF EXISTS `links`;
CREATE TABLE IF NOT EXISTS `links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `parent_id` int(255) NOT NULL DEFAULT '0',
  `position` enum('top','bottom') COLLATE utf8_persian_ci NOT NULL DEFAULT 'top',
  `created_at` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `modified_at` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(255) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL,
  `author` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL,
  `status` enum('1','2','3') COLLATE utf8_persian_ci NOT NULL DEFAULT '1' COMMENT '1 = NA | 2 = ongoing | 3 = stopped',
  `tags` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `description` text COLLATE utf8_persian_ci NOT NULL,
  `post_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL DEFAULT 'default_image.png',
  `post_cover` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `comments_status` enum('on','off') COLLATE utf8_persian_ci NOT NULL DEFAULT 'on' COMMENT '1 = on | 2 = off',
  `release_date` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `rate` varchar(255) COLLATE utf8_persian_ci NOT NULL DEFAULT '1.0',
  `read_count` int(11) NOT NULL,
  `view_count` int(11) NOT NULL,
  `url_slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci NOT NULL,
  `approved` enum('yes','no') COLLATE utf8_persian_ci NOT NULL DEFAULT 'yes',
  `allow_download` enum('yes','no') COLLATE utf8_persian_ci NOT NULL DEFAULT 'yes',
  `total_download` int(255) NOT NULL,
  `created_at` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `modified_at` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=57 DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_name` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `site_tags` text COLLATE utf8_persian_ci NOT NULL,
  `site_description` text CHARACTER SET utf16 COLLATE utf16_persian_ci NOT NULL,
  `google_analytics_api` text COLLATE utf8_persian_ci,
  `site_template` int(10) NOT NULL DEFAULT '1',
  `logo_url` text COLLATE utf8_persian_ci NOT NULL,
  `custom_css` text COLLATE utf8_persian_ci,
  `custom_js` text COLLATE utf8_persian_ci,
  `ads1` text COLLATE utf8_persian_ci NOT NULL,
  `ads2` text COLLATE utf8_persian_ci NOT NULL,
  `ads3` text COLLATE utf8_persian_ci NOT NULL,
  `ads4` text COLLATE utf8_persian_ci NOT NULL,
  `about_us_text` text COLLATE utf8_persian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `site_name`, `site_tags`, `site_description`, `google_analytics_api`, `site_template`, `logo_url`, `custom_css`, `custom_js`, `ads1`, `ads2`, `ads3`, `ads4`, `about_us_text`) VALUES
(1, 'کاوایی', 'test,2222,234124', 'مرجع دانلود و خواندن آنلاین مانگا و کمیک', 'api test', 1, './public/img/logo2.png', 'css', 'console.log(\'custom js log\');', '<img src=\'http://uupload.ir/files/oeu5_ad1.png\' />', '<img src=\'http://uupload.ir/files/oeu5_ad1.png\' />', '<img src=\'http://uupload.ir/files/oeu5_ad1.png\' />', '<img src=\'http://uupload.ir/files/oeu5_ad1.png\' />', 'آزمایش صفحه درباره ما');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_persian_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_persian_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_persian_ci NOT NULL,
  `profile_picture_url` varchar(255) COLLATE utf8mb4_persian_ci NOT NULL DEFAULT 'profile_picture_default.png',
  `profile_cover_url` varchar(255) COLLATE utf8mb4_persian_ci NOT NULL DEFAULT 'default_cover.jpg',
  `role` int(1) NOT NULL DEFAULT '1' COMMENT '1 = Normal User | 2 = Admin User | 3 = Denied User',
  `firstname` varchar(255) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL,
  `lastname` varchar(255) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL,
  `created_at` varchar(255) COLLATE utf8mb4_persian_ci NOT NULL,
  `modified_at` varchar(255) COLLATE utf8mb4_persian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci COMMENT='Membership';

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `profile_picture_url`, `profile_cover_url`, `role`, `firstname`, `lastname`, `created_at`, `modified_at`) VALUES
(18, 'evoke', 'evoke.lektrique@gmail.com', '65c186c0e43a18aae54f7f3992695c1620640674fba4d34b24222f6b3c85d8a6601298145ec734496535d77d860d8c69d7ea5523d838d059cb98548931756656WqABKLas0t9RlEWm+DRiLyFTsipEyOZzSMOLCPI5u2o=', '677fef77b849cadece41a43e8aaeb5fe.jpg', 'b253d34a38d0dd450bfc09a1a87a999b.jpg', 2, 'evoke', 'lektrique', '', '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
