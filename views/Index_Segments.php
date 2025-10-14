<?php
ini_set("display_errors", '1'); //for testing purposes..

include_once($_SERVER["DOCUMENT_ROOT"]."/php/connection.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/php/account-manager.php");

class Index_Segments{
    public static function inject($obj) {
        Index_Segments::$pdo = $obj;
    }
    protected static $pdo;

    public static function main_header($site_name = SITE_NAME_SHORT) {
        echo <<<HTML
            <!-- start of .headers --> 
            <div class="headers">
                <div class="header_search_icon" style="margin-left:9px;margin-top:8px;padding:1px 5px" onclick="show_div('header_search')">
                    <i class="fa fa-search" style="font-size:12px"></i> Search
                </div> 
                <div class="header_search" id="header_search" style="display:none">
                    <input type="text" placeholder="search for .." class="header_input" id="index_search" onkeyup="ajax_index_search()"/>
                    <span class="x_remove" onclick="clear_and_close('header_search')"><i class="fa fa-times"></i></span>
                </div>
                <h1 class="site_name">
                    <a href="/" style="color:#fff">$site_name<!--site_name--></a>
                </h1>
                <div style="margin-right:12px">
                    <i class="fa fa-plus-circle"></i> Add
                </div>
            </div> <a name="#top"></a> 
            <!-- end of .headers --> 
            <!-- start of 2nd .headers --> 
            <div class="headers" style="top:50px;background-color:#fff;color:#888;font-size:21px;padding:6px 18px">
                <div class="" style="margin-top:6px"><a href="/" style="color:#acc5f8"><i class="fa fa-home"></i></a></div>
                <div class="" style="margin-top:6px"><a href="/questions"><i class="fa fa-pencil-square-o"></i></a></div>
                <div class="" style="margin-top:6px"><a href="/spaces"><i class="fa fa-users"></i></a></div>
                <div class="" style="margin-top:6px"><a href="/notifications"><i class="fa fa-bell-o"></i></a></div>
                <div class="profile_image_div">
                    <a href="/static/images/profile.png"><img src="/static/images/profile.png" class="profile_image"/></a>
                </div>
            </div><!-- end of 2nd .headers --> 
HTML;
    }
    
    public static function header($site_name = SITE_NAME_SHORT, $site_url = SITE_URL, $Hi_user = "", $title=SITE_NAME){
        
        $main_header = Index_Segments::main_header();
        $css_version = filemtime($_SERVER["DOCUMENT_ROOT"]."/static/style.css");

        if (isset($_GET["ref"])) {
            $ref = htmlentities($_GET["ref"]);

            if(isset($_COOKIE["ref"])){
                //delete existing referer cookie
                setcookie("ref", $ref, time()-(24*3600), "/");
            }

            //set new referer cookie:
            setcookie("ref", $ref, time()+(12*3600), "/");
        }

        echo <<<HTML
        <!doctype html>
        <html lang="en">
        <head>
          
            <link rel="stylesheet" href="/static/style.css?$css_version"/>
            <link rel="icon" type="image/x-icon" href="/favicon.ico"/>
            <link rel="stylesheet" href="/static/font-awesome-4.7.0/css/font-awesome.min.css">
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=RocknRoll+One&display=swap" rel="stylesheet">
            <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito|Hammersmith+One|Trirong|Arimo|Prompt"/>
            
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
                
            <title>$title</title>
            <meta name="google-site-verification" content="myB30Ys5EUELpwugPrQITsFCTnKdfNCf9Owd0t6pjmM" /><!-- google site ownership verification -->  
        </head>
        <body>
            $main_header
            
            <div id="search_hint"></div>
            <!-- used by index_ajax_search() function-->
HTML;
       }
                
