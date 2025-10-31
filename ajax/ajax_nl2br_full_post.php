<?php

include_once($_SERVER["DOCUMENT_ROOT"]."/php/connection.php");

$post_id = "";
if(isset($_GET["post_id"])) {
    $post_id = htmlentities($_GET["post_id"]);
}

$posts_stmt = $pdo->prepare("SELECT * FROM posts WHERE post_id = ? ORDER by post_id DESC LIMIT ?, ?");
$posts_stmt->execute([$post_id, 0, 1]);
$posts_data = $posts_stmt->fetch(PDO::FETCH_OBJ);

echo nl2br($posts_data->body);