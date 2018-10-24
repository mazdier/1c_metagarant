<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

$strTitle = "";
//change section url
foreach($arResult["ITEMS"] as &$arItem)
{
	if (!empty($arItem["PROPERTY_HEAD_VALUE"]) && $arUser = CUser::GetByID($arItem["PROPERTY_HEAD_VALUE"])->GetNext())
	{
		$arItem["DESC"] .= " (Ответственный - <a href='/company/personal/user/" . $arUser["ID"] . "/'>".CUser::FormatName(CSite::GetNameFormat(), $arUser)."</a>)";
	}
}
unset($arItem);
?>

<? if($arParams["AJAX"] != "Y"): ?>
	<script type="text/javascript">
		$(document).ready(function(){
			$('#items-list').on('click', 'li a[data-id]', function(){
				event.preventDefault ? event.preventDefault() : (event.returnValue=false);

				var obParentLi = $(this).parent();
				if ($(obParentLi).find('ul').length > 0)
				{
					$(obParentLi).find('ul').toggle();
					return false;
				}
				var url = $(this).attr('href');
				$.ajax({
					url: url,
					data: {
						AJAX: 'Y',
						TEMPLATE: 'head-info'
					},
					method: 'post',
					success: function(data){
						//console.log(data);
						$('#head').html(data);
					}
				});
				$.ajax({
					url: url,
					method: 'post',
					data: {
						AJAX: 'Y',
						TEMPLATE: 'tree'
					},
					success: function(data){
						//console.log(data);
						$(obParentLi).append(data);
					}
				});
			});
		});
	</script>
<? endif; ?>

<? if($arParams["AJAX"] != "Y"): ?>
	<div id="head">
<? endif; ?>

<? if (is_array($arResult["CUR_ELEMENT"]) && count($arResult["CUR_ELEMENT"]) && $arParams["AJAX"] != "Y"): ?>
	<? if (!empty($arResult["CUR_ELEMENT"]["PROPERTY_HEAD_VALUE"]) && $arUser = CUser::GetByID($arResult["CUR_ELEMENT"]["PROPERTY_HEAD_VALUE"])->GetNext()): ?>
		<?
		$imgSrc = "";
		if ($arUser["PERSONAL_PHOTO"] > 0)
		{
			$file = CFile::ResizeImageGet($arUser['PERSONAL_PHOTO'], array('width'=>50, 'height'=>50), BX_RESIZE_IMAGE_EXACT, true);
			$imgSrc = $file['src'];
		}
		$fName = CUser::FormatName(CSite::GetNameFormat(), $arUser);
		?>
		<div class="department-manager">
			<div class="department-titles">Ответственный сотрудник</div>
		<span class="department-manager-info-block">
			<a class="department-manager-avatar" href="/company/personal/user/<?= $arUser["ID"]; ?>/">
				<? if(!empty($imgSrc)): ?><img src="<?= $imgSrc; ?>"><? endif; ?>
			</a>
			<span class="department-manager-name-block">
				<div class="department-manager-name">
					<a href="/company/personal/user/<?= $arUser["ID"]; ?>/" class="department-manager-name-link"><?= $fName; ?></a>
				</div>
			</span>
			<span class="department-manager-info">
				<div class="department-manager-tel">Вн.тел.: <a href="tel:<?= $arUser["UF_PHONE_INNER"]; ?>"><?= $arUser["UF_PHONE_INNER"]; ?></a></div></span>
			<div class="profile-menu-background"></div>
		</span>
		</div>
	<? else: ?>
		<b>Ответственный отсутствует</b>
	<? endif; ?>
<? endif; ?>

<? if($arParams["AJAX"] != "Y"): ?>
	</div>
<? endif; ?>

<? if($arParams["AJAX"] != "Y"): ?>
	<div id="items-list">
<? endif; ?>

	<ul>
		<? foreach($arResult["ITEMS"] as $arItem): ?>
			<li>
				<a data-id="<?= $arItem["ID"]; ?>" href="<?= $arItem['DETAIL_PAGE_URL'] ?>"><?= $arItem["NAME"]; ?></a>
				<?= $arItem["DESC"]; ?>

				<? if($arItem["CHILD_ELEMENTS"] && count($arItem["CHILD_ELEMENTS"])): ?>
					<ul>
						<? foreach($arItem["CHILD_ELEMENTS"] as $arChItem): ?>
							<li>
								<a data-id="<?= $arChItem["ID"]; ?>" href="<?= $arChItem['DETAIL_PAGE_URL'] ?>"><?= $arChItem["NAME"]; ?></a>
								<?= $arChItem["DESC"]; ?>
							</li>
						<? endforeach; ?>
					</ul>
				<? endif; ?>

			</li>
		<? endforeach; ?>
	</ul>

<? if($arParams["AJAX"] != "Y"): ?>
	</div>
<? endif; ?>
