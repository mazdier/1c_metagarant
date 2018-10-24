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
?>
<?=ShowError($arResult["ERRORS"]);?>
<form name="new-ticket" method="post" action="<?= POST_FORM_ACTION_URI; ?>">
    <input type="hidden" name="TITLE" value="<?= $arResult["TICKET_NAME"]; ?>"/>
    <input type="hidden" name="MESSAGE" value="Empty message"/>
    <input type="hidden" name="OPEN" value="Y"/>
    <?= bitrix_sessid_post("key"); ?>
    <? if(!count($arResult["QUESTIONS"])): ?>
        <p>Вопросов нет...</p>
    <? endif; ?>
    <table width="100%">
    <? foreach($arResult["QUESTIONS"] as $arQuestion): ?>
        <?= $arQuestion["SUPPORT_PROPERTY_INFO"]["HTML"]; ?>
    <? endforeach; ?>
    </table>
    <input type="submit" name="add-ticket" value="Отправить"/>
</form>
