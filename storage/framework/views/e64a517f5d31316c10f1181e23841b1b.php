<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/signup.css">  
    <title>New Century Books</title>
</head>
<body>
    <div class="container">
        <div class="catchphrase">
            <h1>Learn + Achieve, all in One Bookstore</h1>
            <p>A space for students and educators to access textbooks and resources. Study smarter and achieve more with every book in one place.</p>  
        </div>
        <div class="login-form">
            <div class="logo-wrapper">
                <img src="/img/logo.png" alt="logo">
            </div>
            <h2>Create your account</h2>

            <?php if($errors->any()): ?>
                <div class="error">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p><?php echo e($error); ?></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('register')); ?>">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <input type="text" name="first_name" placeholder="First Name" value="<?php echo e(old('first_name')); ?>" required>
                </div>
                <div class="form-group">
                    <input type="text" name="last_name" placeholder="Last Name" value="<?php echo e(old('last_name')); ?>" required>
                </div>
                <div class="form-group">
                    <input type="text" name="contact_num" placeholder="Contact Number" value="<?php echo e(old('contact_num')); ?>" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" value="<?php echo e(old('email')); ?>" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password (8+ characters)" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
                </div>
                <div class="terms">
                    <label class="custom-checkbox">
                        <input type="checkbox" required>
                        <span class="checkmark"></span>
                        I accept the<a href="#">&nbsp;Terms of Service</a> &nbsp;and&nbsp; <a href="#">Privacy Policy</a>
                    </label>
                </div>
                <button type="submit" class="btn">Confirm</button>
            </form>
            <p class="already">
                Already have an account? <a href="<?php echo e(route('login')); ?>">Sign in</a>
            </p>
        </div>
    </div>
    <div class="copyright">
        <p> © 2025 New Century Books. All rights reserved.</p>
        <p><a href="">Privacy Policy&nbsp;</a><a href="">Terms of Service&nbsp;</a></p>
    </div>
</body>
</html><?php /**PATH /var/www/html/resources/views/auth/register.blade.php ENDPATH**/ ?>