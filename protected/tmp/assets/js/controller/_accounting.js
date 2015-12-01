myapp.service('_accountingService',function($http){
	var obj = {
		get:function(params,fn){
			$post($http,_host+"accounting/_find",params).success(function(r){
				if(r){
					obj.data = r.data;
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

myapp.controller('_accountingCtrl',function($scope,$http,_accountingService){
	var now = new Date();
	$scope.today = now.getFullYear()+"-"+(now.getMonth()+1)+"-"+now.getDate();

	void function (current,fn){
		var arg = arguments;
		_accountingService.get({"page":current},function(r){
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
	}(1,function(){
		layer.closeAll('loading');
	});
	

	$scope.updateStatus = function(accounting_id){
		// console.log(accounting_id);
		if(this.u.status == 0){
			_accountingService.updateStatus(accounting_id);
			this.u.status = 1;
		}
	};
});