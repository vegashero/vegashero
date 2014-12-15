CREATE TABLE IF NOT EXISTS sites (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    src VARCHAR(500) NOT NULL,
    type ENUM('iframe', 'embed') NOT NULL INDEX type (type),
    created TIMESTAMP DEFAULT NOW()
) ENGINE=InnoDb DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS providers (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    site_id INT NOT NULL,
    created TIMESTAMP DEFAULT NOW()
    INDEX site (site_id),
    FOREIGN KEY (site_id) REFERENCES sites(id) ON DELETE CASCADE
) ENGINE=InnoDb DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS games (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    ref VARCHAR(128) NOT NULL COMMENT 'provided game identifier',
    name VARCHAR(255) NOT NULL,
    category VARCHAR(255) NOT NULL COMMENT '', 
    provider_id INT NOT NULL ,
    status BOOLEAN NOT NULL DEFAULT 1,
    large_image VARCHAR(300),
    thumb_image VARCHAR(300),
    created TIMESTAMP DEFAULT NOW(),
    INDEX provider (provider_id),
    FOREIGN KEY (provider_id) REFERENCES providers(id) ON DELETE CASCADE
) ENGINE=InnoDb DEFAULT CHARSET=utf8;


