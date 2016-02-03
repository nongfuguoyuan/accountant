<?php
/**
 * Created by PhpStorm.
 * User: UI
 * Date: 2016/1/25
 * Time: 15:53
 */
    class AccountingController extends CommonController{
        function get(){
            $guest_id = $this->session['guest'];
            if(!empty($guest_id)){
                $result = $this->load("accounting")->findAccounting($guest_id);
                if(!empty($result)){
                    //查询期限
                    $deadline = $this->load("accounting")->findDeadline($result["accounting_id"]);
                    if(!empty($deadline)){
                        $date = $deadline['deadline'];
                        $date1 = new DateTime($date);
                        $date2 = new DateTIme(date("Y-m-d"));
                        $diff = $date1->diff($date2)->format('%R%a');
                        $count = $this->load("accounting")->findCount($result["accounting_id"])['count'];
                        return json_encode(array(
                            "error_code"    => 0,
                            "data"          => array(
                                "name"      => $result['name'],
                                "phone"     => $result["phone"],
                                "money"     => $deadline['money'],
                                "deadline"  => $deadline['deadline'],
                                "diff"      => $diff,
                                "count"     => $count
                        )
                        ));
                    }else{
                        return json_encode(array(
                            "error_code"    => 0,
                            "data"          => array(
                                "phone"     => $result["phone"],
                                "name"      => $result['name']
                            )
                        ));
                    }
                }else{
                    return json_encode(array(
                        "error_code"    => 0,
                        "data"          => array()
                    ));
                }
            }else{
                return json_encode(array(
                    "error_code"    => "1"
                ));
            }
        }
    }