var myapp = angular.module('myapp',['ngRoute']),
	_host = 'http://192.168.10.105/accountant/';

myapp.config(['$routeProvider',function($routeProvider){
	$routeProvider
	.when('/',{
		templateUrl:'static/dashboard.html',
		controller:'dashboardCtrl'
	})
	.when('/add_user',{
		templateUrl:'static/add_user.html'
	})
	.when('/users',{
		templateUrl:'static/users.html',
		controller:'userCtrl'
	})
	.when('/process_group',{
		templateUrl:'static/process_group.html',
		controller:'processGroupCtrl'
	})
	.when('/process',{
		templateUrl:'static/process.html',
		controller:'processCtrl'
	})
	.when('/business',{
		templateUrl:'static/business.html',
		controller:'businessCtrl'
	})
	.when('/accounting',{
		templateUrl:'static/accounting.html',
		controller:'accountingCtrl'
	})
	.when('/tax',{
		templateUrl:'static/tax.html',
		controller:'taxCtrl'
	})
	.when('/department',{
		templateUrl:'static/department.html',
		controller:'departmentCtrl'
	})
	.when('/roles',{
		templateUrl:'static/roles.html',
		controller:'rolesCtrl'
	})
	.when('/resource',{
		templateUrl:'static/resource.html',
		controller:'resourceCtrl'
	})
	.when('/account',{
		templateUrl:'static/account.html',
		controller:'accountCtrl'
	})
	.when('/tax_type',{
		templateUrl:'static/tax_type.html',
		controller:'taxTypeCtrl'
	})
	.when('/pay_record',{
		templateUrl:'static/pay_record.html',
		controller:'payrecordCtrl'
	})
	.when('/todo',{
		templateUrl:'static/todo.html',
		controller:'todoCtrl'
	});
}]);

myapp.service('dashboardService',function($http){
	var obj = {
		getCount:function(fn){
			$post($http,_host+"dashboard/findCount",{'employee_id':obj.employee_id}).success(function(r){
				fn(r);
			});
		},
		getIng:function(fn){
			$post($http,_host+"dashboard/findIng",{'employee_id':obj.employee_id}).success(function(r){
				fn(r);
			});
		},
		getLose:function(fn){
			$post($http,_host+"dashboard/findLose",{'employee_id':obj.employee_id}).success(function(r){
				fn(r);
			});
		},
		getDeal:function(fn){
			$post($http,_host+"dashboard/findDeal",{'employee_id':obj.employee_id}).success(function(r){
				fn(r);
			});
		},
		getDepartment:function(fn){
			$post($http,_host+"department/findByEmpolyee",{'employee_id':obj.employee_id}).success(function(r){
				fn(r);
			});
		},
		getTodo:function(http){
			return [{
				todo_id:1,
				sender:'乔钱',
				accepter:'赵光杰',
				task:'刻章',
				date_start:'2015-02-23',
				date_end:'2015-02-25'
			},{
				todo_id:2,
				sender:'乔钱',
				accepter:'赵光杰',
				task:'还是刻章',
				date_start:'2015-02-23',
				date_end:'2015-02-25'
			}];
		},
		getEarly:function(http,fn){
			return function(){
				var data = [{
					todo_id:2,
					sender:'拱辰熙',
					accepter:'赵光杰',
					task:'刻章',
					date_start:'2015-02-23',
					date_end:'2015-02-25'
				}];
				fn(data);
			}
		}
	};
	return obj;
});
myapp.controller('dashboardCtrl',function($scope,$http,dashboardService){

	$scope.todos = dashboardService.getTodo($http);

	//get session
	$post($http,_host+"employee/session",{}).success(function(r){
		if(r != 'false'){
			$scope.name = r.user.name;
			dashboardService.employee_id = r.user.employee_id;
			dashboardService.department = '客服';
			dashboardService.getCount(function(r){
				$scope.count = r.count;
			});

			dashboardService.getDeal(function(r){
				$scope.deal = r.count;
			});

			dashboardService.getIng(function(r){
				$scope.ing = r.count;
			});

			dashboardService.getLose(function(r){
				$scope.lose = r.count;
			});

			dashboardService.getDepartment(function(r){
				$scope.department = r.name;
			});


		}
	});



	$scope.todo_count = $scope.todos.length;
	if($scope.todo_count > 0){
		$scope.show_todo = true;
	}
	$scope.getEarly = dashboardService.getEarly($http,function(data){
		$scope.todo_early = data;
		$scope.todo_count_early = data.length;
		if(data.length > 0){
			$scope.show_todo_early = true;
		}
	});
});
/*实时查询*/
myapp.directive('searchUsersRealTime',function($http,$route){
	return {
		restrict: 'A',
	    link: function(scope, ele, attrs) {
	    	ele.bind('keyup',function(e){
	    		var key = e.keyCode;
	    		if(key == 40 || key == 38 || key == 13){
	    			var ul = $("#active-ul");
		    		var lis = ul.children();
		    		var len = lis.length;

		    		if(len > 0){
		    			var active = ul.find('.active');
		    			var index = $(active).index();
		    			if(key == '40'){//down
		    				if(index < len -1){
		    					lis.removeClass('active');
		    					lis.eq(index+1).addClass('active');
		    				}
			    		}
			    		if(key == '38'){//up
			    			if(index > 0){
			    				lis.removeClass('active');
			    				lis.eq(index-1).addClass('active');
			    			}
			    		}
			    		if(key == '13'){//enter
			    			var value = $(ele).val();
			    			if(value){
			    				$(active).trigger('click');	
			    			}else{
			    				$route.reload();
			    			}
			    		}
		    		}
	    		}else{
	    			var str = scope.searchstr;
		    		if(str.match(/^\d+$/)){
		    			//按手机号查询
		    			$post($http,_host+"guest/search",{"phone":str}).success(function(r){
		    				if(r){
		    					scope.results = r;
		    				}else{
		    					scope.results = [{}];
		    				}
		    			});
		    		}else if(str.match(/^[^\d]+$/)){
		    			//默认为按姓名查询
		    			$post($http,_host+"guest/search",{"name":str}).success(function(r){
		    				// console.log(r);
		    				if(r){
		    					scope.results = r;
		    				}else{
		    					scope.results = [{}];
		    				}
		    			});
		    		}
		    		if(typeof scope.results == 'undefined' || scope.results.length == 0){
		    			scope.results = [{}];
		    		}
		    		if(scope.results.length > 0){
		    			$("#active-ul").first().addClass('active');
		    		}
		    		scope.$apply();
	    		}
	    	});
	    	ele.bind('click',function(){
	    		scope.searchstr = '';
	    		scope.$apply();
	    		//send
	    	});
	    }
	};
});

