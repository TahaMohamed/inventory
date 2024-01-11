# A5dr Task

### Description:

You are to design and implement a RESTful API service using the Laravel framework. This API will serve as a
simple inventory management system, enabling users to add, update, delete, and retrieve items.

### Specific Requirements:
1. Database Integration:
   - Use SQL for storing inventory data.
   - Implement database migrations and seeders.
2. Object-Oriented Design:
   - Utilize OOP concepts in PHP effectively.
   - Implement the Repository design pattern to abstract and encapsulate all data access into separate
   classes.
3. API Development:
   - Create RESTful routes for CRUD operations on inventory items.
   - Implement validation and error handling.
4. Coding Standards and Best Practices:
   - Follow PSR standards for coding style.
   - Ensure the code is clean, organized, and reusable.
5. Comments and Documentation:
   - Add comprehensive comments explaining the code logic.
   - Document the API endpoints with examples of requests and responses.
6. Security Practices:
   - Implement secure coding practices to prevent SQL injection, cross-site scripting (XSS), and other
   common vulnerabilities.

7. Version Control:
   - Use Git for version control. Include a .gitignore file and a clear commit history.
8. Performance Optimization:
   - Optimize queries for speed and efficiency.

## Installation

1. Clone the repo and `cd YOUR-PROJECT` into it.
1. `composer install`.
1. Rename or copy `.env.example` file to `.env`.
1. Set database credentials in `.env` file.
1. `php artisan migrate --step --seed`.
1. `php artisan serve`.
1. To watch requests that sent to server go to `localhost:8000/telescope`
1. Use [Apidog Collection](https://fbjufdjek6.apidog.io) to run api services

## Authentication
To make login on server select `Server` environment and use these credentials:s
- Username: +18202327356,
- Password: 123456789
