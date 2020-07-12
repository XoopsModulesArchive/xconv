# phpMyAdmin SQL Dump
# version 2.5.6
# http://www.phpmyadmin.net
#
# Host: localhost
# Generation Time: Aug 17, 2004 at 10:28 AM
# Server version: 4.0.20
# PHP Version: 4.2.3
# 
# Database : `xpsbeta_xoops`
# 

# --------------------------------------------------------

#
# Table structure for table `xconv`
#

CREATE TABLE `xconv` (
  `xconv_id` tinyint(8) unsigned NOT NULL auto_increment,
  `charset` varchar(255) default NULL,
  `alias` varchar(255) default NULL,
  `inc` varchar(255) default NULL,
  PRIMARY KEY  (`xconv_id`)
) TYPE=MyISAM;

#
# Dumping data for table `xconv`
#

INSERT INTO `xconv` VALUES (1, 'utf8', 'utf8,utf-8', '');
INSERT INTO `xconv` VALUES (2, 'unicode', 'unicode', '');
INSERT INTO `xconv` VALUES (3, 'gb', 'gb2312,gb18030,gb,gbk', 'chinese.php');
INSERT INTO `xconv` VALUES (4, 'big5', 'big5,big5-hk', 'chinese.php');
