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
    status VARCHAR(10) NOT NULL,
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
    patient_id VARCHAR(20),
    appointment_id INT NULL,
    ConsultationTypeID INT,
    Amount DECIMAL(10, 2),
    PaymentStatus ENUM('Paid', 'Unpaid'),
    PaymentMethod ENUM('Cash', 'Card', 'None') DEFAULT 'None',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ConsultationTypeID) REFERENCES ConsultationPrices(ID),
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(20) NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS chat_sessions (
    chat_session_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(11) NOT NULL,
    admin_id VARCHAR(11) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (admin_id) REFERENCES users(user_id)
);

CREATE TABLE IF NOT EXISTS messages (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    sender VARCHAR(255) NOT NULL,
    message_content TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    chat_session_id INT NOT NULL,
    FOREIGN KEY (chat_session_id) REFERENCES chat_sessions(chat_session_id)
);

INSERT IGNORE INTO ConsultationPrices (ID, ConsultationType, Price) VALUES
(1, 'Laboratory Consultation', 1000.00),
(2, 'Outpatient Department (OPD)', 750.00),
(3, 'Pediatric Consultation', 800.00),
(4, 'Obstetrics and Gynecology (OBGYN)', 1200.00),
(5, 'Ear, Nose, and Throat (ENT)', 900.00);

INSERT IGNORE INTO ConsultationPrices (ID, ConsultationType, Price) VALUES
(1, 'Laboratory Consultation', 1000.00),
(2, 'Outpatient Department (OPD)', 750.00),
(3, 'Pediatric Consultation', 800.00),
(4, 'Obstetrics and Gynecology (OBGYN)', 1200.00),
(5, 'Ear, Nose, and Throat (ENT)', 900.00);

