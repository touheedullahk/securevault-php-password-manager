# SecureVault Password Manager

SecureVault is a simple PHP OOP web application for generating and securely storing passwords in a MySQL database.

## Features

- User registration and login
- Hashed login passwords
- Password generator with selected character quantities
- Password strength feedback
- Encrypted storage for saved passwords
- Save generated or existing passwords
- View, copy and remove saved records
- Change login password while keeping access to stored passwords

## Technologies Used

- PHP
- MySQL / phpMyAdmin
- HTML, CSS and JavaScript
- XAMPP for local server setup

## Setup Instructions

1. Copy the project folder into the XAMPP `htdocs` folder.
2. Start **Apache** and **MySQL** in XAMPP.
3. Open phpMyAdmin.
4. Import the file:

```text
database/schema.sql
```

5. Open the program in a browser:

```text
http://localhost/securevault-php-password-manager/public/
```

## How to Use

1. Create an account.
2. Log in.
3. Generate a new password or add an existing password.
4. Save the password with a website or program name.
5. Open **Saved Vault** to view stored records.
6. Use **Change Login Password** when needed.

## Project Structure

```text
classes/          PHP OOP classes
config/           Database configuration
database/         SQL database files
documentation/    UML, ERD and testing notes
public/           Application pages and assets
README.md         Project instructions
```

## Security Notes

- Login passwords are stored in hashed form.
- Saved passwords are stored in encrypted form.
- Each user has a protected vault key for password encryption.
- The vault key remains unchanged when the login password is updated.

## Database Tables

- `users` — user accounts, password hashes and encrypted vault keys
- `password_records` — saved website/program passwords in encrypted form

## Testing

The program should be tested for:

- Registration and login
- Password generation
- Encrypted password saving
- Viewing and removing saved records
- Login password change
- Encrypted data shown in phpMyAdmin

## Final Files

The completed submission includes:

- PHP project source code
- MySQL database script
- UML and database diagrams
- Test report PDF
- GitHub repository with project commits
