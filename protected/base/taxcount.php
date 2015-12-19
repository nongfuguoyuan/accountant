<?php 
	class Taxcount extends Model{

		function server_findByDate($params,$page){
			$sql = 'select 
				g.guest_id,
				g.company,
				g.phone,
				g.name,
				e.name server,
				e2.name accounting,
				t.nation,
				t.local,
				t.tax_count_id,
				t.year,
				t.month
				from 
				`guest` g,
				`employee` e,
				`employee` e2,
				`accounting`a,
				`tax_count` t
				where g.guest_id = a.guest_id
				and t.guest_id = a.guest_id
				and g.employee_id = e.employee_id
				and a.employee_id = e2.employee_id
				and a.status = 1
				and g.employee_id=:employee_id
				';

			if(!empty($params['year'])){
				$sql = $sql . " and t.year =:year";
			}else{
				unset($params['year']);
			}
			if(!empty($params['month'])){
				$sql = $sql . " and t.month =:month";
			}else{
				unset($params['month']);
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


			$result = $this->db->query($sql,$params,$page);
			if($result){
				return array("total"=>$this->db->count,'data'=>$result);
			}else{
				return array();
			}
		}

		function accounting_findByDate($params,$page){
			$sql = 'select 
				g.guest_id,
				g.company,
				g.phone,
				g.name,
				e.name server,
				e2.name accounting,
				t.nation,
				t.local,
				t.tax_count_id,
				t.year,
				t.month
				from 
				`guest` g,
				`employee` e,
				`employee` e2,
				`accounting`a,
				`tax_count` t
				where g.guest_id = a.guest_id
				and t.guest_id = a.guest_id
				and g.employee_id = e.employee_id
				and a.employee_id = e2.employee_id
				and a.status = 1
				and a.employee_id=:employee_id
				';

			if(!empty($params['year'])){
				$sql = $sql . " and t.year =:year";
			}else{
				unset($params['year']);
			}
			if(!empty($params['month'])){
				$sql = $sql . " and t.month =:month";
			}else{
				unset($params['month']);
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
			$result = $this->db->query($sql,$params,$page);
			if($result){
				return array("total"=>$this->db->count,'data'=>$result);
			}else{
				return array();
			}
		}

		function accounting_admin_findByDate($params,$page){
			$sql = 'select 
				g.guest_id,
				g.company,
				g.phone,
				g.name,
				e.name server,
				e2.name accounting,
				t.nation,
				t.local,
				t.tax_count_id,
				t.year,
				t.month
				from 
				`guest` g,
				`employee` e,
				`employee` e2,
				`accounting`a,
				`tax_count` t
				where g.guest_id = a.guest_id
				and t.guest_id = a.guest_id
				and g.employee_id = e.employee_id
				and a.status = 1
				and a.employee_id = e2.employee_id
				';

			if(!empty($params['year'])){
				$sql = $sql . " and t.year =:year";
			}else{
				unset($params['year']);
			}
			if(!empty($params['month'])){
				$sql = $sql . " and t.month =:month";
			}else{
				unset($params['month']);
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
			if(!empty($params['department_id'])){
				$sql = $sql . " and e2.department_id=:department_id";
			}else{
				unset($params['department_id']);
			}
			if(!empty($params['employee_id'])){
				$sql = $sql . " and e2.employee_id=:employee_id";
			}else{
				unset($params['employee_id']);
			}
			$result = $this->db->query($sql,$params,$page);
			if($result){
				return array("total"=>$this->db->count,'data'=>$result);
			}else{
				return array();
			}
		}

		function server_admin_findByDate($params,$page){
			$sql = 'select 
				g.guest_id,
				g.company,
				g.phone,
				g.name,
				e.name server,
				e2.name accounting,
				t.nation,
				t.local,
				t.tax_count_id,
				t.year,
				t.month
				from 
				`guest` g,
				`employee` e,
				`employee` e2,
				`accounting`a,
				`tax_count` t
				where g.guest_id = a.guest_id
				and t.guest_id = a.guest_id
				and g.employee_id = e.employee_id
				and a.status = 1
				and a.employee_id = e2.employee_id
				';

			if(!empty($params['year'])){
				$sql = $sql . " and t.year =:year";
			}else{
				unset($params['year']);
			}
			if(!empty($params['month'])){
				$sql = $sql . " and t.month =:month";
			}else{
				unset($params['month']);
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
			if(!empty($params['department_id'])){
				$sql = $sql . " and e.department_id=:department_id";
			}else{
				unset($params['department_id']);
			}
			if(!empty($params['employee_id'])){
				$sql = $sql . " and e.employee_id=:employee_id";
			}else{
				unset($params['employee_id']);
			}
			$result = $this->db->query($sql,$params,$page);
			if($result){
				return array("total"=>$this->db->count,'data'=>$result);
			}else{
				return array();
			}
		}
		function admin_findByDate($params,$page){
			$sql = 'select 
				g.guest_id,
				g.company,
				g.phone,
				g.name,
				e.name server,
				e2.name accounting,
				t.nation,
				t.local,
				t.tax_count_id,
				t.year,
				t.month
				from 
				`guest` g,
				`employee` e,
				`employee` e2,
				`accounting`a,
				`tax_count` t
				where g.guest_id = a.guest_id
				and t.guest_id = a.guest_id
				and g.employee_id = e.employee_id
				and a.status = 1
				and a.employee_id = e2.employee_id
				';

			if(!empty($params['year'])){
				$sql = $sql . " and t.year =:year";
			}else{
				unset($params['year']);
			}
			if(!empty($params['month'])){
				$sql = $sql . " and t.month =:month";
			}else{
				unset($params['month']);
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
			
			if(!empty($params['employee_id'])){
				$sql = $sql . " and (e2.employee_id=:employee_id or e.employee_id=:employee_id)";
			}else{
				unset($params['employee_id']);
			}
			$result = $this->db->query($sql,$params,$page);
			if($result){
				return array("total"=>$this->db->count,'data'=>$result);
			}else{
				return array();
			}
		}

		function findByDate($params,$page){
			$sql = 'select 
				g.guest_id,
				g.company,
				g.phone,
				g.name,
				e.name server,
				e2.name accounting,
				t.nation,
				t.local,
				t.tax_count_id,
				t.year,
				t.month
				from 
				`guest` g,
				`employee` e,
				`employee` e2,
				`accounting`a,
				`tax_count` t
				where g.guest_id = a.guest_id
				and t.guest_id = a.guest_id
				and g.employee_id = e.employee_id
				and a.status = 1
				and a.employee_id = e2.employee_id
				';

			if(!empty($params['year'])){
				$sql = $sql . " and t.year =:year";
			}else{
				unset($params['year']);
			}
			if(!empty($params['month'])){
				$sql = $sql . " and t.month =:month";
			}else{
				unset($params['month']);
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

			$result = $this->db->query($sql,$params,$page);
			if($result){
				return array("total"=>$this->db->count,'data'=>$result);
			}else{
				return array();
			}
		}

		function server_find($params,$page){
			$sql = 'select 
				g.guest_id,
				g.company,
				g.phone,
				g.name,
				e.name server,
				e2.name accounting,
				s.nation,
				s.local,
				s.tax_count_id,
				s.year,
				s.month
				from 
				`guest` g,
				`employee` e,
				`employee` e2,
				`accounting`a
				left join (
				select * from (select tax_count_id ,guest_id,year,month,nation,local from
				`tax_count` as a where year = (
				select max(b.year) from `tax_count` as b
				where b.guest_id = a.guest_id
				) order by month desc) as c group by c.guest_id
				)as s
				on s.guest_id = a.guest_id
				where g.guest_id = a.guest_id
				and g.employee_id = e.employee_id
				and a.employee_id = e2.employee_id
				and a.status = 1
				and e.employee_id=:employee_id
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
			$sql = $sql . " order by s.nation asc";

			$result = $this->db->query($sql,$params,$page);
			if($result){
				return array('total'=>$this->db->count,'data'=>$result);
			}else{
				return array();
			}
		}

		function accounting_find($params,$page){
			$sql = 'select 
				g.guest_id,
				g.company,
				g.phone,
				g.name,
				e.name server,
				e2.name accounting,
				s.nation,
				s.local,
				s.tax_count_id,
				s.year,
				s.month
				from 
				`guest` g,
				`employee` e,
				`employee` e2,
				`accounting`a
				left join (
				select * from (select tax_count_id ,guest_id,year,month,nation,local from
				`tax_count` as a where year = (
				select max(b.year) from `tax_count` as b
				where b.guest_id = a.guest_id
				) order by month desc) as c group by c.guest_id
				)as s
				on s.guest_id = a.guest_id
				where g.guest_id = a.guest_id
				and g.employee_id = e.employee_id
				and a.employee_id = e2.employee_id
				and a.status = 1
				and a.employee_id=:employee_id
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
			$sql = $sql . " order by s.nation asc";
			
			$result = $this->db->query($sql,$params,$page);
			if($result){
				return array('total'=>$this->db->count,'data'=>$result);
			}else{
				return array();
			}
		}

		function server_admin_find($params,$page){
			$sql = 'select 
				g.guest_id,
				g.company,
				g.phone,
				g.name,
				e.name server,
				e2.name accounting,
				s.nation,
				s.local,
				s.tax_count_id,
				s.year,
				s.month
				from 
				`guest` g,
				`employee` e,
				`employee` e2,
				`accounting`a
				left join (
				select * from (select tax_count_id ,guest_id,year,month,nation,local from
				`tax_count` as a where year = (
				select max(b.year) from `tax_count` as b
				where b.guest_id = a.guest_id
				) order by month desc) as c group by c.guest_id
				)as s
				on s.guest_id = a.guest_id
				where g.guest_id = a.guest_id
				and g.employee_id = e.employee_id
				and a.status = 1
				and a.employee_id = e2.employee_id
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
			if(!empty($params['department_id'])){
				$sql = $sql . " and e.department_id=:department_id";
			}else{
				unset($params['department_id']);
			}
			if(!empty($params['employee_id'])){
				$sql = $sql . " and e.employee_id=:employee_id";
			}else{
				unset($params['employee_id']);
			}

			$sql = $sql . " order by s.nation asc";

			$result = $this->db->query($sql,$params,$page);
			if($result){
				return array('total'=>$this->db->count,'data'=>$result);
			}else{
				return array();
			}
		}

		function accounting_admin_find($params,$page){
			$sql = 'select 
				g.guest_id,
				g.company,
				g.phone,
				g.name,
				e.name server,
				e2.name accounting,
				s.nation,
				s.local,
				s.tax_count_id,
				s.year,
				s.month
				from 
				`guest` g,
				`employee` e,
				`employee` e2,
				`accounting`a
				left join (
				select * from (select tax_count_id ,guest_id,year,month,nation,local from
				`tax_count` as a where year = (
				select max(b.year) from `tax_count` as b
				where b.guest_id = a.guest_id
				) order by month desc) as c group by c.guest_id
				)as s
				on s.guest_id = a.guest_id
				where g.guest_id = a.guest_id
				and g.employee_id = e.employee_id
				and a.status = 1
				and a.employee_id = e2.employee_id
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
			if(!empty($params['department_id'])){
				$sql = $sql . " and e2.department_id=:department_id";
			}else{
				unset($params['department_id']);
			}
			if(!empty($params['employee_id'])){
				$sql = $sql . " and e2.employee_id=:employee_id";
			}else{
				unset($params['employee_id']);
			}

			$sql = $sql . " order by s.nation asc";

			$result = $this->db->query($sql,$params,$page);
			if($result){
				return array('total'=>$this->db->count,'data'=>$result);
			}else{
				return array();
			}
		}

		function admin_find($params,$page){
			$sql = 'select 
				g.guest_id,
				g.company,
				g.phone,
				g.name,
				e.name server,
				e2.name accounting,
				s.nation,
				s.local,
				s.tax_count_id,
				s.year,
				s.month
				from 
				`guest` g,
				`employee` e,
				`employee` e2,
				`accounting`a
				left join (
				select * from (select tax_count_id ,guest_id,year,month,nation,local from
				`tax_count` as a where year = (
				select max(b.year) from `tax_count` as b
				where b.guest_id = a.guest_id
				) order by month desc) as c group by c.guest_id
				)as s
				on s.guest_id = a.guest_id
				where g.guest_id = a.guest_id
				and g.employee_id = e.employee_id
				and a.status = 1
				and a.employee_id = e2.employee_id
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
				$sql = $sql . " and (e.employee_id=:employee_id or e2.employee_id=:employee_id)";
			}else{
				unset($params['employee_id']);
			}

			$sql = $sql . " order by s.nation asc";

			$result = $this->db->query($sql,$params,$page);
			if($result){
				return array('total'=>$this->db->count,'data'=>$result);
			}else{
				return array();
			}
		}

		function find($params,$page){
			$sql = 'select 
				g.guest_id,
				g.company,
				g.phone,
				g.name,
				e.name server,
				e2.name accounting,
				s.nation,
				s.local,
				s.tax_count_id,
				s.year,
				s.month
				from 
				`guest` g,
				`employee` e,
				`employee` e2,
				`accounting`a
				left join (
				select tax_count_id ,guest_id,year,month,nation,local from
				`tax_count` as a where year = (
				select max(b.year) from `tax_count` as b
				where b.guest_id = a.guest_id
				)
				and month = (
				select max(b.month) from `tax_count` as b
				where b.guest_id = a.guest_id
				)
				)as s
				on s.guest_id = a.guest_id
				where g.guest_id = a.guest_id
				and g.employee_id = e.employee_id
				and a.employee_id = e2.employee_id
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
			$sql = $sql . " order by s.nation asc";

			$result = $this->db->query($sql,$params,$page);
			if($result){
				return array('total'=>$this->db->count,'data'=>$result);
			}else{
				return array();
			}
		}

		function add($params){
			$result = $this->db->exec('insert into `tax_count` set
				guest_id=:guest_id,
				nation=:nation,
				local=:local,
				year=:year,
				month=:month,
				create_time=now()
			',$params);

			if($result){
				return $this->db->lastId();	
			}else{
				return false;
			}
		}

		function has($params){
			return $this->db->query("select tax_count_id from `tax_count`
				where year=:year
				and month=:month
				and guest_id=:guest_id
			",$params);
		}

		function update($params){
			return $this->db->exec('
				update
				`tax_count` t
				inner join 
				( 
				select 
				t.tax_count_id
				from
				`tax_count` t,
				`accounting` a,
				`employee` e
				where
				t.guest_id = a.guest_id and
				e.employee_id = a.employee_id and
				e.employee_id = :employee_id and
				t.tax_count_id = :tax_count_id and
				t.guest_id = :guest_id
				) s
				on 
				s.tax_count_id = t.tax_count_id
				set
				t.nation = :nation,
				t.local = :local
			',$params);
		}

		function delete($tax_count_id){
			return $this->db->exec('delete from `tax_count` where tax_count_id=?',$tax_count_id);
		}
	}
 ?>