INSERT IGNORE INTO users (user_id, first_name, last_name, gender, contact_number, email, password, role, created_at) VALUES
('A-2025-0001', 'Maria', 'San Pedro', 'female', '09988776655', 'admin@gmail.com', '$2y$10$2mwmIZ/HgMcV1c5Tu6Y4cO0RGohjuafHeR2oN0hMlj1Dbnu375TBS', 'admin', '2025-03-01 10:15:00'),
('P-2025-0000', 'Juan', 'Dela Cruz', 'male', '09121231234', 'juandelacruz@gmail.com', '$2y$10$fVoOoIKuE4lQuklzsM2nLeuiboX68uXC.5xSjP04KVzcYYBZlIFh.', 'patient', '2025-03-02 08:30:00'),
('P-2025-0001', 'Carlos', 'Santos', 'male', '09123456780', 'carlos.santos@gmail.com', '$2b$12$LSp89dEcSWH8k/0nGn7o0uDNtW4QVwmBaUaMDkqBxclc5Hpczrx62', 'patient', '2025-03-03 09:45:00'),
('P-2025-0002', 'Ana', 'Reyes', 'female', '09234567891', 'ana.reyes@gmail.com', '$2b$12$0fjIvREWdgSumXtA1FIDfeaYBCWBMBXYZm2La2pAZSwlmxFgCKJzq', 'patient', '2025-03-04 14:20:00'),
('P-2025-0003', 'Miguel', 'Torres', 'male', '09345678912', 'miguel.torres@gmail.com', '$2b$12$Kch/lUCodSA23jcEAeQ3Q.62uO8vjNz0bGKZqorwsl2i0IUXP6xve', 'patient', '2025-03-05 11:10:00'),
('P-2025-0004', 'Sofia', 'Gomez', 'female', '09456789123', 'sofia.gomez@gmail.com', '$2b$12$Ogx1RvdByVQejhsWVYhxweGiWeCrmVRO6IcoIGfiyw1ZWYgi5dZOe', 'patient', '2025-03-06 16:50:00'),
('P-2025-0005', 'Daniel', 'Fernandez', 'male', '09567891234', 'daniel.fernandez@gmail.com', '$2b$12$q5TknDlGazZH6w7D3ZA5huzYE5kfjtirpI4AnWW0qD1KO8qHHmA5O', 'patient', '2025-03-07 13:40:00'),
('P-2025-0006', 'Isabel', 'Cruz', 'female', '09678912345', 'isabel.cruz@gmail.com', '$2b$12$3tCMp80VTX70t8MVTRmQaO6SRdSPmwr2LLl8H0CVmTdlexh/G41tW', 'patient', '2025-03-08 07:55:00'),
('P-2025-0007', 'Jose', 'Lopez', 'male', '09789123456', 'jose.lopez@gmail.com', '$2b$12$.x8yq6vRVfo17ihWB3uLS.Fh5xs7EdUQYJL4g0RClqX5Wb5OFghTq', 'patient', '2025-03-09 12:25:00'),
('P-2025-0008', 'Marian', 'Ramos', 'female', '09891234567', 'marian.ramos@gmail.com', '$2b$12$r0Yolx/8AvW2yOXO3meduuGxGfHyUJ6wQEpU3m94HVmlzvgl0jhUO', 'patient', '2025-03-10 15:05:00'),
('P-2025-0009', 'Rafael', 'Navarro', 'male', '09912345678', 'rafael.navarro@gmail.com', '$2b$12$cEenkEKMPrnuDDwE5EqCAulgvggnL2E52rLrsx./YML6v0MKXdoGW', 'patient', '2025-03-11 09:00:00'),
('P-2025-0010', 'Lara', 'Vega', 'female', '09110000010', 'lara.vega@gmail.com', '$2y$10$eImiTXuWVxfM37uY4JANj.QdQnX3R3DqW5ZLjOyRJzpY6YpTOtmZO', 'patient', '2025-04-01 09:00:00'),
('P-2025-0011', 'Marco', 'Rivera', 'male', '09110000011', 'marco.rivera@gmail.com', '$2y$10$eImiTXuWVxfM37uY4JANj.QdQnX3R3DqW5ZLjOyRJzpY6YpTOtmZO', 'patient', '2025-04-02 10:00:00'),
('P-2025-0012', 'Bianca', 'Lim', 'female', '09110000012', 'bianca.lim@gmail.com', '$2y$10$eImiTXuWVxfM37uY4JANj.QdQnX3R3DqW5ZLjOyRJzpY6YpTOtmZO', 'patient', '2025-04-03 11:00:00'),
('P-2025-0013', 'Jared', 'Chan', 'male', '09110000013', 'jared.chan@gmail.com', '$2y$10$eImiTXuWVxfM37uY4JANj.QdQnX3R3DqW5ZLjOyRJzpY6YpTOtmZO', 'patient', '2025-04-04 12:00:00'),
('P-2025-0014', 'Nina', 'Flores', 'female', '09110000014', 'nina.flores@gmail.com', '$2y$10$eImiTXuWVxfM37uY4JANj.QdQnX3R3DqW5ZLjOyRJzpY6YpTOtmZO', 'patient', '2025-04-05 13:00:00'),
('P-2025-0015', 'Gino', 'Bautista', 'male', '09110000015', 'gino.bautista@gmail.com', '$2y$10$eImiTXuWVxfM37uY4JANj.QdQnX3R3DqW5ZLjOyRJzpY6YpTOtmZO', 'patient', '2025-04-06 14:00:00'),
('P-2025-0016', 'Celine', 'Uy', 'female', '09110000016', 'celine.uy@gmail.com', '$2y$10$eImiTXuWVxfM37uY4JANj.QdQnX3R3DqW5ZLjOyRJzpY6YpTOtmZO', 'patient', '2025-04-07 15:00:00'),
('P-2025-0017', 'Rico', 'Valdez', 'male', '09110000017', 'rico.valdez@gmail.com', '$2y$10$eImiTXuWVxfM37uY4JANj.QdQnX3R3DqW5ZLjOyRJzpY6YpTOtmZO', 'patient', '2025-04-08 16:00:00'),
('P-2025-0018', 'Paula', 'Tan', 'female', '09110000018', 'paula.tan@gmail.com', '$2y$10$eImiTXuWVxfM37uY4JANj.QdQnX3R3DqW5ZLjOyRJzpY6YpTOtmZO', 'patient', '2025-04-09 17:00:00'),
('P-2025-0019', 'Daryl', 'Reyes', 'male', '09110000019', 'daryl.reyes@gmail.com', '$2y$10$eImiTXuWVxfM37uY4JANj.QdQnX3R3DqW5ZLjOyRJzpY6YpTOtmZO', 'patient', '2025-04-10 18:00:00'),
('P-2025-0020', 'Olivia', 'Cruz', 'female', '09110000020', 'olivia.cruz@gmail.com', '$2y$10$eImiTXuWVxfM37uY4JANj.QdQnX3R3DqW5ZLjOyRJzpY6YpTOtmZO', 'patient', '2025-04-11 10:00:00'),
('P-2025-0021', 'Ethan', 'Reyes', 'male', '09110000021', 'ethan.reyes@gmail.com', '$2y$10$eImiTXuWVxfM37uY4JANj.QdQnX3R3DqW5ZLjOyRJzpY6YpTOtmZO', 'patient', '2025-04-11 11:00:00'),
('P-2025-0022', 'Sophia', 'Lim', 'female', '09110000022', 'sophia.lim@gmail.com', '$2y$10$eImiTXuWVxfM37uY4JANj.QdQnX3R3DqW5ZLjOyRJzpY6YpTOtmZO', 'patient', '2025-04-12 12:00:00'),
('P-2025-0023', 'Lucas', 'Tan', 'male', '09110000023', 'lucas.tan@gmail.com', '$2y$10$eImiTXuWVxfM37uY4JANj.QdQnX3R3DqW5ZLjOyRJzpY6YpTOtmZO', 'patient', '2025-04-12 13:00:00'),
('P-2025-0024', 'Mia', 'Flores', 'female', '09110000024', 'mia.flores@gmail.com', '$2y$10$eImiTXuWVxfM37uY4JANj.QdQnX3R3DqW5ZLjOyRJzpY6YpTOtmZO', 'patient', '2025-04-13 14:00:00'),
('P-2025-0025', 'Noah', 'Santos', 'male', '09110000025', 'noah.santos@gmail.com', '$2y$10$eImiTXuWVxfM37uY4JANj.QdQnX3R3DqW5ZLjOyRJzpY6YpTOtmZO', 'patient', '2025-04-13 15:00:00'),
('P-2025-0026', 'Ava', 'Gomez', 'female', '09110000026', 'ava.gomez@gmail.com', '$2y$10$eImiTXuWVxfM37uY4JANj.QdQnX3R3DqW5ZLjOyRJzpY6YpTOtmZO', 'patient', '2025-04-14 16:00:00'),
('P-2025-0027', 'Liam', 'Rivera', 'male', '09110000027', 'liam.rivera@gmail.com', '$2y$10$eImiTXuWVxfM37uY4JANj.QdQnX3R3DqW5ZLjOyRJzpY6YpTOtmZO', 'patient', '2025-04-14 17:00:00'),
('P-2025-0028', 'Chloe', 'Bautista', 'female', '09110000028', 'chloe.bautista@gmail.com', '$2y$10$eImiTXuWVxfM37uY4JANj.QdQnX3R3DqW5ZLjOyRJzpY6YpTOtmZO', 'patient', '2025-04-15 18:00:00'),
('P-2025-0029', 'Elijah', 'Valdez', 'male', '09110000029', 'elijah.valdez@gmail.com', '$2y$10$eImiTXuWVxfM37uY4JANj.QdQnX3R3DqW5ZLjOyRJzpY6YpTOtmZO', 'patient', '2025-04-15 19:00:00');

