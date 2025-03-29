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

CREATE TABLE IF NOT EXISTS totalbilling (
  ID int(11) NOT NULL,
  UserID varchar(20) DEFAULT NULL,
  TotalAmount decimal(10,2) DEFAULT 0.00,
  PaymentStatus enum('Paid','Unpaid') DEFAULT 'Unpaid',
  PaymentMethod enum('Cash','Card','None') DEFAULT 'None',
  LastUpdated timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) 

CREATE TABLE IF NOT EXISTS notifications (
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
('A-2025-0001', 'Maria', 'San Pedro', 'female', '09988776655', 'admin@gmail.com', '$2y$10$2mwmIZ/HgMcV1c5Tu6Y4cO0RGohjuafHeR2oN0hMlj1Dbnu375TBS', 'admin', '2025-03-26 13:27:06'),
('P-2025-0000', 'Juan', 'Dela Cruz', 'male', '09121231234', 'juandelacruz@gmail.com', '$2y$10$fVoOoIKuE4lQuklzsM2nLeuiboX68uXC.5xSjP04KVzcYYBZlIFh.', 'patient', '2025-03-26 13:27:06'),
('P-2025-0001', 'Carlos', 'Santos', 'male', '09123456780', 'carlos.santos@gmail.com', '$2b$12$LSp89dEcSWH8k/0nGn7o0uDNtW4QVwmBaUaMDkqBxclc5Hpczrx62', 'patient', '2025-03-29 04:17:49'),
('P-2025-0002', 'Ana', 'Reyes', 'female', '09234567891', 'ana.reyes@gmail.com', '$2b$12$0fjIvREWdgSumXtA1FIDfeaYBCWBMBXYZm2La2pAZSwlmxFgCKJzq', 'patient', '2025-03-29 04:17:49'),
('P-2025-0003', 'Miguel', 'Torres', 'male', '09345678912', 'miguel.torres@gmail.com', '$2b$12$Kch/lUCodSA23jcEAeQ3Q.62uO8vjNz0bGKZqorwsl2i0IUXP6xve', 'patient', '2025-03-29 04:17:49'),
('P-2025-0004', 'Sofia', 'Gomez', 'female', '09456789123', 'sofia.gomez@gmail.com', '$2b$12$Ogx1RvdByVQejhsWVYhxweGiWeCrmVRO6IcoIGfiyw1ZWYgi5dZOe', 'patient', '2025-03-29 04:17:49'),
('P-2025-0005', 'Daniel', 'Fernandez', 'male', '09567891234', 'daniel.fernandez@gmail.com', '$2b$12$q5TknDlGazZH6w7D3ZA5huzYE5kfjtirpI4AnWW0qD1KO8qHHmA5O', 'patient', '2025-03-29 04:17:49'),
('P-2025-0006', 'Isabel', 'Cruz', 'female', '09678912345', 'isabel.cruz@gmail.com', '$2b$12$3tCMp80VTX70t8MVTRmQaO6SRdSPmwr2LLl8H0CVmTdlexh/G41tW', 'patient', '2025-03-29 04:17:49'),
('P-2025-0007', 'Jose', 'Lopez', 'male', '09789123456', 'jose.lopez@gmail.com', '$2b$12$.x8yq6vRVfo17ihWB3uLS.Fh5xs7EdUQYJL4g0RClqX5Wb5OFghTq', 'patient', '2025-03-29 04:17:49'),
('P-2025-0008', 'Marian', 'Ramos', 'female', '09891234567', 'marian.ramos@gmail.com', '$2b$12$r0Yolx/8AvW2yOXO3meduuGxGfHyUJ6wQEpU3m94HVmlzvgl0jhUO', 'patient', '2025-03-29 04:17:49'),
('P-2025-0009', 'Rafael', 'Navarro', 'male', '09912345678', 'rafael.navarro@gmail.com', '$2b$12$cEenkEKMPrnuDDwE5EqCAulgvggnL2E52rLrsx./YML6v0MKXdoGW', 'patient', '2025-03-29 04:17:49');

INSERT INTO doctors (id, doctor_id, name, mobile) VALUES
(1, 'D-2025-0000', 'Dr. John Doe', '09123456789');

INSERT INTO prescriptions (patient_id, doctor_id, date_prescribed) VALUES
(1, 'P-2025-0000', 1, '2025-03-25 09:00:00'),
(2, 'P-2025-0001', 1, '2025-03-26 10:00:00'),
(3, 'P-2025-0002', 1, '2025-03-26 11:00:00'),
(4, 'P-2025-0003', 1, '2025-03-26 12:00:00'),
(5, 'P-2025-0004', 1, '2025-03-26 13:00:00'),
(6, 'P-2025-0005', 1, '2025-03-26 14:00:00'),
(7, 'P-2025-0006', 1, '2025-03-26 15:00:00'),
(8, 'P-2025-0007', 1, '2025-03-26 16:00:00'),
(9, 'P-2025-0008', 1, '2025-03-26 17:00:00'),
(10, 'P-2025-0009', 1, '2025-03-26 18:00:00');

INSERT INTO prescription_details (prescription_id, medicine, dosage, duration, instruction, advice) VALUES
(1, 'Amoxicillin', '500mg', '7 days', 'Take one capsule every 8 hours', 'Complete the full course of antibiotics'),
(1, 'Paracetamol', '500mg', '3 days', 'Take one tablet every 6 hours as needed', 'Do not exceed 4g per day'),
(1, 'Cetirizine', '10mg', '5 days', 'Take one tablet once daily', 'Avoid allergens and stay hydrated'),
(2, 'Ibuprofen', '200mg', '5 days', 'Take every 8 hours', 'Rest, stay hydrated, avoid strenuous activity.'),
(2, 'Loratadine', '10mg', '7 days', 'Take once daily', 'Avoid allergens, clean surroundings.'),
(2, 'Omeprazole', '20mg', '14 days', 'Take before breakfast', 'Avoid spicy and fatty foods.'),
(3, 'Metformin', '500mg', '30 days', 'Take twice daily', 'Monitor blood sugar, eat healthy.'),
(3, 'Atorvastatin', '10mg', '30 days', 'Take at bedtime', 'Limit fats, exercise daily.'),
(3, 'Losartan', '50mg', '30 days', 'Take once daily', 'Reduce salt, check BP regularly.'),
(4, 'Salbutamol', '2mg', 'As needed', 'Take every 6 hours', 'Avoid smoke, use inhaler properly.'),
(4, 'Cetirizine', '10mg', '5 days', 'Take once daily', 'Stay away from allergens.'),
(4, 'Budesonide', '200mcg', '14 days', 'Inhale twice daily', 'Rinse mouth after use.'),
(5, 'Vitamin C', '500mg', '30 days', 'Take once daily', 'Eat fruits, drink water.'),
(5, 'Folic Acid', '400mcg', '30 days', 'Take once daily', 'Eat leafy greens, avoid alcohol.'),
(5, 'Iron Supplement', '325mg', '30 days', 'Take with meals', 'Eat iron-rich foods.'),
(6, 'Amlodipine', '5mg', '30 days', 'Take once daily', 'Exercise, reduce salt.'),
(6, 'Metoprolol', '50mg', '30 days', 'Take twice daily', 'Manage stress, avoid alcohol.'),
(6, 'Aspirin', '81mg', '30 days', 'Take daily', 'Stay hydrated, avoid smoking.'),
(7, 'Diphenhydramine', '25mg', 'As needed', 'Take at bedtime', 'Avoid allergens, keep bedroom clean.'),
(7, 'Loperamide', '2mg', 'As needed', 'Take after loose stool', 'Drink fluids, avoid dairy.'),
(7, 'Ranitidine', '150mg', '14 days', 'Take twice daily', 'Avoid caffeine and alcohol.'),
(8, 'Cefuroxime', '500mg', '7 days', 'Take every 12 hours', 'Complete course, rest well.'),
(8, 'Acetaminophen', '500mg', '5 days', 'Take every 6 hours', 'Stay hydrated, avoid overexertion.'),
(8, 'Dextromethorphan', '15mg', 'As needed', 'Take every 8 hours', 'Drink warm tea, avoid cold drinks.'),
(9, 'Hydrochlorothiazide', '25mg', '30 days', 'Take in the morning', 'Stay hydrated, limit salt.'),
(9, 'Potassium Chloride', '20mEq', '30 days', 'Take daily', 'Eat potassium-rich foods.'),
(9, 'Carvedilol', '6.25mg', '30 days', 'Take twice daily', 'Exercise, reduce stress.'),
(10, 'Levofloxacin', '500mg', '7 days', 'Take daily', 'Avoid dairy, drink water.'),
(10, 'Prednisone', '10mg', '5 days', 'Take in the morning', 'Reduce salt, follow dosage.'),
(10, 'Esomeprazole', '40mg', '14 days', 'Take before breakfast', 'Avoid acidic foods, no late-night eating.');
