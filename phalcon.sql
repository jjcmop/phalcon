/*
 Navicat Premium Data Transfer

 Source Server         : shop
 Source Server Type    : MySQL
 Source Server Version : 50722
 Source Host           : 47.104.109.151:3306
 Source Schema         : phalcon

 Target Server Type    : MySQL
 Target Server Version : 50722
 File Encoding         : 65001

 Date: 22/01/2019 17:29:33
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for zs_area
-- ----------------------------
DROP TABLE IF EXISTS `zs_area`;
CREATE TABLE `zs_area`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `areaID` varchar(6) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `area` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `father` varchar(6) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3169 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zs_banner
-- ----------------------------
DROP TABLE IF EXISTS `zs_banner`;
CREATE TABLE `zs_banner`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pic` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zs_card
-- ----------------------------
DROP TABLE IF EXISTS `zs_card`;
CREATE TABLE `zs_card`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `u_id` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `t_id` int(11) UNSIGNED NOT NULL COMMENT '分类ID',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT '帖子内容',
  `click` int(11) UNSIGNED DEFAULT 0 COMMENT '点赞量',
  `create_time` int(11) UNSIGNED DEFAULT NULL COMMENT '发帖时间',
  `lbs` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '定位',
  `card_pic` json COMMENT '图片地址',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 548 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zs_card_click
-- ----------------------------
DROP TABLE IF EXISTS `zs_card_click`;
CREATE TABLE `zs_card_click`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `u_id` int(11) UNSIGNED DEFAULT NULL,
  `c_id` int(11) UNSIGNED DEFAULT NULL,
  `status` tinyint(2) UNSIGNED DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 516 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zs_card_feedback
-- ----------------------------
DROP TABLE IF EXISTS `zs_card_feedback`;
CREATE TABLE `zs_card_feedback`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `u_id` int(11) UNSIGNED NOT NULL COMMENT '用户id',
  `c_id` int(11) UNSIGNED NOT NULL COMMENT '贴子id',
  `body` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '评论内容',
  `feedtime` int(11) UNSIGNED DEFAULT NULL COMMENT '评论时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 422 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zs_card_type
-- ----------------------------
DROP TABLE IF EXISTS `zs_card_type`;
CREATE TABLE `zs_card_type`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `pic` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zs_city
-- ----------------------------
DROP TABLE IF EXISTS `zs_city`;
CREATE TABLE `zs_city`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cityID` varchar(6) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `city` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `father` varchar(6) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 352 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zs_collection
-- ----------------------------
DROP TABLE IF EXISTS `zs_collection`;
CREATE TABLE `zs_collection`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `u_id` int(11) UNSIGNED DEFAULT NULL COMMENT '用户ID',
  `n_id` int(11) UNSIGNED DEFAULT NULL COMMENT '文章ID',
  `n_title` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '文章标题',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 121 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zs_feedback
-- ----------------------------
DROP TABLE IF EXISTS `zs_feedback`;
CREATE TABLE `zs_feedback`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `u_id` int(11) UNSIGNED NOT NULL COMMENT '用户id',
  `n_id` int(11) UNSIGNED NOT NULL COMMENT '文章id',
  `body` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '评论内容',
  `feedtime` int(11) UNSIGNED DEFAULT NULL COMMENT '评论时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 457 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zs_filter
-- ----------------------------
DROP TABLE IF EXISTS `zs_filter`;
CREATE TABLE `zs_filter`  (
  `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `word` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5042 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zs_frined
-- ----------------------------
DROP TABLE IF EXISTS `zs_frined`;
CREATE TABLE `zs_frined`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `frined_id` int(5) UNSIGNED DEFAULT NULL COMMENT '好友ID',
  `me_id` int(5) UNSIGNED DEFAULT NULL COMMENT '我的ID',
  `ts` int(11) UNSIGNED DEFAULT NULL COMMENT '添加时间',
  `frined_account` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '好友账号名称',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zs_news
-- ----------------------------
DROP TABLE IF EXISTS `zs_news`;
CREATE TABLE `zs_news`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '文章id',
  `title` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '文章标题',
  `author` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '作者',
  `create_time` int(11) UNSIGNED DEFAULT NULL COMMENT '时间',
  `click` int(10) UNSIGNED DEFAULT NULL COMMENT '点赞量',
  `pic` json COMMENT '展示图片',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '文章内容',
  `type` int(2) UNSIGNED DEFAULT NULL COMMENT '类型',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 46 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '文章表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zs_pet_breed
-- ----------------------------
DROP TABLE IF EXISTS `zs_pet_breed`;
CREATE TABLE `zs_pet_breed`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pet_breed` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `p_id` tinyint(2) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 190 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zs_province
-- ----------------------------
DROP TABLE IF EXISTS `zs_province`;
CREATE TABLE `zs_province`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provinceID` varchar(6) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `province` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 35 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zs_setting
-- ----------------------------
DROP TABLE IF EXISTS `zs_setting`;
CREATE TABLE `zs_setting`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `version_number` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '版本号',
  `version_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '版本名字',
  `update_content` text CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '更新内容',
  `is_forced_update` enum('1','0') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '0' COMMENT '是否强制更新',
  `update_address` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '更新地址',
  `package_size` double(11, 4) UNSIGNED DEFAULT NULL COMMENT 'APP大小',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zs_short_message
-- ----------------------------
DROP TABLE IF EXISTS `zs_short_message`;
CREATE TABLE `zs_short_message`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '电话号码',
  `code` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '验证码',
  `send_time` int(11) NOT NULL COMMENT '发送时间',
  `smsId` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '发送短信唯一标识',
  `create_time` int(11) UNSIGNED NOT NULL COMMENT '记录时间',
  `pid` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '渠道id',
  `status` tinyint(2) DEFAULT 0 COMMENT '状态',
  `ratio` int(10) UNSIGNED DEFAULT 0 COMMENT '比率',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8901 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zs_system
-- ----------------------------
DROP TABLE IF EXISTS `zs_system`;
CREATE TABLE `zs_system`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `pass` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `login_time` datetime(0) DEFAULT NULL,
  `log` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '管理员表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zs_tag
-- ----------------------------
DROP TABLE IF EXISTS `zs_tag`;
CREATE TABLE `zs_tag`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tag` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `u_id` int(11) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 498 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zs_user
-- ----------------------------
DROP TABLE IF EXISTS `zs_user`;
CREATE TABLE `zs_user`  (
  `id` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '用户唯一标识',
  `account` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '账号',
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '密码',
  `mobile_num` bigint(11) UNSIGNED DEFAULT NULL COMMENT '注册手机号',
  `sex` enum('男','女','保密') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '保密' COMMENT '性别',
  `nick_name` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '昵称',
  `location_province` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '省',
  `location_city` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '市',
  `location_area` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '区',
  `real_name` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '真实姓名',
  `id_card` varchar(18) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '身份证',
  `user_level` tinyint(3) UNSIGNED DEFAULT NULL COMMENT '用户等级',
  `user_exp` bigint(20) UNSIGNED DEFAULT 0 COMMENT '用户经验',
  `membership_points` int(8) UNSIGNED DEFAULT NULL COMMENT '用户积分',
  `lock_status` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '0' COMMENT '锁定状态(0开启1锁定)',
  `register_time` bigint(13) UNSIGNED DEFAULT NULL COMMENT '注册时间',
  `register_ip` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '登录IP',
  `last_activity_date` bigint(13) UNSIGNED DEFAULT NULL COMMENT '最后活跃日期',
  `is_online` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '1' COMMENT '联网状态(0在线1离线)',
  `avatar` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '用户头像url',
  `access_token` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT 'access_token',
  `competence` enum('普通用户','官方机构','救助站') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '普通用户' COMMENT '用户权限',
  `u_sign` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '个性签名',
  `my_pet` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '我的宠物',
  `pet_breed` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '宠物品种',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 48 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zs_user_bp
-- ----------------------------
DROP TABLE IF EXISTS `zs_user_bp`;
CREATE TABLE `zs_user_bp`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL COMMENT '用户名称',
  `bp` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '加减积分',
  `optime` date DEFAULT NULL COMMENT '操作时间',
  `bp_type` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '操作类型',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 43 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for zs_user_merc
-- ----------------------------
DROP TABLE IF EXISTS `zs_user_merc`;
CREATE TABLE `zs_user_merc`  (
  `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `commercial` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '经营性质',
  `sex` enum('男','女') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '男' COMMENT '性别',
  `real_name` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '姓名',
  `id_card` varchar(18) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '身份证',
  `mobile_num` bigint(11) UNSIGNED DEFAULT NULL COMMENT '手机号',
  `location_province` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '省',
  `location_city` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '市',
  `location_area` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '区',
  `address` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '详细地址',
  `info` text CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '公司描述',
  `idcard` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '身份证正面照',
  `reidcard` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '身份证反面照',
  `handidcard` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '手持身份证照',
  `permit` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '营业执照',
  `field1` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '场地照片图',
  `field2` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '场地照片图',
  `field3` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '场地照片图',
  `field4` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '场地照片图',
  `create_time` int(11) UNSIGNED DEFAULT NULL COMMENT '提交时间',
  `is_commercial` enum('1','0','2') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '2' COMMENT '审核经营性质',
  `is_sex` enum('1','0','2') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '2' COMMENT '审核性别',
  `is_real_name` enum('1','0','2') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '2' COMMENT '审核姓名',
  `is_id_card` enum('1','0','2') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '2' COMMENT '审核身份证',
  `is_mobile_num` enum('1','0','2') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '2' COMMENT '审核手机号',
  `is_location_province` enum('1','0','2') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '2' COMMENT '审核省',
  `is_location_city` enum('1','0','2') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '2' COMMENT '审核市',
  `is_location_area` enum('1','0','2') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '2' COMMENT '审核区',
  `is_address` enum('1','0','2') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '2' COMMENT '审核详细地址',
  `is_info` enum('1','0','2') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '2' COMMENT '审核公司描述',
  `is_idcard` enum('1','0','2') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '2' COMMENT '审核身份证正面照',
  `is_reidcard` enum('1','0','2') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '2' COMMENT '审核身份证反面照',
  `is_handidcard` enum('1','0','2') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '2' COMMENT '审核手持身份证照',
  `is_permit` enum('1','0','2') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '2' COMMENT '审核营业执照',
  `is_field1` enum('1','0','2') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '2' COMMENT '审核场地照片图',
  `is_field2` enum('1','0','2') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '2' COMMENT '审核场地照片图',
  `is_field3` enum('1','0','2') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '2' COMMENT '审核场地照片图',
  `is_field4` enum('1','0','2') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '2' COMMENT '审核场地照片图',
  `status` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '0' COMMENT '审核状态',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 19 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
