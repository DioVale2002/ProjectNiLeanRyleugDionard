<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Bookstore</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    <div class="account-container">
        <h2>Your Account</h2>
        <p>User Account Access: <?php echo e(Auth::guard('customer')->user()->email); ?></p>

        <div class="sidebar">
            <a href="<?php echo e(route('account.orders')); ?>" class="active">My Orders</a>
            <a href="<?php echo e(route('account.addresses')); ?>">Your Addresses</a>
            <a href="<?php echo e(route('account.security')); ?>">Login & Security</a>
            <a href="<?php echo e(route('account.archived')); ?>">Archive Orders</a>
            <form method="POST" action="<?php echo e(route('logout')); ?>" style="display: inline;">
                <?php echo csrf_field(); ?>
                <button type="submit" class="logout-btn">Log Out</button>
            </form>
        </div>

        <div class="content">
            <h3>My Orders</h3>
            <div class="no-orders">
                <p>No orders yet</p>
            </div>
        </div>
    </div>
</body>
</html><?php /**PATH /var/www/html/resources/views/account/orders.blade.php ENDPATH**/ ?>