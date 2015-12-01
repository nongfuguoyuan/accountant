myapp.service('_payrecordService',function($http){
	var obj = {
		get:function(params,fn){
			$post($http,_host+"payrecord/_find",params).success(function(r){
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

myapp.controller('_payrecordCtrl',function($scope,_payrecordService){

	var now = new Date();
	$scope.today = now.getFullYear()+"-"+(now.getMonth()+1)+"-"+now.getDate();

	// _payrecordService.get(function(){
	// 	$scope.payrecord = _payrecordService.data;
	// });
	void function (current,fn){
		var arg = arguments;
		_payrecordService.get({"page":current},function(r){
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
	}(1,function(){
		layer.closeAll('loading');
	});

	$scope.initRight = function(u){

		_payrecordService.accounting_id = u.accounting_id;

		if(_payrecordService.pay_record_id != u.pay_record_id){
			callright(function(){
				_payrecordService.rightwin = true;
			});
		}else{
			if(_payrecordService.rightwin == true){
				closeright(function(){
					_payrecordService.rightwin = false;
				});
			}else{
				callright(function(){
					_payrecordService.rightwin = true;
				});
			}
		}
		$scope.intro = u.company+"/"+u.name;
		_payrecordService.pay_record_id = u.pay_record_id;
		if(u.pay_record_id){
			_payrecordService.recordList(u.accounting_id,function(r){
				$scope.recordlist = r;
			});
		}else{
			$scope.recordlist = [];
		}
		// console.log(u.accounting_id);
	};

	$scope.delete = function(pay_record_id,othis){
		_payrecordService.delete(pay_record_id,function(r){
			$scope.recordlist = _payrecordService.recordlist;
		});
	};

	$scope.add = function(othis){
		
		_payrecordService.add($scope.add_money,$("#record-deadline").val(),function(r){
			$scope.recordlist = _payrecordService.recordlist;
			$(othis).prev().trigger('click');
		});

	};
});