INSERT IGNORE INTO doctors (id, doctor_id, name, mobile) VALUES
(1, 'D-2025-0000', 'Dr. John Doe', '09123456789');

INSERT IGNORE INTO notifications (user_id, title, message, created_at) VALUES
('P-2025-0000', 'Welcome to Medisync', 'Thank you for using our program!', '2025-04-18 20:55:00'),
('P-2025-0001', 'Welcome to Medisync', 'Thank you for using our program!', '2025-04-18 20:55:00'),
('P-2025-0002', 'Welcome to Medisync', 'Thank you for using our program!', '2025-04-18 20:55:00'),
('P-2025-0003', 'Welcome to Medisync', 'Thank you for using our program!', '2025-04-18 20:55:00'),
('P-2025-0004', 'Welcome to Medisync', 'Thank you for using our program!', '2025-04-18 20:55:00'),
('P-2025-0005', 'Welcome to Medisync', 'Thank you for using our program!', '2025-04-18 20:55:00'),
('P-2025-0006', 'Welcome to Medisync', 'Thank you for using our program!', '2025-04-18 20:55:00'),
('P-2025-0007', 'Welcome to Medisync', 'Thank you for using our program!', '2025-04-18 20:55:00'),
('P-2025-0008', 'Welcome to Medisync', 'Thank you for using our program!', '2025-04-18 20:55:00'),
('P-2025-0009', 'Welcome to Medisync', 'Thank you for using our program!', '2025-04-18 20:55:00'),
('P-2025-0010', 'Welcome to Medisync', 'Thank you for using our program!', '2025-04-18 20:55:00'),
('P-2025-0011', 'Welcome to Medisync', 'Thank you for using our program!', '2025-04-18 20:55:00'),
('P-2025-0012', 'Welcome to Medisync', 'Thank you for using our program!', '2025-04-18 20:55:00'),
('P-2025-0013', 'Welcome to Medisync', 'Thank you for using our program!', '2025-04-18 20:55:00'),
('P-2025-0014', 'Welcome to Medisync', 'Thank you for using our program!', '2025-04-18 20:55:00'),
('P-2025-0015', 'Welcome to Medisync', 'Thank you for using our program!', '2025-04-18 20:55:00'),
('P-2025-0016', 'Welcome to Medisync', 'Thank you for using our program!', '2025-04-18 20:55:00'),
('P-2025-0017', 'Welcome to Medisync', 'Thank you for using our program!', '2025-04-18 20:55:00'),
('P-2025-0018', 'Welcome to Medisync', 'Thank you for using our program!', '2025-04-18 20:55:00'),
('P-2025-0019', 'Welcome to Medisync', 'Thank you for using our program!', '2025-04-18 20:55:00'),
('P-2025-0020', 'Welcome to Medisync', 'Thank you for using our program!', '2025-04-18 20:55:00'),
('P-2025-0021', 'Welcome to Medisync', 'Thank you for using our program!', '2025-04-18 20:55:00'),
('P-2025-0022', 'Welcome to Medisync', 'Thank you for using our program!', '2025-04-18 20:55:00'),
('P-2025-0023', 'Welcome to Medisync', 'Thank you for using our program!', '2025-04-18 20:55:00'),
('P-2025-0024', 'Welcome to Medisync', 'Thank you for using our program!', '2025-04-18 20:55:00'),
('P-2025-0025', 'Welcome to Medisync', 'Thank you for using our program!', '2025-04-18 20:55:00'),
('P-2025-0026', 'Welcome to Medisync', 'Thank you for using our program!', '2025-04-18 20:55:00'),
('P-2025-0027', 'Welcome to Medisync', 'Thank you for using our program!', '2025-04-18 20:55:00'),
('P-2025-0028', 'Welcome to Medisync', 'Thank you for using our program!', '2025-04-18 20:55:00'),
('P-2025-0029', 'Welcome to Medisync', 'Thank you for using our program!', '2025-04-18 20:55:00');

