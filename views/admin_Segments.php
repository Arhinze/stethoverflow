<?php

//admin_Segments

include_once($_SERVER["DOCUMENT_ROOT"]."/php/connection.php");

Class admin_Segments{
    public static function headerr () {
        //dummy method used for test purposes
    }
    
    public static function inject($obj){
        admin_Segments::$pdo = $obj;
    }

    private static $pdo;

    public static function main_admin_access(){
        $admin_stmt = admin_Segments::$pdo->prepare("SELECT * FROM `admin` LIMIT ?, ?");
        $admin_stmt->execute([0,2]);
        $admin_data = $admin_stmt->fetchAll(PDO::FETCH_OBJ);
        
        if(count($admin_data)>0) {
            $r = '';
            foreach($admin_data as $d){
                if(($d->admin_name == $_COOKIE["admin_name"]) && ($d->admin_password == $_COOKIE["admin_password"])){
                    $r .= "y";
                } else {
                    $r .= "";  
                }
            }
        } else {
            $r = "<span style='color:red'><li><i class='fa fa-lock'></i> admins</li></span>"; 
        }

        if($r == "y"){
            return "<li><i class='fa fa-lock'></i> <a href='/admin-of-admins'>admins</a></li>";
        } else {
            return "<span style='color:red'><li><i class='fa fa-lock'></i> admins</li></span>";
        }
        
    }    

    public static function site_numbers($query){
        $num_of_prod_stmt = admin_Segments::$pdo->prepare("SELECT * FROM products LIMIT 0, 1000");
        $num_of_prod_stmt->execute([]);
        $num_of_prod_data = $num_of_prod_stmt->fetchAll(PDO::FETCH_OBJ);

        $num_of_users_stmt = admin_Segments::$pdo->prepare("SELECT * FROM customers LIMIT 0, 1000");
        $num_of_users_stmt->execute([]);
        $num_of_users_data = $num_of_users_stmt->fetchAll(PDO::FETCH_OBJ);

        $num_of_orders_stmt = admin_Segments::$pdo->prepare("SELECT * FROM orders WHERE `status` = ? LIMIT ?, ?");
        $num_of_orders_stmt->execute(["processing", 0, 1000]);
        $num_of_orders_data = $num_of_orders_stmt->fetchAll(PDO::FETCH_OBJ);

        if($query == "Products") {
            return count($num_of_prod_data);
        } else if($query == "Users") {
            return count($num_of_users_data);
        } else if($query == "Orders") {
            return count($num_of_orders_data);
        } else {
            return "0.00*";
        }
    }

    public static function header($site_name = SITE_NAME_SHORT, $site_url = SITE_URL){
        $main_admin_access = admin_Segments::main_admin_access();
        $num_of_prod = admin_Segments::site_numbers("Products");
        $num_of_users = admin_Segments::site_numbers("Users");
        $num_of_orders = admin_Segments::site_numbers("Orders");

        $Hi_admin = "";

        if(isset($_COOKIE["admin_name"])){
            $Hi_admin = $_COOKIE["admin_name"];
        }

        $css_version = filemtime($_SERVER["DOCUMENT_ROOT"]."/static/style.css");

        echo <<<HTML
            <!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="/static/style.css?$css_version"/>
    <link rel="stylesheet" href="/static/font-awesome-4.7.0/css/font-awesome.min.css"/>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 user-scalable=no">

    <style>
        .admin_invest_now_button{
            padding:4px 8px;
            color:#fff;
            border-radius:6px;
            background-color:#ff9100;
            font-size:15px;
            border:none;
        }

        .table_row_ED{
            padding:6px;
            font-size:12px;
            border-radius:6px;
            color:#fff;
            text-align:center;
            border:none;
            margin:0 5px 5px 0;
        }

        .edit_product_action_button {
            padding:9px 12px;
            border-radius:9px;
            margin-right:9px;
            color:#fff;
            font-weight:bold
        }

        .edit_product_input {
            border-left:50px solid #000;
            border-radius:4px;
            height:18px;
            width:80%;
            margin-bottom:15px;
            padding:6px;
        }

        .admin-pages-input{
            border-radius:6px;
            border:1px solid #888;
        }

        .menu_list .fa {
            margin-right:6px;
        }

        /*
        .goog-logo-link {
            display:none !important;
        } 
            
        .goog-te-gadget {
            color: transparent !important;
        }
        
        .goog-te-banner-frame.skiptranslate {
            display: none !important;
        }        

        */
    </style>
    <title>Admin - $site_name </title>

</head>
<body>
    <div class="headers" style="height:36px;width:100%;"> 
        <div style="display:flex;justify-content:center;position:absolute;top:10px;left:15px">
            <div class="menu-icon" style="font-size:21px"><label for = "menu-box"><i class="fa fa-bars"></i></label></div>

            <div style="font-size:18px;margin:-14px 19px 0px 3px"><h3 class="site_name"><a href="/">Bilo<span style="color:#ff9100">Online</span> - <i class="fa fa-lock"></i> Admin</a></h3></div>
        </div>

        <div style="position:absolute;float:right;top:5px;right:12px">
            <i style="background-color:#ff9100;color:#fff; border-radius:6px;padding:6px 8px;text-align:center;margin:6px 3px 0px 6px;" class="fa fa-user"></i> 
            $Hi_admin
            <i  style="background-color:#ff9100;color:#fff; border-radius:100%;padding:3px 5px;text-align:center;margin:6px 3px 0px 6px;"  class="fa fa-angle-down"></i>
        </div>
    </div> 

    <input type="checkbox" id="menu-box" class="menu-box" style="display:none"/>
    
    <ul class="menu_list"> 
        <li><i class="fa fa-home"></i> <a href="/admin-products">Products</a></li>

        <li><i class="fa fa-users"></i> <a href="/customers">Customers</a></li>
        <li><i class="fa fa-bolt"></i> <a href="/orders">Orders</a></li>

        $main_admin_access

        <li><i class="fa fa-key"></i> <a href="/admin-reset-password">Reset Password</a></li>

        <li><a href="/logout" style="color:#fff;font-weight:bold;background-color:#0bee3ccc;padding:6px;border-radius:12px">Log out</a></li>
        
        <label for="menu-box"><div class="grey_area"></div></label>
    </ul>

    <div style="display:flex;margin:50px 1%;justify-content:space-around;width:80%">
        <div class="below_header_div" style="background-color:#ff9100">
            <div class="numbers_and_fa">
                <div class="numbers"><a href="/admin-products" style="color:#000">$num_of_prod</a></div>
                <div><a href="/admin-products" style="color:#000"><i class="fa fa-user"></i></a></div>
            </div>
            <div><a href="/admin-products" style="color:#000">Products</a></div>
        </div>

        <div class="below_header_div">
            <div class="numbers_and_fa">
                <div class="numbers"><a href="/customers" style="color:#000">$num_of_users</a></div>
                <div><a href="/customers" style="color:#000"><i class="fa fa-user"></i></a></div>
            </div>
            <div><a href="/customers" style="color:#000">Registered Users</a></div>
        </div>

        <div class="below_header_div" style="background-color:#f3d111">
            <div class="numbers_and_fa">
                <div class="numbers"><a href="/orders" style="color:#000">$num_of_orders</a></div>
                <div><a href="/orders" style="color:#000"><i class="fa fa-user"></i></a></div>
            </div>
            <div><a href="/orders" style="color:#000">Pending Orders</a></div>
        </div>
    </div>
HTML;
}

public static function footer($site_name = SITE_NAME, $site_url = SITE_URL){
    echo <<<HTML
        <hr />
    <div style="text-align:center">
        <div style="display:flex;text-align:center;margin:0 20% 0 20%;">
            
            &nbsp; <h3>$site_name</h3>
        </div>
        &copy;2025 - All Rights Reserved
    </div>

<!--site-users page scripts starts-->    
<script>
    function show_div(vari) {
        if (document.getElementById(vari).style.display == "none") {
            document.getElementById(vari).style.display = "block";
        } else if (document.getElementById(vari).style.display == "block") {
            document.getElementById(vari).style.display = "none";
        }
    }

    
    function create_content(varia, numb) {
        get_id = varia + (numb).toString();
        //get_html = document.getElementById(get_id).innerHTML;

        get_id2 = "content_space" + numb;

        document.getElementById(get_id2).innerHTML = "";
        document.getElementById(get_id2).innerHTML = document.getElementById(get_id).innerHTML;
        document.getElementById(get_id2).style = "display:block";
    }

    function hide_content_space(cont_name, numbr){
        
        con_space = "content_space" + (numbr).toString();
        
        document.getElementById(con_space).innerHTML = ""; //for internet explorer
        document.getElementById(con_space).style = "display:none";
    }

    function ajax_search(){
        sq = document.getElementById("search_input").value;
        obj = new XMLHttpRequest;
        obj.onreadystatechange = function(){
            if(obj.readyState == 4){
                document.getElementById("search").innerHTML = obj.responseText;
            }
        }

        obj.open("GET","/ajax_search.php?search_query="+sq+"&page=admin-products");
        obj.send(null);
    }

    function search_icon(){
        location = "/admin-products/" + document.getElementById("search_input").value;
    }

    var loadFile = function(event, img_id_num) { //function to make pictures visible to user before upload to server
        var img_id = document.getElementById(img_id_num);
        img_id.src = URL.createObjectURL(event.target.files[0]);
        img_id.onload = function(){
            URL.revokeObjectURL(img_id.src);
        }
    }
</script>
<!--site-users page scripts ends -->

    <script>
        function copyText(linkText){
            x = document.getElementById(linkText);
    
            x.select();
            x.setSelectionRange(0, 99999);
    
            //navigator.clipboard.writeText(x.value);
            document.execCommand('copy');
            alert("copied text: " + x.value);
        }
    </script>
</body>
</html>

HTML;
    }
    //Class admin_Segments ends..
}

//$admin_Segments = new admin_Segments;
admin_Segments::inject($pdo);
?>