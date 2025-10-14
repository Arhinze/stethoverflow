<div style="display:flex">
    <div style="background-color: #acc5f8;color:#fff;margin:9px 8px 12px 3px;padding:5px;width:190px;border-radius:4px;display:flex;">                   
        <?php 
            $code_array = [0,1,2,3,4,5,6,7,8,9];
            shuffle($code_array);
            $code = "";
            
            $arr = [0,1,2,3,4,5];
            shuffle($arr);
            
            foreach($arr as $a){
                $code .= $code_array[$a];
        ?> 
                <div class="code<?=$a?>"><?=$code_array[$a];?></div>&nbsp;    
        <?php } ?>    
    </div>   
    <!--Hidden Captcha Code:-->
    <input type="hidden" name="xsrf_code" value="<?=$code?>"/> 
    <!--Visible Input Tag ~ for user:-->
    <div style="margin-top:8px;width:50%"><input type = "number" placeholder = "The 6 Digit Code: example - 123456" name = "user_code" class="input" style="height:30px;width:81%" required/></div>
</div>