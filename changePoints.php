<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    global $USER;
    $userId = $USER->GetID();

    $userData = CUser::GetByID($userId)->Fetch();
    $currentPoints = $userData["UF_POINTS"];

    $changeValueUp = intval($_POST["up-points"]);
    $changeValueDown = intval($_POST["down-points"]);
    if (isset($_POST["increase"])) {
        $newPoints = $currentPoints + $changeValueUp;
    } elseif (isset($_POST["decrease"])) {
        $newPoints = $currentPoints - $changeValueDown;
        $newPoints = max(0, $newPoints);
    }

    $user = new CUser;
    $user->Update($userId, array("UF_POINTS" => $newPoints));
}

LocalRedirect("/index.php");