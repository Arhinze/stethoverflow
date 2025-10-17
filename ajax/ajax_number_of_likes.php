<?php

include_once($_SERVER["DOCUMENT_ROOT"]."/php/connection.php");

if(isset($_GET["post_id"])){
    $post_id = str_replace("post", "", htmlentities($_GET["post_id"]));

    $like_stmt = $pdo->prepare("SELECT * FROM posts WHERE post_id = ? ORDER BY post_id DESC LIMIT ?, ?");
    $like_stmt->execute([$post_id, 0, 1]);
    $like_data = $like_stmt->fetch(PDO::FETCH_OBJ);

    $number_of_likes = explode(";", $like_data->likes);
    echo count($number_of_likes)-1;
}
?>