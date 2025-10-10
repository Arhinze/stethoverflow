<?php
ini_set("display_errors", '1'); //for testing purposes..

include_once($_SERVER["DOCUMENT_ROOT"]."/php/connection.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/views/Index_Segments.php");

$search_q = "";
$output = "";
if(isset($_GET["search_query"])){
    if(!empty($_GET["search_query"])){
        $search_q = htmlentities($_GET["search_query"]);
       
        $search_stmt = $pdo->prepare("SELECT * FROM products WHERE `description` LIKE ? LIMIT ?, ?");
        $search_stmt->execute(["%$search_q%", 0, 10]);
        $search_data = $search_stmt->fetchAll(PDO::FETCH_OBJ);
    }
}else {
    header("location: /");
}

Index_Segments::header();
?>
    <div class="main_body"><!-- .main_body starts -->
        <h5 style="text-align:center">Searching for: "<?=$search_q?>" in Bilo<span style="color:#ff9100">Online</span></h5>

        <!-- Search Result starts -->
        <!-- 1 to 30 -->
        <div class="topselling_div" style="flex-wrap:wrap"><!-- .flex_div starts(.topselling) -->
    <?php
        if(count($search_data) > 0){
            foreach($search_data as $search_d) {
                $short_description = substr($search_d->description,0,36);
                $search_d_price = number_format($search_d->price);
                $search_d_former_price = number_format($search_d->former_price);
    ?>
                <div class="deal_div"><!-- .deal_div starts --> 
                    <a href="/product/<?=$search_d->product_url?>" style="color:inherit"><!-- link to product page starts -->
                    <img src="/static/images/<?=$search_d->image1?>" class="deal_img"/>   
                    <div class="below_deal_img"><!-- .below_deal_img starts -->
                        <div class="topselling_choice_and_title">
                            <span class="topselling_choice"> Choice </span> &nbsp;
                            <span>
                                <?=$short_description?>...
                            </span>
                        </div>
                        <span class="deal_price_black">
                            NG N<?=$search_d_price?>
                        </span>  &nbsp; 
                        <span class="deal_former_price">
                            <s>NG N<?=$search_d_former_price?></s>
                        </span> 
                        <div class="star_and_rating">
                            <i class="fa fa-star"></i> <b>4.6</b> <span style="color:#888"> | </span> 1,000+ sold
                        </div>
    
                        <div class="topselling_text">
                            <i class="fa fa-fire"></i> Top selling on BiloOnline
                        </div>
                        <i class="fa fa-motorcycle"></i> Free shipping
                    </div><!-- .below_deal_img ends -->
                    </a><!-- link to product page ends -->
                </div><!-- .deal_div ends -->
    <?php
            }
        } else {
            echo "<div style='text-align:center'><b>No product found. Kindly check our various <a href='/categories'>categories</a></b></div>";
        }
    ?>
       </div><!-- .topsellingdiv ends -->
    </div><!-- .main_body ends -->
<?php
Index_Segments::footer($site_name = SITE_NAME_SHORT, $site_url = SITE_URL, $additional_scripts = Index_Segments::index_scripts(),$whatsapp_chat = "on");
        
    echo <<<HTML
            <!--this div is only meant to bring up the footer section of product page so that it's not covered by the fixed 'add_to_my_picks' div-->
            <div style="margin-top:45px"></div>
HTML;
?>