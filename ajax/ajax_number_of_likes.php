<?php

include_once($_SERVER["DOCUMENT_ROOT"]."/php/account-manager.php");

if(isset($_GET["post_id"])){
    $post_id = str_replace("post", "", htmlentities($_GET["post_id"]));

    $like_stmt = $pdo->prepare("SELECT * FROM posts WHERE post_id = ? ORDER BY post_id DESC LIMIT ?, ?");
    $like_stmt->execute([$post_id, 0, 1]);
    $like_data = $like_stmt->fetch(PDO::FETCH_OBJ);

    $number_of_likes = explode(";", $like_data->likes);
    if(in_array($data->user_id, $number_of_likes)){
        unset($number_of_likes["$data->user_id"]);
        echo count($number_of_likes)-1;//adding -1 to balance out the extra array counted from explode() function
    } else {
        $update_like_stmt = $pdo->prepare("UPDATE posts SET likes = ? WHERE post_id = ?");
        $update_like_stmt->execute([$like_data->likes.$data->user_id.";", $post_id]);

        echo count($number_of_likes)-1;//adding -1 to balance out the extra array counted from explode() function
    }

    
}
?>