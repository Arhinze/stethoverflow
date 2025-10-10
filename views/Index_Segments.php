<?php
ini_set("display_errors", '1'); //for testing purposes..

include_once($_SERVER["DOCUMENT_ROOT"]."/php/connection.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/php/account-manager.php");

class Index_Segments{
    public static function inject($obj) {
        Index_Segments::$pdo = $obj;
    }
    protected static $pdo;

    public static function main_header($site_name = SITE_NAME_SHORT) {
        echo <<<HTML
            <!-- start of .headers --> 
            <div class="headers">
                <div class="header_search_icon" style="margin-left:9px;margin-top:8px;padding:1px 5px" onclick="show_div('header_search')">
                    <i class="fa fa-search" style="font-size:12px"></i> Search
                </div> 
                <div class="header_search" id="header_search" style="display:none">
                    <input type="text" placeholder="search for .." class="header_input" id="index_search" onkeyup="ajax_index_search()"/>
                    <span class="x_remove" onclick="clear_and_close('header_search')"><i class="fa fa-times"></i></span>
                </div>
                <h1 class="site_name">
                    <a href="/">$site_name<!--site_name--></a>
                </h1>
                <div style="margin-right:12px">
                    <i class="fa fa-plus-circle"></i> Add
                </div>
            </div> <a name="#top"></a> 
            <!-- end of .headers --> 
            <div class="headers" style="top:50px;background-color:#fff;color:#888;font-size:21px;padding:12px">
                <div class="" style="color:#acc5f8"><i class="fa fa-home"></i></div>
                <div class=""><i class="fa fa-file-text-o"></i></div>
                <div class=""><i class="fa fa-pencil-square-o"></i></div>
                <div class=""><i class="fa fa-users"></i></div>
                <div class="" style="margin-right:12px"><i class="fa fa-bell-o"></i></div>
            </div>
HTML;
    }
    
    public static function header($site_name = SITE_NAME_SHORT, $site_url = SITE_URL, $Hi_user = "", $title=SITE_NAME){
        
        $main_header = Index_Segments::main_header();
        $css_version = filemtime($_SERVER["DOCUMENT_ROOT"]."/static/style.css");

        if (isset($_GET["ref"])) {
            $ref = htmlentities($_GET["ref"]);

            if(isset($_COOKIE["ref"])){
                //delete existing referer cookie
                setcookie("ref", $ref, time()-(24*3600), "/");
            }

            //set new referer cookie:
            setcookie("ref", $ref, time()+(12*3600), "/");
        }

        echo <<<HTML
        <!doctype html>
        <html lang="en">
        <head>
          
            <link rel="stylesheet" href="/static/style.css?$css_version"/>
            <link rel="icon" type="image/x-icon" href="/favicon.ico"/>
            <link rel="stylesheet" href="/static/font-awesome-4.7.0/css/font-awesome.min.css">
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=RocknRoll+One&display=swap" rel="stylesheet">
            <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito|Hammersmith+One|Trirong|Arimo|Prompt"/>
            
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
                
            <title>$title</title>
            <meta name="google-site-verification" content="myB30Ys5EUELpwugPrQITsFCTnKdfNCf9Owd0t6pjmM" /><!-- google site ownership verification -->  
        </head>
        <body>
            $main_header
            
            <div id="search_hint"></div>
            <!-- used by index_ajax_search() function-->
HTML;
       }
                
        public static function body($site_name = SITE_NAME_SHORT, $site_url = SITE_URL){
            $site_name_uc = strtoupper($site_name);

            echo <<<HTML
                <!-- .main_body starts -->
                <div class="main_body">
                    <div style="display:flex;background-color:#fff">
                        <div style="width:30px;height:30px;border:2px solid #d6e3fd;border-radius:100%">
                            <a href="$site_url/static/images/profile.png"><img src="/static/images/profile.png" style="width:27px;height:27px;border-radius:100%;margin:1.35px 0 0 1.35px"/></a>
                        </div>
                        <div style="width:90%;margin-left:6px">
                            <input type="text" placeholder="What do you want to ask or share?" style="width:100%" class="input"/>
                        </div>
                    </div>
                    <div style="display:flex;justify-content:space-between">
                        <div><i class="fa fa-question-circle-o"></i> Ask</div>
                        <div><i class="fa fa-edit"></i> Answer</div>
                        <div><i class="fa fa-pencil"></i> Post</div>
                        <div></div>
                    </div>
                </div>
                <!-- .main_body ends -->
HTML;

       }
                                                                
