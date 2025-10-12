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

    <div class="discover_spaces">
        <h3 style="padding:12px 9px">Discover Spaces</h3>

        <div class="discover_spaces_div_container">
            <div class="discover_spaces_div" style="position:relative;border-radius:9px">
                <div style="height:15%;width:100%;overflow:hidden"><img src="/static/images/cover_photo1.png"/></div>
                <div style="position:absolute;height:18%;width:15%;border-radius:9px;border:1px solid #fff;top:8%;left:32%"><img src="/static/images/spaces_profile_photo1.png"/></div>
                <div style="height:85%;width:100%;background-color:#fff">
                    <div style="text-align:center"><b>Radiography</b></div>
                    <div style="text-align:center"><b>The Eyes of Medicine. Can yoy make a diagnosis from these x-ray images?</b></div>
                </div>
            </div>
        </div>


    </div>
</div>

<?php
Index_Segments::footer();
?>