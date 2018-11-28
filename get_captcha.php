<script type="text/javascript">
        $(function(){
            $("#refresh_captcha").on("click",function(){
                var file = '<?=$this->GetFolder();?>/get_captcha.php';
                $.get(file,{},function(data){
                    $('#captcha_sid').attr('value',data);
                    $('#captcha_container').html('<img src="/bitrix/tools/captcha.php?captcha_sid='+data+'" width="272" height="60" alt="CAPTCHA">');
                });
            });
        });
    </script>

				<input type="hidden" id="captcha_sid" name="captcha_sid" value="<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" />
                <div id="captcha_container">
				    <img src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" width="272" height="60" alt="CAPTCHA" />
			    </div>


		    <span><?=GetMessage("FORM_CAPTCHA_FIELD_TITLE")?></span>
			<input type="text" name="captcha_word" maxlength="50" class="form-control pull-left" value="" />
            <a href="javascript:void(0);" id="refresh_captcha" class="btn"><span class="glyphicon glyphicon-refresh"></span></a>
<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include.php");
$code=CMain::CaptchaGetCode();
echo $code;