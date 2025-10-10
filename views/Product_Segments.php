<?php
ini_set("display_errors", '1'); //for testing purposes..

include_once($_SERVER["DOCUMENT_ROOT"]."/php/connection.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/views/Index_Segments.php");

$prod_url = "no-product";

if(isset($_GET["url"])) {
    $prod_url = htmlentities($_GET["url"]);
}

$product_stmt = $pdo->prepare("SELECT * FROM products WHERE product_url = ? LIMIT ?, ?");
$product_stmt->execute([$prod_url, 0, 1]);
$product_data = $product_stmt->fetch(PDO::FETCH_OBJ);

if(!$product_data){
    header("location: /404.php");
}

$num_products_in_cart = 0;
$customer_id = $user_unique_id;
$num_products_in_cart_stmt = $pdo->prepare("SELECT * FROM orders_processor WHERE customer_id = ? AND qty > ? LIMIT ?, ?");
$num_products_in_cart_stmt->execute([$customer_id, 0, 0, 100]);
$num_products_in_cart_data = $num_products_in_cart_stmt->fetchAll(PDO::FETCH_OBJ);
$num_products_in_cart = count($num_products_in_cart_data);

$orders_qty = 0;
$added_or_not = "Add to my picks";
$orders_qty_stmt = $pdo->prepare("SELECT * FROM orders_processor WHERE customer_id = ? AND product_id = ?");
$orders_qty_stmt->execute([$customer_id, $product_data->product_id]);
$orders_qty_data = $orders_qty_stmt->fetch(PDO::FETCH_OBJ);
if($orders_qty_data) {
    $orders_qty = $orders_qty_data->qty;
    if($orders_qty == 0){
        $orders_qty = "<span style='color:#888'>0</span>";
    } else {
        $added_or_not = "Added to my picks <i class='fa fa-check-circle-o' style='color:green'></i>";
    }
}

define("PRODUCT_URL", $prod_url);
define("PRODUCT_ID", $product_data->product_id);
define("PRODUCT_IMAGE1", $product_data->image1);
define("PRODUCT_IMAGE2", $product_data->image2);
define("PRODUCT_IMAGE3", $product_data->image3);
define("PRODUCT_DESC", $product_data->description);
define("PRODUCT_PRICE", $product_data->price);
define("PRODUCT_CATEGORY", $product_data->category);
define("NUM_OF_PRODUCTS_IN_CART", $num_products_in_cart);
define("ORDERS_QTY", $orders_qty);
define("ADDED_OR_NOT", $added_or_not);


