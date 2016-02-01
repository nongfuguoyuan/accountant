<?php
	//by zjh
	class Progress extends Model{


		function findName($business_id){
			return $this->db->queryOne("select 
				pr.name
				from
				`progress` p
				left join 
				`process` pr
				on pr.process_id = p.process_id
				where p.business_id = ?
				order by p.progress_id desc",$business_id);
		}
		function add($params){
		 $re = $this->db->exec("insert into `progress` set 
				business_id=:business_id,
				process_id=:process_id,
				note=:note,
				date_end=:date_end,
				create_time=now()
			",$params);
		 if($re==1){
		 	include 'msg.php';
		 	$msg=new Msg($params['business_id'], 'business');
		 	$msg->pushMessage('工商注册', '尊敬的客户你的工商注册进度更新了');
		 }
		return $re;
		
		}
		function update($params){
			return $this->db->exec("update `progress` set 
				process_id=:process_id,
				note=:note,
				date_end=:date_end
				where
				progress_id=:progress_id
			",$params);
		}

		function delete($progress_id){
			return $this->db->exec('delete from `progress` where progress_id=?',$progress_id);
		}
		function find($business_id){
			return $this->db->query('select
				pr.progress_id,
				pr.business_id,
				p.process_id,
				pr.note,
				p.name,
				DATE_FORMAT(pr.date_end ,"%Y-%m-%d") date_end,
				pr.create_time
				from
				`progress` pr,
				`process`  p,
				`business` b
				where
				pr.business_id = b.business_id and
				pr.process_id = p.process_id and
				pr.business_id=?',$business_id);
		}
	}
?>