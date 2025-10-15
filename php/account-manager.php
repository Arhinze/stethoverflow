<?php
ini_set("session.use_only_cookies", 1);
include_once($_SERVER["DOCUMENT_ROOT"]."/php/connection.php");

$data = false;

function generate_unique_id(){
    //generate random appended_id:
    $code_array = [0,1,2,3,4,5,6,7,8,9];
    shuffle($code_array);
    $code_out = "";
    
    $arr = [0,1,2,3,4,5];
    shuffle($arr);
    
    foreach($arr as $a){
        $code_out .= $code_array[$a];
    }
    
    $user_unique_id = time()."_".$code_out;
    return $user_unique_id;
}

$code_out = explode("_", generate_unique_id())[1];

if(isset($_COOKIE["google_user_email"])) {
    $nge = htmlentities($_COOKIE["google_user_email"]);
    $nge_stmt = $pdo->prepare("SELECT * FROM stethoverflow_users WHERE user_email = ? LIMIT ?, ?");
    $nge_stmt->execute([$nge, 0, 1]);
    $nge_data = $nge_stmt->fetch(PDO::FETCH_OBJ);

    if($nge_data) {//that means user already exists, just delete google cookies, possible old unique_id cookie and set new unique_id cookie:
        //delete google cookies:
        setcookie("google_user_name", "", time()-(48*3600), "/");
        setcookie("google_user_email", "", time()-(48*3600), "/");
        setcookie("google_user_picture", "", time()-(48*3600), "/");

        //delete possible old unique_id cookie:
        setcookie("unique_id", "",  time()-(48*3600), "/");

        //set new unique_id cookie:
        setcookie("unique_id", $nge_data->unique_id,  time()+(48*3600), "/");
    } else {//that means user doesn't exist yet, create(insert) new user and delete google cookies
        //generate_unique_id()
        $user_unique_id = generate_unique_id();

        //create(insert) new user
        $create_user_stmt = $pdo->prepare("INSERT INTO customers(date_joined, customer_realname, `password`, customer_email, unique_id) VALUES(?,?,?,?,?)");
        $create_user_stmt->execute([date("Y-m-d H:i:s", time()), htmlentities($_COOKIE["google_user_name"]), "Goo--gle1",htmlentities($_COOKIE["google_user_email"]),$user_unique_id]);

        //delete google cookies:
        setcookie("google_user_name", "", time()-(48*3600), "/");
        setcookie("google_user_email", "", time()-(48*3600), "/");
        setcookie("google_user_picture", "", time()-(48*3600), "/");

        //delete possible old unique_id cookie:
        setcookie("unique_id", $user_unique_id,  time()-(48*3600), "/");

        //set new unique_id cookie:
        setcookie("unique_id", $user_unique_id,  time()+(48*3600), "/");

        //get user details from database:
        //$stmt = $pdo->prepare("SELECT * FROM customers WHERE unique_id = ? LIMIT ?, ?");
        //$stmt->execute([$user_unique_id, 0, 1]);
      
        //$data = $stmt->fetch(PDO::FETCH_OBJ);
    }
}

if((isset($_COOKIE["unique_id"]))){
    $user_unique_id = htmlentities($_COOKIE["unique_id"]);

    $stmt = $pdo->prepare("SELECT * FROM stethoverflow_users WHERE user_id = ? LIMIT ?, ?");
    $stmt->execute([$user_unique_id, 0, 1]);
  
    $data = $stmt->fetch(PDO::FETCH_OBJ);
} else {
    $user_unique_id = generate_unique_id();
    setcookie("unique_id", $user_unique_id,  time()+(48*3600), "/");
}

// then call 'if data(){ ... }' for all necessary dashboard related page.