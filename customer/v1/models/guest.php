<?php
	class Guest extends Model{
		function login($phone,$password){
			return $this->db->queryOne("select * from `guest` where 
				phone = ? and
				pass = ? 
			",array($phone,$password));
		}
		function findPhone($phone){
			return $this->db->queryOne("select phone from `guest` where phone=? and pass is not null and pass !=''",$phone);
		}
		function updatePass($arr){
			return $this->db->exec("
				update `guest`
				set
				pass = :new_pass
				where
				pass = :old_pass and
				guest_id = :guest_id
			",$arr);
		}
		function updatePassByPhone($phone,$pass){
			return $this->db->exec("update `guest` set pass=? where phone=?",array($pass,$phone));
		}
		function findCompany($guest_id){
			return $this->db->queryOne("select company,name from `guest` where guest_id=?",$guest_id);
		}
	}	
?>