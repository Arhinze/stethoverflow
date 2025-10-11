<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/views/Index_Segments.php");
Index_Segments::header();
?>

<div class="main_body">
    <div style="display:flex;margin-left:15px">
        <div style="margin-top:18px;margin-left:6px"><a href="/questions" style="color:#888">For you</a></div>
        <div style="border-bottom:2px solid #2b8eeb;padding:15px 6px;margin-left:6px"><a href="/drafts" style="color:#2b8eeb">Drafts</a></div>
        <!-- <div>AI Questions</div> -->
    </div>
    <!-- demarcation --><div class="demarcation" style="width:100%;height:7px;background-color:#d6e3fd"></div><!-- demarcation -->
</div>

<?php
Index_Segments::footer();
?>