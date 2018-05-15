<!doctype html>
<html >
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    
        <title>Assigment_03</title>

        <link rel="stylesheet"  href="/Assigment_03public/css/bootstrap.min.css">
        <script src="/Assigment_03/public/js/jquery-3.3.1.min.js"></script>
   
    </head>
    <body>

    <nav class="navbar navbar-light bg-light">
        <a class="navbar-brand" href="index.php">Assignment 03</a>
        <ul class="nav justify-content-end">
            <!-- check whether session cookie is set, to enable logout  -->
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
        <div class="row" align="center" style="padding-top: 100px;">
            <div class="col-12">
                <?php  
                    session_start();
                    // check whether session variable "access" is set to display below content
                    if(isset($_SESSION['access'])) 
                    { 
                        echo "<div class='card'>
                                <h5 class='card-header'>Add new Status : </h5>
                                <div class='card-body'>
                                    <div class='row'>
                                        <div class='col-2'></div>
                                        <div class='col-8'>
                                            <form action='profile.php' method='POST' enctype='multipart/form-data'>
                                                <div class='form-group row'>
                                                    <label for='msg' class='col-sm-2 col-form-label'>Status</label>
                                                    <div class='col-sm-10'>
                                                        <input type='text' class='form-control' id='msg' name='msg' placeholder='Status'>
                                                    </div>
                                                </div>
                                                <button type='submit' class='btn btn-primary' id='poststatus' name='poststatus'>Post on the wall</button>
                                            </form>
                                        </div>
                                        <div class='col-2'></div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <br>";
                        //check whether poststatus button is pressed to post the status on users facebook account 
                        if(isset($_POST['poststatus']))
                        {
                            //get access token form the session variable
                            $access=$_SESSION['access'];
                            //add status content message to the post request
                            $postfields = array(
                                        'message' => $_POST['msg'],
                                        'access_token'=>$access
                                        );
                            //send post request 
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/v2.11/me/feed');
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                            curl_setopt($ch, CURLOPT_POST, 1);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
                            
                            $result = curl_exec($ch);
                            $r=json_decode($result);
                        }  
                    }
                ?>    
                        
                <div class="card">
                    <!-- display user details derived from facebook or google account -->
                    <h5 class="card-header">Profile</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-8">
                                <?php
                                    // check wether session cookie is set at the login time
                                    if(isset($_COOKIE['session_cookie'])) 
                                    {    
                                        //get user details from profile cookie
                                        $string2= $_COOKIE['profile'];
                                        $string2=explode('#', $string2);
                                        echo"
                                        <div class='row'>
                                            <div class='col-md-2'>
                                                <img src='".$string2[4]."' alt='cover' style='width:100px;height:100px;border-radius: 50%;'>
                                            </div>
                                            <div class='col-md-10' style='padding-top: 20px;'>
                                                <div class='row'>
                                                    <div class='col-md-4'>
                                                        <b>Name</b>
                                                    </div>
                                                    <div class='col-md-8'>
                                                        ".$string2[1]." ".$string2[2]."
                                                    </div>
                                                </div>
                                                <div class='row'>
                                                        <div class='col-md-4'>
                                                            <b>Gender</b>
                                                        </div>
                                                        <div class='col-md-8'>
                                                            ".$string2[3]."
                                                        </div>
                                                </div>
                                                <div class='row'>
                                                    <div class='col-md-4'>
                                                        <b>E-mail</b>
                                                    </div>
                                                    <div class='col-md-8'>
                                                        ".$string2[0]."
                                                    </div>
                                                </div>
                                            </div>
                                        </div>";
                                    }
                                    else
                                    {
                                        //check whether access token is derived from the url (only in facebook )
                                        if(isset($_POST["at"]))
                                        {
                                            //check whether access token is empty
                                            if ($_POST["at"] != '' && $_POST["at"] != null)
                                            {
                                                //get access token and save it to new variable
                                                $new=$_POST["at"];
                                                //endpoint to send get request and derived user details
                                                //first name, last name,email,gender, profile picture and latest 3 post posted by the user will be derived from the facebook
                                                $user_details = "https://graph.facebook.com/me?fields=first_name,last_name,email,gender,picture.type(large),posts.limit(3)&access_token=".$new;
                                                $response = file_get_contents($user_details);
                                                $response = json_decode($response);
                                                //check whether details are in the respond from the facebook
                                                if($response->email != null || $response->email != '')
                                                {
                                                    //start cookie
                                                    session_set_cookie_params(300);
                                                    session_start();
                                                    session_regenerate_id();
                                                        
                                                    setcookie('session_cookie', session_id(), time() + 300, '/');
                                                    $_SESSION['access'] = $new;
                                                    //set CSRF cookie
                                                    $token = generate_token();
                                                    setcookie('csrf_token', $token, time() + 300, '/','www.assignment03.com',true);
                                                    //set profile cookie which contain basic user details derived from facebook
                                                    $res=$response->picture;
                                                    $res2=$res->data;
                                                    $string=$response->email."#".$response->first_name."#".$response->last_name."#".$response->gender."#".$res2->url;
                                                    setcookie('profile', $string, time() + 300, '/','www.assignment03.com',true);  
                                                    //get the latest post posted by the user and set the post1 cookie 
                                                    $post=$response->posts->data[0];
                                                    $story1=$post->story;
                                                    $c_date=$post->created_time;
                                                    $p_id=$post->id;
                                                    $user_details2 = "https://graph.facebook.com/v2.11/".$p_id."?fields=full_picture,picture,caption,id&access_token=".$new;
                                                    $img_1= file_get_contents($user_details2);
                                                    $response_img1 = json_decode($img_1);
                                                    $post_img_1=$response_img1->full_picture;
                                                    $post1_info=$story1."#".$c_date."#".$post_img_1;
                                                    setcookie('post1', $post1_info, time() + 300, '/','www.assignment03.com',true);
                                                    //get the second latest post posted by the user and set the post2 cookie
                                                    $post2=$response->posts->data[1];
                                                    $story2=$post2->story;
                                                    $c_date2=$post2->created_time;
                                                    $p_id2=$post2->id;
                                                    $user_details3 = "https://graph.facebook.com/v2.11/".$p_id2."?fields=full_picture,picture,caption,id&access_token=".$new;
                                                    $img_2= file_get_contents($user_details3);
                                                    $response_img2 = json_decode($img_2);
                                                    $post_img_2=$response_img2->full_picture;
                                                    $post2_info=$story12."#".$c_date2."#".$post_img_2;
                                                    setcookie('post2', $post2_info, time() + 300, '/','www.assignment03.com',true);
                                                    //get the third latest post posted by the user and set the post3 cookie
                                                    $post3=$response->posts->data[2];
                                                    $story3=$post3->story;
                                                    $c_date3=$post3->created_time;
                                                    $p_id3=$post3->id;
                                                    $user_details4 = "https://graph.facebook.com/v2.11/".$p_id3."?fields=full_picture,picture,caption,id&access_token=".$new;
                                                    $img_3= file_get_contents($user_details4);
                                                    $response_img3 = json_decode($img_3);
                                                    $post_img_3=$response_img3->full_picture;
                                                    $post3_info=$story3."#".$c_date3."#".$post_img_3;
                                                    setcookie('post3', $post3_info, time() + 300, '/','www.assignment03.com',true);
                                                    //redirect to the profile.php
                                                    header("Location:profile.php");
                                                    exit;

                                                }  
                                        

                                            }
                                        }
                                        //check whether code variable is set on the URL (if user logged in using google account)
                                        else if(isset($_GET["code"]))
                                        {
                                            //derived code from the url
                                            $ur=str_replace("/profile.php?code=", "",urldecode($_SERVER['REQUEST_URI']) );
                                            //set the variable to send post request to the endpoint to retrive access token 
                                            $postfields = array('code' => $ur, 'client_id' => '####################', 
                                                        'client_secret' => '#################', 
                                                        'redirect_uri' => 'https://www.assignment03.com:#/profile.php',
                                                        'grant_type' => 'authorization_code'
                                                    );
                                            $ch = curl_init();
                                            curl_setopt($ch, CURLOPT_URL, 'https://accounts.google.com/o/oauth2/token');
                                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                                            curl_setopt($ch, CURLOPT_POST, 1);
                                            curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
                                            //access token is in the respond sent by the google endpoint
                                            $result = curl_exec($ch);
                                            $r=json_decode($result);
                                            //send access token to the google and get the user details from the google
                                            $geturl='https://www.googleapis.com/oauth2/v3/userinfo?access_token='.$r->access_token;
                                            $re = file_get_contents($geturl);
                                            $array2 = json_decode($re);

                                            $res2=$array2->picture;
                                            //check whether user details are in the respond
                                            if($array2->email != null || $array2->email != '')
                                            {
                                                //start session
                                                session_set_cookie_params(300);
                                                session_start();
                                                session_regenerate_id();
                                                setcookie('session_cookie', session_id(), time() + 300, '/');
                                                //set CSRF cookie
                                                $token = generate_token();
                                                setcookie('csrf_token', $token, time() + 300, '/','www.assignment03.com',true);
                                                //set profile cookie which contain the user profile details get from the google
                                                $string=$array2->email."#".$array2->given_name."#".$array2->family_name."#".''."#".$res2;
                                                setcookie('profile', $string, time() + 300, '/','www.assignment03.com',true);   
                                                //redirect to the profile.php
                                                header("Location:profile.php");
                                                exit;
                                            }

                                        }
                                        else
                                        {
                                            //when logged in using facebook facebook send access token in the URL 
                                            //below javascript will derived the access token from the URL
                                            //and make a post request which containing the access token in the body
                                            echo "
                                            <form action='profile.php'  method='post' id='form'>
                                                <input type='hidden' name='at' id='at'>     
                                            </form>

                                            <script >
                                                var ur=location.hash.replace('#access_token=', '');
                                                var ur=ur.split('&');
                                                var u=ur[0];

                                                document.getElementById('at').value = u;
                                                document.getElementById('form').submit();
                                            </script>";
                                        }


                                    }
                                    //function to generate CSRF token
                                    function generate_token()
                                    {
                                        return sha1(base64_encode(openssl_random_pseudo_bytes(30)));    
                                    }
                                ?>
                             </div>
                            <div class="col-sm-2"></div>
                        </div>
                    </div>
                </div>
                <?php
                    //check whether post1 cookie is set (user posts were derived from facebook or not)
                    if(isset($_COOKIE['post1'])) 
                    {
                        //get post 1 details from the cookie
                        $u_post1= $_COOKIE['post1'];
                        $info1=explode('#', $u_post1);
                        //get post 2 details from the cookie
                        $u_post2= $_COOKIE['post2'];
                        $info2=explode('#', $u_post2);
                        //get post 3 details from the cookie
                        $u_post3= $_COOKIE['post3'];
                        $info3=explode('#', $u_post3);
                        //display latest 3 posts deived from the facebook
                        echo "<br><br>
                        <div class='card'>
                            <h5 class='card-header'>Latest Posts Posted by the user</h5>
                            <div class='card-body'>
                                <div class='row'>
                                    <div class='col-4'>
                                        <div class='row'><b>Story : </b>".$info1[0]."</div>
                                        <div class='row'><b>Date : </b>".$info1[1]."</div>
                                        <div class='row'><img src='".$info1[2]."' style='width:300px;height:400px;'></div>
                                    </div>
                                     <div class='col-4'>
                                        <div class='row'><b>Story : </b>".$info2[0]."</div>
                                        <div class='row'><b>Date : </b>".$info2[1]."</div>
                                        <div class='row'><img src='".$info2[2]."' style='width:300px;height:400px;'></div>
                                    </div>
                                     <div class='col-4'>
                                        <div class='row'><b>Story : </b>".$info3[0]."</div>
                                        <div class='row'><b>Date : </b>".$info3[1]."</div>
                                        <div class='row'><img src='".$info3[2]."' style='width:300px;height:400px;'></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br><br>";

                    }
                ?>
            </div>
        </div>
    </div>

    <script src="/Assigment_03/public/js/bootstrap.min.js"></script>
    <script src="/Assigment_03/public/js/popper.min.js"></script>

</body>
</html>
