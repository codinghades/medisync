USE user_database;

INSERT INTO users (first_name, last_name, gender, contact_number, email, password) VALUES
('John', 'Doe', 'male', '09123456789', 'john@example.com', MD5('password123')),
('Jane', 'Doe', 'female', '09234567890', 'jane@example.com', MD5('password123'));