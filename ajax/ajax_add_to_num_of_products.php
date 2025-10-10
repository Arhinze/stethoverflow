<?php

include_once($_SERVER["DOCUMENT_ROOT"]."/php/connection.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/php/account-manager.php");

$customer_id = $user_unique_id;
$product_id = "nil";
if(isset($_GET["id"])) {
    $product_id = htmlentities($_GET["id"]);
}

$first_sel_stmt = $pdo->prepare("SELECT * FROM orders_processor WHERE customer_id = ? AND product_id = ?");
$first_sel_stmt->execute([$customer_id, $product_id]);
$first_sel_data = $first_sel_stmt->fetchAll(PDO::FETCH_OBJ);
//if (count($first_sel_data) == 0) {//that means order is not yet recorded, --record it now:
//    $insert_stmt = $pdo->prepare("INSERT INTO orders_processor(customer_id, product_id, qty) VALUES(?,?,?)");
//    $insert_stmt->execute([$customer_id,$product_id,1]);

    ////$select_processing_orders_stmt = $pdo->prepare("SELECT * FROM orders_processor WHERE customer_id = ? AND qty > ?");
    ////$select_processing_orders_stmt->execute([$customer_id, 0]);
    ////$select_processing_orders_data = $select_processing_orders_stmt->fetchAll(PDO::FETCH_OBJ);
//} 

$select_processing_orders_stmt = $pdo->prepare("SELECT * FROM orders_processor WHERE customer_id = ? AND qty > ?");
$select_processing_orders_stmt->execute([$customer_id, 0]);
$select_processing_orders_data = $select_processing_orders_stmt->fetchAll(PDO::FETCH_OBJ);

echo count($select_processing_orders_data);