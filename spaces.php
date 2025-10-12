<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/views/Index_Segments.php");
Index_Segments::header();
?>

<div class="main_body">
    <!-- .spaces start -->
    <div class="your_spaces">
        <div style="border-bottom:2px solid #2b8eeb;padding:12px 9px"><h3>Your Spaces</h3></div>
        
        <div style="display:flex;justify-content:space-between;padding:12px;border-bottom:1px solid #888">
            <div><img src="/static/images/space1.png"/> &nbsp; <b>Medical Robotics</b></div>
            <div><i class="fa fa-angle-right"></i></div>
        </div>

        <div style="display:flex;justify-content:space-between;padding:12px;border-bottom:1px solid #888">
            <div><img src="/static/images/space2.png"/> &nbsp; <b>Surgery</b></div>
            <div><i class="fa fa-angle-right"></i></div>
        </div>

        <div style="display:flex;justify-content:space-between;padding:12px;border-bottom:1px solid #888">
            <div><img src="/static/images/space2.png"/> &nbsp; <b>Anatomy</b></div>
            <div><i class="fa fa-angle-right"></i></div>
        </div>

        <div style="display:flex;justify-content:space-between;padding:12px;border-bottom:1px solid #888">
            <div><img src="/static/images/space2.png"/> &nbsp; <b>Biochemistry</b></div>
            <div><i class="fa fa-angle-right"></i></div>
        </div>

        <div style="display:flex;justify-content:space-between;padding:12px;border-bottom:1px solid #888">
            <div><img src="/static/images/space2.png"/> &nbsp; <b>Pharmacology</b></div>
            <div><i class="fa fa-angle-right"></i></div>
        </div>

        <div style="display:flex;justify-content:space-between;padding:12px;border-bottom:1px solid #888">
            <div><img src="/static/images/space2.png"/> &nbsp; <b>Bioelectronics</b></div>
            <div><i class="fa fa-angle-right"></i></div>
        </div>

        <div style="display:flex;justify-content:space-between;padding:12px;border-bottom:1px solid #888">
            <div><img src="/static/images/space2.png"/> &nbsp; <b>Medical Wearables and IOT in Medicine</b></div>
            <div><i class="fa fa-angle-right"></i></div>
        </div>

        <div style="display:flex;justify-content:space-between;padding:12px;border-bottom:1px solid #888">
            <div><img src="/static/images/space2.png"/> &nbsp; <b>Internal Medicine</b></div>
            <div><i class="fa fa-angle-right"></i></div>
        </div>
    </div><!-- .spaces end -->

    <!-- demarcation --><div class="demarcation" style="width:100%;height:7px;background-color:#d6e3fd"></div><!-- demarcation -->

    <!-- .discover_spaces starts -->
    <div class="discover_spaces" style="padding:12px">
        <h3>Spaces You May Like</h3>

        <!-- .discover_spaces_div_container starts -->
        <div class="discover_spaces_div_container">
            <!-- .discover_spaces_div1 starts -->
            <div class="discover_spaces_div" style="position:relative;border-radius:9px;border:1px solid #888;width:41%">
                <div style="height:9%;width:auto;overflow:hidden"><img src="/static/images/spaces_cover_photo1.png" style="height:fit-content;width:auto;border-radius:9px 9px 0 0"/></div>

                <div style="height:fit-content;width:100%;margin:42px 0;background-color:#fff">
                    <div style="text-align:center;padding:15px 12px"><b>Radiography</b></div>
                    <div style="text-align:center;padding:0 12px 15px 12px">The Eyes of Medicine. <br />Can you make a diagnosis from these x-ray images?</div>
                </div>

                <div style="position:absolute;height:70px;width:70px;border-radius:9px;border:1px solid #888;top:15%;left:28%;"><img src="/static/images/spaces_profile_photo1.png" style="width:100%;height:auto;border-radius:9px"/></div>
            </div><!-- .discover_spaces_div1 ends -->

            <!-- .discover_spaces_div2 starts -->
            <div class="discover_spaces_div" style="position:relative;border-radius:9px;border:1px solid #888;width:41%">
                <div style="height:9%;width:auto;overflow:hidden"><img src="/static/images/spaces_cover_photo2.png" style="height:fit-content;width:auto;border-radius:9px 9px 0 0"/></div>

                <div style="height:fit-content;width:100%;margin:42px 0;background-color:#fff">
                    <div style="text-align:center;padding:15px 12px"><b>Nursing</b></div>
                    <div style="text-align:center;padding:0 12px 15px 12px">Nurses are great!. <br />Nurses are caring !! <br />Yes, I'm proud to be a Nurse!!!</div>
                </div>

                <div style="position:absolute;height:70px;width:70px;border-radius:9px;border:1px solid #888;top:15%;left:28%;"><img src="/static/images/spaces_profile_photo2.png" style="width:100%;height:auto;border-radius:9px"/></div>
            </div><!-- .discover_spaces_div2 ends -->

            <!-- .discover_spaces_div3 starts -->
            <div class="discover_spaces_div" style="position:relative;border-radius:9px;border:1px solid #888;width:41%">
                <div style="height:9%;width:auto;overflow:hidden"><img src="/static/images/spaces_cover_photo3.png" style="height:fit-content;width:auto;border-radius:9px 9px 0 0"/></div>

                <div style="height:fit-content;width:100%;margin:42px 0;background-color:#fff">
                    <div style="text-align:center;padding:15px 12px"><b>Organ Regeneration</b></div>
                    <div style="text-align:center;padding:0 12px 15px 12px">Do you believe whole limb generation would be possible soon? Regeneration of other organs too? How soon do you think this will be?</div>
                </div>

                <div style="position:absolute;height:70px;width:70px;border-radius:9px;border:1px solid #888;top:15%;left:28%;"><img src="/static/images/spaces_profile_photo3.png" style="width:100%;height:auto;border-radius:9px"/></div>
            </div><!-- .discover_spaces_div3 ends -->


        </div><!-- .discover_spaces_div_conainer starts -->
    </div><!-- .discover_spaces ends -->
</div><!-- .main_body ends -->

<?php
Index_Segments::footer();
?>