<<<<<<< HEAD
/* DROP DATABASE IF EXISTS Duck_DB;
CREATE DATABASE Duck_DB;
USE Duck_DB;
 */
CREATE TABLE user (
=======
DROP DATABASE IF EXISTS Duck_DB;
CREATE DATABASE Duck_DB;
USE Duck_DB;

CREATE TABLE User (
>>>>>>> e2d15aa780a54a20a9aef31367f478be6b952653
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

<<<<<<< HEAD
CREATE TABLE address (
=======
CREATE TABLE Address (
>>>>>>> e2d15aa780a54a20a9aef31367f478be6b952653
    addressID int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    street varchar(255) NOT NULL,
    country varchar(255) NOT NULL,
    userID int NOT NULL,
    postalcode int NOT NULL,
<<<<<<< HEAD
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
=======
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
>>>>>>> e2d15aa780a54a20a9aef31367f478be6b952653
    messageID int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    message LONGTEXT NOT NULL,
    time DATETIME NOT NULL
);

<<<<<<< HEAD
CREATE TABLE orders (
    orderID int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    time DATETIME NOT NULL,
    userID int NOT NULL,
    FOREIGN KEY (userID) REFERENCES user (userID)
);

CREATE TABLE category (
=======
CREATE TABLE Orders (
    orderID int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    time DATETIME NOT NULL,
    userID int NOT NULL,
    FOREIGN KEY (userID) REFERENCES User (userID)
);

CREATE TABLE Category (
>>>>>>> e2d15aa780a54a20a9aef31367f478be6b952653
    categoryID int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    categoryName varchar(255) NOT NULL
);

<<<<<<< HEAD
CREATE TABLE product (
=======
CREATE TABLE Product (
>>>>>>> e2d15aa780a54a20a9aef31367f478be6b952653
    productID int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name varchar(255),
    productImage varchar(255),
    price int NOT NULL,
    categoryID int NOT NULL,
<<<<<<< HEAD
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
=======
    FOREIGN KEY (categoryID) REFERENCES Category (categoryID)
);

CREATE TABLE Basket (
    orderID int NOT NULL,
    productID int NOT NULL,
    CONSTRAINT PK_Basket PRIMARY KEY (orderID, productID),
    FOREIGN KEY (orderID) REFERENCES Orders (orderID),
    FOREIGN KEY (productID) REFERENCES Product (productID)
);

CREATE TABLE Product_of_the_day (
>>>>>>> e2d15aa780a54a20a9aef31367f478be6b952653
    end_date DATETIME NOT NULL,
    percentage int NOT NULL,
    productID int NOT NULL,
    is_item_of_the_day BOOL NOT NULL,
<<<<<<< HEAD
    FOREIGN KEY (productID) REFERENCES product (productID)
);

CREATE TABLE news (
=======
    FOREIGN KEY (productID) REFERENCES Product (productID)
);

CREATE TABLE News (
>>>>>>> e2d15aa780a54a20a9aef31367f478be6b952653
    newsID int NOT NULL PRIMARY KEY,
    title varchar(255) NOT NULL,
    message LONGTEXT,
    image varchar(255),
    time DATE NOT NULL
);

<<<<<<< HEAD
INSERT INTO category VALUES 
=======
INSERT INTO Category VALUES 
>>>>>>> e2d15aa780a54a20a9aef31367f478be6b952653
(NULL, "Weapons"),
(NULL, "Attire"),
(NULL, "Vehicles");

<<<<<<< HEAD
INSERT INTO product VALUES
=======
INSERT INTO Product VALUES
>>>>>>> e2d15aa780a54a20a9aef31367f478be6b952653
(NULL, "Glock 17D", "../resources/duckGlock.png", 150, 1),
(NULL, "AR-15D", "../resources/duckAr.png", 230, 1),
(NULL, "Quack-Pack", "../resources/duckBackpack.png", 99, 2),
(NULL, "Duck-Timbs", "../resources/duckBoots.png", 75, 2),
(NULL, "Duck Vision Goggles", "../resources/duckGoggles.png", 99, 2),
(NULL, "UH-1Duck", "../resources/duckHeli.png", 450, 3),
(NULL, "Tactical Bread Slicer", "../resources/duckKnife.png", 35, 1),
(NULL, "Bread Blaster", "../resources/duckLauncher.png", 300, 1),
(NULL, "Dodge™ Duck", "../resources/duckVehicle.png", 400, 3);

<<<<<<< HEAD
INSERT INTO product_of_the_day VALUES
("2020-05-15 12:00:00", 40, 3, 1);

INSERT INTO user VALUES
=======
INSERT INTO Product_of_the_day VALUES
("2020-05-15 12:00:00", 40, 3, 1);

INSERT INTO User VALUES
>>>>>>> e2d15aa780a54a20a9aef31367f478be6b952653
(NULL, "manager", "cookies4me", "spiffy@gmail.com", "Richard", "Duck");