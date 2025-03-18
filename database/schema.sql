CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    gender ENUM('male', 'female', 'other') NOT NULL,
    contact_number VARCHAR(15) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,  
    name VARCHAR(255) NOT NULL,        
    date DATE NOT NULL,               
    contact VARCHAR(15) NOT NULL,      
    time TIME NOT NULL,                
    appointment_type VARCHAR(255) NOT NULL,  
    reason TEXT NOT NULL,              
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP  
);