<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/views/Index_Segments.php");
Index_Segments::header();
?>

<div style="display:flex">
    <div style="border-bottom:2px solid #2b8eeb"><a href="/questions" style="color:#2b8eeb">For you</a></div>
    <div><a href="/drafts" style="color:#888">Drafts</a></div>
    <!-- <div>AI Questions</div> -->
</div>

<?php
Index_Segments::footer();
?>