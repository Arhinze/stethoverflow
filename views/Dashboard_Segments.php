<?php

include_once("/home/u590828029/domains/aguanit.com/public_html/php/account-manager.php");
include_once("/home/u590828029/domains/aguanit.com/public_html/views/Index_Segments.php");

class Dashboard_Segments extends Index_Segments{
    public static function header($site_name = SITE_NAME_SHORT, $site_url = SITE_URL, $site_mining_page_url = SITE_MINING_PAGE_URL, $Hi_user = "",$title=SITE_NAME){

    
    $css_version = filemtime("/home/u590828029/domains/aguanit.com/public_html/static/style.css");

    echo <<<HTML
    <!doctype html>
    <html lang="en">
    <head>
        <link rel="stylesheet" href="$site_url/static/style.css?$css_version"/>
        <link rel="icon" type="image/x-icon" href="$site_url/static/images/favicon.png"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0 user-scalable=no">
        <title>$site_name</title>        
        <style>
            body{
                background-color: #000500;
            }
        </style>
    </head>
    <body>

    <div class="body">
        <div class="headers" style="height:36px;padding:16px 16px 8px 8px"> 
            <div style="font-size:18px;margin:-16px 19px 0px 14px;float:left">
                <h3 class="site_name">
                    <a href="$site_url">$site_name</a>
                </h3>
            </div>

            <div class="menu_and_user_icon">

            <span class="other-menu-icon" style="">
                <label for = "menu-box"><i class="fa fa-bars"></i></label>
            </span>
            </div>
        </div> 

        <div class="hi_user"> 
            <!-- Google Translate div --> 
            <!--<div class="clear"><div id="google_translate_element" style="position:fixed;float:left;left:13px;top:59px;background-color:#fff;border-radius:4px;padding:0px 3px"></div></div>-->
            
            <!-- Hi user --> 
            
            <span style="float:right;background-color:#042c06;border-radius:6px;margin:3px;font-size:12px;padding:1px 0 6px 9px">
                Hi $Hi_user

                <i style="background-color:#0bee3ccc;color:#fff; border-radius:6px;padding:6px 8px;text-align:center;margin:6px 9px 0px 6px;" class="fa fa-user"></i> 
            </span>
        </div>
    
        <a name="#top"></a>

        <input type="checkbox" id="menu-box" class="menu-box"/>

        <ul class="menu-list"> 
            
            <li class="x"><label for="menu-box"><i class="fa fa-times"></i></label></li>
            
            <li><a href="$site_url/dashboard">Dashboard</a></li>
            <li><a href="$site_url/settings">Settings</a></li>

            <li class="clear" style="padding-bottom:16px">
                <label for="hidden-menu-item">
                    <span style="float:left">Referrals</span> <i class="fa fa-angle-down" style="float:right"></i> 
                </label>
            </li>

            <input type="checkbox" style="display:none" id="hidden-menu-item" class="hidden-menu-item"/>
            <div class="hidden-menu-div">
                <a href="$site_url/referred-users">Referred Users</a><br />
                <a href="$site_url/referred-commissions">Referred Commissions</a>
            </div>
            
            <li><a href="$site_url/reset-password">Reset Password</a></li>

            <li><a href="$site_url/claim-airdrop" style="color:#fff;font-weight:bold;background-color:#0bee3ccc;padding:6px;border-radius:12px">Claim Airdrop</a></li>

            <li><a href="$site_mining_page_url/logout.php" style="color:#fff;font-weight:bold;background-color:red;padding:6px;border-radius:12px">Log out</a></li>
        </ul>     
HTML;
    }



