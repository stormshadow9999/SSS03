<!doctype html>
<html >
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Assigment_03</title>
     
        <link rel="stylesheet"  href="/Assigment_03/public/css/bootstrap.min.css">
        <script src="/Assigment_03/public/js/jquery-3.3.1.min.js"></script>
        <!-- animation css to move the student name and id in the index page -->
        <style>
            
        </style>
    </head>
    <body style="background-image: url('/Assigment_03/ironman.png');color: white;">

        <nav class="navbar navbar-light bg-light">
            <a class="navbar-brand" href="index.php">Assignment 03</a>
            <ul class="nav justify-content-end">
                <?php 
                    if(isset($_COOKIE['session_cookie'])) 
                    {
                        echo "<li class='nav-item'>
                                <a class='nav-link active' href='profile.php'>Profile</a>
                            </li>";
                    }
                ?>
                <?php 
                    if(!isset($_COOKIE['session_cookie'])) 
                    {
                        echo "<li class='nav-item'>
                                <a class='nav-link' href='login.php'>Login</a>
                            </li>";
                    }
                ?>
                <?php 
                    if(isset($_COOKIE['session_cookie'])) 
                    {
                        echo "<li class='nav-item'>
                                <a class='nav-link active' href='logout.php'>Logout</a>
                            </li>";
                    }
                ?>
            </ul>
        </nav>
        <div class="container">
            <div class="row" align="center" style="padding-top: 170px;">
                <div class="col-12">
                    <h1 style="font-size: 90px;">Social Login</h1> 
                </div>
            </div>
            <div class="row" align="center" style="padding-top: 20px;">
                <div class="col-12">
                    <h2 style="font-size: 50px;">OAuth framework</h2>  
                </div>
            </div>
            <div class="row" align="center" style="padding-top: 10px;">
                <div class="col-12">
                        <h3 style="font-size:20px;"><bold>Hemantha Dananjaya Dissanayke </bold></h3>
                        <h3 style="font-size:20px;"></bold>IT16516458</bold></h3>
                         
                </div>
            </div>
        </div>
    
        <script src="/Assigment_03/public/js/bootstrap.min.js"></script>
        <script src="/Assigment_03/public/js/popper.min.js"></script>
    </body>
</html>
