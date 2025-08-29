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

 Date: 29/08/2025 11:12:22
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for document_submissions
-- ----------------------------
DROP TABLE IF EXISTS `document_submissions`;
CREATE TABLE `document_submissions`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `template_id` int NOT NULL,
  `desa_id` int NOT NULL,
  `earmarked` decimal(15, 0) NULL DEFAULT NULL,
  `non_earmarked` decimal(15, 0) NULL DEFAULT NULL,
  `status_desa` enum('draft','submitted','resubmitted','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'submitted',
  `status_kecamatan` enum('pending','approved','rejected','in_progress','submitted','resubmitted') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'pending',
  `status_kabupaten` enum('pending','approved','rejected','layak_salur','submitted') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'pending',
  `keterangan_kecamatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `keterangan_kabupaten` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `created_at` datetime NULL DEFAULT current_timestamp,
  `updated_at` datetime NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  `status_desa_at` datetime NULL DEFAULT NULL,
  `status_kecamatan_at` datetime NULL DEFAULT NULL,
  `status_kabupaten_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uniq_template_desa`(`template_id` ASC, `desa_id` ASC) USING BTREE,
  INDEX `fk_sub_desa`(`desa_id` ASC) USING BTREE,
  CONSTRAINT `fk_sub_desa` FOREIGN KEY (`desa_id`) REFERENCES `desa` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_sub_template` FOREIGN KEY (`template_id`) REFERENCES `document_templates` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 25 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
