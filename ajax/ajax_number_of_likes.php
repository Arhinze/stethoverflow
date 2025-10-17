<?php

include_once($_SERVER["DOCUMENT_ROOT"]."/php/account-manager.php");

if($data) {
    if(isset($_GET["post_id"])){
        $post_id = str_replace("post", "", htmlentities($_GET["post_id"]));
    
        $like_stmt = $pdo->prepare("SELECT * FROM posts WHERE post_id = ? ORDER BY post_id DESC LIMIT ?, ?");
        $like_stmt->execute([$post_id, 0, 1]);
        $like_data = $like_stmt->fetch(PDO::FETCH_OBJ);
    
        $number_of_likes = explode(";", $like_data->likes);
        
        if(in_array($data->user_id, $number_of_likes)){
            $new_no_of_likes = array_diff($number_of_likes,["$data->user_id"]);
            echo count($new_no_of_likes)-1;//adding -1 to balance out the extra array counted from explode() function
    
            $update_like_stmt = $pdo->prepare("UPDATE posts SET likes = ? WHERE post_id = ?");
            $update_like_stmt->execute([implode(";",$new_no_of_likes), $post_id]);
        } else {
            $update_like_stmt = $pdo->prepare("UPDATE posts SET likes = ? WHERE post_id = ?");
            $update_like_stmt->execute([$like_data->likes.$data->user_id.";", $post_id]);
    
            echo count($number_of_likes);//not adding -1 so it will just balance out the extra array counted from explode() function
        }
    
        
    }
} else {
    echo <<<HTML
        <div class='invalid' style='background-color: #d6e2fb;color:#fff;top:30%;left:15%;height:fit-content;width:70%;padding:18px'>
            <div style="display:flex;justify-content:space-between">
                <div><b>kindly login to continue</b></div>
                <div><i class="fa fa-times"></i></div>
            </div>
            <div>    
                <div style="text-align:center;font-size:27px;margin-top:30px;margin-bottom:-16px;font-family:Dancing Script">Welcome to</div>
                <h1 style="font-size:30px;text-align:center">Steth<span style="color: #2b8eeb">Overflow</span></h1>
                <div style="color:#2b8eeb;font-size:15px;text-align:center;margin-top:-19px"><i class="fa fa-key"></i>&nbsp; All data is encrypted</div>
            </div>
        
            <div class="input" style="border-radius:36px;text-align:center;background-color:blue;border:1px solid blue;font-weight:bold;color:#fff;margin-top:30px">
                <a href="/auth/google-login.php" style="color:#fff;padding:12px 21%"><i class="fa fa-google" id="signinButton"></i>&nbsp; Continue with Google</a>
            </div>

            <div class="input" style="border-radius:36px;text-align:center;background-color:blue;border:1px solid blue;font-weight:bold;color:#fff;margin-top:30px">
                <a href="/login.php" style="color:#fff;padding:12px 21%"><i class="fa fa-google" id="signinButton"></i>&nbsp; Login/Sign up</a>
            </div>
        </div>
HTML;
}
?>