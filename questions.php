<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/views/Index_Segments.php");
Index_Segments::header();
?>

<div class="main_body"><!-- .main_body starts -->
    <div style="display:flex;margin-left:15px">
        <div style="border-bottom:2px solid #2b8eeb;padding:15px 6px"><a href="/questions" style="color:#2b8eeb">For you</a></div>
        <div style="margin-left:12px;margin-top:18px"><a href="/drafts" style="color:#888">Drafts</a></div>
        <!-- <div>AI Questions</div> -->
    </div>

    <!-- demarcation --><div class="demarcation" style="width:100%;height:7px;background-color:#d6e3fd"></div><!-- demarcation -->

    <!-- Question 1 -->
    <!-- .posts_and_questions_div starts -->
    <div class="posts_and_questions" style="margin:12px 6px">
        <div class="questions" style="margin-bottom:3px;display:flex">
            <h4 style="width:90%">What's one medical myth patients still strongly believe in your country?</h4>
            <div style="width:6%;margin-top:24px;margin-left:6px;color:#888"><i class="fa fa-times"></i></div>
        </div>

        <!-- .below_only_questions start -->
        <div class="below_only_questions" style="display:flex">
            <div style="border:1px solid #888;padding:6px;border-radius:15px" onclick="show_div('write_answer_div1')"><i class="fa fa-edit"></i>&nbsp; Answer </div>
            <div style="margin-left:12px;margin-top:6px"><i class="fa fa-feed"></i>&nbsp; Follow <i class="fa fa-circle" style="font-size:3px"></i> 3</div>
        </div><!-- .below_only_questions end -->
    </div><!-- .posts_and_questions_div ends -->

    <!-- .write_answer starts -->
    <div class="write_answer" id="write_answer_div1" style="display:none">
        <!-- .write_answer_top starts -->
        <div class="write_answer_top">
            <div style="font-size:18px;color:#888" onclick="show_div('write_answer_div1')"><b> X </b></div>
            <div class="button" style="font-size:12px">Post</div>
        </div><!-- .write_answer_top ends -->
        <div style="display:flex">
            <div class="profile_image_div" style="margin-top:5px">
                <a href="/static/images/profile_new.png"><img src="/static/images/profile_new.png" class="profile_image"/></a>
            </div>
            <div style="margin-left:-13px">
                <div style="font-size:12px"><b>Cassy Maya</b></b></div>
                <div class="input" style="width:100%;font-size:12px">Orthopaedic Surgeon (2007 - present) &nbsp; <i class="fa fa-angle-down"></i></div>
            </div>
        </div>
        
        <h4 style="width:90%">What's one medical myth patients still strongly believe in your country?</h4>
        <div style="color:#888">Add Image + </div>
        <div class="">
            <textarea class="textarea" placeholder="Write your answer on this"></textarea>
        </div>
    </div><!-- .write_answer ends -->
    <!-- demarcation --><div class="demarcation" style="width:100%;height:7px;background-color:#d6e3fd"></div><!-- demarcation -->

<?php
    $search_question = $pdo->prepare("SELECT * FROM questions ORDER BY question_id DESC LIMIT ?, ?");
    $search_question->execute([htmlentities($_POST["question"]),0,1000]);
    $sq_data = $search_question->fetchAll(PDO::FETCH_OBJ);

    foreach($sq_data as $sd){
?>
    <!-- Question 1,2,3... -->
    <!-- .posts_and_questions_div starts -->
    <div class="posts_and_questions" id="pq<?=$sd->question_id?>" style="margin:12px 6px">
        <div class="questions" style="margin-bottom:3px;display:flex">
            <h4 style="width:90%"><?=$sd->title?></h4>
            <div style="width:6%;margin-top:24px;margin-left:6px;color:#888" onclick="show_div('pq<?=$sd->question_id?>')"><i class="fa fa-times"></i></div>
        </div>

        <!-- .below_only_questions start -->
        <div class="below_only_questions" style="display:flex">
            <div style="border:1px solid #888;padding:6px;border-radius:15px" onclick="show_div('write_answer_div<?=$sd->question_id?>')"><i class="fa fa-edit"></i>&nbsp; Answer </div>
            <!--<div style="margin-left:12px;margin-top:6px"><i class="fa fa-feed"></i>&nbsp; Follow <i class="fa fa-circle" style="font-size:3px"></i> 3</div>--><!-- coming soon -->
        </div><!-- .below_only_questions end -->
    </div><!-- .posts_and_questions_div ends -->

    <!-- .write_answer starts -->
    <div class="write_answer" id="write_answer_div<?=$sd->question_id?>" style="display:none">
        <!-- .write_answer_top starts -->
        <div class="write_answer_top">
            <div style="font-size:18px;color:#888" onclick="show_div('write_answer_div<?=$sd->question_id?>')"><b> X </b></div>
            <div class="button" style="font-size:12px">Post</div>
        </div><!-- .write_answer_top ends -->
        <div style="display:flex">
            <div class="profile_image_div" style="margin-top:5px">
                <a href="<?=$profile_picture?>"><img src="<?=$profile_picture?>" class="profile_image"/></a>
            </div>
            <div style="margin-left:-13px">
                <div style="font-size:12px"><b><?=$data->real_name?></b></b></div>
                <div class="input" style="width:100%;font-size:12px"><?=$data->bio?> &nbsp; <i class="fa fa-angle-down"></i></div>
            </div>
        </div>
        
        <h4 style="width:90%"><?=$sd->title?></h4>
        <!--<div style="color:#888">Add Image + </div>--><!-- coming soon -->
        <div class="">
            <textarea class="textarea" placeholder="Write your answer on this"></textarea>
        </div>
    </div><!-- .write_answer ends -->

    <!-- demarcation --><div class="demarcation" style="width:100%;height:7px;background-color:#d6e3fd"></div><!-- demarcation -->
<?php
    }
?>

</div><!-- .main_body ends -->

<?php
Index_Segments::footer();
?>