myapp.directive('sureSearchResult',function(userService,$http){
	return {
		restrict:'A',
		link:function(scope, ele, attrs){
			ele.bind('click',function(){
				var user_id = attrs.id;
				scope.$parent.searchstr = scope.r.name;
				scope.$parent.results = [{}];
				$.post(_host+"guest/findbyid",{'guest_id':user_id},function(r){
					// console.log(r);
					r = eval("("+r+")");
					scope.$parent.guests = r.data;
					scope.$parent.$apply();
				});
				// (function(scope){
				// 	$post($http,_host+"guest/findbyid",{'guest_id':user_id}).success(function(r){
				// 		// scope.$parent.guests = r.data;
				// 		// scope.$parent.$apply();
				// 		console.log(scope);
				// 	});
				// })(scope);
				//search
			});
		}
	};
});

/*请求所有的区*/
myapp.directive('requestArea',function($http){
	var obj = '';
	return {
		restrict:'A',
		link:function(scope,ele,attrs){
			$post($http,_host+"area/findall",{}).success(function(r){
				if(r){
					scope.areas = r;
				}
			});
		}
	};
});
/*请求所有客服*/
myapp.directive('requestServer',function(){
	return {
		restrict:'A',
		link:function(scope,ele,attrs){
			ele.bind('focus',function(){
				if(true){
					scope.servers = [{
						'server_id':1,
						'name':'all servers'
					}];
					scope.$apply();
				}
			});
		}
	};
});
/*请求所有会计*/
myapp.directive('requestAccounting',function(){
	return {
		restrict:'A',
		link:function(scope,ele,attrs){
			ele.bind('focus',function(){
				if(true){
					scope.accountings = [{
						'accounting_id':1,
						'name':'all accountings'
					}];
					scope.$apply();
				}
			});
		}
	};
});
/*请求工商所有流程*/
myapp.directive('requestBusinessProcess',function(){
	return {
		restrict:'A',
		link:function(scope,ele,attrs){
			ele.bind('focus',function(){
				scope.processes = [{
					'id':1,
					'name':'完成'
				},{
					'id':9,
					'name':'延期'
				}];
				scope.$apply();
			});
		}
	};
});
/*请求所有部门*/
myapp.directive('requestDepartment',function(){
	return {
		restrict:'A',
		link:function(scope,ele,attrs){
			ele.bind('focus',function(){
				if(!scope.department){
					scope.department = [{
						id:1,
						name:'客服部'
					},{
						id:2,
						name:'财务部'
					}];
					scope.$apply();
				}
			});
		}
	};
});
myapp.service('processGroupService',function(){
	var obj = {
		get:function(http,fn){
			$post(http,_host+"processgroup/find",{}).success(function(r){
				if(r){
					obj.data = r;
					fn();
				}
			});
		},
		add:function(http,scope,fn){
			return function(othis){
				var name = scope.add_name;
				if(typeof name == 'undefined' || name.length < 1){
					layer.msg('字符太短');
					return;
				}
				layer.load();
				$post(http,_host+"processgroup/save",{
					'name':name
				}).success(function(r){
					layer.closeAll('loading');
					if(r != 'false'){
						obj.data.splice(0,0,r);
						fn(othis);
					}else{
						layer.msg('不能重复');
					}
				});
			};
		},
		update:function(http,scope,fn){
			return function(othis){
				var name = scope.edit_name;
				if(typeof name == 'undefined' || name.length < 1){
					layer.msg('字符太短');
					return;
				}
				$post(http,_host+"processgroup/update",{
					'process_group_id':obj.select_id,
					'name':name
				}).success(function(r){
					if(r != 'false'){
						obj.data.forEach(function(ele,index){
							if(ele.process_group_id == r.process_group_id){
								obj.data[index] = r;
							}
						});
						fn(othis);
					}else{
						layer.msg('不能重复');
					}
				});
			};
		},
		click:function(ele){
			var othis = this;
			var name = othis.process_group_name;
			if(!validate('name',name)){
				layer.msg('名称有误',function(){});
			}else{
				layer.load();
				//send
				setTimeout(function(){
					layer.closeAll('loading');
					othis.process_group_name = '123';
					$(ele).prev().trigger('click');
					//刷新数据
					othis.$broadcast('refresh');
				},1000);
			}
		},
		delete:function(http,fn){
			return function(othis){
				layer.load();
				$post(http,_host+"processgroup/delete",{'process_group_id':obj.select_id}).success(function(r){
					layer.closeAll('loading');
					if(r == 1){
						var arr = [];
						obj.data.forEach(function(ele,index){
							if(ele.process_group_id != obj.select_id){
								arr.push(ele);
							}
						});
						obj.data = arr;
						fn(othis);
					}else{
						layer.msg('删除失败');
					}
				});
			};
		}
	};
	return obj;
});

myapp.controller('processGroupCtrl',function($scope,$http,processGroupService){

	$scope.get = processGroupService.get($http,function(){
		$scope.process_groups = processGroupService.data;
	});

	$scope.log_group = function(){
		processGroupService.select_id = this.u.process_group_id;
		$scope.edit_name = this.u.name;
	};

	$scope.edit = processGroupService.update($http,$scope,function(ele){
		if(ele.nodeName == 'I'){
			$(ele).parent().prev().trigger('click');
		}else{
			$(ele).prev().trigger('click');
		}
		$scope.process_groups = processGroupService.data;
	});

	$scope.add = processGroupService.add($http,$scope,function(ele){
		if(ele.nodeName == 'I'){
			$(ele).parent().prev().trigger('click');
		}else{
			$(ele).prev().trigger('click');
		}
		$scope.process_groups = processGroupService.data;
	});

	$scope.delete = processGroupService.delete($http,function(ele){
		$(ele).next().trigger('click');
		$scope.process_groups = processGroupService.data;
	});
});

