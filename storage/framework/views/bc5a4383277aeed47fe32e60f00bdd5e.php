<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Addresses - Bookstore</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    <div class="account-container">
        <h2>Your Account</h2>
        <p>User Account Access: <?php echo e(Auth::guard('customer')->user()->email); ?></p>

        <div class="sidebar">
            <a href="<?php echo e(route('account.orders')); ?>">My Orders</a>
            <a href="<?php echo e(route('account.addresses')); ?>" class="active">Your Addresses</a>
            <a href="<?php echo e(route('account.security')); ?>">Login & Security</a>
            <a href="<?php echo e(route('account.archived')); ?>">Archive Orders</a>
            <form method="POST" action="<?php echo e(route('logout')); ?>" style="display: inline;">
                <?php echo csrf_field(); ?>
                <button type="submit" class="logout-btn">Log Out</button>
            </form>
        </div>

        <div class="content">
            <h3>My Address</h3>

            <?php if(session('success')): ?>
                <div class="success"><?php echo e(session('success')); ?></div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="error">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p><?php echo e($error); ?></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('account.address.update')); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="form-group">
                    <label>Country</label>
                    <input type="text" name="country" value="<?php echo e(old('country', $address->country ?? '')); ?>">
                </div>

                <div class="form-group">
                    <label>Province</label>
                    <input type="text" name="province" value="<?php echo e(old('province', $address->province ?? '')); ?>">
                </div>

                <div class="form-group">
                    <label>City</label>
                    <input type="text" name="city" value="<?php echo e(old('city', $address->city ?? '')); ?>">
                </div>

                <div class="form-group">
                    <label>Barangay</label>
                    <input type="text" name="barangay" value="<?php echo e(old('barangay', $address->barangay ?? '')); ?>">
                </div>

                <div class="form-group">
                    <label>Zip/Postal Code</label>
                    <input type="text" name="zip_postal_code" value="<?php echo e(old('zip_postal_code', $address->zip_postal_code ?? '')); ?>">
                </div>

                <button type="submit" class="btn-save">Save</button>
            </form>
        </div>
    </div>
</body>
</html><?php /**PATH /var/www/html/resources/views/account/addresses.blade.php ENDPATH**/ ?>