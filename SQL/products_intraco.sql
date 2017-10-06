/*
Navicat MySQL Data Transfer

Source Server         : Combell_iis
Source Server Version : 50623
Source Host           : 178.208.48.50:3306
Source Database       : xsetup

Target Server Type    : MYSQL
Target Server Version : 50623
File Encoding         : 65001

Date: 2017-10-06 11:39:06
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `pages_to_products_information_group`
-- ----------------------------
DROP TABLE IF EXISTS `pages_to_products_information_group`;
CREATE TABLE `pages_to_products_information_group` (
  `page_id` int(11) unsigned DEFAULT NULL,
  `information_id` int(11) unsigned DEFAULT NULL,
  `information_group_id` int(11) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT null,
  `updated_at` timestamp NULL DEFAULT null,
) ENGINE=InnoDB DEFAULT CHARSET=utf8 

-- ----------------------------
-- Records of pages_to_products_information_group
-- ----------------------------
