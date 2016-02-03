<?php 
	class Msg extends Model{
		//统计有几条消息
		function findCount($guest_id,$readed){
			return $this->db->queryOne("select count(*) count from `msg` where readed=? and guest_id=?",array($readed,$guest_id));
		}

		function find($guest_id,$readed){
			return $this->db->query('
			select 
			msg_id,
			type,
			guest_id,
			title,
			content,
			readed,
			date_format(create_date,"%Y-%m-%d") create_date
			from `msg`
			where
			showed = 1 and
			guest_id=? and
			readed=?',
			array($guest_id,$readed));
		}

		function update($guest_id,$readed){
			return $this->db->exec("update `msg` set readed=? where guest_id=?",array($readed,$guest_id));
		}

		function updateShow($guest_id,$arr){
			$length = count($arr);
			if($length <= 0){
				return 0;
			}else{
				$holder_arr = [];
				for($i = 0;$i < $length;$i++){
					array_push($holder_arr,"?");
				}
				$holder_str = implode(",",$holder_arr);
				array_unshift($arr,$guest_id);
				return $this->db->exec("update `msg` set showed = 0 where guest_id=? and msg_id in (".$holder_str.")",$arr);
			}
		}
	}
?>