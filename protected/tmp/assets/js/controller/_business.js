myapp.service('_businessService',function($http){
	var obj = {
		get:function(params,fn){
			$post($http,_host+"business/_find",params).success(function(r){
				if(r != 'false'){
					obj.data = r.data;
					fn(r);
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


myapp.controller('_businessCtrl',function($scope,$http,_businessService,progressService,processService){
	
	void function (current,fn){
		var arg = arguments;
		_businessService.get({"page":current},function(r){
			$scope.business = _businessService.data;
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

	$scope.updateStatus = function(business_id){
		if(this.u.status == 0){
			this.u.status = 1;
			_businessService.updateStatus($http,business_id);
		}
	};

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

		$post($http,_host+"progress/delete",{'progress_id':progress_id}).success(function(r){
			if(r == 1){
				$(othis).parent().remove();
			}
		});
	};
	
});