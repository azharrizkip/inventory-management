
**Inventory Management System README**

This repository contains the source code for an Inventory Management System developed using Laravel. The system allows users to manage transactions, products, and serial numbers, and is designed to help businesses keep track of their inventory-related activities.

## Features

-   Manage transactions: Add, edit, and delete transactions for sales and purchases.
-   Manage products: Add, edit, and delete product information.
-   Manage serial numbers: Keep track of serial numbers for products.
-   Generate transaction numbers: Automatic generation of transaction numbers in a specific format.
-   Calculate total price: Automatically calculate total price based on product price and discount.
-   Display reports: Display sales and purchases reports, daily profit reports, and more.

## Installation

1.  Clone the repository to your local machine:
    `git clone  https://github.com/azharrizkip/inventory-management.git`

2.  Navigate to the project directory:

    `cd inventory-management`

3.  Install dependencies using Composer:
`composer install`

4.  Create a  `.env`  file by copying  `.env.example`:
`cp .env.example .env`

5.  Generate an application key:
`php artisan key:generate`

6.  Configure the database connection in the  `.env`  file:
    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_username
    DB_PASSWORD=your_database_password
    ```

7.  Run database migrations and seeders to create tables and sample data:
`php artisan migrate --seed`

8.  Install npm dependencies:
 `npm install`

9.  Compile assets:
 `npm run dev`

10.  Start the development server:
 `php artisan serve`

11.  Access the application in your web browser at  `http://localhost:8000`.

## Usage

-   Login as an administrator to manage products and transactions.
-   Create new transactions for sales or purchases and specify relevant details.
-   View sales and purchases reports for insights into your inventory activities.

## Contributing

Contributions are welcome! If you find any issues or have suggestions for improvements, feel free to open an issue or submit a pull request.

## License

This project is open-source and available under the  [MIT License](https://dillinger.io/LICENSE).

## Credits

This project was developed by Azhar Rizki Pratama. Special thanks to the Laravel community for providing a robust framework.

