<?php
ini_set("display_errors", '1'); //for testing purposes..

include_once($_SERVER["DOCUMENT_ROOT"]."/php/connection.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/php/account-manager.php");

$images_array = ["image1"];
define("IMAGES_ARRAY", $images_array);

if($data){//if user is logged in:
    if(isset($_POST["question"])) {
        $search_question = $pdo->prepare("SELECT * FROM questions WHERE title = ? ORDER BY question_id DESC LIMIT ?, ?");
        $search_question->execute([htmlentities($_POST["question"]),0,1]);
        $sq_data = $search_question->fetch(PDO::FETCH_OBJ);
        if(!$sq_data) {//that means this is a new post
            $insert_stmt = $pdo->prepare("INSERT INTO questions(title,user_id,time_asked) VALUES(?,?,?)");
            $insert_stmt->execute([htmlentities($_POST["question"]),$data->user_id,date("Y-m-d H:i:s", time())]);

            echo "<div class='invalid' style='background-color: #344c80ff'>Question uploaded successfully</div>";
        } else {
            echo "<div class='invalid'>Sorry, the question has already been asked.</div>";
        }
    }

    if(isset($_POST["write_up"])) {
        $search_post = $pdo->prepare("SELECT * FROM posts WHERE body = ? ORDER BY post_id DESC LIMIT ?, ?");
        $search_post->execute([htmlentities($_POST["write_up"]),0,1]);
        $sp_data = $search_post->fetch(PDO::FETCH_OBJ);

        if(!$sp_data) {//that means this is a new post
            $insert_stmt = $pdo->prepare("INSERT INTO posts(title,body,user_id,time_posted) VALUES(?,?,?,?)");
            $insert_stmt->execute([htmlentities($_POST["post_title"]),htmlentities($_POST["write_up"]),$data->user_id,date("Y-m-d H:i:s", time())]);

            echo "<div class='invalid' style='background-color: #2b8eeb'>Post uploaded successfully</div>";

            //get post's id:
            $last_post = $pdo->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY post_id DESC LIMIT ?, ?");
            $last_post->execute([$data->user_id,0,1]);
            $lp_data = $last_post->fetch(PDO::FETCH_OBJ);
            $last_post_id = $lp_data->post_id;

            //upload images:
            $img_i = 0;
            foreach($images_array as $images_ad) { //foreach loop - [images_array] starts
                $img_i++;
                if(!empty($_FILES["add_".$images_ad]["name"])){ //if (!empty($_FILES["add_".$images_ad])) starts
                    /* Image Upload Script starts */
                    $target_dir = "static/images/";
                    $target_basename = "stethoverflow_".$data->user_id."_".time()."_".$img_i.".png";
                    $target_file = $target_dir.$target_basename;
                    $uploadOk = 1;
                    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
                    //Check if image file is a actual image or fake image
                    $check_img = getimagesize($_FILES["add_".$images_ad]["tmp_name"]);
                    if ($check_img !== false) {
                        //echo "image security test passed - ".$check_img["mime"].".<br/>";
                        $uploadOk = 1;
                    } else {
                        echo "<div class='invalid'>image security test failed - file is not an image</div>";
                        $uploadOk = 0;
                    }
                    if(file_exists($target_file)) {
                        echo "<div class='invalid'>Sorry, file already exists</div>";
                        $uploadOk = 0;
                    }
        
                    //Allow certain file formats:
                    if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif" ) {
                        echo "<div class='invalid'>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</div>";
                        $uploadOk = 0;
                    }
        
                    //Checking if any $uploadOk = 0 by an error:
                    if ($uploadOk == 0) {
                        echo "<div class='invalid'>Sorry, your file was not uploaded.</div>";
                    //if everything is ok, upload file
                    } else {
                        if (move_uploaded_file($_FILES["add_".$images_ad]["tmp_name"], $target_file)) {
                            //echo "The file ".$target_basename." has been uploaded.<br />";                            
                            //insert(update) image(s)
                            $up_stmt = $pdo->prepare("UPDATE posts SET $images_ad = ? WHERE post_id = ?");
                            $up_stmt->execute([$target_basename, $last_post_id]);
                        } else {
                          echo "<div class='invalid'>Sorry, there was an error uploading your file.</div>";
                        }
                    }
                    /* Image Upload Script ends */
                }//if(!empty($_FILES["add_".$images_ad])) ends
            }//foreach loop - looping around array to upload multiple product images at once ends
        } else { //that means post already exists
            echo "<div class='invalid'>Sorry, this post already exists.</div>";
        }

        //check for file upload errors:
        if ($_FILES['add_image1']['error'] !== UPLOAD_ERR_OK) {
            echo "<div class='invalid'>Upload failed with error code " . $_FILES['add_image1']['error']."</div>";
        }
    }
} else {
    if(isset($_POST["question"]) || isset($_POST["write_up"])) { //if user is not logged in but attempting to post
        echo "<div class='invalid'>Login to continue</div>";
    }
}


