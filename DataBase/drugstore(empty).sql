/*
 Navicat Premium Data Transfer

 Source Server         : ScarZ
 Source Server Type    : MySQL
 Source Server Version : 100113
 Source Host           : localhost:3306
 Source Schema         : drugstore

 Target Server Type    : MySQL
 Target Server Version : 100113
 File Encoding         : 65001

 Date: 10/10/2018 14:16:50
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for drug_brand
-- ----------------------------
DROP TABLE IF EXISTS `drug_brand`;
CREATE TABLE `drug_brand`  (
  `db_id` int(4) NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `mash_pri` int(2) NOT NULL,
  `mash_sec` int(2) DEFAULT NULL,
  `mash_th` int(2) DEFAULT NULL,
  `drug_kind` int(1) NOT NULL,
  `max` int(7) NOT NULL DEFAULT 0,
  `min` int(2) NOT NULL DEFAULT 0,
  `receive` int(10) NOT NULL DEFAULT 0 COMMENT 'จำนวนนำเข้าสะสม',
  `sell` int(10) NOT NULL DEFAULT 0 COMMENT 'จำนวนขายสะสม',
  PRIMARY KEY (`db_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for drug_kind
-- ----------------------------
DROP TABLE IF EXISTS `drug_kind`;
CREATE TABLE `drug_kind`  (
  `dk_id` int(4) NOT NULL AUTO_INCREMENT,
  `dk_type` int(1) NOT NULL COMMENT '1.ใช้ภายนอก\r\n2.ใช้ภายใน',
  `dk_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`dk_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of drug_kind
-- ----------------------------
INSERT INTO `drug_kind` VALUES (1, 1, 'อุปกรณ์ปฐมพยาบาล');
INSERT INTO `drug_kind` VALUES (2, 1, 'ยาใช้ภายนอก');
INSERT INTO `drug_kind` VALUES (3, 2, 'ยาน้ำ');
INSERT INTO `drug_kind` VALUES (4, 2, 'ยาเม็ด');

-- ----------------------------
-- Table structure for lot
-- ----------------------------
DROP TABLE IF EXISTS `lot`;
CREATE TABLE `lot`  (
  `lot_id` int(7) NOT NULL AUTO_INCREMENT,
  `comp_id` int(3) NOT NULL,
  `lot_date` date NOT NULL,
  `lot_price` decimal(10, 2) NOT NULL,
  `lot_amount` int(4) NOT NULL COMMENT 'จำนวนรายการนำเข้า',
  `importer` int(7) NOT NULL,
  PRIMARY KEY (`lot_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for lot_item
-- ----------------------------
DROP TABLE IF EXISTS `lot_item`;
CREATE TABLE `lot_item`  (
  `li_id` int(7) NOT NULL AUTO_INCREMENT,
  `lot_id` int(7) NOT NULL,
  `db_id` int(7) NOT NULL,
  `item_price` decimal(10, 2) NOT NULL,
  `item_amount` int(4) NOT NULL,
  `barcode` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `expire_date` date DEFAULT NULL,
  `total_now` int(4) NOT NULL,
  PRIMARY KEY (`li_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for medicinal
-- ----------------------------
DROP TABLE IF EXISTS `medicinal`;
CREATE TABLE `medicinal`  (
  `med_id` int(4) NOT NULL AUTO_INCREMENT,
  `med_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`med_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of medicinal
-- ----------------------------
INSERT INTO `medicinal` VALUES (1, 'Paracetamol (พาราเซตามอล)');
INSERT INTO `medicinal` VALUES (2, 'Chlorpheniramine: C.P.M. (คลอเฟนิรามีน)');

-- ----------------------------
-- Table structure for seller
-- ----------------------------
DROP TABLE IF EXISTS `seller`;
CREATE TABLE `seller`  (
  `comp_id` int(7) NOT NULL AUTO_INCREMENT,
  `comp_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `comp_vax` varchar(13) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `comp_address` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `comp_tell` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `comp_mobile` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `comp_fax` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`comp_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_fname` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `user_lname` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `user_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_account` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `user_pwd` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `status_user` varchar(1) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'N',
  `date_login` date DEFAULT NULL,
  `time_login` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `photo` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  PRIMARY KEY (`user_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES (1, 'ฐาปนพงศ์', 'ดีอุดมจันทร์', 'scarz', 'b9a4ffe8f7aacdac3e8bfcf24bb8ba4f', '048b6ae1d417351c46d74b7b1244ecdc', 'Y', '0000-00-00', '', 'USimage26092018225748.png');
INSERT INTO `user` VALUES (2, 'ณัฏฐณิชา', 'หิริศักดิ์สกุล', 'noize', 'dee3f0b9258d6dd4e0c03a1af419a831', '7f8d3ae4b028fde7cb59cdb604a25aef', 'Y', '2018-09-27', '1538021894', 'USimage26092018225931.jpg');

SET FOREIGN_KEY_CHECKS = 1;