INSERT IGNORE INTO prescriptions (id, patient_id, doctor_id, date_prescribed) VALUES
(1, 'P-2025-0000', 1, '2025-04-18 09:00:00'),
(2, 'P-2025-0001', 1, '2025-04-06 10:00:00'),
(3, 'P-2025-0002', 1, '2025-04-1 12:00:00'),
(4, 'P-2025-0003', 1, '2025-03-18 12:00:00'),
(5, 'P-2025-0004', 1, '2025-04-18 13:00:00'),
(6, 'P-2025-0005', 1, '2025-03-15 14:00:00'),
(7, 'P-2025-0006', 1, '2025-04-18 15:00:00'),
(8, 'P-2025-0007', 1, '2025-03-10 16:00:00'),
(9, 'P-2025-0008', 1, '2025-04-12 17:00:00'),
(10, 'P-2025-0009', 1, '2025-03-08 18:00:00'),
(11, 'P-2025-0020', 1, '2025-03-05 12:00:00'),
(12, 'P-2025-0021', 1, '2025-03-10 17:00:00'),
(13, 'P-2025-0022', 1, '2025-03-15 10:30:00'),
(14, 'P-2025-0023', 1, '2025-03-20 16:30:00'),
(15, 'P-2025-0024', 1, '2025-03-25 11:30:00'),
(16, 'P-2025-0025', 1, '2025-03-30 17:00:00'),
(17, 'P-2025-0026', 1, '2025-04-02 10:00:00'),
(18, 'P-2025-0027', 1, '2025-04-07 15:00:00'),
(19, 'P-2025-0028', 1, '2025-04-12 09:30:00'),
(20, 'P-2025-0029', 1, '2025-04-15 20:00:00');

INSERT IGNORE INTO prescription_details (prescription_id, medicine, dosage, duration, instruction, advice) VALUES
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
(10, 'Esomeprazole', '40mg', '14 days', 'Take before breakfast', 'Avoid acidic foods, no late-night eating.'),
(11, 'Loratadine', '10mg', '7 days', 'Take once daily', 'Avoid allergens, clean surroundings.'),
(12, 'Omeprazole', '20mg', '14 days', 'Take before breakfast', 'Avoid spicy and fatty foods.'),
(13, 'Atorvastatin', '10mg', '30 days', 'Take at bedtime', 'Limit fats, exercise daily.'),
(14, 'Cetirizine', '10mg', '5 days', 'Take once daily', 'Stay away from allergens.'),
(15, 'Folic Acid', '400mcg', '30 days', 'Take once daily', 'Eat leafy greens, avoid alcohol.'),
(16, 'Metoprolol', '50mg', '30 days', 'Take twice daily', 'Manage stress, avoid alcohol.'),
(17, 'Loperamide', '2mg', 'As needed', 'Take after loose stool', 'Drink fluids, avoid dairy.'),
(18, 'Acetaminophen', '500mg', '5 days', 'Take every 6 hours', 'Stay hydrated, avoid overexertion.'),
(19, 'Potassium Chloride', '20mEq', '30 days', 'Take daily', 'Eat potassium-rich foods.'),
(20, 'Prednisone', '10mg', '5 days', 'Take in the morning', 'Reduce salt, follow dosage.');

INSERT IGNORE INTO appointments (patient_id, appointment_type, appointment_date, appointment_time, contact_number, notes, status, created_at) VALUES
('P-2025-0000', 'laboratory', '2025-05-01', '09:00:00', '09121231234', 'Demo lab appointment', 'Active', '2025-04-18 20:45:00'),
('P-2025-0000', 'opd', '2025-05-05', '10:30:00', '09121231234', 'Demo OPD appointment', 'Active', '2025-04-18 20:46:00'),
('P-2025-0000', 'pedia', '2025-05-10', '11:00:00', '09121231234', 'Demo pediatric appointment', 'Active', '2025-04-18 20:47:00'),
('P-2025-0000', 'obgyn', '2025-04-15', '14:00:00', '09121231234', 'Demo OBGYN appointment', 'Active', '2025-04-18 20:48:00'),
('P-2025-0000', 'ent', '2025-04-20', '16:30:00', '09121231234', 'Demo ENT appointment', 'Completed', '2025-04-18 20:49:00'),

