# Lumière API Route List

Current Laravel API routes verified with:

`php artisan route:list --path=api`

| # | Method | Endpoint | Controller |
|---:|---|---|---|
| 1 | GET | `/api/categories` | `Api\CategoryController@index` |
| 2 | GET | `/api/categories/{id}/products` | `Api\CategoryController@products` |
| 3 | POST | `/api/login` | `Api\AuthController@login` |
| 4 | POST | `/api/logout` | `Api\AuthController@logout` |
| 5 | GET | `/api/orders` | `Api\OrderController@index` |
| 6 | POST | `/api/orders` | `Api\OrderController@store` |
| 7 | GET | `/api/orders/{id}` | `Api\OrderController@show` |
| 8 | GET | `/api/products` | `Api\ProductController@index` |
| 9 | GET | `/api/products/{id}` | `Api\ProductController@show` |
| 10 | GET | `/api/profile` | `Api\ProfileController@show` |
| 11 | PUT | `/api/profile` | `Api\ProfileController@update` |
| 12 | POST | `/api/register` | `Api\AuthController@register` |
| 13 | GET | `/api/user` | `Api\AuthController@user` |

Total: **13 API routes**
