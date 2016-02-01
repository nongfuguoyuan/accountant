<?php
	class Accounting extends Model{
		
		//查询状态
		function findStatus($accounting_id){
			return $this->db->queryOne('select status from `accounting` where accounting_id=?',$accounting_id);
		}
		//统计
		function findCount($status,$employee_id=null){
			if(empty($employee_id)){
				return $this->db->queryOne('select count(*) count from `accounting` where status=:status',array('status'=>$status));
			}else{
				return $this->db->queryOne("select count(*) count from `accounting` where status=? and employee_id=?",array($status,$employee_id));
			}
		}
		//将在15天后欠款统计
		function findOwe($employee_id=null){
			if(empty($employee_id)){
				return $this->db->queryOne("select 
					count(*) count
					from 
					(
					select a.accounting_id
					from
					`accounting` a,
					`pay_record` p
					where 
					a.accounting_id = p.accounting_id and
					p.deadline < timestampadd(day, 15, now()) 
					group by p.accounting_id
					) as s");
			}else{
				return $this->db->queryOne("select 
					count(*) count
					from 
					(
					select a.accounting_id
					from
					`accounting` a,
					`pay_record` p
					where 
					a.accounting_id = p.accounting_id and
					a.employee_id = ? and
					p.deadline < timestampadd(day, 15, now())
					group by p.accounting_id
					) as s",$employee_id);
			}
		}

		// 查询指定用户是否开通代理记账服务
		function findOpen($guest_id){
			$re = $this->db->queryOne("select 
				DATE_FORMAT(p.deadline,'%Y-%m-%d') deadline,
				a.guest_id
				from
				`accounting` a
				left join
				`pay_record` p on
				a.accounting_id = p.accounting_id
				where a.guest_id = ?
				order by p.deadline desc limit 1",$guest_id);
			
			return $re;
		}
		//查询是否由指定员工负责
		function findByEmployee($employee_id,$accounting_id){

			return $this->db->query("select accounting_id from `accounting` where employee_id=? and accounting_id=?",array($employee_id,$accounting_id));

		}
		//根据guest_id,employee_id 查询是否由指定用户负责
		function findByGuestid($guest_id,$employee_id){
			
			return $this->db->queryOne('select accounting_id from `accounting` where guest_id=? and employee_id=?',array($guest_id,$employee_id));

		}
		//办理代理记账并且受理过（有人负责）的用户
		function findHasAccounting($params,$page){
			$sql = '
				select 
				g.guest_id,
				g.name,
				g.company,
				g.phone,
				a.accounting_id,
				e.name accounting
				from
				`guest` g,
				`accounting` a
				left join
				employee e
				on 
				e.employee_id = a.employee_id
				where g.guest_id = a.guest_id

			';

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

			if(!empty($params['employee_id'])){
				$sql = $sql . " and a.employee_id=:employee_id";
			}else{
				unset($params['employee_id']);
			}

			$result = $this->db->query($sql,$params,$page);

			if($result){
				return array('total'=>$this->db->count,'data'=>$result);	
			}else{
				return array();
			}
		}

		function updateStatus($accounting_id){
			return $this->db->exec('update `accounting` set status=1 where accounting_id=?',$accounting_id);
		}

		function accounting_find($params,$page){

			$sql = "
				select
				g.guest_id,
				e.employee_id,
				g.company,
				g.name,
				g.phone,
				a.accounting_id,
				a.status,
				e2.name server,
				e.name accounting,
				DATE_FORMAT(b.deadline,'%Y-%m-%d') deadline,
				DATE_FORMAT(a.create_time,'%Y-%m-%d') create_time
				from 
				`guest` g,
				`employee` e,
				`employee` e2,
				`accounting` a
				left join 
				(select max(deadline) deadline,money,pay_record_id,accounting_id from `pay_record` group by accounting_id) b
				on b.accounting_id = a.accounting_id
				where 
				a.guest_id = g.guest_id and
				e.employee_id = a.employee_id and
				g.employee_id = e2.employee_id
				and e.employee_id=:employee_id
			";
			
			if(isset($params['status'])){
				$sql = $sql . " and a.status=:status";
			}

			//未受理状态不区分是否欠费
			if($params['status'] == 1){
				if(isset($params['owe'])){
					if($params['owe'] == 0){
						$sql = $sql . " and (b.deadline <= now()";
					}else{
						$sql = $sql . " and (b.deadline > now()";
					}
					$sql = $sql . " or b.deadline is null)";
				}
			}
			
			unset($params['owe']);

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
				$sql = $sql . " and YEAR(a.create_time)=:year";
			}else{
				unset($params['year']);
			}
			if(!empty($params['month'])){
				$sql = $sql . " and MONTH(a.create_time) = :month";
			}else{
				unset($params['month']);
			}
			// return $params;
			$result =  $this->db->query($sql,$params,$page);

			if($result){
				return array('total'=>$this->db->count,'data'=>$result);	
			}else{
				return array();
			}
		}

		function server_find($params,$page){
			
			$sql = "
				select
				g.guest_id,
				e.employee_id,
				g.company,
				g.name,
				g.phone,
				a.accounting_id,
				a.status,
				e2.name server,
				e.name accounting,
				DATE_FORMAT(b.deadline,'%Y-%m-%d') deadline,
				DATE_FORMAT(a.create_time,'%Y-%m-%d') create_time
				from 
				`guest` g,
				`employee` e,
				`employee` e2,
				`accounting` a
				left join 
				(select max(deadline) deadline,money,pay_record_id,accounting_id from `pay_record` group by accounting_id) b
				on b.accounting_id = a.accounting_id
				where 
				a.guest_id = g.guest_id and
				e.employee_id = a.employee_id and
				g.employee_id = e2.employee_id
				and g.employee_id=:employee_id
			";
			
			if(isset($params['status'])){
				$sql = $sql . " and a.status=:status";
			}

			//未受理状态不区分是否欠费
			if($params['status'] == 1){
				if(isset($params['owe'])){
					if($params['owe'] == 0){
						$sql = $sql . " and (b.deadline <= now()";
					}else{
						$sql = $sql . " and (b.deadline > now()";
					}
					$sql = $sql . " or b.deadline is null)";
				}
			}
			
			unset($params['owe']);

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
				$sql = $sql . " and YEAR(a.create_time)=:year";
			}else{
				unset($params['year']);
			}
			if(!empty($params['month'])){
				$sql = $sql . " and MONTH(a.create_time) = :month";
			}else{
				unset($params['month']);
			}
			
			$result =  $this->db->query($sql,$params,$page);

			if($result){
				return array('total'=>$this->db->count,'data'=>$result);	
			}else{
				return array();
			}
		}

		function server_admin_find($params,$page){
			
			$sql = "
				select
				g.guest_id,
				e.employee_id,
				g.company,
				g.name,
				g.phone,
				a.accounting_id,
				a.status,
				e2.name server,
				e.name accounting,
				DATE_FORMAT(b.deadline,'%Y-%m-%d') deadline,
				DATE_FORMAT(a.create_time,'%Y-%m-%d') create_time
				from 
				`guest` g,
				`employee` e,
				`employee` e2,
				`accounting` a
				left join 
				(select max(deadline) deadline,money,pay_record_id,accounting_id from `pay_record` group by accounting_id) b
				on b.accounting_id = a.accounting_id
				where 
				a.guest_id = g.guest_id and
				e.employee_id = a.employee_id and
				g.employee_id = e2.employee_id
			";
			
			if(isset($params['status'])){
				$sql = $sql . " and a.status=:status";
			}

			//未受理状态不区分是否欠费
			if($params['status'] == 1){
				if(isset($params['owe'])){
					if($params['owe'] == 0){
						$sql = $sql . " and (b.deadline <= now()";
					}else{
						$sql = $sql . " and (b.deadline > now()";
					}
					$sql = $sql . " or b.deadline is null)";
				}
			}
			
			unset($params['owe']);

			if(!empty($params['employee_id'])){
				$sql = $sql . " and g.employee_id=:employee_id";
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
				$sql = $sql . " and YEAR(a.create_time)=:year";
			}else{
				unset($params['year']);
			}
			if(!empty($params['month'])){
				$sql = $sql . " and MONTH(a.create_time) = :month";
			}else{
				unset($params['month']);
			}
			
			$result =  $this->db->query($sql,$params,$page);

			if($result){
				return array('total'=>$this->db->count,'data'=>$result);	
			}else{
				return array();
			}
		}

		function accounting_admin_find($params,$page){
			
			$sql = "
				select
				g.guest_id,
				e.employee_id,
				g.company,
				g.name,
				g.phone,
				a.accounting_id,
				a.status,
				e2.name server,
				e.name accounting,
				DATE_FORMAT(b.deadline,'%Y-%m-%d') deadline,
				DATE_FORMAT(a.create_time,'%Y-%m-%d') create_time
				from 
				`guest` g,
				`employee` e,
				`employee` e2,
				`accounting` a
				left join 
				(select max(deadline) deadline,money,pay_record_id,accounting_id from `pay_record` group by accounting_id) b
				on b.accounting_id = a.accounting_id
				where 
				a.guest_id = g.guest_id and
				e.employee_id = a.employee_id and
				g.employee_id = e2.employee_id
			";
			
			if(isset($params['status'])){
				$sql = $sql . " and a.status=:status";
			}

			//未受理状态不区分是否欠费
			if($params['status'] == 1){
				if(isset($params['owe'])){
					if($params['owe'] == 0){
						$sql = $sql . " and (b.deadline <= now()";
					}else{
						$sql = $sql . " and (b.deadline > now()";
					}
					$sql = $sql . " or b.deadline is null)";
				}
			}
			
			unset($params['owe']);

			if(!empty($params['employee_id'])){
				$sql = $sql . " and a.employee_id=:employee_id";
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
				$sql = $sql . " and YEAR(a.create_time)=:year";
			}else{
				unset($params['year']);
			}
			if(!empty($params['month'])){
				$sql = $sql . " and MONTH(a.create_time) = :month";
			}else{
				unset($params['month']);
			}

			$result =  $this->db->query($sql,$params,$page);

			if($result){
				return array('total'=>$this->db->count,'data'=>$result);	
			}else{
				return array();
			}
		}

		function admin_find($params,$page){
			
			$sql = "
				select
				g.guest_id,
				e.employee_id,
				g.company,
				g.name,
				g.phone,
				a.accounting_id,
				a.status,
				e2.name server,
				e.name accounting,
				DATE_FORMAT(b.deadline,'%Y-%m-%d') deadline,
				DATE_FORMAT(a.create_time,'%Y-%m-%d') create_time
				from 
				`guest` g,
				`employee` e,
				`employee` e2,
				`accounting` a
				left join 
				(select max(deadline) deadline,money,pay_record_id,accounting_id from `pay_record` group by accounting_id) b
				on b.accounting_id = a.accounting_id
				where 
				a.guest_id = g.guest_id and
				e.employee_id = a.employee_id and
				g.employee_id = e2.employee_id
			";
			
			if(isset($params['status'])){
				$sql = $sql . " and a.status=:status";
			}

			//未受理状态不区分是否欠费
			if($params['status'] == 1){
				if(isset($params['owe'])){
					if($params['owe'] == 0){
						$sql = $sql . " and (b.deadline <= now()";
					}else{
						$sql = $sql . " and (b.deadline > now()";
					}
					$sql = $sql . " or b.deadline is null)";
				}
			}
			
			unset($params['owe']);

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
				$sql = $sql . " and YEAR(a.create_time)=:year";
			}else{
				unset($params['year']);
			}
			if(!empty($params['month'])){
				$sql = $sql . " and MONTH(a.create_time) = :month";
			}else{
				unset($params['month']);
			}
			
			$result =  $this->db->query($sql,$params,$page);

			if($result){
				return array('total'=>$this->db->count,'data'=>$result);	
			}else{
				return array();
			}
		}


		function add($params){
			$result = $this->db->exec('insert into `accounting` set 
				guest_id=:guest_id,
				employee_id=:employee_id,
				create_time=now()
			',$params);

			if($result){
				$this->db->lastId();	
			}else{
				return 0;//insert fail
			}
		}

		function update($params){
			return $this->db->exec('update `accounting` set 
				guest_id=:guest_id,
				employee_id=:employee_id
				where accounting_id=:accounting_id
			',$params);
		}

		function delete($accounting_id){
			return $this->db->exec('delete from `accounting` where accounting_id=?',$accounting_id);
		}

		function findById($accounting_id){
			return $this->db->queryOne("
				select
				g.guest_id,
				g.name,
				a.accounting_id,
				a.status,
				e2.name server,
				e.name accounting,
				b.deadline,
				a.create_time
				from 
				`guest` g,
				`employee` e,
				`employee` e2,
				`accounting` a
				left join 
				(select max(deadline) deadline,money,pay_record_id,accounting_id from `pay_record` group by accounting_id) b
				on b.accounting_id = a.accounting_id
				where 
				a.guest_id = g.guest_id and
				e.employee_id = a.employee_id and
				g.employee_id = e2.employee_id and
				a.accounting_id=?
			",$accounting_id);
		}
	}
