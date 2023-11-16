<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("1С-Битрикс: Управление сайтом");
?><?$APPLICATION->IncludeComponent(
	"bitrix:system.auth.form",
	"",
	Array(
		"FORGOT_PASSWORD_URL" => "",
		"PROFILE_URL" => "",
		"REGISTER_URL" => "",
		"SHOW_ERRORS" => "N"
	)
);?>
<?$APPLICATION->IncludeComponent(
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
);?>
<?php if($USER->IsAuthorized()){
	$userId = $USER->GetID();

	$userData = CUser::GetByID($userId)->Fetch();
	$pointsValue = $userData['UF_POINTS'];

	echo "Очки: " . $pointsValue;
}?>
	<form method="post" action="/changePoints.php">
		<input type="number" name="down-points" id="down-points">
		<button type="submit" name="decrease" value="2">Списать</button>
		<br>
		<input type="number" name="up-points" id="up-points">
		<button type="submit" name="increase" value="1">Начислить</button>
	</form>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>