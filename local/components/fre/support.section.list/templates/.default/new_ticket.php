<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>

<?$APPLICATION->IncludeComponent(
    "fre:support.ticket.create",
    "",
    Array(
        "IBLOCK_ID" => $arParams['IBLOCK_ID'],
        "SECTION_ID" => $arResult["SECTION"]["ID"],
        "SECTION_NAME" => $arResult["SECTION"]["NAME"]
    ),
    $component
);?>

