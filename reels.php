<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/views/Index_Segments.php");
Index_Segments::header();

$reels_query = $pdo->prepare("SELECT * FROM videos ORDER BY video_id DESC LIMIT ?, ?");
$reels_query->execute([0,100]);
$reels_data = $reels_query->fetchAll(PDO::FETCH_OBJ);

?>

<div class="main_body">
    
</div>

<?php
Index_Segments::footer();
?>