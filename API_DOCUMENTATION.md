# Lumière Cosmetic & Skincare Store API

## Base URL

`http://127.0.0.1:8000/api`

## Authentication

The API uses Laravel Sanctum Bearer Token authentication.

Protected endpoints require:

`Authorization: Bearer {token}`

---

## API Routes

| Method | Route | Description | Authentication |
|---|---|---|---|
| POST | `/api/register` | Register a new customer | Public |
| POST | `/api/login` | Login and receive Sanctum token | Public |
| POST | `/api/logout` | Logout current authenticated session | Bearer token |
| GET | `/api/user` | Get authenticated user | Bearer token |
| GET | `/api/profile` | Get customer profile | Bearer token |
| PUT | `/api/profile` | Update customer profile | Bearer token |
| GET | `/api/products` | Get all products / search products | Public |
| GET | `/api/products/{id}` | Get one product | Public |
| GET | `/api/categories` | Get all categories | Public |
| GET | `/api/categories/{id}/products` | Get products by category | Public |
| GET | `/api/orders` | Get authenticated customer's order history | Bearer token |
| POST | `/api/orders` | Create a new order | Bearer token |
| GET | `/api/orders/{id}` | Get one order | Bearer token |

---

# Authentication Endpoints

## Register

**POST** `/api/register`

Example request:

```json
{
  "name": "Test Customer",
  "email": "testcustomer@gmail.com",
  "password": "Test@1234",
  "password_confirmation": "Test@1234"
}
```

Strong password rules:

- Minimum 8 characters
- Uppercase letter
- Lowercase letter
- Number
- Special character
- No spaces

Successful response: `201 Created`

---

## Login

**POST** `/api/login`

```json
{
  "email": "testcustomer@gmail.com",
  "password": "Test@1234"
}
```

Successful login returns a Laravel Sanctum Bearer token.

---

## Logout

**POST** `/api/logout`

Header:

`Authorization: Bearer {token}`

---

## Authenticated User

**GET** `/api/user`

Header:

`Authorization: Bearer {token}`

---

# Product Endpoints

## All Products

**GET** `/api/products`

Search example:

`GET /api/products?search=serum`

## One Product

**GET** `/api/products/{id}`

Example:

`GET /api/products/1201`

---

# Category Endpoints

## All Categories

**GET** `/api/categories`

## Products by Category

**GET** `/api/categories/{id}/products`

Example:

`GET /api/categories/1/products`

---

# Profile Endpoints

## Get Profile

**GET** `/api/profile`

Header:

`Authorization: Bearer {token}`

## Update Profile

**PUT** `/api/profile`

Header:

`Authorization: Bearer {token}`

Example:

```json
{
  "name": "Lumière Customer",
  "phone": "098765432",
  "shipping_address": "Phnom Penh, Cambodia",
  "province": "Phnom Penh"
}
```

---

# Order Endpoints

## Get Order History

**GET** `/api/orders`

Header:

`Authorization: Bearer {token}`

## Create Order

**POST** `/api/orders`

Header:

`Authorization: Bearer {token}`

Example:

```json
{
  "customer_name": "Test Customer",
  "customer_phone": "098765432",
  "delivery_phone": "098765432",
  "delivery_address": "Phnom Penh, Cambodia",
  "delivery_province": "Phnom Penh",
  "contact_via_telegram": true,
  "payment_method": "cod",
  "items": [
    {
      "product_id": 1201,
      "selected_size": "30 ml",
      "quantity": 1
    }
  ]
}
```

Security design: Flutter sends the product ID, selected size, and quantity. Laravel reads trusted product prices from MySQL and calculates subtotal, shipping, discount, and total on the server.

## One Order

**GET** `/api/orders/{id}`

Header:

`Authorization: Bearer {token}`

---

# HTTP Status Codes

- `200 OK` — successful request
- `201 Created` — resource created successfully
- `401 Unauthorized` — missing/invalid authentication
- `404 Not Found` — resource not found
- `422 Unprocessable Entity` — validation error
- `500 Internal Server Error` — unexpected server error
