<?php

include_once($_SERVER["DOCUMENT_ROOT"]."/views/Index_Segments.php");

Index_Segments::header(); 

?>

<div class="main_body" style="margin:0"><!-- .main_body starts -->
    
<?php 
    if($data) {//if user is logged in... ~ $data from /php/account-manager.php
?>
        <div style="margin:45px 15px">
            <h3>Hi <?=$data->customer_realname?>, Welcome to BiloOnline</h3>
            <h4 style="margin-top:18px">You're currently logged in with the email address: <span style="color:#ff9100"><?=$data->customer_email?></span></h4>
            <div style="margin-top:18px;text-align:center">
                <a href="/" style="color:#fff;font-weight:bold;background-color:green;padding:9px 12px;border-radius:12px">Continue shopping <i class="fa fa-chevron-circle-right"></i></a> &nbsp; <a href="/logout" style="color:#fff;font-weight:bold;background-color:red;padding:9px 12px;border-radius:12px">Log out</a>
            </div>
        </div>
<?php
    } else {//if user is not logged in:
?>
        <div style="text-align:center;margin-top:45px">
            <a href="/login"><img src="/static/images/signout.png" style=""/></a>
            <p><b>You are currently Logged Out</b></p>
            <p>Already have an account? <b><a href="/login">Login</a></b>. Don't have an account? <b><a href="/sign-up">Sign up</a></b>.</p>
        </div>
<?php
    }
?>
</div><!-- .main_body ends -->


<?php

Index_Segments::footer($site_name = SITE_NAME_SHORT, $site_url = SITE_URL, $additional_scripts = Index_Segments::index_scripts(), $whatsapp_chat = "off", $shopping_cart = "on", $number_of_products_in_cart = INDEX_NUM_OF_PRODUCTS_IN_CART);

?>