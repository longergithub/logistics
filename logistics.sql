/*
 Navicat Premium Data Transfer

 Source Server         : 测试
 Source Server Type    : MySQL
 Source Server Version : 50722
 Source Host           : 119.23.178.210:3306
 Source Schema         : logistics

 Target Server Type    : MySQL
 Target Server Version : 50722
 File Encoding         : 65001

 Date: 27/11/2018 16:36:56
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for wwt_operate_log
-- ----------------------------
DROP TABLE IF EXISTS `wwt_operate_log`;
CREATE TABLE `wwt_operate_log`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '操作ID。',
  `type` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '日志操作类型。',
  `operateName` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '操作者姓名。',
  `operateUsername` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '操作者用户名。',
  `info` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '操作信息。',
  `operateIp` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '操作IP。',
  `operateTime` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '操作时间。',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for wwt_user
-- ----------------------------
DROP TABLE IF EXISTS `wwt_user`;
CREATE TABLE `wwt_user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID。',
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '姓名。',
  `username` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户名。',
  `password` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '密码。',
  `isAdmin` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否是管理员。',
  `isLock` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否锁定。',
  `loginCount` int(11) NOT NULL DEFAULT 0 COMMENT '登录次数。',
  `lastLoginTime` int(10) NULL DEFAULT NULL COMMENT '最近登录时间。',
  `lastLoginIP` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '最近登录IP。',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for wwt_vehicle
-- ----------------------------
DROP TABLE IF EXISTS `wwt_vehicle`;
CREATE TABLE `wwt_vehicle`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '车辆ID。',
  `plateNo` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '车牌号。',
  `driver` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '司机。',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注。',
  `createdTime` int(10) NOT NULL COMMENT '创建时间。',
  `isDisabled` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否已禁用。',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for wwt_vehicle_record
-- ----------------------------
DROP TABLE IF EXISTS `wwt_vehicle_record`;
CREATE TABLE `wwt_vehicle_record`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '记录ID。',
  `vehicleId` int(11) NOT NULL COMMENT '车辆Id。',
  `quantity` int(11) NOT NULL COMMENT '数量。',
  `caseNo` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '箱号。',
  `startLocation` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '起点。',
  `endLocation` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '终点。',
  `goodsName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '货物名称。',
  `etcAmount` decimal(10, 2) NULL DEFAULT NULL COMMENT 'ETC过路费。',
  `roadAmount` decimal(10, 2) NULL DEFAULT NULL COMMENT '现金过路费。',
  `refuelAmount` decimal(10, 2) NULL DEFAULT NULL COMMENT '加油金额。',
  `washAmount` decimal(10, 2) NULL DEFAULT NULL COMMENT '洗车加水金额。',
  `tailGasDealAmount` decimal(10, 2) NULL DEFAULT NULL COMMENT '尿素金额。',
  `policePenaltyAmount` decimal(10, 2) NULL DEFAULT NULL COMMENT '交警罚款。',
  `roadPenaltyAmount` decimal(10, 2) NULL DEFAULT NULL COMMENT '路政罚款。',
  `fixedAmount` decimal(10, 2) NULL DEFAULT NULL COMMENT '维修金额。',
  `fixedFactoryName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '维修厂名称。',
  `fixedContent` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '维修项目。',
  `advanceAmount` decimal(10, 2) NULL DEFAULT NULL COMMENT '预支金额。',
  `etcRechargeAmount` decimal(10, 2) NULL DEFAULT NULL COMMENT 'ETC充值金额。',
  `turnoverAmount` decimal(10, 2) NULL DEFAULT NULL COMMENT '营业额。',
  `daySalary` decimal(10, 2) NULL DEFAULT NULL COMMENT '计件工资。',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注。',
  `creatorId` int(11) NOT NULL COMMENT '录入人。',
  `createdTime` int(10) NOT NULL COMMENT '创建时间。',
  `isDeleted` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否已删除。',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
