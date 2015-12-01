myapp.service('_taxService',function($http){
	var obj = {
		get:function(params,fn){
			$post($http,_host+"taxcollect/_find",params).success(function(r){
				if(r){
					obj.data = r;
					fn(r);
				}
			});
		},
		add:function(data,fn){
			$post($http,_host+"taxcollect/save",{'data':data}).success(function(r){
				if(r == 1){
					fn();
				}else{
					layer.msg('添加失败');
				}
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
				if(r){
					obj.taxlist = r;
					fn();
				}
			});
		},
		editOne:function(scope,fn){
			var fee = scope.e_one_fee;
			
			if(/(^\d+[.]?\d$)/.test(fee)){
				layer.load();
				$post($http,_host+"taxcollect/updateById",{'tax_collect_id':obj.tax_collect_id,'fee':fee}).success(function(r){
					layer.closeAll('loading');
					if(r != 'false'){
						obj.taxlist.forEach(function(ele,index){
							if(ele.tax_collect_id == obj.tax_collect_id){
								obj.taxlist[index] = r;
							}
						});
						fn();
					}else{
						layer.msg('没有更新');
					}
				});
			}else{
				layer.msg('请输入正确的税额');
			}
		},
		deleteOne:function(tax_collect_id,fn){
			$post($http,_host+"taxcollect/delete",{'tax_collect_id':tax_collect_id}).success(function(r){
				if(r == 1){
					var arr = [];
					obj.taxlist.forEach(function(ele,index){
						if(ele.tax_collect_id != tax_collect_id){
							arr.push(ele);
						}
					});
					obj.taxlist = arr;
					fn();
				}else{
					layer.msg('删除失败');
				}
			});
		},
		addCount:function(scope,fn){
			var nation = scope.nation_count,
				local = scope.local_count,
				year = scope.add_count_year,
				month = scope.add_count_month;

			if(!/^20\d{2}$/.test(year)){
				layer.msg('年份不正确');
				return;
			}
			if(!/^\d{1,2}$/.test(month)){
				layer.msg('月份不正确');
				return;
			}
			if(!/^\d+[.]?\d$/.test(nation)){
				layer.msg('国税金额不正确');
				return;
			}
			if(!/^\d+[.]?\d$/.test(local)){
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
				console.log(r);
			});
		}
	};
	return obj;
});

myapp.controller('_taxCtrl',function($scope,_taxService){

	void function (current,fn){
		var arg = arguments;
		_taxService.get({"page":current},function(r){
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
	}(1,function(){
		layer.closeAll('loading');
	});

	$scope.closewin = function(){
		closeright(function(){
			_taxService.rightwin = false;
		});
	};

	$scope.initTaxtype = function(){
		_taxService.initTaxtype(1,function(r){
			$scope.nation = r;
		});
		_taxService.initTaxtype(2,function(r){
			$scope.local = r;
		});
	};

	$scope.initOne = function(u){
		_taxService.tax_collect_id = u.tax_collect_id;
		$scope.e_one_fee = u.fee;
		$scope.one_name = u.name;
	};

	$scope.editOne = function(othis){
		_taxService.editOne($scope,function(){
			$scope.taxlist = _taxService.taxlist;
			$(othis).prev().trigger('click');
		});
	};

	$scope.deleteOne = function(tax_collect_id){
		layer.msg('确定删除？', {
		    time: 0
		    ,btn: ['确定', '取消']
		    ,yes: function(index){
		        layer.close(index);
				_taxService.deleteOne(tax_collect_id,function(){
					$scope.taxlist = _taxService.taxlist;
				});
		    }
		});

	};

	$scope.add = function(othis){
		var year = $scope.add_year,
			month = $scope.add_month,
			nation = $("#add-nation").find('.row'),
			local = $("#add-local").find('.row');

		
		if(!/^20\d{2}$/.test(year)){
			layer.msg('年份不正确');
			return;
		}
		if(!/\d{1,2}$/.test(month)){
			layer.msg('月份不正确');
			return;
		}
		
		if(!_taxService.guest_id){
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
			'guest_id':_taxService.guest_id,
			'year':year,
			'month':month,
			'nationData':nation_arr,
			'localData':local_arr
		};

		_taxService.add(data,function(){
			if(_taxService.year != null && _taxService.month != null){
				_taxService.findList({'year':_taxService.year,'month':_taxService.month,'guest_id':_taxService.guest_id},function(r){
					$scope.listshow = true;
					$scope.taxlist = _taxService.taxlist;
				});	
			}
			$(othis).prev().trigger('click');
		});
	}

	$scope.addCount = function(othis){
		_taxService.addCount($scope,function(){

		});
	}
	
	$scope.findList = function(){
		if(_taxService.year != null && _taxService.month != null){
			_taxService.findList({'year':_taxService.year,'month':_taxService.month,'guest_id':_taxService.guest_id},function(r){
				$scope.listshow = true;
				$scope.taxlist = _taxService.taxlist;
			});	
		}else{
			layer.msg('没有详细记录');
		}
	};

	$scope.initRight = function(u){
		// console.log(u);
		$scope.intro = u.company+"/"+u.name;
		_taxService.year = u.year;
		_taxService.month = u.month;
		_taxService.guest_id = u.guest_id;

		if(_taxService.year == null || _taxService.month == null){
			$scope.taxlist = [];
			$scope.listshow = false;
		}

		if(_taxService.guest_id != u.guest_id){
			callright(function(){
				_taxService.rightwin = true;
			});
		}else{
			if(_taxService.rightwin == true){
				closeright(function(){
					_taxService.rightwin = false;
				});
			}else{
				callright(function(){
					_taxService.rightwin = true;
				});
			}
		}

	};

});
