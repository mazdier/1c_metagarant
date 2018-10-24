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
foreach($arResult["SECTIONS"] as &$arSection)
{
	$arSection["SECTION_PAGE_URL"] = $APPLICATION->GetCurPageParam("SECTION_ID=".$arSection["ID"], array("SECTION_ID"));
	if (!empty($arSection["UF_HEAD"]) && $arUser = CUser::GetByID($arSection["UF_HEAD"])->GetNext())
	{
		$arSection["DESC"] .= " (Ответственный - <a href='/company/personal/user/" . $arUser["ID"] . "/'>".CUser::FormatName(CSite::GetNameFormat(), $arUser)."</a>)";
	}
}
unset($arSection);
?>
<? if (is_array($arResult["SECTION"]) && count($arResult["SECTION"])): ?>
	<? if (!empty($arResult["SECTION"]["UF_HEAD"]) && $arUser = CUser::GetByID($arResult["SECTION"]["UF_HEAD"])->GetNext()): ?>
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
				<div class="department-manager-tel">Вн.тел.: <a href="tel:<?= $arUser["UF_PHONE_INNER"]; ?>"><?= $arUser["UF_PHONE_INNER"]; ?></a></div>			</span>
			<div class="profile-menu-background"></div>
		</span>
		</div>
	<? else: ?>
		<b>Ответственный отсутствует</b>
	<? endif; ?>
<? endif; ?>

<div class="catalog-section-list">
	<?
	$TOP_DEPTH = $arResult["SECTION"]["DEPTH_LEVEL"];
	$CURRENT_DEPTH = $TOP_DEPTH;

	foreach($arResult["SECTIONS"] as $arSection)
	{
		$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_EDIT"));
		$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM')));
		if($CURRENT_DEPTH < $arSection["DEPTH_LEVEL"])
		{
			echo "\n",str_repeat("\t", $arSection["DEPTH_LEVEL"]-$TOP_DEPTH),"<ul>";
		}
		elseif($CURRENT_DEPTH == $arSection["DEPTH_LEVEL"])
		{
			echo "</li>";
		}
		else
		{
			while($CURRENT_DEPTH > $arSection["DEPTH_LEVEL"])
			{
				echo "</li>";
				echo "\n",str_repeat("\t", $CURRENT_DEPTH-$TOP_DEPTH),"</ul>","\n",str_repeat("\t", $CURRENT_DEPTH-$TOP_DEPTH-1);
				$CURRENT_DEPTH--;
			}
			echo "\n",str_repeat("\t", $CURRENT_DEPTH-$TOP_DEPTH),"</li>";
		}

		$count = $arParams["COUNT_ELEMENTS"] && $arSection["ELEMENT_CNT"] ? "&nbsp;(".$arSection["ELEMENT_CNT"].")" : "";

		if ($_REQUEST['SECTION_ID']==$arSection['ID'])
		{
			$link = '<b>'.$arSection["NAME"].$count.'</b> '.$arSection["DESC"];
			$strTitle = $arSection["NAME"];
		}
		else
		{
			$link = '<a href="'.$arSection["SECTION_PAGE_URL"].'">'.$arSection["NAME"].$count.'</a> '.$arSection["DESC"];
		}

		echo "\n",str_repeat("\t", $arSection["DEPTH_LEVEL"]-$TOP_DEPTH);
		?><li id="<?=$this->GetEditAreaId($arSection['ID']);?>"><?=$link?><?

		$CURRENT_DEPTH = $arSection["DEPTH_LEVEL"];
	}

	while($CURRENT_DEPTH > $TOP_DEPTH)
	{
		echo "</li>";
		echo "\n",str_repeat("\t", $CURRENT_DEPTH-$TOP_DEPTH),"</ul>","\n",str_repeat("\t", $CURRENT_DEPTH-$TOP_DEPTH-1);
		$CURRENT_DEPTH--;
	}
	?>
</div>
<?=($strTitle?'<br/><h2>'.$strTitle.'</h2>':'')?>
