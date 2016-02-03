<?php
	class Tax extends Model{
		function find($arr){
			return $this->db->query("select
				c.year,
				c.month,
				c.fee,
				c.tax_type_id,
				t.name,
				t.parent_id
				from 
				`tax_type` t,
				`tax_collect` c
				where 
				t.tax_type_id = c.tax_type_id and
				c.year = :year and
				c.month = :month and
				c.guest_id = :guest_id",$arr);
		}
		function findCount($arr){
			return $this->db->queryOne("
				select
				nation,
				local
				from 
				`tax_count`
				where 
				year = :year and
				month = :month and
				guest_id = :guest_id
			",$arr);
		}
	}
?>