myapp.service('userService',function($http){
	var obj = {
		data:[],
		get:function(http,params,fn){
			$post(http,_host+"guest/find",params).success(function(r){
				obj.data = r.data;
				fn(r);
			});
		},
		deleteGuest:function(fn){
			$post($http,_host+"guest/delete",{'guest_id':obj.guest_id}).success(function(r){
				if(r){
					var arr = [];
					obj.data.forEach(function(ele,index){
						if(ele.guest_id != obj.guest_id){
							arr.push(ele);
						}
					});
					obj.data = arr;
					fn();
				}
			});
		},
		addGuest:function(scope,fn){
			var company = scope.add_company,
				name = scope.add_name,
				phone = scope.add_phone,
				tel = scope.add_tel,
				job = scope.add_job,
				area_id = scope.add_area_id,
				address = scope.add_address,
				status = scope.add_status,
				resource_id = scope.add_resource_id;

			if(!validate('name',name)){
				layer.msg('姓名不正确',function(){});
				return;
			}
			if(!validate('phone',phone) && !validate('tel',tel)){
				layer.msg('电话不正确',function(){});
				return;
			}
			layer.load();
			$post($http,_host+"guest/save",{
				'company':company,
				'name':name,
				'phone':phone,
				'tel':tel,
				'area_id':area_id,
				'job':job,
				'address':address,
				'resource_id':resource_id,
				'status':status
			}).success(function(r){
				layer.closeAll('loading');
				if(r != 'false'){
					if(obj.data){
						obj.data.splice(0,0,r);	
					}else{
						obj.data = [r];
					}
					fn();
				}else{
					layer.msg('添加失败');
				}
			});
		},
		editGuest:function(scope,fn){
			var phone = scope.edit_phone,
				name = scope.edit_name,
				tel = scope.edit_tel;

			if(!validate('name',name)){
				layer.msg('姓名不正确',function(){});
				return;
			}
			if(!validate('phone',phone) && !validate('tel',tel)){
				layer.msg('电话不正确',function(){});
				return;
			}
			layer.load();
			$post($http,_host+"guest/update",{
				'guest_id':obj.guest_id,
				'company':scope.edit_company,
				'name':name,
				'phone':phone,
				'tel':tel,
				'area_id':scope.edit_area_id,
				'status':scope.edit_status,
				'address':scope.edit_address
			}).success(function(r){
				layer.closeAll('loading');
				if(r){
					obj.data.forEach(function(ele,index){
						if(ele['guest_id'] == r.guest_id){
							obj.data[index] = r;
						}
					});
					fn();
				}
			});
		},
		showFollowDetail:function(guest_id,fn){
			$post($http,_host+"record/find",{"guest_id":guest_id}).success(function(r){
				if(r){
					obj.follow_record = r;
					fn();
				}
			});
		},
		addRecord:function(scope,fn){
			if(typeof scope.add_record == 'undefined' || scope.add_record.length < 2){
				layer.msg('字符太短');
				return;
			}
			
			layer.load();
			$post($http,_host+"record/save",{'guest_id':obj.guest_id,'content':scope.add_record}).success(function(r){
				layer.closeAll('loading');
				if(r){
					if(obj.follow_record){
						obj.follow_record.splice(0,0,r);
					}else{
						obj.follow_record = [r];
					}
					fn();
				}
			});
		},
		initResource:function(http,fn){
			$post(http,_host+"resource/findAll",{}).success(function(r){
				if(r){
					fn(r);
				}
			});
		},
		deleteRecord:function(record_id,fn){
			$post($http,_host+"record/delete",{'record_id':record_id}).success(function(r){
				if(r == 1){
					var arr = [];
					obj.follow_record.forEach(function(ele,index){
						if(ele.record_id != record_id){
							arr.push(ele);
						}
					});
					obj.follow_record = arr;
					fn();
				}
			});
		}
	};
	return obj;
});

myapp.controller('userCtrl',function($scope,$http,userService,businessService,accountingService){

	userService.rightwin = false;

	$scope.initResource = userService.initResource($http,function(data){
		$scope.resource = data;
	});

	userService.get($http,{page:1,pageNum:10},function(r){
		$scope.guests = r.data;
		if(r.total > 1){
			// var arr = ['<'];
			var arr = [];
			for(var i = 0;i<r.total;i++){
				arr.push(i+1);
			}
			// arr.push('>');
			$scope.pagination = arr;
			// var pageLi = $('.pagination').children();
			// if(pageLi[1]){
			// 	console.log($(pageLi[1]).children());
			// 	$(pageLi[1]).children().addClass('active');
			// }
		}
	});
	//按页码获取
	$scope.getByPage = function(othis){
		userService.get($http,{page:this.p,pageNum:10},function(r){
			$scope.guests = r.data;
			$(othis).addClass('active');
		});
	};

	//添加新用户回调
	$scope.addGuest = function(othis){
		userService.addGuest($scope,function(){
			for(s in $scope){
				var x = s.toString();
				if(x.indexOf('add_') > -1){
					$scope[s] = '';	
				}
			}
			$(othis).prev().trigger('click');
			$scope.guests = userService.data;
		});
	};
	$scope.initGuestid = function(){
		userService.initGuestid = this.u.guest_id;
	};
	//添加跟进记录
	$scope.addRecord = function(othis){
		userService.addRecord($scope,function(){
			$scope.follow_record = userService.follow_record;
			$(othis).prev().trigger('click');
		});
	};
	
	//编辑用户
	$scope.editGuest = function(ele){
		userService.editGuest($scope,function(){
			if(ele.nodeName == 'I'){
				$(ele).parent().prev().trigger('click');
			}else{
				$(ele).prev().trigger('click');
			}
			$scope.guests = userService.data;
			closeright();
		});
	};
	
	$scope.initBusiness = businessService.initBusiness($http,$scope,function(r){
		$scope.process_group = r;
	});
	
	$scope.requestEmployee = businessService.requestEmployee($http,$scope,function(r){
		$scope.employee = r;
	});
	//添加工商
	$scope.addBusiness = function(ele){
		businessService.add($scope,function(){
			if(ele.nodeName == 'I'){
				$(ele).parent().prev().trigger('click');
			}else{
				$(ele).prev().trigger('click');
			}
		});
	};
	$scope.log_guest = function(){
		userService.select_id = this.u.guest_id;
		$scope.select_id = userService.select_id;
		// console.log(userService.select_id);
	};
	$scope.initRight = function(u){

		businessService.guest_id = u.guest_id;

		if(userService.guest_id != u.guest_id){
			callright(function(){
				userService.rightwin = true;
			});
			$scope.intro = '客户详细信息';
			$scope.company = u.company;
			$scope.name = u.name;

			/*编辑客服初始化*/
			$scope.edit_name = u.name;
			$scope.edit_company = u.company;
			$scope.edit_phone = u.phone;
			$scope.edit_tel = u.tel;
			$scope.edit_address = u.address;
			$scope.edit_status = u.status;
			$scope.edit_area_id = u.area_id;

			userService.showFollowDetail(u.guest_id,function(){
				$scope.follow_record = userService.follow_record;
			});
			userService.guest_id = u.guest_id;
			businessService.findOpen(u.guest_id,function(r){
				$scope.opens = r;
			});
		}else{
			if(userService.rightwin == true){
				closeright(function(){
					userService.rightwin = false;
				});
			}else{
				callright(function(){
					userService.rightwin = true;
				});
				businessService.findOpen(u.guest_id,function(r){
					$scope.opens = r;
				});
			}
		}
	};

	$scope.deleteRecord = function(record_id){
		userService.deleteRecord(record_id,function(){
			$scope.follow_record = userService.follow_record;
		});
	};

	$scope.deleteGuest = function(){
		userService.deleteGuest(function(){
			$scope.guests = userService.data;
			closeright();
		});
	};

	//添加代理记账任务
	$scope.addAccounting = function(ele){
		var employee_id = $scope.accounting_employee_id;
		if(employee_id){
			accountingService.add(userService.guest_id,employee_id,function(){
				$(ele).prev().trigger('click');
			});
		}else{
			layer.msg('必须指定负责人');
		}
	};
});