class Product_Segments extends Index_Segments{     
    public static function body(
        $site_name = SITE_NAME_SHORT, 
        $site_url = SITE_URL, 
        $product_id = PRODUCT_ID,
        $product_url = PRODUCT_URL,
        $image1 = PRODUCT_IMAGE1,
        $image2 = PRODUCT_IMAGE2,
        $image3 = PRODUCT_IMAGE3,
        $description = PRODUCT_DESC,
        $price = PRODUCT_PRICE,
        $category = PRODUCT_CATEGORY,
        $number_of_products_in_cart = NUM_OF_PRODUCTS_IN_CART,
        $orders_qty = ORDERS_QTY,
        $added_or_not = ADDED_OR_NOT
    ){
        $price="N ".number_format($price);
        echo <<<HTML
            <div class="main_body" style="margin:0"><!-- .main_body starts -->
                <div class="product_image_div"><!-- .product_image_div starts -->
                    <img class="product_image" src="/static/images/$image1"/>
                    <div class="upi_top_left">
                        <a href="/" style="color:#000"><i class="fa fa-angle-left" style="font-size:18px;padding:6px 12px"></i></a>
                    </div>
                    <div class="upi_top_right">
                        <i class="fa fa-search" style="margin-right:3px" onclick="show_div('header_search')"></i>
                        <i class="fa fa-share-alt"></i>
                    </div>

                    <div class="upi_bottom_left">
                        <div class="upi_bl_contents">
                            <i class="fa fa-star"></i> "High quality"
                        </div>
                        <div class="upi_bl_contents">
                            <i class="fa fa-fire" style="background-color:red"></i> 359 people bought this item
                        </div>

                        <div class="upi_bl_contents" style="background-color:#fff;color:#000">
                            <b>Item 1/6</b> <span style="color:#888">| Color </span>
                        </div>
                    </div>
                    <div class="upi_bottom_right">
                        <i class="fa fa-heart"></i>
                    </div>
                </div><!-- .product_image_div ends -->   

                <div class="below_product_images" style="margin-top:0"><!-- .below_product_images starts -->  
                    <div class="additional_product_images_div_container">
                        <div class="additional_product_images_div">
                            <img class="additional_product_image" src="/static/images/$image1"/>
                        </div>
                        <div class="additional_product_images_div">
                            <img class="additional_product_image" src="/static/images/$image2"/>
                        </div>
                        <div class="additional_product_images_div">
                            <img class="additional_product_image" src="/static/images/$image3"/>
                        </div>
                    </div>
                    <div class="product_description">
                        <!--Original Unlocked Apple iPhone 12 Pro Face ID 5G 6GB RAM 128/256GB ROM 12MP 6.7'' NFC France shipping usd smartphone 99%-->
                        $description
                    </div>

                    <div class="product_fa_star">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        &nbsp; 4.8 &nbsp; | &nbsp; 2000+ sold
                    </div>

                    <div class="product_price_div">
                        <div class="product_price_top">
                            <div class="product_price_head">Choice Deals</div>
                            <div class="product_price_img_div">
                                <img class="product_price_img" src="/static/images/tiny_site_logo.png"/>
                            </div>
                        </div>
                        <div class="product_price_bottom">
                            $price
                        </div>
                    </div>
                    <div>
                        <p style="margin-bottom:-7px"><i class="fa fa-address-card-o"></i>&nbsp; Pay in NGN</p>
                        <p><i class="fa fa-ban"></i>&nbsp; Tax excluded</p>
                    </div>
                </div><!-- .below_product_images ends -->

                <div class="below_product_images"><!-- .below_product_images starts again --> 
                    <div class="commitment_container"><!-- .commitment_container starts -->
                        <div class="commitment_head">
                            <b><span style="padding:1px 4px;background-color:#ff9100;border-radius:6px">Choice</span>&nbsp; <span style="font-size:15px">Bilo<span style="color:#ff9100">Online</span> Commitment</span></b>
                        </div>
                        <div class="commitment">
                            <div class="commitment_left">
                                <i class="fa fa-bus"></i> &nbsp; Free shipping to any location.
                            </div>
                            <div class="commitment_right">
                            </div>
                        </div>

                        <div class="commitment">
                            <div class="commitment_left">
                                <i class="fa fa-reply-all"></i>&nbsp; <b>Return & refund policy</b>
                            </div>
                            <div class="commitment_right">
                                <i class="fa fa-angle-right"></i>
                            </div>
                        </div>

                        <div class="commitment">
                            <div class="commitment_left">
                                <i class="fa fa-shirtsinbulk"></i>&nbsp; <b>Security & Privacy</b>
                                &nbsp; Safe payments, secure personal...
                            </div>
                            <div class="commitment_right">
                                <i class="fa fa-angle-right"></i>
                            </div>
                        </div>
                    </div><!-- .commitment_container ends -->
                </div><!-- .below_product_images ends again -->  



                <div class="below_product_images"><!-- .below_product_images starts again (for Related Products) -->  
        
                    <!-- Related Products start -->
                    <div class="mpdc_heading">Related products</div>
                    <div class="multiple_product_div_container"><!-- .multiple_product_div_container starts -->
                    
                    <div class="multiple_product_div"><!-- .flex_div starts(.multiple_product_div) --> 
HTML;
                $obj_array = [];
                $category_array = explode(";", $category);
                array_pop($category_array);
                //echo "<h1 style='margin-top:30px'>";print_r($category_array);echo "</h1>"; --I used this for testing
                foreach($category_array as $cat_arr){
                    $category_array_stmt = Index_Segments::$pdo->prepare("SELECT * FROM products WHERE category LIKE ? ORDER BY product_id DESC LIMIT ?, ?");
                    $category_array_stmt->execute(["%$cat_arr%", 0, 12]);
                    $category_array_data = $category_array_stmt->fetchAll(PDO::FETCH_OBJ);

                    foreach($category_array_data as $cd) {
                        if(!in_array($cd, $obj_array)) {
                            $obj_array[] = $cd;
                        }
                    }
                }

                $main_obj_array = $obj_array;//array_unique($obj_array);

                if (count($main_obj_array)>0) { 
                    foreach ($main_obj_array as $moa) {
                        $moa_price = number_format($moa->price);
                        //$short_desc = substr($l3->description,0,21);
                        echo <<<HTML
                            <!-- multi - 1 to 5 -->
                            <div class="deal_div"><!-- .deal_div starts --> 
                                <a href ="/product/$moa->product_url" style="color:inherit"><!-- start of link to product page -->
                                <img src="/static/images/$moa->image1" class="deal_img"/>   
                                <div class="below_deal_img"><!-- .below_deal_img starts -->
                                    <div class="topselling_choice_and_title">
                                        <span>
                                            $moa->product_name
                                        </span>
                                    </div>
                                    <span class="deal_price_black">
                                        NG N$moa_price
                                    </span>  
                                </div><!-- .below_deal_img ends -->
                                </a><!-- end of link to product page -->
                            </div><!-- .deal_div ends -->
HTML;
                    }
                }

        echo <<<HTML
                    </div><!-- .flex_div(.multiple_product_div) ends -->
                    </div><!-- .multiple_product_div_container ends -->
                    <!-- Related Products end -->
                </div><!-- .below_product_images ends again (for Related Products)-->  

                <div class="below_product_images">
                
                </div>

                
                <div class="add_to_my_picks"><!-- .add_to_my_picks starts -->
                    <div class="long_action_button" onclick='added_or_not($product_id)' style="background-color:#ff9100;box-shadow: 0 0 6px #888 inset">
                        <i class="fa fa-shopping-cart"></i>&nbsp; <span id="added_or_not_id">$added_or_not</span>
                    </div>
                </div><!-- .add_to_my_picks ends -->
                
        
                <div id="continue_to_cashout" class="continue_to_cashout" style="display:none"><!-- .continue to cashout starts -->
                    <div style="height:20%;width:100%;background-color:#888;opacity:0.1" onclick="show_div('continue_to_cashout')"></div><!-- dummy "grey area" div used to close continue_to_cashout div-->

                    <div style="height:80%;border-radius:21px 21px 0 0;background-color:#fff;box-shadow:0 0 3px 0 #ff9100"><!-- ..height:80% starts -->
                        <div style="margin-bottom:12px;height:40%">
                            <center><img src="/static/images/$image1" style="width:auto;height:100%"/></center>
                        </div>
                        <div class="below_continue_to_cashout_img" style="padding:12px"><!-- .below_continue_to_cashout_img starts -->
                            <div class="product_price_div"><!-- .product_price_div starts -->
                                <div class="product_price_top">
                                    <div class="product_price_head">Choice Deals</div>
                                    <div class="product_price_img_div">
                                        <img class="product_price_img" src="/static/images/tiny_site_logo.png"/>
                                    </div>
                                </div>
                                <div class="product_price_bottom">
                                    $price
                                </div>
                            </div><!-- .product_price_div ends -->
        
                            <div><!-- .pay in NGN starts -->
                                <p style="margin-bottom:-7px"><i class="fa fa-address-card-o"></i>&nbsp; Pay in NGN</p>
                                <p><i class="fa fa-ban"></i>&nbsp; Tax excluded</p>
                            </div><!-- .pay in NGN ends -->
        
                            <div><!-- .product_qty starts -->
                                <b>Qty</b> &nbsp; 
                                <!--<div style="display:none;position:relative;background-color:#000" id='atc_div'><div style="width:60px;height:60px;border-radius:100%;background-color:#ff9100;position:absolute" class="add_to_cart_ball"></div></div>-->
                                <span class="qty">
                                    <b style="font-size:24px" onclick='ajax_qty("$product_id","decrease")'>-</b>&nbsp;&nbsp;
                                    <span id="qty_$product_id">$orders_qty</span>&nbsp;&nbsp;
                                    <b style="font-size:18px" onclick='ajax_qty("$product_id","increase")' id="increase_qty">+</b>
                                </span>
                            </div><!-- .product_qty ends -->
                        </div><!-- .below_continue_to_cashout_img ends -->
    
                        <div class="add_to_my_picks" style="position:static"><!-- .add_to_my_picks starts -->
                            <div class="long_action_button" style="background-color:#ff9100;box-shadow: 0 0 6px #888 inset">
                                <a href="/cart" style="color:#fff;padding:12px 36%">Continue <i class="fa fa-chevron-circle-right"></i></a>
                            </div>
                        </div><!-- .add_to_my_picks ends -->

                        <div class="shopping_cart" style="top:24%;right:4%;">
                            <div id="num_of_products_in_cart" style="font-size:12px;margin-bottom:-77px">$number_of_products_in_cart</div>
                            <a href="/cart"><img src="/static/images/shopping_cart.png"/></a>
                        </div>
                    </div><!-- ..height:80% ends -->
                </div><!-- .continue to cashout ends -->


            </div><!--.main_body end-->     
HTML;
    }
                                                                
