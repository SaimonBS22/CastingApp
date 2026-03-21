CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  rol ENUM('admin','user') DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO usuarios (nombre, email, password, rol) VALUES (
  'Admin',
  'admin@casting.com',
  '$2y$10$eImiTXuWVxfM37uY4JANjQ==',
  'admin'
);
