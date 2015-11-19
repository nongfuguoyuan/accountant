var myapp = angular.module('myapp',['ngRoute']),
	_host = 'http://192.168.10.35/accountant/index.php/';

myapp.config(['$routeProvider',function($routeProvider){
	$routeProvider
	.when('/',{
		templateUrl:'tmp/server_count.html',
		controller:'serverCtrl'
	})
	.when('/add_user',{
		templateUrl:'tmp/add_user.html'
	})
	.when('/users',{
		templateUrl:'tmp/users.html',
		controller:'userCtrl'
	})
	.when('/process_group',{
		templateUrl:'tmp/process_group.html',
		controller:'processGroupCtrl'
	})
	.when('/process',{
		templateUrl:'tmp/process.html',
		controller:'processCtrl'
	})
	.when('/business',{
		templateUrl:'tmp/business.html',
		controller:'businessCtrl'
	})
	.when('/accounting',{
		templateUrl:'tmp/accounting.html',
		controller:'accountingCtrl'
	})
	.when('/tax',{
		templateUrl:'tmp/tax.html',
		controller:'taxCtrl'
	})
	.when('/department',{
		templateUrl:'tmp/department.html',
		controller:'departmentCtrl'
	})
	.when('/roles',{
		templateUrl:'tmp/roles.html',
		controller:'rolesCtrl'
	})
	.when('/resource',{
		templateUrl:'tmp/resource.html',
		controller:'resourceCtrl'
	})
	.when('/account',{
		templateUrl:'tmp/account.html',
		controller:'accountCtrl'
	})
	.when('/tax_type',{
		templateUrl:'tmp/tax_type.html',
		controller:'taxTypeCtrl'
	})
	.when('/todo',{
		templateUrl:'tmp/todo.html',
		controller:'todoCtrl'
	});
}]);

