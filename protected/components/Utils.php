<?php


class Utils
{

    const STATUS_GOOD = 'good';
    const STATUS_BAD = 'bad';
    const ALERT_SUCCESS ="success";
    const ALERT_INFO = "info";
    const ALERT_ERROR = "error";
    const APP_ASSETS_VERSION = '0.0.1';

    public static function setAlert($type, $message)
    {
        Yii::app()->user->setFlash($type, $message);
    }

    public static function formatMoney($value){
        return money_format('%.2n', $value);
    }

    public static function getMonth(){
        return date('M');
    }

    public static function getNumMonth(){
        return date('m');
    }

    public static function getYear(){
        return date('Y');
    }

    public static function getTransactionIcon($type){
        if($type == 'expense'){
            return '<i style="color:darkred;" class="fa fa-minus"></i>';
        }else{
            return '<i style="color:green;" class="fa fa-plus"></i>';
        }
    }

    public static function getReconcileIcon($type){
        if($type == 'minus'){
            return '<i style="color:darkred;" class="fa fa-minus"></i>';
        }else{
            return '<i style="color:green;" class="fa fa-plus"></i>';
        }
    }

    public static function getUserAccounts(){

    }

    public static function queryUserAccounts($col='account_id'){
        $query = "$col IN(";
        $accounts = Accounts::model()->getUserAccounts();
        foreach ($accounts as $account){
            $query .= $account->id . ",";
        }
        $query = rtrim($query,",");
        return $query . ")";
    }

    public static function pageSettings($settings=array()){
        $formattedSettings = json_encode($settings);
        $o = "<input type='hidden' id='page-settings' data-settings='$formattedSettings' />";
        return $o;
    }

    public static function logger($message, $tag='console'){
        Yii::log($message, CLogger::LEVEL_ERROR, $tag);
    }

    public static function getCurrentUserId(){
        return '2';
    }

    public static function getUserName(){
        return Users::model()->findByPk(self::getCurrentUserId())->username;
    }

    public static function jsonResponse($status, $message, $data=""){
        echo json_encode(['response'=>[
            'message' => $message,
            'status' => $status,
            'data' => $data
        ]]);
    }

    public static function getCurrentUrl(){
        return Yii::app()->request->url;
    }

    public static function renderCssAssets(){
        Yii::app()->controller->renderPartial('//layouts/css_assets');
    }

    public static function renderJsAssets(){
        $file= "/public/assets/js/built.js";
        if(YII_DEBUG){
            Yii::app()->controller->renderPartial('//layouts/js_assets');
        }else{
            Yii::app()->clientScript->registerScriptFile($file);
        }
    }

    public static function registerPageJs( $file )
    {
        if(YII_DEBUG){
            $file = "/js/pages/{$file}.js?v=" .self::getAssetsVersion();
        }else{
            $file = "/public/assets/js/{$file}.js?v=". self::getAssetsVersion();
        }
        Yii::app()->clientScript->registerScriptFile($file,
            CClientScript::POS_END);
    }

    public static function registerJs($file){
        if(YII_DEBUG){
            $file = "/js/{$file}.js?v=" .self::getAssetsVersion();
        }else{
            $file = "/public/assets/js/{$file}.js?v=". self::getAssetsVersion();
        }
        Yii::app()->clientScript->registerScriptFile($file,
            CClientScript::POS_END);
    }

    private static function getAssetsVersion()
    {
        return self::APP_ASSETS_VERSION;
    }
}