        public static function body($site_name = SITE_NAME_SHORT, $site_url = SITE_URL){
            $site_name_uc = strtoupper($site_name);

            echo <<<HTML
                <!-- .main_body starts -->
                <div class="main_body">
                    <!-- .main_page_topmost_div starts -->
                    <div class="main_page_topmost_div" style="padding:12px">
                        <div style="display:flex;background-color:#fff">
                            <div style="width:30px;height:30px;border:2px solid #d6e3fd;border-radius:100%">
                                <a href="$site_url/static/images/profile.png"><img src="/static/images/profile.png" style="width:27px;height:27px;border-radius:100%;margin:1.35px 0 0 1.35px"/></a>
                            </div>
                            <div style="width:90%;margin-left:6px">
                                <input type="text" placeholder="What do you want to ask or share?" style="width:100%" class="input"/>
                            </div>
                        </div>
    
                        <div style="display:flex;justify-content:space-around;margin-top:9px;">
                            <div><i class="fa fa-question-circle-o"></i> Ask</div>
                            <div><a href="/questions" style="color:#000"><i class="fa fa-edit"></i> Answer</a></div>
                            <div><i class="fa fa-pencil"></i> Post</div>
                            <div></div>
                        </div>
                    </div>
                    <!-- .main_page_topmost_div ends -->

                    <!-- demarcation --><div class="demarcation" style="width:100%;height:7px;background-color:#d6e3fd"></div><!-- demarcation --> 

                    <!-- .posts_and_questions_div starts -->
                    <div class="posts_and_questions" style="margin:12px 6px">
                        <div style="display:flex">
                            <div style="width:39px;height:39px;border:2px solid #d6e3fd;border-radius:100%">
                                <a href="$site_url/static/images/profile.png"><img src="/static/images/profile.png" style="width:36px;height:36px;border-radius:100%;margin:1.35px 0 0 1.35px"/></a>
                            </div>
                            <div style="margin-left:6px;margin-top:2px">
                                <div style="font-size:15px"><b>Francis Arinze</b> <i class="fa fa-circle" style="font-size:6px"></i> <b style="color:#2b8eeb">Follow</b></div>
                                <div style="color:#888;font-size:12px">Orthopaedic Surgeon (2007 - present) <i class="fa fa-circle" style="font-size:6px"></i> 21h</div>
                            </div>
                        </div>

                        <div class="questions" style="margin-bottom:3px"><h4>What are some life saving surgical procedures you've witnessed?</h4></div>

                        <div class="answers" style="padding:6px">In 2014, 17-year-old Grace from the Democratic Republic of Congo underwent life-saving surgery to remove a very aggressive tumor.                            
                            <p>The huge disfigurement was sprouting from the centre of her lower jaw bone and was formed by cells that usually make the enamel of teeth.</p>
    
                            <p>If she didn't have it removed, it would have eventually suffocated her to death.</p>
                            
                            <div><img src="/static/images/post1.png" style="width:100%;height:auto"/></div>
                        </div>

                        <!-- .like,comment and share icons start -->
                        <div style="display:flex;justify-content:space-between;margin:12px 9px -2px 9px;color:#888">
                            <div class="" style="display:flex">
                                <div><span id="post1" style="color:grey" onclick="like_post()"><i class="fa fa-heart-o"></i></span> 10</div>
                                <div style="margin-left:10px"><i class="fa fa-comment-o" onclick="show_div('add_comment')"></i> 9</div>
                                <div style="margin-left:10px"><i class="fa fa-retweet" onclick="show_div('quote_comment_div1')"></i> 3</div>
                                <div style="margin-left:10px"><i class="fa fa-share-alt"></i> 5</div>
                            </div>
                            <div>
                                ...
                            </div>
                        </div><!-- .like,comment and share icons end -->
                    </div><!-- .posts_and_questions_div ends -->

                    <!-- .add_comment starts -->
                    <div style="display:none" class="add_comment" id="add_comment" onclick="alternate_comment_div()">
                        <div style="display:flex">
                            <div class="profile_image_div" style="border:2px solid #fff">
                                <a href="/static/images/profile.png"><img src="/static/images/profile.png" class="profile_image"/></a>
                            </div>
                            <div class="input" style="background-color:#fff;color:#888;margin-left:-13px">Add a comment...</div>
                        </div>
                    </div>
                    <!-- .add_comment ends -->

                    <!-- .reply_comment starts -->
                    <div class="reply_comment" id="reply_comment" style="display:none">
                        <div style="display:flex;justify-content:space-between;margin-top:9px">
                            <div style="display:flex">
                                <div class="profile_image_div">
                                    <a href="/static/images/profile.png"><img src="/static/images/profile.png" class="profile_image"/></a>
                                </div>
                                <div style="margin-left:-17px;margin-top:7px;color:#888">Replying to <a href="/">@arhinze</a></div>
                            </div>
                            <div style="margin-top:10px;margin-right:12px;color:#888"><i class="fa fa-times" onclick="alternate_comment_div()"></i></div>
                        </div>
                        <div>
                            <textarea class="textarea" style="height:150px"></textarea>
                        </div>
                        <div><button class="button">Reply</button></div>
                    </div>
                    <!-- .reply_comment ends -->

                    <!-- .quote_comment starts -->
                    <!-- .write_answer starts -->
                    <div class="write_answer" id="quote_comment_div1" style="display:none">
                        <!-- .write_answer_top starts -->
                        <div class="write_answer_top">
                            <div style="font-size:18px;color:#888" onclick="show_div('quote_comment_div1')"><b> X </b></div>
                            <div class="button" style="font-size:12px">Post</div>
                        </div><!-- .write_answer_top ends -->
                        <div style="display:flex">
                            <div class="profile_image_div" style="margin-top:5px">
                                <a href="/static/images/profile.png"><img src="/static/images/profile.png" class="profile_image"/></a>
                            </div>
                            <div style="margin-left:-13px">
                                <div style="font-size:12px"><b>Francis Arinze</b></b></div>
                                <div class="input" style="width:100%;font-size:12px">Orthopaedic Surgeon (2007 - present) &nbsp; <i class="fa fa-angle-down"></i></div>
                            </div>
                        </div>
                     
                        <div style="color:#888">Add Image + </div>

                        <div class="">
                            <textarea class="textarea" style="border-radius:0;border-bottom:0;height:50px" placeholder="Make a post about this"></textarea>
                        </div>

                        <!-- .quoted_post starts -->
                        <div style="border-left:2px solid #888;padding-left:9px">
                            <div style="display:flex">
                                <div class="profile_image_div">
                                    <a href="/static/images/profile.png"><img src="/static/images/profile.png" class="profile_image"/></a>
                                </div>
                                <div style="color:#888;margin-left:-17px;margin-top:7px">Francis Arinze - <a href="/">@arhinze</a></div>
                            </div>

                            <div class="questions" style="margin-bottom:3px"><h4>What are some life saving surgical procedures you've witnessed?</h4></div>
    
                            <div class="answers" style="padding:6px">
                                In 2014, 17-year-old Grace from the Democratic Republic of Congo underwent life-saving surgery to remove a very aggressive tumor.  
                            </div>

                            <div><img src="/static/images/post1.png" style="width:100%;height:auto"/></div>
                        </div><!-- .quoted_post ends -->
                    </div><!-- .write_answer ends -->
                    <!-- .quote_comment ends -->

                    <!-- demarcation --><div class="demarcation" style="width:100%;height:7px;background-color:#d6e3fd"></div><!-- demarcation -->

                    <!-- .posts_and_questions_div starts -->
                    <div class="posts_and_questions" style="margin:12px 6px">
                        <div style="display:flex">
                            <div style="width:39px;height:39px;border:2px solid #d6e3fd;border-radius:100%">
                                <a href="$site_url/static/images/profile.png"><img src="/static/images/profile.png" style="width:36px;height:36px;border-radius:100%;margin:1.35px 0 0 1.35px"/></a>
                            </div>
                            <div style="margin-left:6px;margin-top:2px">
                                <div style="font-size:15px"><b>Francis Arinze</b> <i class="fa fa-circle" style="font-size:6px"></i> <b style="color:#2b8eeb">Follow</b></div>
                                <div style="color:#888;font-size:12px">Orthopaedic Surgeon (2007 - present) <i class="fa fa-circle" style="font-size:6px"></i> 21h</div>
                            </div>
                        </div>

                        <div class="questions" style="margin-bottom:3px"><h4>Why don't elderly men have surgery to remove their prostrate as standard, since so many prostrates end up causing death?</h4></div>

                        <!-- .answers start -->
                        <div class="answers" style="padding:6px">
                            <p>I recently had a discussion about this with my urologist, after my primary care doctor had referred me because of an elevated PSA (test for prostate-specific antigen).</p>

                            <p>He said that the PSA should be discontinued after age 70 (I'm 80 as I write this). The reasoning is that prostate cancer is so slow acting that one will likely die of something else before it becomes fatal. It is not worth the pain and discomfort of cancer treatment or prostate surgery.</p>

                            <p>It is middle-aged men who are a risk of death from prostate cancer.</p>
                        
                            <img src="/static/images/post2.png" style="width:100%;height:auto"/>
                        </div><!-- .answers stop -->
                    </div><!-- .posts_and_questions_div ends -->

                    <!-- demarcation --><div class="demarcation" style="width:100%;height:7px;background-color:#d6e3fd"></div><!-- demarcation -->

                    <!-- .posts_and_questions_div starts -->
                    <div class="posts_and_questions" style="margin:12px 6px">
                        <div style="display:flex">
                            <div style="width:39px;height:39px;border:2px solid #d6e3fd;border-radius:100%">
                                <a href="$site_url/static/images/profile.png"><img src="/static/images/profile.png" style="width:36px;height:36px;border-radius:100%;margin:1.35px 0 0 1.35px"/></a>
                            </div>
                            <div style="margin-left:6px;margin-top:2px">
                                <div style="font-size:15px"><b>Francis Arinze</b> <i class="fa fa-circle" style="font-size:6px"></i> <b style="color:#2b8eeb">Follow</b></div>
                                <div style="color:#888;font-size:12px">Orthopaedic Surgeon (2007 - present) <i class="fa fa-circle" style="font-size:6px"></i> 21h</div>
                            </div>
                        </div>

                        <div class="questions" style="margin-bottom:3px"><h4>Can you share some of your surgery experiences as a doctor?</h4></div>

                        <!-- .answers start -->
                        <div class="answers" style="padding:6px">
                            <p>On one fine evening while doing my opd patients I received a call from emergency department.</p>

                            <p>The said patient was a 30 years gentleman with history of run over by a truck on his both lower limb, he was hauling with pain, profuse bleeding, and was in hypovolumic shock.</p>
                            
                            <p>He was taken to ICU for resuscitation, After stabilisation we posted him for emergency surgeries, Rt. Lower limb needed amputation below knee in view of irreversible damage to blood vessels, soft tissue and bones, Lt lower limb we could save and fixed with a nail but left with huge area devoid of skin.</p>
                            
                            <p>We started the Surgery at about 11 pm and finished at 5 am.</p>
                            
                            <p>After the initial stabilisation he underwent many dressings bed side procedures and skin grafting.</p>
                            
                            <p>After 3 to 4 months of pain and despite of all difficulties he could stand with one artificial limb and one what we could save.</p>
                            
                            <p>Now its been a year and he walks near normal with the prosthesis without stick.</p>
                            
                            <p>It gives immense pleasure and gratitude when I see him walking.</p>
                        
                            <img src="/static/images/post3.png" style="width:100%;height:auto"/>
                        </div><!-- .answers stop -->
                    </div><!-- .posts_and_questions_div ends -->

                    <!-- demarcation --><div class="demarcation" style="width:100%;height:7px;background-color:#d6e3fd"></div><!-- demarcation -->

                    <!-- .posts_and_questions_div starts -->
                    <div class="posts_and_questions" style="margin:12px 6px">
                        <div style="display:flex">
                            <div style="width:39px;height:39px;border:2px solid #d6e3fd;border-radius:100%">
                                <a href="$site_url/static/images/profile.png"><img src="/static/images/profile.png" style="width:36px;height:36px;border-radius:100%;margin:1.35px 0 0 1.35px"/></a>
                            </div>
                            <div style="margin-left:6px;margin-top:2px">
                                <div style="font-size:15px"><b>Francis Arinze</b> <i class="fa fa-circle" style="font-size:6px"></i> <b style="color:#2b8eeb">Follow</b></div>
                                <div style="color:#888;font-size:12px">Orthopaedic Surgeon (2007 - present) <i class="fa fa-circle" style="font-size:6px"></i> 21h</div>
                            </div>
                        </div>

                        <div class="questions" style="margin-bottom:3px"><h4>How do brain surgeons close the brain after surgery?</h4></div>

                        <!-- .answers start -->
                        <div class="answers" style="padding:6px">
                            <p>Depends on the age of the patient. Plus the affordability/ availablity of resources.</p>
                            
                            <p>For children, especially infants, toddlers and pre-adolescent patients, the skull flap is secured with sutures. Tiny holes are made in the bone flap and the corresponding area of the skull and sutures (mostly silk sutures) are passed through these holes and tied. This is done to prevent any hindrance to the bone growth by metallic implants.</p>
                            
                            <p>For adults miniplates and screws are fixed to secure the bone flap in place.</p>

                            <p>The miniplates and screws are relatively expensive, and in a country like Nigeria, where a majority of the population belongs to the lower socio-economic group, sutures are used instead of implants, for patients who cannot afford them.</p>

                            <p>The results with both are comparable.</p>

                            <img src="/static/images/post4.png" style="width:100%;height:auto"/>
                        </div><!-- .answers stop -->
                    </div><!-- .posts_and_questions_div ends -->

                    <!-- demarcation --><div class="demarcation" style="width:100%;height:7px;background-color:#d6e3fd"></div><!-- demarcation -->

                    <!-- .posts_and_questions_div starts -->
                    <div class="posts_and_questions" style="margin:12px 6px">
                        <div style="display:flex">
                            <div style="width:39px;height:39px;border:2px solid #d6e3fd;border-radius:100%">
                                <a href="$site_url/static/images/profile.png"><img src="/static/images/profile.png" style="width:36px;height:36px;border-radius:100%;margin:1.35px 0 0 1.35px"/></a>
                            </div>
                            <div style="margin-left:6px;margin-top:2px">
                                <div style="font-size:15px"><b>Francis Arinze</b> <i class="fa fa-circle" style="font-size:6px"></i> <b style="color:#2b8eeb">Follow</b></div>
                                <div style="color:#888;font-size:12px">Orthopaedic Surgeon (2007 - present) <i class="fa fa-circle" style="font-size:6px"></i> 21h</div>
                            </div>
                        </div>

                        <div class="questions" style="margin-bottom:3px"><h4>Has anyone been born without a brain?</h4></div>

                        <!-- .answers start -->
                        <div class="answers" style="padding:6px">
                            <p>Yes, it's called hydranencephaly.</p>

                            <p>The creepiest part is that you can shine a light through the babies skull because there's no brain tissue present.</p>

                            <p>This condition happens in about 1 in 5,000 pregnancies and typically results in death within minutes of the child being born (many of these pregnancies are terminated or miscarried). The brain does far more than thinking. It runs the whole ship. And if nothing is there, everything breaks down.</p>

                            <p>As horrible as this is, I am actually glad that the child passes almost immediately. This wouldn't be a way to live anyways.</p>
                        
                            <img src="/static/images/post5.png" style="width:100%;height:auto"/>
                        </div><!-- .answers stop -->
                    </div><!-- .posts_and_questions_div ends -->

                    <!-- demarcation --><div class="demarcation" style="width:100%;height:7px;background-color:#d6e3fd"></div><!-- demarcation -->

                    <!-- .posts_and_questions_div starts -->
                    <div class="posts_and_questions" style="margin:12px 6px">
                        <div style="display:flex">
                            <div style="width:39px;height:39px;border:2px solid #d6e3fd;border-radius:100%">
                                <a href="$site_url/static/images/profile.png"><img src="/static/images/profile.png" style="width:36px;height:36px;border-radius:100%;margin:1.35px 0 0 1.35px"/></a>
                            </div>
                            <div style="margin-left:6px;margin-top:2px">
                                <div style="font-size:15px"><b>Francis Arinze</b> <i class="fa fa-circle" style="font-size:6px"></i> <b style="color:#2b8eeb">Follow</b></div>
                                <div style="color:#888;font-size:12px">Orthopaedic Surgeon (2007 - present) <i class="fa fa-circle" style="font-size:6px"></i> 21h</div>
                            </div>
                        </div>

                        <!-- .answers start -->
                        <div class="answers" style="padding:6px">
                            <p>Robert Liston, popularly known as the fastest knife in the west was famous for his method of surgery.</p>

                            <p>The Scottish surgeon was popular in the 18th century for his speed and precision in carrying out surgical operations.</p>

                            <p>This was before the invention of anaesthesia, thus speed was implored to prevent mortality. A skilled surgeon could perform amputation in 3 minutes.</p>

                            <p>Patients however often died from post-operative infection and blood loss. Liston however had a strong sense of cleanliness and advocated for handwashing and sterile surgical procedures long before the formulation of the theory of germs and antiseptics.
                            </p>

                            <p>Liston would usually turn to his students and audience shortly before surgery and say: "Time me, gentlemen, time me". His above-the-knee amputationsmwere completed in less than 30 seconds (from incision to final suture).</p>

                            <p>This speed is however not without some costs.</p>

                            <p>Liston reportedly accidentally removed a patient's testicles along with the leg being amputated due to the swiftness of his work on one occasion.</p>

                            <p>On a different occasion, during an amputation, Liston accidentally cut off his assistant's fingers and slashed a spectator's coat. His patient and assistant both died from sepsis while the spectator died from shock.</p>

                            <p>Thus this surgery had a mortality rate of 300%.</p>
                            
                            <p>He is famously known and remembered historically as the surgeon with the highest mortality rate from a single surgery and he performed Europe's first operation using anaesthesia in the 19th century.</p>
                        
                            <img src="/static/images/post7.png" style="width:100%;height:auto"/>
                        </div><!-- .answers stop -->
                    </div><!-- .posts_and_questions_div ends -->

                    <!-- demarcation --><div class="demarcation" style="width:100%;height:7px;background-color:#d6e3fd"></div><!-- demarcation -->

                    <!-- .posts_and_questions_div starts -->
                    <div class="posts_and_questions" style="margin:12px 6px">
                        <div style="display:flex">
                            <div style="width:39px;height:39px;border:2px solid #d6e3fd;border-radius:100%">
                                <a href="$site_url/static/images/profile.png"><img src="/static/images/profile.png" style="width:36px;height:36px;border-radius:100%;margin:1.35px 0 0 1.35px"/></a>
                            </div>
                            <div style="margin-left:6px;margin-top:2px">
                                <div style="font-size:15px"><b>Francis Arinze</b> <i class="fa fa-circle" style="font-size:6px"></i> <b style="color:#2b8eeb">Follow</b></div>
                                <div style="color:#888;font-size:12px">Orthopaedic Surgeon (2007 - present) <i class="fa fa-circle" style="font-size:6px"></i> 21h</div>
                            </div>
                        </div>

                        <!-- .answers start -->
                        <div class="answers" style="padding:6px">
                            <p>I lost a friend to glioblastoma.</p>
                            
                            <p>It's the most common brain cancer and starts with tumors in the brain. It's likely that many people seeing this have also lost someone to this disease.</p>
                            
                            <p>He woke up one day with a mind splitting headache and was rushed to the hospital.</p>
                            
                            <p>He was diagnosed. They removed the tumor in his brain and started radiation and chemotherapy.</p>
                            
                            <p>Despite this, the doctors said he'd have only two to three years to live at most (he lived 3.5 years, before passing). He was only 25 years old.</p>
                            
                            <p>Why is glioblastoma near impossible to cure? Because when you remove the tumor, it still has cells that spread to other parts of the brain, which then begin growing into new tumors. There's motility of the cancer cells. This is why people who this type of cancer have to get multiple brain surgeries to stay alive. They'll have scars all over their head if they live long enough.</p>
                        
                            <img src="/static/images/post8.png" style="width:100%;height:auto"/>
                        </div><!-- .answers stop -->
                    </div><!-- .posts_and_questions_div ends -->

                    <!-- demarcation --><div class="demarcation" style="width:100%;height:7px;background-color:#d6e3fd"></div><!-- demarcation -->

                    <!-- .posts_and_questions_div starts -->
                    <div class="posts_and_questions" style="margin:12px 6px">
                        <div class="questions" style="margin-bottom:3px;display:flex">
                            <h4 style="width:90%">What is the most bizarre and rare syndrome you've personally encountered or read about?</h4>
                            <div style="width:6%;margin-top:24px;margin-left:6px"><i class="fa fa-times"></i></div>
                        </div>

                        <!-- .below_only_questions start -->
                        <div class="below_only_questions" style="display:flex">
                            <div style="border:1px solid #888;padding:6px;border-radius:15px"><i class="fa fa-edit"></i>&nbsp; Answer </div>
                            <div style="margin-left:12px;margin-top:6px"><i class="fa fa-feed"></i>&nbsp; Follow <i class="fa fa-circle" style="font-size:3px"></i> 3</div>
                        </div><!-- .below_only_questions end -->
                    </div><!-- .posts_and_questions_div ends -->

                    <!-- demarcation --><div class="demarcation" style="width:100%;height:7px;background-color:#d6e3fd"></div><!-- demarcation -->

                    <!-- .posts_and_questions_div starts -->
                    <div class="posts_and_questions" style="margin:12px 6px">
                        <div class="questions" style="margin-bottom:3px;display:flex">
                            <h4 style="width:90%">Doctors: What's the worst piece of health advice you hear non-professionals give all the time?</h4>
                            <div style="width:6%;margin-top:24px;margin-left:6px"><i class="fa fa-times"></i></div>
                        </div>

                        <!-- .below_only_questions start -->
                        <div class="below_only_questions" style="display:flex">
                            <div style="border:1px solid #888;padding:6px;border-radius:15px"><i class="fa fa-edit"></i>&nbsp; Answer </div>
                            <div style="margin-left:12px;margin-top:6px"><i class="fa fa-feed"></i>&nbsp; Follow <i class="fa fa-circle" style="font-size:3px"></i> 3</div>
                        </div><!-- .below_only_questions end -->
                    </div><!-- .posts_and_questions_div ends -->

                    <!-- demarcation --><div class="demarcation" style="width:100%;height:7px;background-color:#d6e3fd"></div><!-- demarcation -->
                </div><!-- .main_body ends -->
HTML;

       }
                                                                
       public static function index_scripts(){
        echo <<<HTML
                                                                
        <!-- Footer - index_scripts -->
        <script>
            function show_div(vari) {
                if (document.getElementById(vari).style.display == "none") {
                    document.getElementById(vari).style.display = "block";
                } else if (document.getElementById(vari).style.display == "block") {
                    document.getElementById(vari).style.display = "none";
                }
            }

            function like_post(){
                if (document.getElementById("post1").style.color == "grey") {
                    document.getElementById("post1").style.color = "red";
                    document.getElementById("post1").innerHtml = "<i class='fa fa-heart'></i>";             
                } else if (document.getElementById("post1").style.color == "red") {
                    document.getElementById("post1").style.color = "grey";
                    document.getElementById("post1").innerHtml = "<i class='fa fa-heart-o'></i>";             
                }
            }

            function alternate_comment_div(){
                if (document.getElementById('add_comment').style.display == "block") {
                    document.getElementById('add_comment').style.display = "none";
                    document.getElementById('reply_comment').style.display = "block";
                } else if (document.getElementById('add_comment').style.display == "none") {
                    document.getElementById('add_comment').style.display = "block";
                    document.getElementById('reply_comment').style.display = "none";
                }
            }
                                                                                         
            const collection = document.getElementsByClassName("invalid");
                                                                                                 
            for (let i=0; i < collection.length; i++){
                //collection[i].style = "display:none";
                                                                                                            
                var innerHT = collection[i].innerHTML;
                                                                                            
                var newInnerHT = innerHT + "<span style='float:right;margin:4px 18px'><i class='fa fa-times' onclick='hide_invalid_div()'></i></span>";
                          
                collection[i].innerHTML = newInnerHT;
            }
                                                                                           
            function hide_invalid_div() {
                //const collection = document.getElementsByClassName("invalid");
                i = 0;
                for (i=0; i<collection.length; i++){
                    collection[i].style.display = "none";
                }  
            }
                                                                
            //Implementing multi-line placeholder for textarea html documents
            var textAreas = document.getElementsByTagName('textarea');
                                                                
            Array.prototype.forEach.call(textAreas, function(elem) {
                elem.placeholder = elem.placeholder.replace(/\\n/g, '\\n');
            });
                                                                
            function show_bt_input_div(){
                document.getElementById("bt_input_div").style.display = "block";
            }
                                                                        
            function close_bt_input_div(){
                document.getElementById("bt_input_div").style.display = "none";
            }
                                                                    
            function calculate_total(){
                total_num = document.getElementById("total_number").value;
                amt_for_each = document.getElementById("amount_to_pay_each_person").value;
                total_amount = Number(total_num) * Number(amt_for_each);
                                                                    
                document.getElementById("total_to_transfer_text").innerHTML = "<div style='margin:12px 3px'>Total cost of transaction: <b><i class='fa fa-naira'></i>N "+total_amount.toString()+"</b></div>";
                                                                
                obj = new XMLHttpRequest;
                obj.onreadystatechange = function(){
                    if(obj.readyState == 4){
                        if (document.getElementById("current_balance_text")){
                            document.getElementById("current_balance_text").innerHTML = obj.responseText;
                        }
                    }
                }
                                                                        
                obj.open("GET","/ajax/ajax_cb.php?total_="+total_amount);
                obj.send(null);
                                                                
                //disable button and allow only when total_amount < current balance and amt_for_each > 100
                button_status = document.getElementById("proceed_to_pay_button");
                current_balance_text = document.getElementById("current_balance_text");
                if((Number((current_balance_text.innerHTML).replace("N", "")) >= total_amount) & (amt_for_each >= 10)) {
                    button_status.style="background-color:#333131";
                    button_status.disabled = false;
                } else {
                    button_status.style="background-color:#888";
                    button_status.disabled = true;
                }
                                                                
                //turn current balance text green or red depending on if it's > or < than total_amount
                if(Number((current_balance_text.innerHTML).replace("N", "")) >= total_amount) {
                    current_balance_text.style="color:green";
                } else {
                    current_balance_text.style="color:red";
                }
            }
        </script>

        <script>
            function ajax_index_search(){
                sq = document.getElementById("index_search").value;
                obj = new XMLHttpRequest;
                obj.onreadystatechange = function(){
                    if(obj.readyState == 4){
                        document.getElementById("search_hint").style.display = "block";
                        document.getElementById("search_hint").innerHTML = obj.responseText;
                    }
                }
        
                obj.open("GET","/ajax/ajax_index_search.php?search_query="+sq);
                obj.send(null);
            }
        
            function search_icon(){
                location = "/search.php?query=" + document.getElementById("index_search").value;
            }

            function clear_and_close(s_q) {
                document.getElementById("search_hint").style.display = "none";
                show_div(s_q)
            }
        </script>
HTML;
        }
                                                                
                                                                
        public static function footer($site_name = SITE_NAME_SHORT, $site_url = SITE_URL, $additional_scripts = "", $whatsapp_chat = "on"){ 
                                                                            
            $index_scripts = Index_Segments::index_scripts();    

            if ($whatsapp_chat == "on") {
                echo <<<HTML
                    <!-- .whatsapp_box starts -->
                    <div class="whatsapp_box" id="whatsapp_box" style="display:none;position:fixed;bottom:129px;right:18px;background-color:#fff;border-radius:9px;width:75%;box-shadow:0 0 3px 0 #888">
                        <div class="whatsapp_box_top" style="display:flex;justify-content:space-around;height:30%;padding:12px;background-color:green;color:#fff;border-radius:9px 9px 0 0">
                            <div style="width:10%;margin-right:0"><i class="fa fa-whatsapp" style="font-size:42px;color:#fff"></i></div>
                            <div style="width:80%;text-align:left;margin-left:0">
                                <div style="font-size:21px;margin-bottom:6px">Start a Conversation</div>
                                <div>Hi! Click one of our member below to chat on <b>WhatsApp</b></div>
                            </div>
                        </div>
                        <div class="whatsapp_box_bottom" style="padding:12px">
                            <div style="font-size:12px;color:#888">The team typically replies in a few minutes.</div>
                            <a href="https://wa.me/2347076032581" style="color:#000"><!-- whatsapp link starts -->
                            <div style="display:flex;border-radius:0 6px 6px 0;background-color:#d9eee0;margin-top:15px">
                                <div style="display:flex;border-left:3px solid green">
                                    <div style="color:#fff;font-size:24px;padding:6px 8px;margin:15px 6px;border-radius:100%;background-color:green;"><i class="fa fa-whatsapp" onclick="show_div('whatsapp_box')"></i></div>
                                    <div style="font-size:21px;margin:16px 3">Support</div>
                                </div>
                                <div style="margin-left:42px;margin-top:21px;font-size:18px;color:green"><i class="fa fa-whatsapp"></i></div>
                            </div>
                            </a><!-- whatsapp link ends -->
                        </div>
                    </div>
                    <!-- .whatsapp_box ends -->
    
                    <!-- fixed whatsapp sticker(bottom-right) starts -->
                    <div style="color:#fff;font-size:33px;padding:9px 12px;border-radius:100%;background-color:green;position:fixed;bottom:72px;right:18px"><i class="fa fa-whatsapp" onclick="show_div('whatsapp_box')"></i></div>
                    <!-- fixed whatsapp sticker(bottom-right) ends -->
HTML;
            }
                                                                
        echo <<<HTML
        <br/><br/><br/><br/>
            <div class="footer"><!-- .footer starts --> 
                <div class="footer_site_name">$site_name</div>

                <div>Steth Overflow is designed to demystify medical facts and to bring you closer to your doctor for better healthcare.</div>

                <div class="footer_fa_links">
                    <a href="https://www.facebook.com/share/19MmpBuEn6/?mibextid=wwXIfr" style="color:#000"><i class="fa fa-facebook"></i></a> &nbsp;
                    <a href="https://www.tiktok.com/@bilo.ng?_t=ZM-8xVIXWH0E21&_r=1"><img src="/static/images/tiktok_logo.png" style="height:24px;width:auto;margin-bottom:-4px"/></a> &nbsp;
                    <a href="" style="color:#000"><i class="fa fa-instagram"></i></a> &nbsp;
                    <a href="https://www.youtube.com/@biloonline" style="color:#000"><i class="fa fa-youtube-play"></i></a> &nbsp;
                </div>

                <div class="footer_heading">Blog</div>
                Terms & Conditions
                Sitemap
                Press   

                <div class="footer_heading">Support</div>
                Documentation
                <b><a href="/contact">Help Center</a></b>
                General FAQs
                
                <div class="footer_heading">Newsletter</div>
                Stay medically up to date by joining our newsletter.
                
                 
                
                <div style="border-top:1px dotted #888;margin-top:15px;padding:15px 0;text-align:center">© 2025 $site_name <a href="/privacy-policy" style="font-weight:bold">Privacy Policy</a> All rights reserved. Designed & developed by Medivify</div>
            </div><!-- .footer ends -->                                                          
            $index_scripts
            $additional_scripts


        </body>
        </html>    
HTML;
    }
}

Index_Segments::inject($pdo);
?>