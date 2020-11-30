# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.6.47)
# Database: apim
# Generation Time: 2020-11-30 08:38:31 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table apim_admin_group
# ------------------------------------------------------------

DROP TABLE IF EXISTS `apim_admin_group`;

CREATE TABLE `apim_admin_group` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `description` text COMMENT '备注',
  `menus` text COMMENT '用户组拥有的菜单id',
  `listorder` smallint(5) unsigned DEFAULT '0' COMMENT '排序',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `admin_id` smallint(3) NOT NULL DEFAULT '0' COMMENT '操作人',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `listorder` (`listorder`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='分组';

LOCK TABLES `apim_admin_group` WRITE;
/*!40000 ALTER TABLE `apim_admin_group` DISABLE KEYS */;

INSERT INTO `apim_admin_group` (`id`, `name`, `description`, `menus`, `listorder`, `updated_at`, `created_at`, `admin_id`)
VALUES
	(1,'API 查看','只能管理自己 API','274,193,250,251,253,252,264,263,266,270,269,268,267,255,257,256,259,258,265,240,242,244,243,241',0,1533029848,0,2),
	(2,'管理员','新增权限要勾选','274,250,254,253,252,251,261,264,263,193,245,249,248,262,247,246,255,257,266,270,269,268,267,240,244,243,242,241,19,275,260,273,272,33,66,36,35,34',0,1536721952,1529913257,2);

/*!40000 ALTER TABLE `apim_admin_group` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table apim_admin_group_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `apim_admin_group_user`;

CREATE TABLE `apim_admin_group_user` (
  `admin_id` int(10) unsigned NOT NULL COMMENT '用户id',
  `group_id` mediumint(8) unsigned NOT NULL COMMENT '角色id',
  UNIQUE KEY `uid_group_id` (`admin_id`,`group_id`) USING BTREE,
  KEY `uid` (`admin_id`) USING BTREE,
  KEY `group_id` (`group_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户所属分组';



# Dump of table apim_admin_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `apim_admin_log`;

CREATE TABLE `apim_admin_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_menu_id` mediumint(8) NOT NULL DEFAULT '0' COMMENT '菜单id',
  `querystring` varchar(255) DEFAULT '' COMMENT '参数',
  `data` text COMMENT 'POST数据',
  `ip` varchar(18) NOT NULL DEFAULT '',
  `admin_id` mediumint(8) NOT NULL DEFAULT '0' COMMENT '操作人',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `primary_id` int(11) DEFAULT '0' COMMENT '表中主键ID',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_menu_id` (`admin_menu_id`) USING BTREE,
  KEY `idx_admin_id` (`admin_id`) USING BTREE,
  KEY `idx_created_at` (`created_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

LOCK TABLES `apim_admin_log` WRITE;
/*!40000 ALTER TABLE `apim_admin_log` DISABLE KEYS */;

INSERT INTO `apim_admin_log` (`id`, `admin_menu_id`, `querystring`, `data`, `ip`, `admin_id`, `created_at`, `updated_at`, `primary_id`)
VALUES
	(1,283,NULL,NULL,'127.0.0.1',1,1606725456,1606725456,0),
	(2,283,NULL,NULL,'127.0.0.1',1,1606725473,1606725473,0);

/*!40000 ALTER TABLE `apim_admin_log` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table apim_admin_menu
# ------------------------------------------------------------

DROP TABLE IF EXISTS `apim_admin_menu`;

CREATE TABLE `apim_admin_menu` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(40) NOT NULL DEFAULT '' COMMENT '菜单名称',
  `parentid` smallint(6) DEFAULT '0' COMMENT '上级',
  `icon` varchar(20) DEFAULT '' COMMENT '图标',
  `m` varchar(10) NOT NULL DEFAULT 'admin' COMMENT '模块',
  `c` varchar(20) NOT NULL DEFAULT '' COMMENT 'controller',
  `a` varchar(20) NOT NULL DEFAULT '' COMMENT 'action',
  `data` varchar(50) DEFAULT '' COMMENT '更多参数',
  `group` varchar(50) DEFAULT '' COMMENT '分组',
  `listorder` smallint(6) unsigned DEFAULT '999',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示1:显示,2:不显示',
  `write_log` tinyint(1) NOT NULL DEFAULT '2' COMMENT '记录日志:1记录,2不记录',
  `updated_at` int(10) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `listorder` (`listorder`) USING BTREE,
  KEY `parentid` (`parentid`) USING BTREE,
  KEY `idx_a_c` (`a`,`c`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

LOCK TABLES `apim_admin_menu` WRITE;
/*!40000 ALTER TABLE `apim_admin_menu` DISABLE KEYS */;

INSERT INTO `apim_admin_menu` (`id`, `name`, `parentid`, `icon`, `m`, `c`, `a`, `data`, `group`, `listorder`, `status`, `write_log`, `updated_at`, `created_at`)
VALUES
	(1,'后台菜单',3,NULL,'admin','adminMenu','index',NULL,'',4,1,2,1554566008,1503299010),
	(2,'日志管理',3,NULL,'admin','adminLog','index',NULL,'',5,1,2,1554566008,1503300454),
	(3,'系统管理',0,'fa-gears','admin','system','index',NULL,'',1000,1,2,1554566008,1503300505),
	(6,'菜单查看',1,NULL,'admin','adminMenu','info',NULL,'',999,2,2,1554566008,1503303676),
	(7,'菜单添加',1,NULL,'admin','adminMenu','add',NULL,'',999,2,1,1554566008,1503303742),
	(8,'菜单修改',1,NULL,'admin','adminMenu','edit',NULL,'',999,2,1,1554566008,1503303780),
	(19,'用户管理',0,'fa-users','admin','adminUser','index',NULL,'',10,2,2,1595944426,1503305413),
	(21,'角色管理',3,'fa-check-square-o','admin','adminGroup','index',NULL,'',11,1,2,1595944413,1503305466),
	(28,'日志详情',2,NULL,'admin','adminLog','info',NULL,'',999,2,2,1554566008,1503561164),
	(29,'角色详情',21,NULL,'admin','adminGroup','info',NULL,'',999,2,2,1554566008,1503655888),
	(30,'角色添加',21,NULL,'admin','adminGroup','add',NULL,'',999,2,1,1554566008,0),
	(31,'角色修改',21,NULL,'admin','adminGroup','edit',NULL,'',999,2,1,1554566008,0),
	(33,'用户详情',19,'','admin','adminUser','info','','',999,2,2,1554566008,0),
	(34,'用户添加',19,'','admin','adminUser','add','','',999,2,1,1554566008,0),
	(35,'用户修改',19,'','admin','adminUser','edit','','',999,2,1,1554566008,0),
	(36,'修改用户密码',19,'','admin','adminUser','changePwd','','',999,2,1,1554566008,0),
	(58,'菜单排序',1,NULL,'admin','adminMenu','setListorder',NULL,'',999,2,2,1554566008,1503657729),
	(66,'用户删除',19,NULL,'admin','adminUser','del',NULL,'',999,2,1,1554566008,1504605933),
	(79,'菜单删除',1,NULL,'admin','adminMenu','del',NULL,'',999,2,1,1554566008,1504998588),
	(122,'站点管理',3,NULL,'admin','site','info',NULL,'',10,1,2,1554566009,1506336604),
	(123,'站点详情',122,'','admin','site','info',NULL,'',999,2,2,1554566009,1506336604),
	(125,'站点修改',122,'','admin','site','edit',NULL,'',999,2,1,1554566009,1506336604),
	(193,'数据统计',0,'fa-bar-chart-o','admin','apiUrl','index',NULL,'',1,1,2,1554566007,1517073674),
	(197,'定时任务',0,'fa-fire','admin','crontab','index',NULL,'',9,1,2,1595944363,1518159827),
	(198,'定时任务详情',197,NULL,'admin','crontab','info',NULL,'',999,2,2,1554566008,1518159827),
	(199,'定时任务添加',197,'','admin','crontab','add',NULL,'',999,2,1,1554566008,1518159827),
	(200,'定时任务修改',197,'','admin','crontab','edit',NULL,'',999,2,1,1554566008,1518159827),
	(201,'定时任务删除',197,'','admin','crontab','del',NULL,'',999,2,1,1554566008,1518159827),
	(213,'计划任务禁用',197,NULL,'admin','crontab','status',NULL,'',999,2,1,1554566008,1520172047),
	(240,'工作管理',0,'fa-inbox','admin','workInfo','index',NULL,'',5,2,2,1596022436,1529926496),
	(241,'工作详情',240,'','admin','workInfo','info',NULL,'',999,2,2,1554566008,1529926496),
	(242,'工作添加',240,'','admin','workInfo','add',NULL,'',999,2,2,1554566008,1529926496),
	(243,'工作修改',240,'','admin','workInfo','edit',NULL,'',999,2,2,1554566008,1529926496),
	(244,'工作删除',240,'','admin','workInfo','del',NULL,'',999,2,2,1554566008,1529926496),
	(245,'项目管理',0,'fa-globe','admin','apiDomain','index',NULL,'',2,1,2,1554566007,1529927173),
	(246,'项目详情',245,'','admin','apiDomain','info',NULL,'',999,2,2,1554566007,1529927173),
	(247,'项目添加',245,'','admin','apiDomain','add',NULL,'',999,2,2,1554566007,1529927173),
	(248,'项目修改',245,NULL,'admin','apiDomain','edit',NULL,'',999,2,2,1554566007,1529927173),
	(249,'项目删除',245,'','admin','apiDomain','del',NULL,'',999,2,2,1554566007,1529927173),
	(250,'API接口管理',0,'fa-bar-chart-o','admin','apiUrl','index',NULL,'',1,2,2,1554566007,1529979234),
	(251,'API接口详情',250,'','admin','apiUrl','info',NULL,'',999,2,2,1554566007,1529979234),
	(252,'API接口添加',250,'','admin','apiUrl','add',NULL,'',999,2,2,1554566007,1529979234),
	(253,'API接口修改',250,'','admin','apiUrl','edit',NULL,'',999,2,2,1554566007,1529979234),
	(254,'API接口删除',250,'','admin','apiUrl','del',NULL,'',999,2,2,1554566007,1529979234),
	(255,'API响应时长',0,'fa-exchange','admin','apiResponseTime','index',NULL,'',2,2,2,1595385858,1529980552),
	(257,'10分钟响应TOP20',255,NULL,'admin','apiResponseTime','info',NULL,'',999,1,2,1554566007,1529980552),
	(260,'分配项目信息',19,NULL,'admin','adminUser','domainAccount',NULL,'',999,2,2,1554566008,1530441069),
	(261,'异步获取url地址',250,NULL,'admin','apiUrl','bachApiUrl',NULL,'',999,2,2,1554566007,1531051533),
	(262,'状态',245,NULL,'admin','apiDomain','status',NULL,'',999,2,2,1554566007,1531053940),
	(263,'异步获取响应数据',250,NULL,'admin','apiUrl','asyncResponseTime',NULL,'',999,2,2,1554566007,1531060287),
	(264,'状态',250,NULL,'admin','apiUrl','status',NULL,'',999,2,2,1554566007,1531061709),
	(266,'模块管理',0,'fa-folder-o','admin','apiModule','index',NULL,'',3,1,2,1554566007,1531125178),
	(267,'详情',266,'','admin','apiModule','info',NULL,'',999,2,2,1554566007,1531125178),
	(268,'模块管理添加',266,'','admin','apiModule','add',NULL,'',999,2,2,1554566007,1531125178),
	(269,'修改',266,'','admin','apiModule','edit',NULL,'',999,2,2,1554566007,1531125179),
	(270,'删除',266,'','admin','apiModule','del',NULL,'',999,2,2,1554566007,1531125179),
	(272,'分配项目详情',19,NULL,'admin','adminUser','userApiDomainInfo',NULL,'',999,2,2,1554566008,1531364637),
	(273,'分配项目修改',19,NULL,'admin','adminUser','userApiDomainEdit',NULL,'',999,2,1,1554566008,1531364751),
	(274,'Dashboard',0,'fa-home','admin','adminHome','publicIndex',NULL,'',0,1,2,1554566007,1531572616),
	(275,'用户禁用',19,NULL,'admin','adminUser','status',NULL,'',999,2,1,1554566008,1532337278),
	(276,'进程管理',3,NULL,'admin','pcntl','index',NULL,'',999,2,2,1605517878,1545125110),
	(277,'进程管理删除',276,NULL,'admin','pcntl','del',NULL,'',999,2,2,1554566009,1545385007),
	(278,'运行日志',3,NULL,'admin','systemLog','index',NULL,'',999,1,2,1595305121,1548295645),
	(279,'详情',278,'','admin','systemLog','info',NULL,'',999,2,2,1554566009,1548295645),
	(280,'添加',278,'','admin','systemLog','add',NULL,'',999,2,2,1554566009,1548295645),
	(281,'修改',278,'','admin','systemLog','edit',NULL,'',999,2,2,1554566009,1548295645),
	(282,'删除',278,'','admin','systemLog','del',NULL,'',999,2,2,1554566009,1548295645),
	(283,'报警设置',0,'fa-bell','admin','adminHome','publicAlert',NULL,'',1,1,1,1596026930,1554565990),
	(285,'cron',3,NULL,'admin','crontab','statement',NULL,'',999,2,2,1554777783,1554777783),
	(286,'批量删除',250,NULL,'admin','apiUrl','batchDel',NULL,'',999,2,2,1596008211,1596008211);

/*!40000 ALTER TABLE `apim_admin_menu` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table apim_admin_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `apim_admin_user`;

CREATE TABLE `apim_admin_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `mobile` varchar(11) COLLATE utf8_unicode_ci DEFAULT '',
  `password` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '密码',
  `remember_token` varchar(36) COLLATE utf8_unicode_ci DEFAULT '',
  `email` varchar(125) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '邮箱',
  `realname` varchar(50) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '真实姓名',
  `level` tinyint(1) DEFAULT '1' COMMENT '1,普通,2经理',
  `status` tinyint(1) DEFAULT '1' COMMENT '1正常,2禁用',
  `type` tinyint(2) DEFAULT '1' COMMENT '1:外部账号,2域账号',
  `setting` text COLLATE utf8_unicode_ci COMMENT '其它设置',
  `is_super` tinyint(1) DEFAULT '0' COMMENT '超级管理员,直接拥有所有权限',
  `bind_weinxin_code` int(11) DEFAULT '0',
  `weixin_openid` varchar(64) COLLATE utf8_unicode_ci DEFAULT '',
  `headimgurl` varchar(255) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '微信远程头像',
  `deptname` varchar(255) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '部门名称',
  `admin_id` int(11) DEFAULT '0',
  `created_at` int(10) NOT NULL COMMENT '创建时间',
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_name` (`name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT COMMENT='后台管理员';

LOCK TABLES `apim_admin_user` WRITE;
/*!40000 ALTER TABLE `apim_admin_user` DISABLE KEYS */;

INSERT INTO `apim_admin_user` (`id`, `name`, `mobile`, `password`, `remember_token`, `email`, `realname`, `level`, `status`, `type`, `setting`, `is_super`, `bind_weinxin_code`, `weixin_openid`, `headimgurl`, `deptname`, `admin_id`, `created_at`, `updated_at`)
VALUES
	(1,'admin',NULL,'$2y$10$m6XJmQ4WEj8CdCJME4uPceGVjCLmD8er1fl0Ky4ofuADp4RUhKMAe','','duzhenxun@xin.com','系统',1,1,1,'',1,0,'','http://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83eprnaibZuUicyAiaILkeKcfIe9f6vGDPcmFgZ9hlybw6QiawiaMvd1AhicibeMBHXWhqGS3w8ftOj0FWO80g/132','',2,0,1546511353),
	(2,'duzhenxun','18888873646','$2y$10$m6XJmQ4WEj8CdCJME4uPceGVjCLmD8er1fl0Ky4ofuADp4RUhKMAe','','5552123@qq.com','杜振训',1,1,1,'{\"alert_overtime\":\"email\",\"alert_error_code\":\"email\"}',1,0,'o7Vaw1Cry3PvKx6vKhgAm0HllkL4','http://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83eprnaibZuUicyAiaILkeKcfIe9f6vGDPcmFgZ9hlybw6QiawiaMvd1AhicibeMBHXWhqGS3w8ftOj0FWO80g/132','',2,1505587984,1595821381);

/*!40000 ALTER TABLE `apim_admin_user` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table apim_api_alert
# ------------------------------------------------------------

DROP TABLE IF EXISTS `apim_api_alert`;

CREATE TABLE `apim_api_alert` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `api_domain_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '域名id',
  `api_url_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'apiid',
  `type` tinyint(1) unsigned DEFAULT '1' COMMENT '1时间，2状态码',
  `timestamp` datetime NOT NULL COMMENT '时间',
  `over_total` int(10) NOT NULL DEFAULT '0' COMMENT '超出',
  `total` int(10) DEFAULT '0' COMMENT '数量',
  `max` decimal(6,3) unsigned DEFAULT '0.000' COMMENT '最大时间',
  `status` tinyint(1) DEFAULT '1' COMMENT '1可用,2禁用',
  `code` varchar(255) DEFAULT '0' COMMENT '状态码报警',
  `updated_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `created_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_status` (`status`),
  KEY `idx_api_domain_id` (`api_domain_id`),
  KEY `idx_api_url_id` (`api_url_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='报警接口';



# Dump of table apim_api_domain
# ------------------------------------------------------------

DROP TABLE IF EXISTS `apim_api_domain`;

CREATE TABLE `apim_api_domain` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT '' COMMENT '名称',
  `domain` varchar(100) NOT NULL DEFAULT '' COMMENT '域名',
  `description` text COMMENT '备注',
  `orderlist` tinyint(3) unsigned DEFAULT '0' COMMENT '排序',
  `response_time_alert` smallint(5) DEFAULT '10' COMMENT '响应报警阀值,0不报警',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态:1可用,2不可用',
  `admin_id` tinyint(3) DEFAULT '0' COMMENT '操作人',
  `created_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `sync_at` int(11) DEFAULT '0' COMMENT '同步时间',
  `updated_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `es_index` tinyint(1) DEFAULT '1' COMMENT '索引文件',
  `env_type` tinyint(1) DEFAULT '1' COMMENT '环境',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_domain` (`domain`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='域名';



# Dump of table apim_api_domain_admin_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `apim_api_domain_admin_user`;

CREATE TABLE `apim_api_domain_admin_user` (
  `admin_user_id` int(11) DEFAULT '0' COMMENT '管理员id',
  `api_domain_id` int(11) DEFAULT '0' COMMENT '域名 id',
  KEY `idx_admin_user_id` (`admin_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员可用域名';



# Dump of table apim_api_module
# ------------------------------------------------------------

DROP TABLE IF EXISTS `apim_api_module`;

CREATE TABLE `apim_api_module` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `api_domain_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '域名id',
  `title` varchar(80) DEFAULT '' COMMENT '名称',
  `prefix` varchar(255) NOT NULL DEFAULT '' COMMENT '前缀',
  `description` text COMMENT '备注',
  `orderlist` tinyint(3) unsigned DEFAULT '0' COMMENT '排序',
  `is_delete` tinyint(1) DEFAULT '2' COMMENT '1是,2否',
  `sync_at` int(10) DEFAULT '0',
  `admin_id` tinyint(3) DEFAULT '0' COMMENT '操作人',
  `created_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_domain_id` (`api_domain_id`),
  KEY `idx_prefix` (`prefix`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='api 模块';



# Dump of table apim_api_response_time
# ------------------------------------------------------------

DROP TABLE IF EXISTS `apim_api_response_time`;

CREATE TABLE `apim_api_response_time` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `api_domain_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'api域名',
  `api_url_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'api地址',
  `timestamp` datetime NOT NULL COMMENT '时间段',
  `avg` decimal(6,3) unsigned DEFAULT '0.000' COMMENT '平均时间',
  `min` decimal(6,3) unsigned DEFAULT '0.000' COMMENT '最小时间',
  `max` decimal(6,3) unsigned DEFAULT '0.000' COMMENT '最大时间',
  `total` int(10) DEFAULT '0' COMMENT '数量',
  `total_1` int(11) DEFAULT '0' COMMENT '0-1秒 数量',
  `total_2` int(11) DEFAULT '0' COMMENT '1-5 秒 数量',
  `total_3` int(11) DEFAULT '0' COMMENT '5-10秒 数量',
  `total_4` int(11) DEFAULT '0' COMMENT '10秒以上 数量',
  `time_alert_total` int(11) DEFAULT '0' COMMENT '超过阀值次数',
  `code_200` int(10) DEFAULT '0',
  `code_3xx` int(11) DEFAULT '0',
  `code_502` int(11) DEFAULT '0',
  `code_4xx` int(11) DEFAULT '0',
  `code_499` int(11) DEFAULT '0',
  `code_500` int(11) DEFAULT '0',
  `code_504` int(11) DEFAULT '0',
  `code_5xx` int(11) DEFAULT '0',
  `created_at` int(10) DEFAULT '0',
  `updated_at` int(11) DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_timestamp` (`timestamp`) USING BTREE,
  KEY `idx_domain_id` (`api_domain_id`),
  KEY `idx_url_id` (`api_url_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='api 响应时间';



# Dump of table apim_api_url
# ------------------------------------------------------------

DROP TABLE IF EXISTS `apim_api_url`;

CREATE TABLE `apim_api_url` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `api_domain_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '域名id',
  `api_module_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '模块id',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `url` varchar(255) NOT NULL COMMENT '地址',
  `description` text COMMENT '备注',
  `orderlist` tinyint(3) unsigned DEFAULT '0' COMMENT '排序',
  `admin_id` tinyint(3) DEFAULT '0' COMMENT '操作人',
  `response_time_alert` smallint(5) DEFAULT '3' COMMENT '响应报警阀值,0不报警',
  `code_alert` varchar(255) DEFAULT '500' COMMENT '状态码报警',
  `sync_at` int(10) NOT NULL DEFAULT '0' COMMENT '同步时间',
  `time_alert_type` tinyint(1) DEFAULT '2' COMMENT '1数量，2百分比',
  `time_alert_total` int(10) DEFAULT '10' COMMENT '数量或百分比',
  `status` tinyint(1) DEFAULT '1' COMMENT '1可用,2禁用',
  `updated_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `created_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_url` (`url`),
  KEY `idx_status` (`status`),
  KEY `idx_api_domain_id` (`api_domain_id`),
  KEY `idx_api_module_id` (`api_module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='api地址';



# Dump of table apim_crontab
# ------------------------------------------------------------

DROP TABLE IF EXISTS `apim_crontab`;

CREATE TABLE `apim_crontab` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '任务名',
  `code` varchar(100) NOT NULL DEFAULT '' COMMENT '代码',
  `crontab` varchar(100) NOT NULL DEFAULT '' COMMENT '时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态:1可用,2不可用',
  `description` varchar(255) DEFAULT '' COMMENT '备注',
  `admin_id` int(10) NOT NULL DEFAULT '0' COMMENT '管理员id',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='crontab定时任务';

LOCK TABLES `apim_crontab` WRITE;
/*!40000 ALTER TABLE `apim_crontab` DISABLE KEYS */;

INSERT INTO `apim_crontab` (`id`, `name`, `code`, `crontab`, `status`, `description`, `admin_id`, `created_at`, `updated_at`)
VALUES
	(1,'同步域名下url 地址','sync_domain_url','20 18 * * *',2,'抓取域名下所有url 地址',2,1529917283,1554692175),
	(2,'同步 api 响应数据','sync_response_time','*/10 * * * *',1,'同步 api 响应数据',2,1530788986,1596026813),
	(3,'工作提醒','work_reminder','* * * * *',1,'发邮件或微信提醒',2,1531305592,1595821892),
	(4,'默认报警状态码','update_code_alert','* * * * *',2,'定时检测要报警的接口数据',2,1536147347,1554231893);

/*!40000 ALTER TABLE `apim_crontab` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table apim_failed_jobs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `apim_failed_jobs`;

CREATE TABLE `apim_failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table apim_jobs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `apim_jobs`;

CREATE TABLE `apim_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table apim_migrations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `apim_migrations`;

CREATE TABLE `apim_migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table apim_site
# ------------------------------------------------------------

DROP TABLE IF EXISTS `apim_site`;

CREATE TABLE `apim_site` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT '' COMMENT '名称',
  `keywords` varchar(255) DEFAULT '' COMMENT '关键词',
  `description` varchar(255) DEFAULT '' COMMENT '说明',
  `admin_title` varchar(50) DEFAULT '' COMMENT '后台名称',
  `setting` text COMMENT '其它设置',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='站点设置';

LOCK TABLES `apim_site` WRITE;
/*!40000 ALTER TABLE `apim_site` DISABLE KEYS */;

INSERT INTO `apim_site` (`id`, `title`, `keywords`, `description`, `admin_title`, `setting`, `created_at`, `updated_at`)
VALUES
	(1,'api',NULL,'API 监控','小手Api性能监控','{\"get_api_url\":\"apim:middle\",\"get_api_response_time\":\"apim:high\",\"save_api_response_time\":\"apim:high\",\"queue_api_alert\":\"apim:low\",\"queue_weixin\":\"apim:low\",\"queue_email\":\"apim:low\",\"send_log\":\"redis_log\",\"es_request_time_hist\":\"10m\",\"es_first_start_time\":\"-7\",\"es_end_time\":\"60\",\"process_max\":\"5\",\"mail\":\"duzhenxun@126.com\"}',0,1606720871);

/*!40000 ALTER TABLE `apim_site` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table apim_work_info
# ------------------------------------------------------------

DROP TABLE IF EXISTS `apim_work_info`;

CREATE TABLE `apim_work_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` text COMMENT '内容',
  `reminder_status` tinyint(1) DEFAULT '1' COMMENT '是否提醒:1是,2否',
  `admin_id` tinyint(3) DEFAULT '0',
  `is_reminder` tinyint(1) DEFAULT '1' COMMENT '1未提醒,2已提醒',
  `reminder_at` int(11) DEFAULT '0' COMMENT '提醒日期',
  `is_delete` tinyint(1) DEFAULT '0',
  `created_at` int(10) unsigned NOT NULL DEFAULT '0',
  `updated_at` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='工作提醒';

LOCK TABLES `apim_work_info` WRITE;
/*!40000 ALTER TABLE `apim_work_info` DISABLE KEYS */;

INSERT INTO `apim_work_info` (`id`, `content`, `reminder_status`, `admin_id`, `is_reminder`, `reminder_at`, `is_delete`, `created_at`, `updated_at`)
VALUES
	(1,'周一早会',1,2,2,1531562915,1,1529926576,1533026453),
	(2,'提醒时间提醒时间提醒时间提醒时间提醒时间提醒时间提醒时间提醒时间提醒时间提醒时间提醒时间提醒时间',1,2,2,1537252260,0,1537252144,1595821985);

/*!40000 ALTER TABLE `apim_work_info` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
