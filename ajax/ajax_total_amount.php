<?php

include_once($_SERVER["DOCUMENT_ROOT"]."/php/connection.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/php/account-manager.php");

$customer_id = $user_unique_id;
$total_amount = 0;

$cart_stmt = $pdo->prepare("SELECT * FROM orders_processor WHERE customer_id =  ? AND qty > ? LIMIT ?, ?");
$cart_stmt->execute([$customer_id, 0, 0, 25]);
$cart_data = $cart_stmt->fetchAll(PDO::FETCH_OBJ);
$cart_count = count($cart_data);

if($cart_count > 0) {
    foreach($cart_data as $cc){
        $p_stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
        $p_stmt->execute([$cc->product_id]);
        $p_data = $p_stmt->fetch(PDO::FETCH_OBJ);

        $total_amount += $p_data->price*$cc->qty;
    }
}

echo number_format($total_amount);