myapp.service('processService',function($http){
	var obj = {
		process_group:'',
		add:function(http,scope,fn){
			return function(othis){
				if(scope.process_group_id == 0){
					layer.msg('错误');
					return;
				}
				var inputs = $('#add-process-input').find('input');
				var arr = [];
				for(var i = 0,len = inputs.length;i<len;i++){
					var value = inputs.eq(i).val();
					if(typeof value != 'undefined') value = value.trim();
					if(value.length > 0){
						arr.push(value);
					}
				}
				if(arr.length == 0){
					layer.msg('不能为空');
				}else{
					layer.load();
					$post(http,_host+"process/save",{'process_group_id':scope.process_group_id,'values':arr}).success(function(r){
						layer.closeAll('loading');
						if(r != 'false'){
							obj.data = r;
							fn(othis);
						}
					});
				}
			};
		},
		get:function(http,fn){
			$post(http,_host+"process/find",{}).success(function(r){
				if(r != 'false'){
					obj.data = r;
					fn();
				}
			});
		},
		edit:function(http,fn){
			return function(othis){
				var inputs = $('#edit-process').find('input');
				var arr = [];
				for(var i = 0,len = inputs.length;i<len;i++){
					var input = $(inputs[i]);
					if(input.val()){
						var id = input.attr('data-id') || 0;
						arr.push({
							'process_id':id,
							'name':input.val()
						});
					}
				}
				$post(http,_host+"process/update",{'process_group_id':obj.select_id,'values':arr}).success(function(r){
					if(r != 'false'){
						obj.data = r;
						fn(othis);
					}
				});
			}
		},
		requestProcessGroup:function(http,fn){
			if(!obj.process_group){
				$post(http,_host+"processgroup/find",{}).success(function(r){
					if(r){
						obj.process_group = r;
						fn();
					}
				});
			}else{
				fn();
			}
		},
		//根据process_group_id请求process的name
		getByGroupid:function(process_group_id,fn){
			$post($http,_host+"process/getList",{'process_group_id':process_group_id}).success(function(r){
				if(r) fn(r);
			});
		}
	};
	return obj;
});

myapp.controller('processCtrl',function($scope,$http,processService){

	processService.get($http,function(){
		$scope.processes = processService.data;
	});


	$scope.requestProcessGroup = processService.requestProcessGroup($http,function(){
		$scope.process_group = processService.process_group;
	});

	$scope.add = processService.add($http,$scope,function(ele){
		if(ele.nodeName == 'I'){
			$(ele).parent().prev().trigger('click');
		}else{
			$(ele).prev().trigger('click');
		}
		$scope.processes = processService.data;
	});

	$scope.log_process = function(){

		processService.select_id = this.u.process_group_id;

		$post($http,_host+"process/getList",{'process_group_id':processService.select_id}).success(function(r){
			if(r != 'false'){
				$scope.edit_process = r;
			}
		});
	};

	$scope.edit = processService.edit($http,function(ele){
		if(ele.nodeName == 'I'){
			$(ele).parent().prev().trigger('click');
		}else{
			$(ele).prev().trigger('click');
		}
		$scope.processes = processService.data;
	});

	$scope.delete = function(ele){
		layer.load();
		$post($http,_host+"process/delete",{'process_id':this.e.process_id}).success(function(r){
			layer.closeAll('loading');
			if(r != 'false'){
				if(ele.nodeName == "I"){
					$(ele).parent().parent().remove();
				}else{
					$(ele).parent().remove();
				}
				$scope.processes = r;
			}
		});
	};
});

myapp.controller('editProcessCtrl',function($scope,$http){
	$scope.processes = [3,4];
});

myapp.service('progressService',function($http){
	var obj = {
		data:[],
		add:function(scope,fn){
			return function(othis){
				var process_id = scope.process_id,
					date_end = $("#finish_date_end").val(),
					note = scope.note;

				if(typeof process_id == 'undefined' || typeof date_end == 'undefined' || typeof note == 'undefined' || date_end.length < 5 || note.length < 3){
					layer.msg('字符太短');
					return;
				}
				// console.log(process_id,date_end,note,obj.select_id);
				layer.load();
				$post($http,_host+"progress/save",{
					'process_id':process_id,
					'date_end':date_end,
					'business_id':obj.select_id,
					'note':note
				}).success(function(r){
					layer.closeAll('loading');
					fn(othis);
					layer.msg('添加成功');
				});
			};
		},
		get:function(business_id,fn){
			$post($http,_host+"progress/find",{'business_id':business_id}).success(function(r){
				if(r){
					fn(r);
				}
			});
		},
		delete:function(){

		}
	};
	return obj;
});

myapp.service('businessService',function($http){
	var obj = {
		get:function(fn){
			$post($http,_host+"business/find",{}).success(function(r){
				if(r != 'false'){
					obj.data = r;
					fn();
				}
			});
		},
		add:function(scope,fn){
			var process_group_id = scope.process_group_id,
				employee_id = scope.employee_id,
				should_fee = scope.should_fee,
				have_fee = scope.have_fee;

			if(obj.guest_id == 0 || process_group_id == 0 || employee_id == 0 || should_fee == 0 || have_fee == 0){
				layer.msg('必填项不能为空');
				return;
			}
			// console.log(obj.guest_id,process_group_id,employee_id,should_fee,have_fee);
			// return;
			layer.load();
			$post($http,_host+"business/save",{
				'guest_id':obj.guest_id,
				'process_group_id':process_group_id,
				'employee_id':employee_id,
				'should_fee':should_fee,
				'have_fee':have_fee
			}).success(function(r){
				layer.closeAll('loading');
				if(r == 1){
					layer.msg('添加工商成功');
					fn();
				}else{
					layer.msg("添加工商失败");
				}
			});
		},
		requestEmployee:function(http,scope,fn){
			return function(){
				$post(http,_host+"employee/findByDepartmentid",{'department_id':scope.department_id}).success(function(r){
					if(r) fn(r);
				});
			};
		},
		// requestProcess:function(http,scope,fn){
		// 	return function(){
		// 		$post(http,_host+"process/getList",{'process_group_id':scope.process_group_id}).success(function(r){
		// 			fn(r);
		// 			console.log(r);
		// 		});
		// 	};
		// },
		initBusiness:function(http,scope,fn){
			//业务类型
			$post(http,_host+"processgroup/find",{}).success(function(r){
				if(r){
					scope.process_group = r;
				}
			});
			$post(http,_host+"department/findAll",{}).success(function(r){
				if(r){
					scope.department = r;
				}
			});
		},
		update:function($scope){
			$scope.accept = 1;
		},
		updateStatus:function(http,business_id){
			$post(http,_host+"business/updateStatus",{'status':1,'business_id':business_id});
		},
		findOpen:function(guest_id,fn){
			$post($http,_host+"business/findOpen",{'guest_id':guest_id}).success(function(r){
				if(r) fn(r);
			});
		}
	};
	return obj;
});


