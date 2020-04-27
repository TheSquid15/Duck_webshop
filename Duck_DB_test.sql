DROP DATABASE IF EXISTS Duck_DB;
CREATE DATABASE Duck_DB;
USE Duck_DB;

CREATE TABLE User (
    userID int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username varchar(255) NOT NULL,
    password varchar(255) NOT NULL,
    email varchar(255) NOT NULL,
    fname varchar(255),
    lname varchar(255) NOT NULL
);

CREATE TABLE postal_city (
    postalcode int NOT NULL PRIMARY KEY,
    city varchar NOT NULL
);

CREATE TABLE Address (
    addressID int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    street varchar(255) NOT NULL,
    country varchar(255) NOT NULL,
    userID int NOT NULL,
    postalcode int NOT NULL,
    FOREIGN KEY (userID) REFERENCES User (userID),
    FOREIGN KEY (postalcode) REFERENCES postal_city (postalcode)
);

CREATE TABLE About_US (
    slogan varchar(255),
    about_us_text LONGTEXT,
    addressID int NOT NULL,
    FOREIGN KEY (addressID) REFERENCES Address (addressID)
); 

CREATE TABLE Message (
    messageID int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    message LONGTEXT NOT NULL,
    time DATETIME NOT NULL
);

CREATE TABLE Order (
    orderID int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    timestamp DATETIME NOT NULL,
    userID int NOT NULL,
    FOREIGN KEY (userID) REFERENCES User (userID)
);

CREATE TABLE Category (
    categoryID int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    categoryName
);

CREATE TABLE Product (
    productID int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name varchar(255),
    productImage varchar(255),
    categoryID int NOT NULL,
    FOREIGN KEY (categoryID) REFERENCES Category (categoryID)
);

CREATE TABLE Basket (
    orderID int NOT NULL,
    productID int NOT NULL,
    CONSTRAINT PK_Basket PRIMARY KEY (orderID, productID),
    FOREIGN KEY (orderID) REFERENCES Order (orderID),
    FOREIGN KEY (productID) REFERENCES Product (productID)
);

CREATE TABLE Product_of_the_day (
    end_date DATETIME NOT NULL,
    percentage int NOT NULL,
    productID int NOT NULL,
    FOREIGN KEY (productID) REFERENCES Product (productID)
);

CREATE TABLE News (
    newsID int NOT NULL PRIMARY KEY,
    title varchar(255) NOT NULL,
    message LONGTEXT,
    image varchar(255),
    timestamp DATE NOT NULL
);