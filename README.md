# Laravel Online Exam Application

- Application to manage and take the exam online.
- Teacher can create test, manage tests and students.
- Students can apply for the test and view results and answer sheets.



# Installation

1. **Clone or download this Repository.**
2. **Run the command**
   ```
   composer install
   ```
   if you get any problems while running above command then run the following command.
   ```
   composer install --ignore-platform-reqs
   ```

3. **Create `.env` file by copying the `.env.example`, or run the following command**
   ```
   cp .env.example .env
   ```

4. **Update the database name and credentials in `.env` file**  
   ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE="Your database name"
    DB_USERNAME="your database username"
    DB_PASSWORD="your database password"
   ```
5. **Run the following command**
   ```
   php artisan migrate --seed
   ```
6. **Run npm command**
   ```
   npm install
   ```
7. **Run the command to compile the theme**
    ```
    npm run dev
    ```
8. **Finally run the application**
   ```
   php artisan serve
   ```
   Teacher login
   email: teacher@exams.com
   password : password

   Student Login
   email: student@gmail.com',
   password : password


# Packages

Theme Creation.
   - [Qirolab](https://github.com/qirolab/laravel-themer)





