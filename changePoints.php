<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    global $USER;
    global $DB;
    $userId = $USER->GetID();

    $userData = CUser::GetByID($userId)->Fetch();
    $currentPoints = $userData["UF_POINTS"];

    if($_POST["up-points"]){
        $changeValue = intval($_POST["up-points"]);
    } elseif($_POST["down-points"]){
        $changeValue = intval($_POST["down-points"]);
    }
    if (isset($_POST["increase"])) {
        $newPoints = $currentPoints + $changeValue;
        $changeType = 'increase';
    } elseif (isset($_POST["decrease"])) {
        $newPoints = $currentPoints - $changeValue;
        $newPoints = max(0, $newPoints);
        $changeType = 'decrease';
    }

    $user = new CUser;
    $user->Update($userId, array("UF_POINTS" => $newPoints));

    $DB->Query("
        INSERT INTO transaction_history (USER_ID, CHANGE_VALUE, CHANGE_TYPE, CHANGE_DATE)
        VALUES ('$userId','$changeValue', '$changeType', NOW())
");
}

LocalRedirect("/index.php");