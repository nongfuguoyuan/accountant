<?php
	class Payrecord extends Model{

		function add($params){

			$result = $this->db->exec('insert into `pay_record` set
				accounting_id=:accounting_id,
				money=:money,
				deadline=:deadline,
				create_time=now()
			',$params);

			if($result){
				return $this->db->lastId();	
			}else{
				return 0;//add fail
			}
		}

		function update($params){
			return $this->db->exec("update `pay_record` p
				 inner join 
				(
				select
				p.pay_record_id
				from 
				`pay_record` p,
				`accounting` a,
				`employee` e
				where 
				p.accounting_id = a.accounting_id and
				e.employee_id = a.employee_id and
				e.employee_id = :employee_id and
				p.pay_record_id = :pay_record_id
				) s
				on  p.pay_record_id =s.pay_record_id
				set p.money = :money,
					p.deadline=:deadline
			",$params);
		}

		function findOneList($pay_record_id){
			return $this->db->queryOne("
				select
				p.pay_record_id,
				p.accounting_id,
				g.company,
				g.name,
				DATE_FORMAT(p.create_time,'%Y-%m-%d') create_time,
				DATE_FORMAT(p.deadline,'%Y-%m-%d') deadline,
				p.money
				from
				`accounting` a,
				`guest` g,
				`pay_record` p
				where 
				a.accounting_id = p.accounting_id and
				a.guest_id = g.guest_id and 
				p.pay_record_id = ?
			",$pay_record_id);
		}

		function findList($accounting_id,$year){
			return $this->db->query("
				select
				p.pay_record_id,
				p.accounting_id,
				g.company,
				g.name,
				DATE_FORMAT(p.create_time,'%Y-%m-%d') create_time,
				DATE_FORMAT(p.deadline,'%Y-%m-%d') deadline,
				p.money
				from
				`accounting` a,
				`guest` g,
				`pay_record` p
				where 
				a.accounting_id = p.accounting_id and
				a.guest_id = g.guest_id and 
				a.accounting_id = ?
				and YEAR(p.create_time) = ?
				order by p.deadline desc
			",array($accounting_id,$year));
		}

		function accounting_admin_find($params,$page){
			$sql = '
				select 
				g.company,
				g.name,
				g.phone,
				s.money,
				s.pay_record_id,
				e.name server,
				e2.name accounting,
				DATE_FORMAT(s.create_time,"%Y-%m-%d") create_time,
				DATE_FORMAT(s.deadline,"%Y-%m-%d") deadline,
				a.accounting_id
				 from
				`guest` g,
				`employee` e,
				`employee` e2,
				`accounting` a
				left join
				(select * from (
				select * from `pay_record` p
				where deadline = (
				select max(r.deadline) from `pay_record` r where  
				r.accounting_id = p.accounting_id
				)
				order by deadline desc
				) as b group by b.accounting_id) s
				on
				s.accounting_id = a.accounting_id
				where
				a.guest_id = g.guest_id and
				a.status = 1 and
				a.employee_id = e2.employee_id and 
				e.employee_id = g.employee_id
			';

			if(isset($params['owe'])){
				if($params['owe'] == 0){
					$sql = $sql . " and (s.deadline <= now()";
				}else{
					$sql = $sql . " and (s.deadline > now()";
				}
				$sql = $sql . " or s.deadline is null)";
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

			$sql = $sql . " order by s.pay_record_id desc";

			$result = $this->db->query($sql,$params,$page);

			if($result){
				return array('total'=>$this->db->count,'data'=>$result);	
			}else{
				return array();
			}
		}

		function server_admin_find($params,$page){
			$sql = '
				select 
				g.company,
				g.name,
				g.phone,
				s.money,
				s.pay_record_id,
				e.name server,
				e2.name accounting,
				DATE_FORMAT(s.create_time,"%Y-%m-%d") create_time,
				DATE_FORMAT(s.deadline,"%Y-%m-%d") deadline,
				a.accounting_id
				 from
				`guest` g,
				`employee` e,
				`employee` e2,
				`accounting` a
				left join
				(select * from (
				select * from `pay_record` p
				where deadline = (
				select max(r.deadline) from `pay_record` r where  
				r.accounting_id = p.accounting_id
				)
				order by deadline desc
				) as b group by b.accounting_id) s
				on
				s.accounting_id = a.accounting_id
				where
				a.guest_id = g.guest_id and
				a.status = 1 and
				a.employee_id = e2.employee_id and 
				e.employee_id = g.employee_id
			';

			if(isset($params['owe'])){
				if($params['owe'] == 0){
					$sql = $sql . " and (s.deadline <= now()";
				}else{
					$sql = $sql . " and (s.deadline > now()";
				}
				$sql = $sql . " or s.deadline is null)";
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

			$sql = $sql . " order by s.pay_record_id desc";

			$result = $this->db->query($sql,$params,$page);

			if($result){
				return array('total'=>$this->db->count,'data'=>$result);	
			}else{
				return array();
			}
		}

		function accounting_find($params,$page){
			$sql = '
				select 
				g.company,
				g.name,
				g.phone,
				s.money,
				s.pay_record_id,
				e.name server,
				e2.name accounting,
				DATE_FORMAT(s.create_time,"%Y-%m-%d") create_time,
				DATE_FORMAT(s.deadline,"%Y-%m-%d") deadline,
				a.accounting_id
				 from
				`guest` g,
				`employee` e,
				`employee` e2,
				`accounting` a
				left join
				(select * from (
				select * from `pay_record` p
				where deadline = (
				select max(r.deadline) from `pay_record` r where  
				r.accounting_id = p.accounting_id
				)
				order by deadline desc
				) as b group by b.accounting_id) s
				on
				s.accounting_id = a.accounting_id
				where
				a.guest_id = g.guest_id and
				a.status = 1 and
				a.employee_id = e2.employee_id and 
				e.employee_id = g.employee_id
			';

			if(isset($params['owe'])){
				if($params['owe'] == 0){
					$sql = $sql . " and (s.deadline <= now()";
				}else{
					$sql = $sql . " and (s.deadline > now()";
				}
				$sql = $sql . " or s.deadline is null)";
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

			if(!empty($params['employee_id'])){
				$sql = $sql . " and e2.employee_id=:employee_id";
			}else{
				unset($params['employee_id']);
			}
			$sql = $sql . " order by s.pay_record_id desc";

			$result = $this->db->query($sql,$params,$page);

			if($result){
				return array('total'=>$this->db->count,'data'=>$result);	
			}else{
				return array();
			}
		}

		function server_find($params,$page){
			$sql = '
				select 
				g.company,
				g.name,
				g.phone,
				s.money,
				s.pay_record_id,
				e.name server,
				e2.name accounting,
				DATE_FORMAT(s.create_time,"%Y-%m-%d") create_time,
				DATE_FORMAT(s.deadline,"%Y-%m-%d") deadline,
				a.accounting_id
				 from
				`guest` g,
				`employee` e,
				`employee` e2,
				`accounting` a
				left join
				(select * from (
				select * from `pay_record` p
				where deadline = (
				select max(r.deadline) from `pay_record` r where  
				r.accounting_id = p.accounting_id
				)
				order by deadline desc
				) as b group by b.accounting_id) s
				on
				s.accounting_id = a.accounting_id
				where
				a.guest_id = g.guest_id and
				a.status = 1 and
				a.employee_id = e2.employee_id and 
				e.employee_id = g.employee_id
			';

			if(isset($params['owe'])){
				if($params['owe'] == 0){
					$sql = $sql . " and (s.deadline <= now()";
				}else{
					$sql = $sql . " and (s.deadline > now()";
				}
				$sql = $sql . " or s.deadline is null)";
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

			if(!empty($params['employee_id'])){
				$sql = $sql . " and g.employee_id=:employee_id";
			}else{
				unset($params['employee_id']);
			}
			
			$sql = $sql . " order by s.pay_record_id desc";

			$result = $this->db->query($sql,$params,$page);

			if($result){
				return array('total'=>$this->db->count,'data'=>$result);	
			}else{
				return array();
			}
		}

		function admin_find($params,$page){
			$sql = '
				select 
				g.company,
				g.name,
				g.phone,
				s.money,
				s.pay_record_id,
				e.name server,
				e2.name accounting,
				DATE_FORMAT(s.create_time,"%Y-%m-%d") create_time,
				DATE_FORMAT(s.deadline,"%Y-%m-%d") deadline,
				a.accounting_id
				 from
				`guest` g,
				`employee` e,
				`employee` e2,
				`accounting` a
				left join
				(select * from (
				select * from `pay_record` p
				where deadline = (
				select max(r.deadline) from `pay_record` r where  
				r.accounting_id = p.accounting_id
				)
				order by deadline desc
				) as b group by b.accounting_id) s
				on
				s.accounting_id = a.accounting_id
				where
				a.guest_id = g.guest_id and
				a.status = 1 and
				a.employee_id = e2.employee_id and 
				e.employee_id = g.employee_id
			';

			if(isset($params['owe'])){
				if($params['owe'] == 0){
					$sql = $sql . " and (s.deadline <= now()";
				}else{
					$sql = $sql . " and (s.deadline > now()";
				}
				$sql = $sql . " or s.deadline is null)";
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

			if(!empty($params['employee_id'])){
				$sql = $sql . " and (e.employee_id=:employee_id or e2.employee_id=:employee_id)";
			}else{
				unset($params['employee_id']);
			}
			
			$sql = $sql . " order by s.pay_record_id desc";

			$result = $this->db->query($sql,$params,$page);

			if($result){
				return array('total'=>$this->db->count,'data'=>$result);	
			}else{
				return array();
			}
		}

		function server_find_date($params,$page){

			$sql = 'select 
				g.company,
				a.accounting_id,
				p.pay_record_id,
				g.guest_id,
				g.phone,
				g.name,
				e.name server,
				e2.name accounting,
				DATE_FORMAT(p.create_time,"%Y-%m-%d") create_time,
				DATE_FORMAT(p.deadline,"%Y-%m-%d") deadline
				from
				`pay_record` p,
				`guest` g,
				`employee` e,
				`employee` e2,
				`accounting` a
				where
				g.guest_id = a.guest_id and
				a.status = 1 and
				p.accounting_id = a.accounting_id and
				g.employee_id = e.employee_id and
				a.employee_id = e2.employee_id 
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

			if(!empty($params['year'])){
				$sql = $sql . " and year(p.create_time) =:year";
			}else{
				unset($params['year']);
			}
			if(!empty($params['month'])){
				$sql = $sql . " and month(p.create_time) =:month";
			}else{
				unset($params['month']);
			}
			if(!empty($params['employee_id'])){
				$sql = $sql . " and e.employee_id=:employee_id";
			}else{
				unset($params['employee_id']);
			}

			$sql = $sql . " order by p.create_time desc";

			if($result){
				return array('total'=>$this->db->count,'data'=>$result);
			}else{
				return array();
			}
		}

		function accounting_find_date($params,$page){

			$sql = 'select 
				p.money,
				g.guest_id,
				a.accounting_id,
				p.pay_record_id,
				g.company,
				g.phone,
				g.name,
				e.name server,
				e2.name accounting,
				DATE_FORMAT(p.create_time,"%Y-%m-%d") create_time,
				DATE_FORMAT(p.deadline,"%Y-%m-%d") deadline
				from
				`pay_record` p,
				`guest` g,
				`employee` e,
				`employee` e2,
				`accounting` a
				where
				g.guest_id = a.guest_id and
				a.status = 1 and
				p.accounting_id = a.accounting_id and
				g.employee_id = e.employee_id and
				a.employee_id = e2.employee_id 
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

			if(!empty($params['year'])){
				$sql = $sql . " and year(p.create_time) =:year";
			}else{
				unset($params['year']);
			}
			if(!empty($params['month'])){
				$sql = $sql . " and month(p.create_time) =:month";
			}else{
				unset($params['month']);
			}
			if(!empty($params['employee_id'])){
				$sql = $sql . " and a.employee_id=:employee_id";
			}else{
				unset($params['employee_id']);
			}

			$sql = $sql . " order by p.create_time desc";

			$result = $this->db->query($sql,$params,$page);

			if($result){
				return array('total'=>$this->db->count,'data'=>$result);
			}else{
				return array();
			}
		}

		function accounting_admin_find_date($params,$page){

			$sql = 'select 
				p.money,
				g.company,
				a.accounting_id,
				p.pay_record_id,
				g.phone,
				g.name,
				e.name server,
				e2.name accounting,
				DATE_FORMAT(p.create_time,"%Y-%m-%d") create_time,
				DATE_FORMAT(p.deadline,"%Y-%m-%d") deadline
				from
				`pay_record` p,
				`guest` g,
				`employee` e,
				`employee` e2,
				`accounting` a
				where
				g.guest_id = a.guest_id and
				a.status = 1 and
				p.accounting_id = a.accounting_id and
				g.employee_id = e.employee_id and
				a.employee_id = e2.employee_id 
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

			if(!empty($params['year'])){
				$sql = $sql . " and year(p.create_time) =:year";
			}else{
				unset($params['year']);
			}
			if(!empty($params['month'])){
				$sql = $sql . " and month(p.create_time) =:month";
			}else{
				unset($params['month']);
			}
			if(!empty($params['employee_id'])){
				$sql = $sql . " and a.employee_id=:employee_id";
			}else{
				unset($params['employee_id']);
			}
			if(!empty($params['department_id'])){
				$sql = $sql . " and e2.department_id=:department_id";
			}else{
				unset($params['department_id']);
			}

			$sql = $sql . " order by p.create_time desc";

			$result = $this->db->query($sql,$params,$page);

			if($result){
				return array('total'=>$this->db->count,'data'=>$result);
			}else{
				return array();
			}
		}

		function server_admin_find_date($params,$page){

			$sql = 'select 
				p.money,
				g.guest_id,
				a.accounting_id,
				p.pay_record_id,
				g.company,
				g.phone,
				g.name,
				e.name server,
				e2.name accounting,
				DATE_FORMAT(p.create_time,"%Y-%m-%d") create_time,
				DATE_FORMAT(p.deadline,"%Y-%m-%d") deadline
				from
				`pay_record` p,
				`guest` g,
				`employee` e,
				`employee` e2,
				`accounting` a
				where
				g.guest_id = a.guest_id and
				a.status = 1 and
				p.accounting_id = a.accounting_id and
				g.employee_id = e.employee_id and
				a.employee_id = e2.employee_id 
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

			if(!empty($params['year'])){
				$sql = $sql . " and year(p.create_time) =:year";
			}else{
				unset($params['year']);
			}
			if(!empty($params['month'])){
				$sql = $sql . " and month(p.create_time) =:month";
			}else{
				unset($params['month']);
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

			$sql = $sql . " order by p.create_time desc";

			$result = $this->db->query($sql,$params,$page);

			if($result){
				return array('total'=>$this->db->count,'data'=>$result);
			}else{
				return array();
			}
		}

		function admin_find_date($params,$page){

			$sql = 'select 
				p.money,
				g.guest_id,
				a.accounting_id,
				p.pay_record_id,
				g.company,
				g.phone,
				g.name,
				e.name server,
				e2.name accounting,
				DATE_FORMAT(p.create_time,"%Y-%m-%d") create_time,
				DATE_FORMAT(p.deadline,"%Y-%m-%d") deadline
				from
				`pay_record` p,
				`guest` g,
				`employee` e,
				`employee` e2,
				`accounting` a
				where
				g.guest_id = a.guest_id and
				a.status = 1 and
				p.accounting_id = a.accounting_id and
				g.employee_id = e.employee_id and
				a.employee_id = e2.employee_id 
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

			if(!empty($params['year'])){
				$sql = $sql . " and year(p.create_time) =:year";
			}else{
				unset($params['year']);
			}
			if(!empty($params['month'])){
				$sql = $sql . " and month(p.create_time) =:month";
			}else{
				unset($params['month']);
			}

			if(!empty($params['employee_id'])){
				$sql = $sql . " and (a.employee_id=:employee_id or e.employee_id=:employee_id)";
			}else{
				unset($params['employee_id']);
			}

			$sql = $sql . " order by p.create_time desc";

			$result = $this->db->query($sql,$params,$page);

			if($result){
				return array('total'=>$this->db->count,'data'=>$result);
			}else{
				return array();
			}
		}

		function find($params,$page){
			$sql = '
				select 
				g.company,
				g.name,
				g.phone,
				s.money,
				s.pay_record_id,
				e.name server,
				e2.name accounting,
				DATE_FORMAT(s.create_time,"%Y-%m-%d") create_time,
				DATE_FORMAT(s.deadline,"%Y-%m-%d") deadline,
				a.accounting_id
				 from
				`guest` g,
				`employee` e,
				`employee` e2,
				`accounting` a
				left join
				(select * from (
				select * from `pay_record` p
				where deadline = (
				select max(r.deadline) from `pay_record` r where  
				r.accounting_id = p.accounting_id
				)
				order by deadline desc
				) as b group by b.accounting_id) s
				on
				s.accounting_id = a.accounting_id
				where
				a.guest_id = g.guest_id and
				a.employee_id = e2.employee_id and 
				e.employee_id = g.employee_id
			';

			if(isset($params['owe'])){
				if($params['owe'] == 0){
					$sql = $sql . " and s.deadline <= now()";
				}else{
					$sql = $sql . " and s.deadline > now()";
				}
			}
			unset($params['owe']);

			if(!empty($params['phone'])){
				$sql = $sql . ' and g.phone like "%":phone"%"';
			}else{
				unset($params['phone']);
			}

			if(!empty($params['com'])){
				$sql = $sql . ' and g.company like "%":com"%" or g.name like "%":com"%"';
			}else{
				unset($params['com']);
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

			$sql = $sql . " order by s.pay_record_id desc";

			$result = $this->db->query($sql,$params,$page);

			if($result){
				return array('total'=>$this->db->count,'data'=>$result);	
			}else{
				return array();
			}
		}

		function delete($pay_record_id){
			return $this->db->exec('delete from `pay_record` where pay_record_id=?',$pay_record_id);
		}
		
	}