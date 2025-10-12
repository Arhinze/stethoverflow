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

    <div class="discover_spaces" style="padding:12px">
        <h3>Spaces You May Like</h3>

        <div class="discover_spaces_div_container">
            <div class="discover_spaces_div" style="position:relative;border-radius:9px;border:1px solid #888;width:41%">
                <div style="height:50%;width:auto;overflow:hidden;border-bottom:3px solid #2b8eeb">Hello !!<!--<img src="/static/images/spaces_cover_photo1.png" style="height:15%;border-radius:9px 9px 0 0"/>--></div>

                <!--<div style="position:absolute;height:18%;width:18%;border-radius:9px;border:1px solid #fff;top:8%;left:32%"><img src="/static/images/spaces_profile_photo1.png" style="width:100%;height:auto;border-radius:9px"/></div>-->

                <div style="height:85%;width:100%;background-color:#fff">
                    <div style="text-align:center;padding:15px 12px"><b>Radiography</b></div>
                    <div style="text-align:center;padding:0 12px 15px 12px">The Eyes of Medicine. Can yoy make a diagnosis from these x-ray images?</div>
                </div>
            </div>
        </div>


    </div>
</div>

<?php
Index_Segments::footer();
?>