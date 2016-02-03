<?php

/**
 * Created by PhpStorm.
 * User: UI
 * Date: 2016/1/24
 * Time: 21:22
 */
class Payrecord extends Model{
    function find($guest_id){
        return $this->db->query('select
            p.money,
            date_format(p.deadline,"%Y-%m-%d") deadline
            from
            `accounting`a,
            `pay_record`p
            where
            a.accounting_id = p.accounting_id and
            a.guest_id = ?
            order by p.deadline desc',$guest_id);
    }
}