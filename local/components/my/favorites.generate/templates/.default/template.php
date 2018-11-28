<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
if($arResult["ELEMENTS"]):
?>
	<script>
		globalSessionProduct=<?=CUtil::PhpToJSObject($arResult["ITEMS"]);?>;
		$(function(){
			<?foreach($arResult["ITEMS"] as $arId):?>
				$(".product-container .right-padding-ip a[data-id='<?=$arId?>']").addClass("active").attr("data-action",0);
			<?endforeach;?>
			var favBlock$=$(".insert-html").css("display","block");
			if(favBlock$.length>0)
			{
				$("#favorites_link").html(favBlock$);
			}
		});
	</script>
	<div class="insert-html" style="display: none;"><b><?=getMessage("FAVORITES_TITLE")?></b> <span><?=$arResult["ELEMENTS"]?></span></div>
<?
else:
	?>
	<script>
		$(function(){
			var favBlock$=$(".insert-html").css("display","block");
			if(favBlock$.length>0)
			{
				$("#favorites_link").html(favBlock$);
			}
		});
	</script>
	<div class="insert-html" style="display: none;"><b><?=getMessage("FAVORITES_TITLE")?></b> <span>0</span></div>
	<?
endif;
?>
