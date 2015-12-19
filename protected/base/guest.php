<?php
	class Guest extends Model{

		function searchByPhone($phone){
			return $this->db->query('select company,name,guest_id from `guest` where phone like "%"?"%" limit 5',$phone);
		}

		function searchByCom($com){
			return $this->db->query('select company,name,guest_id from `guest` where company like "%"?"%" or name like "%"?"%" limit 5',array($com,$com));
		}
		//统计
		function findCount($status,$employee_id=null){
			if(empty($employee_id)){
				return $this->db->queryOne("select count(*) count from `guest` where status=:status",array('status'=>$status));
			}else{
				return $this->db->queryOne("select count(*) count from `guest` where status=? and employee_id=?",array($status,$employee_id));
			}
		}
		//查询某员工是否负责某用户
		function findByEmployee($employee_id,$guest_id){
			return $this->db->query("select guest_id from `guest` where guest_id=? and employee_id=?",array($guest_id,$employee_id));
		}
		//查询用户已经开通的工商类服务
		function findBusiness($guest_id){
			return $this->db->query("select 
				p.name
				 from 
				`business` b,
				`process_group` p
				where b.guest_id = ?
				and b.process_group_id = p.process_group_id"
				,$guest_id);
		}
		//查询已经开通代理记账类服务
		function findAccounting($guest_id){
			return $this->db->queryOne("select accounting_id from `accounting` where guest_id=?",$guest_id);
		}

		function searchById($guest_id){
			return $this->findById($guest_id);
		}

		function findById($guest_id){
			return $this->db->queryOne('select 
				g.guest_id,
				g.name,
				g.address,
				g.phone,
				g.company,
				g.tel,
				g.status,
				g.create_time,
				r.description,
				e.name e_name,
				a.name a_name
				from `guest` g
				left join `employee` e
				on e.employee_id = g.employee_id
				left join `area` a
				on a.area_id = g.area_id
				left join `resource` r
				on r.resource_id = g.resource_id
				where g.guest_id = ?
				',$guest_id);
		}
		function find($params,$page){

			$sql = '
				select 
				g.guest_id,
				g.employee_id,
				g.name,
				g.phone,
				g.address,
				g.company,
				g.tel,
				g.status,
				g.area_id,
				date_format(g.create_time,"%Y-%m-%d") create_time,
				r.description,
				e.name e_name,
				a.name a_name
				from `guest` g
				left join `employee` e
				on e.employee_id = g.employee_id
				left join `area` a
				on a.area_id = g.area_id
				left join `resource` r
				on r.resource_id = g.resource_id
				where 
				g.status=:status';

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
				$sql = $sql . " and year(g.create_time) = :year";
			}else{
				unset($params['year']);
			}
			if(!empty($params['month'])){
				$sql = $sql . " and month(g.create_time) = :month";
			}else{
				unset($params['month']);
			}

			$sql = $sql." order by g.guest_id desc";
			
			$result = $this->db->query($sql,$params,$page);

			if($result){
				return array('total'=>$this->db->count,'data'=>$result);
			}else{
				return array();
			}
			
		}

		function add($params){
			$result = $this->db->exec('insert into `guest` set 
				company=:company,
				name=:name,
				phone=:phone,
				job=:job,
				area_id=:area_id,
				address=:address,
				resource_id=:resource_id,
				employee_id=:employee_id,
				tel=:tel,
				status=:status,
				create_time=now()
			',$params);

			if($result){
				return $this->db->lastId();
			}else{
				return false;
			}
		}
		function update($params){
			return $this->db->exec('update `guest` set 
				company=:company,
				name=:name,
				phone=:phone,
				area_id=:area_id,
				address=:address,
				tel=:tel,
				status=:status
				where guest_id=:guest_id
				and employee_id=:employee_id
			',$params);
		}
		function delete($guest_id){
			return $this->db->exec('delete from `guest` where guest_id=?',$guest_id);
		}
	}
?>