-- Carlos Santos (P-2025-0001)
 ('P-2025-0001', 'pedia', '2025-03-02', '09:00:00', '09123456780', 'Child vaccination', 'Completed', '2025-02-18 07:45:00'),
 ('P-2025-0001', 'opd', '2025-04-13', '13:00:00', '09123456780', 'Back pain checkup', 'Active', '2025-03-25 13:30:00'),
 ('P-2025-0001', 'laboratory', '2025-04-17', '15:00:00', '09123456780', 'Blood test', 'Active', '2025-03-28 16:05:00'),
 ('P-2025-0001', 'laboratory', '2025-04-22', '09:30:00', '09123456780', 'Routine blood test', 'Active', '2025-03-30 08:55:00'),

-- Chloe Bautista (P-2025-0028)
('P-2025-0028', 'obgyn', '2025-04-10', '11:30:00', '09110000028', 'Annual checkup', 'Completed', '2025-04-05 17:00:00'),
('P-2025-0028', 'laboratory', '2025-04-17', '10:00:00', '09110000028', 'Blood work', 'Completed', '2025-04-11 08:00:00'),
('P-2025-0028', 'opd', '2025-04-25', '15:30:00', '09110000028', 'Follow-up', 'Active', '2025-04-19 12:00:00'),
('P-2025-0028', 'pedia', '2025-05-02', '13:00:00', '09110000028', 'Child wellness', 'Active', '2025-04-26 16:00:00'),
('P-2025-0028', 'ent', '2025-05-08', '09:00:00', '09110000028', 'Allergy test', 'Active', '2025-05-03 10:00:00'),

-- Elijah Valdez (P-2025-0029)
('P-2025-0029', 'ent', '2025-04-12', '14:30:00', '09110000029', 'Sinus infection', 'Completed', '2025-04-07 11:00:00'),
('P-2025-0029', 'opd', '2025-04-19', '11:00:00', '09110000029', 'General exam', 'Completed', '2025-04-13 15:00:00'),
('P-2025-0029', 'laboratory', '2025-04-26', '16:30:00', '09110000029', 'Culture test', 'Active', '2025-04-20 09:00:00'),
('P-2025-0029', 'obgyn', '2025-05-03', '10:30:00', '09110000029', 'Consultation', 'Active', '2025-04-27 14:00:00'),
('P-2025-0029', 'pedia', '2025-05-09', '17:00:00', '09110000029', 'Infant feeding', 'Active', '2025-05-04 18:00:00'),

-- Lara Vega (P-2025-0010)
('P-2025-0010', 'opd', '2025-04-03', '10:30:00', '09110000010', 'Routine checkup', 'Completed', '2025-04-01 09:00:00'),
('P-2025-0010', 'laboratory', '2025-04-10', '14:30:00', '09110000010', 'Blood panel', 'Completed', '2025-04-05 11:30:00'),
('P-2025-0010', 'obgyn', '2025-04-17', '16:00:00', '09110000010', 'Follow-up', 'Active', '2025-04-12 13:00:00'),
('P-2025-0010', 'ent', '2025-04-24', '09:30:00', '09110000010', 'Throat issue', 'Active', '2025-04-19 17:00:00'),

-- Marco Rivera (P-2025-0011)
('P-2025-0011', 'pedia', '2025-04-05', '11:00:00', '09110000011', 'Child exam', 'Completed', '2025-04-02 10:00:00'),
('P-2025-0011', 'opd', '2025-04-12', '15:00:00', '09110000011', 'Allergy check', 'Completed', '2025-04-07 14:00:00'),
('P-2025-0011', 'laboratory', '2025-04-19', '10:30:00', '09110000011', 'Urine test', 'Active', '2025-04-14 09:00:00'),
('P-2025-0011', 'ent', '2025-04-26', '17:00:00', '09110000011', 'Hearing problem', 'Active', '2025-04-21 11:30:00'),

-- Bianca Lim (P-2025-0012)
('P-2025-0012', 'obgyn', '2025-04-08', '13:30:00', '09110000012', 'Consultation', 'Completed', '2025-04-03 11:00:00'),
('P-2025-0012', 'opd', '2025-04-15', '09:00:00', '09110000012', 'General health', 'Completed', '2025-04-10 16:00:00'),
('P-2025-0012', 'laboratory', '2025-04-22', '16:30:00', '09110000012', 'Hormone test', 'Active', '2025-04-17 08:30:00'),
('P-2025-0012', 'pedia', '2025-04-29', '11:30:00', '09110000012', 'Child development', 'Active', '2025-04-24 12:30:00'),

-- Jared Chan (P-2025-0013)
('P-2025-0013', 'pedia', '2025-04-10', '16:30:00', '09110000013', 'Vaccination', 'Completed', '2025-04-04 12:00:00'),
('P-2025-0013', 'laboratory', '2025-04-17', '11:00:00', '09110000013', 'Allergy screen', 'Completed', '2025-04-11 14:30:00'),
('P-2025-0013', 'opd', '2025-04-24', '14:30:00', '09110000013', 'Skin issue', 'Active', '2025-04-18 10:00:00'),
('P-2025-0013', 'ent', '2025-05-01', '10:00:00', '09110000013', 'Nose bleed', 'Active', '2025-04-25 15:30:00'),

