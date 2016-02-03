<?php

/**
 * Created by PhpStorm.
 * User: UI
 * Date: 2016/1/24
 * Time: 21:16
 */
class PayrecordController extends CommonController{
    function get(){
        $guest_id = $this->session["guest"];
        if(!empty($guest_id)){
            $result = $this->load("payrecord")->find($guest_id);
            return json_encode(array(
                "error_code"    => 0,
                "data"          => $result
            ));
        }else{
            return json_encode(array(
                "error_code" => 1
            ));
        }
    }
}