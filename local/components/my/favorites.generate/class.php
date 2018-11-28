<?php
class CAddFavorites extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $arPrefix=COption::GetOptionString("main", "cookie_name");
        $result["IBLOCK_ID"]=intval($arParams["IBLOCK_ID"]);
        $result["COOKIE_REAL_NAME"]=(trim($arParams["COOKIE_NAME"])?$arPrefix."_".trim($arParams["COOKIE_NAME"]):$arPrefix."_favorites");
        $result["COOKIE_NAME"]=(trim($arParams["COOKIE_NAME"])?trim($arParams["COOKIE_NAME"]):"favorites");
        $result["ACTION_VARIABLE"]=(trim($arParams["ACTION_VARIABLE"])?trim($arParams["ACTION_VARIABLE"]):"action");
        $result["ACTION_ADD"]=(trim($arParams["ACTION_ADD"])?trim($arParams["ACTION_ADD"]):"ADD");
        $result["ACTION_DEL"]=(trim($arParams["ACTION_DEL"])?trim($arParams["ACTION_DEL"]):"DEL");
        if(intVal($_REQUEST["id"]))$result["ID"]=intVal($_REQUEST["id"]);
        if(strlen($arParams["FILTER_NAME"])<=0 || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"]))
        {
            $result["arrFilter"] = array();

        }
        else
        {
            $result["arrFilter"] = $GLOBALS[$arParams["FILTER_NAME"]];
            if(!is_array($result["arrFilter"]))
                $result["arrFilter"] = array();
        }
        return $result;
    }
    public function checkArray($array, $filter)
    {
        if(CModule::IncludeModule("iblock") && !empty($array) && is_array($array))
        {
            $array=array_unique($array);
            $arIds=array();
            $arSelect = Array("ID");
            $arFilter = Array("IBLOCK_ID"=>$this->arParams["IBLOCK_ID"], "ID"=> $array);
            if(!empty($filter))
            {
                foreach($filter as $cell=>$arItem)
                {
                    if(!$arItem)$filter[$cell]=false;
                }
            }
            $res = CIBlockElement::GetList(Array(), array_merge($arFilter, $filter), false, false, $arSelect);
            while($ob = $res->GetNext())
            {
                $arIds[]=$ob["ID"];
            }
            if(!empty($arIds) && count($arIds) != count($array))
            {
                foreach($array as $key=>$id)
                {
                    if(!in_array($id, $arIds))unset($array[$key]);
                }
            }
        }
        if(empty($array))$array=array();
        return $array;
    }
    public function executeComponent()
    {
        if(!$this->arParams["IBLOCK_ID"] || !$this->arParams["COOKIE_NAME"])
        {
            $this->arResult["ELEMENTS"]=0;
            $this->includeComponentTemplate();
        }
        else
        {
            $arCookieArray=unserialize($_COOKIE[$this->arParams["COOKIE_REAL_NAME"]]);
            if(empty($arCookieArray))
            {
                $arCookieArray=array();
            }
            if(!empty($_REQUEST[$this->arParams["ACTION_VARIABLE"]]) && $this->arParams["ID"])
            {
                if($_REQUEST[$this->arParams["ACTION_VARIABLE"]]==$this->arParams["ACTION_ADD"])
                {
                    if(!in_array($this->arParams["ID"], $arCookieArray))
                    {
                        $arCookieArray[]=$this->arParams["ID"];
                    }
                }
                elseif($_REQUEST[$this->arParams["ACTION_VARIABLE"]]==$this->arParams["ACTION_DEL"])
                {
                    $key=array_search($this->arParams["ID"], $arCookieArray);
                    if($key !== false)
                    {
                        unset($arCookieArray[$key]);
                    }
                }
                if(!empty($arCookieArray))
                {
                    $arCookieArray=$this->checkArray($arCookieArray, $this->arParams["arrFilter"]);
                    $this->arResult["ELEMENTS"]=count($arCookieArray);
                    $this->arResult["ITEMS"]=$arCookieArray;
                    $arString=serialize($arCookieArray);
                    $GLOBALS["APPLICATION"]->set_cookie($this->arParams["COOKIE_NAME"], $arString, time()+60*60*24*30*12*1, SITE_DIR);
                    $_COOKIE[$this->arParams["COOKIE_REAL_NAME"]]=$arString;
                }
                else
                {
                    $GLOBALS["APPLICATION"]->set_cookie($this->arParams["COOKIE_NAME"], "", time()+60*60*24*30*12*1, SITE_DIR);
                    $_COOKIE[$this->arParams["COOKIE_REAL_NAME"]]="";
                }
            }
            else
            {
                $arCookieArray=unserialize($_COOKIE[$this->arParams["COOKIE_REAL_NAME"]]);
                if(!empty($arCookieArray))
                {
                    $arCookieArray=$this->checkArray($arCookieArray, $this->arParams["arrFilter"]);
                    $this->arResult["ELEMENTS"]=count($arCookieArray);
                    $this->arResult["ITEMS"]=$arCookieArray;
                }
            }
            $this->includeComponentTemplate();
        }
    }
}