-- Nina Flores (P-2025-0014)
('P-2025-0014', 'ent', '2025-04-12', '09:30:00', '09110000014', 'Sore throat', 'Completed', '2025-04-05 13:00:00'),
('P-2025-0014', 'obgyn', '2025-04-19', '13:00:00', '09110000014', 'Checkup', 'Completed', '2025-04-13 09:30:00'),
('P-2025-0014', 'opd', '2025-04-26', '16:00:00', '09110000014', 'Headache', 'Active', '2025-04-20 13:30:00'),
('P-2025-0014', 'laboratory', '2025-05-03', '11:30:00', '09110000014', 'Lipid profile', 'Active', '2025-04-27 17:00:00'),

-- Gino Bautista (P-2025-0015)
('P-2025-0015', 'laboratory', '2025-04-15', '14:00:00', '09110000015', 'Glucose test', 'Completed', '2025-04-06 14:00:00'),
('P-2025-0015', 'pedia', '2025-04-22', '09:30:00', '09110000015', 'Well-baby check', 'Completed', '2025-04-16 10:30:00'),
('P-2025-0015', 'opd', '2025-04-29', '17:00:00', '09110000015', 'Flu-like symptoms', 'Active', '2025-04-23 15:00:00'),
('P-2025-0015', 'ent', '2025-05-06', '13:00:00', '09110000015', 'Earache', 'Active', '2025-04-30 09:00:00'),

-- Celine Uy (P-2025-0016)
('P-2025-0016', 'obgyn', '2025-04-17', '10:00:00', '09110000016', 'Routine exam', 'Completed', '2025-04-07 15:00:00'),
('P-2025-0016', 'opd', '2025-04-24', '14:00:00', '09110000016', 'Allergy consultation', 'Completed', '2025-04-18 11:30:00'),
('P-2025-0016', 'laboratory', '2025-05-01', '09:30:00', '09110000016', 'Allergy testing', 'Active', '2025-04-25 16:00:00'),
('P-2025-0016', 'pedia', '2025-05-08', '15:00:00', '09110000016', 'Child vaccination', 'Active', '2025-05-02 10:00:00'),

-- Rico Valdez (P-2025-0017)
('P-2025-0017', 'pedia', '2025-04-19', '16:30:00', '09110000017', 'Annual physical', 'Completed', '2025-04-08 16:00:00'),
('P-2025-0017', 'laboratory', '2025-04-26', '11:30:00', '09110000017', 'Cholesterol check', 'Completed', '2025-04-20 14:00:00'),
('P-2025-0017', 'opd', '2025-05-03', '10:00:00', '09110000017', 'Joint pain', 'Active', '2025-04-27 09:30:00'),
('P-2025-0017', 'ent', '2025-05-10', '17:30:00', '09110000017', 'Hearing test', 'Active', '2025-05-04 11:00:00'),

-- Paula Tan (P-2025-0018)
('P-2025-0018', 'ent', '2025-04-21', '13:00:00', '09110000018', 'Nasal congestion', 'Completed', '2025-04-09 17:00:00'),
('P-2025-0018', 'obgyn', '2025-04-28', '09:00:00', '09110000018', 'Consultation', 'Completed', '2025-04-22 15:30:00'),
('P-2025-0018', 'opd', '2025-05-05', '15:30:00', '09110000018', 'Skin rash', 'Active', '2025-04-29 12:00:00'),
('P-2025-0018', 'laboratory', '2025-05-12', '11:00:00', '09110000018', 'Thyroid function', 'Active', '2025-05-06 16:30:00'),

-- Daryl Reyes (P-2025-0019)
('P-2025-0019', 'laboratory', '2025-04-23', '15:30:00', '09110000019', 'Blood sugar', 'Completed', '2025-04-10 18:00:00'),
('P-2025-0019', 'pedia', '2025-04-30', '14:30:00', '09110000019', 'Follow-up', 'Completed', '2025-04-24 10:00:00'),
('P-2025-0019', 'opd', '2025-05-07', '09:00:00', '09110000019', 'Back pain', 'Active', '2025-05-01 13:30:00'),
('P-2025-0019', 'ent', '2025-05-14', '16:00:00', '09110000019', 'Tinnitus', 'Active', '2025-05-08 17:00:00');

INSERT IGNORE INTO Billing (patient_id, appointment_id, ConsultationTypeID, created_at, Amount, PaymentStatus, PaymentMethod) VALUES
('P-2025-0000', 1, 1, '2025-05-01 09:00:00', 1000.00, 'Unpaid', 'None'),
('P-2025-0000', 2, 2, '2025-05-05 10:30:00', 750.00, 'Unpaid', 'None'),
('P-2025-0000', 3, 3, '2025-05-10 11:00:00', 800.00, 'Unpaid', 'None'),
('P-2025-0000', 4, 4, '2025-05-15 14:00:00', 1200.00, 'Unpaid', 'None'),
('P-2025-0000', 5, 5, '2025-05-20 16:30:00', 900.00, 'Unpaid', 'None'),

