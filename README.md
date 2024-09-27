# TCG Player Clone

## Description
This project is a clone of the TCG Player website, focusing on displaying and managing trading cards from games such as Magic: The Gathering, Pokémon, Yu-Gi-Oh!, and other trading card games.

## Features
- Display a list of trading cards
- Search and filter cards
- View card details
- Shopping cart management (in development)
- User authentication and registration (in development)

## Technologies Used
- PHP 7.4+
php
$host = 'localhost';
$dbname = 'tcg_database';
$username = 'your_username';
$password = 'your_password';
## Directory Structure
project/
│
├── includes/
│ ├── header.php
│ ├── footer.php
│ └── db_connect.php
│
├── public/
│ ├── css/
│ │ ├── main.css
│ │ ├── style.css
│ │ └── sub-page.css
│ │
│ ├── js/
│ │ └── main.js
│ │
│ └── images/
│ └── product/
│
├── views/
│ └── one_piece.php
│
├── index.php
└── README.md
## Database Schema
The main table used in this project is `cards`:
sql
CREATE TABLE cards (
id INT PRIMARY KEY AUTO_INCREMENT,
name VARCHAR(255) NOT NULL,
image_filename VARCHAR(255) NOT NULL,
product_details TEXT,
rarity VARCHAR(50),
card_number VARCHAR(50),
color VARCHAR(50),
card_type VARCHAR(50),
cost VARCHAR(50),
power VARCHAR(50),
subtype VARCHAR(100),
attribute VARCHAR(50),
artist VARCHAR(100),
price DECIMAL(10, 2),
set_id INT
);

## Usage
- Navigate to `index.php` to view the home page
- Visit `views/one_piece.php` to see the One Piece card listing

## Contributing
Contributions are welcome. Please open an issue or submit a pull request to contribute.

## License
[MIT License](https://opensource.org/licenses/MIT)