    public static function dashboard_scripts($site_name = SITE_NAME_SHORT, $site_url = SITE_URL, $site_mining_page_url = SITE_MINING_PAGE_URL){

        //Index_Segments::index_scripts();

        echo <<<HTML
        <!-- Footer - dashboard_script -->
        <script>
                         
            const pop_up_collection = document.getElementsByClassName("pop_up");
                                 
            for (let i=0; i < pop_up_collection.length; i++){
                //pop_up_collection[i].style = "display:none";
                                            
                var innerHT = pop_up_collection[i].innerHTML;
                            
                var newInnerHT = innerHT + "<span style='float:right;position:absolute;top:6px;right:6px'><i class='fa fa-times' onclick='close_pop_up()'></i></span>";
                          
                pop_up_collection[i].innerHTML = newInnerHT;
            }
                           
            function close_pop_up() {
                //const pop_up_collection = document.getElementsByClassName("pop_up");
                i = 0;
                               
                for (i=0; i<pop_up_collection.length; i++){
                    pop_up_collection[i].style.display = "none";
                }  
            } 

            function copyText(link_text_id){
                x = document.getElementById(link_text_id);
                x.select();
                x.setSelectionRange(0, 99999);
        
                document.execCommand('copy');
                //alert("copied text: " + x.value);
                document.getElementById("alert_text_copied").innerHTML = "<div class='pop_up'>copied text: <b><br />" + x.value + "</b> <span style='float:right;position:absolute;top:6px;right:6px'><i class='fa fa-times' onclick='close_pop_up()'></i></span><div/>";
            }
        </script>

        <script>
            function boost_mining_speed() {
                document.getElementById("boost_mining_speed").innerHTML = "<div class='pop_up'> This feature is coming soon. <span style='float:right;position:absolute;top:6px;right:6px'><i class='fa fa-times' onclick='close_pop_up()'></i></span></div>";
            }

            function rotate_360() {
                if(document.getElementById("small_coin").className !== "rotate_360_once") {
                    document.getElementById("small_coin").className = "rotate_360_once";
                } else {
                    document.getElementById("small_coin").className="no_class_name";
                } 
            }
        </script>



        <!-- Mining Scripts -->
        <script>
            function start_mining(u_name, u_password) {
                //alert("Active !!!")
                document.getElementById("mining_status").innerHTML = "active";
                document.getElementById("inner_button").className = "rotate_360";
                if (document.getElementById("mining_time_left").innerHTML == "00:00:00") {
                    document.getElementById("mining_time_left").innerHTML = "48:00:00";
                }

                obj = new XMLHttpRequest;
                obj.onreadystatechange = function(){
                    if(obj.readyState == 4){
                        if (document.getElementById("ajax_mine")){
                            document.getElementById("ajax_mine").innerHTML = obj.responseText;
                        }
                    }
                }
        
                obj.open("GET","/ajax_mining_page.php?un="+u_name+"&up="+u_password);
                obj.send(null);
            }

            setInterval(() => {
                if (document.getElementById("mining_status")){ //checks is id exists at all
                if (document.getElementById("mining_status").innerHTML == "active"){
                    //increment amount earned per second
                    var amount = document.getElementById("amount_mined").innerHTML;
                    var new_amount = Number(amount) + 0.0000058;
                    document.getElementById("amount_mined").innerHTML = new_amount.toFixed(6);

                    //calculate time left
                    var time_left = document.getElementById("mining_time_left").innerHTML;
                    var time_left_array = time_left.split(":");
                    var total_remaining_mining_seconds = (((Number(time_left_array[0]))*60*60)+((Number(time_left_array[1]))*60)+(Number(time_left_array[2])));

                    total_remaining_mining_seconds -= 1; //decrease it every second setInterval() is called

                    var total_remaining_mining_minutes = Math.floor(total_remaining_mining_seconds/60);
                    var remaining_mining_hours = Math.floor(total_remaining_mining_minutes/60);
                    var remaining_mining_minutes = total_remaining_mining_minutes - (remaining_mining_hours*60);
                    var remaining_mining_seconds = total_remaining_mining_seconds - (total_remaining_mining_minutes*60);
                    //OR var remaining_mining_seconds = total_remaining_mining_seconds % 60 //(modulus ~ reurns the remainder)

                    //so it appears in 00:00:00 format instead of 0:0:00 or otherwise
                    remaining_mining_hours = remaining_mining_hours >= 10 ? remaining_mining_hours : "0"+remaining_mining_hours.toString();
                    remaining_mining_minutes = remaining_mining_minutes >= 10 ? remaining_mining_minutes : "0"+remaining_mining_minutes.toString();
                    remaining_mining_seconds = remaining_mining_seconds >= 10 ? remaining_mining_seconds : "0"+remaining_mining_seconds.toString();

                    var new_time_left = remaining_mining_hours.toString() + ":" + remaining_mining_minutes.toString()  + ":" + remaining_mining_seconds.toString() ;

                    document.getElementById("mining_time_left").innerHTML = new_time_left;

                    /*for testing purposes:
                        
                    document.getElementById("test").innerHTML = "<b>Test:</b><br />"
                    +"time_left_array: "+time_left_array+"<br />"
                    +"time_left_array[0]: "+time_left_array[0].toString()+"<br />"
                    +"time_left_array[1]: "+time_left_array[1].toString()+"<br />"
                    +"time_left_array[2]: "+time_left_array[2].toString()+"<br />"
                    +"total_remaining_mining_seconds: "+total_remaining_mining_seconds.toString()+"<br />"
                    +"total_remaining_mining_minutes: "+total_remaining_mining_minutes.toString()+"<br />"+"remaining_mining_hours:"+remaining_mining_hours.toString()+"<br />"+"remaining_mining_minutes:"+remaining_mining_minutes.toString()+"<br />"+"remaining_mining_seconds: "+remaining_mining_seconds.toString();
                    */
                    }
                }
            }, 1000);
        </script>
        <!-- Mining Script ends -->

    <noscript> 
        Texts won't display well. please enable Javascript.
    </noscript>
HTML;
    }

    public static function dashboard_footer(){
        Index_Segments::footer($site_name = SITE_NAME_SHORT, $site_url = SITE_URL, $site_mining_page_url = SITE_MINING_PAGE_URL, $additional_scripts = Dashboard_Segments::dashboard_scripts());
    }
}