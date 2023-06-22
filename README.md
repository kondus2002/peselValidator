# PESEL Validation & Employee Management App

This repository contains the source code for an PESEL Validation & Employee Management App. It provides a simple interface to manage employee data.

## Setup

To set up the app, follow the steps below:

1. Import the Database:
   - The `/src/db` directory contains a `.sql` file that includes the "pracownicy" table.
   - Import this file into the database you are using for the app.

2. Configure the Database Connection:
   - Open the `/src/public/index.php` file.
   - Locate the `//DB Config` section.
   - Update the following code snippet with your database connection details:

   ```php
   $config['db']['host'] = 'host';
   $config['db']['user'] = 'user';
   $config['db']['pass'] = 'password';
   $config['db']['dbname'] = 'dbname';
   
Replace `host`, `user`, `password`, and `dbname` with your actual database connection information.

3. Launch the App:
   - Start your local development environment (e.g., Apache server, PHP, etc.).
   - Access the app by navigating to `localhost/{appname}/src/public/` in your web browser.
   - You should now be able to use the Employee Management App!

## Contributing

If you'd like to contribute to this project, please follow these guidelines:

  1. Fork the repository on GitHub.
  2. Make your changes in a new branch.
  3. Ensure that your code follows the project's coding conventions.
  4. Test your changes thoroughly.
  5. Commit and push your changes to your forked repository.
  6. Create a new pull request, describing the changes you made.

## License

This project is licensed under the [MIT License](https://opensource.org/license/mit/).

Feel free to use, modify, and distribute this code according to the terms of the license.
