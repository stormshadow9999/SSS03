<!-- client side code -->
<!doctype html>
<html >
  <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Assigment_03</title>
   
        <link rel="stylesheet"  href="/Assigment_03/public/css/bootstrap.min.css">        
        <script src="/Assigment_03/public/js/jquery-3.3.1.min.js"></script>
   
  </head>
  <body >

    <nav class="navbar navbar-light bg-light">
      <a class="navbar-brand" href="index.php">Assignment 03</a>
      <ul class="nav justify-content-end">
      </ul>
    </nav>
    <div class="container">
      <div class="row" align="center" style="padding-top: 100px;">
        <div class="col-12">
          <div class="card">
            <h5 class="card-header">Login</h5>
            <div class="card-body">
              <div class="row">
                <div class="col-sm-2"></div>
                <div class="col-sm-8">
                  <!-- loging form -->
                  <form action='login.php' method='POST' enctype='multipart/form-data'>
                    <div class="form-group row">
                      <label for="Email" class="col-sm-2 col-form-label">Email</label>
                      <div class="col-sm-10">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="password" class="col-sm-2 col-form-label">Password</label>
                      <div class="col-sm-10">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                      </div>
                    </div>            
                    <button type="submit" class="btn btn-primary" id="submit" name="submit">Login</button>
                  </form>
                  <!--  -->
                </div>
                <div class="col-sm-2"></div>
              </div>
              <!-- social login with facebook and google -->
              <div class="row" align="center">
                <div class="col-sm-1"></div>
                <div class="col-sm-1">
                  <!-- facebook login -->
                  <!-- ask permission to get public profile details,email,user posts and permission to post on the wall -->
                  <a href="https://graph.facebook.com/oauth/authorize?response_type= token&client_id=############&redirect_uri=https://www.assignment03.com:###/profile.php&scope=email%20public_profile%20user_posts%20publish_actions" class="btn btn-primary" style="border-radius: 50%;color: white;width: 70px;height: 70px;font-size: 40px">f
                  </a>
                  <!--  -->
                </div>
                <div class="col-sm-1">
                  <!-- google login -->
                  <!-- ask permission to get email and profile details -->
                  <a href="https://accounts.google.com/o/oauth2/auth?redirect_uri=https://www.assignment03.com:###/profile.php&response_type=code&client_id=####################################&scope=email profile&approval_prompt=force&access_type=offline" class="btn btn-danger" style="border-radius: 50%;color: white;width: 70px;height: 70px;font-size: 40px;">G
                  </a>
                  <!--  -->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="/Assigment_03/public/js/bootstrap.min.js"></script>
    <script src="/Assigment_03/public/js/popper.min.js"></script>
  </body>
</html>

<!-- server side code -->
<?php
  // check whether user have pressed the login button
	if(isset($_POST['submit']))
  {
    // if yes execute login function
		login();
	}
?>

<?php
	
	function login()
	{

		$email='shadow@gmail.com';
		$password='shadow';

	
		$input_email = $_POST['email'];
		$input_pwd = $_POST['password'];

		
		if(($input_email == $email)&&($input_pwd == $password))
		{
			session_set_cookie_params(300);
			session_start();
			session_regenerate_id();
			
			
			setcookie('session_cookie', session_id(), time() + 300, '/');

			$token = generate_token();

      setcookie('csrf_token', $token, time() + 300, '/','www.assignment03.com',true);
			
			header("Location:profile.php");
   		exit;
			
		}
		else
		{
			echo "<script>alert('email password are not a match!')</script>";
		}


	}
	
  function generate_token()
	{
	  return sha1(base64_encode(openssl_random_pseudo_bytes(30))); 
	}


?>
