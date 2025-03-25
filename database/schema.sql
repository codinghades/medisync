CREATE TABLE IF NOT EXISTS users (
    user_id VARCHAR(11) PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    gender ENUM('male', 'female', 'other') NOT NULL,
    contact_number VARCHAR(15) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'patient') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

<<<<<<< HEAD
CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,  
    name VARCHAR(255) NOT NULL,        
    date DATE NOT NULL,               
    contact VARCHAR(15) NOT NULL,      
    time TIME NOT NULL,                
    appointment_type VARCHAR(255) NOT NULL,  
    reason TEXT NOT NULL,
    status VARCHAR(255) NOT NULL,              
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP  
=======
CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id VARCHAR(11) NOT NULL,
    appointment_type ENUM('laboratory', 'opd', 'pedia', 'obgyn', 'ent') NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    contact_number VARCHAR(15) NOT NULL,
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES users(user_id) ON DELETE CASCADE
>>>>>>> 59e0cb76f0f71ea44ee7cee484fbd02c15b42c1d
);