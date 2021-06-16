CREATE DATABASE IF NOT EXISTS padariaCripto;

USE padariaCripto;

CREATE TABLE IF NOT EXISTS user(
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name varchar(255),
    password varchar(255),
    birth date,
    login varchar(255) NOT NULL,
    cpf varchar(11),
    mail varchar(255),
    type varchar(255),
    active boolean
);

CREATE TABLE IF NOT EXISTS product(
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name varchar(255),
    description varchar(500),
    shortDescription varchar(255),
    price double DEFAULT '0.00',
    quantityAvailable double DEFAULT '0.00',
    image longblob NULL,
    image_type VARCHAR(255) NULL,
    active boolean
);

CREATE TABLE IF NOT EXISTS orders(
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id int NOT NULL,
    created_at datetime,
    status varchar(255),
    FOREIGN KEY (user_id) REFERENCES user(id)
);

CREATE TABLE IF NOT EXISTS product_order(
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    product_id int NOT NULL,
    order_id int NOT NULL,
    quantity double,
    price double,
    FOREIGN KEY (product_id) REFERENCES product(id),
    FOREIGN KEY (order_id) REFERENCES orders(id)
);