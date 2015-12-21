var myapp = angular.module('myapp',['ngRoute']);

myapp.config(['$routeProvider',function($routeProvider){
	$routeProvider
	.when('/',{
		templateUrl:_static+"dashboard.html",
		controller:'dashboardCtrl'
	})
	.when('/add_user',{
		templateUrl:_static+'add_user.html'
	})
	.when('/users',{
		templateUrl:_static+'users.html',
		controller:'userCtrl'
	})
	.when('/process_group',{
		templateUrl:_static+'process_group.html',
		controller:'processGroupCtrl'
	})
	.when('/process',{
		templateUrl:_static+'process.html',
		controller:'processCtrl'
	})
	.when('/business',{
		templateUrl:_static+'business.html',
		controller:'businessCtrl'
	})
	.when('/accounting',{
		templateUrl:_static+'accounting.html',
		controller:'accountingCtrl'
	})
	.when('/tax',{
		templateUrl:_static+'tax.html',
		controller:'taxCtrl'
	})
	.when('/department',{
		templateUrl:_static+'department.html',
		controller:'departmentCtrl'
	})
	.when('/roles',{
		templateUrl:_static+'roles.html',
		controller:'rolesCtrl'
	})
	.when('/resource',{
		templateUrl:_static+'resource.html',
		controller:'resourceCtrl'
	})
	.when('/account',{
		templateUrl:_static+'account.html',
		controller:'accountCtrl'
	})
	.when('/tax_type',{
		templateUrl:_static+'tax_type.html',
		controller:'taxTypeCtrl'
	})
	.when('/pay_record',{
		templateUrl:_static+'pay_record.html',
		controller:'payrecordCtrl'
	})
	.when('/todo',{
		templateUrl:_static+'todo.html',
		controller:'todoCtrl'
	});
}]);

myapp.service('dashboardService',function($http){
	var obj = {
		getOverview:function(fn){
			$post($http,_host+"dashboard/findOverview",{}).success(function(r){
				viewResult(r,function(r){
					fn(r);
				});
			});
		},
		getTodo:function(http,fn){
			$post(http,_host+'todo/todoNotice',{'accepter':'34'}).success(function(r){
				// fn(r);
			})
		},
		getEarly:function(fn){
			$post($http,_host+'todo/todoEarly',{'accepter':obj.employee_id}).success(function(r){
				fn(r);
			})
			
		}
	};
	return obj;
});