class Index_Segments{
    private static PDO $pdo;

    public static function inject(PDO $obj) {
        self::$pdo = $obj;
    }

    public static function main_header($site_name = SITE_NAME_SHORT, $profile_or_sign_in = PROFILE_OR_SIGN_IN, $profile_picture = PROFILE_PICTURE) {
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
                    <a href="/" style="color:#fff">$site_name<!--site_name--></a>
                </h1>
                <div style="margin-right:12px" onclick="show_div('ask_or_post_div')">
                    <i class="fa fa-plus-circle"></i> Add
                </div>
            </div> <a name="#top"></a> 
            <!-- end of .headers --> 
            <!-- start of 2nd .headers --> 
            <div class="headers" style="top:50px;background-color:#fff;color:#888;font-size:21px;padding:6px 18px">
                <div class="" style="margin-top:6px"><a href="/" style="color:#acc5f8"><i class="fa fa-home"></i></a></div>
                <div class="" style="margin-top:6px"><a href="/questions"><i class="fa fa-pencil-square-o"></i></a></div>
                <div class="" style="margin-top:6px"><a href="/spaces"><i class="fa fa-users"></i></a></div>
                <div class="" style="margin-top:6px"><a href="/notifications"><i class="fa fa-bell-o"></i></a></div>
                <div class="profile_image_div">
                    <span onclick="show_div('join_us')"><img src="$profile_picture" class="profile_image"/></span>
                </div>
            </div><!-- end of 2nd .headers --> 
            
            <div id="join_us" style="display:none">$profile_or_sign_in</div>
            
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
            <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito|Hammersmith+One|Trirong|Arimo|Prompt|Dancing+Script"/>
            
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
                
        public static function body($site_name = SITE_NAME_SHORT, $site_url = SITE_URL, $profile_picture = PROFILE_PICTURE, $data = DATA){
            $site_name_uc = strtoupper($site_name);

            echo <<<HTML
                <!-- .main_body starts -->
                <div class="main_body">
                    <!-- .main_page_topmost_div starts -->
                    <div class="main_page_topmost_div" style="padding:12px">
                        <div style="display:flex;background-color:#fff" onclick="show_div('ask_or_post_div')">
                            <div style="width:30px;height:30px;border:2px solid #d6e3fd;border-radius:100%">
                                <a href="$site_url/static/images/profile_new.png"><img src="$profile_picture" style="width:27px;height:27px;border-radius:100%;margin:1.35px 0 0 1.35px"/></a>
                            </div>
                            <div class="input" style="background-color:#fff;color:#888;margin-left:5px">What do you want to ask or post?</div>
                        </div>
    
                        <div style="display:flex;justify-content:space-around;margin-top:9px;">
                            <div onclick="show_div('ask_or_post_div')"><i class="fa fa-question-circle-o"></i> Ask</div>
                            <div><a href="/questions" style="color:#000"><i class="fa fa-edit"></i> Answer</a></div>
                            <div onclick="show_div('ask_or_post_div')"><i class="fa fa-pencil"></i> Post</div>
                            <div></div>
                        </div>
                    </div>
                    <!-- .main_page_topmost_div ends -->

                    <!-- demarcation --><div class="demarcation" style="width:100%;height:7px;background-color:#d6e3fd"></div><!-- demarcation --> 
HTML;
            $posts_stmt = self::$pdo->prepare("SELECT * FROM posts ORDER by post_id DESC LIMIT ?, ?");
            $posts_stmt->execute([0,10]);
            $posts_data = $posts_stmt->fetchAll(PDO::FETCH_OBJ);

            foreach($posts_data as $post_d) {
                $post_nl2br = nl2br($post_d->body);
                $user_data_stmt = self::$pdo->prepare("SELECT * FROM stethoverflow_users WHERE user_id = ?");
                $user_data_stmt->execute([$post_d->user_id]);

                $user_data_data = $user_data_stmt->fetch(PDO::FETCH_OBJ);
                $date_posted =  date("j M y", strtotime($post_d->time_posted));

                $post_short_form = substr($post_d->body, 0, 36);

                $number_of_likes = count(explode(";", $post_d->likes)) - 1;

                echo <<<HTML
                    <!-- .posts_and_questions_div starts -->
                    <div class="posts_and_questions" style="margin:12px 6px">
                        <div style="display:flex">
                            <div style="width:39px;height:39px;border:2px solid #d6e3fd;border-radius:100%">
                                <a href="$user_data_data->profile_picture"><img src="$user_data_data->profile_picture" style="width:36px;height:36px;border-radius:100%;margin:1.35px 0 0 1.35px"/></a>
                            </div>
                            <div style="margin-left:6px;margin-top:2px">
                                <div style="font-size:15px"><b>$user_data_data->real_name</b> &nbsp; <i class="fa fa-circle" style="font-size:6px"></i> &nbsp; <!-- <b style="color:#2b8eeb">Follow</b>--> </div>
                                <div style="color:#888;font-size:12px"> $user_data_data->bio &nbsp; <i class="fa fa-circle" style="font-size:6px"></i> &nbsp; $date_posted</div>
                            </div>
                        </div>

                        <div class="questions" style="margin-bottom:3px"><h4>$post_d->title</h4></div>

                        <!-- .answer starts -->
                        <div class="answers" style="padding:6px">
                            $post_nl2br
                            <div><img src="/static/images/$post_d->image1" style="width:100%;height:auto"/></div>
                        </div><!-- .answer ends -->


                        <!-- .like,comment and share icons start -->
                        <div class="like_comment_and_share_icons">
                            <div class="" style="display:flex">
                                <div><span id="post$post_d->post_id" style="color:grey" onclick="like_post('post$post_d->post_id')"><i class="fa fa-heart-o"></i></span> <span id="no_of_likes_of_post$post_d->post_id">$number_of_likes</span></div>
                                <div style="margin-left:10px"><i class="fa fa-comment-o" onclick="show_div('add_comment$post_d->post_id')"></i> <span id="no_of_comments$post_d->post_id">9</span></div>
                                <div style="margin-left:10px"><i class="fa fa-retweet" onclick="show_div('quote_comment_div$post_d->post_id')"></i> <span id="no_of_quotes$post_d->post_id"> </span></div>
                                <div style="margin-left:10px"><i class="fa fa-share-alt"></i> <span id="no_of_shares$post_d->post_id"> </span></div>
                            </div>
                            <div>
                                ...
                            </div>
                        </div><!-- .like,comment and share icons end -->
                    </div><!-- .posts_and_questions_div ends -->

                    <!-- .add_comment starts -->
                    <div style="display:none" class="add_comment" id="add_comment$post_d->post_id" onclick="alternate_comment_div('add_comment$post_d->post_id','reply_comment$post_d->post_id')">
                        <div style="display:flex">
                            <div class="profile_image_div" style="border:2px solid #fff">
                                <a href="$profile_picture"><img src="$profile_picture" class="profile_image"/></a>
                            </div>
                            <div class="input" style="background-color:#fff;color:#888;margin-left:-13px">Add a comment...</div>
                        </div>
                    </div>
                    <!-- .add_comment ends -->

                    <!-- .reply_comment starts -->
                    <div class="reply_comment" id="reply_comment$post_d->post_id" style="display:none">
                        <div style="display:flex;justify-content:space-between;margin-top:9px">
                            <div style="display:flex">
                                <div class="profile_image_div">
                                    <a href="$profile_picture"><img src="$profile_picture" class="profile_image"/></a>
                                </div>
                                <div style="margin-left:-17px;margin-top:7px;color:#888">Replying to <a href="/">@$user_data_data->username</a></div>
                            </div>
                            <div style="margin-top:10px;margin-right:12px;color:#888"><i class="fa fa-times" onclick="alternate_comment_div('add_comment$post_d->post_id','reply_comment$post_d->post_id')"></i></div>
                        </div>
                        <div>
                            <textarea class="textarea" style="height:150px"></textarea>
                        </div>
                        <div><button class="button">Reply</button></div>
                    </div>
                    <!-- .reply_comment ends -->

                    <!-- .quote_comment starts -->
                    <!-- .write_answer starts -->
                    <div class="write_answer" id="quote_comment_div$post_d->post_id" style="display:none">
                        <!-- .write_answer_top starts -->
                        <div class="write_answer_top">
                            <div style="font-size:18px;color:#888" onclick="show_div('quote_comment_div$post_d->post_id')"><b> X </b></div>
                            <div class="button" style="font-size:12px">Post</div>
                        </div><!-- .write_answer_top ends -->
                        <div style="display:flex">
                            <div class="profile_image_div" style="margin-top:5px">
                                <a href="$profile_picture"><img src="$profile_picture" class="profile_image"/></a>
                            </div>
                            <div style="margin-left:-13px">
                                <div style="font-size:12px"><b>$data->real_name</b></b></div>
                                <div class="input" style="width:100%;font-size:12px">$data->bio &nbsp; <i class="fa fa-angle-down"></i></div>
                            </div>
                        </div>
                     
                        <!--<div style="color:#888">Add Image + </div>--> <!-- coming soon -->

                        <div class="">
                            <textarea class="textarea" style="border-radius:0;border-bottom:0;height:50px" placeholder="Make a post about this"></textarea>
                        </div>

                        <!-- .quoted_post starts -->
                        <div style="border-left:2px solid #888;padding-left:9px">
                            <div style="display:flex">
                                <div class="profile_image_div">
                                    <a href="$user_data_data->profile_picture"><img src="$user_data_data->profile_picture" class="profile_image"/></a>
                                </div>
                                <div style="color:#888;margin-left:-17px;margin-top:7px">$user_data_data->real_name - <a href="/">@$user_data_data->username</a></div>
                            </div>

                            <div class="questions" style="margin-bottom:3px"><h4>$post_d->title</h4></div>
    
                            <div class="answers" style="padding:6px">
                                $post_short_form ...
                            </div>

                            <div><img src="/static/images/$post_d->image1" style="width:100%;height:auto"/></div>
                        </div><!-- .quoted_post ends -->
                    </div><!-- .write_answer ends -->
                    <!-- .quote_comment ends -->

                    <!-- demarcation --><div class="demarcation" style="width:100%;height:7px;background-color:#d6e3fd"></div><!-- demarcation --> 
HTML;
                }
  






            echo <<<HTML
                    <!-- .posts_and_questions_div starts -->
                    <div class="posts_and_questions" style="margin:12px 6px">
                        <div class="questions" style="margin-bottom:3px;display:flex">
                            <h4 style="width:90%">What is the most bizarre and rare syndrome you've personally encountered or read about?</h4>
                            <div style="width:6%;margin-top:24px;margin-left:6px"><i class="fa fa-times"></i></div>
                        </div>

                        <!-- .below_only_questions start -->
                        <div class="below_only_questions" style="display:flex">
                            <div style="border:1px solid #888;padding:6px;border-radius:15px"><i class="fa fa-edit"></i>&nbsp; Answer </div>
                            <div style="margin-left:12px;margin-top:6px"><i class="fa fa-feed"></i>&nbsp; Follow <i class="fa fa-circle" style="font-size:3px"></i> 3</div>
                        </div><!-- .below_only_questions end -->
                    </div><!-- .posts_and_questions_div ends -->

                    <!-- demarcation --><div class="demarcation" style="width:100%;height:7px;background-color:#d6e3fd"></div><!-- demarcation -->
HTML;
 
            
 
                echo <<<HTML
                    <!-- .posts_and_questions_div starts -->
                    <div class="posts_and_questions" style="margin:12px 6px">
                        <div class="questions" style="margin-bottom:3px;display:flex">
                            <h4 style="width:90%">Doctors: What's the worst piece of health advice you hear non-professionals give all the time?</h4>
                            <div style="width:6%;margin-top:24px;margin-left:6px"><i class="fa fa-times"></i></div>
                        </div>

                        <!-- .below_only_questions start -->
                        <div class="below_only_questions" style="display:flex">
                            <div style="border:1px solid #888;padding:6px;border-radius:15px"><i class="fa fa-edit"></i>&nbsp; Answer </div>
                            <div style="margin-left:12px;margin-top:6px"><i class="fa fa-feed"></i>&nbsp; Follow <i class="fa fa-circle" style="font-size:3px"></i> 3</div>
                        </div><!-- .below_only_questions end -->
                    </div><!-- .posts_and_questions_div ends -->

                    <!-- demarcation --><div class="demarcation" style="width:100%;height:7px;background-color:#d6e3fd"></div><!-- demarcation -->
                </div><!-- .main_body ends -->
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

            function like_post(vari){
                number_of_likes = "no_of_likes_of_"+vari;
                                               
                obj = new XMLHttpRequest;
                obj.onreadystatechange = function(){
                    if(obj.readyState == 4){
                        if (document.getElementById(number_of_likes)){
                            document.getElementById(number_of_likes).innerHTML = obj.responseText;
                        }
                    }
                }
                                                                        
                obj.open("GET","/ajax/ajax_number_of_likes.php?post_id="+vari);
                obj.send(null);

                if (document.getElementById(vari).style.color == "grey") {
                    document.getElementById(vari).style.color = "red";
                    document.getElementById(vari).innerHTML = "<i class='fa fa-heartbeat'></i>";             
                } else if (document.getElementById(vari).style.color == "red") {
                    document.getElementById(vari).style.color = "grey";
                    document.getElementById(vari).innerHTML = "<i class='fa fa-heart-o'></i>";             
                }
            }

            function alternate_comment_div(add_comment,reply_comment){
                if (document.getElementById(add_comment).style.display == "block") {
                    document.getElementById(add_comment).style.display = "none";
                    document.getElementById(reply_comment).style.display = "block";
                } else if (document.getElementById(add_comment).style.display == "none") {
                    document.getElementById(add_comment).style.display = "block";
                    document.getElementById(reply_comment).style.display = "none";
                }
            }

            function alternate_add_question_and_create_post(vari_text,vari_div){
                if (vari_text == "aq_text"){
                    document.getElementById('aq_text').style = "border-bottom:2px solid #2b8eeb";
                    document.getElementById('add_question').style.display = "block";
                    document.getElementById('create_post').style.display = "none";
                    document.getElementById('cp_text').style = "border:0";
                    document.getElementById('aq_cp_tag').innerHTML = "<label for='submit_question_tag'>Post</label>";
                } else {
                    document.getElementById('cp_text').style = "border-bottom:2px solid #2b8eeb";
                    document.getElementById('create_post').style.display = "block";
                    document.getElementById('add_question').style.display = "none";
                    document.getElementById('aq_text').style = "border:0";
                    document.getElementById('aq_cp_tag').innerHTML = "<label for='submit_post_tag'>Post</label>";
                }
            }        

            var loadFile = function(event, img_id_num) { //function to make pictures visible to user before upload to server
                var img_id = document.getElementById(img_id_num);
                img_id.src = URL.createObjectURL(event.target.files[0]);
                img_id.onload = function(){
                    URL.revokeObjectURL(img_id.src);
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
                                                                
                                                                
        public static function footer($site_name = SITE_NAME_SHORT, $site_url = SITE_URL, $additional_scripts = "", $whatsapp_chat = "on", $images_array = IMAGES_ARRAY){ 
   
            $index_scripts = Index_Segments::index_scripts();  

            echo <<<HTML
                <!-- .ask_or_post_div starts ~ only shown onclick and placed on footer area so that users can access it from any page and not just the home page -->
                <div class="ask_or_post_div" id="ask_or_post_div" style="display:none">
                <!-- .write_answer starts -->
                <div class="write_answer">
                    <!-- .write_answer_top starts -->
                    <div class="write_answer_top">
                        <div style="font-size:18px;color:#888" onclick="show_div('ask_or_post_div')"><b> X </b></div>
                        <div class="button" style="font-size:12px" id="aq_cp_tag"><label for="submit_question_tag">Post</label></div>
                    </div><!-- .write_answer_top ends -->

                    <div style="display:flex;justify-content:space-around;margin:15px 0;font-size:15px">
                        <div onclick="alternate_add_question_and_create_post('aq_text','add_question')" id="aq_text" class="aq_cp_text" style = "border-bottom:2px solid #2b8eeb"><b>Ask Question</b></div>
                        <div onclick="alternate_add_question_and_create_post('cp_text','create_post')" id="cp_text" class="aq_cp_text"><b>Create Post</b></div>
                    </div>
                    <div style="display:flex">
                        <div class="profile_image_div" style="margin-top:5px">
                            <a href="/static/images/profile_new.png"><img src="/static/images/profile_new.png" class="profile_image"/></a>
                        </div>
                        <div style="margin-left:-13px">
                            <div style="font-size:12px"><b>Cassy Maya</b></b></div>
                            <div class="input" style="width:100%;font-size:12px">Orthopaedic Surgeon (2007 - present) &nbsp; <i class="fa fa-angle-down"></i></div>
                        </div>
                    </div>

                    <!-- add_question starts -->
                    <div id="add_question" style="display:block">
                        <div class="question_tips">
                            <div><b>Keep Questions Simple:</b></div>
                            <ul>
                                <li>Double check your grammar.</li>
                                <li>Start question with "why", "what", "how"</li>
                                <li>Ensure the question has not already been asked</li>
                            </ul>
                        </div>
                        <form method="post" action="">
                            <textarea name="question" class="textarea" style="border-bottom:0;border-radius:0;height:75px" placeholder="Ask your question" minlength="10" required></textarea>
                            <input type="submit" id="submit_question_tag" style="display:none"/>
                        </form>
                    </div><!-- ask_question ends -->

                    
                    <!-- create_post starts -->
                    <form method="post" action="" enctype="multipart/form-data">
                    <div id="create_post" style="display:none">
                        <!-- Add Image Starts -->
                        <div style="font-size:18px;margin:15px 0 9px 0"><b>Add Image:</b> (accepted formats - png, jpg, jpeg, gif)<span style="font-size:12px;color:green"></span></div>

                        <div class="x_scroll"><!-- style .overflow-x:scroll starts -->
                            <div class="additional_product_images_div_container" style="width:fit-content;overflow:visible"><!-- .additional_product_images_div_container starts -->
HTML;
                        $i=0;
                        foreach($images_array as $images_ad) {
                            $i++;
                            $tag = "add_".$images_ad."_file_upload_tag";
                            $img_id = "add_".$images_ad;
                echo <<<HTML
                            <div class="additional_product_images_div"><!-- img1 to img4 -->
                                <label for="$tag"><img src="/static/images/add_image_icon.png" id="$img_id" class="additional_product_image"/><span class="additional_product_image_number">$i</span></label>
                            </div>
HTML;
                        }
                echo <<<HTML
                        </div><!-- .additional_product_images_div_container ends -->
                    </div><!-- style .overflow-x:scroll ends -->

                    <!-- The input tags which does the work but remains hidden starts -->
HTML;
                        foreach($images_array as $images_ad) {
                            $tag = "add_".$images_ad."_file_upload_tag";
                            $img_id = "add_".$images_ad;
                echo <<<HTML
                            <input type="file" name="$img_id" id="$tag" accept="image/*" style="display:none" onchange="loadFile(event, '$img_id')"/><!-- file tag 1 to file tag 10 -->
                            <!-- The input tags which does the work but remains hidden ends -->
                    <!-- Add Image Ends -->
HTML;
                        }   


            echo <<<HTML
                            <!--<textarea name="post_title" class="textarea" style="border-bottom:0;border-radius:0;height:60px" placeholder="Enter a title for this post(optional)"></textarea>-->
                            <input type="hidden" name="post_title"/><!-- I will make this visible soon -->
                            <textarea name="write_up" class="textarea" style="border-bottom:0;border-radius:0" placeholder="Create a post about something" minlength="30" required></textarea>
                            <input type="submit" id="submit_post_tag" style="display:none"/>
                    </div><!-- create_post ends -->
                    </form>
                </div><!-- .write_answer ends -->
                </div><!-- .ask_or_post_div ends ~ only shown onclick -->
HTML;


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
                            <a href="https://wa.me/2347076032581" style="color:#000"><!-- whatsapp link starts -->
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
                    <div style="color:#fff;font-size:33px;padding:9px 12px;border-radius:100%;background-color:green;position:fixed;bottom:54px;right:18px"><i class="fa fa-whatsapp" onclick="show_div('whatsapp_box')"></i></div>
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