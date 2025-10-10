<?php

include_once($_SERVER["DOCUMENT_ROOT"]."/php/connection.php");

if(isset($_GET["search_query"])){
    if(!empty($_GET["search_query"])){
        $search_q = htmlentities($_GET["search_query"]);
       
        $search_stmt = $pdo->prepare("SELECT * FROM products WHERE `description` LIKE ? LIMIT ?, ?");
        $search_stmt->execute(["%$search_q%", 0, 10]);
        $search_data = $search_stmt->fetchAll(PDO::FETCH_OBJ);

        echo "<div style='border:1px solid #888;border-radius:9px;position:fixed;top:45px;margin:15px;padding:3px;width:90%;z-index:15'>";
        foreach($search_data as $sd){
?>
            <div style="border-bottom:1px solid #888;border-radius:0 0 9px 9px;padding:12px 6px;background-color:#fff;">
                <a href ="/product/<?=$sd->product_url?>" style="color:#2b8eeb"><?=substr($sd->description, 0, 21)."..."?></a>
            </div>
<?php 
       }
       echo "<div style='background-color:#000;height:100%;width:100%'></div></div>";
    }
}

?>