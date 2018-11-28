<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
CAjax::Init();?>

<?=CAjax::GetForm('method="POST" class="form-horizontal"', $arResult["AJAX_ID"], '1')?>
<input type="hidden" name="ajax" value="y">

<div id="<?=$arResult["AJAX_ID"]?>">
<?if ($_POST["FB_SUBMIT_".$arResult["AJAX_ID"]] && $_POST["ajax"] == "y"):
	$APPLICATION->RestartBuffer();
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
endif;?>

<?if (count($arResult["ERRORS"]) > 0):?>
	<div class="alert alert-error">
	  <button type="button" class="close" data-dismiss="alert">&times;</button>
	  <h4><?=GetMessage("SIMAI_FORM_ERROR")?></h4> 
		<p>- <?=implode("<br>- ",$arResult["ERRORS"])?></p>
	</div>
<?elseif ($arResult["OK"]):?>
	<div class="alert alert-success">
	  <button type="button" class="close" data-dismiss="alert">&times;</button>
	  <?=$arParams["OK_MSG"]?>
	</div>
<?endif;?>

	<?foreach($arResult["FIELDS"] as $field_code => $field_name):?>
		<div class="control-group">
			<label class="control-label"><?=$field_name?><?if (array_key_exists($field_code,$arResult["FIELDS_REQ"])):?><span class="text-error"> *</span><?endif;?></label>
			<div class="controls">
				<?if (array_key_exists($field_code,$arResult["FIELDS_TXT"])):?>
					<textarea name="PROP[<?=$field_code?>]" rows="5" class="input-xlarge"><?=($arResult["OK"] ? "" : htmlspecialchars(strip_tags($_POST["PROP"][$field_code]),ENT_QUOTES))?></textarea>
				<?else:?>
					<input name="PROP[<?=$field_code?>]" type="text" value="<?=($arResult["OK"] ? "" : htmlspecialchars(strip_tags($_POST["PROP"][$field_code]),ENT_QUOTES))?>" class="input-large">
				<?endif?>
			</div>	
		</div>
	<?endforeach;?>
	<?if (isset($arResult["CAP_CODE"])):?>
		<div class="control-group">
			<div class="controls">
				<img src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialchars($arResult["CAP_CODE"])?>" width="180" height="40">
				<input type="hidden" name="CAPTCHA_SID" value="<?=htmlspecialchars($arResult["CAP_CODE"])?>">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label"><?=GetMessage("SIMAI_FORM_CAPTCHA_INPUT")?><span style="color:red">*</span></label>
			<div class="controls">
					<input type="text" name="CAPTCHA_WORD" maxlength="50" value="">
			</div>
		</div>
	<?endif;?>
	
	<div class="control-group">	
		<div class="controls">
			<input type="submit" name="FB_SUBMIT_<?=$arResult["AJAX_ID"]?>" class="btn" value=" <?=GetMessage("SIMAI_FORM_SEND")?>">
		</div>
	</div>

<?if ($_POST["FB_SUBMIT_".$arResult["AJAX_ID"]] && $_POST["ajax"] == "y"):
	exit();
endif;?>	

</div>
</form>
<pre><span class="text-error">*</span> - <?=GetMessage("SIMAI_FORM_MANDATORY")?></pre>