myapp.controller('businessCtrl',function($scope,$http,businessService,progressService,processService){
	
	businessService.get(function(){
		$scope.business = businessService.data;
	});

	$scope.updateStatus = function(business_id){
		if(this.u.status == 0){
			this.u.status = 1;
			businessService.updateStatus($http,business_id);
		}
	};

	// $scope.checkBusiness = businessService.addBusiness($scope,function(ele){
	// 	if(ele.nodeName == 'I'){
	// 		$(ele).parent().prev().trigger('click');
	// 	}else{
	// 		$(ele).prev().trigger('click');
	// 	}
	// });
	// $scope.toggleBusiness = businessService.updateAccept($scope);
	// $scope.checkBusinessProgress = businessService.addProgress($scope,function(ele){
	// 	if(ele.nodeName == 'I'){
	// 		$(ele).parent().prev().trigger('click');
	// 	}else{
	// 		$(ele).prev().trigger('click');
	// 	}
	// 	//清空scope上自定义参数
	// 	$scope.note = '';
	// 	$scope.$apply();		
	// });
	//添加进度
	$scope.addProgress = progressService.add($scope,function(ele){
		if(ele.nodeName == 'I'){
			$(ele).parent().prev().trigger('click');
		}else{
			$(ele).prev().trigger('click');
		}
	});
	//初始化进度
	$scope.requestProgress = function(business_id,process_group_id){
		progressService.select_id = business_id;
		progressService.get(business_id,function(r){
			$scope.progress = r;
		});
		processService.getByGroupid(process_group_id,function(r){
			$scope.process = r;
		});
	}
	//删除进度
	$scope.deleteProgress = function(othis,progress_id,business_id){
		// console.log(othis,progress_id,business_id);return;
		$post($http,_host+"progress/delete",{'progress_id':progress_id}).success(function(r){
			if(r == 1){
				$(othis).parent().remove();
			}
		});
	};

	
});

myapp.service('accountingService',function($http){
	var obj = {
		get:function(fn){
			$post($http,_host+"accounting/find",{'page':1,'pageNum':100}).success(function(r){
				if(r){
					obj.data = r;
					fn(r);
				}
			});
		},
		add:function(guest_id,employee_id,fn){
			$post($http,_host+"accounting/save",{'employee_id':employee_id,'guest_id':guest_id}).success(function(r){
				if(r == 1){
					layer.msg('添加成功');
					fn();
				}else{
					layer.msg('添加失败');
				}
			});
		},
		update:function($scope){
			return function(){
				$scope.accept = 1;
			};
			//send
		},
		delete:function($scope,fn){
			// var follow_accounting = $scope;
		},
		updateStatus:function(accounting_id){
			$post($http,_host+"accounting/updateStatus",{'accounting_id':accounting_id});
		}
	};
	return obj;
});
myapp.controller('accountingCtrl',function($scope,$http,accountingService){
	var now = new Date();
	$scope.today = now.getFullYear()+"-"+(now.getMonth()+1)+"-"+now.getDate();

	accountingService.get(function(){
		$scope.accounting = accountingService.data;
	});

	$scope.updateStatus = function(accounting_id){
		// console.log(accounting_id);
		if(this.u.status == 0){
			accountingService.updateStatus(accounting_id);
			this.u.status = 1;
		}
	};
});

myapp.service('taxService',function($http){
	var obj = {
		get:function(fn){
			$post($http,_host+"taxcollect/find",{}).success(function(r){
				if(r){
					obj.data = r;
					fn();
				}
			});
		},
		add:function(data,fn){
			$post($http,_host+"taxcollect/save",{'data':data}).success(function(r){
				console.log(r);
				// if(r != 'false'){
				// 	fn();
				// }else{
				// 	layer.msg('添加失败');
				// }
			});
		},
		initTaxtype:function(parent_id,fn){
			$post($http,_host+"taxtype/findByParentid",{'parent_id':parent_id}).success(function(r){
				if(r){
					fn(r);
				}else{
					layer.msg('没有详细记录');
				}
			});
		},
		findList:function(params,fn){
			$post($http,_host+"taxcollect/findList",params).success(function(r){
				if(r) fn(r);
			});
		}
	};
	return obj;
});

myapp.controller('taxCtrl',function($scope,taxService){

	taxService.get(function(){
		$scope.tax = taxService.data;
	});

	$scope.closewin = function(){
		closeright(function(){
			taxService.rightwin = false;
		});
	};

	$scope.initTaxtype = function(){
		taxService.initTaxtype(1,function(r){
			$scope.nation = r;
		});
		taxService.initTaxtype(2,function(r){
			$scope.local = r;
		});
	};

	$scope.add = function(othis){
		var year = $scope.add_year,
			month = $scope.add_month,
			nation = $("#add-nation").find('.row'),
			local = $("#add-local").find('.row'),
			nationCount = $("#nation-count").val(),
			localCount = $("#local-count").val();

		
		if(!/^20\d{2}$/.test(year)){
			layer.msg('年份不正确');
			return;
		}
		if(!/\d{1,2}$/.test(month)){
			layer.msg('月份不正确');
			return;
		}
		if(!/(^\d+$)|(^\d{1,}[.]\d{1,}$)/.test(nationCount)){
			layer.msg('税总和不正确');
			return;
		}
		if(!/(^\d+$)|(^\d{1,}[.]\d{1,}$)/.test(localCount)){
			layer.msg('税总和不正确');
			return;
		}
		if(!taxService.guest_id){
			layer.msg('缺少guest_id');
			return;
		}

		var nation_arr = [];
		var local_arr = [];

		for(var i = 0,len = nation.length;i<len;i++){
			var obj = nation.eq(i);
			var tax_type_id = obj.find('select').val();
			var fee = obj.find('input').val();
			if(/^\d+$/.test(tax_type_id) && /(^\d+$)|(^\d{1,}[.]\d{1,}$)/.test(fee)){
				nation_arr.push({
					'tax_type_id':tax_type_id,
					'fee':fee	
				});
			}
		}

		for(var i = 0,len = local.length;i<len;i++){
			var obj = local.eq(i);
			var tax_type_id = obj.find('select').val();
			var fee = obj.find('input').val();
			if(/^\d+$/.test(tax_type_id) && /(^\d+$)|(^\d{1,}[.]\d{1,}$)/.test(fee)){
				local_arr.push({
					'tax_type_id':tax_type_id,
					'fee':fee
				});
			}
		}

		var data = {
			'guest_id':taxService.guest_id,
			'year':year,
			'month':month,
			'nationCount':nationCount,
			'localCount':localCount,
			'nationData':nation_arr,
			'localData':local_arr
		};

		taxService.add(data,function(){
			$(othis).prev().trigger('click');
		});
	}

	
	$scope.findList = function(){
		if(taxService.year != null && taxService.month != null){
			taxService.findList({'year':taxService.year,'month':taxService.month,'guest_id':taxService.guest_id},function(r){
				$scope.listshow = true;
				$scope.taxlist = r;
			});	
		}else{
			layer.msg('没有详细记录');
		}
	};

	$scope.initRight = function(u){
		// console.log(u);
		$scope.intro = u.company+"/"+u.name;
		taxService.year = u.year;
		taxService.month = u.month;
		taxService.guest_id = u.guest_id;

		if(taxService.guest_id != u.guest_id){
			callright(function(){
				taxService.rightwin = true;
			});
		}else{
			if(taxService.rightwin == true){
				closeright(function(){
					taxService.rightwin = false;
				});
			}else{
				callright(function(){
					taxService.rightwin = true;
				});
			}
		}

	};

});