myapp.controller('dashboardCtrl',function($scope,$http,dashboardService){
	$scope.todos = dashboardService.getTodo($http);
	$scope.r_name = _config.r_name;
	//get session
	$scope.name = _config.name;
	dashboardService.employee_id = _config.employee_id;
	$scope.tag = _config.tag;

	dashboardService.getOverview(function(r){
		if(_config.tag == 'server'){
			var total= 0;

			$scope.ing = r.ing.count || 0;
			total += parseInt($scope.ing);
			$scope.deal = r.deal.count || 0;
			total += parseInt($scope.deal);
			$scope.lose = r.lose.count || 0;
			total += parseInt($scope.lose);

			$scope.total = total;	
		}
		if(_config.tag == 'accounting'){
			$scope.accept_business = r.accept_business.count || 0;
			$scope.unaccept_business = r.unaccept_business.count || 0;
			$scope.accept_accounting = r.accept_accounting.count || 0;
			$scope.unaccept_accounting = r.unaccept_accounting.count || 0;
			$scope.owe_business = r.owe_business.count || 0;
			$scope.owe_accounting = r.owe_accounting.count || 0;
		}

		if(_config.tag == 'accounting_admin'){

			$scope.accept_business = r.accept_business.count || 0;
			$scope.unaccept_business = r.unaccept_business.count || 0;
			$scope.accept_accounting = r.accept_accounting.count || 0;
			$scope.unaccept_accounting = r.unaccept_accounting.count || 0;
			$scope.owe_business = r.owe_business.count || 0;
			$scope.owe_accounting = r.owe_accounting.count || 0;

			$scope.d_accept_business = r.d_accept_business.count || 0;
			$scope.d_unaccept_business = r.d_unaccept_business.count || 0;
			$scope.d_accept_accounting = r.d_accept_accounting.count || 0;
			$scope.d_unaccept_accounting = r.d_unaccept_accounting.count || 0;
			$scope.d_owe_business = r.d_owe_business.count || 0;
			$scope.d_owe_accounting = r.d_owe_accounting.count || 0;
		}

		if(_config.tag == 'server_admin'){
			
			var total= 0;

			$scope.ing = r.ing.count || 0;
			total += parseInt($scope.ing);
			$scope.deal = r.deal.count || 0;
			total += parseInt($scope.deal);
			$scope.lose = r.lose.count || 0;
			total += parseInt($scope.lose);

			$scope.total = total;

			var d_total = 0;

			$scope.d_ing = r.d_ing.count || 0;
			d_total += parseInt($scope.d_ing);
			$scope.d_deal = r.d_deal.count || 0;
			d_total += parseInt($scope.d_deal);
			$scope.d_lose = r.d_lose.count || 0;
			d_total += parseInt($scope.d_lose);

			$scope.d_total = d_total;
		}

		if(_config.tag == 'admin'){
			$scope.ing = r.ing.count || 0;
			total += parseInt($scope.ing);
			$scope.deal = r.deal.count || 0;
			total += parseInt($scope.deal);
			$scope.lose = r.lose.count || 0;
			total += parseInt($scope.lose);

			$scope.total = total;

			$scope.accept_business = r.accept_business.count || 0;
			$scope.unaccept_business = r.unaccept_business.count || 0;
			$scope.accept_accounting = r.accept_accounting.count || 0;
			$scope.unaccept_accounting = r.unaccept_accounting.count || 0;
			$scope.owe_business = r.owe_business.count || 0;
			$scope.owe_accounting = r.owe_accounting.count || 0;

		}
	});

	$scope.getEarly = dashboardService.getEarly(function(r){
		$scope.todo_early = r.data;
		$scope.todo_count_early = r.total;
		if($scope.todo_count_early > 0){
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
		    			$post($http,_host+"guest/searchByPhone",{"phone":str}).success(function(r){
		    				if(r){
		    					scope.results = r;
		    				}else{
		    					scope.results = [{}];
		    				}
		    			});
		    		}else if(str.match(/^[^\d\w]+$/)){
		    			//默认为按公司/姓名查询
		    			$post($http,_host+"guest/searchByCom",{"com":str}).success(function(r){
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

myapp.directive('sureSearchResultTodo',function(todoService,$http){
	return {
		restrict:'A',
		link:function(scope,ele,attrs){
			ele.bind('click',function(){
				var todo_id=attrs.id;
				scope.$parent.taskstr = scope.r.task_content;
				scope.$parent.taskresults=[{}];
				$.post(_host+"todo/findById",{"todo_id":todo_id},function(r){
					scope.$parent.todos=r;
					scope.$parent.$apply();	
				})
			})
		}
	}
})

myapp.directive('sureSearchResultEmployee',function(todoService,$http){
	return{
		restrict:'A',
		link:function(scope,ele,attrs){
			ele.bind('click',function(){
				todoService.employee_id=scope.r.employee_id;
				scope.$parent.searchstr = scope.r.name;
				scope.$parent.results= [{}];
				scope.$parent.$apply();
			})
		}
	}
});

myapp.directive('sureSearchResult',function(userService,$http){
	return {
		restrict:'A',
		link:function(scope, ele, attrs){
			ele.bind('click',function(){
				var guest_id = attrs.id;
				scope.$parent.searchstr = scope.r.company+"/"+scope.r.name;
				scope.$parent.results = [];
				$.post(_host+"guest/searchById",{'guest_id':guest_id},function(r){
					scope.$parent.guests = [r];
					scope.$parent.pagination = [];//清空分页
					scope.$parent.$apply();
				});
			});
		}
	};
});

myapp.directive('requestArea',function($http){
	var obj = '';
	return {
		restrict:'A',
		link:function(scope,ele,attrs){
			if(!_config.area){
				
			}else{
				scope.areas = _config.area;
			}
		}
	};
});
/*实时搜索任务*/
myapp.directive('searchTodoRealTime',function($http,$route){
	return {
		restrict:'A',
		link:function(scope, ele, attrs){
			ele.bind('keyup',function(e){
				var key = e.keyCode;
				if(key == 40 || key == 38 || key == 13){
					var ul = $("#task-ul");
					var lis = ul.children();
					var len = lis.length;
					
					if(len > 0){
						var active = ul.find('.active');
						var index = $(active).index();
						
						if(key == '40'){//down
							if(index < len-1){
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
					var str = scope.taskstr;
					//默认按任务内容
						$post($http,_host+"todo/search",{"task_content":str}).success(function(r){
							if(r){
								for (var i = r.length - 1; i >= 0; i--) {
									r[i].task_content=r[i].task_content.substr(0,10);
								};
								scope.taskresults = r;
							}else{
								scope.taskresults = [{}];
							}
						});
					
					if(typeof scope.taskresults == 'undefined' || scope.taskresults.length == 0){
						scope.taskresults = [{}];
					}
					scope.$apply();
				}
			});
			
			ele.bind('click',function(){
				scope.taskstr = '';
				scope.$apply();
			})
		}
	}
})
/*实时搜索员工*/
myapp.directive('searchEmployeeRealTime',function($http,$route){
	return {
		restrict:'A',
		link:function(scope, ele, attrs){
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
							if(index<len-1){
								lis.removeClass('active');
								lis.eq(index+1).addClass('active');
							}
						}
						if(key == '38'){//up
							if(index >0){
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
					if(str.match(/^\d+$/)){//按手机号查询
						$post($http,_host+"employee/search",{"phone":str}).success(function(r){
							if(r){
								scope.results = r;
							}else{
								scope.results = [{}];
							}
						});
					}else if(str.match(/^[^\d]+$/)){//默认按姓名查询
						$post($http,_host+"employee/search",{"name":str}).success(function(r){
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
					scope.$apply();
				}
			});
			
			ele.bind('click',function(){
				scope.searchstr = '';
				scope.$apply();
			})
		}
	}
})
/*请求所有部门*/
myapp.directive('requestDepartment',function($http){
	return {
		restrict:'A',
		link:function(scope,ele,attrs){
			ele.bind('focus',function(){
				if(!scope.department){

					$post($http,_host+"department/find",{}).success(function(r){
						scope.department=r;
					})
					
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
				viewResult(r,function(r){
					obj.data = r;
					fn(r);
				});
			});
		},
		// add:function(http,scope,fn){
		// 	return function(othis){
		// 		var name = scope.add_name;
		// 		if(typeof name == 'undefined' || name.length < 1){
		// 			layer.msg('字符太短');
		// 			return;
		// 		}
		// 		layer.load();
		// 		$post(http,_host+"processgroup/save",{
		// 			'name':name
		// 		}).success(function(r){
		// 			layer.closeAll('loading');
		// 			if(r != 'false'){
		// 				obj.data.splice(0,0,r);
		// 				fn(othis);
		// 			}else{
		// 				layer.msg('不能重复');
		// 			}
		// 		});
		// 	};
		// },
		// update:function(http,scope,fn){
		// 	return function(othis){
		// 		var name = scope.edit_name;
		// 		if(typeof name == 'undefined' || name.length < 1){
		// 			layer.msg('字符太短');
		// 			return;
		// 		}
		// 		$post(http,_host+"processgroup/update",{
		// 			'process_group_id':obj.select_id,
		// 			'name':name
		// 		}).success(function(r){
		// 			if(r != 'false'){
		// 				obj.data.forEach(function(ele,index){
		// 					if(ele.process_group_id == r.process_group_id){
		// 						obj.data[index] = r;
		// 					}
		// 				});
		// 				fn(othis);
		// 			}else{
		// 				layer.msg('不能重复');
		// 			}
		// 		});
		// 	};
		// },
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

/*请求所有的区*/
myapp.service('areaService',function($http){
	var obj = {
		get:function(fn){
			if(obj.data){
				fn(obj.data);
			}else{
				$post($http,_host+"area/find",{}).success(function(r){
					viewResult(r,function(r){
						obj.data = r;
						fn(r);
					});
				});
			}
		}
	};
	return obj;
});

myapp.service('userService',function($http){

	/*filterParams 变量里存放的是过滤条件*/

	var obj = {
		filterParams:{'status':1},//默认成交
		get:function(params,fn){
			if(obj.filterParams){
				for(f in obj.filterParams){
					params[f] = obj.filterParams[f];
				}
			}
			$post($http,_host+"guest/find",params).success(function(r){
				// console.log(r);
				viewResult(r,function(r){
					obj.data = r.data;
					fn(r);
				});
			});
		},
		deleteGuest:function(fn){
			$post($http,_host+"guest/delete",{'guest_id':obj.guest_id}).success(function(r){
				viewResult(r,function(r){
					var arr = [];
					obj.data.forEach(function(ele,index){
						if(ele.guest_id != obj.guest_id){
							arr.push(ele);
						}
					});
					obj.data = arr;
					fn();
				});
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
			if(!validate('phone',phone)){
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
				viewResult(r,function(r){
					if(obj.data){
						obj.data.splice(0,0,r);	
					}else{
						obj.data = [r];
					}
					fn();
				});
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
				viewResult(r,function(r){
					obj.data.forEach(function(ele,index){
						if(ele['guest_id'] == r.guest_id){
							obj.data[index] = r;
						}
					});
					fn();
				});
			});
		},
		loadTree:function(fn){
			if(!obj.trees){
				$post($http,_host+"department/findWholeMenu",{}).success(function(r){
					viewResult(r,function(r){
						obj.trees = r;
						fn(r);
					});
				});
			}else{
				fn(obj.trees);
			}
		}
	};
	return obj;
});

myapp.service('recordService',function($http){
	var obj = {
		get:function(fn){
			$post($http,_host+"record/find",{"guest_id":obj.guest_id}).success(function(r){
				viewResult(r,function(r){
					obj.data = r;
					fn();
				});
			});
		},
		add:function(scope,fn){
			if(typeof scope.add_record == 'undefined' || scope.add_record.length < 2){
				layer.msg('字符太短');
				return;
			}
			layer.load();
			$post($http,_host+"record/save",{'guest_id':obj.guest_id,'content':scope.add_record}).success(function(r){
				layer.closeAll('loading');
				viewResult(r,function(r){
					if(obj.data){
						obj.data.splice(0,0,r);
					}else{
						obj.data = [r];
					}
					fn();
				});
			});
		},
		update:function(content,fn){
			content = content ? content.trim():"";
			if(content.length < 1){
				layer.msg('字符太短');
				return;
			}
			$post($http,_host+"record/update",{'record_id':obj.record_id,'content':content}).success(function(r){
				viewResult(r,function(r){
					obj.data.forEach(function(ele,index){
						if(ele.record_id == obj.record_id){
							obj.data[index]['content'] = content;
							return;
						}
					});
					fn();
				});
			});
		},
		delete:function(record_id,fn){
			$post($http,_host+"record/delete",{'record_id':record_id}).success(function(r){
				viewResult(r,function(r){
					var arr = [];
					obj.data.forEach(function(ele,index){
						if(ele.record_id != record_id){
							arr.push(ele);
						}
					});
					obj.data = arr;
					fn();
				});
			});
		}
	};
	return obj;
});

myapp.controller('userCtrl',function($scope,$http,$route,areaService,resourceService,employeeService,userService,businessService,accountingService,recordService){

	$scope.level = _config.level;
	initPermission(_permission);

	$scope.initYear = function(othis){
		dateComponent.initYear($scope,othis,function(year){
			userService.filterParams.year = year;
			init(1,function(){
				layer.closeAll('loading');
			});
		});
	}

	$scope.clearDate = function(){
		dateComponent.clear($scope,function(){
			delete userService.filterParams.year;
			delete userService.filterParams.month;
			init(1,function(){
				layer.closeAll('loading');
			});
		});
	};

	$scope.initMonth= function(othis){
		dateComponent.initMonth($scope,othis,function(month){
			userService.filterParams.month = month;
			init(1,function(){
				layer.closeAll('loading');
			});
		});
	}

	//部门员工树形菜单
	userService.loadTree(function(r){
		$("#tree-show").append(buildEmployeeTree(r,function(obj,span){

			userService.filterParams.department_id = null;
			userService.filterParams.employee_id = null;

			if(obj.department_id){
				userService.filterParams.department_id = obj.department_id;
			}
			if(obj.employee_id){
				userService.filterParams.department_id = null;
				userService.filterParams.employee_id = obj.employee_id;
			}
			$scope.select_department = obj.name;
		}));
	});

	$scope.reload = function(othis){
		init(1,function(){
			layer.closeAll('loading');
		});
		$(othis).prev().trigger('click');
	};

	$scope.changeStatus = function(){
		userService.filterParams.status = $scope.status;
		init(1,function(){
			layer.closeAll('loading');
		});
	};

	$scope.closewin = function(){
		closeright(function(){
			userService.rightwin = false;
		});
	};

	$scope.selectCom = function(othis,e){
		var key = e.keyCode;
		if(key == 13){
			var value = othis.value ? othis.value.trim() : "";
			if(value.length > 0){

				if(/^1[345789]\d*$/.test(value)){
					delete userService.filterParams.com;
					userService.filterParams.phone = value;
					init(1,function(){
						layer.closeAll("loading");
					});
				}else{
					delete userService.filterParams.phone;
					userService.filterParams.com = value;
					init(1,function(){
						layer.closeAll("loading");
					});
				}
			}else{
				delete userService.filterParams.com;
				delete userService.filterParams.phone;
				init(1,function(){
					layer.closeAll("loading");
				});
			}
		}
	};

	userService.rightwin = false;

	$scope.vanAddGuest = function(){
		resourceService.get(function(data){
			$scope.resource = data;
		});
		areaService.get(function(r){
			$scope.areas = r;
		});
	};

	//初始化用户页面
	function init(current,fn){
		// layer.load();
		var arg = arguments;
		userService.get({"page":current},function(r){
			$scope.guests = r.data;
			var pagination = pageit(current,r.total);
			if(pagination.length > 0){
				$scope.pagination = pagination;
				$scope.current = current;
				$scope.getPage = function(othis){
					var want_current = $(othis).attr('data-current');
					if(want_current != current){
						layer.load();
						arg.callee(want_current,fn);
					}
				};
			}
			fn();
		});
	}

	init(1,function(){
		layer.closeAll('loading');
	});

	//按页码获取
	$scope.getByPage = function(othis){
		userService.get($http,{page:this.p},function(r){
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
		recordService.add($scope,function(){
			$scope.follow_record = recordService.data;
			$(othis).prev().trigger('click');
			//更新追踪记录次数
			var ele = $(recordService.viewing_ele),
				count = ele.attr('data-count');
			$scope.add_record = "";
			if(count){
				ele.text(parseInt(count)+1);
				ele.attr('data-count',parseInt(count)+1);
			}else{
				ele.text(1);
				ele.attr('data-count',1);
			}
		});
	};
	//预 编辑跟进记录
	$scope.vanEditRecord = function(f){
		$scope.edit_record = f.content;
		recordService.record_id = f.record_id;
	};
	//编辑跟进记录
	$scope.editRecord = function(othis){
		recordService.update($scope.edit_record,function(){
			$(othis).prev().trigger('click');
			$scope.follow_record = recordService.data;
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
	
	$scope.initBusiness = function(){
		businessService.initBusiness(function(r){
			$scope.process_group = r;
			$scope.process_group_id = r[0].process_group_id;
		});
		employeeService.findAccountings(function(r){
			$scope.employee = r;
			$scope.employee_id = r[0].employee_id;
		});
	};

	$scope.vanAddAccounting = function(){
		employeeService.findAccountings(function(r){
			$scope.employee = r;
			$scope.accounting_employee_id = r[0].employee_id;
		});
	};

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
	};

	$scope.initRight = function(u){

		businessService.guest_id = u.guest_id;
		$scope.follow_record = [];

		if(u.employee_id == _config.employee_id){
			$scope.can_edit = true;
		}else{
			$scope.can_edit = false;
		}

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

			userService.guest_id = u.guest_id;
			//查询工商开通服务
			businessService.findOpen(u.guest_id,function(r){
				$scope.opens = r;
			});
			//查询代理记账开通服务
			accountingService.findOpen(u.guest_id,function(r){
				var data = {'deadline':'暂未开始交费','status':'欠费'};
				if(r && r.guest_id){
					data.open = true;
					if(r.deadline != null){
						if(r.deadline > datenow()){
							data.status = '正常';
						}
						data.deadline = r.deadline;
					}
					$scope.accounting = data;
				}else{
					$scope.accounting = {'open':false};
				}
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
				//查询工商开通服务
			businessService.findOpen(u.guest_id,function(r){
				$scope.opens = r;
			});
			//查询代理记账开通服务
			accountingService.findOpen(u.guest_id,function(r){
				var data = {'deadline':'暂未开始交费','status':'欠费'};
				if(r && r.guest_id){
					data.open = true;
					if(r.deadline != null){
						if(r.deadline > datenow()){
							data.status = '正常';
						}
						data.deadline = r.deadline;
					}
					$scope.accounting = data;
				}else{
					$scope.accounting = {'open':false};
				}
			});
			}
		}
	};

	$scope.getRecord = function(guest_id,e){
		recordService.guest_id = guest_id;
		recordService.viewing_ele = e.target;//保存点击的追踪记录对象
		recordService.get(function(){
			$scope.follow_record = recordService.data;
		});
	};

	$scope.deleteRecord = function(record_id){
		layer.msg('确定删除？', {
		    time: 0
		    ,btn: ['确定', '取消']
		    ,yes: function(index){
		        layer.close(index);
				recordService.delete(record_id,function(){
					$scope.follow_record = recordService.data;
					//更新追踪记录次数
					var ele = $(recordService.viewing_ele),
						count = ele.attr('data-count');
					if(count){
						ele.text(parseInt(count)-1);
						ele.attr('data-count',parseInt(count)-1);
					}else{
						ele.text(0);
						ele.attr('data-count',0);
					}
				});
		    }
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
				layer.msg('添加成功');
				$(ele).prev().trigger('click');
			});
		}else{
			layer.msg('必须指定负责人');
		}
	};
	//
	$scope.vanEditGuest = function(){
		areaService.get(function(r){
			$scope.areas = r;
		});
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
				// console.log(r);
				viewResult(r,function(r){
					obj.data = r;
					fn(r);
				});
			});
		},
		edit:function(fn){
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
			$post($http,_host+"process/update",{'process_group_id':obj.select_id,'values':arr}).success(function(r){
				viewResult(r,function(r){
					obj.data = r;
					fn(r);
				});
			});
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
				viewResult(r,function(r){
					fn(r);
				});
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
			viewResult(r,function(r){
				$scope.edit_process = r;
			});
		});
	};

	$scope.edit = function(ele){
		processService.edit(function(){
			if(ele.nodeName == 'I'){
				$(ele).parent().prev().trigger('click');
			}else{
				$(ele).prev().trigger('click');
			}
			$scope.processes = processService.data;
		});
	};

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


myapp.service('progressService',function($http){
	var obj = {
		add:function(scope,fn){
			var process_id = scope.process_id,
				date_end = $("#finish_date_end").val(),
				note = scope.note;

			if(typeof process_id == 'undefined'){
				layer.msg('请指定进度');
				return;
			}
			if(!validate('date',date_end)){
				layer.msg('请指定日期');
				return;
			}
			if(typeof note == 'undefined' || note.length < 2){
				layer.msg('备注不能少于2个字符');
				return;
			}
			if(!obj.select_id){
				layer.msg('请指定记录');
				return;
			}
			// console.log(process_id,date_end,obj.select_id,note);
			// return;
			layer.load();
			$post($http,_host+"progress/save",{
				'process_id':process_id,
				'date_end':date_end,
				'business_id':obj.select_id,
				'note':note
			}).success(function(r){
				layer.closeAll('loading');
				viewResult(r,function(r){
					fn();
				});
			});
		},
		get:function(business_id,fn){
			$post($http,_host+"progress/find",{'business_id':business_id}).success(function(r){
				viewResult(r,function(r){
					fn(r);
				});
			});
		},
		edit:function(scope,fn){
			var date_end = $("#edit_finish_date_end").val().trim(),
				process_id = scope.edit_process_id,
				progress_id = obj.progress_id,
				business_id = obj.business_id,
				note = scope.edit_note;
			// console.log(date_end,process_id,progress_id,business_id,note);
			// return;
			$post($http,_host+"progress/update",{
				'progress_id':progress_id,
				'business_id':business_id,
				'process_id':process_id,
				'note':note,
				'date_end':date_end
			}).success(function(r){
				viewResult(r,function(r){
					fn(r);
				});
			});
		},
		delete:function(progress_id,fn){
			$post($http,_host+"progress/delete",{'progress_id':progress_id}).success(function(r){
				viewResult(r,function(r){
					fn();
				});
			});
		}
	};
	return obj;
});

myapp.service('businessService',function($http){
	var obj = {
		filterParams:{"status":1},
		get:function(params,fn){
			if(obj.filterParams){
				for(f in obj.filterParams){
					params[f] = obj.filterParams[f];
				}
			}
			$post($http,_host+"business/find",params).success(function(r){
				viewResult(r,function(r){
					obj.data = r.data;
					fn(r);
				});
			});
		},
		add:function(scope,fn){
			var process_group_id = scope.process_group_id,
				employee_id = scope.employee_id,
				should_fee = scope.should_fee,
				have_fee = scope.have_fee;

			if(obj.guest_id == 0){
				layer.msg('请先指定用户');
				return;
			}
			if(!process_group_id){
				layer.msg('请先指定工商类型');
				return;	
			}
			if(!employee_id){
				layer.msg('请先指定负责人');
				return;
			}
			if(isNaN(should_fee) || parseFloat(should_fee) <= 0){
				layer.msg('应收款只能是正数');
				return;
			}
			if(isNaN(have_fee) || parseFloat(have_fee) < 0){
				layer.msg('已付款不能为负数');
				return;
			}

			layer.load();
			$post($http,_host+"business/save",{
				'guest_id':obj.guest_id,
				'process_group_id':process_group_id,
				'employee_id':employee_id,
				'should_fee':should_fee,
				'have_fee':have_fee
			}).success(function(r){
				layer.closeAll('loading');
				viewResult(r,function(r){
					fn(r);
				});
			});
		},
		requestEmployee:function(http,scope,fn){
			return function(){
				$post(http,_host+"employee/findByDepartmentid",{'department_id':scope.department_id}).success(function(r){
					viewResult(r,function(r){
						fn(r);
					});
				});
			};
		},
		requestProcess:function(fn){
			$post($http,_host+"process/getAll",{}).success(function(r){
				viewResult(r,function(r){
					fn(r);
				});
			});
		},
		initBusiness:function(fn){
			//业务类型
			$post($http,_host+"processgroup/find",{}).success(function(r){
				viewResult(r,function(r){
					fn(r);
				});
			});
		},
		updateFee:function(scope,fn){
			var should_fee = scope.edit_should,
				have_fee = scope.edit_have;

			if(!obj.business_id){
				layer.msg('请指定记录');
				return;
			}
			if(isNaN(should_fee) || parseFloat(should_fee) <= 0){
				layer.msg('应收款必须为正数');
				return;
			}
			if(isNaN(have_fee) || parseFloat(have_fee) < 0){
				layer.msg("已收款不能为负数");
				return;
			}
			$post($http,_host+"business/updateFee",{
				'business_id':obj.business_id,
				'should_fee':should_fee,
				'have_fee'	:have_fee
			}).success(function(r){
				viewResult(r,function(r){
					obj.data.forEach(function(ele,index){
						if(ele.business_id == obj.business_id){
							obj.data[index].should_fee = should_fee;
							obj.data[index].have_fee = have_fee;
							return;
						}
					});
					fn(r);
				});
			});
		},
		updateStatus:function(business_id,fn){
			$post($http,_host+"business/updateStatus",{'business_id':business_id}).success(function(r){
				viewResult(r,function(r){
					fn(r);
				});
			});
		},
		findOpen:function(guest_id,fn){
			//所有用户->详细信息面板
			$post($http,_host+"business/findOpen",{'guest_id':guest_id}).success(function(r){
				viewResult(r,function(r){
					fn(r);
				});
			});
		}
	};
	return obj;
});


myapp.controller('businessCtrl',function($scope,$http,businessService,progressService,processService,userService){

	$scope.level = _config.level;

	businessService.requestProcess(function(r){
		$scope.processgroup = r;
	});

	$scope.initYear = function(othis){
		dateComponent.initYear($scope,othis,function(year){
			businessService.filterParams.year = year;
			init(1,function(){
				layer.closeAll('loading');
			});
		});
	}

	$scope.clearDate = function(){
		dateComponent.clear($scope,function(){
			delete businessService.filterParams.year;
			delete businessService.filterParams.month;
			init(1,function(){
				layer.closeAll('loading');
			});
		});
	};

	$scope.initMonth= function(othis){
		dateComponent.initMonth($scope,othis,function(month){
			businessService.filterParams.month = month;
			init(1,function(){
				layer.closeAll('loading');
			});
		});
	}

	$scope.selectStatus = function(){
		businessService.filterParams.status = $scope.status;
		init(1,function(){
			layer.closeAll('loading');
		});
	};

	$scope.selectCom = function(othis,e){
		var key = e.keyCode,
			value = othis.value ? othis.value.trim() : "";

		if(key == 13){
			if(value.length > 0){
				if(/^1[345789]\d*$/.test(value)){
					delete businessService.filterParams.com;
					businessService.filterParams.phone = value;
					init(1,function(){layer.closeAll("loading");});
				}else{
					delete businessService.filterParams.phone;
					businessService.filterParams.com = value;
					init(1,function(){layer.closeAll("loading");});
				}
			}else{
				delete businessService.filterParams.com;
				delete businessService.filterParams.phone;
				init(1,function(){layer.closeAll("loading");});
			}
		}
	};

	$scope.selectProcess = function(othis,process_group_id,groupname,r){
		// console.log(othis,process_group_id,groupname,r);
		if(typeof process_group_id == 'undefined'){
			delete businessService.filterParams.process_id;
			$scope.process_str = "";
		}else{
			$scope.process_str = groupname+">"+r.name;
			businessService.filterParams.process_id = r.process_id;
		}
		init(1,function(){
			layer.closeAll("loading");
		});
	};

	function initTree(obj,span){
		businessService.filterParams.department_id = null;
		businessService.filterParams.employee_id = null;

		if(obj.department_id){
			businessService.filterParams.department_id = obj.department_id;
		}
		if(obj.employee_id){
			businessService.filterParams.department_id = null;
			businessService.filterParams.employee_id = obj.employee_id;
		}
		$scope.select_department = obj.name;
	}

	$scope.loadTree = function(){
		if(!userService.trees){
			userService.loadTree(function(r){
				userService.trees = r;
				$("#tree-show").append(buildEmployeeTree(r,initTree));
			});
		}else{
			if($("#tree-show").html().trim().length == 0){
				$("#tree-show").append(buildEmployeeTree(r,initTree));
			}
		}
	};

	$scope.reload = function(othis){
		init(1,function(){
			layer.closeAll("loading");
		});
		$(othis).prev().trigger('click');
	};

	function init(current,fn){
		var arg = arguments;
		businessService.get({"page":current},function(r){
			$scope.business = businessService.data;
			var pagination = pageit(current,r.total);
			if(pagination.length > 0){
				$scope.pagination = pagination;
				$scope.current = current;
				$scope.getPage = function(othis){
					var want_current = $(othis).attr('data-current');
					if(want_current != current){
						layer.load();
						arg.callee(want_current,fn);
					}
				};
			}
			fn();
		});
	}

	init(1,function(){
		layer.closeAll('loading');
	});

	$scope.updateStatus = function(business_id,employee_id){
		var othis = this;
		if(othis.u.status == 0){
			businessService.updateStatus(business_id,function(r){
				othis.u.status = 1;
			});
		}
	};

	$scope.vanEditFee = function(u){
		$scope.edit_should = u.should_fee;
		$scope.edit_have = u.have_fee;
		businessService.business_id = u.business_id;
	};

	$scope.editProgress = function(othis){
		progressService.edit($scope,function(r){
			progressService.get(progressService.business_id,function(r){
				$scope.progress = r;
			});
			$(othis).prev().trigger('click');
		});
	}

	$scope.editFee = function(othis){
		businessService.updateFee($scope,function(r){
			$scope.business = businessService.data;
			$(othis).prev().trigger('click');
		});
	};
	//添加进度
	$scope.addProgress = function(ele){
		progressService.add($scope,function(){
			$("#finish_date_end").val("");
			$scope.note = "";
			layer.msg('添加成功');
			$(ele).prev().trigger('click');
		});
	};

	$scope.vanEditProgress = function(u,othis){
		$scope.edit_note = u.note;
		$scope.edit_process_id = u.process_id;
		$("#edit_finish_date_end").val(u.date_end);
		progressService.progress_id = u.progress_id;
		progressService.business_id = u.business_id;
	};

	//初始化进度
	$scope.vanAddProgress = function(u){
		var business_id = u.business_id,
			process_group_id = u.process_group_id,
			employee_id = u.employee_id;

		if(u.status == 0){
			layer.msg('请先受理，再添加进度');
		}
		
		progressService.select_id = business_id;
		$scope.company = u.company;

		progressService.get(business_id,function(r){
			$scope.progress = r;
		});
		processService.getByGroupid(process_group_id,function(r){
			console.log(r);
			$scope.process = r;
			$scope.process_id = r[0].process_id;
		});
	}
	//删除进度
	$scope.deleteProgress = function(othis,progress_id,business_id){
		progressService.delete(progress_id,function(){
			progressService.get(business_id,function(r){
				$scope.progress = r;
			});
		});
	};
});

myapp.service('accountingService',function($http){
	var obj = {
		filterParams:{'status':1,'owe':0},
		get:function(params,fn){
			if(obj.filterParams){
				for(f in obj.filterParams){
					params[f] = obj.filterParams[f];
				}
			}
			$post($http,_host+"accounting/find",params).success(function(r){
				viewResult(r,function(r){
					obj.data = r.data;
					fn(r);
				});
			});
		},
		add:function(guest_id,employee_id,fn){
			$post($http,_host+"accounting/save",{'employee_id':employee_id,'guest_id':guest_id}).success(function(r){
				viewResult(r,function(r){
					fn(r);
				});
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
		updateStatus:function(accounting_id,fn){
			$post($http,_host+"accounting/updateStatus",{'accounting_id':accounting_id}).success(function(r){
				viewResult(r,function(r){
					fn(r);
				});
			});
		},
		findOpen:function(guest_id,fn){
			$post($http,_host+"accounting/findOpen",{'guest_id':guest_id}).success(function(r){
				viewResult(r,function(r){
					fn(r);
				});
			});
		}
	};
	return obj;
});
myapp.controller('accountingCtrl',function($scope,$http,accountingService,userService){

	$scope.today = datenow();
	$scope.today15after = datenow(15);
	$scope.level = _config.level;

	$scope.initYear = function(othis){
		dateComponent.initYear($scope,othis,function(year){
			accountingService.filterParams.year = year;
			init(1,function(){
				layer.closeAll('loading');
			});
		});
	}

	$scope.clearDate = function(){
		dateComponent.clear($scope,function(){
			delete accountingService.filterParams.year;
			delete accountingService.filterParams.month;
			init(1,function(){
				layer.closeAll('loading');
			});
		});
	};

	$scope.initMonth= function(othis){
		dateComponent.initMonth($scope,othis,function(month){
			accountingService.filterParams.month = month;
			init(1,function(){
				layer.closeAll('loading');
			});
		});
	}

	$scope.selectCom = function(othis,e){
		var key = e.keyCode,
			value = othis.value ? othis.value.trim() : "";

		if(key == 13){
			if(value.length > 0){
				if(/^1[345789]\d*$/.test(value)){
					delete accountingService.filterParams.com;
					accountingService.filterParams.phone = value;
					init(1,function(){layer.closeAll("loading");});
				}else{
					delete accountingService.filterParams.phone;
					accountingService.filterParams.com = value;
					init(1,function(){layer.closeAll("loading");});
				}
			}else{
				delete accountingService.filterParams.com;
				delete accountingService.filterParams.phone;
				init(1,function(){layer.closeAll("loading");});
			}
		}
	};

	$scope.selectStatus = function(){
		accountingService.filterParams.status = $scope.status;
		init(1,function(){
			layer.closeAll('loading');
		});
	};

	$scope.selectFee = function(){
		accountingService.filterParams.owe = $scope.owe;
		init(1,function(){
			layer.closeAll("loading");
		});
	};

	function initTree(obj,span){
		accountingService.filterParams.department_id = null;
		accountingService.filterParams.employee_id = null;

		if(obj.department_id){
			accountingService.filterParams.department_id = obj.department_id;
		}
		if(obj.employee_id){
			accountingService.filterParams.department_id = null;
			accountingService.filterParams.employee_id = obj.employee_id;
		}
		$scope.select_department = obj.name;
	}

	$scope.loadTree = function(){
		if(!userService.trees){
			userService.loadTree(function(r){
				userService.trees = r;
				$("#tree-show").append(buildEmployeeTree(r,initTree));
			});
		}else{
			if($("#tree-show").html().trim().length == 0){
				$("#tree-show").append(buildEmployeeTree(userService.trees,initTree));
			}
		}
	};

	$scope.reload = function(othis){
		init(1,function(){
			layer.closeAll("loading");
		});

		$(othis).prev().trigger('click');
	};

	function init(current,fn){
		var arg = arguments;
		accountingService.get({"page":current},function(r){
			$scope.accounting = r.data;
			var pagination = pageit(current,r.total);
			if(pagination.length > 0){
				$scope.pagination = pagination;
				$scope.current = current;
				$scope.getPage = function(othis){
					var want_current = $(othis).attr('data-current');
					if(want_current != current){
						layer.load();
						arg.callee(want_current,fn);
					}
				};
			}
			fn();
		});
	}

	init(1,function(){
		layer.closeAll('loading');
	});
	
	$scope.updateStatus = function(accounting_id,employee_id){
		var othis = this;
		if(othis.u.status == 0){
			accountingService.updateStatus(accounting_id,function(){
				othis.u.status = 1;
			});
		}
	};
});

myapp.service('taxService',function($http){
	var obj = {
		filterParams:{},
		get:function(params,fn){
			if(obj.filterParams){
				for(f in obj.filterParams){
					params[f] = obj.filterParams[f];
				}
			}
			$post($http,_host+"taxcount/find",params).success(function(r){
				viewResult(r,function(r){
					obj.data = r.data;
					fn(r);
				});
			});
		},
		initTaxtype:function(parent_id,fn){
			$post($http,_host+"taxtype/findByParentid",{'parent_id':parent_id}).success(function(r){
				viewResult(r,function(r){
					fn(r);
				});
			});
		},
		add:function(scope,fn){
			var nation = scope.nation_count,
				local = scope.local_count,
				year = scope.add_count_year,
				month = scope.add_count_month;

			if(!validate('year',year)){
				layer.msg('年份不正确');
				return;
			}
			if(!validate('month',month)){
				layer.msg('月份不正确');
				return;
			}
			if(!validate('number',nation)){
				layer.msg('国税金额不正确');
				return;
			}
			if(!validate('number',local)){
				layer.msg('地税金额不正确');
				return;
			}

			if(typeof obj.guest_id == 'undefined'){
				layer.msg('不能添加');
				return;
			}

			$post($http,_host+"taxcount/save",{
				'guest_id':obj.guest_id,
				'year':year,
				'month':month,
				'nation':nation,
				'local':local
			}).success(function(r){
				viewResult(r,function(r){
					fn(r);
				})
			});
		},
		editCount:function(scope,fn){
			var nation = scope.edit_nation,
				local = scope.edit_local;

			if(!validate('number',nation)){
				layer.msg('国税金额不正确');
				return;
			}
			if(!validate('number',local)){
				layer.msg('地税金额不正确');
				return;
			}

			if(typeof obj.tax_count_id == 'undefined'){
				layer.msg('请指定编辑的记录');
				return;
			}
			if(typeof obj.guest_id == 'undefined'){
				layer.msg('请指定编辑的用户');
				return;
			}
			$post($http,_host+"taxcount/update",{
				'tax_count_id':obj.tax_count_id,
				'guest_id':obj.guest_id,
				'nation':nation,
				'local':local
			}).success(function(r){
				viewResult(r,function(r){
					obj.data.forEach(function(ele,index){
						if(ele.tax_count_id == obj.tax_count_id){
							obj.data[index].nation = nation;
							obj.data[index].local = local;
							return;
						}
					});
					fn();
				});
			});
		}
	};
	return obj;
});

myapp.service('taxcollectService',function($http){
	var obj = {
		get:function(params,fn){
			$post($http,_host+"taxcollect/find",params).success(function(r){
				// console.log(r);
				viewResult(r,function(r){
					obj.data = r;
					fn(r);
				});
			});
		},
		add:function(data,fn){
			$post($http,_host+"taxcollect/save",{'data':data}).success(function(r){
				viewResult(r,function(r){
					fn(r);
				});
			});
		},
		update:function(scope,fn){
			var fee = scope.e_one_fee;

			if(!obj.guest_id){
				layer.msg('请先指定用户');
				return;
			}
			if(!obj.tax_collect_id){
				layer.msg('请先指定记录');
				return;
			}
			if(!validate('number',fee) || parseFloat(fee) < 0){
				layer.msg('请输入正确的金额');
				return;
			}

			layer.load();
			$post($http,_host+"taxcollect/update",{
				'tax_collect_id':obj.tax_collect_id,
				'fee':fee,
				'guest_id':obj.guest_id
			}).success(function(r){
				layer.closeAll('loading');
				viewResult(r,function(r){
					obj.data.forEach(function(ele,index){
						if(ele.tax_collect_id == obj.tax_collect_id){
							obj.data[index].fee = fee;
							return;
						}
					});
					fn();
				});
			});
		},
		delete:function(tax_collect_id,fn){
			$post($http,_host+"taxcollect/delete",{'tax_collect_id':tax_collect_id}).success(function(r){
				viewResult(r,function(r){
					var arr = [];
					obj.data.forEach(function(ele,index){
						if(ele.tax_collect_id != tax_collect_id){
							arr.push(ele);
						}
					});
					obj.data = arr;
					fn();
				});
			});
		}
	};
	return obj;
});

myapp.controller('taxCtrl',function($scope,taxService,taxcollectService){

	initPermission(_permission);

	$scope.initYear = function(othis){
		dateComponent.initYear($scope,othis,function(year){
			taxService.filterParams.year = year;
			init(1,function(){
				layer.closeAll('loading');
			});
		});
	}

	$scope.clearDate = function(){
		dateComponent.clear($scope,function(){
			delete taxService.filterParams.year;
			delete taxService.filterParams.month;
			init(1,function(){
				layer.closeAll('loading');
			});
		});
	};

	$scope.initMonth= function(othis){
		dateComponent.initMonth($scope,othis,function(month){
			taxService.filterParams.month = month;
			init(1,function(){
				layer.closeAll('loading');
			});
		});
	}

	$scope.selectCom = function(othis,e){
		var key = e.keyCode,
			value = othis.value ? othis.value.trim() : "";

		if(key == 13){
			if(value.length > 0){
				if(/^1[345789]\d*$/.test(value)){
					delete taxService.filterParams.com;
					taxService.filterParams.phone = value;
					init(1,function(){layer.closeAll("loading");});
				}else{
					delete taxService.filterParams.phone;
					taxService.filterParams.com = value;
					init(1,function(){layer.closeAll("loading");});
				}
			}else{
				delete taxService.filterParams.com;
			delete taxService.filterParams.phone;
				init(1,function(){layer.closeAll("loading");});
			}
		}
	};

	function init(current,fn){
		var arg = arguments;
		taxService.get({"page":current},function(r){
			$scope.tax = r.data;
			var pagination = pageit(current,r.total);
			if(pagination.length > 0){
				$scope.pagination = pagination;
				$scope.current = current;
				$scope.getPage = function(othis){
					var want_current = $(othis).attr('data-current');
					if(want_current != current){
						layer.load();
						arg.callee(want_current,fn);
					}
				};
			}
			fn();
		});
	}

	init(1,function(){
		layer.closeAll('loading');
	});

	$scope.filterName = function(name){
		$scope.keywords = name;
		taxService.filterParams.com = name;
		init(1,function(){
			layer.closeAll('loading');
		});
	};

	$scope.closewin = function(){
		closeright(function(){
			taxService.rightwin = false;
		});
	};

	$scope.initTaxtype = function(){
		$scope.add_year = taxService.year;
		$scope.add_month = taxService.month;
		taxService.initTaxtype(1,function(r){
			$scope.nation = r;
		});
		taxService.initTaxtype(2,function(r){
			$scope.local = r;
		});
	};

	$scope.initOne = function(u){
		taxcollectService.tax_collect_id = u.tax_collect_id;
		$scope.e_one_fee = u.fee;
		$scope.one_name = u.name;
	};

	$scope.editOne = function(othis){
		taxcollectService.update($scope,function(){
			$scope.taxlist = taxcollectService.data;
			$(othis).prev().trigger('click');
		});
	};

	$scope.deleteOne = function(tax_collect_id){
		layer.msg('确定删除？', {
		    time: 0
		    ,btn: ['确定', '取消']
		    ,yes: function(index){
		        layer.close(index);
				taxcollectService.delete(tax_collect_id,function(){
					$scope.taxlist = taxcollectService.data;
				});
		    }
		});
	};

	$scope.add = function(othis){
		var year = $scope.add_year,
			month = $scope.add_month,
			nation = $("#add-nation").find('.row'),
			local = $("#add-local").find('.row');

		
		if(!validate('year',year)){
			layer.msg('年份不正确');
			return;
		}
		if(!validate('month',month)){
			layer.msg('月份不正确');
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
			'guest_id':taxcollectService.guest_id,
			'year':year,
			'month':month,
			'nationData':nation_arr,
			'localData':local_arr
		};

		taxcollectService.add(data,function(){
			layer.msg('添加成功');
			$(othis).prev().trigger('click');
		});
	}

	$scope.addCount = function(othis){
		taxService.add($scope,function(){
			layer.msg('添加成功');
			$(othis).prev().trigger('click');
		});
	}
	
	$scope.vanEditCount = function(u){
		// console.log(u);
		$scope.edit_count_year = u.year;
		$scope.edit_count_month = u.month;
		$scope.edit_nation = u.nation;
		$scope.edit_local = u.local;
		taxService.guest_id = u.guest_id;
		taxService.tax_count_id = u.tax_count_id;
	};

	$scope.editCount = function(othis){
		taxService.editCount($scope,function(){
			// layer.msg('更新成功');
			$(othis).prev().trigger('click');
		});
	};

	$scope.findList = function(u){
		taxcollectService.year = u.year;
		taxcollectService.month = u.month;
		taxcollectService.guest_id = u.guest_id;
		if(taxcollectService.year != null && taxcollectService.month != null){
			taxcollectService.get({'year':taxcollectService.year,'month':taxcollectService.month,'guest_id':taxcollectService.guest_id},function(r){
				$scope.taxlist = taxcollectService.data;
			});
		}else{
			layer.msg('没有详细记录');
		}
	};

	$scope.initRight = function(u){
		
		$scope.intro = u.company+"/"+u.name;
		taxService.year = u.year;
		taxService.month = u.month;
		taxService.guest_id = u.guest_id;
		taxcollectService.guest_id = u.guest_id;

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

myapp.service('todoService',function($http){
	var obj = {
		get:function(params,fn){
			$post($http,_host+"todo/findAll",params).success(function(r){
				viewResult(r,function(r){
					viewResult(r,function(r){
						obj.data = r;
						fn(r);
					});
				});
			});	
		},
		getEmployee:function(fn){
			if(!obj.employees){
				$post($http,_host+"department/findWholeMenu",{}).success(function(r){
					viewResult(r,function(r){
						obj.trees = r;
						fn(r);
					});
				});
			}else{
				fn(obj.trees);
			}
		},
		add:function($scope,fn){
			var task_content = ($scope.add_task_content || "").trim(),
				date_start = ($("#date_start_task").val() || '').trim(),
				date_end = ($("#date_end_task").val() || '').trim();

				if(task_content.length < 1){
					layer.msg('任务内容不能为空')
					return;
				}

				if(!validate('date',date_start)){
					layer.msg('起始日期不能为空');
					return;
				}

				if(!validate('date',date_end)){
					layer.msg('终止日期不能为空');	
					return;
				}

				if(typeof obj.employees == 'undefined' || obj.employees.length == 0){
					layer.msg('请先指定指派人');	
					return;
				}

				layer.load();
				var arr = [],
					tmp = "";
				for(tmp in obj.employees){
					arr.push(tmp);
				}
				$post($http,_host+"todo/save",{
					'task_content':task_content,
					'date_start':date_start,
					'date_end':date_end,
					'accepters':arr
				}).success(function(r){
					layer.closeAll('loading');
					viewResult(r,function(r){
						if(!obj.data){
							obj.data = [];
						}
						obj.data.splice(0,0,r.data[0]);
						fn(r);
					});
				});

		},
		editTask:function(http,$scope,fn){
			return function(othis){			
				var todo_id=obj.todo_id,
					task_content = $scope.edit_task_content,
					date_start=$("#date_start_task1").val(),
					date_end=$("#date_end_task1").val(),
					sender=obj.sender,
					accepter=obj.employee_id;
					layer.load();
					console.log(sender);
					$post(http,_host+"todo/update",{
						'todo_id':todo_id,
						'task_content':task_content,
						'date_start':date_start,
						'date_end':date_end,
						'sender':sender,
						'accepter':accepter
					}).success(function(r){
						layer.closeAll('loading');
						if(r.affected_rows==1){
							obj.data.forEach(function(ele,index){
								if(ele.todo_id==todo_id){
									obj.data[index]=r.data[0];
								}
							});
							
							fn(othis);
						}else{
							layer.msg('没有更新');
						}
					});
			}
		}
	};
	return obj;
});

myapp.controller('todoCtrl',function($scope,$http,todoService){

	//zgj 2015-12-2 添加分页
	void function (current,fn){
		var arg = arguments;
		todoService.get({"page":current},function(r){
			$scope.todos = r.data;
			var pagination = pageit(current,r.total);
			if(pagination.length > 0){
				$scope.pagination = pagination;
				$scope.current = current;
				$scope.getPage = function(othis){
					var want_current = $(othis).attr('data-current');
					if(want_current != current){
						layer.load();
						arg.callee(want_current,fn);
					}
				}
			}
			fn();
		})
	}(1,function(){
		layer.closeAll('loading');
	});
	
	$scope.sendSubTodo = function(){
		$scope.copytask = this.u.task;
	};

	$scope.getEmployee = function(){
		if(!todoService.trees){
			todoService.getEmployee(function(r){
				$("#tree-show").empty().append(buildSelectEmployeeTree(r,function(obj,span,tag){
					if(typeof obj.employee_id != 'undefined' && typeof tag != 'undefined'){
						

						if(!todoService.employees){
							todoService.employees = {};
						}

						if(tag == true){
							todoService.employees[obj.employee_id] = obj.name;
						}

						if(tag == false){
							delete todoService.employees[obj.employee_id];
						}
						var arr = [],
							tmp = "";
						for(tmp in todoService.employees){
							arr.push(todoService.employees[tmp]);
						}
						$scope.employee_name = arr.join(",");
						$scope.$apply();
					}
				}));
			});
		}
	};

	$scope.add = function(){
		todoService.add($scope,function(ele){
			if(ele.nodeName == 'I'){
				$(ele).parent().prev().trigger('click');
			}else{
				$(ele).prev().trigger('click');
			}
			//清空scope中自定义变量
			for(s in $scope){
				var x = s.toString();
				if(x.indexOf('add_')>-1){
					$scope[s] = '';
				}	
			}
			$("#date_start_task").val('');
			$("#date_end_task").val('');
			$scope.todos = todoService.data;
		});
	};

	$scope.editTask = todoService.editTask($http,$scope,function(ele){
		if(ele.nodeName == 'I'){
			$(ele).parent().prev().trigger('click');
		}else{
			$(ele).prev().trigger('click');
		}
		$scope.add_task_content='a';
		$scope.searchstr='a';
		$scope.todos = todoService.data;
	});

	$scope.initEdit = function(){
		$scope.edit_task_content=this.u.task_content;
		$scope.searchstr=this.u.accepter;
		$("#date_start_task1").val(this.u.date_start);
		$("#date_end_task1").val(this.u.date_end);
		todoService.todo_id=this.u.todo_id;
		todoService.employee_id=this.u.accepter_id;
	}

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
				viewResult(r,function(r){
					obj.data = r.data;
					fn(r);
				});
			});
		},
		getByDepartmentid:function(department_id,fn){
			$post($http,_host+"employee/findByDepartmentid",{'department_id':department_id}).success(function(r){
				viewResult(r,function(r){
					obj.data = r;
					fn();
				});
			});
		},
		add:function(scope,fn){
			var name = scope.e_name,
				phone = scope.e_phone,
				sex = scope.e_sex,
				roles_id = scope.e_roles_id || 0,
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
				'roles_id':roles_id,
				'department_id':department_id
			}).success(function(r){
				layer.closeAll('loading');
				viewResult(r,function(r){
					obj.data.splice(0,0,r);
					fn();
				});				
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
			
			$post($http,_host+"employee/update",{
				'name':name,
				'phone':phone,
				'sex':scope.e_sex,
				'department_id':scope.e_department_id,
				'roles_id':scope.e_roles_id,
				'employee_id':obj.employee_id
			}).success(function(r){
				viewResult(r,function(r){
					obj.data.forEach(function(ele,index){
						if(ele.employee_id == r.employee_id){
							obj.data[index] = r;
						}
					});
					fn();
				});
			});
		},
		updateStatus:function(fn){
			$post($http,_host+"employee/updateStatus",{'status':0,'employee_id':obj.employee_id}).success(function(r){
				viewResult(r,function(r){
					var arr = [];
					obj.data.forEach(function(ele,index){
						if(ele.employee_id != obj.employee_id){
							arr.push(ele);
						}
					});
					obj.data = arr;
					fn();
				});
			});
		},
		findAccountings:function(fn){
			$post($http,_host+"employee/findAccountings",{}).success(function(r){
				viewResult(r,function(r){
					fn(r);
				});
			});
		}
	};
	return obj;
});

myapp.service('departmentService',function($http){
	var obj = {
		data:[],
		edit:'',
		employees:[],
		select:{},
		active:'',
		add:function(scope,fn){
			var name = scope.add_name,
				department_id = obj.edit || 0;

			if(!name || name.length < 1){
				layer.msg('字符太短',function(){});
				return;
			}
			layer.load();
			$post($http,_host+"department/save",{'name':name,'department_id':department_id}).success(function(r){
				layer.closeAll('loading');
				viewResult(r,function(r){
					fn(r);
				});
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
				layer.msg('字符太短');
				return;
			}
			layer.load();
			$post($http,_host+"department/update",{'department_id':department_id,'name':name}).success(function(r){
				layer.closeAll('loading');
				viewResult(r,function(r){
					fn(r);
				});
			});
		},
		delete:function(fn){
			var department_id = obj.edit;
			if(typeof department_id == 'undefined'){
				layer.msg('必须选定内容');
			}else{
				$post($http,_host+"department/delete",{'department_id':department_id}).success(function(r){
					viewResult(r,function(r){
						fn(r);
					});
				});
			}
		},
		loadTree:function(fn){
			$post($http,_host+"department/findMenu",{}).success(function(r){
				viewResult(r,function(r){
					obj.tree = r;
					fn();
				});
			});
		}
	};
	return obj;
});
myapp.controller('departmentCtrl',function($scope,$http,$route,departmentService,employeeService,rolesService){

	//权限
	initPermission(_permission);
	//部门下拉列表
	function loadSelect(){
		$post($http,_host+"department/find",{}).success(function(r){
			viewResult(r,function(r){
				$scope.alldepartment = r;	
			});
		});
	}

	loadSelect();

	$scope.initRight = function(u){

		rolesService.roles_id = u.roles_id;

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
				departmentService.head_name = obj.name;
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

	
	void function (current,fn){
		var arg = arguments;
		layer.load();
		employeeService.get({"page":current},function(r){
			$scope.employees = r.data;
			var pagination = pageit(current,r.total);
			if(pagination.length > 0){
				$scope.pagination = pagination;
				$scope.current = current;
				$scope.getPage = function(othis){
					var want_current = $(othis).attr('data-current');
					if(want_current != current){
						layer.load();
						arg.callee(want_current,fn);
					}
				};
			}
			fn();
		});
	}(1,function(){
		layer.closeAll('loading');
	});

	$scope.vanEdit = function(){
		if(departmentService.edit){
			$("#call-edit").trigger('click');
		}else{
			layer.msg('选定才能编辑');
		}
	};

	$scope.edit = function(othis){
		departmentService.update($scope,function(){
			$(othis).prev().trigger('click');
			loadtree();
		});
	};

	$scope.vanAdd = function(){
		$scope.head_name = departmentService.head_name;
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
	$scope.delete = function(){
		if(departmentService.edit){
			layer.msg('连同名下部门将一起删除，确定删除？', {
			    time: 0
			    ,btn: ['确定', '取消']
			    ,yes: function(index){
			    	layer.close(index);
			    	layer.load();
			    	departmentService.delete(function(){
			    		layer.closeAll('loading');
			    		loadtree();
			    	});
			    }
			});
		}else{
			layer.msg('选定才能删除');
		}
	};

	$scope.initRoles = function(){
		if(!rolesService.allname){
			rolesService.findAllName(function(){
				$scope.roles = rolesService.allname;
				$scope.e_roles_id = rolesService.roles_id;
			});
		}else{
			$scope.roles = rolesService.allname;
			$scope.e_roles_id = rolesService.roles_id;
		}
	};

	$scope.vanAddEmployee = function(){
		$scope.e_name = "";
		$scope.e_phone = "";
		if(!rolesService.allname){
			rolesService.findAllName(function(){
				$scope.roles = rolesService.allname;
				$scope.e_roles_id = rolesService.roles_id;
			});
		}else{
			$scope.roles = rolesService.allname;
			$scope.e_roles_id = rolesService.roles_id;
		}
	};
});

myapp.service('rolesService',function($http){
	var obj = {
		get:function(fn){
			$post($http,_host+"roles/find",{}).success(function(r){
				viewResult(r,function(r){
					obj.data = r;
					fn(r);
				});
			});
		},
		add:function(name,fn){
			if(typeof name != 'undefined' && name.length > 0){
				$post($http,_host+"roles/save",{
					'name':name,
					'permission':obj.select_add
				}).success(function(r){
					viewResult(r,function(r){
						if(!obj.data){
							obj.data = [];
						}
						obj.data.splice(0,0,r);
						fn();
					});
				});
			}else{
				layer.msg('角色名称格式不正确');
			}
		},
		permissionList:function(roles_id,fn){
			$post($http,_host+'roles/permissionList',{'roles_id':roles_id}).success(function(r){
				viewResult(r,function(r){
					fn(r);
				});
			});
		},
		edit:function(name,fn){
			if(typeof name != 'undefined' && name.length > 0){
				layer.load();
				$post($http,_host+"roles/update",{'roles_id':obj.roles_id,'name':name,'permission':obj.select_edit}).success(function(r){
					layer.closeAll('loading');
					viewResult(r,function(r){
						obj.data.forEach(function(ele,index){
							if(ele.roles_id == obj.roles_id){
								obj.data[index] = r;
							}
						});
						fn();
					});
				});
			}else{
				layer.msg('名字不符合要求');
			}
		},
		initPermission:function(fn){
			if(!obj.permissions){
				$post($http,_host+"roles/allPermission",{}).success(function(r){
					viewResult(r,function(r){
						obj.permissions = r;
						fn(r);
					});
				});
			}else{
				fn(obj.permissions);
			}
		},
		in_array:function(key,arr){
			var tag = false;
			if(typeof arr == 'undefined' || arr.length == 0){
				return false;
			}else{
				arr.forEach(function(ele,index){
					if(ele == key){
						tag = true;
						return;
					}
				});
			}
			return tag;
		},
		delete:function(fn){
			$post($http,_host+"roles/delete",{'roles_id':obj.roles_id}).success(function(r){
				viewResult(r,function(r){
					var arr = [];
					obj.data.forEach(function(ele,index){
						if(ele.roles_id != obj.roles_id){
							arr.push(ele);
						}
					});
					obj.data = arr;
					fn();
				});
			});
		},
		findAllName:function(fn){
			$post($http,_host+'roles/findAllName',{}).success(function(r){
				viewResult(r,function(r){
					obj.allname = r;
					fn();
				});
			});
		}
	};
	return obj;
});
myapp.controller('rolesCtrl',function($scope,$http,rolesService){

	rolesService.get(function(r){
		$scope.roles = rolesService.data;
	});

	$scope.closeWin = function(){
		closeright(function(){
			rolesService.rightwin = false;
		});
	};

	$scope.initPermission = function(){
		rolesService.initPermission(function(r){
			var arr = [];
			for(el in r){
				arr.push({
					'key':el,
					'value':r[el]
				});
			}
			$scope.permissions = arr;
		});
	};

	$scope.initEdit = function(){
		rolesService.initPermission(function(permissions){
			rolesService.permissionList(rolesService.roles_id,function(r){
				var arr = [];
				for(p in permissions){
					arr.push({
						'key':p,
						'value':permissions[p],
						'select':rolesService.in_array(p,r)
					});
				}
				$scope.edit_permissions = arr;
				rolesService.select_edit = r;
				$scope.e_name = rolesService.name;
			});
		});
	};

	$scope.select_edit = function(key,othis){
		if(othis.checked){
			if(!rolesService.select_edit){
				rolesService.select_edit = [];
			}
			rolesService.select_edit.push(key);
		}else{
			var arr = [];
			rolesService.select_edit.forEach(function(ele,index){
				if(ele != key){
					arr.push(ele);
				}
			});
			rolesService.select_edit = arr;
		}
		
	};

	$scope.delete = function(){
		layer.msg('确定删除？', {
		    time: 0
		    ,btn: ['确定', '取消']
		    ,yes: function(index){
		        layer.close(index);
				rolesService.delete(function(r){
					$scope.roles = rolesService.data;
					closeright(function(){
						rolesService.rightwin = false;
					});
				});
		    }
		});
	}

	$scope.select_add = function(key,othis){
		if(othis.checked){
			if(!rolesService.select_add){
				rolesService.select_add = [];
			}
			rolesService.select_add.push(key);
		}else{
			var arr = [];
			rolesService.select_add.forEach(function(ele,index){
				if(ele != key){
					arr.push(ele);
				}
			});
			rolesService.select_add = arr;
		}
	};

	$scope.add = function(othis){		
		rolesService.add($scope.add_name,function(){
			$scope.roles = rolesService.data;
			$(othis).prev().trigger('click');
		});
	};

	//请求权限列表
	function permission_list(roles_id){
		rolesService.permissionList(roles_id,function(r){
			$scope.permission_list = r;
		});
	}

	$scope.edit = function(othis){
		rolesService.edit($scope.e_name,function(){
			$scope.roles = rolesService.data;
			permission_list(rolesService.roles_id);
			$(othis).prev().trigger('click');
		});
	};

	$scope.initRight = function(u){

		$scope.intro = u.name;
		rolesService.name = u.name;
		rolesService.roles_id = u.roles_id;

		if(rolesService.roles_id != u.roles_id){

			callright(function(){
				rolesService.rightwin = true;
				permission_list(u.roles_id);
			});

			rolesService.roles_id = u.roles_id;

		}else{
			if(rolesService.rightwin == true){
				closeright(function(){
					rolesService.rightwin = false;
				});
			}else{
				callright(function(){
					rolesService.rightwin = true;
					permission_list(u.roles_id);
				});
			}
		}

	};
	
});

myapp.service('resourceService',function($http){
	var obj = {
		get:function(fn){
			if(!obj.data){
				$post($http,_host+"resource/find",{}).success(function(r){
					viewResult(r,function(r){
						obj.data = r;
						fn(r);
					});
				});
			}else{
				fn(obj.data);
			}
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
	$scope.resource = resourceService.get(function(r){
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
myapp.service('accountService',function($http){
	var obj = {
		update:function(scope,fn){
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
			layer.load();
			$post($http,_host+"employee/updatePass",{'old_pass':old_pass,'new_pass':new_pass}).success(function(r){
				layer.closeAll('loading');
				viewResult(r,function(r){
					layer.msg('修改成功');
					fn();
				});
			});
		}
	};
	return obj;
});

myapp.controller('accountCtrl',function($scope,accountService){
	$scope.editPass = function(){
		accountService.update($scope,function(){
			$scope.old_pass = '';
			$scope.new_pass = '';
			$scope.repeat_pass = '';
		});
	}
});

myapp.service('taxTypeService',function($http){
	var obj = {
		get:function(fn){
			$post($http,_host+"taxtype/find",{}).success(function(r){
				viewResult(r,function(r){
					obj.data = r;
					fn(r);
				});
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
				viewResult(r,function(r){
					obj.data.forEach(function(ele,index){
						if(ele.tax_type_id == r.tax_type_id){
							obj.data[index]  = r;
						}
					});
					fn(r);
				});
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
				viewResult(r,function(r){
					if(obj.data){
						obj.data.splice(0,0,r);
					}else{
						obj.data = [r];
					}
					fn(r);
				});
			});
		},
		delete:function(fn){
			layer.load();
			$post($http,_host+"taxtype/delete",{'tax_type_id':obj.select_id}).success(function(r){
				layer.closeAll('loading');
				viewResult(r,function(r){
					var arr = [];
					obj.data.forEach(function(ele){
						if(ele.tax_type_id != obj.select_id){
							arr.push(ele);
						}
					});
					obj.data = arr;
					fn(r);
				});
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
			$scope.taxtype = taxTypeService.data;
		});
	}

	$scope.delete = function(othis){
		layer.msg('确定删除？', {
		    time: 0
		    ,btn: ['确定', '取消']
		    ,yes: function(index){
		        layer.close(index);
				taxTypeService.delete(function(r){
					$(othis).next().trigger('click');
					$scope.taxtype = taxTypeService.data;
				});
		    }
		});
	};
	
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

// myapp.directive('requestProcessGroup',function($http){
// 	return {
// 		restrict:'A',
// 		link:function(scope,ele,attrs){
// 			ele.bind('focus',function(){
// 				scope.process_group = [{
// 					process_group_id:1,
// 					name:'工商注册'
// 				},{
// 					process_group_id:2,
// 					name:'工商注销'
// 				}];
// 				scope.$apply();
// 			});
// 		}
// 	};
// });

myapp.service('payrecordService',function($http){
	var obj = {
		filterParams:{},
		get:function(params,fn){
			if(obj.filterParams){
				for(f in obj.filterParams){
					params[f] = obj.filterParams[f];
				}
			}
			$post($http,_host+"payrecord/find",params).success(function(r){
				viewResult(r,function(r){
					// console.log(r);
					obj.data = r.data;
					fn(r);
				});
			});
		},
		edit:function(scope,fn){
			var pay_record_id = obj.pay_record_id,
				money = scope.edit_money,
				deadline = $("#edit_record-deadline").val().trim();


			if(pay_record_id == 0){
				layer.msg('请先指定记录');
				return;
			}
			if(isNaN(money) || money < 0){
				layer.msg('请输入正确的金额');
				return;
			}
			if(!validate('date',deadline)){
				layer.msg('请输入正确的日期');
				return;
			}
			// console.log(pay_record_id);
			// return;
			$post($http,_host+"payrecord/update",{
				'pay_record_id':pay_record_id,
				'money':money,
				'deadline':deadline
			}).success(function(r){
				viewResult(r,function(r){
					obj.recordlist.forEach(function(ele,index){
						if(ele.pay_record_id == pay_record_id){
							obj.recordlist[index].money = money;
							obj.recordlist[index].deadline = deadline;
						}
					});
					fn(r);
				});
			});
		},
		recordList:function(params,fn){
			// console.log(params);
			$post($http,_host+"payrecord/findList",params).success(function(r){
				viewResult(r,function(r){
					obj.recordlist = r;
					fn(r);
				});
			});
		},
		delete:function(pay_record_id,fn){
			layer.load();
			$post($http,_host+"payrecord/delete",{'pay_record_id':pay_record_id}).success(function(r){
				layer.closeAll('loading');
				viewResult(r,function(r){
					var arr = [];
					obj.recordlist.forEach(function(ele,index){
						if(ele.pay_record_id != pay_record_id){
							arr.push(ele);
						}
					});
					obj.recordlist = arr;
					fn();
				});
			});
		},
		add:function(money,deadline,fn){
			if(isNaN(money) || parseInt(money) < 0){
				layer.msg('请输入正确的金额');
				return;
			}
			if(!validate('date',deadline)){
				layer.msg('请输入正确的日期');
				return;
			}
			if(!obj.accounting_id){
				layer.msg('请先指定记录');
			}
			// console.log(money,deadline,accounting_id);
			// return;
			layer.load();
			$post($http,_host+"payrecord/save",{'money':money,'deadline':deadline,'accounting_id':obj.accounting_id}).success(function(r){
				layer.closeAll('loading');
				viewResult(r,function(r){
					fn(r);
				});
			});
		}
	};
	return obj;
});

myapp.controller('payrecordCtrl',function($scope,payrecordService,userService){

	$scope.today = datenow();
	$scope.today15after = datenow(15);
	$scope.level = _config.level;
	initPermission(_permission);
	
	$scope.initYear = function(othis){
		dateComponent.initYear($scope,othis,function(year){
			payrecordService.filterParams.year = year;
			init(1,function(){
				layer.closeAll('loading');
			});
		});
	}

	$scope.clearDate = function(){
		dateComponent.clear($scope,function(){
			delete payrecordService.filterParams.year;
			delete payrecordService.filterParams.month;
			init(1,function(){
				layer.closeAll('loading');
			});
		});
	};

	$scope.initMonth = function(othis){
		dateComponent.initMonth($scope,othis,function(month){
			payrecordService.filterParams.month = month;
			init(1,function(){
				layer.closeAll('loading');
			});
		});
	}	

	$scope.selectOwe = function(){
		payrecordService.filterParams.owe = $scope.owe;
		init(1,function(){
			layer.closeAll("loading");
		});
	};

	$scope.reload = function(othis){
		init(1,function(){
			layer.closeAll("loading");
		});
		$(othis).prev().trigger('click');
	};

	$scope.selectCom = function(othis,e){
		var key = e.keyCode,
			value = othis.value ? othis.value.trim() : "";

		if(key == 13){
			if(value.length > 0){
				if(/^1[345789]\d*$/.test(value)){
					delete payrecordService.filterParams.com;
					payrecordService.filterParams.phone = value;
					init(1,function(){layer.closeAll("loading");});
				}else{
					delete payrecordService.filterParams.phone;
					payrecordService.filterParams.com = value;
					init(1,function(){layer.closeAll("loading");});
				}
			}else{
				delete payrecordService.filterParams.com;
				delete payrecordService.filterParams.phone;
				init(1,function(){layer.closeAll("loading");});
			}
		}
	};

	function initTree(obj,span){
		payrecordService.filterParams.department_id = null;
		payrecordService.filterParams.employee_id = null;

		if(obj.department_id){
			payrecordService.filterParams.department_id = obj.department_id;
		}
		if(obj.employee_id){
			payrecordService.filterParams.department_id = null;
			payrecordService.filterParams.employee_id = obj.employee_id;
		}
		$scope.select_department = obj.name;
	}

	$scope.loadTree = function(){
		if(!userService.trees){
			userService.loadTree(function(r){
				userService.trees = r;
				$("#tree-show").append(buildEmployeeTree(r,initTree));
			});
		}else{
			if($("#tree-show").html().trim().length == 0){
				$("#tree-show").append(buildEmployeeTree(r,initTree));
			}
		}
	};

	$scope.closewin = function(){
		closeright(function(){
			payrecordService.rightwin = false;
		});
	};

	function init(current,fn){
		var arg = arguments;
		payrecordService.get({"page":current},function(r){
			$scope.payrecord = r.data;
			var pagination = pageit(current,r.total);
			if(pagination.length > 0){
				$scope.pagination = pagination;
				$scope.current = current;
				$scope.getPage = function(othis){
					var want_current = $(othis).attr('data-current');
					if(want_current != current){
						layer.load();
						arg.callee(want_current,fn);
					}
				};
			}
			fn();
		});
	}

	init(1,function(){
		layer.closeAll('loading');
	});

	$scope.initRight = function(u){

		$scope.intro = u.company+"/"+u.name;
		payrecordService.pay_record_id = u.pay_record_id;
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
	};

	$scope.getList = function(u){
		payrecordService.pay_record_id = u.pay_record_id;
		payrecordService.recordList({
			'accounting_id':u.accounting_id,
			'year':new Date(u.create_time).getFullYear()
		},function(r){
			$scope.recordlist = r;
		});
	};

	$scope.vanEdit = function(u,othis){
		payrecordService.pay_record_id = u.pay_record_id;
		$scope.edit_money = u.money;
		$scope.edit_deadline = u.deadline;
	};

	$scope.edit = function(othis){
		payrecordService.edit($scope,function(r){
			$(othis).prev().trigger('click');
			$scope.recordlist = payrecordService.recordlist;
		});
	}

	$scope.delete = function(pay_record_id,othis){
		layer.msg('确定删除？', {
		    time: 0
		    ,btn: ['确定', '取消']
		    ,yes: function(index){
		        layer.close(index);
				payrecordService.delete(pay_record_id,function(r){
					$scope.recordlist = payrecordService.recordlist;
				});
		    }
		});
	};

	$scope.add = function(othis){
		payrecordService.add($scope.add_money,$("#record-deadline").val(),function(r){
			// $scope.recordlist = payrecordService.data;
			layer.msg('添加成功');
			$(othis).prev().trigger('click');
		});

	};
});
