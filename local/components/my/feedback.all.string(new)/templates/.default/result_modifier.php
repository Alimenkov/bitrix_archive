<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Application;

$objRequest = Application::getInstance()->getContext()->getRequest();

$arResult['STATUS'] = $objRequest->getPost('inf');
