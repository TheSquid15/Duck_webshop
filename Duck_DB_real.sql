/* DROP DATABASE IF EXISTS Duck_DB;
CREATE DATABASE Duck_DB;
USE Duck_DB;
 */
CREATE TABLE user (
    userID int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username varchar(255) NOT NULL,
    password varchar(255) NOT NULL,
    email varchar(255) NOT NULL,
    fname varchar(255),
    lname varchar(255) NOT NULL
);

CREATE TABLE postal_city (
    postalcode int NOT NULL PRIMARY KEY,
    city varchar(255) NOT NULL
);

CREATE TABLE address (
    addressID int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    street varchar(255) NOT NULL,
    country varchar(255) NOT NULL,
    userID int NOT NULL,
    postalcode int NOT NULL,
    FOREIGN KEY (userID) REFERENCES user (userID),
    FOREIGN KEY (postalcode) REFERENCES postal_city (postalcode)
);

CREATE TABLE about_US (
    slogan varchar(255),
    about_us_text LONGTEXT,
    addressID int NOT NULL,
    FOREIGN KEY (addressID) REFERENCES address (addressID)
); 

CREATE TABLE message (
    messageID int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    message LONGTEXT NOT NULL,
    time DATETIME NOT NULL
);

CREATE TABLE orders (
    orderID int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    time DATETIME NOT NULL,
    userID int NOT NULL,
    FOREIGN KEY (userID) REFERENCES user (userID)
);

CREATE TABLE category (
    categoryID int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    categoryName varchar(255) NOT NULL
);

CREATE TABLE product (
    productID int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name varchar(255),
    productImage varchar(255),
    price int NOT NULL,
    categoryID int NOT NULL,
    FOREIGN KEY (categoryID) REFERENCES category (categoryID)
);

CREATE TABLE basket (
    orderID int NOT NULL,
    productID int NOT NULL,
    CONSTRAINT PK_Basket PRIMARY KEY (orderID, productID),
    FOREIGN KEY (orderID) REFERENCES orders (orderID),
    FOREIGN KEY (productID) REFERENCES product (productID)
);

CREATE TABLE product_of_the_day (
    end_date DATETIME NOT NULL,
    percentage int NOT NULL,
    productID int NOT NULL,
    is_item_of_the_day BOOL NOT NULL,
    FOREIGN KEY (productID) REFERENCES product (productID)
);

CREATE TABLE news (
    newsID int NOT NULL PRIMARY KEY,
    title varchar(255) NOT NULL,
    message LONGTEXT,
    image varchar(255),
    time DATE NOT NULL
);

INSERT INTO category VALUES 
(NULL, "Weapons"),
(NULL, "Attire"),
(NULL, "Vehicles");

INSERT INTO product VALUES
(NULL, "Glock 17D", "../resources/duckGlock.png", 150, 1),
(NULL, "AR-15D", "../resources/duckAr.png", 230, 1),
(NULL, "Quack-Pack", "../resources/duckBackpack.png", 99, 2),
(NULL, "Duck-Timbs", "../resources/duckBoots.png", 75, 2),
(NULL, "Duck Vision Goggles", "../resources/duckGoggles.png", 99, 2),
(NULL, "UH-1Duck", "../resources/duckHeli.png", 450, 3),
(NULL, "Tactical Bread Slicer", "../resources/duckKnife.png", 35, 1),
(NULL, "Bread Blaster", "../resources/duckLauncher.png", 300, 1),
(NULL, "Dodge™ Duck", "../resources/duckVehicle.png", 400, 3);

INSERT INTO product_of_the_day VALUES
("2020-05-15 12:00:00", 40, 3, 1);

INSERT INTO user VALUES
(NULL, "manager", "cookies4me", "spiffy@gmail.com", "Richard", "Duck");