<?php

include_once($_SERVER["DOCUMENT_ROOT"]."/views/Index_Segments.php");

$error_button = "";
$email = "";
$fullName = "";

if(isset($_POST["email"])){//check if new user already exists:
    $email = trim(htmlentities($_POST["email"]));
    $sel_stmt = $pdo->prepare("SELECT * FROM stethoverflow_users WHERE user_email = ? LIMIT ?, ?");
    $sel_stmt->execute([$email, 0, 1]);
    $sel_data = $sel_stmt->fetch(PDO::FETCH_OBJ);

    if ($sel_data) {//that means user exists . .
        if($sel_data->password == htmlentities($_POST["password"])) {//that means user entered correct login details
            //delete possible old unique_id cookie:
            setcookie("unique_id", "",  time()-(48*3600), "/");
            //set new unique_id cookie:
            setcookie("unique_id", $sel_data->unique_id,  time()+(48*3600), "/");
        } else {//user inputed wrong password
            $error_button = "<div class='invalid'>Invalid Username or Password</div>
            <div style='margin:60px 12px;text-align:center'>
                <form method = 'POST' action='/login'>
                    <input type='hidden' name='email' value='$email'/>
                    <button type='submit' class='input' style='color:#fff;background-color:#ff9100;border:1px solid #fff;font-weight:bold'><i class='fa fa-arrow-left'></i> &nbsp; return to previous page</button>
                </form>
            </div>";
        }
    } else {//if user doesn't exist: ~ create(insert) user
        if(isset($_POST["repeat_password"])) {//user is attempting to create new account
            if(htmlentities($_POST["password"]) == htmlentities($_POST["repeat_password"])) {//if passwords match: ~~~ JS enforces this to work
                $fullName = htmlentities($_POST["full_name"]);
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {//if valid email ~ insert user
                    //generate_unique_id()
                    $user_unique_id = generate_unique_id();

                    //create(insert) new user
                    $create_user_stmt = $pdo->prepare("INSERT INTO stethoverflow_users(entry_date, customer_realname, `password`, customer_email, unique_id) VALUES(?,?,?,?,?)");
                    $create_user_stmt->execute([date("Y-m-d H:i:s", time()), htmlentities($_POST["full_name"]), htmlentities($_POST["password"]), $email, $user_unique_id]);

                    //delete possible old unique_id cookie:
                    setcookie("unique_id", "",  time()-(48*3600), "/");
                    //set new unique_id cookie:
                    setcookie("unique_id", $user_unique_id,  time()+(48*3600), "/");
                } else {//invalid email address:
                    $error_button = "<div class='invalid'>Invalid Email Address</div>
                    <div style='margin:60px 12px;text-align:center'>
                        <form method = 'POST' action='/login'>
                            <input type='hidden' name='email' value='$email'/>
                            <input type='hidden' name='full_name' value='$fullName'/>
                            <button type='submit' class='input' style='color:#fff;background-color:#ff9100;border:1px solid #fff;font-weight:bold'><i class='fa fa-arrow-left'></i> &nbsp; return to previous page</button>
                        </form>
                    </div>";
                }
            } else {//if passwords do not match ~ ~ ~ might not be called because JS already handles this
                $error_button = "<div class='invalid'>Passwords do not match</div>
                <div style='margin:60px 12px;text-align:center'>
                    <form method = 'POST' action='/login'>
                        <input type='hidden' name='email' value='$email'/>
                        <input type='hidden' name='full_name' value='$fullName'/>
                        <button type='submit' class='input' style='color:#fff;background-color:#ff9100;border:1px solid #fff;font-weight:bold'><i class='fa fa-arrow-left'></i> &nbsp; return to previous page</button>
                    </form>
                </div>";
            }
        } else {//user is attempting to login with a wrong password: ~ inform user that this is a wrong password
            $error_button = $error_button;
        }
    }
}
Index_Segments::header();
?>

<div class="main_body">
    <?php
        if ($error_button == "") {//if there are no errors:
    ?>
            <div style="margin:21px 9px;border:1px solid #fff;text-align:center"><a class="input" style="color:#fff;border:1px solid #fff;background-color:#acc5f8;font-weight:bold;padding:6px 9px" href="/">Continue to my Account &nbsp; <i class="fa fa-arrow-right"></i></a></div>
    <?php 
        } else {
            echo $error_button;
        }
    ?>
</div>

<?php Index_Segments::footer(); ?>