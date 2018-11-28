<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (count($arResult["ERRORS"]) > 0):?>
<p style="color:red"><?=implode("<br>",$arResult["ERRORS"])?></p>
<?elseif ($_REQUEST["inf"] == "ok"):?>
<p style="color:green"><?=$arParams["OK_MSG"]?></p>
<?endif;?>
<br /> 
<form method="post" action="">
<table class="data-table">
<?foreach($arResult["FIELDS"] as $field_code=>$field_name):
	if (array_key_exists($field_code,$arResult["FIELDS_DIV"])):?>
<tr><th colspan="2"><?=$field_name?></th></tr>	
	<?else:?>
<tr>
<td style="text-align:right"><?=$field_name?><?if (array_key_exists($field_code,$arResult["FIELDS_REQ"])):?><span style="color:red">*</span><?endif;?>:</td>
<td>
		<?if (array_key_exists($field_code,$arResult["FIELDS_TXT"])):?>
<textarea name="PROP[<?=$field_code?>]" style="width:350px;height:150px"><?=htmlspecialchars(strip_tags($_POST["PROP"][$field_code]),ENT_QUOTES)?></textarea>
		<?else:?>
<input name="PROP[<?=$field_code?>]" type="text" style="width:350px" value="<?=htmlspecialchars(strip_tags($_POST["PROP"][$field_code]),ENT_QUOTES)?>">
		<?endif?>
</td>
</tr>  
	<?endif;
endforeach;?>
<?if (isset($arResult["CAP_CODE"])):?>
<tr><th rowspan="2" style="text-align:right;vertical-align:top"><?=getMessage("FORM_ANTI_SPAM")?>:
<br><?=getMessage("FORM_ENTER_CODE");?><span style="color:red">*</span>:</th>
<td><input type="text" name="CAPTCHA_WORD" maxlength="50" value="" style="width:250px"></td></tr>
<tr><td>
<img src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialchars($arResult["CAP_CODE"])?>" width="180" height="40">
<input type="hidden" name="CAPTCHA_SID" value="<?=htmlspecialchars($arResult["CAP_CODE"])?>">
</td>
</tr>
<?endif;?>
<tr>
<td colspan='2'><input type='submit' name='FB_SUBMIT' value=' Отправить '></td>
</tr>
<tr> 
</table>
</form>
<br><span style='color:red'>*</span> - <?=getMessage("FORM_REQUIRED");?>