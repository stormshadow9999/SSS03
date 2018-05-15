<?php
	//logout handle 
	session_start();
	//uset all the session variables and delete dession
	session_unset();
	session_destroy();
	unset($_COOKIE['session_cookie']);
	
	//expire all the cookies created at the login time
	setcookie('PHPSESSID', '', time() - 3600, '/');
    setcookie('session_cookie', '', time() - 3600, '/');
    setcookie('csrf_token', '', time() - 3600, '/','www.assignment02.com',true);
    setcookie('profile', '', time() - 3600, '/','www.assignment03.com',true);
    setcookie('post1', '', time() - 3600, '/','www.assignment03.com',true);
    setcookie('post2', '', time()- 3600, '/','www.assignment03.com',true);
    setcookie('post3', '', time() - 3600, '/','www.assignment03.com',true);
    //redirect to the home page
	header("Location:index.php");
   	exit;


?>