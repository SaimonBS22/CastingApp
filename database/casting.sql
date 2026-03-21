CREATE TABLE talentos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  apellido VARCHAR(100),
  fecha_nacimiento DATE,
  telefono VARCHAR(30),
  ubicacion VARCHAR(100),
  genero VARCHAR(20),

  altura INT,
  peso INT,
  color_pelo VARCHAR(50),
  color_ojos VARCHAR(50),
  tez VARCHAR(20),
  talle_ropa VARCHAR(10),
  talle_calzado INT,

  experiencia VARCHAR(50),
  observaciones TEXT,

  perfil_completo TINYINT DEFAULT 0,

  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE habilidades (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50)
);

INSERT INTO habilidades (nombre) VALUES
('Actuación'),
('Baile'),
('Deportes'),
('Idiomas'),
('Canto');


CREATE TABLE usuario_habilidad (
  usuario_id INT,
  habilidad_id INT,
  PRIMARY KEY (usuario_id, habilidad_id),
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  FOREIGN KEY (habilidad_id) REFERENCES habilidades(id) ON DELETE CASCADE
);

CREATE TABLE talento_media (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  tipo ENUM('foto','video','link') NOT NULL,
  archivo VARCHAR(255),
  url TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);