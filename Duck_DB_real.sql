DROP DATABASE IF EXISTS Duck_DB;
CREATE DATABASE Duck_DB;
USE Duck_DB;

CREATE TABLE user (
    userID int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username varchar(255) NOT NULL,
    password varchar(255) NOT NULL,
    email varchar(255) NOT NULL,
    fname varchar(255),
    lname varchar(255) NOT NULL
);

INSERT INTO user VALUES
(NULL, "manager", "$2y$10$lG8hNIHlJx1jodagjm5BteSLKDrcmiIdlof1A2qNh9ZjSwMT9W.Te", "bing@bong.com", "Johnathon", "Duck");

CREATE TABLE postal_city (
    postalcode int NOT NULL,
    city varchar(255) NOT NULL,
    CONSTRAINT PK_postal_code PRIMARY KEY (postalcode, city)
);

INSERT INTO postal_city VALUES
(6700, "Esbjerg");

CREATE TABLE address (
    addressID int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    street varchar(255) NOT NULL,
    country varchar(255) NOT NULL,
    userID int NOT NULL,
    postalcode int NOT NULL,
    city varchar(255) NOT NULL,
    FOREIGN KEY (userID) REFERENCES user (userID),
    CONSTRAINT fk_postal_city FOREIGN KEY (postalcode, city) REFERENCES postal_city (postalcode, city)
);

INSERT INTO address VALUES
(NULL, "Fynsgade 4", "Denmark", 1, 6700, "Esbjerg"),
(NULL, "Spangsbjerg Kirkevej 109", "Denmark", 1, 6700, "Esbjerg");

CREATE TABLE about_Us (
    slogan varchar(255),
    about_us_text LONGTEXT,
    email varchar(255),
    addressID int NOT NULL,
    FOREIGN KEY (addressID) REFERENCES address (addressID)
); 

INSERT INTO about_Us VALUES
("Get ducked!", "Some info on us", "DuckTeam@Quack-Tac.com", 1);

CREATE TABLE message (
    messageID int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    subject varchar(255) NOT NULL,
    message LONGTEXT NOT NULL,
    time DATETIME DEFAULT CURRENT_TIMESTAMP(),
    email varchar(255) NOT NULL
);

INSERT INTO message VALUES
(NULL, "Jammed rifle", "Hey guys, I was trying to shoot the old lady that keeps feeding the pigeons but my rifle jammed and I can't unjamm it! Could you please repair it or send a replacement, this bitch has to die ASAP.", DEFAULT, "v.vignisson@gmail.com");

CREATE TABLE orders (
    orderID int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    time DATETIME DEFAULT CURRENT_TIMESTAMP() NOT NULL,
    userID int NOT NULL,
    FOREIGN KEY (userID) REFERENCES user (userID)
);

INSERT INTO orders VALUES
(1, DEFAULT, 1),
(2, DEFAULT, 1);

CREATE TABLE category (
    categoryID int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    categoryName varchar(255) NOT NULL
);

INSERT INTO category VALUES 
(NULL, "Weapons"),
(NULL, "Attire"),
(NULL, "Vehicles");

CREATE TABLE product (
    productID int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name varchar(255),
    productImage varchar(255),
    price int NOT NULL,
    categoryID int NOT NULL,
    FOREIGN KEY (categoryID) REFERENCES category (categoryID)
);

INSERT INTO product VALUES
(NULL, "Glock 17D", "duckGlock.png", 150, 1),
(NULL, "AR-15D", "duckAr.png", 230, 1),
(NULL, "Quack-Pack", "duckBackpack.png", 99, 2),
(NULL, "Duck-Timbs", "duckBoots.png", 75, 2),
(NULL, "Duck Vision Goggles", "duckGoggles.png", 99, 2),
(NULL, "UH-1Duck", "duckHeli.png", 450, 3),
(NULL, "Tactical Bread Slicer", "duckKnife.png", 35, 1),
(NULL, "Bread Blaster", "duckLauncher.png", 300, 1),
(NULL, "Dodge™ Duck", "duckVehicle.png", 400, 3);

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

INSERT INTO product_of_the_day VALUES
("2020-06-15 12:00:00", 40, 3, 1);

CREATE TABLE news (
    newsID int NOT NULL PRIMARY KEY,
    title varchar(255) NOT NULL,
    message LONGTEXT,
    image varchar(255),
    time DATETIME DEFAULT CURRENT_TIMESTAMP() NOT NULL
);

INSERT INTO news VALUES
(NULL, "War declared on Duckie's Kingdom", 
"The leader of the republic of Duckie's Kingdom was seen executing 
Bill-land soldiers in the south-eastern jungles of Duckie's Kingdom. 
This is a declaration of War!", "duckiesSnapshot.png", DEFAULT),
(NULL, "Duckie has been caught", "Recent video has surfaced of Duckie, the former 
leader of Duckie's kingdom, being taken prisoner by a local rebel group. More 
information as the story unfolds.", "duckiesSnapshot.png", DEFAULT);

CREATE VIEW products_full_information AS
SELECT DISTINCT p.productID, p.name, p.productImage, p.price, p.categoryID, c.categoryName, pd.end_date, pd.percentage, pd.is_item_of_the_day
FROM product p
JOIN category c ON p.categoryID = c.categoryID
LEFT JOIN product_of_the_day pd ON p.productID = pd.productID
ORDER BY name ASC;

CREATE VIEW order_overview AS
SELECT b.orderID, b.productID, p.name, p.price, ca.categoryName, o.time, u.userID, u.username, u.email, u.fname, u.lname, ad.street, ad.country, ad.postalcode, ad.city
FROM basket b
JOIN orders o ON o.orderID = b.orderID
JOIN product p ON p.productID = b.productID
JOIN category ca ON ca.categoryID = p.categoryID
JOIN user u ON u.userID = o.userID
JOIN address ad ON ad.userID = u.userID
ORDER BY orderID ASC;