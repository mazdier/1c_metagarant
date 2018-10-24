<?
use Bitrix\Main\Localization\Loc;
if (!check_bitrix_sessid())
    return;
if ($ex=$APPLICATION->GetException()) {
    echo CAdminMessage::ShowMesage(array(
        "TYPE" => "ERROR",
        "MESSAGE" => loc::getMessage("MOD_INS_ERR"),
        "DETAILS" => $ex->GetString(),
        "HTML" => true,

    ));
}
else {
    echo CAdminMessage::ShowNote(loc::GetMessage("MDO_INS_OK"));
}

?>
<form  action="<? echo $APPLICATION->GetCurPage();?>">
    <input type="hidden" name="lang" value="<? echo LANGUAGE_ID?>">
    <input type="submit" name="" value="<? echo Loc::GetMessage("MOD_BACK")?>">
</form>
