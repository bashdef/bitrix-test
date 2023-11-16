<?php

namespace App;

use Bitrix\Main\EventManager;
use CUser;

class PointHandler
{
    public static function init(){
        $eventManager = EventManager::getInstance();

        $eventManager->addEventHandler('main', 'OnAfterUserRegister', [self::class, 'defaultPoints']);
    }

    public static function defaultPoints($arFields){
        $userId = $arFields['USER_ID'];
        $pointsValue = 1000;

        $user = new CUser();
        $user->Update($userId, array('UF_POINTS' => $pointsValue));
    }
}