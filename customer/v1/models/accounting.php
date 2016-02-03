<?php
/**
 * Created by PhpStorm.
 * User: UI
 * Date: 2016/1/25
 * Time: 15:56
 */
    class Accounting extends Model{
        function findAccounting($guest_id){
            return $this->db->queryOne("select
                e.name,
                e.phone,
                a.accounting_id
                from
                `accounting` a,
                `employee` e
                where
                a.employee_id = e.employee_id and
                a.status = 1 and
                a.guest_id = ?",$guest_id);
        }

        function findDeadline($accounting_id){
            return $this->db->queryOne("select
                p.money,
                DATE_FORMAT(p.deadline,\"%Y-%m-%d\") deadline
                from
                `pay_record` p
                where
                p.deadline = (
                select
                max(deadline)
                from
                `pay_record` r
                where
                r.pay_record_id = p.pay_record_id
                )
                and
                p.accounting_id = ?
                order by deadline desc limit 1",
                $accounting_id);
        }
        function findCount($accounting_id){
            return $this->db->queryOne("select count(*) from `pay_record` where accounting_id=?",$accounting_id);
        }
    }
