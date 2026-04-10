# Product Inventory Management System

A comprehensive RESTful API for managing product inventory, built with Laravel 11, Docker, Redis, and PostgreSQL.

## Features
- Full Product CRUD: Create, Read, Update, and Soft Delete.
- Advanced Filtering & Pagination.
- Stock Management: Adjust stock quantities and low-stock alerts.
- Performance Optimization: Redis caching for search results and product details.
- Consistent API Responses: Standardized JSON format with proper error handling.
- Automated Testing: Comprehensive feature tests for all endpoints.

---

## Prerequisites
The only requirement for running this project is:
- [Docker Desktop](https://www.docker.com/products/docker-desktop)

**Note:** You do NOT need PHP or Composer installed locally. Everything runs inside Docker containers.

---

## Getting Started (Docker Only)

### 1. Clone the Project
Download or clone the repository to your local machine.

### 2. Set Up Environment Variables
Copy the `.env.example` file to create a new `.env` file:
```bash
cp .env.example .env
```

### 3. Build and Start the Containers
Run the following command to build the image and start the services. This process will automatically install all PHP dependencies (the `vendor` folder) inside the container:
```bash
docker-compose up -d --build
```
This will start:
- **Laravel App:** PHP-FPM (where your code and `vendor` folder live)
- **Nginx:** Web server (accessible at [http://localhost:8000](http://localhost:8000))
- **PostgreSQL:** Database
- **Redis:** Cache

### 4. Generate Application Key
```bash
docker-compose exec app php artisan key:generate
```

### 5. Run Database Migrations
Create the necessary database tables:
```bash
docker-compose exec app php artisan migrate
```

---

## FAQ

**Q: Where is the `vendor` folder?**  
**A:** The `vendor` folder is automatically generated inside the Docker container when you run `docker-compose up --build`. It is usually ignored in Git and doesn't need to exist on your local machine for the app to work inside Docker.

**Q: Do I need to install Composer locally?**  
**A:** No. Composer is included in the Docker image. If you ever need to run a composer command (like `composer require`), you can do it through Docker:
```bash
docker-compose exec app composer <your-command>
```

---

## API Endpoints

| Feature | Endpoint (URL) | Method |
| :--- | :--- | :--- |
| List All Products | `/api/products` | `GET` |
| Get Single Product | `/api/products/{id}` | `GET` |
| Create Product | `/api/products` | `POST` |
| Update Product | `/api/products/{id}` | `PUT` |
| Delete Product (Soft Delete) | `/api/products/{id}` | `DELETE` |
| Adjust Stock | `/api/products/{id}/stock` | `POST` |
| List Low Stock Products | `/api/products/low-stock` | `GET` |

---

## Running Tests
To ensure everything is working correctly, run the automated tests inside the container:
```bash
docker-compose exec app php artisan test
```

---

## Technical Notes
- **Caching:** Search results and product details are cached in Redis for 1 hour. The cache is automatically invalidated when a product is created or updated.
- **Filtering:** You can filter products using query parameters: `name`, `sku`, `status`, `min_price`, `max_price`.
  - Example: `/api/products?status=active&min_price=100`

---
Developed with the help of Trae IDE AI Assistant.