-- Carlos Santos (P-2025-0001)
('P-2025-0001', 6, 3, '2025-02-18 07:45:00', 800.00, 'Paid', 'Cash'),
('P-2025-0001', 7, 2, '2025-02-25 13:30:00', 750.00, 'Unpaid', 'None'),
('P-2025-0001', 8, 1, '2025-03-05 16:05:00', 1000.00, 'Unpaid', 'None'),
('P-2025-0001', 9, 1, '2025-03-12 08:55:00', 1000.00, 'Unpaid', 'None'),

-- Ana Reyes (P-2025-0002)
('P-2025-0002', 10, 5, '2025-02-10 09:00:00', 900.00, 'Unpaid', 'None'),
('P-2025-0002', 11, 2, '2025-02-20 12:40:00', 750.00, 'Paid', 'Card'),
('P-2025-0002', 12, 1, '2025-03-05 11:55:00', 1000.00, 'Unpaid', 'None'),
('P-2025-0002', 13, 4, '2025-03-08 14:35:00', 1200.00, 'Unpaid', 'None'),
('P-2025-0002', 14, 3, '2025-03-14 10:20:00', 800.00, 'Unpaid', 'None'),
('P-2025-0002', 15, 3, '2025-03-18 15:50:00', 800.00, 'Unpaid', 'None'),

-- Miguel Torres (P-2025-0003)
('P-2025-0003', 16, 1, '2025-02-15 07:30:00', 1000.00, 'Paid', 'Cash'),
('P-2025-0003', 17, 5, '2025-02-28 16:10:00', 900.00, 'Unpaid', 'None'),
('P-2025-0003', 18, 2, '2025-03-10 12:05:00', 750.00, 'Unpaid', 'None'),
('P-2025-0003', 19, 2, '2025-03-15 08:15:00', 750.00, 'Unpaid', 'None'),

-- Sofia Gomez (P-2025-0004)
('P-2025-0004', 20, 4, '2025-02-12 13:45:00', 1200.00, 'Unpaid', 'None'),
('P-2025-0004', 21, 1, '2025-02-22 15:55:00', 1000.00, 'Paid', 'Card'),
('P-2025-0004', 22, 2, '2025-03-08 09:25:00', 750.00, 'Unpaid', 'None'),
('P-2025-0004', 23, 5, '2025-03-14 16:40:00', 900.00, 'Unpaid', 'None'),
('P-2025-0004', 24, 4, '2025-03-18 10:50:00', 1200.00, 'Unpaid', 'None'),

-- Olivia Cruz (P-2025-0020)
('P-2025-0020', 25, 2, '2025-03-01 10:00:00', 750.00, 'Paid', 'Card'),
('P-2025-0020', 26, 1, '2025-03-08 12:00:00', 1000.00, 'Paid', 'Cash'),
('P-2025-0020', 27, 3, '2025-04-15 15:00:00', 800.00, 'Unpaid', 'None'),
('P-2025-0020', 28, 4, '2025-04-22 11:00:00', 1200.00, 'Unpaid', 'None'),

-- Ethan Reyes (P-2025-0021)
('P-2025-0021', 29, 5, '2025-03-05 09:30:00', 900.00, 'Paid', 'Cash'),
('P-2025-0021', 30, 2, '2025-03-12 14:30:00', 750.00, 'Paid', 'Card'),
('P-2025-0021', 31, 1, '2025-04-18 16:30:00', 1000.00, 'Unpaid', 'None'),
('P-2025-0021', 32, 3, '2025-04-24 08:00:00', 800.00, 'Unpaid', 'None'),

-- Sophia Lim (P-2025-0022)
('P-2025-0022', 33, 4, '2025-03-10 11:30:00', 1200.00, 'Paid', 'Card'),
('P-2025-0022', 34, 2, '2025-03-18 09:00:00', 750.00, 'Paid', 'Cash'),
('P-2025-0022', 35, 1, '2025-04-20 13:00:00', 1000.00, 'Unpaid', 'None'),
('P-2025-0022', 36, 5, '2025-04-26 17:00:00', 900.00, 'Unpaid', 'None'),

-- Lucas Tan (P-2025-0023)
('P-2025-0023', 37, 3, '2025-03-15 16:00:00', 800.00, 'Paid', 'Cash'),
('P-2025-0023', 38, 1, '2025-03-22 10:30:00', 1000.00, 'Paid', 'Card'),
('P-2025-0023', 39, 2, '2025-04-21 09:30:00', 750.00, 'Unpaid', 'None'),
('P-2025-0023', 40, 5, '2025-04-25 14:00:00', 900.00, 'Unpaid', 'None'),