myapp.service('todoService',function(){
	var obj = {
		"hasSelect":[],	
		todos:[],
		getTodo:function(http,params,fn){
			$post(http,_host+"todo/findAll",params).success(function(r){
				if(r){
					obj.todos = r.data;
					fn(r);	
				}
			});
		},
		getEmployee:function($scope,fn){
			return function(){
				var data = [{
					id:1,
					name:'翟晶辉'
				},{
					id:2,
					name:'王大明'
				}];
				if(typeof fn == 'function'){
					fn(data);
				}
			};
		}
	};
	return obj;
});
myapp.controller('todoCtrl',function($scope,$http,todoService){
	$scope.todos = todoService.getTodo();
	$scope.sendSubTodo = function(){
		$scope.copytask = this.u.task;
	};
	$scope.getTodo = todoService.getTodo($scope,function(r){
		$scope.todos = r.data;
		var id = $scope.employee_id;
	});

	$scope.hasSelect = function(othis){
		var that = this;
		if(othis.checked){
			todoService.hasSelect.push({
				id:that.u.id,
				name:that.u.name
			});
		}else{
			var newarr = [];
			for(var i = 0,len = todoService.hasSelect.length;i<len;i++){
				var obj = todoService.hasSelect[i];
				if(obj.id != that.u.id){
					newarr.push(obj);
				}
			}
			todoService.hasSelect = newarr;
		}
		$scope.hasSelects = todoService.hasSelect;

	};
});

myapp.service('employeeService',function($http){

	var obj = {
		get:function(params,fn){
			$post($http,_host+"employee/find",params).success(function(r){
				if(r){
					obj.data = r.data;
					fn();
				}
			});
		},
		getByDepartmentid:function(department_id,fn){
			$post($http,_host+"employee/findByDepartmentid",{'department_id':department_id}).success(function(r){
				if(r){
					obj.data = r;
					fn();
				}
			});
		},
		add:function(scope,fn){
			var name = scope.e_name,
				phone = scope.e_phone,
				sex = scope.e_sex,
				department_id = scope.e_department_id;

			if(!validate('name',name)){
				layer.msg('姓名不符合要求');
				return;
			}
			if(!validate('phone',phone)){
				layer.msg('电话不符合要求');
				return;
			}
			layer.load();
			$post($http,_host+"employee/save",{
				'name':name,
				'phone':phone,
				'sex':sex,
				'department_id':department_id
			}).success(function(r){
				layer.closeAll('loading');
				if(r){
					obj.data.splice(0,0,r);
					fn();
				}else{
					layer.msg('添加失败');
				}
			});
		},
		update:function(scope,fn){
			var name = scope.e_name,
				phone = scope.e_phone;

			if(!validate('name',name)){
				layer.msg('姓名不正确');
				return;
			}
			if(!validate('phone',phone)){
				layer.msg('电话不正确');
				return;
			}
			// console.log(name,phone,scope.e_sex,scope.e_department_id,obj.employee_id);
			// return;
			$post($http,_host+"employee/update",{
				'name':name,
				'phone':phone,
				'sex':scope.e_sex,
				'department_id':scope.e_department_id,
				'employee_id':obj.employee_id
			}).success(function(r){
				if(r){
					obj.data.forEach(function(ele,index){
						if(ele.employee_id == r.employee_id){
							obj.data[index] = r;
						}
					});
					fn();
				}
			});
		},
		updateStatus:function(fn){
			$post($http,_host+"employee/updateStatus",{'status':0,'employee_id':obj.employee_id}).success(function(r){
				if(r == 1){
					var arr = [];
					obj.data.forEach(function(ele,index){
						if(ele.employee_id != obj.employee_id){
							arr.push(ele);
						}
					});
					obj.data = arr;
					fn();
				}else{
					layer.msg('离职失败');
				}
			});
		}
		// delete:function(fn){
		// 	$post($http,_host+"employee/delete",{'employee_id':obj.employee_id}).success(function(r){
		// 		if(r == 1){
		// 			var arr = [];
		// 			obj.data.forEach(function(ele,index){
		// 				if(ele.employee_id != obj.employee_id){
		// 					arr.push(ele);
		// 				}
		// 			});
		// 			obj.data = arr;
		// 			fn();
		// 		}else{
		// 			layer.msg('删除失败');
		// 		}
		// 	});
		// }
	};
	return obj;
});

