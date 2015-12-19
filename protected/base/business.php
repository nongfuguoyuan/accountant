<?php
	class Business extends Model{
		//未及时更新状态用户
		function findOwe($employee_id=null){
			
			$sql = "select 
				count(*) count 
				from
				(
				select 
				b.business_id
				from
				`business` b,
				(
				select 
				r.business_id,
				r.date_end
				from `progress` r
				where date_end = (
				select 
				max(p.date_end) date_end
				from
				`progress`p
				where
				p.business_id = r.business_id
				)
				) as s
				where 
				b.business_id = s.business_id and
				s.date_end <= now()
				";

			if(!empty($employee_id)){
				$sql = $sql . " and b.employee_id=?";
			}

			$sql = $sql . " ) as c";

			if(!empty($employee_id)){
				return $this->db->queryOne($sql,$employee_id);
			}else{
				return $this->db->queryOne($sql);
			}
		}
		//统计
		function findCount($status,$employee_id=null){
			if(empty($employee_id)){
				return $this->db->queryOne('select count(*) count from `business` where status=:status',array('status'=>$status));
			}else{
				return $this->db->queryOne('select count(*) count from `business` where status=? and employee_id=?',array($status,$employee_id));	
			}
		}

		function findOpen($guest_id){
			return $this->db->query("select 
				b.business_id,
				b.process_group_id
				from
				`guest` g,
				`business` b
				where 
				g.guest_id = b.guest_id
				and g.guest_id=?
				",$guest_id);
		}
		//查询一个业务是否是指定员工负责
		function findByEmployee($employee_id,$business_id){
			return $this->db->query("select business_id from `business` where employee_id=? and business_id=?",array($employee_id,$business_id));
		}
		//更新应、已款
		function updateFee($params){
			return $this->db->exec("update `business` set
				should_fee=:should_fee,
				have_fee=:have_fee
				where business_id=:business_id
			",$params);
		}
		//查询此用户是否已经开通了指定类型的业务
		function findByProcessGroupid($process_group_id,$guest_id){
			return $this->db->query("select business_id from `business` where process_group_id=? and guest_id=?",array($process_group_id,$guest_id));
		}
		
		function server_find($params,$page){
			$sql = '
				select * from (
				select 
				b.business_id,
				b.employee_id,
				date_format(pr.date_end,"%Y-%m-%d") date_end,
				pg.process_group_id,
				pg.name pg_name,
				g.company,
				g.name,
				g.phone,
				g.tel,
				e2.name server,
				e.name accounting,
				b.status,
				b.should_fee,
				b.have_fee,
				p.name p_name,
				DATE_FORMAT(b.create_time ,"%Y-%m-%d") create_time
				from 
				`guest` g,
				`employee` e2,
				`business` b
				left join 
				`process_group` pg
				on b.process_group_id = pg.process_group_id
				left join `employee` e
				on b.employee_id = e.employee_id 
				left join `progress` pr 
				on pr.business_id = b.business_id 
				left join  `process` p
				on p.process_id = pr.process_id
				where 
				g.employee_id = e2.employee_id
				and b.guest_id = g.guest_id 
				and g.employee_id=:employee_id
				';

			if(isset($params['status'])){
				$sql = $sql . " and b.status=:status";
			}
			if(!empty($params['process_id'])){
				$sql = $sql . " and pr.process_id=:process_id";
			}else{
				unset($params['process_id']);
			}
			if(!empty($params['phone'])){
				$sql = $sql . ' and g.phone like "%":phone"%"';
			}else{
				unset($params['phone']);
			}
			if(!empty($params['com'])){
				$sql = $sql . ' and (g.company like "%":com"%" or g.name like "%":com"%")';
			}else{
				unset($params['com']);
			}
			if(!empty($params['year'])){
				$sql = $sql . " and YEAR(b.create_time)=:year";
			}else{
				unset($params['year']);
			}
			if(!empty($params['month'])){
				$sql = $sql . " and MONTH(b.create_time) = :month";
			}else{
				unset($params['month']);
			}

			$sql = $sql." order by pr.progress_id desc) b group by b.business_id";			

			$result =  $this->db->query($sql,$params,$page);

			if($result){
				return array('total'=>$this->db->count,'data'=>$result);	
			}else{
				return array();
			}
		}

		function accounting_find($params,$page){

			$sql = '
				select * from (
				select 
				b.business_id,
				b.employee_id,
				date_format(pr.date_end,"%Y-%m-%d") date_end,
				pg.process_group_id,
				pg.name pg_name,
				g.company,
				g.name,
				g.phone,
				g.tel,
				e2.name server,
				e.name accounting,
				b.status,
				b.should_fee,
				b.have_fee,
				p.name p_name,
				DATE_FORMAT(b.create_time ,"%Y-%m-%d") create_time
				from 
				`guest` g,
				`employee` e2,
				`business` b
				left join 
				`process_group` pg
				on b.process_group_id = pg.process_group_id
				left join `employee` e
				on b.employee_id = e.employee_id 
				left join `progress` pr 
				on pr.business_id = b.business_id 
				left join  `process` p
				on p.process_id = pr.process_id
				where 
				g.employee_id = e2.employee_id
				and b.guest_id = g.guest_id 
				';

			if(isset($params['status'])){
				$sql = $sql . " and b.status=:status";
			}
			if(!empty($params['process_id'])){
				$sql = $sql . " and pr.process_id=:process_id";
			}else{
				unset($params['process_id']);
			}
			if(!empty($params['employee_id'])){
				$sql = $sql . " and b.employee_id=:employee_id";
			}else{
				unset($params['employee_id']);
			}
			if(!empty($params['phone'])){
				$sql = $sql . ' and g.phone like "%":phone"%"';
			}else{
				unset($params['phone']);
			}
			if(!empty($params['com'])){
				$sql = $sql . ' and (g.company like "%":com"%" or g.name like "%":com"%")';
			}else{
				unset($params['com']);
			}
			if(!empty($params['year'])){
				$sql = $sql . " and YEAR(b.create_time)=:year";
			}else{
				unset($params['year']);
			}
			if(!empty($params['month'])){
				$sql = $sql . " and MONTH(b.create_time) = :month";
			}else{
				unset($params['month']);
			}

			$sql = $sql." order by pr.progress_id desc) b group by b.business_id";			

			$result =  $this->db->query($sql,$params,$page);

			if($result){
				return array('total'=>$this->db->count,'data'=>$result);	
			}else{
				return array();
			}
		}

		function server_admin_find($params,$page){

			$sql = '
				select * from (
				select 
				b.business_id,
				b.employee_id,
				date_format(pr.date_end,"%Y-%m-%d") date_end,
				pg.process_group_id,
				pg.name pg_name,
				g.company,
				g.name,
				g.phone,
				g.tel,
				e2.name server,
				e.name accounting,
				b.status,
				b.should_fee,
				b.have_fee,
				p.name p_name,
				DATE_FORMAT(b.create_time ,"%Y-%m-%d") create_time
				from 
				`guest` g,
				`employee` e2,
				`business` b
				left join 
				`process_group` pg
				on b.process_group_id = pg.process_group_id
				left join `employee` e
				on b.employee_id = e.employee_id 
				left join `progress` pr 
				on pr.business_id = b.business_id 
				left join  `process` p
				on p.process_id = pr.process_id
				where 
				g.employee_id = e2.employee_id
				and b.guest_id = g.guest_id 
				';

			if(isset($params['status'])){
				$sql = $sql . " and b.status=:status";
			}
			if(!empty($params['process_id'])){
				$sql = $sql . " and pr.process_id=:process_id";
			}else{
				unset($params['process_id']);
			}

			if(!empty($params['employee_id'])){
				$sql = $sql . " and e2.employee_id=:employee_id";
			}else{
				unset($params['employee_id']);
			}

			if(!empty($params['department_id'])){
				$sql = $sql . " and e2.department_id=:department_id";
			}else{
				unset($params['department_id']);
			}

			if(!empty($params['phone'])){
				$sql = $sql . ' and g.phone like "%":phone"%"';
			}else{
				unset($params['phone']);
			}
			if(!empty($params['com'])){
				$sql = $sql . ' and (g.company like "%":com"%" or g.name like "%":com"%")';
			}else{
				unset($params['com']);
			}
			if(!empty($params['year'])){
				$sql = $sql . " and YEAR(b.create_time)=:year";
			}else{
				unset($params['year']);
			}
			if(!empty($params['month'])){
				$sql = $sql . " and MONTH(b.create_time) = :month";
			}else{
				unset($params['month']);
			}

			$sql = $sql." order by pr.progress_id desc) b group by b.business_id";			

			$result =  $this->db->query($sql,$params,$page);

			if($result){
				return array('total'=>$this->db->count,'data'=>$result);	
			}else{
				return array();
			}
		}

		function accounting_admin_find($params,$page){

			$sql = '
				select * from (
				select 
				b.business_id,
				b.employee_id,
				date_format(pr.date_end,"%Y-%m-%d") date_end,
				pg.process_group_id,
				pg.name pg_name,
				g.company,
				g.name,
				g.phone,
				g.tel,
				e2.name server,
				e.name accounting,
				b.status,
				b.should_fee,
				b.have_fee,
				p.name p_name,
				DATE_FORMAT(b.create_time ,"%Y-%m-%d") create_time
				from 
				`guest` g,
				`employee` e2,
				`business` b
				left join 
				`process_group` pg
				on b.process_group_id = pg.process_group_id
				left join `employee` e
				on b.employee_id = e.employee_id 
				left join `progress` pr 
				on pr.business_id = b.business_id 
				left join  `process` p
				on p.process_id = pr.process_id
				where 
				g.employee_id = e2.employee_id
				and b.guest_id = g.guest_id 
				';

			if(isset($params['status'])){
				$sql = $sql . " and b.status=:status";
			}
			if(!empty($params['process_id'])){
				$sql = $sql . " and pr.process_id=:process_id";
			}else{
				unset($params['process_id']);
			}

			if(!empty($params['employee_id'])){
				$sql = $sql . " and e.employee_id=:employee_id";
			}else{
				unset($params['employee_id']);
			}

			if(!empty($params['department_id'])){
				$sql = $sql . " and e.department_id=:department_id";
			}else{
				unset($params['department_id']);
			}

			if(!empty($params['phone'])){
				$sql = $sql . ' and g.phone like "%":phone"%"';
			}else{
				unset($params['phone']);
			}
			if(!empty($params['com'])){
				$sql = $sql . ' and (g.company like "%":com"%" or g.name like "%":com"%")';
			}else{
				unset($params['com']);
			}
			if(!empty($params['year'])){
				$sql = $sql . " and YEAR(b.create_time)=:year";
			}else{
				unset($params['year']);
			}
			if(!empty($params['month'])){
				$sql = $sql . " and MONTH(b.create_time) = :month";
			}else{
				unset($params['month']);
			}

			$sql = $sql." order by pr.progress_id desc) b group by b.business_id";			

			$result =  $this->db->query($sql,$params,$page);

			if($result){
				return array('total'=>$this->db->count,'data'=>$result);	
			}else{
				return array();
			}
		}

		function admin_find($params,$page){

			$sql = '
				select * from (
				select 
				b.business_id,
				b.employee_id,
				date_format(pr.date_end,"%Y-%m-%d") date_end,
				pg.process_group_id,
				pg.name pg_name,
				g.company,
				g.name,
				g.phone,
				g.tel,
				e2.name server,
				e.name accounting,
				b.status,
				b.should_fee,
				b.have_fee,
				p.name p_name,
				DATE_FORMAT(b.create_time ,"%Y-%m-%d") create_time
				from 
				`guest` g,
				`employee` e2,
				`business` b
				left join 
				`process_group` pg
				on b.process_group_id = pg.process_group_id
				left join `employee` e
				on b.employee_id = e.employee_id 
				left join `progress` pr 
				on pr.business_id = b.business_id 
				left join  `process` p
				on p.process_id = pr.process_id
				where 
				g.employee_id = e2.employee_id
				and b.guest_id = g.guest_id 
				';

			if(isset($params['status'])){
				$sql = $sql . " and b.status=:status";
			}
			if(!empty($params['process_id'])){
				$sql = $sql . " and pr.process_id=:process_id";
			}else{
				unset($params['process_id']);
			}

			if(!empty($params['phone'])){
				$sql = $sql . ' and g.phone like "%":phone"%"';
			}else{
				unset($params['phone']);
			}
			if(!empty($params['com'])){
				$sql = $sql . ' and (g.company like "%":com"%" or g.name like "%":com"%")';
			}else{
				unset($params['com']);
			}
			if(!empty($params['year'])){
				$sql = $sql . " and YEAR(b.create_time)=:year";
			}else{
				unset($params['year']);
			}
			if(!empty($params['month'])){
				$sql = $sql . " and MONTH(b.create_time) = :month";
			}else{
				unset($params['month']);
			}
			
			$sql = $sql." order by pr.progress_id desc) b group by b.business_id";			

			$result =  $this->db->query($sql,$params,$page);

			if($result){
				return array('total'=>$this->db->count,'data'=>$result);	
			}else{
				return array();
			}
		}


		// function find($params,$page){

		// 	$sql = '
		// 		select * from (
		// 		select 
		// 		b.business_id,
		// 		b.employee_id,
		// 		pg.process_group_id,
		// 		pg.name pg_name,
		// 		g.company,
		// 		g.name,
		// 		g.phone,
		// 		g.tel,
		// 		e2.name server,
		// 		e.name accounting,
		// 		b.status,
		// 		b.should_fee,
		// 		b.have_fee,
		// 		p.name p_name,
		// 		DATE_FORMAT(b.create_time ,"%Y-%m-%d") create_time
		// 		from 
		// 		`guest` g,
		// 		`employee` e2,
		// 		`business` b
		// 		left join 
		// 		`process_group` pg
		// 		on b.process_group_id = pg.process_group_id
		// 		left join `employee` e
		// 		on b.employee_id = e.employee_id 
		// 		left join `progress` pr 
		// 		on pr.business_id = b.business_id 
		// 		left join  `process` p
		// 		on p.process_id = pr.process_id
		// 		where 
		// 		g.employee_id = e2.employee_id
		// 		and b.guest_id = g.guest_id 
		// 		';

		// 	if(isset($params['status'])){
		// 		$sql = $sql . " and b.status=:status";
		// 	}
		// 	if(!empty($params['process_id'])){
		// 		$sql = $sql . " and pr.process_id=:process_id";
		// 	}else{
		// 		unset($params['process_id']);
		// 	}
		// 	if(!empty($params['employee_id'])){
		// 		if($params['self']){
		// 			$sql = $sql . " and g.employee_id=:employee_id";
		// 		}else{
		// 			$sql = $sql . " and b.employee_id=:employee_id";
		// 		}
		// 	}else{
		// 		unset($params['employee_id']);
		// 	}
		// 	unset($params['self']);
		// 	if(!empty($params['department_id'])){
		// 		$sql = $sql . " and e.department_id=:department_id";
		// 	}else{
		// 		unset($params['department_id']);
		// 	}

		// 	if(!empty($params['phone'])){
		// 		$sql = $sql . ' and g.phone like "%":phone"%"';
		// 	}else{
		// 		unset($params['phone']);
		// 	}
		// 	if(!empty($params['com'])){
		// 		$sql = $sql . ' and (g.company like "%":com"%" or g.name like "%":com"%")';
		// 	}else{
		// 		unset($params['com']);
		// 	}



		// 	$sql = $sql." order by pr.progress_id desc) b group by b.business_id";			

		// 	$result =  $this->db->query($sql,$params,$page);

		// 	if($result){
		// 		return array('total'=>$this->db->count,'data'=>$result);	
		// 	}else{
		// 		return array();
		// 	}
		// }
		function add($params){
			$result =  $this->db->exec('insert into `business` set 
				process_group_id=:process_group_id,
				guest_id=:guest_id,
				employee_id=:employee_id,
				should_fee=:should_fee,
				have_fee=:have_fee,
				create_time=now()
			',$params);
			
			return $result;
			// if($result){
			// 	return $this->db->lastId();
			// }
		}
		function update($params){
			return $this->db->exec('update `business` set 
				process_group_id=:process_group_id,
				employee_id=:employee_id,
				should_fee=:should_fee,
				have_fee=:have_fee,
				status=:status
				where business_id=:business_id
			',$params);
		}
		function delete($business_id){
			return $this->db->exec('delete from `business` where business_id=?',$business_id);
		}
		function updateStatus($params){
			return $this->db->exec('update `business` set status=:status where business_id=:business_id',$params);
		}
		function updateProcess($params){
			return $this->db->exec('update `business` set process_id=:process_id where business_id=:business_id',$params);
		}
		/*
			add by zgj 2015-12-8
			查询工商代办事项
		*/
		function waitProcess($employee_id,$page){
			return $this->db->query("select g.company,e.name,p.* from 
				(select * from (select * from progress order by create_time desc)p 
				group by p.business_id)p,guest g,employee e,business b where 
				p.business_id=b.business_id and b.guest_id=g.guest_id and 
				b.employee_id=e.employee_id and e.employee_id=?",$employee_id,$page);
		}
	}
?>