<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Admin Dashboard</h1>

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        User Management
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item"><a href="{{ url('api/auth/users') }}">View Users</a></li>
                            <li class="list-group-item"><a href="{{ url('api/auth/delete_user/{id}') }}">Delete User</a>
                            </li>
                            <li class="list-group-item"><a href="{{ url('api/auth/update_user/{id}') }}">Update User</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Product Management
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item"><a href="{{ url('api/products') }}">Fetch All Products</a></li>
                            <li class="list-group-item"><a href="{{ url('api/products') }}"
                                    onclick="event.preventDefault(); document.getElementById('create-product-form').submit();">Create
                                    New Product</a></li>
                            <li class="list-group-item"><a href="{{ url('api/products/{id}') }}">Get Product Details</a>
                            </li>
                            <li class="list-group-item"><a href="{{ url('api/products/{id}') }}">Update Product</a></li>
                            <li class="list-group-item"><a href="{{ url('api/products/{id}') }}">Delete Product</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Order Management
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item"><a href="{{ url('api/orders') }}">Fetch All Orders</a></li>
                            <li class="list-group-item"><a href="{{ url('api/orders/{id}') }}">Get Order Details</a>
                            </li>
                            <li class="list-group-item"><a href="{{ url('api/orders/user/{userId}') }}">Get User
                                    Orders</a></li>
                            <li class="list-group-item"><a href="{{ url('api/orders/{id}/cancel') }}">Cancel Order</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Category Management
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item"><a href="{{ url('api/categories') }}">List All Categories</a>
                            </li>
                            <li class="list-group-item"><a href="{{ url('api/categories') }}"
                                    onclick="event.preventDefault(); document.getElementById('create-category-form').submit();">Create
                                    New Category</a></li>
                            <li class="list-group-item"><a href="{{ url('api/categories/{id}') }}">Get Category</a></li>
                            <li class="list-group-item"><a href="{{ url('api/categories/{id}') }}">Update Category</a>
                            </li>
                            <li class="list-group-item"><a href="{{ url('api/categories/{id}') }}">Delete Category</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Review Management
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item"><a href="{{ url('api/reviews/submit') }}">Submit Review</a></li>
                            <li class="list-group-item"><a href="{{ url('api/reviews/{product_id}') }}">Get Reviews</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Promo Code Management
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item"><a href="{{ url('api/promo-codes/add') }}">Add Promo Code</a>
                            </li>
                            <li class="list-group-item"><a href="{{ url('api/promo-codes/apply/{order_id}') }}">Apply
                                    Promo Code</a></li>
                            <li class="list-group-item"><a href="{{ url('api/promo-codes/remove/{order_id}') }}">Remove
                                    Promo Code</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Wishlist Management
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item"><a href="{{ url('api/wishlist/add') }}">Add to Wishlist</a></li>
                            <li class="list-group-item"><a href="{{ url('api/wishlist/{user_id}') }}">Get Wishlist</a>
                            </li>
                            <li class="list-group-item"><a href="{{ url('api/wishlist/remove') }}">Remove from
                                    Wishlist</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Cart Management
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item"><a href="{{ url('api/cart/add') }}">Add to Cart</a></li>
                            <li class="list-group-item"><a href="{{ url('api/cart/remove') }}">Remove from Cart</a></li>
                            <li class="list-group-item"><a href="{{ url('api/cart/update') }}">Update Cart</a></li>
                            <li class="list-group-item"><a href="{{ url('api/cart/get/{user_id}') }}">Get Cart</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>