<?php
define("STOP_STATISTICS", true);
define("BX_SECURITY_SHOW_MESSAGE", true);
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule("iblock");

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers", "origin, x-requested-with, content-type");
header("Access-Control-Allow-Methods", "PUT, GET, POST, DELETE, OPTIONS");

$result = array("success" => false);

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["action"])) {
    switch ($_GET["action"]) {
        case "getBalance":
            if (isset($_GET["userId"])) {
                $userId = intval($_GET["userId"]);
                $result["points"] = getBalance($userId);
                $result["success"] = true;
            } else {
                $result["error"] = "User ID is required.";
            }
            break;
        case "getHistory":
            if (isset($_GET["userId"])) {
                $userId = intval($_GET["userId"]);
                $result["data"] = getHistory($userId);
                if($result["data"] == []){
                    http_response_code(404);
                    die(json_encode(array("error" => "User not found")));
                } else {
                    $result["success"] = true;
                }
            } else {
                $result["error"] = "User ID is required.";
            }
    }
}

echo json_encode($result);

function getBalance($userId)
{
    $userData = CUser::GetByID($userId)->Fetch();
    if ($userData) {
        return isset($userData['UF_POINTS']) ? intval($userData['UF_POINTS']) : 0;
    } else {
        http_response_code(404);
        die(json_encode(array("error" => "User not found")));
    }
}

function getHistory($userId)
{
    global $DB;

    $history = array();

    $res = $DB->Query("
        SELECT * FROM transaction_history
        WHERE USER_ID = '$userId'
        "
    );

    while ($row = $res->Fetch()) {
        $changeType = ($row['CHANGE_TYPE'] == 'increase') ? 'начислено' : 'списано';
        $history[] = array(
            "USER_ID" => intval($row['USER_ID']),
            "CHANGE_DATE" => $row['CHANGE_DATE'],
            "CHANGE_TYPE" => $changeType,
            "CHANGE_VALUE" => intval($row['CHANGE_VALUE']),
        );
    }

    return $history;
}