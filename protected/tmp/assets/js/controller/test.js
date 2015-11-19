myapp.controller('userCtrl',function($scope,$http){
	$scope.users = [{
		'user_id':'1',
		'name':'翟晶辉',
		'company':'郑州富士康',
		'job':'经历',
		'phone':'15036053625',
		'people':'赵光杰',
		'follower':'拱辰熙'
	},{
		'user_id':'2',
		'name':'Tom',
		'company':'郑州富士康',
		'job':'主管',
		'phone':'15036053625',
		'people':'赵光杰',
		'follower':'拱辰熙'
	}];
	//客户追踪详情
	$scope.showFollowDetail = function(othis){
		var user_id = othis.getAttribute('data-id');
		var result = ['11.20跟踪未果','今天跟踪未果'];
		var html = "";
		result.forEach(function(ele,index){
			html = html + "<p>"+(index+1)+" , "+ele+"</p>";
		});
		layer.tips(html,othis, {
		    tips: [4, '#78BA32']
		});
	};
});