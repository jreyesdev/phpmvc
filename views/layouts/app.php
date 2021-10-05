<?php
use App\Core\App;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        .container{
            padding: 0px 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <ul>
            <li><a href="/">Home</a></li>
            <?php if(!App::isGuest()): ?>
            <li><a href="/contact">Contact</a></li>
            <li><a href="/register">Register</a></li>
            <li><a href="/profile">Profile</a></li>
            <li><a href="/logout">Logout</a></li>
            <?php else: ?>
            <li><a href="/login">Login</a></li>
            <?php endif; ?>
        </ul>
    </div>
    <?php if(App::$app->session->getFlash('success')): ?>
    <div class="alert alert-success">
        <?= App::$app->session->getFlash('success') ?>
    </div>
    <?php endif; ?>
    {{content}}
</body>
</html>