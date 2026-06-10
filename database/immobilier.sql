CREATE DATABASE IF NOT EXISTS immobilier_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE immobilier_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    avatar VARCHAR(255) DEFAULT NULL,
    role ENUM('client', 'bailleur', 'agent', 'manager') NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE properties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    owner_id INT NOT NULL,
    titre VARCHAR(255) NOT NULL,
    type ENUM('terrain', 'villa', 'appartement', 'commerce', 'immeuble') NOT NULL,
    usage_type ENUM('residence', 'bureau', 'commerce', 'agriculture') NOT NULL,
    option_type ENUM('location', 'vente') NOT NULL,
    superficie DECIMAL(10,2) NOT NULL,
    prix DECIMAL(15,2) NOT NULL,
    ville VARCHAR(150) NOT NULL,
    adresse TEXT NOT NULL,
    latitude DECIMAL(10,8) DEFAULT NULL,
    longitude DECIMAL(11,8) DEFAULT NULL,
    description TEXT NOT NULL,
    source ENUM('owner', 'agency') DEFAULT 'owner',
    status ENUM('pending', 'published', 'refused', 'retired') DEFAULT 'pending',
    bedrooms INT DEFAULT 0,
    bathrooms INT DEFAULT 0,
    garage INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE property_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    property_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE
);

CREATE TABLE favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    property_id INT NOT NULL,
    UNIQUE KEY unique_favorite (client_id, property_id),
    FOREIGN KEY (client_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE
);

CREATE TABLE client_agent (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    agent_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (agent_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE visit_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    property_id INT NOT NULL,
    client_id INT NOT NULL,
    agent_id INT DEFAULT NULL,
    visit_date DATE DEFAULT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    FOREIGN KEY (client_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (agent_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Comptes professionnels (déclarés en base, connexion via /connexion.php)
-- Manager : manager@immofaso.bf / admin123
-- Agent   : agent@immofaso.bf / agent123
INSERT INTO users (nom, prenom, email, telephone, password, role) VALUES
('Admin', 'Manager', 'manager@immofaso.bf', '70000000',
 '$2y$12$MylTCOgm69bB8nXn8jiAbeuxu8V0UTZFNQ/AeAUnhZ7IH9yv2o03u', 'manager'),
('Kaboré', 'Agent', 'agent@immofaso.bf', '70111111',
 '$2y$12$Sp6A.pl3ZpqD/Zqv3FiY.OOjqLgnga2glHA7zJi/Mc3fEiWpF7GoS', 'agent'),
('Ouédraogo', 'Moussa', 'bailleur@immofaso.bf', '70222222',
 '$2y$12$dWyCpANwzTuL5aTWulCGpeM7L.Qy5eJZKOFsQaIEJiCEbQaG1QkoO', 'bailleur');

SET @bailleur_id = (SELECT id FROM users WHERE email = 'bailleur@immofaso.bf' LIMIT 1);

INSERT INTO properties (owner_id, titre, type, usage_type, option_type, superficie, prix, ville, adresse, description, status, bedrooms, bathrooms) VALUES
(@bailleur_id, 'Villa moderne Ouaga 2000', 'villa', 'residence', 'vente', 350.00, 85000000, 'Ouagadougou', 'Ouaga 2000, secteur 15', 'Villa R+1 climatisée, jardin, garage 2 voitures.', 'published', 4, 3),
(@bailleur_id, 'Appartement à louer Koulouba', 'appartement', 'residence', 'location', 95.00, 250000, 'Ouagadougou', 'Koulouba', 'F3 standing, cuisine équipée, balcon.', 'published', 3, 2),
(@bailleur_id, 'Terrain viabilisé Karpala', 'terrain', 'residence', 'vente', 500.00, 15000000, 'Ouagadougou', 'Karpala', 'Terrain clôturé, titre foncier.', 'published', 0, 0),
(@bailleur_id, 'Maison à louer Gounghin', 'villa', 'residence', 'location', 120.00, 180000, 'Ouagadougou', 'Gounghin', 'Maison 3 chambres, cour.', 'published', 3, 1),
(@bailleur_id, 'Local commercial Zone 1', 'commerce', 'commerce', 'location', 80.00, 350000, 'Ouagadougou', 'Zone 1', 'Boutique vitrine, fort passage.', 'published', 0, 1),
(@bailleur_id, 'Immeuble R+3 à vendre', 'immeuble', 'residence', 'vente', 600.00, 120000000, 'Bobo-Dioulasso', 'Sarfalao', 'Immeuble 6 appartements.', 'published', 12, 8),
(@bailleur_id, 'Appartement standing Bonheur', 'appartement', 'residence', 'location', 110.00, 320000, 'Ouagadougou', 'Bonheur Ville', 'F4 luxe, ascenseur, parking.', 'published', 4, 2),
(@bailleur_id, 'Villa à louer Pissy', 'villa', 'residence', 'location', 200.00, 450000, 'Ouagadougou', 'Pissy', 'Grande villa 5 chambres, piscine.', 'published', 5, 3),
(@bailleur_id, 'Terrain agricole Bagré', 'terrain', 'agriculture', 'vente', 2000.00, 8000000, 'Baga', 'Périmètre Bagré', 'Terrain fertile, accès route.', 'published', 0, 0),
(@bailleur_id, 'Bureau à louer centre-ville', 'appartement', 'bureau', 'location', 65.00, 200000, 'Ouagadougou', 'Centre-ville', 'Open space, fibre optique.', 'published', 0, 1);
