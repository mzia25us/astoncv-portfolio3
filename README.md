# AstonCV Portfolio 3

AstonCV is a dynamic CV database web application developed for the **DG1IAD Internet Applications and Databases** module at Aston University.

The system allows public users to browse and search programmer CVs, while registered users can create an account, log in, and update their own CV details. The application was developed using **PHP, MySQL, HTML, and CSS** and demonstrates key server-side development concepts together with core web security practices.

## Live Website

**Deployed URL:**  
http://250118283.cs2410-web01pvm.aston.ac.uk/

## Technologies Used

- PHP
- MySQL
- HTML
- CSS

## Main Features

- View all CVs
- View full CV details
- Search CVs by name or key programming language
- Register a new user account
- Log in to the system
- Update your own CV details
- Log out securely

## Security Features

- Authentication using sessions
- Authorisation for protected pages
- Password hashing with `password_hash()` and `password_verify()`
- SQL injection prevention using prepared statements
- CSRF protection using tokens
- XSS prevention using `htmlspecialchars()`
- Server-side form validation
- Session regeneration after login
- Session timeout after inactivity
- Secure URL validation and handling

## Main Files

- `index.php` – displays all CVs
- `viewcv.php` – displays full CV details
- `search.php` – searches CVs
- `register.php` – handles user registration
- `login.php` – handles user login
- `updatecv.php` – allows logged-in users to update their CV
- `logout.php` – securely logs users out
- `csrf.php` – generates and verifies CSRF tokens
- `database.php` – handles database connection
- `cvs.sql` – database structure and sample data

## Test Account

Use the following account to test logged-in functionality:

- **Email:** `daniel.moore.web@gmail.com`
- **Password:** `dannYweb26!`

## Notes

- The project was tested using **Google Chrome**.
- The application is deployed on the Aston host server.
- The SQL file is included for database structure and sample data reference.

## Author

**Mahnoor Zia**  
