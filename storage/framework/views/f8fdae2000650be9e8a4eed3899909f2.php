<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/header.css">
    <link rel="stylesheet" href="/css/userAccount.css">
    <title>Login & Security</title>
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
                    <a href="<?php echo e(route('account.addresses')); ?>" class="sideNavLink">
                        <img src="/images/AdressIcon.png" class="navIcon" alt="Adresses Icon">
                        <p>Adresses</p>
                    </a>
                    <a href="<?php echo e(route('account.security')); ?>" class="sideNavLink active">
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

            <form method="POST" action="<?php echo e(route('account.info.update')); ?>" class="mainContent">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <h1>Your Personal Information</h1>
                <div class="divider"></div>

                <div class="form">
                    <div class="labels">
                    <label for="first_name">First Name</label>
                    <label for="last_name">Last Name</label>
                    <label for="email">Email</label>
                    <label for="contact_number">Contact Number</label>           
                    <label for="current_password">Current Password</label>   
                    <label for="new_password">New Password</label>
                    <label for="new_password_confirmation">Confirm New Password</label>
                </div>

                <div class="input-fields">
                    <input type="text" name="first_name" value="<?php echo e(old('first_name', $customer->first_name)); ?>" required>
                    <input type="text" name="last_name" value="<?php echo e(old('last_name', $customer->last_name)); ?>" required>
                    <input type="email" name="email" value="<?php echo e(old('email', $customer->email)); ?>" required>
                    <input type="text" name="contact_num" value="<?php echo e(old('contact_num', $customer->contact_num)); ?>" required>
                    <input type="password" name="current_password" placeholder="Required only when changing password">
                    <input type="password" name="new_password" placeholder="Leave blank to keep current password">
                    <input type="password" name="new_password_confirmation" placeholder="Confirm new password">
                </div>

                </div>
                    <div class="buttonContainer">
                    <button type="submit" class="button1" style="margin-right: 50px;">Save</button>
                </div>
            </form>
            <form method="POST" action="<?php echo e(route('account.delete')); ?>" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');" class="mainContent2">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <h1>Delete Account</h1>
                <div class="warning-badge" style="color: red;">
                    <p>Warning: Deleting your account is irreversible. All your data will be permanently removed.</p>
                </div>
                <div class="divider"></div>

                <div class="form">
                    <div class="labels">
                    <label for="password">Confirm Password</label>
                </div>

                <div class="input-fields">
                    <input type="password" name="password" required>
                </div>
                </div>
                    <div class="buttonContainer">
                    <button type="submit" class="button1" style="margin-right: 50px;">DELETE</button>
                </div>
            </form>
            </div>
    </main>
    <script>
        document.getElementById('updateInfoForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const firstName = document.querySelector('input[name="first_name"]').value;
            const lastName = document.querySelector('input[name="last_name"]').value;
            const newPassword = document.querySelector('input[name="new_password"]').value;
            
            let message = 'Are you sure you want to update your information?';
            
            if (newPassword) {
                message = 'Are you sure you want to update your information and change your password?';
            }
            
            if (confirm(message)) {
                this.submit();
            }
        });
    </script>
</body>
</html>
<?php /**PATH /var/www/html/resources/views/account/security.blade.php ENDPATH**/ ?>