myapp.service('departmentService',function($http){
	var obj = {
		'data':[],
		edit:'',
		employees:[],
		select:{},
		active:'',
		add:function(scope,fn){
			var name = scope.add_name,
				parent_id = obj.edit || 0;

			if(!name || name.length < 1){
				layer.msg('字符太短',function(){});
				return;
			}
			layer.load();
			$post($http,_host+"department/save",{'name':name,'parent_id':parent_id}).success(function(r){
				layer.closeAll('loading');
				if(r != 'false'){
					fn();
				}else{
					layer.msg('添加失败');
				}
			});
		},
		update:function(scope,fn){
			var name = scope.edit_name,
					department_id = obj.edit;

			if(typeof department_id == 'undefined'){
				layer.msg('请先选定');
				return;
			}
			if(!name || name.length < 1){
				layer.msg('字符太短',function(){});
				return;
			}
			layer.load();
			$post($http,_host+"department/update",{'department_id':department_id,'name':name}).success(function(r){
				layer.closeAll('loading');
				if(r == 1){
					fn();
				}else{
					layer.msg('更新失败');
				}
			});
		},
		delete:function(fn){
			var department_id = obj.edit;
			if(typeof department_id == 'undefined'){
				layer.msg('必须选定内容');
			}else{
				$post($http,_host+"department/delete",{'department_id':department_id}).success(function(r){
					if(r == 1){
						fn();
					}else{
						layer.msg('删除失败');
					}
				});
			}
		},
		loadTree:function(fn){
			$post($http,_host+"department/findMenu",{}).success(function(r){
				if(r){
					obj.tree = r;
					fn();
				}
			});
		}
	};
	return obj;
});
myapp.controller('departmentCtrl',function($scope,$http,$route,departmentService,employeeService){

	//部门下拉列表
	function loadSelect(){
		$post($http,_host+"department/findAll",{}).success(function(r){
			$scope.alldepartment = r;
		});
	}

	loadSelect();

	$scope.initRight = function(u){
		if(employeeService.employee_id != u.employee_id){
			$scope.intro = u.d_name+"/"+u.name;
			callright(function(){
				employeeService.rightwin = true;
			});
			$scope.e_name = u.name;
			$scope.e_phone = u.phone;
			$scope.e_sex = u.sex;
			$scope.e_department_id = u.department_id;
			// console.log(u.department_id);
			employeeService.employee_id = u.employee_id;
		}else{
			if(employeeService.rightwin == true){
				closeright(function(){
					employeeService.rightwin = false;
				});
			}else{
				callright(function(){
					employeeService.rightwin = true;
				});
			}
		}
	};
	//初始化菜单
	function loadtree(){

		departmentService.loadTree(function(){

			var menu = departmentService.tree;

			$("#tree-show").empty().append(buildTree(menu,function(obj,span){
				departmentService.select = obj.department_id;
				if(!obj.sub){
					employeeService.getByDepartmentid(obj.department_id,function(){
						$scope.employees = employeeService.data;
					});
				}else{
					employeeService.get({page:1,pageNum:100},function(){
						$scope.employees = employeeService.data;
					});
				}
			}));
			
			$("#tree-edit").empty().append(buildTree(menu,function(obj,span){
				departmentService.edit = obj.department_id;
				$scope.head_name = obj.name;
				$scope.edit_name = obj.name;
			}));

		});	
	}

	loadtree();

	$scope.editEmployee = function(ele){
		employeeService.update($scope,function(){
			$scope.employees = employeeService.data;
			if(ele.nodeName == 'I'){
				$(ele).parent().prev().trigger('click');
			}else{
				$(ele).prev().trigger('click');
			}
			closeright(function(){
				employeeService.rightwin = false;
			});
		});
	};

	$scope.updateStatus = function(ele){
		layer.msg('确定设为离职？', {
		    time: 0
		    ,btn: ['确定', '取消']
		    ,yes: function(index){
		        layer.close(index);
				employeeService.updateStatus(function(){
					$scope.employees = employeeService.data;
					$(ele).next().trigger('click');
				});
				closeright(function(){
					employeeService.rightwin = false;
				});
		    }
		});
	};

	$scope.addEmployee = function(ele){
		employeeService.add($scope,function(){
			if(ele.nodeName == 'I'){
				$(ele).parent().prev().trigger('click');
			}else{
				$(ele).prev().trigger('click');
			}
			$scope.employees = employeeService.data;
		});
	};

	$scope.getEmployee = employeeService.get({'page':1,"pageNum":'20'},function(r){
		$scope.employees = employeeService.data;
		// var page_arr = [];
		// for(var i = 0;i<r.count;i++){
		// 	page_arr.push(i+1);
		// }
		// $scope.pagination = page_arr;
	});

	$scope.getByPage = function($http,fn){

	};

	$scope.showEditWindow = function(){
		if(departmentService.select){
			$("#call-edit").trigger('click');
		}else{
			layer.msg('选定才能编辑',function(){});
		}
	};

	$scope.edit = function(othis){
		departmentService.update($scope,function(){

			$(othis).prev().trigger('click');
			loadtree();

		});
	};

	$scope.add = function(ele){
		departmentService.add($scope,function(){

			if(ele.nodeName == 'I'){
				$(ele).parent().prev().trigger('click');
			}else{
				$(ele).prev().trigger('click');
			}

			$scope.add_name = "";

			loadtree();
		});
	}
	
	//呼出删除菜单
	$scope.showDeleteWindow = function(){
		if(departmentService.select){
			layer.msg('连同名下部门将一起删除，确定删除？', {
			    time: 0
			    ,btn: ['确定', '取消']
			    ,yes: function(index){
			    	layer.close(index);
			    	layer.load();
			    	departmentService.delete($http,function(){
			    		layer.closeAll('loading');
			    	});
			    }
			});
		}else{
			layer.msg('选定才能删除');
		}
	};
	
	$scope.delete = function(){
		departmentService.delete(function(){
			loadtree();
		});
	};
});

myapp.service('rolesService',function(){
	var obj = {
		get:function(){
			return [{
				role_id:1,
				name:'客服'
			}];
		},
		update:function(){

		},
		delete:function(){

		},
		getPermissions:function(fn){
			return function(){
				if(typeof fn == 'function'){
					var data = ['server/index','accounting/index'];
					fn(data);
				}
			};
		}
	};
	return obj;
});
myapp.controller('rolesCtrl',function($scope,$http,rolesService){
	$scope.roles = rolesService.get();
	$scope.getPermissions = rolesService.getPermissions(function(data){
		$scope.permissions = data;
	});
	$scope.pushCheck = function(permission,othis){
		// console.log(permission,othis.checked);
	}
});
myapp.service('resourceService',function(){
	var obj = {
		'data':[],
		get:function(http,fn){
			$post(http,_host+"resource/findAll",{}).success(function(r){
				if(r){
					obj.data = r;
					fn();
				}
			});
		},
		add:function(http,scope,fn){
			return function(othis){
				var des = scope.add_description;
				if(typeof des != 'undefined' && des.length > 1){
					layer.load();
					$post(http,_host+"resource/save",{
						'description':des
					}).success(function(r){
						layer.closeAll('loading');
						if(r != "false"){
							obj.data.splice(0,0,r);
							fn(othis);
						}else{
							layer.msg('添加失败');
						}
					});
				}else{
					layer.msg('字符太短');
				}
			};
		},
		delete:function(http,fn){
			return function(othis){
				$post(http,_host+"resource/delete",{'resource_id':obj.select_id}).success(function(r){
					if(r == 1){
						var arr = [];
						obj.data.forEach(function(ele,index){
							if(obj.select_id != ele.resource_id){
								arr.push(ele);
							}
						});
						obj.data = arr;
						fn(othis);
					}else{
						layer.msg('删除失败');
					}
				});
			};
		},
		update:function(http,scope,fn){
			return function(othis){
				if(scope.edit_description.length > 1){
					layer.load();
					$post(http,_host+"resource/update",{
						'description':scope.edit_description,
						'resource_id':obj.select_id
					}).success(function(r){
						layer.closeAll('loading');
						// console.log(r);
						if(r != 'false'){
							obj.data.forEach(function(ele,index){
								if(ele.resource_id == r.resource_id){
									obj.data[index] = r;
									// console.log(r);
								}
							});
							fn(othis);
						}else{
							layer.msg('更新失败');
						}
					});
				}else{
					layer.msg('字符太短');
				}
			};
		}
	};
	return obj;
});
myapp.controller('resourceCtrl',function($scope,$http,resourceService){
	$scope.resource = resourceService.get($http,function(r){
		$scope.resource = resourceService.data;
	});
	$scope.edit = resourceService.update($http,$scope,function(ele){
		if(ele.nodeName == 'I'){
			$(ele).parent().prev().trigger('click');
		}else{
			$(ele).prev().trigger('click');
		}
		$scope.resource = resourceService.data;
	});
	$scope.add = resourceService.add($http,$scope,function(ele){
		if(ele.nodeName == 'I'){
			$(ele).parent().prev().trigger('click');
		}else{
			$(ele).prev().trigger('click');
		}
	});
	$scope.delete = resourceService.delete($http,function(ele){
		$(ele).next().trigger('click');
		$scope.resource = resourceService.data;
	});
	$scope.log_resource = function(){
		$scope.edit_description = this.u.description;
		resourceService.select_id = this.u.resource_id;
	};
});
myapp.service('accountService',function(){
	var obj = {
		update:function(scope,fn){
			return function(){
				var old_pass = scope.old_pass,
					new_pass = scope.new_pass,
					repeat_pass = scope.repeat_pass;

				if(!validate('pass',old_pass)){
					layer.msg('老密码格式不正确');
					return;
				}
				if(!validate('pass',new_pass)){
					layer.msg('新密码格式不正确');
					return;
				}
				if(new_pass != repeat_pass){
					layer.msg('两次输入密码不相同');
					return;
				}
				//send
				layer.load();
				setTimeout(function(){
					layer.closeAll('loading');
					layer.msg('修改成功');
					if(typeof fn == 'function'){
						fn();
					}
				},1000);
			};
		}
	};
	return obj;
});
myapp.controller('accountCtrl',function($scope,$http,accountService){
	$scope.checkPass = accountService.update($scope,function(){
		$scope.old_pass = '';
		$scope.new_pass = '';
		$scope.repeat_pass = '';
		$scope.$apply();
	});
});
myapp.service('taxTypeService',function($http){
	var obj = {
		get:function(fn){
			$post($http,_host+"taxtype/find",{}).success(function(r){
				obj.data = r;
				fn(r);
			});
		},
		update:function(scope,fn){
			var name = scope.edit_name;
					
			if(typeof name == 'undefined' || name.length < 1){
				layer.msg('字符太短');
				return;
			}
			layer.load();
			$post($http,_host+"taxtype/update",{'tax_type_id':obj.select_id,'name':name}).success(function(r){
				layer.closeAll('loading');
				if(r != 'false'){
					obj.data.forEach(function(ele,index){
						if(ele.tax_type_id == r.tax_type_id){
							obj.data[index]  = r;
						}
					});
					fn(r);
				}
			});
		},
		add:function(scope,fn){
			var name = scope.add_name,
				parent_id = scope.add_parent_id;
			if(typeof name == 'undefined' || name.length < 1){
				layer.msg('字符太短');
				return;
			}
			layer.load();
			$post($http,_host+"taxtype/save",{'name':name,'parent_id':parent_id}).success(function(r){
				layer.closeAll('loading');
				if(r != 'false'){
					if(obj.data){
						obj.data.splice(0,0,r);
					}else{
						obj.data = [r];
					}
					fn(r);
				}else{
					layer.msg('添加失败');
				}
			});
		},
		delete:function(fn){
			layer.load();
			$post($http,_host+"taxtype/delete",{'tax_type_id':obj.select_id}).success(function(r){
				layer.closeAll('loading');
				if(r == 1){
					var arr = [];
					obj.data.forEach(function(ele){
						if(ele.tax_type_id != obj.select_id){
							arr.push(ele);
						}
					});
					obj.data = arr;
					fn(r);
				}
			});
		}
	};
	return obj;
});
myapp.controller('taxTypeCtrl',function($scope,taxTypeService){
	
	taxTypeService.get(function(){
		$scope.taxtype = taxTypeService.data;
	});

	$scope.edit = function(othis){
		taxTypeService.update($scope,function(){
			if(othis.nodeName == 'I'){
				$(othis).parent().prev().trigger('click');
			}else{
				$(othis).prev().trigger('click');
			}
		});
	}

	$scope.delete = function(othis){
		taxTypeService.delete(function(r){
			$(othis).next().trigger('click');
			$scope.taxtype = taxTypeService.data;
		});
	}
	
	$scope.log_taxtype = function(tax_type_id,name){
		taxTypeService.select_id = tax_type_id;
		$scope.edit_name = name;
	};

	$scope.add = function(othis){
		taxTypeService.add($scope,function(){
			$scope.taxtype = taxTypeService.data;
			if(othis.nodeName == 'I'){
				$(othis).parent().prev().trigger('click');
			}else{
				$(othis).prev().trigger('click');
			}
		});
	};
});
myapp.directive('requestProcessGroup',function($http){
	return {
		restrict:'A',
		link:function(scope,ele,attrs){
			ele.bind('focus',function(){
				scope.process_group = [{
					process_group_id:1,
					name:'工商注册'
				},{
					process_group_id:2,
					name:'工商注销'
				}];
				scope.$apply();
			});
		}
	};
});

