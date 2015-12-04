myapp.service('_userService',function($http){
	var obj = {
		get:function(params,fn){
			$post($http,_host+"guest/_find",params).success(function(r){
				obj.data = r.data;
				fn(r);
			});
		},
		getById:function(guest_id){
			
		},
		deleteGuest:function(fn){
			$post($http,_host+"guest/_delete",{'guest_id':obj.guest_id}).success(function(r){
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
				if(r.length > 0){
					obj.follow_record = r;
					fn();
				}else{
					layer.msg('没有跟踪记录');
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
		},
		searchByPhoneCom:function(){

		}
	};
	return obj;
});

myapp.controller('_userCtrl',function($scope,$http,_userService,businessService,accountingService){

	_userService.rightwin = false;

	$scope.initResource = _userService.initResource($http,function(data){
		$scope.resource = data;
	});

	$scope.searchByPhoneCom = function(othis,e){
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
			var str = $scope.searchstr;
    		if(str.match(/^\d+$/)){
    			//按手机号查询
    			$post($http,_host+"guest/searchByPhone",{"phone":str}).success(function(r){
    				if(r){
    					$scope.results = r;
    				}else{
    					$scope.results = [{}];
    				}
    			});
    		}else if(str.match(/^[^\d\w]+$/)){
    			//默认为按公司/姓名查询
    			$post($http,_host+"guest/searchByCom",{"com":str}).success(function(r){
    				// console.log(r);
    				if(r){
    					$scope.results = r;
    				}else{
    					$scope.results = [{}];
    				}
    			});
    		}
    		// if(typeof scope.results == 'undefined' || scope.results.length == 0){
    		// 	scope.results = [{}];
    		// }
    		// if(scope.results.length > 0){
    		// 	$("#active-ul").first().addClass('active');
    		// }
    		// scope.$apply();
		}
	};

	$scope.whole = dateComponent.initWhole;

	$scope.initYear = function(othis){
		dateComponent.initYear($scope,othis);
	}

	$scope.initMonth= function(othis){
		dateComponent.initMonth($scope,othis);
	}

	//初始化用户页面
	void function (current,fn){
		var arg = arguments;
		_userService.get({"page":current},function(r){
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
	}(1,function(){
		layer.closeAll('loading');
	});


	//按页码获取
	$scope.getByPage = function(othis){
		_userService.get($http,{page:this.p},function(r){
			$scope.guests = r.data;
			$(othis).addClass('active');
		});
	};

	//添加新用户回调
	$scope.addGuest = function(othis){
		_userService.addGuest($scope,function(){
			for(s in $scope){
				var x = s.toString();
				if(x.indexOf('add_') > -1){
					$scope[s] = '';	
				}
			}
			$(othis).prev().trigger('click');
			$scope.guests = _userService.data;
		});
	};

	$scope.initGuestid = function(){
		_userService.initGuestid = this.u.guest_id;
	};

	//添加跟进记录
	$scope.addRecord = function(othis){
		_userService.addRecord($scope,function(){
			$scope.follow_record = _userService.follow_record;
			$(othis).prev().trigger('click');
		});
	};
	
	//编辑用户
	$scope.editGuest = function(ele){
		_userService.editGuest($scope,function(){
			if(ele.nodeName == 'I'){
				$(ele).parent().prev().trigger('click');
			}else{
				$(ele).prev().trigger('click');
			}
			$scope.guests = _userService.data;
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
		_userService.select_id = this.u.guest_id;
		$scope.select_id = _userService.select_id;
		// console.log(_userService.select_id);
	};

	$scope.initRight = function(u){

		businessService.guest_id = u.guest_id;
		$scope.follow_record = [];

		if(_userService.guest_id != u.guest_id){
			callright(function(){
				_userService.rightwin = true;
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

			_userService.guest_id = u.guest_id;

			businessService.findOpen(u.guest_id,function(r){
				if(r){
					$scope.opens = r;
				}else{

				}
			});

		}else{
			if(_userService.rightwin == true){
				closeright(function(){
					_userService.rightwin = false;
				});
			}else{
				callright(function(){
					_userService.rightwin = true;
				});
				businessService.findOpen(u.guest_id,function(r){
					$scope.opens = r;
				});
			}
		}
	};

	$scope.getRecord = function(othis){
		_userService.showFollowDetail(_userService.guest_id,function(){
			$scope.follow_record = _userService.follow_record;
		});
	};

	$scope.deleteRecord = function(record_id){
		_userService.deleteRecord(record_id,function(){
			$scope.follow_record = _userService.follow_record;
		});
	};

	$scope.deleteGuest = function(){
		_userService.deleteGuest(function(){
			$scope.guests = _userService.data;
			closeright();
		});
	};

	//添加代理记账任务
	$scope.addAccounting = function(ele){
		var employee_id = $scope.accounting_employee_id;
		if(employee_id){
			accountingService.add(_userService.guest_id,employee_id,function(){
				$(ele).prev().trigger('click');
			});
		}else{
			layer.msg('必须指定负责人');
		}
	};
});