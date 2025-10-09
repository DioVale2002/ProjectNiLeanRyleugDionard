<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/header.css">
    <link rel="stylesheet" href="/css/userAccount.css">
    <title>Your Addresses</title>
</head>

<body>
    <header class="header">
        <a href="#"></a><img class="logo" src="/images/Logo(1).png" alt="logo"></a>
        <input class="search-bar" type="text" placeholder="Search ">
        <div class="navigation">
            <p>Welcome, <?php echo e(Auth::guard('customer')->user()->first_name); ?> <?php echo e(Auth::guard('customer')->user()->last_name); ?>!</p>
            <a href="#"><img src="/images/User.png" alt="Profile Picture"></a>
            <a href="#"><img src="/images/cart.png" alt="cart"></a>
        </div>
    </header>
    <nav class="navbar">
        <div class="navlinks-container">
            <a href="#">BOOKS</a>
            <a href="#">E-BOOKS</a>
            <a href="#">BEST SELLERS</a>
            <a href="#">NEW</a>
            <a href="#">COLLECTIONS</a>
        </div> 
    </nav> 

    <main>
        <div class="accountheader">
            <h1>Welcome</h1>
            <p><?php echo e(Auth::guard('customer')->user()->first_name); ?> <?php echo e(Auth::guard('customer')->user()->last_name); ?>, Email: <?php echo e(Auth::guard('customer')->user()->email); ?></p>
        </div>

        <div class="mainContainer">
            <div class="sideNav">
                    <a href="<?php echo e(route('account.orders')); ?>" class="sideNavLink">
                        <img src="/images/Icon Delivery.png" class="navIcon" alt="Icon Delivery">
                        <p>My Orders</p>
                    </a>
                    <a href="<?php echo e(route('account.addresses')); ?>" class="sideNavLink active">
                        <img src="/images/AdressIcon.png" class="navIcon" alt="Adresses Icon">
                        <p>Adresses</p>
                    </a>
                    <a href="<?php echo e(route('account.security')); ?>" class="sideNavLink">
                        <img src="/images/SecurityIcon.png" class="navIcon" alt="Login & Security Icon">
                        <p>Login & Security</p>
                    </a>
                    <a href="<?php echo e(route('account.archived')); ?>" class="sideNavLink">
                        <img src="/images/ArchiveIcon.png" class="navIcon" alt="Archive Orders Icon">
                        <p>Archived Orders</p>
                    </a>
                    <div class="divider"></div>
                    <form action="<?php echo e(route('logout')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="sideNavLink">
                            <img src="/images/LogoutIcon(1).png" class="navIcon" alt="Logout Icon">
                            <p>Logout</p>
                        </button>
                    </form>
            </div>

            <div>
                <?php if(session('success')): ?>
                    <div class="success" style="padding: 15px; margin: 20px 0; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px;">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="error" style="padding: 15px; margin: 20px 0; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px;">
                        <ul style="margin: 0; padding-left: 20px;">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(route('account.address.update')); ?>" class="mainContent">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <h1>Your Address Information</h1>
                    <div class="divider"></div>

                    <div class="form">
                        <div class="labels">
                        <label for="Country">Country</label>
                        <label for="Province">Province</label>
                        <label for="City">City</label>
                        <label for="Barangay">Barangay</label>           
                        <label for="Zip/Postal Code">Zip/Postal Code</label>   
                    </div>

                    <div class="input-fields">
                        <input type="text" name="country" value="<?php echo e(old('country', $address->country ?? '')); ?>">
                        <input type="text" name="province" value="<?php echo e(old('province', $address->province ?? '')); ?>">
                        <input type="text" name="city" value="<?php echo e(old('city', $address->city ?? '')); ?>">
                        <input type="text" name="barangay" value="<?php echo e(old('barangay', $address->barangay ?? '')); ?>">
                        <input type="text" name="zip_postal_code" value="<?php echo e(old('zip_postal_code', $address->zip_postal_code ?? '')); ?>" placeholder="Numbers only">
                    </div>
                    
                    </div>
                        <div class="buttonContainer">
                        <button type="submit" class="button1">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </main> 

</body>
</html>
<?php /**PATH /var/www/html/resources/views/account/addresses.blade.php ENDPATH**/ ?>