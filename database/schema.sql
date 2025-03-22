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

CREATE TABLE Billing (
    BillingID INT PRIMARY KEY AUTO_INCREMENT,
    UserID VARCHAR(20),
    ConsultationTypeID INT,
    ConsultationDate DATE,
    Amount DECIMAL(10, 2),
    PaymentStatus ENUM('Paid', 'Unpaid'),
    FOREIGN KEY (ConsultationTypeID) REFERENCES ConsultationPrices(ID)
);

CREATE TABLE ConsultationPrices (
    ID INT PRIMARY KEY,
    ConsultationType VARCHAR(100),
    Price DECIMAL(10, 2)
);

INSERT INTO ConsultationPrices (ID, ConsultationType, Price) VALUES
(1, 'Laboratory Consultation', 100.00),
(2, 'Outpatient Department (OPD)', 75.00),
(3, 'Pediatric Consultation', 80.00),
(4, 'Obstetrics and Gynecology (OBGYN)', 120.00),
(5, 'Ear, Nose, and Throat (ENT)', 90.00);