    public static function product_scripts(){
        echo <<<HTML
        <!-- Footer - index_scripts -->
        <script>
            function added_or_not(prod_id) {
                show_div('continue_to_cashout');
                if (document.getElementById("added_or_not_id").innerHTML == "Add to my picks") {
                    ajax_qty(prod_id, "increase_to_1");
                    document.getElementById("added_or_not_id").innerHTML = "Added to my picks <i class='fa fa-check-circle-o' style='color:green'></i>";
                }              
            }

            function ajax_qty(prod_id, incr_or_decr) {
                let obj = new XMLHttpRequest;
                obj.onreadystatechange = function(){
                    if(obj.readyState == 4){
                        if (document.getElementById("qty_"+prod_id)){
                            document.getElementById("qty_"+prod_id).innerHTML = obj.responseText;
                        }
                    }
                    //call ajax_add_to_cart() function to automatically update cart if items get to 0 or 1, etc.
                    ajax_add_to_cart(prod_id);
                }
 
                obj.open("GET","/ajax/ajax_qty.php?id="+prod_id+"&increase_or_decrease="+incr_or_decr);
                obj.send(null);    
            }

            function ajax_add_to_cart(prod_id) {
                let obj2 = new XMLHttpRequest;
                obj2.onreadystatechange = function(){
                    if(obj2.readyState == 4){
                        if (document.getElementById("num_of_products_in_cart")){
                            document.getElementById("num_of_products_in_cart").innerHTML = obj2.responseText;
                            document.getElementById("index_num_of_products_in_cart").innerHTML = obj2.responseText;
                        }
                    }
                }
 
                obj2.open("GET","/ajax/ajax_add_to_num_of_products.php?id="+prod_id);
                obj2.send(null);
            }
        </script>

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
HTML;
    }
                                                                
                                                                
    public static function product_footer(){
        
        Index_Segments::footer($site_name = SITE_NAME_SHORT, $site_url = SITE_URL, $additional_scripts = Product_Segments::product_scripts(),$whatsapp_chat = "off");
        echo <<<HTML
            <!-- this div is only meant to bring up the footer section of product page so that it's not covered by the fixed 'add_to_my_picks' div-->
            <div style="margin-top:45px"></div>
HTML;
    }
}
?>