<?php
    AddEventHandler("main", "OnAfterUserRegister", "OnAfterUserRegisterHandler");

    function OnAfterUserRegisterHandler($arFields): void
    {
        $userId = $arFields["USER_ID"];

        $pointsValue = 1000;

        $user = new CUser;
        $user->Update($userId, array("UF_POINTS" => $pointsValue));
    }