<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/views/admin_Segments.php");

if(isset($_COOKIE["admin_name"]) && isset($_COOKIE["admin_password"])){
    $stmt = $pdo->prepare("SELECT * FROM `admin` WHERE admin_name = ? AND admin_password = ?");
    $stmt->execute([$_COOKIE["admin_name"], $_COOKIE["admin_password"]]);

    $data = $stmt->fetch(PDO::FETCH_OBJ);
    if($data){
        //that means admin is logged in
        admin_Segments::header();
?>
        <div class="dashboard_div">

        <h1 style="margin:24px 6px">Registered Users on <?=$site_name?></h2>

        <!-- "refresh" class -->
        <a href = '' style='color:#fff;font-size:19px;margin:0 3px 0 6px;padding:6px 9px;background-color:<?=$site_color_light?>;border-radius:5px' class="refresh">
        
            <i class='fa fa-refresh'></i>
            <span style="font-size:18px; font-weight:bold">&nbsp; Refresh</span>

        </a> 
        <!-- End of "refresh" class -->

        <br /><br /> 
<?php
        //check if admin is searching for someone:
?>
        <input type="text" onkeyup="ajax_search()" id="search_input" class="input" placeholder="Enter username: try: abc" style="border:1px solid <?=$site_color_light?>;width:75%"/> 
        
        <i class="fa fa-search" onclick ="search_icon()" style="padding:12px;border-radius:4px;font-size:16px;color:#fff;background-color:<?=$site_color_light?>"></i>

        <div id="search" style="position:absolute;width:75%"></div>
        
        <div class='main'>    <!-- 'main' div starts -->
<?php
        //To Delete User:
        if(isset($_POST["remove_user"])){
            //check if user still exists
            $ds_stmt = $pdo->prepare("SELECT * FROM miners WHERE user_id = ?");
            $ds_stmt->execute([$_POST["remove_user"]]);
    
            $ds_data = $ds_stmt->fetch(PDO::FETCH_OBJ);
            if($ds_data){ 
                //then delete
                $dd_stmt = $pdo->prepare("DELETE FROM miners WHERE user_id = ?");
                $dd_stmt->execute([$_POST["remove_user"]]);

                echo "<h4 style='color:red'>User: ", $ds_data->username, " has been deleted successfully</h4>";
            }
        }

        //Mail Investor:

        if(isset($_POST["message_to_investor"]) && !empty($_POST["message_to_investor"])){

            $messageFromAdmin = nl2br(htmlentities($_POST["message_to_investor"]));

            $message = <<<HTML
                <html>
                <head>
                    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Trirong|Arimo"/>
                            <link rel="stylesheet" href="https://$site_url_short/static/font-awesome-4.7.0/css/font-awesome.min.css"/>
            
                </head>
                <body style ="font-family:Trirong;">
                    <center>
                        <img src="https://$site_url_short/static/images/aguanit.png" style="margin-left:36%;margin-right:36%;width:25%;"/>
                    </center>
                    <h2 style="color:#00008b;font-family:Arimo;text-align:center">$site_name Investment</h2>
        
            HTML;

            $sender = "admin@$site_url_short";

            $headers = "From: $sender \r\n";
            $headers .="Reply-To: $sender \r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type:text/html; charset=UTF-8\r\n";

            $mail = mail($_POST["investor_mail"],"Message From $site_name Investment",$message.$messageFromAdmin."</body></html>", $headers);

            if($mail){
            ?>

                <div id="message_success" style="background-color:#ff9100;color:#fff;
                border-radius:3px;padding:4px;margin:8px 8px;display:block;position:fixed;top:40%;width:80%;
                box-shadow:0px 0px 9px 0px #fff">
                    
                    <div class="clear">
                        <span class="float:right"><b>A Mail has been sent to the investor </b></span>
                        &nbsp;&nbsp;&nbsp;

                        <i class="fa fa-times" style="float:right" onclick="show_div('message_success')"></i>
                    </div>
                </div>

            <?php
            } else {
                echo "Sorry, an error occurred, Mail not sent";
            }

        }


        //Select and view all users for easy decision making:

        //A Simple Pagination Algorithm:
        $p = 1;
        $num_of_rows = 10;

        if(isset($_GET["page"])){
            $p = htmlentities($_GET["page"]);
            if(!is_numeric($p) || $p < 1){
                $p = 1;
            }
        }
        
        $page_to_call = ($p - 1)*$num_of_rows;

        //count entire users:
        $u_search_stmt = $pdo->prepare("SELECT * FROM miners ORDER BY user_id DESC LIMIT ?, ?");
        $u_search_stmt->execute([0, 1000]);

        $num_of_users = count($u_search_stmt->fetchAll(PDO::FETCH_OBJ));

        $max = ceil($num_of_users/$num_of_rows);
        // -- end of pagination algorithm --

        
        //first check if admin searched for someone in particular
        if(isset($_GET["user"])){
            $search_q = htmlentities($_GET["user"]);

            $u_search_stmt = $pdo->prepare("SELECT * FROM miners WHERE username LIKE ? ORDER BY user_id DESC LIMIT ?, ?");
            $u_search_stmt->execute(["%$search_q%",$page_to_call, $num_of_rows]);

            $u_data = $u_search_stmt->fetchAll(PDO::FETCH_OBJ);
        }  else {
            //if no particular person is searched for, call out everyone:
            $u_stmt = $pdo->prepare("SELECT * FROM miners ORDER BY user_id DESC LIMIT ?, ?");
            $u_stmt->execute([$page_to_call, $num_of_rows]);
    
            $u_data = $u_stmt->fetchAll(PDO::FETCH_OBJ);
        }

        if(count($u_data)>0){     
            $i = 0;
            foreach($u_data as $d){
                $i += 1;
?>
        <div class="everything-both-buttons-nd-hidden-divs" style='line-height:30px'> 
                <div class="visible_buttons">
                    <?=$i + (($p - 1)*$num_of_rows)?>. &nbsp;<b style='font-size:21px'> <?=$d->username?> </b> <br />

                    <button onclick = "create_content('user_details',<?=$i?>)" style="background-color:blue" 
                    class="show_hidden_divs_button">
                        <i class="fa fa-user"></i> User Details
                    </buton>

                    <button onclick = "create_content('referred-by',<?=$i?>)" style="background-color:#888"
                    class="show_hidden_divs_button">
                        <i class="fa fa-eye"></i> Referred By? 
                    </buton>

                    <button onclick = "create_content('message',<?=$i?>)" style="background-color:#ff9100"
                    class="show_hidden_divs_button">
                        <i class="fa fa-envelope"></i> Message
                    </buton>
                    
                    <button onclick = "create_content('remove',<?=$i?>)" style="background-color:red"
                    class="show_hidden_divs_button">
                        <i class="fa fa-warning"></i> Remove 
                    </buton>
                </div>


            <div class="clear">
            <div style="margin-top:18px;" class="all-hidden-divs">

            <!-- To make all hidden div content to appear in the same spot on display: -->
            <div id="content_space<?=$i?>" class="calculator" style=""> 
            <!-- style="display:block creates undesirable problems like making the div not to appear even onclick" -->
            </div>

            <!--hidden section 1: View User Details: -->
            <div id="user_details<?=$i?>" style="display:none;border:2px solid blue;border-radius:6px;margin-top:12px;padding:4px;">
                <?php
                    $ud_stmt = $pdo->prepare("SELECT * FROM miners WHERE user_id = ? ORDER BY user_id DESC LIMIT ?, ?");
                    $ud_stmt->execute([$d->user_id, 0, 100]);
                    $ud_data = $ud_stmt->fetch(PDO::FETCH_OBJ);

                    if($ud_data){  
                ?>
                        <div class="admin_user_details"><b>Username: </b><br /><?=$ud_data->username?></div>
                        <div class="admin_user_details"><b>Password: </b><br /><?=$ud_data->password?></div>
                        <div class="admin_user_details"><b>Email: </b><br /><?=$ud_data->user_email?></div>
                        <div class="admin_user_details"><b>Twitter Username: </b><br /><?=$ud_data->twitter_username?></div>
                        <div class="admin_user_details" style="overflow:scroll">
                            <b>$Avax wallet address: </b><br /><?=$ud_data->avax_wallet_address?>
                        </div>
                        <div class="admin_user_details" style="overflow:scroll">
                            <b>$Aguat wallet address: </b><br /><?=$ud_data->aguat_wallet_address?>
                        </div>
                <?php
                    }
                ?>
            </div>
            <!--End of transaction div-->



            <!--hidden section 2: Referred By?-->
            <div id="referred-by<?=$i?>" style="display:none;border:2px solid #888;border-radius:6px;margin-top:12px;padding:4px;">
                
                <?php
                    if(!empty($d->referred_by)){
                        echo "Referred By: <b>", $d->referred_by, "</b>";
                    } else {
                        echo "Not referred";
                    }
                ?>

            </div>
            <!--End of referred by div-->


            <!--hidden section 3: Message-->
            <div id="message<?=$i?>" style="display:none;border:2px solid #888;border-radius:6px;margin-top:12px;
                padding:4px;">

                <form method="post" action="">
                <!-- -->
                <span style="position:absolute;left:48px;">To:</span> 
                
                <input type="text" id="investor_mail<?=$i?>" style=
                "border-left:30px solid #ff9100;border-right:30px solid #ff9100;border-radius:4px;
                height:21px;width:70%;margin-bottom:15px"
                <?php if(!empty($d->user_email)){ ?> value="<?=$d->user_email?>" <?php } ?> 
                name="investor_mail"/>

                <i style="margin-left:-27px" class="fa fa-copy" onclick="copyText('investor_mail<?=$i?>')"></i>

                <textarea style="width:75%;height:100px;
                border-radius:4px" name="message_to_investor">Hello <?=$d->real_name?>, </textarea>

                <div class="clear">
                    <div style="float:left">
                        <input type="submit" class="admin_invest_now_button" value="Send Mail"/>
                        <span class="admin_invest_now_button" onclick="hide_content_space('message',<?=$i?>)"; style="
                        background-color:red">
                        Cancel</span> 
                    </div>
                </div>

                </form>
            </div>
            <!--End of message div-->



            <!-- hidden section 4: Remove User -->
            
            <div id="remove<?=$i?>" style="display:none;border:2px solid red;border-radius:6px;margin-top:12px;padding:3px">

            <form method="post" action="" id="message_form<?=$i?>" class="pop_up">
            <span style="text-align:center">Are you sure you want to remove user: <b style="font-size:18px;color:red;border-bottom:2px solid #fff"><?=$d->username?>?</b> &nbsp;

            <b>This can't be Undone</b></span><br /><br />

            <input type="hidden"  name="remove_user" value="<?=$d->user_id?>"/>

            <input type="submit" value="Remove" style="background-color:red;
                    padding:3px;margin:3px;border-radius:6px;color:#fff;border:none;height:24px;"/> 

            <!--Cancel "Remove User" (Don't remove):-->
            <!--onclick = "show_div('remove <= $i >')"-->
            <span onclick="hide_content_space('remove',<?=$i?>)" style="background-color:#ff9100;
                    padding:3px;border-radius:6px;color:#fff;
                    margin-left:6px;text-align:center;height:24px;border:none">
                    Cancel 
            </span>    
            </form>

            </div> <!--End of remove<=i> div-->

            </div><!--End of hidden divs-->
            </div><!--End of hidden divs clear class-->   
        </div> <hr /><br /><!--End of Both Buttons and hidden divs-->
        
        <!--End of all - both buttons hidden divs-->

<?php
            }
        }
?>

        <!--Paginator-->
        <div class="clear" style="font-weight:bold;font-size:18px">
            <?php if($p > 1) { ?> 
                <div style="float:left">
                   <b>
                       <a href="?page=<?=$p-1?>" style="color:#fff"><i class="fa fa-angle-left">&nbsp; Previous</i></a>
                    </b>
                </div> 
            <?php } ?>

            <?php if($p < $max) { ?> 
                <div style="float:right">
                    <b>
                        <a href="?page=<?=$p+1?>" style="color:#fff">Next &nbsp;<i class="fa fa-angle-right"></i></a>
                    </b>
                </div> 
            <?php } ?>
        </div> <!-- End of Paginator -->

        </div> <!-- End of class 'main_div' -->

<?php
    }else{
        //redirect
        header("location:/admin");
    }

    echo "</div>"; //end of 'main' div
    admin_Segments::footer();
} else {
    //redirect
    header("location:/admin");
}

?>