       public static function index_scripts(){
        echo <<<HTML
                                                                
        <!-- Footer - index_scripts -->
        <script>
            function show_div(vari) {
                if (document.getElementById(vari).style.display == "none") {
                    document.getElementById(vari).style.display = "block";
                } else if (document.getElementById(vari).style.display == "block") {
                    document.getElementById(vari).style.display = "none";
                }
            }
                                                                                         
            const collection = document.getElementsByClassName("invalid");
                                                                                                 
            for (let i=0; i < collection.length; i++){
                //collection[i].style = "display:none";
                                                                                                            
                var innerHT = collection[i].innerHTML;
                                                                                            
                var newInnerHT = innerHT + "<span style='float:right;margin:4px 18px'><i class='fa fa-times' onclick='hide_invalid_div()'></i></span>";
                          
                collection[i].innerHTML = newInnerHT;
            }
                                                                                           
            function hide_invalid_div() {
                //const collection = document.getElementsByClassName("invalid");
                i = 0;
                for (i=0; i<collection.length; i++){
                    collection[i].style.display = "none";
                }  
            }
                                                                
            //Implementing multi-line placeholder for textarea html documents
            var textAreas = document.getElementsByTagName('textarea');
                                                                
            Array.prototype.forEach.call(textAreas, function(elem) {
                elem.placeholder = elem.placeholder.replace(/\\n/g, '\\n');
            });
                                                                
            function show_bt_input_div(){
                document.getElementById("bt_input_div").style.display = "block";
            }
                                                                        
            function close_bt_input_div(){
                document.getElementById("bt_input_div").style.display = "none";
            }
                                                                    
            function calculate_total(){
                total_num = document.getElementById("total_number").value;
                amt_for_each = document.getElementById("amount_to_pay_each_person").value;
                total_amount = Number(total_num) * Number(amt_for_each);
                                                                    
                document.getElementById("total_to_transfer_text").innerHTML = "<div style='margin:12px 3px'>Total cost of transaction: <b><i class='fa fa-naira'></i>N "+total_amount.toString()+"</b></div>";
                                                                
                obj = new XMLHttpRequest;
                obj.onreadystatechange = function(){
                    if(obj.readyState == 4){
                        if (document.getElementById("current_balance_text")){
                            document.getElementById("current_balance_text").innerHTML = obj.responseText;
                        }
                    }
                }
                                                                        
                obj.open("GET","/ajax/ajax_cb.php?total_="+total_amount);
                obj.send(null);
                                                                
                //disable button and allow only when total_amount < current balance and amt_for_each > 100
                button_status = document.getElementById("proceed_to_pay_button");
                current_balance_text = document.getElementById("current_balance_text");
                if((Number((current_balance_text.innerHTML).replace("N", "")) >= total_amount) & (amt_for_each >= 10)) {
                    button_status.style="background-color:#333131";
                    button_status.disabled = false;
                } else {
                    button_status.style="background-color:#888";
                    button_status.disabled = true;
                }
                                                                
                //turn current balance text green or red depending on if it's > or < than total_amount
                if(Number((current_balance_text.innerHTML).replace("N", "")) >= total_amount) {
                    current_balance_text.style="color:green";
                } else {
                    current_balance_text.style="color:red";
                }
            }
        </script>

        <script>
            function ajax_index_search(){
                sq = document.getElementById("index_search").value;
                obj = new XMLHttpRequest;
                obj.onreadystatechange = function(){
                    if(obj.readyState == 4){
                        document.getElementById("search_hint").style.display = "block";
                        document.getElementById("search_hint").innerHTML = obj.responseText;
                    }
                }
        
                obj.open("GET","/ajax/ajax_index_search.php?search_query="+sq);
                obj.send(null);
            }
        
            function search_icon(){
                location = "/search.php?query=" + document.getElementById("index_search").value;
            }

            function clear_and_close(s_q) {
                document.getElementById("search_hint").style.display = "none";
                show_div(s_q)
            }
        </script>
HTML;
        }
                                                                
                                                                
        public static function footer($site_name = SITE_NAME_SHORT, $site_url = SITE_URL, $additional_scripts = "", $whatsapp_chat = "on"){ 
                                                                            
            $index_scripts = Index_Segments::index_scripts();    

            if ($whatsapp_chat == "on") {
                echo <<<HTML
                    <!-- .whatsapp_box starts -->
                    <div class="whatsapp_box" id="whatsapp_box" style="display:none;position:fixed;bottom:129px;right:18px;background-color:#fff;border-radius:9px;width:75%;box-shadow:0 0 3px 0 #888">
                        <div class="whatsapp_box_top" style="display:flex;justify-content:space-around;height:30%;padding:12px;background-color:green;color:#fff;border-radius:9px 9px 0 0">
                            <div style="width:10%;margin-right:0"><i class="fa fa-whatsapp" style="font-size:42px;color:#fff"></i></div>
                            <div style="width:80%;text-align:left;margin-left:0">
                                <div style="font-size:21px;margin-bottom:6px">Start a Conversation</div>
                                <div>Hi! Click one of our member below to chat on <b>WhatsApp</b></div>
                            </div>
                        </div>
                        <div class="whatsapp_box_bottom" style="padding:12px">
                            <div style="font-size:12px;color:#888">The team typically replies in a few minutes.</div>
                            <a href="https://wa.me/2348147964486" style="color:#000"><!-- whatsapp link starts -->
                            <div style="display:flex;border-radius:0 6px 6px 0;background-color:#d9eee0;margin-top:15px">
                                <div style="display:flex;border-left:3px solid green">
                                    <div style="color:#fff;font-size:24px;padding:6px 8px;margin:15px 6px;border-radius:100%;background-color:green;"><i class="fa fa-whatsapp" onclick="show_div('whatsapp_box')"></i></div>
                                    <div style="font-size:21px;margin:16px 3">Support</div>
                                </div>
                                <div style="margin-left:42px;margin-top:21px;font-size:18px;color:green"><i class="fa fa-whatsapp"></i></div>
                            </div>
                            </a><!-- whatsapp link ends -->
                        </div>
                    </div>
                    <!-- .whatsapp_box ends -->
    
                    <!-- fixed whatsapp sticker(bottom-right) starts -->
                    <div style="color:#fff;font-size:33px;padding:9px 12px;border-radius:100%;background-color:green;position:fixed;bottom:72px;right:18px"><i class="fa fa-whatsapp" onclick="show_div('whatsapp_box')"></i></div>
                    <!-- fixed whatsapp sticker(bottom-right) ends -->
HTML;
            }
                                                                
        echo <<<HTML
        <br/><br/><br/><br/>
            <div class="footer"><!-- .footer starts --> 
                <div class="footer_site_name">$site_name</div>

                <div>Steth Overflow is designed to demystify medical facts and to bring you closer to your doctor for better healthcare.</div>

                <div class="footer_fa_links">
                    <a href="https://www.facebook.com/share/19MmpBuEn6/?mibextid=wwXIfr" style="color:#000"><i class="fa fa-facebook"></i></a> &nbsp;
                    <a href="https://www.tiktok.com/@bilo.ng?_t=ZM-8xVIXWH0E21&_r=1"><img src="/static/images/tiktok_logo.png" style="height:24px;width:auto;margin-bottom:-4px"/></a> &nbsp;
                    <a href="" style="color:#000"><i class="fa fa-instagram"></i></a> &nbsp;
                    <a href="https://www.youtube.com/@biloonline" style="color:#000"><i class="fa fa-youtube-play"></i></a> &nbsp;
                </div>

                <div class="footer_heading">Blog</div>
                Terms & Conditions
                Sitemap
                Press   

                <div class="footer_heading">Support</div>
                Documentation
                <b><a href="/contact">Help Center</a></b>
                General FAQs
                
                <div class="footer_heading">Newsletter</div>
                Stay medically up to date by joining our newsletter.
                
                 
                
                <div style="border-top:1px dotted #888;margin-top:15px;padding:15px 0;text-align:center">© 2025 $site_name <a href="/privacy-policy" style="font-weight:bold">Privacy Policy</a> All rights reserved. Designed & developed by Medivify</div>
            </div><!-- .footer ends -->                                                          
            $index_scripts
            $additional_scripts


        </body>
        </html>    
HTML;
    }
}

Index_Segments::inject($pdo);
?>