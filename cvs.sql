-- AstonCV Database Setup
-- Run this file in phpMyAdmin or MySQL to set up the database

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;

-- Create the database
CREATE DATABASE IF NOT EXISTS `astoncv`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;
USE `astoncv`;

-- Drop table if it already exists (for fresh setup)
DROP TABLE IF EXISTS `cvs`;

-- Create the cvs table (using the provided structure)
CREATE TABLE `cvs` (
  `id`             bigint(20) UNSIGNED NOT NULL,
  `name`           varchar(100)  COLLATE utf8mb4_unicode_ci NOT NULL,
  `email`          varchar(100)  COLLATE utf8mb4_unicode_ci NOT NULL,
  `password`       varchar(255)  COLLATE utf8mb4_unicode_ci NOT NULL,
  `keyprogramming` varchar(255)  COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile`        varchar(500)  COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `education`      varchar(500)  COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `URLlinks`       varchar(500)  COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Indexes
ALTER TABLE `cvs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY (`email`);

-- Auto-increment
ALTER TABLE `cvs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

-- -------------------------------------------------------
-- Sample data for testing
-- All passwords below are:  Password1!
-- (hashed with bcrypt via PHP password_hash())
-- -------------------------------------------------------
INSERT INTO `cvs` (`name`, `email`, `password`, `keyprogramming`, `profile`, `education`, `URLlinks`) VALUES

('Alice Johnson',
 'alice@example.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 'Python',
 'Passionate Python developer with 3 years of experience building data pipelines and web APIs. I enjoy solving complex problems and contributing to open-source projects.',
 'BSc Computer Science, Aston University, 2020-2023. A-Levels: Maths (A*), Computing (A), Physics (A).',
 'https://github.com/alicejohnson,https://linkedin.com/in/alicejohnson'),

('Bob Smith',
 'bob@example.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 'JavaScript',
 'Full-stack JavaScript developer specialising in React and Node.js. Love building sleek, responsive UIs and scalable back-end services.',
 'MEng Software Engineering, Aston University, 2019-2024.',
 'https://github.com/bobsmith,https://bobsmith.dev'),

('Carol White',
 'carol@example.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 'Java',
 'Backend Java engineer with a focus on enterprise systems and Spring Boot microservices. Strong advocate for clean code and TDD.',
 'BSc Computer Science, University of Birmingham, 2018-2021. Oracle Java SE 11 Certified.',
 'https://github.com/carolwhite'),

('David Lee',
 'david@example.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 'PHP',
 'PHP and Laravel developer with experience building CMS platforms and e-commerce sites. Also comfortable with Vue.js on the front end.',
 'HND Computing, Birmingham Metropolitan College, 2017-2019.',
 'https://github.com/davidlee,https://linkedin.com/in/davidlee'),

('Emma Davis',
 'emma@example.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 'Python',
 'Data scientist and ML engineer. Experienced with scikit-learn, TensorFlow and Pandas. Currently researching NLP at Aston.',
 'MSc Data Science, Aston University, 2023-2024. BSc Mathematics, University of Warwick, 2020-2023.',
 'https://github.com/emmadavis,https://kaggle.com/emmadavis');

COMMIT;

-- -------------------------------------------------------
-- Test credentials:
--   Email:    alice@example.com  (or any of the above)
--   Password: Password1!
-- -------------------------------------------------------
