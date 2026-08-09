# ⚡ Electricity Payment

**Electricity Payment** is a web-based application designed to manage electricity customers, bills, payments, and customer feedback.

This application was developed using **Native PHP, MySQL, Bootstrap**, and **XAMPP**.

---

## ✨ Features

### 👨‍💼 Admin

Administrators can:

- Log in as an administrator
- View the total number of customers
- View the electricity price per kWh
- View the number of unpaid bills
- Add customer data
- Edit customer data
- Delete customer data
- Add electricity bills
- Update payment status
- View customer feedback
- Log out

### 👤 User / Customer

Customers can:

- Log in using their email and password
- View the dashboard
- View their total unpaid bills
- View electricity bill details
- Check payment status
- Submit feedback and suggestions
- View their personal information
- Log out

---

## 🛠️ Technologies

- **PHP Native**
- **MySQL**
- **Bootstrap 5**
- **HTML5**
- **CSS3**
- **JavaScript**
- **XAMPP / Apache**

---

## 📁 Project Structure

```text
electricity-payment/
│
├── admin.php
├── user.php
├── login.php
│
├── home.php
├── home_user.php
│
├── cust.php
├── tagihan.php
├── tagihan_user.php
│
├── feedback.php
├── feedback_user.php
├── me.php
│
├── logout.php
├── logout_user.php
│
├── connect.php
├── database.sql
│
├── style.css
│
├── bootstrap/
│   ├── css/
│   └── js/
│
└── README.md
💾 Database

The application uses a MySQL database named:

listrik

The database consists of the following tables:

listrik
│
├── admin
├── konsumen
├── tagihan
└── feedback
admin

Stores administrator account information.

konsumen

Stores customer account and personal information.

tagihan

Stores electricity billing information, including:

Customer email
Electricity usage
Billing period
Bill amount
Payment deadline
Payment status

The payment status column is:

pembayaran

with the following values:

Lunas
Belum Lunas
feedback

Stores feedback and suggestions submitted by customers.

🚀 Installation
1. Install XAMPP

Make sure XAMPP is installed on your computer.

Start the following services:

Apache → Start
MySQL  → Start
2. Place the Project in htdocs

Place the project folder inside:

C:\xampp\htdocs\

The final project path should be:

C:\xampp\htdocs\electricity-payment
3. Import the Database

Open phpMyAdmin:

http://localhost/phpmyadmin

Then:

Create a database named listrik
Select the listrik database
Open the Import menu
Select database.sql
Click Go
4. Configure the Database Connection

Open:

connect.php

Make sure the configuration matches your XAMPP setup:

<?php

$host = "localhost";
$user = "root";
$pass = "";
$db   = "listrik";

$koneksi = new mysqli(
    $host,
    $user,
    $pass,
    $db
);

if ($koneksi->connect_error) {
    die(
        "Database connection failed: "
        . $koneksi->connect_error
    );
}

$koneksi->set_charset("utf8mb4");

?>
▶️ Running the Project

After Apache and MySQL are running, open:

http://localhost/electricity-payment/

Or directly access the login page:

http://localhost/electricity-payment/login.php
🔐 Test Accounts
Admin Account
Username : admin
Password : admin123
User Account
Email    : budi@gmail.com
Password : 123456

These accounts are provided for application testing purposes.

🔄 Application Flow
                         LOGIN
                           │
                 ┌─────────┴─────────┐
                 │                   │
               ADMIN                USER
                 │                   │
                 ▼                   ▼
             home.php          home_user.php
                 │                   │
          ┌──────┼──────┐      ┌────┼────┐
          │      │      │      │    │    │
          ▼      ▼      ▼      ▼    ▼    ▼
       Customer Bills Feedback Bills Feedback Profile
          │      │      │
          └──────┴──────┘
                 │
                 ▼
              Database
               listrik
💳 Billing System

Administrators can create electricity bills based on:

Customer
Electricity usage in kWh
Billing period
Bill amount
Payment deadline
Payment status

The bill amount is calculated based on:

Electricity Usage × Price per kWh

Payment statuses include:

Belum Lunas
Lunas

If an unpaid bill has passed its payment deadline, the application can display:

Terlambat
📩 Customer Feedback

Customers can submit feedback through:

feedback_user.php

Submitted feedback is stored in the database and can be viewed by administrators through:

feedback.php
📱 Responsive Design

The application is designed to work across different screen sizes, including:

💻 Desktop
💻 Laptop
📱 Tablet
📱 Mobile

Bootstrap is used to support the responsive layout.

🔒 Logout

Administrators use:

logout.php

Customers use:

logout_user.php

The logout process destroys the current login session and redirects the user back to the login page.

⚠️ Security Note

This project currently uses plain-text passwords for learning and testing purposes.

For a production application, passwords should be securely stored using:

password_hash()

and verified using:

password_verify()
👩‍💻 Development

Project Name: Electricity Payment

Project Folder:

electricity-payment

Database:

listrik
📄 License

This project was developed for educational purposes and for managing electricity payment and customer information.
