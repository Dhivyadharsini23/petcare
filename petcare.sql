CREATE DATABASE IF NOT EXISTS petcare;
USE petcare;

DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS cart;
DROP TABLE IF EXISTS appointments;
DROP TABLE IF EXISTS pet_items;
DROP TABLE IF EXISTS doctors;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) NOT NULL,
  email VARCHAR(120) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  location VARCHAR(100) NOT NULL,
  address TEXT,
  phone VARCHAR(20),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE doctors (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) NOT NULL,
  specialization VARCHAR(80),
  location VARCHAR(100) NOT NULL,
  rating DECIMAL(2,1) DEFAULT 0,
  fee INT DEFAULT 300,
  experience INT DEFAULT 5
);

CREATE TABLE pet_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  category VARCHAR(50),
  price INT NOT NULL,
  stock INT DEFAULT 0,
  description VARCHAR(255)
);

CREATE TABLE appointments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  doctor_id INT NOT NULL,
  pet_name VARCHAR(80) NOT NULL,
  pet_issue TEXT NOT NULL,
  appt_date DATE NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE
);

CREATE TABLE cart (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  item_id INT NOT NULL,
  qty INT DEFAULT 1,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (item_id) REFERENCES pet_items(id) ON DELETE CASCADE
);

CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  total INT NOT NULL,
  delivery_fee INT NOT NULL,
  grand_total INT NOT NULL,
  address TEXT NOT NULL,
  phone VARCHAR(20) NOT NULL,
  status VARCHAR(30) DEFAULT 'Placed - Out for Door Delivery',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  item_name VARCHAR(100),
  qty INT,
  price INT,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

INSERT INTO doctors (name, specialization, location, rating, fee, experience) VALUES
('Dr. Ramesh Kumar','Dog Specialist','Chennai',4.8,500,10),
('Dr. Anita Sharma','Cat Specialist','Bangalore',4.6,450,8),
('Dr. Suresh Iyer','General Vet','Chennai',4.2,300,6),
('Dr. Priya Nair','Surgery Specialist','Bangalore',4.9,800,12),
('Dr. Manoj Verma','General Vet','Mumbai',4.0,350,5),
('Dr. Kavitha Reddy','Dermatology','Chennai',4.7,600,9),
('Dr. Arjun Menon','Bird Specialist','Kochi',4.5,400,7),
('Dr. Neha Gupta','Cat Specialist','Delhi',4.6,500,8),
('Dr. Rajesh Pillai','Dog Specialist','Bangalore',4.4,450,7),
('Dr. Sneha Kapoor','Dental Vet','Mumbai',4.8,700,11),
('Dr. Vikram Singh','Orthopedic','Delhi',4.9,900,14),
('Dr. Divya Rao','Exotic Pets','Chennai',4.3,550,6),
('Dr. Karthik Raj','General Vet','Coimbatore',4.5,300,8),
('Dr. Meera Joshi','Nutrition','Pune',4.6,400,9),
('Dr. Amit Deshmukh','Surgery Specialist','Mumbai',4.7,850,13);

INSERT INTO pet_items (name, category, price, stock, description) VALUES
('Pedigree Dog Food 3kg','Food',650,20,'Complete nutrition for adult dogs'),
('Whiskas Cat Food 1kg','Food',280,30,'Tuna flavor dry cat food'),
('Royal Canin Puppy 2kg','Food',890,15,'For puppies aged 2-10 months'),
('Chew Toy Bone','Toys',150,50,'Durable rubber chew toy'),
('Feather Cat Wand','Toys',180,40,'Interactive cat play toy'),
('Pet Shampoo 500ml','Grooming',220,25,'Anti-tick herbal shampoo'),
('Nail Clipper','Grooming',160,35,'Stainless steel pet nail clipper'),
('Grooming Brush','Grooming',250,28,'Self-cleaning slicker brush'),
('Leash & Collar Set','Accessories',400,15,'Nylon leash with padded collar'),
('Pet Bed Medium','Accessories',1200,10,'Soft plush washable pet bed'),
('Water Bowl Steel','Accessories',180,45,'Non-slip stainless steel bowl'),
('Pet Carrier Bag','Accessories',1500,8,'Ventilated travel carrier'),
('Deworming Tablets','Health',320,40,'Broad-spectrum dewormer'),
('Tick & Flea Spray','Health',450,22,'Fast-acting protection spray'),
('Multivitamin Syrup','Health',380,30,'Daily vitamin supplement'),
('Training Pads (50)','Hygiene',550,25,'Absorbent puppy training pads');