-- Mia Flores (P-2025-0024)
('P-2025-0024', 41, 5, '2025-03-20 13:30:00', 900.00, 'Paid', 'Cash'),
('P-2025-0024', 42, 4, '2025-03-26 11:00:00', 1200.00, 'Paid', 'Card'),
('P-2025-0024', 43, 2, '2025-04-20 17:00:00', 750.00, 'Unpaid', 'None'),
('P-2025-0024', 44, 1, '2025-04-27 10:00:00', 1000.00, 'Unpaid', 'None'),

-- Noah Santos (P-2025-0025)
('P-2025-0025', 45, 1, '2025-03-25 08:00:00', 1000.00, 'Paid', 'Cash'),
('P-2025-0025', 46, 3, '2025-03-30 14:00:00', 800.00, 'Paid', 'Card'),
('P-2025-0025', 47, 2, '2025-04-22 12:30:00', 750.00, 'Unpaid', 'None'),
('P-2025-0025', 48, 5, '2025-04-28 09:00:00', 900.00, 'Unpaid', 'None'),

-- Ava Gomez (P-2025-0026)
('P-2025-0026', 49, 2, '2025-03-28 11:00:00', 750.00, 'Paid', 'Cash'),
('P-2025-0026', 50, 4, '2025-04-03 15:00:00', 1200.00, 'Paid', 'Card'),
('P-2025-0026', 51, 1, '2025-04-23 10:00:00', 1000.00, 'Unpaid', 'None'),
('P-2025-0026', 52, 3, '2025-04-29 14:00:00', 800.00, 'Unpaid', 'None'),

-- Liam Rivera (P-2025-0027)
('P-2025-0027', 53, 3, '2025-04-01 09:00:00', 800.00, 'Paid', 'Cash'),
('P-2025-0027', 54, 5, '2025-04-08 13:00:00', 900.00, 'Paid', 'Card'),
('P-2025-0027', 55, 2, '2025-04-24 11:00:00', 750.00, 'Unpaid', 'None'),
('P-2025-0027', 56, 1, '2025-04-30 16:00:00', 1000.00, 'Unpaid', 'None'),

-- Chloe Bautista (P-2025-0028)
('P-2025-0028', 57, 4, '2025-04-05 17:00:00', 1200.00, 'Paid', 'Cash'),
('P-2025-0028', 58, 1, '2025-04-11 08:00:00', 1000.00, 'Paid', 'Card'),
('P-2025-0028', 59, 2, '2025-04-19 12:00:00', 750.00, 'Unpaid', 'None'),
('P-2025-0028', 60, 3, '2025-04-26 16:00:00', 800.00, 'Unpaid', 'None'),
('P-2025-0028', 61, 5, '2025-05-03 10:00:00', 900.00, 'Unpaid', 'None'),

-- Elijah Valdez (P-2025-0029)
('P-2025-0029', 62, 5, '2025-04-07 11:00:00', 900.00, 'Paid', 'Cash'),
('P-2025-0029', 63, 2, '2025-04-13 15:00:00', 750.00, 'Paid', 'Card'),
('P-2025-0029', 64, 1, '2025-04-20 09:00:00', 1000.00, 'Unpaid', 'None'),
('P-2025-0029', 65, 4, '2025-04-27 14:00:00', 1200.00, 'Unpaid', 'None'),
('P-2025-0029', 66, 3, '2025-05-04 18:00:00', 800.00, 'Unpaid', 'None');

INSERT INTO chat_sessions (chat_session_id, user_id, admin_id)
VALUES (1, 'P-2025-0000', 'A-2025-0001'),
       (2, 'P-2025-0003', 'A-2025-0001'),
       (3, 'P-2025-0007', 'A-2025-0001'),
       (4, 'P-2025-0012', 'A-2025-0001'),
       (5, 'P-2025-0016', 'A-2025-0001'),
       (6, 'P-2025-0021', 'A-2025-0001'),
       (7, 'P-2025-0025', 'A-2025-0001');

INSERT INTO messages (sender, message_content, chat_session_id) VALUES 
('P-2025-0000', 'Hello, I need assistance with my account.', 1),
('A-2025-0001', 'Hi there! How can I help you today?', 1),
('P-2025-0000', 'I have a question about my recent bill.', 1),

('P-2025-0003', "Good morning, I\'d like to reschedule my appointment.", 2),
('A-2025-0001', 'Certainly, can you please provide your appointment details?', 2),

('P-2025-007', "Hi, I\'m having trouble logging in.", 3),
('A-2025-0001', 'I can help with that. What seems to be the issue?', 3),

('P-2025-0012', 'Hello, I wanted to confirm my appointment for tomorrow.', 4),
('A-2025-0001', 'Yes, your appointment is scheduled for...', 4),

('P-2025-0016', 'Good day, I have a question about a medication.', 5),
('A-2025-0001', 'Please provide the name of the medication.', 5),

('P-2025-0021', 'Hi, I need to book a new appointment.', 6),
('A-2025-0001', 'What type of consultation are you looking for?', 6),

('P-2025-0025', "Hello, I haven\'t received my lab results yet.", 7),
('A-2025-0001', 'Let me check on that for you.', 7);