myapp.service('payrecordService',function($http){
	var obj = {
		get:function(fn){
			$post($http,_host+"payrecord/find",{}).success(function(r){
				if(r) obj.data = r;
				fn(r);
			});
		},
		recordList:function(accounting_id,fn){
			$post($http,_host+"payrecord/findList",{'accounting_id':accounting_id}).success(function(r){
				if(r){
					obj.recordlist = r;
					fn(r);
				}
			});
		},
		delete:function(pay_record_id,fn){
			layer.load();
			$post($http,_host+"payrecord/delete",{'pay_record_id':pay_record_id}).success(function(r){
				layer.closeAll('loading');
				if(r == 1){
					var arr = [];
					obj.recordlist.forEach(function(ele,index){
						if(ele.pay_record_id != pay_record_id){
							arr.push(ele);
						}
					});
					obj.recordlist = arr;
					fn();
				}
			});
		},
		add:function(money,deadline,fn){
			if(typeof money == 'undefined' || !/^\d+$/.test(money)){
				layer.msg('请输入正确的金额');
				return;
			}
			if(deadline.length < 5){
				layer.msg('请输入正确的日期');
				return;
			}
			if(obj.accounting_id){
				var accounting_id = obj.accounting_id;
			}else{
				layer.msg('不能添加');
				return;
			}
			// console.log(money,deadline,accounting_id);
			// return;
			layer.load();
			$post($http,_host+"payrecord/save",{'money':money,'deadline':deadline,'accounting_id':accounting_id}).success(function(r){
				layer.closeAll('loading');
				if(r != 'false'){
					if(obj.recordlist){
						obj.recordlist.splice(0,0,r);
					}else{
						obj.recordlist = [r];
					}
					fn(r);
				}
			});
		}
	};
	return obj;
});

myapp.controller('payrecordCtrl',function($scope,payrecordService){

	var now = new Date();
	$scope.today = now.getFullYear()+"-"+(now.getMonth()+1)+"-"+now.getDate();

	payrecordService.get(function(){
		$scope.payrecord = payrecordService.data;
	});

	$scope.initRight = function(u){

		payrecordService.accounting_id = u.accounting_id;

		if(payrecordService.pay_record_id != u.pay_record_id){
			callright(function(){
				payrecordService.rightwin = true;
			});
		}else{
			if(payrecordService.rightwin == true){
				closeright(function(){
					payrecordService.rightwin = false;
				});
			}else{
				callright(function(){
					payrecordService.rightwin = true;
				});
			}
		}
		$scope.intro = u.company+"/"+u.name;
		payrecordService.pay_record_id = u.pay_record_id;
		if(u.pay_record_id){
			payrecordService.recordList(u.accounting_id,function(r){
				$scope.recordlist = r;
			});
		}else{
			$scope.recordlist = [];
		}
		// console.log(u.accounting_id);
	};

	$scope.delete = function(pay_record_id,othis){
		payrecordService.delete(pay_record_id,function(r){
			$scope.recordlist = payrecordService.recordlist;
		});
	};

	$scope.add = function(othis){
		
		payrecordService.add($scope.add_money,$("#record-deadline").val(),function(r){
			$scope.recordlist = payrecordService.recordlist;
			$(othis).prev().trigger('click');
		});

	};
});
