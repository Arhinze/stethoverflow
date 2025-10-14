<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/views/Index_Segments.php");

Index_Segments::header();

$remember_email = "";
$remember_full_name = "";

if(isset($_POST["email"])) {
    $remember_email = htmlentities($_POST["email"]);
}

if(isset($_POST["full_name"])) {
    $remember_full_name = htmlentities($_POST["full_name"]);
}

?>

<!--HTML:-->
<div class="main_body" style="padding:12px;margin-left:9px"><!-- .main_body starts -->
    <div style="text-align:center;font-size:18px"><i>Welcome to</i></div>
    <h1 style="font-size:30px;text-align:center">Steth<span style="color: #2b8eeb">Overflow</span></h1>
    <div style="color:#2b8eeb;font-size:15px;text-align:center;margin-top:-19px"><i class="fa fa-key"></i>&nbsp; All data is encrypted</div>

    <div class="input" style="border-radius:36px;text-align:center;background-color:blue;border:1px solid blue;font-weight:bold;color:#fff;margin-top:30px">
        <a href="/auth/google-login.php" style="color:#fff;padding:12px 21%"><i class="fa fa-google" id="signinButton"></i>&nbsp; Continue with Google</a>
    </div>
    <!--<div class="input" style="border-radius:36px;text-align:center;margin-top:9px;font-weight:bold">
        <a href="/auth/facebook-login.php"><i class="fa fa-facebook" style="color:blue"></i>&nbsp; Continue with Facebook -- </a>
    </div>-->
    <div class="input" style="border-radius:36px;text-align:center;margin-top:9px;font-weight:bold;display:block" onclick="open_email_form()" id="email_button"><i class="fa fa-envelope"></i>&nbsp; Continue with Email</div>    

    <div style="border:1px solid #000;border-radius:15px;width:90%;margin-top:21px;display:none" id="email_form">
        <div style="position:relative;width:100%;height:30px"><!-- .fa times starts -->
            <div style="position:absolute;float:right;right:12px;top:9px;color:red;font-size:21px"><i class="fa fa-times" onclick="close_email_form()"></i></div>
        </div><!-- .fa times ends -->
        <div style="position:relative;height:fit-content;margin:6px 12px"><!-- .email and continue button starts -->
            <form method="POST" action="/create-or-login">
                <div><input type="email" name="email" class="input" minlength="5" maxlength="250" placeholder="Enter Email Address" value="<?=$remember_email?>" required/></div>
                <!-- code(captcha) starts -->
                <?php include($_SERVER["DOCUMENT_ROOT"]."/views/captcha.php"); ?>
                <!-- code(captcha) ends -->
                <input name="full_name" type="hidden" value="<?=$remember_full_name?>"/>
                <div style="margin:9px 0 24px 0;width:100%"><button class="input" style="padding:9px 36%;border-radius:30px;color:#fff;font-weight:bold;background-color:#ff9100">Continute</button></div>
            </form>
        </div><!-- .email and continue button ends -->
    </div>
</div><!-- .main_body ends -->

<script>
    function open_email_form(){
        document.getElementById("email_form").style.display="block";
        document.getElementById("email_button").style.display="none";
    }

    function close_email_form(){
        document.getElementById("email_form").style.display="none";
        document.getElementById("email_button").style.display="block";
    }
</script>
    
<?php Index_Segments::footer(); ?>