<?php

include_once($_SERVER["DOCUMENT_ROOT"]."/views/Index_Segments.php");

$cookies = ["username_or_email","password","admin_name","admin_password", "unique_id"];
foreach($cookies as $c){
    if(isset($_COOKIE["$c"])){
        setcookie($c, $_COOKIE["$c"], time()-(24*3600), "/");
    }
}

Index_Segments::header();
?>

<div class="dashboard_div" style="text-align:center;padding-bottom:21px">   
    <h3>You've Logged Out Successfully</h3>

    <b><a href="/login">Login Again</a> or Proceed to <a href="/"><i class="fa fa-home"></i> Home</a></b>
</div>

<?php
Index_Segments::footer();

?>