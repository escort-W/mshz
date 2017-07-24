/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : mshz

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-07-24 13:59:38
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ot_apply`
-- ----------------------------
DROP TABLE IF EXISTS `ot_apply`;
CREATE TABLE `ot_apply` (
  `AL_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户申请表',
  `AL_type` varchar(30) DEFAULT '' COMMENT '数据类型',
  `AL_time` datetime DEFAULT NULL COMMENT '提交时间',
  `AL_replyTime` datetime DEFAULT NULL COMMENT '回复时间，管理员处理时间',
  `AL_dataID` int(11) DEFAULT '0' COMMENT '与其它表关联ID',
  `AL_userID` int(11) DEFAULT '0' COMMENT '与用户表关联ID',
  `AL_userName` varchar(50) DEFAULT '' COMMENT '申请用户名，管理列表显示',
  `AL_contact` text COMMENT '联系方式，管理列表显示第一个',
  `AL_userInfo` text COMMENT '用户其它相关信息',
  `AL_subIP` varchar(50) DEFAULT NULL COMMENT '信息提交IP',
  `AL_otherInfo` text COMMENT '其它信息',
  `AL_note` text COMMENT '申请说明',
  `AL_reply` text COMMENT '管理员备注信息',
  `AL_status` smallint(1) DEFAULT '0' COMMENT '处理状态，0-未处理，1-已处理',
  PRIMARY KEY (`AL_ID`),
  KEY `MA_dataID` (`AL_dataID`),
  KEY `MA_ID` (`AL_ID`),
  KEY `MA_userID` (`AL_userID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_apply
-- ----------------------------

-- ----------------------------
-- Table structure for `ot_attribute`
-- ----------------------------
DROP TABLE IF EXISTS `ot_attribute`;
CREATE TABLE `ot_attribute` (
  `AB_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '商品属性-商品类型',
  `AB_atid` int(11) DEFAULT '0' COMMENT '商品类型ID，attrtype',
  `AB_theme` varchar(200) DEFAULT '' COMMENT '名称',
  `AB_rank` int(11) DEFAULT '0',
  `AB_status` smallint(1) DEFAULT '1',
  `AB_editMode` smallint(1) DEFAULT '0' COMMENT '属性编辑方式',
  `AB_values` text COMMENT '属性可选值列表',
  PRIMARY KEY (`AB_ID`),
  UNIQUE KEY `IM_ID` (`AB_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_attribute
-- ----------------------------

-- ----------------------------
-- Table structure for `ot_backupdatabase`
-- ----------------------------
DROP TABLE IF EXISTS `ot_backupdatabase`;
CREATE TABLE `ot_backupdatabase` (
  `BD_ID` int(11) NOT NULL AUTO_INCREMENT,
  `BD_time` datetime DEFAULT NULL,
  `BD_type` varchar(25) DEFAULT NULL,
  `BD_filePartNum` smallint(6) DEFAULT NULL,
  `BD_filePath` varchar(200) DEFAULT NULL,
  `BD_fileSize` int(11) DEFAULT '0',
  `BD_tableStr` varchar(4000) DEFAULT NULL,
  `BD_note` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`BD_ID`),
  KEY `BB_ID` (`BD_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_backupdatabase
-- ----------------------------

-- ----------------------------
-- Table structure for `ot_drrz`
-- ----------------------------
DROP TABLE IF EXISTS `ot_drrz`;
CREATE TABLE `ot_drrz` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `ip` varchar(60) DEFAULT NULL,
  `user` varchar(60) DEFAULT NULL,
  `leixin` int(8) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15655 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_drrz
-- ----------------------------
INSERT INTO `ot_drrz` VALUES ('15607', '2017-07-11 11:58:05', '1.193.126.112', 'admin', '1');
INSERT INTO `ot_drrz` VALUES ('15608', '2017-07-11 12:02:00', '1.193.126.112', 'admin', '1');
INSERT INTO `ot_drrz` VALUES ('15609', '2017-07-11 16:34:10', '1.193.126.112', 'admin', '1');
INSERT INTO `ot_drrz` VALUES ('15610', '2017-07-11 17:56:38', '', '15737168980', '0');
INSERT INTO `ot_drrz` VALUES ('15611', '2017-07-11 18:23:56', '1.193.126.112', 'admin', '1');
INSERT INTO `ot_drrz` VALUES ('15612', '2017-07-11 18:25:19', '', '15136272070', '0');
INSERT INTO `ot_drrz` VALUES ('15613', '2017-07-12 09:01:35', '', '15136272070', '0');
INSERT INTO `ot_drrz` VALUES ('15614', '2017-07-12 09:04:30', '115.60.190.208', 'admin', '1');
INSERT INTO `ot_drrz` VALUES ('15615', '2017-07-13 09:05:41', '115.60.189.56', 'admin', '1');
INSERT INTO `ot_drrz` VALUES ('15616', '2017-07-13 11:44:35', '', '15136272070', '0');
INSERT INTO `ot_drrz` VALUES ('15617', '2017-07-13 18:59:41', '115.60.184.76', 'admin', '1');
INSERT INTO `ot_drrz` VALUES ('15618', '2017-07-14 09:06:46', '115.60.189.20', 'admin', '1');
INSERT INTO `ot_drrz` VALUES ('15619', '2017-07-14 09:13:46', '115.60.189.20', 'admin', '1');
INSERT INTO `ot_drrz` VALUES ('15620', '2017-07-14 13:33:27', '', '15136272070', '0');
INSERT INTO `ot_drrz` VALUES ('15621', '2017-07-14 16:30:06', '', '15136272070', '0');
INSERT INTO `ot_drrz` VALUES ('15622', '2017-07-14 17:23:33', '', '15136272070', '0');
INSERT INTO `ot_drrz` VALUES ('15623', '2017-07-14 17:24:02', '', '15136272070', '0');
INSERT INTO `ot_drrz` VALUES ('15624', '2017-07-15 08:56:58', '', '15136272070', '0');
INSERT INTO `ot_drrz` VALUES ('15625', '2017-07-15 09:41:02', '', '15136272070', '0');
INSERT INTO `ot_drrz` VALUES ('15626', '2017-07-15 13:32:14', '', '15136272070', '0');
INSERT INTO `ot_drrz` VALUES ('15627', '2017-07-15 13:56:55', '1.193.126.131', 'admin', '1');
INSERT INTO `ot_drrz` VALUES ('15628', '2017-07-15 13:59:34', '', '15937187918', '0');
INSERT INTO `ot_drrz` VALUES ('15629', '2017-07-15 14:03:14', '', '15737168980', '0');
INSERT INTO `ot_drrz` VALUES ('15630', '2017-07-15 16:02:38', '', '15136272070', '0');
INSERT INTO `ot_drrz` VALUES ('15631', '2017-07-15 16:05:11', '', '15737168980', '0');
INSERT INTO `ot_drrz` VALUES ('15632', '2017-07-15 16:06:50', '', '15737168980', '0');
INSERT INTO `ot_drrz` VALUES ('15633', '2017-07-15 16:52:15', '', '15937187918', '0');
INSERT INTO `ot_drrz` VALUES ('15634', '2017-07-15 17:32:10', '', '15737168981', '0');
INSERT INTO `ot_drrz` VALUES ('15635', '2017-07-17 08:49:17', '', '15737168980', '0');
INSERT INTO `ot_drrz` VALUES ('15636', '2017-07-17 11:34:51', '', '15937187918', '0');
INSERT INTO `ot_drrz` VALUES ('15637', '2017-07-17 13:32:42', '', '15737168981', '0');
INSERT INTO `ot_drrz` VALUES ('15638', '2017-07-17 13:34:08', '115.60.184.179', 'admin', '1');
INSERT INTO `ot_drrz` VALUES ('15639', '2017-07-17 15:00:44', '115.60.189.110', 'admin', '1');
INSERT INTO `ot_drrz` VALUES ('15640', '2017-07-17 16:56:22', '115.60.191.43', 'admin', '1');
INSERT INTO `ot_drrz` VALUES ('15641', '2017-07-18 08:57:23', '115.60.190.27', 'admin', '1');
INSERT INTO `ot_drrz` VALUES ('15642', '2017-07-18 09:01:54', '115.60.190.27', 'admin', '1');
INSERT INTO `ot_drrz` VALUES ('15643', '2017-07-18 14:10:37', '1.193.124.45', 'admin', '1');
INSERT INTO `ot_drrz` VALUES ('15644', '2017-07-18 14:36:46', '', '15136272070', '0');
INSERT INTO `ot_drrz` VALUES ('15645', '2017-07-18 15:11:02', '', '18137832920', '0');
INSERT INTO `ot_drrz` VALUES ('15646', '2017-07-18 15:22:17', '', '18137832920', '0');
INSERT INTO `ot_drrz` VALUES ('15647', '2017-07-20 08:38:27', '115.60.187.165', 'admin', '1');
INSERT INTO `ot_drrz` VALUES ('15648', '2017-07-20 16:06:31', '115.60.188.205', 'admin', '1');
INSERT INTO `ot_drrz` VALUES ('15649', '2017-07-21 09:00:12', '', '15737168980', '0');
INSERT INTO `ot_drrz` VALUES ('15650', '2017-07-21 10:06:03', '1.199.77.211', 'admin', '1');
INSERT INTO `ot_drrz` VALUES ('15651', '2017-07-21 10:25:00', '1.199.77.211', 'admin', '1');
INSERT INTO `ot_drrz` VALUES ('15652', '2017-07-21 13:37:47', '115.60.189.186', 'admin', '1');
INSERT INTO `ot_drrz` VALUES ('15653', '2017-07-21 13:44:21', '', '15737168980', '0');
INSERT INTO `ot_drrz` VALUES ('15654', '2017-07-24 13:40:38', '115.60.191.117', 'admin', '1');

-- ----------------------------
-- Table structure for `ot_font`
-- ----------------------------
DROP TABLE IF EXISTS `ot_font`;
CREATE TABLE `ot_font` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `reg_num` varchar(255) DEFAULT '0' COMMENT '注册人数',
  `user_up` int(11) DEFAULT '0' COMMENT '在线人数',
  `apply_money` int(11) DEFAULT '0' COMMENT '提供帮助金额',
  `need_money` int(11) DEFAULT '0' COMMENT '需求金额',
  `trade_num` int(11) DEFAULT '0' COMMENT '成功交易订单数',
  `up_time` int(11) DEFAULT '0' COMMENT '更改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_font
-- ----------------------------

-- ----------------------------
-- Table structure for `ot_info`
-- ----------------------------
DROP TABLE IF EXISTS `ot_info`;
CREATE TABLE `ot_info` (
  `IF_ID` int(11) NOT NULL AUTO_INCREMENT,
  `IF_time` datetime NOT NULL,
  `IF_revTime` datetime DEFAULT NULL,
  `IF_type` varchar(50) DEFAULT NULL,
  `IF_type1ID` int(11) DEFAULT '0',
  `IF_type2ID` int(11) DEFAULT '0',
  `IF_theme` varchar(250) DEFAULT NULL,
  `IF_webImg` varchar(255) DEFAULT '',
  `IF_content` longtext,
  `IF_rank` int(11) DEFAULT '0',
  `IF_readNum` int(11) DEFAULT '0',
  `IF_isIndex` smallint(1) DEFAULT '0',
  `IF_seodesc` longtext,
  `IF_seokeyword` longtext,
  `zt` int(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`IF_ID`),
  KEY `IF_ID` (`IF_ID`),
  KEY `IF_menu1` (`IF_type1ID`),
  KEY `IF_readNum` (`IF_readNum`),
  KEY `IF_type1ID` (`IF_type`)
) ENGINE=MyISAM AUTO_INCREMENT=127 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_info
-- ----------------------------
INSERT INTO `ot_info` VALUES ('126', '2017-07-13 09:36:34', null, 'news', '0', '0', '11111', '', '1234567894545455', '0', '0', '0', null, null, '0');

-- ----------------------------
-- Table structure for `ot_ip`
-- ----------------------------
DROP TABLE IF EXISTS `ot_ip`;
CREATE TABLE `ot_ip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `leixin` varchar(255) NOT NULL DEFAULT '0',
  `user` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_ip
-- ----------------------------

-- ----------------------------
-- Table structure for `ot_jsbz`
-- ----------------------------
DROP TABLE IF EXISTS `ot_jsbz`;
CREATE TABLE `ot_jsbz` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zffs1` int(8) NOT NULL DEFAULT '0',
  `zffs2` int(8) NOT NULL DEFAULT '0',
  `zffs3` int(8) NOT NULL DEFAULT '0',
  `jb` decimal(15,0) NOT NULL DEFAULT '0',
  `zt` int(8) NOT NULL DEFAULT '0',
  `user` varchar(255) DEFAULT NULL,
  `qr_zt` int(8) DEFAULT '0',
  `user_tjr` varchar(255) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `user_nc` varchar(255) DEFAULT NULL,
  `qb` int(8) NOT NULL DEFAULT '0',
  `date1` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_jsbz
-- ----------------------------
INSERT INTO `ot_jsbz` VALUES ('5', '1', '1', '1', '1000', '1', '15737168981', '1', '15136272070', '2017-07-24 11:22:07', '', '2', null);
INSERT INTO `ot_jsbz` VALUES ('6', '1', '1', '1', '1000', '0', '15136272070', '0', '15737168980', '2017-07-24 11:27:55', '王小二', '2', null);

-- ----------------------------
-- Table structure for `ot_link`
-- ----------------------------
DROP TABLE IF EXISTS `ot_link`;
CREATE TABLE `ot_link` (
  `LN_ID` int(11) NOT NULL AUTO_INCREMENT,
  `LN_type` varchar(20) NOT NULL,
  `LN_theme` varchar(200) DEFAULT NULL,
  `LN_rank` int(11) DEFAULT '0',
  `LN_state` smallint(1) DEFAULT '1',
  `LN_imgMode` varchar(20) DEFAULT NULL,
  `LN_imgUrl` varchar(200) DEFAULT NULL,
  `LN_webUrl` varchar(200) DEFAULT NULL,
  `LN_time` datetime DEFAULT NULL,
  PRIMARY KEY (`LN_ID`),
  UNIQUE KEY `IM_ID` (`LN_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_link
-- ----------------------------

-- ----------------------------
-- Table structure for `ot_logusers`
-- ----------------------------
DROP TABLE IF EXISTS `ot_logusers`;
CREATE TABLE `ot_logusers` (
  `LU_ID` int(11) NOT NULL AUTO_INCREMENT,
  `LU_time` datetime DEFAULT NULL,
  `LU_userName` varchar(35) DEFAULT NULL,
  `LU_userID` int(11) DEFAULT '0',
  `LU_type` varchar(35) DEFAULT NULL,
  `LU_note` longtext,
  PRIMARY KEY (`LU_ID`),
  KEY `LU_ID` (`LU_ID`),
  KEY `LU_userID` (`LU_userID`),
  KEY `LU_userID1` (`LU_userName`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_logusers
-- ----------------------------

-- ----------------------------
-- Table structure for `ot_match`
-- ----------------------------
DROP TABLE IF EXISTS `ot_match`;
CREATE TABLE `ot_match` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `supply_timelimit` int(11) DEFAULT '0',
  `accept_timelimit` int(11) DEFAULT '0',
  `math_switch` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_match
-- ----------------------------

-- ----------------------------
-- Table structure for `ot_member`
-- ----------------------------
DROP TABLE IF EXISTS `ot_member`;
CREATE TABLE `ot_member` (
  `MB_ID` int(11) NOT NULL AUTO_INCREMENT,
  `MB_time` date DEFAULT NULL,
  `MB_loginTime` datetime DEFAULT NULL,
  `MB_loginNum` int(11) DEFAULT '0',
  `MB_loginIP` varchar(20) DEFAULT NULL,
  `MB_realname` varchar(30) DEFAULT NULL,
  `MB_username` varchar(30) NOT NULL,
  `MB_userpwd` varchar(32) NOT NULL,
  `MB_userKey` varchar(36) DEFAULT NULL,
  `MB_right` int(11) DEFAULT '20',
  `MB_userGroup` int(11) DEFAULT '0',
  `MB_rightStr` longtext,
  `MB_itemNum` int(11) DEFAULT '20',
  `MB_rights` varchar(500) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '' COMMENT '权限',
  PRIMARY KEY (`MB_ID`),
  KEY `MB_itemNum` (`MB_itemNum`),
  KEY `MB_loginNum` (`MB_loginNum`),
  KEY `MB_userKey` (`MB_userKey`)
) ENGINE=MyISAM AUTO_INCREMENT=2034 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_member
-- ----------------------------
INSERT INTO `ot_member` VALUES ('1', null, null, '0', null, null, 'admin', 'e10adc3949ba59abbe56e057f20f883e', null, '1', '0', null, '20', '');

-- ----------------------------
-- Table structure for `ot_memberlog`
-- ----------------------------
DROP TABLE IF EXISTS `ot_memberlog`;
CREATE TABLE `ot_memberlog` (
  `ML_ID` int(11) NOT NULL AUTO_INCREMENT,
  `ML_time` datetime NOT NULL,
  `ML_date` date NOT NULL,
  `ML_userID` int(11) NOT NULL,
  `ML_realname` varchar(30) NOT NULL,
  `ML_ip` varchar(20) NOT NULL,
  `ML_ipCN` varchar(50) NOT NULL,
  `ML_menuFileID` mediumint(9) NOT NULL DEFAULT '0',
  `ML_note` varchar(255) DEFAULT NULL,
  `ML_readNum` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ML_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=243 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_memberlog
-- ----------------------------

-- ----------------------------
-- Table structure for `ot_menufile`
-- ----------------------------
DROP TABLE IF EXISTS `ot_menufile`;
CREATE TABLE `ot_menufile` (
  `MF_ID` int(11) NOT NULL AUTO_INCREMENT,
  `MF_level` int(11) DEFAULT '0',
  `MF_fileID` int(11) DEFAULT '0',
  `MF_theme` varchar(50) DEFAULT NULL,
  `MF_fileName` varchar(35) DEFAULT NULL,
  `MF_getMudi` varchar(16) DEFAULT NULL,
  `MF_example` varchar(160) DEFAULT NULL,
  `MF_rank` int(11) DEFAULT '0',
  `MF_note` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`MF_ID`),
  KEY `MF_fileID` (`MF_fileID`),
  KEY `MF_ID` (`MF_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=105 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_menufile
-- ----------------------------

-- ----------------------------
-- Table structure for `ot_menutree`
-- ----------------------------
DROP TABLE IF EXISTS `ot_menutree`;
CREATE TABLE `ot_menutree` (
  `MT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `MT_level` int(11) DEFAULT '0',
  `MT_menuID` int(11) DEFAULT '0',
  `MT_fileID` int(11) DEFAULT '0',
  `MT_theme` varchar(50) DEFAULT NULL,
  `MT_fileName` varchar(25) DEFAULT NULL,
  `MT_getMudi` varchar(20) DEFAULT NULL,
  `MT_getDataMode` varchar(50) DEFAULT NULL,
  `MT_getDataModeStr` varchar(50) DEFAULT NULL,
  `MT_getDataType` varchar(20) DEFAULT NULL,
  `MT_getDataTypeCN` varchar(50) DEFAULT NULL,
  `MT_getDataType2` varchar(20) DEFAULT NULL,
  `MT_getDataID` int(11) DEFAULT '0',
  `MT_getImgSize` varchar(60) DEFAULT '',
  `MT_getImgSize2` varchar(60) DEFAULT '',
  `MT_getOthers` varchar(160) DEFAULT NULL,
  `MT_URL` varchar(200) DEFAULT NULL,
  `MT_rank` int(11) DEFAULT '0',
  `MT_state` int(11) DEFAULT '0',
  PRIMARY KEY (`MT_ID`),
  KEY `MT_fileID` (`MT_fileID`),
  KEY `MT_ID` (`MT_ID`),
  KEY `MT_menuID` (`MT_menuID`)
) ENGINE=MyISAM AUTO_INCREMENT=166 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_menutree
-- ----------------------------

-- ----------------------------
-- Table structure for `ot_message`
-- ----------------------------
DROP TABLE IF EXISTS `ot_message`;
CREATE TABLE `ot_message` (
  `MA_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '前台提交信息，留言、申请等',
  `MA_type` varchar(30) DEFAULT '' COMMENT '数据类型',
  `MA_theme` varchar(60) DEFAULT '' COMMENT '留言主题',
  `MA_time` datetime DEFAULT NULL COMMENT '提交时间',
  `MA_replyTime` datetime DEFAULT NULL COMMENT '回复时间',
  `MA_dataID` int(11) DEFAULT '0' COMMENT '与其它表关联ID',
  `MA_userID` int(11) DEFAULT '0' COMMENT '与用户表关联ID',
  `MA_userName` varchar(50) DEFAULT '' COMMENT '留言用户名，管理列表显示',
  `MA_contact` text COMMENT '联系方式',
  `MA_userInfo` text COMMENT '用户其它相关信息',
  `MA_subIP` varchar(50) DEFAULT NULL COMMENT '信息提交IP',
  `MA_otherInfo` text COMMENT '其它信息',
  `MA_note` text COMMENT '用户留言内容',
  `MA_reply` text COMMENT '管理员回复内容',
  `MA_status` smallint(1) DEFAULT '0' COMMENT '审核状态',
  `zt` int(8) NOT NULL DEFAULT '0',
  `pic` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`MA_ID`),
  KEY `MA_dataID` (`MA_dataID`),
  KEY `MA_ID` (`MA_ID`),
  KEY `MA_userID` (`MA_userID`)
) ENGINE=MyISAM AUTO_INCREMENT=99 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_message
-- ----------------------------
INSERT INTO `ot_message` VALUES ('96', 'message', '111', '2017-07-13 16:32:40', '2017-07-13 16:32:56', '0', '0', '15136272070', null, null, null, '参与者拒绝汇款', '1111111111111', '1111111111111111111111111', '0', '1', null);
INSERT INTO `ot_message` VALUES ('97', 'message', '1212', '2017-07-15 16:13:44', null, '0', '0', '15737168980', null, null, null, '无法接受手机短信', '1212', null, '0', '0', '');
INSERT INTO `ot_message` VALUES ('98', 'message', '去去去', '2017-07-18 17:15:36', '2017-07-18 17:16:02', '0', '0', '18137832920', null, null, null, '汇款时，对方资料与系统提供的有差别', '凄凄切切', '22222', '0', '1', '');

-- ----------------------------
-- Table structure for `ot_mobmsgset`
-- ----------------------------
DROP TABLE IF EXISTS `ot_mobmsgset`;
CREATE TABLE `ot_mobmsgset` (
  `SYS_ID` int(11) NOT NULL AUTO_INCREMENT,
  `SYS_theme` varchar(100) DEFAULT NULL,
  `SYS_address` varchar(200) DEFAULT NULL,
  `SYS_postCode` varchar(50) DEFAULT NULL,
  `SYS_contact` varchar(50) DEFAULT '',
  `SYS_mobile` varchar(50) DEFAULT '',
  `SYS_mail` varchar(80) DEFAULT NULL,
  `SYS_phone` varchar(50) DEFAULT NULL,
  `SYS_hotPhone` varchar(50) DEFAULT NULL,
  `SYS_fax` varchar(50) DEFAULT NULL,
  `SYS_qq` varchar(30) DEFAULT NULL,
  `SYS_banquan` varchar(100) DEFAULT NULL,
  `SYS_seoTitle` varchar(300) DEFAULT '',
  `SYS_seoWord` text,
  `SYS_seoDesc` text,
  PRIMARY KEY (`SYS_ID`),
  KEY `SYS_postCode` (`SYS_postCode`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_mobmsgset
-- ----------------------------

-- ----------------------------
-- Table structure for `ot_pdb`
-- ----------------------------
DROP TABLE IF EXISTS `ot_pdb`;
CREATE TABLE `ot_pdb` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `UE_id` int(8) DEFAULT NULL,
  `UE_account` varchar(50) DEFAULT NULL,
  `give_time` datetime DEFAULT NULL,
  `UE_theme` varchar(50) DEFAULT NULL,
  `UE_pdb` int(8) DEFAULT NULL,
  `UE_type` varchar(255) NOT NULL,
  `UE_type_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_pdb
-- ----------------------------
INSERT INTO `ot_pdb` VALUES ('2', null, null, '2017-07-13 10:22:05', null, null, '', '');
INSERT INTO `ot_pdb` VALUES ('3', null, null, '2017-07-13 10:30:25', null, null, '', '');
INSERT INTO `ot_pdb` VALUES ('4', null, null, '2017-07-13 10:31:17', null, null, '', '');
INSERT INTO `ot_pdb` VALUES ('5', '2', '15136272070', '2017-07-13 10:32:28', '王坤', '10', '', '');
INSERT INTO `ot_pdb` VALUES ('6', '2', '15136272070', '2017-07-13 10:33:12', '王坤', '-10', '', '');
INSERT INTO `ot_pdb` VALUES ('7', '3', '15937187918', '2017-07-15 17:00:16', '小伟', '10', '', '');

-- ----------------------------
-- Table structure for `ot_pin`
-- ----------------------------
DROP TABLE IF EXISTS `ot_pin`;
CREATE TABLE `ot_pin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(255) NOT NULL,
  `pin` varchar(255) DEFAULT NULL,
  `zt` varchar(255) DEFAULT NULL,
  `sc_date` datetime DEFAULT NULL,
  `sy_user` varchar(255) NOT NULL DEFAULT '0',
  `sy_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_pin
-- ----------------------------
INSERT INTO `ot_pin` VALUES ('1', '15737168980', '3268a97aee0f4e498b02cf3308580680', '0', '2017-07-11 17:57:58', '0', null);
INSERT INTO `ot_pin` VALUES ('2', '15737168980', '0808a8fdcd20bf1769954b0e6fbcb76a', '0', '2017-07-11 17:57:58', '0', '1899-12-30 00:00:00');
INSERT INTO `ot_pin` VALUES ('3', '15136272070', '46d71c301418f0a72b00d2c4b4ae0980', '1', '2017-07-12 16:43:45', '15136272070', '2017-07-15 13:51:44');
INSERT INTO `ot_pin` VALUES ('4', '15136272070', '5db471306d3d5147458c59c5d573f7a1', '1', '2017-07-12 16:43:45', '15136272070', '2017-07-15 13:52:28');
INSERT INTO `ot_pin` VALUES ('5', '15136272070', '474727145bfce6e460fcfca95f261835', '0', '2017-07-12 16:43:45', '0', '1899-12-30 00:00:00');
INSERT INTO `ot_pin` VALUES ('6', '15136272070', '508c0eb4bcb7e3f9f87b387ae7fd5528', '0', '2017-07-13 16:29:21', '0', '1899-12-30 00:00:00');
INSERT INTO `ot_pin` VALUES ('7', '15136272070', '6bae2e6204a053231c5aa9caadad0f11', '0', '2017-07-13 16:29:21', '0', '1899-12-30 00:00:00');
INSERT INTO `ot_pin` VALUES ('8', '15136272070', '284fe3f40e4187f91f38a892d9c60e65', '0', '2017-07-13 16:29:21', '0', '1899-12-30 00:00:00');
INSERT INTO `ot_pin` VALUES ('9', '15937187918', '78b585a0cccdf6c187824eb20db10384', '1', '2017-07-18 15:10:32', '15937187918', '2017-07-18 15:10:40');
INSERT INTO `ot_pin` VALUES ('10', '18137832920', '3e8b99241d7f08e878051ad9669c0828', '0', '2017-07-18 15:23:40', '0', null);
INSERT INTO `ot_pin` VALUES ('11', '18137832920', '95f2822d90544be65f1f33b4828e9f32', '0', '2017-07-18 15:23:40', '0', null);
INSERT INTO `ot_pin` VALUES ('12', '15937187918', '953aa5a707f0e05ca52367351b3beb41', '1', '2017-07-18 15:23:40', '15937187918', '2017-07-20 10:21:23');
INSERT INTO `ot_pin` VALUES ('13', '15937187918', '47928e32ea6e798170ea44d1b85f36d9', '1', '2017-07-18 15:23:40', '15937187918', '2017-07-20 10:26:14');
INSERT INTO `ot_pin` VALUES ('14', '15937187918', 'a71fe55b2b3d6c006017d4f705f8cac5', '1', '2017-07-18 15:23:40', '', '2017-07-20 11:23:58');
INSERT INTO `ot_pin` VALUES ('15', '15937187918', 'f41c38b4ac89e07fdd00d21f9629b28f', '1', '2017-07-18 15:23:40', '18137832920', '2017-07-20 11:25:25');
INSERT INTO `ot_pin` VALUES ('16', '15937187918', 'efe7d70f995621b209c0fd113c6f792c', '1', '2017-07-18 15:23:40', '18137832920', '2017-07-20 11:30:04');
INSERT INTO `ot_pin` VALUES ('17', '15937187918', '1fe4584f9c125b1f2fa0ee08e76b6de1', '0', '2017-07-18 15:23:40', '0', null);
INSERT INTO `ot_pin` VALUES ('18', '15937187918', 'a044044612ba88332271722513c2a3e1', '0', '2017-07-18 15:23:40', '0', null);
INSERT INTO `ot_pin` VALUES ('19', '15937187918', '13e1502720411ec81af7bff1399c46a3', '0', '2017-07-18 15:23:40', '0', null);

-- ----------------------------
-- Table structure for `ot_ppdd`
-- ----------------------------
DROP TABLE IF EXISTS `ot_ppdd`;
CREATE TABLE `ot_ppdd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `p_id` varchar(255) DEFAULT NULL,
  `g_id` varchar(255) DEFAULT NULL,
  `jb` decimal(15,0) DEFAULT NULL,
  `p_user` varchar(255) DEFAULT NULL,
  `g_user` varchar(255) DEFAULT NULL,
  `date` datetime DEFAULT NULL COMMENT '匹配时间',
  `zt` int(8) NOT NULL DEFAULT '0',
  `pic` varchar(255) DEFAULT NULL,
  `zffs1` int(8) DEFAULT NULL,
  `zffs2` int(8) DEFAULT NULL,
  `zffs3` int(8) DEFAULT NULL,
  `ts_zt` int(8) NOT NULL DEFAULT '0',
  `date_hk` datetime DEFAULT NULL COMMENT '确认收款时间',
  `pic2` varchar(255) DEFAULT NULL,
  `date_hk1` datetime DEFAULT NULL COMMENT '确认收款时间',
  `date1` datetime DEFAULT NULL,
  `cold_status` int(2) unsigned DEFAULT '0',
  `zhh` int(2) DEFAULT '0',
  `ly` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_ppdd
-- ----------------------------
INSERT INTO `ot_ppdd` VALUES ('6', '9', '5', '1000', '15136272070', '15737168981', '2017-07-24 11:22:35', '2', 'Uploads/Pic/2017-07-24/59758b6c23cd3.png', '1', '1', '1', '0', '2017-07-24 13:53:48', null, '2017-07-24 13:54:11', null, '0', '0', '1111');

-- ----------------------------
-- Table structure for `ot_ppdd_ly`
-- ----------------------------
DROP TABLE IF EXISTS `ot_ppdd_ly`;
CREATE TABLE `ot_ppdd_ly` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ppdd_id` int(14) DEFAULT NULL,
  `user` varchar(14) DEFAULT NULL,
  `nr` text,
  `date` datetime DEFAULT NULL,
  `user_nc` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_ppdd_ly
-- ----------------------------
INSERT INTO `ot_ppdd_ly` VALUES ('1', '4', '15937187918', '凄凄切切', '2017-07-20 17:06:18', '');
INSERT INTO `ot_ppdd_ly` VALUES ('2', '4', '18137832920', '                 2625', '2017-07-20 17:06:45', '电信');

-- ----------------------------
-- Table structure for `ot_retrieve_token`
-- ----------------------------
DROP TABLE IF EXISTS `ot_retrieve_token`;
CREATE TABLE `ot_retrieve_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `token` varchar(48) DEFAULT NULL,
  `expire_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_retrieve_token
-- ----------------------------

-- ----------------------------
-- Table structure for `ot_rwfb`
-- ----------------------------
DROP TABLE IF EXISTS `ot_rwfb`;
CREATE TABLE `ot_rwfb` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) unsigned DEFAULT NULL,
  `name` varchar(60) DEFAULT NULL,
  `title` varchar(60) DEFAULT NULL,
  `content` text,
  `old_price` int(11) DEFAULT '0',
  `price` int(10) DEFAULT '0',
  `zt` enum('0','1','2','3','4') DEFAULT NULL,
  `imagepath` text,
  `addtime` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=57 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_rwfb
-- ----------------------------

-- ----------------------------
-- Table structure for `ot_rwmx`
-- ----------------------------
DROP TABLE IF EXISTS `ot_rwmx`;
CREATE TABLE `ot_rwmx` (
  `MA_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '前台提交信息，留言、申请等',
  `MA_type` varchar(30) DEFAULT '' COMMENT '数据类型',
  `MA_theme` varchar(60) DEFAULT '' COMMENT '留言主题',
  `MA_time` datetime DEFAULT NULL COMMENT '提交时间',
  `MA_replyTime` datetime DEFAULT NULL COMMENT '回复时间',
  `MA_dataID` int(11) DEFAULT '0' COMMENT '与其它表关联ID',
  `MA_userID` int(11) DEFAULT '0' COMMENT '与用户表关联ID',
  `MA_userName` varchar(50) DEFAULT '' COMMENT '留言用户名，管理列表显示',
  `MA_contact` text COMMENT '联系方式',
  `MA_userInfo` text COMMENT '用户其它相关信息',
  `MA_subIP` varchar(50) DEFAULT NULL COMMENT '信息提交IP',
  `MA_otherInfo` text COMMENT '其它信息',
  `MA_note` text COMMENT '用户留言内容',
  `MA_reply` text COMMENT '管理员回复内容',
  `MA_status` smallint(1) DEFAULT '0' COMMENT '审核状态',
  `zt` int(8) NOT NULL DEFAULT '0',
  `pic` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`MA_ID`),
  KEY `MA_dataID` (`MA_dataID`),
  KEY `MA_ID` (`MA_ID`),
  KEY `MA_userID` (`MA_userID`)
) ENGINE=MyISAM AUTO_INCREMENT=71 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_rwmx
-- ----------------------------

-- ----------------------------
-- Table structure for `ot_service`
-- ----------------------------
DROP TABLE IF EXISTS `ot_service`;
CREATE TABLE `ot_service` (
  `SV_ID` int(11) NOT NULL AUTO_INCREMENT,
  `SV_type` varchar(20) NOT NULL,
  `SV_time` datetime NOT NULL,
  `SV_rank` int(11) DEFAULT '0',
  `SV_theme` varchar(200) DEFAULT NULL,
  `SV_dataMode` varchar(20) DEFAULT NULL,
  `SV_accounts` varchar(200) DEFAULT NULL,
  `SV_state` int(11) DEFAULT '1',
  PRIMARY KEY (`SV_ID`),
  UNIQUE KEY `IM_ID` (`SV_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_service
-- ----------------------------

-- ----------------------------
-- Table structure for `ot_settings`
-- ----------------------------
DROP TABLE IF EXISTS `ot_settings`;
CREATE TABLE `ot_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_settings
-- ----------------------------

-- ----------------------------
-- Table structure for `ot_shopsj`
-- ----------------------------
DROP TABLE IF EXISTS `ot_shopsj`;
CREATE TABLE `ot_shopsj` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sjmc` varchar(255) DEFAULT NULL,
  `jyxm` varchar(255) DEFAULT NULL,
  `lxfs` varchar(255) DEFAULT NULL,
  `dz` varchar(255) DEFAULT NULL,
  `slt` varchar(255) DEFAULT NULL,
  `content` longtext,
  `user` varchar(255) DEFAULT NULL,
  `zt` int(15) NOT NULL DEFAULT '0',
  `date` datetime DEFAULT NULL,
  `leixin` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_shopsj
-- ----------------------------

-- ----------------------------
-- Table structure for `ot_shop_image`
-- ----------------------------
DROP TABLE IF EXISTS `ot_shop_image`;
CREATE TABLE `ot_shop_image` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `imagepath` varchar(20) DEFAULT NULL,
  `addtime` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_shop_image
-- ----------------------------

-- ----------------------------
-- Table structure for `ot_shop_leibie`
-- ----------------------------
DROP TABLE IF EXISTS `ot_shop_leibie`;
CREATE TABLE `ot_shop_leibie` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(50) DEFAULT '' COMMENT '分类名称',
  `addtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_shop_leibie
-- ----------------------------

-- ----------------------------
-- Table structure for `ot_shop_orderform`
-- ----------------------------
DROP TABLE IF EXISTS `ot_shop_orderform`;
CREATE TABLE `ot_shop_orderform` (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `user` varchar(30) DEFAULT NULL,
  `project` varchar(30) DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  `sumprice` decimal(10,2) NOT NULL,
  `addtime` varchar(30) DEFAULT NULL,
  `zt` int(1) NOT NULL DEFAULT '0',
  `address` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_shop_orderform
-- ----------------------------

-- ----------------------------
-- Table structure for `ot_shop_project`
-- ----------------------------
DROP TABLE IF EXISTS `ot_shop_project`;
CREATE TABLE `ot_shop_project` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) unsigned DEFAULT NULL,
  `name` varchar(60) DEFAULT NULL,
  `title` varchar(60) DEFAULT NULL,
  `content` text,
  `old_price` int(11) DEFAULT '0',
  `price` int(10) DEFAULT '0',
  `zt` enum('0','1','2','3','4') DEFAULT NULL,
  `imagepath` text,
  `addtime` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=64 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_shop_project
-- ----------------------------

-- ----------------------------
-- Table structure for `ot_system`
-- ----------------------------
DROP TABLE IF EXISTS `ot_system`;
CREATE TABLE `ot_system` (
  `SYS_ID` int(11) NOT NULL AUTO_INCREMENT,
  `SYS_theme` varchar(100) DEFAULT NULL,
  `SYS_address` varchar(200) DEFAULT NULL,
  `SYS_postCode` varchar(50) DEFAULT NULL,
  `SYS_contact` varchar(50) DEFAULT '',
  `SYS_mobile` varchar(50) DEFAULT '',
  `SYS_mail` varchar(80) DEFAULT NULL,
  `SYS_phone` varchar(50) DEFAULT NULL,
  `SYS_hotPhone` varchar(50) DEFAULT NULL,
  `SYS_fax` varchar(50) DEFAULT NULL,
  `SYS_qq` varchar(30) DEFAULT NULL,
  `SYS_banquan` varchar(100) DEFAULT NULL,
  `SYS_seoTitle` varchar(300) DEFAULT '',
  `SYS_seoWord` text,
  `SYS_seoDesc` text,
  `SPS_smtpHost` varchar(80) DEFAULT NULL,
  `SPS_sendMail` varchar(80) DEFAULT NULL,
  `SPS_sendPwd` varchar(80) DEFAULT NULL,
  `SPS_giveMail` varchar(80) DEFAULT NULL,
  `a_ztj` decimal(15,4) NOT NULL DEFAULT '0.0000' COMMENT '直推荐奖',
  `a_ztj2` decimal(15,4) NOT NULL DEFAULT '0.0000' COMMENT '间推奖',
  `a_ztj3` decimal(15,4) NOT NULL DEFAULT '0.0000' COMMENT '间间推奖',
  `a_bdj` decimal(15,4) NOT NULL DEFAULT '0.0000' COMMENT '报单奖',
  `a_ld8` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `a_ld9` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `a_ld10` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `a_kd_zsb` decimal(15,4) NOT NULL DEFAULT '0.0000' COMMENT '钻石币开单数量',
  `a_sxf` decimal(15,4) NOT NULL DEFAULT '0.0000' COMMENT '交易大厅手续费',
  `a_btbjg` decimal(15,4) NOT NULL DEFAULT '0.0000' COMMENT '比特币价格',
  `a_fxzl` decimal(15,4) NOT NULL DEFAULT '0.0000' COMMENT '发行总量',
  `a_fuhuo` decimal(15,4) NOT NULL DEFAULT '0.0000' COMMENT '复活费用',
  `a_mrfh_cj` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `a_ybfxsl` decimal(15,4) NOT NULL DEFAULT '0.0000' COMMENT '銀幣發行數量',
  `a_zsbfxsl` decimal(15,4) NOT NULL,
  `a_ybhuilv` decimal(15,6) NOT NULL,
  `a_zsbhuilv` decimal(15,6) NOT NULL,
  `a_bdzxds` decimal(15,4) NOT NULL,
  `zt` int(8) NOT NULL DEFAULT '0',
  `toggleshop` varchar(15) DEFAULT '0',
  PRIMARY KEY (`SYS_ID`),
  KEY `SYS_postCode` (`SYS_postCode`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_system
-- ----------------------------

-- ----------------------------
-- Table structure for `ot_taobaoset`
-- ----------------------------
DROP TABLE IF EXISTS `ot_taobaoset`;
CREATE TABLE `ot_taobaoset` (
  `TBS_ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `TBS_state` tinyint(1) DEFAULT '1',
  `TBS_appkey` varchar(30) DEFAULT '',
  `TBS_secret` varchar(150) DEFAULT '',
  PRIMARY KEY (`TBS_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_taobaoset
-- ----------------------------

-- ----------------------------
-- Table structure for `ot_tgbz`
-- ----------------------------
DROP TABLE IF EXISTS `ot_tgbz`;
CREATE TABLE `ot_tgbz` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zffs1` int(8) NOT NULL DEFAULT '0' COMMENT '微信支付',
  `zffs2` int(8) NOT NULL DEFAULT '0' COMMENT '支付宝支付',
  `zffs3` int(8) NOT NULL DEFAULT '0' COMMENT '银行卡支付',
  `jb` decimal(15,0) NOT NULL DEFAULT '0' COMMENT '排单额',
  `zt` int(8) NOT NULL DEFAULT '0',
  `user` varchar(255) DEFAULT NULL,
  `qr_zt` int(255) DEFAULT '0',
  `user_tjr` varchar(255) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `user_nc` varchar(255) DEFAULT NULL,
  `cf_ds` int(8) NOT NULL DEFAULT '0',
  `jycg_ds` int(8) NOT NULL DEFAULT '0',
  `yid` int(11) DEFAULT NULL,
  `yjb` decimal(15,0) NOT NULL DEFAULT '0',
  `zhh` int(2) NOT NULL DEFAULT '0',
  `zhh1` int(2) NOT NULL DEFAULT '0',
  `date1` datetime DEFAULT NULL COMMENT '冻结',
  `date2` datetime DEFAULT NULL COMMENT '显示',
  `yfk` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_tgbz
-- ----------------------------
INSERT INTO `ot_tgbz` VALUES ('9', '1', '1', '1', '1000', '1', '15136272070', '1', '15737168980', '2017-07-24 11:21:35', '王小二', '0', '0', null, '0', '0', '0', null, null, null);

-- ----------------------------
-- Table structure for `ot_topup`
-- ----------------------------
DROP TABLE IF EXISTS `ot_topup`;
CREATE TABLE `ot_topup` (
  `TU_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户订单表',
  `TU_type` varchar(30) DEFAULT '' COMMENT '订单类型',
  `TU_time` datetime DEFAULT NULL COMMENT '添加时间',
  `TU_revTime` datetime DEFAULT NULL COMMENT '状态修改时间',
  `TU_userID` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `TU_money` float(11,2) DEFAULT '0.00' COMMENT '充值金额',
  `TU_payment` varchar(30) DEFAULT '' COMMENT '充值方式',
  `TU_userNote` varchar(250) DEFAULT '' COMMENT '会员留言',
  `TU_adminNote` varchar(250) DEFAULT '' COMMENT '管理员备注',
  `TU_status` tinyint(1) DEFAULT '0' COMMENT '到款状态',
  PRIMARY KEY (`TU_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_topup
-- ----------------------------

-- ----------------------------
-- Table structure for `ot_turn`
-- ----------------------------
DROP TABLE IF EXISTS `ot_turn`;
CREATE TABLE `ot_turn` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `consume` int(11) DEFAULT '0' COMMENT '抽奖单次消耗',
  `switch` tinyint(1) DEFAULT '1' COMMENT '  抽奖开关',
  `turn_num` char(200) DEFAULT '' COMMENT ' 奖励内容',
  `turn_v` char(200) DEFAULT '' COMMENT '概率',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_turn
-- ----------------------------

-- ----------------------------
-- Table structure for `ot_turn_log`
-- ----------------------------
DROP TABLE IF EXISTS `ot_turn_log`;
CREATE TABLE `ot_turn_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '用户id',
  `consume` int(11) DEFAULT '0' COMMENT ' 消耗',
  `reward_id` int(11) DEFAULT '0' COMMENT '几等奖',
  `reward_num` int(11) DEFAULT '0' COMMENT '奖励金额',
  `addtime` int(11) DEFAULT '0' COMMENT '获奖时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_turn_log
-- ----------------------------

-- ----------------------------
-- Table structure for `ot_upfile`
-- ----------------------------
DROP TABLE IF EXISTS `ot_upfile`;
CREATE TABLE `ot_upfile` (
  `UF_ID` int(11) NOT NULL AUTO_INCREMENT,
  `UF_time` datetime DEFAULT NULL,
  `UF_type` varchar(25) DEFAULT NULL,
  `UF_oldName` varchar(80) DEFAULT NULL,
  `UF_name` varchar(50) DEFAULT NULL,
  `UF_ext` varchar(10) DEFAULT NULL,
  `UF_size` int(11) DEFAULT '0',
  `UF_width` int(11) DEFAULT '0',
  `UF_height` int(11) DEFAULT '0',
  `UF_isThumb` smallint(6) DEFAULT '0',
  `UF_thumbName` varchar(50) DEFAULT NULL,
  `UF_useNum` mediumint(9) DEFAULT '0',
  PRIMARY KEY (`UF_ID`),
  UNIQUE KEY `UF_ID` (`UF_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=952 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_upfile
-- ----------------------------

-- ----------------------------
-- Table structure for `ot_user`
-- ----------------------------
DROP TABLE IF EXISTS `ot_user`;
CREATE TABLE `ot_user` (
  `UE_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '商城用户注册登录表',
  `UE_img` varchar(60) DEFAULT '' COMMENT '用户头像',
  `UE_account` varchar(60) NOT NULL DEFAULT '' COMMENT '登录账号',
  `UE_accName` varchar(60) DEFAULT NULL COMMENT '推荐人账号',
  `sfjl` int(15) NOT NULL DEFAULT '0',
  `zcr` varchar(60) NOT NULL DEFAULT '' COMMENT '推荐人',
  `UE_Faccount` varchar(30) DEFAULT '0' COMMENT '父账号',
  `UE_verMail` varchar(60) NOT NULL DEFAULT '' COMMENT '验证邮箱',
  `UE_check` smallint(1) DEFAULT '0' COMMENT '是否验证，0-未验证，1-邮箱验证，2-手机验证',
  `check_time` varchar(100) DEFAULT NULL,
  `UE_actiCode` varchar(10) DEFAULT '' COMMENT '邮箱/手机验证激活码',
  `UE_password` varchar(80) DEFAULT '' COMMENT '用户密码',
  `UE_regTime` datetime DEFAULT NULL COMMENT '注册时间',
  `UE_regIP` varchar(60) DEFAULT '',
  `UE_nowTime` text COMMENT '当前登录时间',
  `UE_nowIP` varchar(60) DEFAULT '' COMMENT '当前登录IP',
  `UE_lastTime` text COMMENT '最近一次登录时间',
  `UE_lastIP` varchar(60) DEFAULT '' COMMENT '最近一次录陆IP',
  `UE_logNum` int(11) DEFAULT '0' COMMENT '用户登录次数',
  `UE_status` smallint(1) DEFAULT '1' COMMENT '用户状态，0-正常，1-禁用，2-未激活',
  `UE_level` int(1) DEFAULT '0' COMMENT '会员等级',
  `UE_note` text COMMENT '管理页备注信息',
  `UE_integral` decimal(15,0) DEFAULT '0' COMMENT '当前账户积分余额',
  `UE_money` decimal(15,0) DEFAULT '0' COMMENT '当前帐户余额（静态钱包）',
  `UE_sum` float(11,0) DEFAULT '0' COMMENT '当前账户总消费数',
  `UE_info` text COMMENT '用户信息',
  `UE_secpwd` varchar(80) DEFAULT NULL COMMENT '二级密码',
  `UE_theme` varchar(60) DEFAULT '',
  `UE_tjx` varchar(60) DEFAULT NULL COMMENT '推荐奖总和',
  `UE_ldx` varchar(60) DEFAULT NULL COMMENT '领导奖',
  `UE_mailCheck` varchar(30) DEFAULT '0' COMMENT '邮箱验证0未验证，1验证了',
  `UE_sfz` varchar(20) DEFAULT NULL COMMENT '身份证',
  `UE_qq` varchar(20) DEFAULT NULL,
  `UE_phone` varchar(20) DEFAULT NULL COMMENT '手机',
  `UE_truename` varchar(60) DEFAULT NULL COMMENT '真实名字',
  `UE_activeTime` text COMMENT '激活时间',
  `UE_stop` tinyint(2) DEFAULT '1' COMMENT '停止分红，0标志停止分红，1标志正常分红',
  `UE_toActive` tinyint(2) DEFAULT '0' COMMENT '1表示已经被用过去激活新增帐号',
  `UE_drpd` varchar(60) DEFAULT NULL,
  `zbqx` int(5) NOT NULL DEFAULT '0' COMMENT '是否充许其它账号转币',
  `zbzh` varchar(60) DEFAULT NULL,
  `ybhe` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `zsbhe` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `email` varchar(60) DEFAULT NULL,
  `jihuouser` varchar(60) NOT NULL,
  `btbdz` varchar(60) NOT NULL DEFAULT '0',
  `pin` varchar(255) DEFAULT NULL COMMENT '激活码',
  `mz` varchar(255) DEFAULT NULL,
  `xin` varchar(255) DEFAULT NULL,
  `weixin` varchar(255) DEFAULT NULL,
  `zfb` varchar(255) DEFAULT NULL,
  `yhmc` varchar(255) DEFAULT NULL COMMENT '银行名称',
  `zhxm` varchar(255) DEFAULT NULL COMMENT '开户地址',
  `yhzh` varchar(255) DEFAULT NULL COMMENT '银行卡号',
  `tz_leiji` decimal(15,0) NOT NULL DEFAULT '0',
  `date_leiji` datetime DEFAULT NULL,
  `jl_he` decimal(15,0) NOT NULL DEFAULT '0' COMMENT '动态钱包',
  `tj_he` decimal(15,0) NOT NULL DEFAULT '0' COMMENT '推荐奖',
  `pp_user` varchar(255) DEFAULT NULL,
  `tx_leiji` decimal(15,0) NOT NULL,
  `tx_date` datetime DEFAULT NULL,
  `shop_money` int(11) DEFAULT '0',
  `cold` int(2) DEFAULT '0' COMMENT '冻结状态（0，未冻结   1，已冻结）',
  `cold_time` datetime DEFAULT NULL COMMENT '冻结时间',
  `UE_regTime1` datetime DEFAULT NULL,
  `cold_type` int(1) DEFAULT '0' COMMENT '冻结原因（1,72小时不排单2,48小时不打款3,24小时不收款）',
  `sfz_img_url` text,
  `li_lv` varchar(255) DEFAULT '0' COMMENT '排单金额',
  `ue_pdb` int(11) DEFAULT '0',
  `liyou` text COMMENT '审核不通过理由',
  PRIMARY KEY (`UE_ID`),
  UNIQUE KEY `anme` (`UE_account`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_user
-- ----------------------------
INSERT INTO `ot_user` VALUES ('1', '', '15737168980', '15937178918', '0', '15937178918', '0', '', '1', null, '', 'e10adc3949ba59abbe56e057f20f883e', '2017-07-11 17:38:37', '1.193.126.112', null, '', null, '', '0', '0', '0', null, '0', '30', '0', null, 'e10adc3949ba59abbe56e057f20f883e', '小余', null, null, '0', '412159166952525149', null, null, '小余', null, '1', '0', null, '0', null, '0.0000', '0.0000', null, '', '0', null, null, null, '王11', 'wang111', '郑州', '郑州', '6221504910002558888', '0', null, '105', '70', null, '0', null, '35', '0', null, '2017-07-11 17:38:37', '0', 'Uploads/Pic/2017-07-11/59649c9da33fc.jpg', '30', '2', null);
INSERT INTO `ot_user` VALUES ('2', '', '15136272070', '15737168980', '0', '15737168980', '0', '', '1', null, '', '25f9e794323b453885f5181f1b624d0b', '2017-07-11 18:23:18', '115.60.190.208', null, '', null, '', '0', '0', '0', null, '0', '10350', '0', null, 'e10adc3949ba59abbe56e057f20f883e', '王小二', null, null, '0', '41018419963256456', null, null, '王坤', null, '1', '0', null, '0', null, '0.0000', '0.0000', null, '', '0', null, null, null, '111', '11', '郑州', '中国邮政', '11111111111', '0', null, '75', '160', '15737168981', '0', null, '25', '0', null, '2017-07-11 18:23:18', '0', 'Uploads/Pic/2017-07-11/5964a71645007.png', '30', '2', null);
INSERT INTO `ot_user` VALUES ('3', '', '15937187918', '15136272070', '0', '15136272070', '0', '', '1', '2017-07-15 15:47:41', '', 'e10adc3949ba59abbe56e057f20f883e', '2017-07-15 09:40:06', '', null, '', null, '', '0', '0', '0', null, '0', '0', '0', '', 'e10adc3949ba59abbe56e057f20f883e', '', null, null, '0', '410125499520144125', null, null, '小伟', null, '1', '0', null, '0', null, '0.0000', '0.0000', null, '', '0', null, null, null, '1111', '111', '郑州', '郑州', '6221504910003856969', '0', null, '44666', '100', '18137832920', '0', null, '0', '1', null, '2017-07-15 09:40:06', '3', 'Uploads/Pic/2017-07-15/59697276c132a.jpg', '30', '1', null);
INSERT INTO `ot_user` VALUES ('5', '', '15737168981', '15136272070', '0', '15737168980', '0', '', '1', '2017-07-15 17:14:25', '', 'e10adc3949ba59abbe56e057f20f883e', '2017-07-15 17:12:36', '', null, '', null, '', '0', '0', '0', null, '0', '4630', '0', '', 'e10adc3949ba59abbe56e057f20f883e', '', null, null, '0', '401189199207011569', null, '', '大余', null, '1', '0', null, '0', null, '0.0000', '0.0000', null, '', '0', null, null, null, '', '', '郑州', null, '6221504910002558888', '0', null, '0', '0', '15136272070', '0', null, '0', '0', null, '2017-07-15 17:12:36', '0', 'Uploads/Pic/2017-07-15/5969dc8409ac1.jpg', '30', '3', null);
INSERT INTO `ot_user` VALUES ('6', '', '18137832920', '15937187918', '0', '15937187918', '0', '', '0', '2017-07-18 15:21:54', '', 'e10adc3949ba59abbe56e057f20f883e', '2017-07-18 15:03:39', '', null, '', null, '', '0', '0', '0', null, '0', '0', '0', null, 'e10adc3949ba59abbe56e057f20f883e', '电信', null, null, '0', '41018419920', null, null, '电信', null, '1', '0', null, '0', null, '0.0000', '0.0000', null, '', '0', null, null, null, '1111', '111', '郑州', '11111', '6221504910003659897', '0', null, '0', '0', '15937187918', '0', null, '0', '0', null, '2017-07-18 15:03:39', '0', 'Uploads/Pic/2017-07-18/596db2cb0676a.jpg', '30', '0', '12222');

-- ----------------------------
-- Table structure for `ot_userget`
-- ----------------------------
DROP TABLE IF EXISTS `ot_userget`;
CREATE TABLE `ot_userget` (
  `UG_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '商城用户注册登录表',
  `UG_account` varchar(60) DEFAULT '' COMMENT '登录账号',
  `UG_type` varchar(60) DEFAULT '' COMMENT '奖金分类',
  `UG_integral` decimal(15,4) DEFAULT '0.0000' COMMENT '当前账户种子币余额',
  `UG_money` varchar(255) DEFAULT '0.0000' COMMENT '当前帐户金币余额',
  `UG_getTime` datetime DEFAULT NULL COMMENT '结算时间',
  `UG_allGet` decimal(20,0) DEFAULT NULL COMMENT '用户密码',
  `UG_balance` decimal(20,0) DEFAULT NULL COMMENT '当前账户余额',
  `UG_regIP` varchar(30) DEFAULT '',
  `UG_dataType` varchar(10) DEFAULT '' COMMENT '分红类型',
  `UG_note` text COMMENT '金币获取说明',
  `UG_othraccount` varchar(60) DEFAULT NULL COMMENT '推荐帐号/开单帐号',
  `yb` decimal(15,2) DEFAULT '0.00',
  `ybhe` decimal(15,2) DEFAULT NULL,
  `zsb` decimal(15,2) DEFAULT NULL,
  `zsbhe` decimal(15,2) DEFAULT NULL,
  `yb1` decimal(15,2) DEFAULT NULL,
  `zsb1` decimal(15,2) DEFAULT NULL,
  `pdb_js_zh` varchar(255) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  PRIMARY KEY (`UG_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_userget
-- ----------------------------
INSERT INTO `ot_userget` VALUES ('1', '15937187918', 'jb', '0.0000', '+300', '2017-07-18 14:31:21', null, null, '', 'pdxizr', '排单利息转入', null, '0.00', null, null, null, null, null, null, null);
INSERT INTO `ot_userget` VALUES ('2', '15937187918', 'jb', '0.0000', '+1000', '2017-07-18 14:31:21', null, null, '', 'tgbz', '提供帮助本金转入', null, '0.00', null, null, null, null, null, null, null);
INSERT INTO `ot_userget` VALUES ('5', '15937187918', 'mp', '0.0000', '-2', '2017-07-18 15:24:11', '10', '8', '', 'jbzc', '激活码转出', null, '0.00', null, null, null, null, null, '18137832920', null);
INSERT INTO `ot_userget` VALUES ('9', null, 'tjj', '0.0000', null, '2017-07-20 10:21:23', null, null, '', 'tjj', '推荐奖转入', null, '0.00', null, null, null, null, null, null, null);
INSERT INTO `ot_userget` VALUES ('6', '18137832920', 'mp', '0.0000', '+2', '2017-07-18 15:24:11', '0', '2', '', 'jbzr', '激活码转入', null, '0.00', null, null, null, null, null, '15937187918', null);
INSERT INTO `ot_userget` VALUES ('7', '15937187918', 'jb', '0.0000', '-1', '2017-07-18 15:28:19', '2', '1', '', 'pdb', '排单币转出', '1', '0.00', null, '1.00', null, null, '1.00', '18137832920', '1');
INSERT INTO `ot_userget` VALUES ('8', '18137832920', 'jb', '0.0000', '+1', '2017-07-18 15:28:19', '0', '1', '', 'pdb', '排单币转入', null, '0.00', '1.00', '2.00', '1.00', '1.00', '1.00', '15937187918', '2');
INSERT INTO `ot_userget` VALUES ('10', null, 'tjj', '0.0000', '90', '2017-07-20 10:26:14', null, null, '', 'tjj', '推荐奖转入', null, '0.00', null, null, null, null, null, null, null);
INSERT INTO `ot_userget` VALUES ('11', null, 'tjj', '0.0000', '+90', '2017-07-20 11:23:58', null, null, '', 'tjj', '推荐奖转入', null, '0.00', null, null, null, null, null, null, null);
INSERT INTO `ot_userget` VALUES ('12', null, 'tjj', '0.0000', '+90', '2017-07-20 11:25:25', null, null, '', 'tjj', '推荐奖转入', null, '0.00', null, null, null, null, null, null, null);
INSERT INTO `ot_userget` VALUES ('13', '15937187918', 'tjj', '0.0000', '+90', '2017-07-20 11:30:04', null, null, '', 'tjj', '推荐奖转入', null, '0.00', null, null, null, null, null, null, null);
INSERT INTO `ot_userget` VALUES ('14', '15937187918', 'jb', '0.0000', '100', '2017-07-20 11:44:08', '1300', '1400', '', 'xtzs', '静态钱包系统操作', null, '0.00', null, null, null, null, null, null, null);
INSERT INTO `ot_userget` VALUES ('15', '15937187918', 'jb', '0.0000', '+100', '2017-07-20 11:45:39', null, null, '', 'xtzs', '动态钱包系统赠送', null, '0.00', null, null, null, '100.00', null, null, null);
INSERT INTO `ot_userget` VALUES ('16', '15937187918', 'jb', '0.0000', '+122', '2017-07-20 11:46:44', null, null, '', 'xtzs', '动态钱包系统赠送', null, '0.00', null, null, null, '122.00', null, null, null);
INSERT INTO `ot_userget` VALUES ('17', '15937187918', 'yb', '0.0000', '+111', '2017-07-20 11:47:30', '1400', '1511', '', 'xtzs', '静态钱包系统操作', null, '0.00', null, null, null, null, null, null, null);
INSERT INTO `ot_userget` VALUES ('18', '15937187918', 'yb', '0.0000', '+111', '2017-07-20 11:47:59', '1511', '1622', '', 'xtzs', '静态钱包系统操作', null, '0.00', null, null, null, null, null, null, null);
INSERT INTO `ot_userget` VALUES ('19', '15937187918', 'yb', '0.0000', '+11111', '2017-07-20 12:35:15', null, null, '', 'xtzs', '动态钱包系统赠送', null, '0.00', null, null, null, null, null, null, null);
INSERT INTO `ot_userget` VALUES ('20', '15937187918', 'jb', '0.0000', '+500', '2017-07-20 14:10:35', null, null, '', 'tjtx', '推荐奖提现', null, '0.00', null, null, null, null, null, null, null);
INSERT INTO `ot_userget` VALUES ('21', '15937187918', 'jb', '0.0000', '+500', '2017-07-20 14:10:35', null, null, '', 'tjtx', '推荐奖提现转入', null, '0.00', null, null, null, null, null, null, null);
INSERT INTO `ot_userget` VALUES ('22', '15937187918', 'jb', '0.0000', '-1300', '2017-07-20 14:27:41', null, '2122', '', 'jsbz', '静态提现', null, '0.00', null, null, null, null, null, null, null);
INSERT INTO `ot_userget` VALUES ('23', '15937187918', 'jb', '0.0000', '-1000', '2017-07-20 14:40:25', null, '2000', '', 'jsbz', '静态提现', null, '0.00', null, null, null, null, null, null, null);
INSERT INTO `ot_userget` VALUES ('24', '15937187918', 'jb', '0.0000', '-1000', '2017-07-20 18:52:06', null, '1000', '', 'jsbz', '静态提现', null, '0.00', null, null, null, null, null, null, null);
INSERT INTO `ot_userget` VALUES ('25', '15737168981', 'jb', '0.0000', '-1000', '2017-07-24 11:22:07', null, '5630', '', 'jsbz', '静态提现', null, '0.00', null, null, null, null, null, null, null);
INSERT INTO `ot_userget` VALUES ('26', '15136272070', 'jb', '0.0000', '-1000', '2017-07-24 11:27:55', null, '10050', '', 'jsbz', '静态提现', null, '0.00', null, null, null, null, null, null, null);
INSERT INTO `ot_userget` VALUES ('27', '15136272070', 'jb', '0.0000', '+300', '2017-07-24 13:54:11', null, null, '', 'pdxizr', '排单利息转入', null, '0.00', null, null, null, null, null, null, null);
INSERT INTO `ot_userget` VALUES ('28', '15136272070', 'jb', '0.0000', '+1000', '2017-07-24 13:54:11', null, null, '', 'tgbz', '提供帮助本金转入', null, '0.00', null, null, null, null, null, null, null);
INSERT INTO `ot_userget` VALUES ('29', '15737168980', 'jb', '0.0000', '50', '2017-07-24 13:54:11', '55', '105', '', 'jlj', '一代奖5%', '2', '0.00', null, null, null, null, null, null, null);
INSERT INTO `ot_userget` VALUES ('30', null, 'jb', '0.0000', '20', '2017-07-24 13:54:11', null, null, '', 'jlj', '二代奖2%', '2', '0.00', null, null, null, null, null, null, null);

-- ----------------------------
-- Table structure for `ot_userget1`
-- ----------------------------
DROP TABLE IF EXISTS `ot_userget1`;
CREATE TABLE `ot_userget1` (
  `UG_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '商城用户注册登录表',
  `UG_account` varchar(60) DEFAULT '' COMMENT '登录账号',
  `UG_type` varchar(60) DEFAULT '' COMMENT '奖金分类',
  `UG_integral` decimal(15,4) DEFAULT '0.0000' COMMENT '当前账户种子币余额',
  `UG_money` varchar(255) DEFAULT '0.0000' COMMENT '当前帐户金币余额',
  `UG_getTime` datetime DEFAULT NULL COMMENT '结算时间',
  `UG_allGet` decimal(20,0) DEFAULT NULL COMMENT '用户密码',
  `UG_balance` decimal(20,0) DEFAULT NULL COMMENT '当前账户余额',
  `UG_regIP` varchar(30) DEFAULT '',
  `UG_dataType` varchar(10) DEFAULT '' COMMENT '分红类型',
  `UG_note` text COMMENT '金币获取说明',
  `UG_othraccount` varchar(60) DEFAULT NULL COMMENT '推荐帐号/开单帐号',
  `jd_date` datetime DEFAULT NULL COMMENT '解冻时间',
  `dtzt` int(2) DEFAULT '0',
  `UG_qrTime` datetime DEFAULT NULL,
  PRIMARY KEY (`UG_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=5421 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_userget1
-- ----------------------------

-- ----------------------------
-- Table structure for `ot_usergroup`
-- ----------------------------
DROP TABLE IF EXISTS `ot_usergroup`;
CREATE TABLE `ot_usergroup` (
  `UG_ID` int(11) NOT NULL AUTO_INCREMENT,
  `UG_time` datetime DEFAULT NULL,
  `UG_name` varchar(50) DEFAULT NULL,
  `UG_rightStr` longtext,
  `UG_note` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`UG_ID`),
  KEY `UG_ID` (`UG_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_usergroup
-- ----------------------------

-- ----------------------------
-- Table structure for `ot_userinfo`
-- ----------------------------
DROP TABLE IF EXISTS `ot_userinfo`;
CREATE TABLE `ot_userinfo` (
  `UI_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户详细信息表',
  `UI_userID` int(11) DEFAULT '0' COMMENT '用户表ID',
  `UI_time` datetime DEFAULT NULL COMMENT '添加时间',
  `UI_revTime` datetime DEFAULT NULL COMMENT '修改时间',
  `UI_realName` varchar(30) DEFAULT '' COMMENT '真实姓名',
  `UI_payaccount` varchar(30) DEFAULT NULL COMMENT '收款账号信息',
  `UI_payStyle` varchar(10) DEFAULT NULL COMMENT '收款方式',
  `UI_isindex` smallint(1) DEFAULT NULL COMMENT '是否设为默认',
  `UI_opendress` varchar(30) DEFAULT NULL COMMENT '开户行',
  PRIMARY KEY (`UI_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_userinfo
-- ----------------------------

-- ----------------------------
-- Table structure for `ot_userlevel`
-- ----------------------------
DROP TABLE IF EXISTS `ot_userlevel`;
CREATE TABLE `ot_userlevel` (
  `UL_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '会员等级划分表',
  `UL_theme` varchar(200) DEFAULT '' COMMENT '等级名称',
  `UL_price` float(11,2) DEFAULT '0.00' COMMENT '消费金额',
  `UL_cheap` float(11,2) DEFAULT '10.00' COMMENT '折扣',
  `UL_time` datetime DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`UL_ID`),
  UNIQUE KEY `IM_ID` (`UL_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_userlevel
-- ----------------------------

-- ----------------------------
-- Table structure for `ot_user_jj`
-- ----------------------------
DROP TABLE IF EXISTS `ot_user_jj`;
CREATE TABLE `ot_user_jj` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(255) DEFAULT NULL,
  `r_id` varchar(255) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `zt` int(8) NOT NULL DEFAULT '0',
  `jb` decimal(15,0) DEFAULT NULL,
  `tgbz_id` int(11) NOT NULL,
  `jianglilixi` varchar(50) DEFAULT '',
  `zhh` int(2) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_user_jj
-- ----------------------------
INSERT INTO `ot_user_jj` VALUES ('12', '15737168980', null, '2017-07-21 13:45:17', '提供帮助', '2', '1000', '7', '', '0');

-- ----------------------------
-- Table structure for `ot_user_jl`
-- ----------------------------
DROP TABLE IF EXISTS `ot_user_jl`;
CREATE TABLE `ot_user_jl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(255) DEFAULT NULL,
  `r_id` int(15) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `zt` int(8) NOT NULL DEFAULT '0',
  `jb` decimal(15,0) DEFAULT NULL,
  `ds` varchar(255) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `jj` decimal(10,0) DEFAULT NULL,
  `leixin` int(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=900 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_user_jl
-- ----------------------------

-- ----------------------------
-- Table structure for `ot_wallet`
-- ----------------------------
DROP TABLE IF EXISTS `ot_wallet`;
CREATE TABLE `ot_wallet` (
  `user_id` varchar(20) NOT NULL,
  `money` int(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ot_wallet
-- ----------------------------
