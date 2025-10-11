<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/views/Index_Segments.php");
Index_Segments::header();
?>

<div class="main_body">
    <div style="display:flex;margin-left:15px">
        <div style="border-bottom:2px solid #2b8eeb;padding:15px 12px"><a href="/questions" style="color:#2b8eeb">For you</a></div>
        <div style="margin-left:12px;margin-top:18px"><a href="/drafts" style="color:#888">Drafts</a></div>
        <!-- <div>AI Questions</div> -->
    </div>
</div>

<?php
Index_Segments::footer();
?>