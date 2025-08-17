/*
 Navicat Premium Data Transfer

 Source Server         : bpmpd
 Source Server Type    : MySQL
 Source Server Version : 100432 (10.4.32-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : bpmpd

 Target Server Type    : MySQL
 Target Server Version : 100432 (10.4.32-MariaDB)
 File Encoding         : 65001

 Date: 17/08/2025 14:34:50
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for user_admin
-- ----------------------------
DROP TABLE IF EXISTS `user_admin`;
CREATE TABLE `user_admin`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('admin','sa') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role_id` int NOT NULL,
  `is_active` tinyint(1) NULL DEFAULT 1,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_by` int NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT current_timestamp,
  `updated_at` datetime NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `username`(`username` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 307 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user_admin
-- ----------------------------
INSERT INTO `user_admin` VALUES (305, 'hasbi', '$2y$10$tPbnMsvgMlkaiyP/CTJ0SueeuGCHc8XDfAifUxwJB1SRGfwgreM.y', 'sa', 99, 1, 'hasbisimaulansyah@gmail.com', 0, '2025-08-13 16:43:23', '2025-08-13 23:46:23');
INSERT INTO `user_admin` VALUES (306, 'admin', '$2y$10$xJOyWh8qI0f5xkGodP5SB.X/DvP1vQVlU.QzeNMGN8xP1Qt2JJeUC', 'admin', 98, 1, 'admindpmd@gmail.com', 0, '2025-08-15 02:04:11', '2025-08-15 02:04:11');

SET FOREIGN_KEY_CHECKS = 1;
