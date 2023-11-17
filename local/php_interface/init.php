<?php
if(file_exists($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/autoload.php")){
    require_once($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/autoload.php");
}
    AddEventHandler("main", "OnAfterUserRegister", "OnAfterUserRegisterHandler");

    function OnAfterUserRegisterHandler($arFields): void
    {
        $userId = $arFields["USER_ID"];

        $pointsValue = 1000;

        $user = new CUser;
        $user->Update($userId, array("UF_POINTS" => $pointsValue));
    }