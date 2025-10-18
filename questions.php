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

    <!-- ajax will use this div to display sign in for logged out users: -->
    <div id="random_signin"></div>
<?php
    $search_question = $pdo->prepare("SELECT * FROM questions ORDER BY question_id DESC LIMIT ?, ?");
    $search_question->execute([0,1000]);
    $sq_data = $search_question->fetchAll(PDO::FETCH_OBJ);

    foreach($sq_data as $sd){
        if($data) {
            $submit_post = "<label for='answer_tag_$sd->question_id'>Post</label>"; 
        } else {
            $submit_post = "<span onclick=show_signin('random_signin')>Post</span>";
        }

        $atqi = "answer_to_question_".$sd->question_id;
        if(isset($_POST[$atqi])) {
            $search_answer = $pdo->prepare("SELECT * FROM answers WHERE answer = ? AND question_id = ? ORDER BY answer_id DESC LIMIT ?, ?");
            $search_answer->execute([htmlentities($_POST[$atqi]),$sd->question_id,0,1]);
            $sa_data = $search_answer->fetch(PDO::FETCH_OBJ);
            if(!$sa_data) {//that means this is a new answer
                $insert_stmt = $pdo->prepare("INSERT INTO answers(question_id,answer,user_id,time_answered) VALUES(?,?,?,?)");
                $insert_stmt->execute([htmlentities($sq->question_id, $_POST[$atqi]),$data->user_id,date("Y-m-d H:i:s", time())]);
        
                echo "<div class='invalid' style='background-color: #344c80ff'>Answer uploaded successfully</div>";
            } else {
                echo "<div class='invalid'>Sorry, this answer has already been given.</div>";
            }
        }
?>
    <!-- Question 1,2,3... -->
    <!-- .posts_and_questions_div starts -->
    <div class="posts_and_questions" id="question_id_<?=$sd->question_id?>" style="margin:12px 6px;display:block">
        <div class="questions" style="margin-bottom:3px;display:flex">
            <h4 style="width:90%"><?=$sd->title?></h4>
            <div style="width:6%;margin-top:24px;margin-left:6px;color:#888" onclick="show_div('question_id_<?=$sd->question_id?>')"><i class="fa fa-times"></i></div>
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
            <div class="button" style="font-size:12px"><?=$submit_post?></div>
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
            <form method="post" action="">
            <textarea class="textarea" name="answer_to_question_<?=$sd->question_id?>" placeholder="Write your answer on this"></textarea>
            <input type="submit" style="display:none" id="answer_tag_<?=$sd->question_id?>"/>
            </form>
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