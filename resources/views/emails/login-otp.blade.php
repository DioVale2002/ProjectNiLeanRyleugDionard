<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Login Code</title>
  </head>
  <body>
    <p>Here is your one-time login code:</p>
    <p style="font-size: 22px; font-weight: bold; letter-spacing: 2px;">{{ $code }}</p>
    <p>This code expires in {{ $expiresMinutes }} minutes.</p>
    <p>If you did not request this, you can ignore this email.</p>
  </body>
</html>