myapp.service('serverService',function(){
	var obj = {
		get:function(http){
			return {
				img:'assets/img/photos/photo8@2x.jpg',
				name:'赵小明',
				department:'客服部/客服',
				all:89,
				negotiation:13,
				deal:12,
				loss:33
			}
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
myapp.controller('serverCtrl',function($scope,$http,serverService){
	$scope.server = serverService.get($http);
	$scope.todos = serverService.getTodo($http);
	$scope.todo_count = $scope.todos.length;
	if($scope.todo_count > 0){
		$scope.show_todo = true;
	}
	$scope.getEarly = serverService.getEarly($http,function(data){
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
	return {
		getProcessGroup:function(){
			return [{
				'name':'工商注册',
				'date':'2015-02-25'
			},{
				'name':'代理记账',
				'date':'2015-04-25'
			}];
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
		}
	};
});

myapp.service('processService',function(){
	return {
		getProcess:function(){
			return [{
				'process_group_id':1,
				"process_group":'工商注册',
				'name':'预审查/确定/好'
			}];
		}
	};
});
myapp.service('userService',function(){
	var obj = {};
	obj = {
		data:[],
		get:function(http,params,fn){
			$post(http,_host+"guest/findall",params).success(function(r){
				obj.data = r.data;
				fn(r);
			});
		},
		addGuest:function(http,scope,fn){
			return function(othis){
				var company = scope.add_company,
					name = scope.add_name,
					phone = scope.add_phone,
					tel = scope.add_tel,
					area_id = scope.add_area,
					address = scope.add_address,
					status = scope.add_status,
					record = scope.add_record;

				if(!validate('name',name)){
					layer.msg('姓名不正确',function(){});
					return;
				}
				if(!validate('phone',phone) && !validate('tel',tel)){
					layer.msg('电话不正确',function(){});
					return;
				}
				layer.load();
				$post(http,_host+"guest/save",{
					'company':company,
					'name':name,
					'phone':phone,
					'tel':tel,
					'area_id':area_id,
					'address':address,
					'status':status,
					'record':record
				}).success(function(r){
					layer.closeAll('loading');
					if(r.tag == true){
						obj.data.splice(0,0,r.info[0]);
						fn(othis);
					}else{
						layer.msg(r.info);
					}
				});
			}
		},
		editGuest:function(http,scope,fn){
			return function(othis){
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
				$post(http,_host+"guest/update",{
					'guest_id':obj.edit_guest_id,
					'company':scope.edit_company,
					'name':name,
					'phone':phone,
					'tel':tel,
					'area_id':scope.edit_area,
					'status':scope.edit_status,
					'address':scope.edit_address
				}).success(function(r){
					layer.closeAll('loading');
					if(r.tag){
						for(var i = 0,len = obj.data.length;i<len;i++){
							if(obj.data[i]['guest_id'] == obj.edit_guest_id){
								obj.data[i] = r.info[0];
								console.log(r.info[0]);
							}
						}
						fn(othis);
					}else{
						layer.msg(r.info);
					}
				});
			}
		},
		showFollowDetail:function(http,fn){
			return function(othis){
				$post(http,_host+"record/findbyguest",{guest_id:this.u.guest_id}).success(function(r){
					if(typeof fn == 'function'){
						fn(othis,r);
					}
				});
			};
		},
		addRecord:function(http,scope,fn){
			return function(othis){
				if(typeof scope.guest_record == 'undefined' || scope.guest_record.length < 2){
					layer.msg('字符太短');
					return;
				}
				layer.load();
				$post(http,_host+"record/save",{'guest_id':obj.initGuestid,'content':scope.guest_record}).success(function(r){
					layer.closeAll('loading');
					fn(othis,r);
					console.log(r);
				});
			}
		}
	};
	return obj;
});

myapp.controller('userCtrl',function($scope,$http,userService){

	userService.get($http,{page:1,pageNum:10},function(r){
		// console.log(r);
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
		// var page = 1;
		// var index = 0;
		// if(typeof this.p == 'number'){
		// 	page = this.p;
		// }else{

		// 	if(this.p == "<"){

		// 	}
		// }
		userService.get($http,{page:this.p,pageNum:10},function(r){
			$scope.guests = r.data;
			$(othis).addClass('active');
		});
	};

	$scope.showFollowDetail = userService.showFollowDetail($http,function(othis,data){
		var html = "";
		data.forEach(function(ele,index){
			html = html + "<p>"+(index+1)+" )"+ele.content+"/"+ele.record_time+"</p>";
		});
		layer.tips(html,othis, {
		    tips: [4, '#78BA32']
		});
	});
	$scope.showAddress = function(othis){
		layer.tips(this.u.address,othis,{
			tips: [4, '#78BA32']
		});
	};
	//添加新用户回调
	$scope.checkGuest = userService.addGuest($http,$scope,function(ele){
		if(ele.nodeName == 'I'){
			$(ele).parent().prev().trigger('click');
		}else{
			$(ele).prev().trigger('click');
		}
		//清空scope中自定义变量
		for(s in $scope){
			var x = s.toString();
			if(x.indexOf('add_') > -1){
				$scope[s] = '';	
			}
		}
		$scope.guests = userService.data;
	});
	$scope.initGuestid = function(){
		userService.initGuestid = this.u.guest_id;
	};
	//添加跟进记录回调
	$scope.checkRecord = userService.addRecord($http,$scope,function(ele){
		if(ele.nodeName == 'I'){
			$(ele).parent().prev().trigger('click');
		}else{
			$(ele).prev().trigger('click');
		}
	});
	
	$scope.initEdit = function(){
		$scope.edit_status = this.u.status;
		$scope.edit_address = this.u.address;
		$scope.edit_company = this.u.company;
		$scope.edit_phone = this.u.phone;
		$scope.edit_name = this.u.name;
		userService.edit_guest_id = this.u.guest_id;
	};
	//编辑用户
	$scope.checkEditGuest = userService.editGuest($http,$scope,function(ele){
		if(ele.nodeName == 'I'){
			$(ele).parent().prev().trigger('click');
		}else{
			$(ele).prev().trigger('click');
		}
		$scope.guests = userService.data;
	});
	//添加工商
	// $scope.checkAddBusiness = userService.checkAddBusiness($scope,$http,function(ele){

	// });
});

myapp.controller('processGroupCtrl',function($scope,$http,processGroupService){
	$scope.process_groups = processGroupService.getProcessGroup();
	$scope.initEditName = function(name){
		$scope.edit_name = name;
	};
	$scope.$on('refresh',function(){
		alert('refresh');
		$scope.process_groups = processGroupService.getProcessGroup();
	});
	$scope.checkProcessGroup = processGroupService.click;
});

myapp.controller('processCtrl',function($scope,$http,processService){
	$scope.processes = processService.getProcess();
});

myapp.controller('editProcessCtrl',function($scope,$http){
	$scope.processes = [3,4];
});

myapp.service('businessService',function(){
	var obj = {};
	obj = {
		'data':[],
		get:function(){
			return [{
				'id':1,
				'name':'张武',
				'phone':'1358965895',
				'follow_server':'张新',
				'follow_accounting':'李二',
				'accept':0,
				'debt':200,
				'obtain':100,
				'progress':'审核',
				'date':'2015-05-23'
			}];
		},
		updateAccept:function($scope){
			$scope.accept = 1;
			//send
		},
		addBusiness:function($scope,fn){
			return function(othis){
				var business_type = $scope.business_type,
					server = $scope.server;

				if(!business_type || !server){
					layer.msg('格式错误',function(){});
					return;
				}
				if(typeof fn == 'function'){
					fn(othis);
				}
			}
		},
		addProgress:function($scope,fn){
			return function(othis){
				var progress = $scope.progress,
					rest = $scope.rest,
					note = $scope.note;

				// console.log(progress,rest,note);
				if(!progress || !rest){
					layer.msg('进度和天数为必填项',function(){});
					return;
				}else{
					//send
					layer.load();
					setTimeout(function(){
						layer.closeAll('loading');
						if(typeof fn == 'function'){
							fn(othis);
						}
					},1000);
				}
			}
		},
		updateBusiness:function($scope){

		}
	};
	return obj;
});

myapp.controller('businessCtrl',function($scope,$http,businessService){
	$scope.business = businessService.get();
	$scope.checkBusiness = businessService.addBusiness($scope,function(ele){
		if(ele.nodeName == 'I'){
			$(ele).parent().prev().trigger('click');
		}else{
			$(ele).prev().trigger('click');
		}
	});
	$scope.toggleBusiness = businessService.updateAccept($scope);
	$scope.checkBusinessProgress = businessService.addProgress($scope,function(ele){
		if(ele.nodeName == 'I'){
			$(ele).parent().prev().trigger('click');
		}else{
			$(ele).prev().trigger('click');
		}
		//清空scope上自定义参数
		$scope.note = '';
		$scope.$apply();		
	});
	$scope.businessProcess = function(id){
		
	}
});
myapp.service('accountingService',function(){
	var obj = {
		'data':[],
		getAccounting:function(){
			return [{
				'id':1,
				'name':'张武',
				'phone':'1358965895',
				'follow_server':'张新',
				'follow_accounting':'李二',
				'rest':'1',
				'accept':'0',
				'status':'欠费',
				'date_start':'2015-05-23',
				'date_end':'2015-06-23'
			}];
		},
		updateAccept:function($scope){
			return function(){
				$scope.accept = 1;
			};
			//send
		},
		addAccounting:function($scope,fn){
			// var follow_accounting = $scope;
		}
	};
	return obj;
});
myapp.controller('accountingCtrl',function($scope,$http,accountingService){
	$scope.accounting = accountingService.getAccounting();
	$scope.updateAccountingAccept = accountingService.updateAccept($scope);
});

myapp.controller('taxCtrl',function($scope,$http){
	$scope.tax = [{
		'id':1,
		'name':'张武',
		'phone':'1358965895',
		'followers':'李二/张新',
		'local_tax':'156',
		'states_tax':'456',
		'current':'8'
	}];
});
myapp.service('todoService',function(){
	var obj = {
		"hasSelect":[],
		getTodo:function(){
			return [{
				'id':1,
				'sender':'李二',
				'accepter':'张新',
				'task':'刻章',
				'date_start':'2015-11-02',
				'date_end':'2015-11-05'
			}];
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
	$scope.getEmployee = todoService.getEmployee($scope,function(data){
		$scope.employee = data;
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

function requestProcess(process_group_id){
	myapp.controller('editProcessCtrl',function($scope,$http){
		$scope.processes = ['预审查','确定','好'];
	});
}
function addUser(){
	alert(1);
}
myapp.service('departmentService',function(){
	var obj = {
		'data':[],
		edit:'',
		employees:[],
		select:{},
		active:'',
		getEmployee:function(http,params,fn){
			$post(http,_host+"employee/findAll",params).success(function(r){
				if(r){
					obj.employees = r.data;
					fn(r);	
				}
			});
		},
		add:function(http,scope,fn){
			return function(othis){
				var name = scope.add_name,
					parent_id = obj.select.department_id || 0;

				// console.log(parent_id);
				if(!name || name.length < 1){
					layer.msg('字符太短',function(){});
					return;
				}
				layer.load();
				$post(http,_host+"department/save",{'name':name,'parent_id':parent_id}).success(function(r){
					layer.closeAll('loading');
					r = eval("("+r+")");
					r = jQuery.parseJSON(r);
					console.log(r);
					if(r == 1){
						// fn(othis);
						window.location.reload();
					}else{
						layer.msg(r.info);
					}
				});
			}
		},
		update:function(http,scope,fn){
			return function(othis){
				var name = scope.edit_name,
					department_id = obj.select.department_id;

				if(typeof department_id == 'undefined'){
					layer.msg('请先选定');
					return;
				}
				if(!name || name.length < 1){
					layer.msg('字符太短',function(){});
					return;
				}
				layer.load();
				$post(http,_host+"department/update",{'department_id':department_id,'name':name}).success(function(r){
					r = r.trim();
					layer.closeAll('loading');
					if(r == 1){
						window.location.reload();
					}else{
						layer.msg('更新失败');
					}
				});
			}
		},
		delete:function(http,fn){
			return function(){
				var department_id = obj.select.department_id;
				if(typeof department_id == 'undefined'){
					layer.msg('必须选定内容');
				}else{
					$post(http,_host+"department/delete",{'department_id':department_id}).success(function(r){
						r = r.trim();
						if(r == 1){
							window.location.reload();
						}else{
							layer.msg('删除失败');
						}
					});
				}
			}
		},
		addEmployee:function(http,scope,fn){
			return function(othis){
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
				$post(http,_host+"employee/save",{
					'name':name,
					'phone':phone,
					'sex':sex,
					'department_id':department_id
				}).success(function(r){
					layer.closeAll('loading');
					if(r){
						obj.employees.splice(0,0,r);
						fn(othis);
					}else{
						layer.msg('添加失败');
					}
				});
			};
		},
		updateEmployee:function(http,scope,fn){
			return function(othis){
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
				$post(http,_host+"employee/update",{
					'name':name,
					'phone':phone,
					'sex':scope.e_sex,
					'department_id':scope.e_department_id,
					'employee_id':obj.delete_employee_id
				}).success(function(r){
					if(r){
						obj.employees.forEach(function(ele,index){
							if(ele.employee_id == r.employee_id){
								obj.employees[index] = r;
							}
						});
						fn(othis);
					}
				});
			};
		},
		deleteEmployee:function(http,fn){
			return function(othis){
				$post(http,_host+"employee/delete",{'employee_id':obj.delete_employee_id}).success(function(r){
					r = r.trim();
					console.log('dele',r);
					if(r == 1){
						var newarr = [];
						obj.employees.forEach(function(ele,index){
							if(ele.employee_id != obj.delete_employee_id){
								newarr.push(ele);
							}
						});
						obj.employees = newarr;
						fn(othis);
					}else{
						layer.msg('删除失败');
					}
				});
			}
		}
	};
	return obj;
});
myapp.controller('departmentCtrl',function($scope,$http,$route,departmentService){
	$scope.editEmployee = departmentService.updateEmployee($http,$scope,function(ele){
		$scope.employees = departmentService.employees;
		// $scope.$apply();
		if(ele.nodeName == 'I'){
			$(ele).parent().prev().trigger('click');
		}else{
			$(ele).prev().trigger('click');
		}
	});
	$scope.deleteEmployee = departmentService.deleteEmployee($http,function(ele){
		$scope.employees = departmentService.employees;
		$(ele).next().trigger('click');
	});
	$scope.log_employee = function(){
		$scope.e_name = this.u.name;
		$scope.e_phone = this.u.phone;
		$scope.e_sex = this.u.sex;
		$scope.e_department_id = this.u.department_id;
		departmentService.delete_employee_id = this.u.employee_id;
	};
	$scope.getEmployee = departmentService.getEmployee($http,{'page':1,"pageNum":'20'},function(r){
		$scope.employees = departmentService.employees;
		var page_arr = [];
		for(var i = 0;i<r.count;i++){
			page_arr.push(i+1);
		}
		$scope.pagination = page_arr;
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

	$scope.updateDepartment = departmentService.update($scope,function(ele){
		// $route.reload();
	});
	$scope.checkAddDepartment = departmentService.add($http,$scope,function(ele){

		if(ele.nodeName == 'I'){
			$(ele).parent().prev().trigger('click');
		}else{
			$(ele).prev().trigger('click');
		}
		$scope.add_name = "";
		// $route.reload();
		// $scope.$apply();
		//reload menu
	});
	$scope.checkEditDepartment = departmentService.update($http,$scope,function(ele){
		if(ele.nodeName == 'I'){
			$(ele).parent().prev().trigger('click');
		}else{
			$(ele).prev().trigger('click');
		}
	});
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
	$scope.checkEmployee = departmentService.addEmployee($http,$scope,function(ele){
		if(ele.nodeName == 'I'){
			$(ele).parent().prev().trigger('click');
		}else{
			$(ele).prev().trigger('click');
		}
		$scope.employees = departmentService.employees;
		// $scope.$apply();
	});
	$scope.deleteDepartment = departmentService.delete($http,function(ele){
		// $route.reload();
	});
});
myapp.directive('requestAllDepartment',function($http){
	return {
		restrict:'A',
		link:function(scope,ele,attrs){
			$post($http,_host+"department/findAll",{}).success(function(r){
				r = eval("("+r+")");
				r = jQuery.parseJSON(r);
				scope.des = r;
				console.log(r);
				// scope.$apply();
			});
		}
	}
});
myapp.directive('loadMenuTree',function($http,departmentService){
	return {
		restrict:'A',
		replace:"true",
		link:function(scope,ele,attrs){
			$post($http,_host+"department/findMenu",{}).success(function(r){
				if(r != false){
					r = eval("("+r+")");
					var menu = jQuery.parseJSON(r);
					console.log(menu);
					if(attrs.type == 'edit'){
						ele.html(buildTree(menu,function(obj,span){
							departmentService.edit = obj;
						}));
					}
					if(attrs.type == 'show'){
						ele.html(buildTree(menu));
					}
					if(attrs.type == 'select'){
						ele.html(buildTree(menu,function(obj,span){
							departmentService.select = obj;
						}));
					}
				}
			});
		}
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
		console.log(permission,othis.checked);
	}
});
myapp.service('resourceService',function(){
	var obj = {
		get:function(){
			return [{resource_id:1,description:'58'}];
		},
		add:function(scope,fn){
			return function(othis){
				var des = scope.add_description;
				if(typeof des != 'undefined' && des.length > 1){
					layer.load();
					setTimeout(function(){
						layer.closeAll('loading');
						if(typeof fn == 'function'){
							fn(othis);
						}
					},1000);
				}else{
					layer.msg('字符太短');
				}
			};
		},
		update:function(scope,fn){
			return function(othis){
				if(scope.edit_description.length > 1){
					layer.load();
					setTimeout(function(){
						layer.closeAll('loading');
						if(typeof fn == 'function'){
							fn(othis);
						}
					},1000);
				}else{
					layer.msg('字符太短');
				}
			};
		}
	};
	return obj;
});
myapp.controller('resourceCtrl',function($scope,$http,resourceService){
	$scope.resource = resourceService.get();
	$scope.checkEditResource = resourceService.update($scope,function(ele){
		if(ele.nodeName == 'I'){
			$(ele).parent().prev().trigger('click');
		}else{
			$(ele).prev().trigger('click');
		}
	});
	$scope.checkAddResource = resourceService.add($scope,function(ele){
		if(ele.nodeName == 'I'){
			$(ele).parent().prev().trigger('click');
		}else{
			$(ele).prev().trigger('click');
		}
	});
	$scope.initEditDes = function(des){
		$scope.edit_description = des;
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
myapp.service('taxTypeService',function(){
	var obj = {
		get:function($http){
			var data = [{
				tax_type_id:2,
				name:'印花税',
				parent_id:1,
				parent:'国税'
			}];
			return data;
		},
		update:function(scope,http,fn){
			return function(othis){
				var name = scope.edit_name,
					parent_id = scope.parent_id;
				if(typeof name == 'undefined' || name.length < 1){
					layer.msg('字符太短');
					return;
				}
				layer.load();
				setTimeout(function(){
					layer.closeAll('loading');
					if(typeof fn == 'function'){
						fn(othis);
					}
				},1000);
			}
		},
		add:function(scope,http,fn){
			return function(othis){
				var name = scope.add_name,
					parent_id = scope.parent_id;
				if(typeof name == 'undefined' || name.length < 1){
					layer.msg('字符太短');
					return;
				}
				layer.load();
				setTimeout(function(){
					layer.closeAll('loading');
					if(typeof fn == 'function'){
						fn(othis);
					}
				},1000);
			}
		}
	};
	return obj;
});
myapp.directive('requestTaxType',function($http){
	return {
		restrict:'A',
		link:function(scope, ele, attrs,http){
			ele.bind('focus',function(){
				scope.tax_types = [{
					tax_type_id:1,
					name:'印花税'
				}];
				scope.$apply();
			});
		}
	};
});
myapp.controller('taxTypeCtrl',function($scope,$http,taxTypeService){
	$scope.tax_type = taxTypeService.get($http);
	$scope.initEdit = function(){
		$scope.tax_types = [{tax_type_id:this.t.parent_id,name:this.t.parent}];
		$scope.parent_id = this.t.parent_id;
		$scope.edit_name = this.t.name;
	};
	$scope.checkEdit = taxTypeService.update($scope,$http,function(ele){
		if(ele.nodeName == 'I'){
			$(ele).parent().prev().trigger('click');
		}else{
			$(ele).prev().trigger('click');
		}
	});
	$scope.checkAdd = taxTypeService.add($scope,$http,function(ele){
		if(ele.nodeName == 'I'){
			$(ele).parent().prev().trigger('click');
		}else{
			$(ele).prev().trigger('click');
		}
	});
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