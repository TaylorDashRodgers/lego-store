-- Select the database
USE tdrodgers;

-- Drop existing tables
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS customer;
DROP TABLE IF EXISTS product;
DROP TABLE IF EXISTS users;

-- Create the CUSTOMER table
CREATE TABLE customer (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255),
    last_name VARCHAR(255),
    email VARCHAR(255)
);

-- Create the PRODUCT table
CREATE TABLE product (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(255),
    image_name VARCHAR(255),
    price DECIMAL(6, 2),
    quantity_in_stock INT,
    inactive TINYINT NOT NULL DEFAULT 0
);

-- Create the ORDERS table
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    customer_id INT,
    quantity_purchased INT,
    price DECIMAL(6, 2),
    tax DECIMAL(6, 2),
    donation DECIMAL(4, 2),
    time BIGINT
);

-- Create the USERS table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(255),
    last_name VARCHAR(255),
    password VARCHAR(255),
    email VARCHAR(255),
    role INT
);

-- Populate the CUSTOMER table
INSERT INTO customer (first_name, last_name, email) VALUES
    ('Mickey', 'Mouse', 'mmouse@mines.edu'),
    ('Taylor', 'Rodgers', 'tdrodgers@mines.edu');

-- Populate the PRODUCT table
INSERT INTO product (product_name, price, quantity_in_stock, image_name, inactive) VALUES
    ('Venator', 649.99, 0, 'venator.png', 0),
    ('AT-AT', 849.99, 3, 'at_at.png', 0),
    ('Millennium Falcon', 849.99, 10, 'millennium.png', 0);

-- Populate the USERS table
INSERT INTO users (first_name, last_name, password, email, role)
VALUES ('Frodo', 'Baggins', 'fb', 'fb@mines.edu', 1),
       ('Harry', 'Potter', 'hp', 'hp@mines.edu', 2);