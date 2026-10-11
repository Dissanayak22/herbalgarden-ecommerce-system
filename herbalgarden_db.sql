-- HerbalGarden SQL Dump
-- Run in phpMyAdmin or mysql CLI: source herbalgarden_db.sql;

CREATE DATABASE IF NOT EXISTS `herbalgarden_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `herbalgarden_db`;

CREATE TABLE IF NOT EXISTS `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `description` TEXT,
  `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `image` VARCHAR(255),
  `category` VARCHAR(50),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `address` TEXT NOT NULL,
  `total` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `status` VARCHAR(50) NOT NULL DEFAULT 'Pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `quantity` INT NOT NULL DEFAULT 1,
  `subtotal` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  CONSTRAINT `fk_order` FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

-- Default admin user (username: admin / password: admin123)
-- NOTE: If you re-run this script multiple times, ensure duplicate insert doesn't fail.
INSERT INTO `admin_users` (`username`,`password`)
SELECT 'admin', '$2y$10$zjRbOrDXd1QuHsr1webKCOBEtN/zlDWPdtlYw2Qd08pBFbAJZMWri' FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM admin_users WHERE username='admin');

-- Sample products (optional)
INSERT INTO `products` (`name`,`description`,`price`,`image`,`category`) VALUES
('Aloe Vera Gel','Soothing natural aloe gel for skin hydration.',8.99,NULL,'Skin Care'),
('Chamomile Tea','Relaxing herbal infusion for better sleep.',5.50,NULL,'Tea'),
('Peppermint Oil','Essential oil for aromatherapy and digestion support.',12.00,NULL,'Essential Oils'),
('Lavender Oil','Calming lavender essential oil ideal for relaxation and sleep support.',14.50,NULL,'Essential Oils'),
('Turmeric Powder','Golden turmeric powder rich in curcumin for anti-inflammatory benefits.',6.75,NULL,'Supplements'),
('Ginger Root Dried','Dried ginger root slices perfect for digestion teas and remedies.',4.40,NULL,'Herbs'),
('Echinacea Capsules','Immune support herbal capsules with standardized echinacea extract.',15.99,NULL,'Supplements'),
('Rosemary Leaves','Fragrant dried rosemary leaves for culinary and aromatic use.',3.25,NULL,'Herbs'),
('Green Tea Matcha','Finely ground matcha powder packed with antioxidants.',18.00,NULL,'Tea'),
('Lemon Balm Tea','Calming lemon balm infusion to ease stress and tension.',7.10,NULL,'Tea'),
('Neem Soap Bar','Antibacterial neem herbal soap gentle on sensitive skin.',4.95,NULL,'Skin Care'),
('Shea Butter Balm','Rich unrefined shea butter balm for deep moisturizing.',9.80,NULL,'Skin Care'),
('Moringa Powder','Nutrient dense moringa leaf powder for smoothies and tonics.',11.20,NULL,'Supplements'),
('Hibiscus Petals','Tart and vibrant hibiscus petals ideal for refreshing teas.',5.95,NULL,'Herbs'),
('Ashwagandha Extract','Adaptogenic root extract supporting stress balance and vitality.',16.40,NULL,'Supplements');
