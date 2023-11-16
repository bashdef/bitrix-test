<?

use Bitrix\Main\UI\PageNavigation;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("1С-Битрикс: Управление сайтом");
global $USER;
global $DB;
?><? $APPLICATION->IncludeComponent(
        "bitrix:system.auth.form",
        "",
        Array(
            "FORGOT_PASSWORD_URL" => "",
            "PROFILE_URL" => "",
            "REGISTER_URL" => "",
            "SHOW_ERRORS" => "N"
        )
    );
?>
<?if(!$USER->IsAuthorized()){
    $APPLICATION->IncludeComponent(
        "bitrix:main.register",
        "",
        Array(
            "AUTH" => "Y",
            "REQUIRED_FIELDS" => array("EMAIL"),
            "SET_TITLE" => "Y",
            "SHOW_FIELDS" => array("EMAIL","TITLE","NAME"),
            "SUCCESS_PAGE" => "/",
            "USE_BACKURL" => "Y"
        )
    );
}?>
<?php if($USER->IsAuthorized()){
	$userId = $USER->GetID();

	$userData = CUser::GetByID($userId)->Fetch();
	$pointsValue = $userData['UF_POINTS'];

	echo "Баллы: " . $pointsValue;
}?>
<?php if($USER->IsAuthorized()){ ?>
	<form method="post" action="/changePoints.php">
		<input type="number" name="down-points" id="down-points">
		<button type="submit" name="decrease" value="2">Списать</button>
		<br>
		<input type="number" name="up-points" id="up-points">
		<button type="submit" name="increase" value="1">Начислить</button>
	</form>
<?php } ?>
<?php
    $userId = $USER->GetID();

    $pageSize = 10;
    $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;

    $navParams = array(
        "nPageSize" => $pageSize,
        "iNavPageCount" => 0,
        "iShowAll" => 0,
        "bDescPageNumbering" => false,
        "bShowAll" => false,
    );

    $nav = new PageNavigation("nav");
    $nav->allowAllRecords(true)->setPageSize($pageSize)->initFromUri();

    $res = $DB->Query("
        SELECT COUNT(ID) as COUNT FROM transaction_history WHERE USER_ID = '$userId'
    ");
    $countRow = $res->Fetch();
    $nav->setRecordCount($countRow['COUNT']);

    $res = $DB->Query("
        SELECT * FROM transaction_history
        WHERE USER_ID = '$userId'
        ORDER BY CHANGE_DATE DESC
        LIMIT " . $nav->getOffset() . ", " . $nav->getLimit()
    );

    while ($row = $res->Fetch()) {
        $changeType = ($row['CHANGE_TYPE'] == 'increase') ? 'начислено' : 'списано';
        echo "Дата: {$row['CHANGE_DATE']}, {$changeType} {$row['CHANGE_VALUE']} баллов<br>";
    }
    if($USER->IsAuthorized()){
        $APPLICATION->IncludeComponent(
            "bitrix:main.pagenavigation",
            "",
            array(
                "NAV_OBJECT" => $nav,
                "SEF_MODE" => "N",
                "SHOW_ALWAYS" => true
            ),
            false
        );
    }
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>