<?php


/**
 * Created by PhpStorm.
 * User: UI
 * Date: 2016/1/20
 * Time: 15:55
 */
class TestController extends CommonController{
    function get(){
        $small = $this->post['small'];
        if(empty($small)){
            return null;
        }else{
            return $small;
        }

    }
}