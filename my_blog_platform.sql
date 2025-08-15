-- My.blog_platform schema
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','author','subscriber') NOT NULL DEFAULT 'author',
  created_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(60) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS posts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  author_id INT NOT NULL,
  title VARCHAR(200) NOT NULL,
  slug VARCHAR(240) NOT NULL UNIQUE,
  content MEDIUMTEXT,
  featured_image VARCHAR(255),
  category_label VARCHAR(60),
  status ENUM('draft','published') NOT NULL DEFAULT 'published',
  view_count INT NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL,
  FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS post_categories (
  post_id INT NOT NULL,
  category_id INT NOT NULL,
  PRIMARY KEY(post_id, category_id),
  FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS tags (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(60) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS post_tags (
  post_id INT NOT NULL,
  tag_id INT NOT NULL,
  PRIMARY KEY(post_id, tag_id),
  FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
  FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS comments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  post_id INT NOT NULL,
  user_id INT NOT NULL,
  content TEXT NOT NULL,
  created_at DATETIME NOT NULL,
  FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Demo data
INSERT INTO users(username,email,password_hash,role,created_at) VALUES
('admin','admin@example.com', '$2y$10$TCCb0kYb2Gd4Yx7Vx2VZBeI7m6y5pQzXyGQz8aY0m7dO4q2HkwI2K', 'admin', NOW());

INSERT INTO categories(name) VALUES ('Tech'),('Lifestyle'),('Travel');

INSERT INTO posts(author_id,title,slug,content,featured_image,category_label,status,created_at,view_count) VALUES
(1,'Welcome to VividLens Agora','welcome-vividlens-agora','<p>This is your new lively blog platform. Enjoy writing!</p>',NULL,'Tech','published',NOW(),10),
(1,'Designing with 3D Gradients','designing-with-3d-gradients','<p>Learn how to use vibrant gradients for a modern look.</p>',NULL,'Lifestyle','published',NOW(),25),
(1,'Top 5 Places to Visit in 2025','top-5-places-2025','<p>Travel guide with beautiful spots.</p>',NULL,'Travel','published',NOW(),15);

INSERT INTO tags(name) VALUES ('design'),('ui'),('travel'),('dev') ON DUPLICATE KEY UPDATE name=name;
INSERT INTO post_tags(post_id,tag_id) VALUES (2,1),(2,2),(3,3) ON DUPLICATE KEY UPDATE post_id=post_id;
INSERT INTO post_categories(post_id,category_id) VALUES (1,1),(2,2),(3,3) ON DUPLICATE KEY UPDATE post_id=post_id;

INSERT INTO comments(post_id,user_id,content,created_at) VALUES
(1,1,'Awesome start!',NOW()),
(2,1,'Love these gradients.',NOW()),
(3,1,'Can''t wait to travel!',NOW());