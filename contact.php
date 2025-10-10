<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/views/Index_Segments.php");
Index_Segments::header();
?>

<div class="contact_us" style="margin:60px 15px">
    <form method="post" action="">
        <h1 style="margin-bottom:-2px">Contact Us:</h1><hr/>
            We are just a click away :)
        <input type="text" placeholder="Name: Example: John Smith" class="input"/>    
        <div style="margin:12px 0"><input type = "text" placeholder = "Email: abc@example.com" name = "email" class="input"/></div>
        <textarea class="textarea" placeholder="Enter Text" style="color:#fff"></textarea><br/>
        <button type="submit" class="button">Send <i class="fa fa-telegram"></i> </button>
    </form>
            
    <br />
            
    <div>
        <i class="fa fa-telephone"></i> +2348147964486<br /><br />
        
        <i class="fa fa-mail"></i> support@<?=$site_url_short?><br />
    </div>

    <br />

    <div>
    <b style="border-bottom:2px solid #fff">WE ARE ACTIVE ON SOCIAL MEDIA:</b> <br /><br />
        <a href='https://instagram.com/brae_sokolski?igshid=YmMyMTA2M2Y='><i class="fa fa-instagram"></i> instagram</a> <br /><br />   
        <a href='https://m.me/Brae.Sokolski'> <i class="fa fa-facebook"></i> facebook</a> <br /><br />  
        <a href='https://t.me/Futurefinancecom'> <i class="fa fa-telegram"></i> telegram</a> <br /><br />
        <a href='https://chat.whatsapp.com/BEclTWAc79S18paVW2Jt7B'> <i class="fa fa-whatsapp"></i> whatsApp</a> <br /><br /> 
    </div>
</div>


<?php
Index_Segments::footer();
?>


<!--


-->