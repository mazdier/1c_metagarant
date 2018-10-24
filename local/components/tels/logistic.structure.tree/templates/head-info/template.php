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
$this->setFrameMode(true);?>
	<? if (is_array($arResult["CUR_ELEMENT"]) && count($arResult["CUR_ELEMENT"])): ?>
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