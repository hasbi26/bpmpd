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

 Date: 29/08/2025 11:13:01
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for document_submission_files
-- ----------------------------
DROP TABLE IF EXISTS `document_submission_files`;
CREATE TABLE `document_submission_files`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `submission_id` int NOT NULL,
  `template_detail_id` int NOT NULL,
  `uploader_role` enum('desa','kecamatan') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `file_size` int NULL DEFAULT NULL,
  `status_verifikasi` enum('pending','valid','invalid') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'pending',
  `catatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `uploaded_at` datetime NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_files_submission`(`submission_id` ASC) USING BTREE,
  INDEX `fk_files_template_detail`(`template_detail_id` ASC) USING BTREE,
  CONSTRAINT `fk_files_submission` FOREIGN KEY (`submission_id`) REFERENCES `document_submissions` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_files_template_detail` FOREIGN KEY (`template_detail_id`) REFERENCES `document_templates_detail` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 88 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
