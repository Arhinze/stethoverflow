<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/views/Index_Segments.php");

Index_Segments::header();

$remember_email = "";
$remember_full_name = "";
$heading = "";
$full_name_tag = "";
$repeat_password_tag = "";
$remember_email = "";
$google_or_email_log_in = "";

if(isset($_POST["email"])) {
    $remember_email = htmlentities($_POST["email"]);
}

if(isset($_POST["full_name"])) {
    $remember_full_name = htmlentities($_POST["full_name"]);
}

if(isset($_POST["user_code"]) && (!empty($_POST["user_code"])) && (htmlentities($_POST["user_code"]) == $_POST["xsrf_code"])){
    $user_email = htmlentities($_POST["email"]);
    $stmt = $pdo->prepare("SELECT * FROM stethoverflow_users WHERE user_email = ? LIMIT ?, ?");
    $stmt->execute([$user_email, 0, 1]);
  
    $data = $stmt->fetch(PDO::FETCH_OBJ);

    $remember_email = $user_email;

    if($data){ //data from php/account-manager.php ~ if true, that means user has an account already.
        $heading = "Login";
        if($data->password == "Goo--gle1") {//user has previously logged in with google
            $google_or_email_log_in = "<div style='text-align:center;margin-top:15px'>We found that you've previously logged in with google, would you like to continue to google or set a password for your account?</div>     <div class='input' style='border-radius:36px;text-align:center;background-color:blue;border:1px solid blue;font-weight:bold;color:#fff;margin-top:15px'><a href='/auth/google-login.php' style='color:#fff'><i class='fa fa-google' id='signinButton'></i>&nbsp; Continue with Google</a></div>     <div class='input' style='border-radius:36px;text-align:center;background-color:green;border:1px solid green;font-weight:bold;color:#fff;margin-top:9px'><a href='/reset-password' style='color:#fff'><i class='fa fa-lock' id='signinButton'></i>&nbsp; Add Password</a></div>";     
        }
    } else {//user has no account ~ display create account form:
        $heading = "Create Account";
        $full_name_tag = '<div style="margin-top:9px"><input name = "full_name" type="text" class="input" placeholder="Enter Full Name:" value="'.$remember_full_name.'" required/></div>';
        $repeat_password_tag = '<div><input type="text" name="repeat_password" class="input" id="password2"  '.'onkeyup='."check_password('password1','password2') ".'placeholder="Repeat Password: ******" required/></div><div id="status"></div>';      
    }

?>

    <!--HTML:-->
    <div class="main_body"><!-- .main_body starts -->
        <h1 style="font-size:30px;text-align:center">Bilo<span style="color:#ff9100">Online</span></h1>
        <div style="color:green;font-size:15px;text-align:center;margin-top:-19px"><i class="fa fa-key"></i>&nbsp; All data is encrypted</div>
    
        <div style="border:1px solid #000;border-radius:15px;width:96%;margin-top:21px">
            <h2 style="text-align:center"><?=$heading?></h2>
            <div style="position:relative;height:fit-content;margin:6px 12px"><!-- .email and continue button starts -->
                <form method="POST" action="/redirect-to-my-account">
                    <div><input name="email" type="email" class="input" id="email" placeholder="Enter Email Address:abc@example.com" value="<?=$remember_email?>"/><div id="email_status"></div></div>

                    <?=$full_name_tag?>
    
                    <?php
                    if ($google_or_email_log_in == "") { //that means user has not logged in with google account before or has added a password to his account
                    ?>
                        <h3>Password: <span style="font-size:12px"><a href="/reset-password">Forgot Password?</a></span></h3>
                        <div style="margin:6px 0"><input type="text" name="password" class="input" id="password1" placeholder="Enter Password: ******" required/></div>

                        <?=$repeat_password_tag?>

                        <div style="margin:9px 0 24px 0;width:100%" id="continue_button"><button class="input" style="padding:9px 36%;border-radius:30px;color:#fff;font-weight:bold;background-color:#ff9100">Continute</button></div>
                    <?php
                    } else {//user has previously logged in with google, suggest him to do same again or add(reset) password
                        echo $google_or_email_log_in;
                    }
                    ?>
                </form>
            </div><!-- .email and continue button ends -->
        </div>
    </div><!-- .main_body ends -->

<?php
} else {//if captcha is wrong or empty:
?>
    <div class="invalid">Invalid or empty captcha <i class="fa fa-warning"></i></div>
    <div style="margin:60px 12px;text-align:center">
        <form method = "POST" action="/login">
            <input type="hidden" name="email" value="<?=$remember_email?>"/>
            <button type="submit" class="input" style="color:#fff;background-color:#ff9100;border:1px solid #fff;font-weight:bold"><i class="fa fa-arrow-left"></i> &nbsp; return to previous page</button>
        </form>
    </div>
<?php
}
?>

<script>
    function check_password(smtn1, smtn2){
        if(document.getElementById(smtn1).value == document.getElementById(smtn2).value){
            document.getElementById("status").innerHTML = "<b style='color:green'>Nice. Passwords Match <i class='fa fa-check'></i></b>";

            document.getElementById("continue_button").innerHTML = '<button class="input" style="padding:9px 36%;border-radius:30px;color:#fff;font-weight:bold;background-color:#ff9100">Continute</button>';
        } else {//if passwords don't match ~ client-side validation:
            document.getElementById("status").innerHTML = "<p style='color:red'><b>Passwords Do Not Match</b>.</p> <small><i class='fa fa-warning' style='color:red'></i> Make sure both password fields match to avoid starting the entire process afresh.<br /></small>";

            document.getElementById("continue_button").innerHTML = '<span class="input" style="padding:9px 36%;border-radius:30px;color:#fff;font-weight:bold;background-color:#888">Continute</span>';
        }
    }
</script>
    
<?php Index_Segments::footer(); ?>