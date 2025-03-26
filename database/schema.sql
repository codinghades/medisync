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
);

CREATE TABLE IF NOT EXISTS doctors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    doctor_id VARCHAR(20) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    mobile VARCHAR(15) NOT NULL
);

CREATE TABLE IF NOT EXISTS prescriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id VARCHAR(20) NOT NULL,
    doctor_id INT NOT NULL,
    date_prescribed DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS prescription_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prescription_id INT NOT NULL,
    medicine VARCHAR(100) NOT NULL,
    dosage VARCHAR(50) NOT NULL,
    duration VARCHAR(50) NOT NULL,
    instruction TEXT NOT NULL,
    advice TEXT,
    FOREIGN KEY (prescription_id) REFERENCES prescriptions(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS ConsultationPrices (
    ID INT PRIMARY KEY,
    ConsultationType VARCHAR(100),
    Price DECIMAL(10, 2)
);

CREATE TABLE IF NOT EXISTS Billing (
    BillingID INT PRIMARY KEY AUTO_INCREMENT,
    UserID VARCHAR(20),
    ConsultationTypeID INT,
    ConsultationDate DATE,
    Amount DECIMAL(10, 2),
    PaymentStatus ENUM('Paid', 'Unpaid'),
    FOREIGN KEY (ConsultationTypeID) REFERENCES ConsultationPrices(ID)
);

CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(20) NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

INSERT INTO ConsultationPrices (ID, ConsultationType, Price) VALUES
(1, 'Laboratory Consultation', 100.00),
(2, 'Outpatient Department (OPD)', 75.00),
(3, 'Pediatric Consultation', 80.00),
(4, 'Obstetrics and Gynecology (OBGYN)', 120.00),
(5, 'Ear, Nose, and Throat (ENT)', 90.00);

INSERT INTO users (user_id, first_name, last_name, gender, contact_number, email, password, role) VALUES
('P-2025-0000', 'Juan', 'Dela Cruz', 'male', '09121231234', 'juandelacruz@gmail.com', '$2y$10$fVoOoIKuE4lQuklzsM2nLeuiboX68uXC.5xSjP04KVzcYYBZlIFh.', 'patient'),
('A-2025-0001', 'Maria', 'San Pedro', 'female', '09988776655', 'admin@gmail.com', '$2y$10$2mwmIZ/HgMcV1c5Tu6Y4cO0RGohjuafHeR2oN0hMlj1Dbnu375TBS', 'admin');

INSERT INTO doctors (id, doctor_id, name, mobile) VALUES
(1, 'D-2025-0000', 'Dr. John Doe', '09123456789');

INSERT INTO prescriptions (patient_id, doctor_id, date_prescribed) VALUES
('P-2025-0000', 1, '2025-03-25 09:00:00');

INSERT INTO prescription_details (prescription_id, medicine, dosage, duration, instruction, advice) VALUES
(1, 'Amoxicillin', '500mg', '7 days', 'Take one capsule every 8 hours', 'Complete the full course of antibiotics'),
(1, 'Paracetamol', '500mg', '3 days', 'Take one tablet every 6 hours as needed', 'Do not exceed 4g per day'),
(1, 'Cetirizine', '10mg', '5 days', 'Take one tablet once daily', 'Avoid allergens and stay hydrated');
