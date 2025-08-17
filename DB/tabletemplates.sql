CREATE TABLE document_templates_desa (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    created_by INT NOT NULL,  -- user_id admin
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES user_admin(id)
);
CREATE TABLE document_templates_kecamatan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    created_by INT NOT NULL,  -- user_id admin
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES user_admin(id)
);

ALTER TABLE document_templates_desa 
ADD updated_at TIMESTAMP NULL DEFAULT NULL;
ALTER TABLE document_templates_desa 
ADD is_active int NULL DEFAULT 1;

ALTER TABLE document_templates_kecamatan 
ADD is_active int NULL DEFAULT 1;