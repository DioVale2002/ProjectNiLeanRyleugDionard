<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bookstore</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 400px; margin: 50px auto; padding: 20px; }
        input { width: 100%; padding: 8px; margin: 5px 0 15px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background: #0056b3; }
        .error { color: red; font-size: 14px; }
        .success { color: green; font-size: 14px; }
        a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <h2>Login</h2>
    
    <?php if(session('success')): ?>
        <div class="success">
            <p><?php echo e(session('success')); ?></p>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="error">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <p><?php echo e($error); ?></p>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('login')); ?>">
        <?php echo csrf_field(); ?>
        
        <label>Email</label>
        <input type="email" name="email" value="<?php echo e(old('email')); ?>" required>
        
        <label>Password</label>
        <input type="password" name="password" required>
        
        <button type="submit">Login</button>
    </form>
    
    <p style="margin-top: 20px;">Don't have an account? <a href="<?php echo e(route('register')); ?>">Register here</a></p>
</body>
</html><?php /**PATH /var/www/html/resources/views/auth/login.blade.php ENDPATH**/ ?>