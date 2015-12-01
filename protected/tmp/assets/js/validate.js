function gologout(){
	window.location.href = _host+"employee/logout";
}
function callright(fn){
	$("#zside-overlay").stop().animate({'right':'0'},200,'swing',function(){
		if(typeof fn == 'function') fn();
	});
}

function closeright(fn){
	$("#zside-overlay").stop().animate({'right':'-350px'},200,'swing',function(){
		if(typeof fn == 'function') fn();
	});
}

function toggleright(fn){
	if($("#zside-overlay").attr('data-status') == 'close'){
		callright();
	}else{
		closeright();
	}
}

function $post(http,url,params){
	return http({
	    method: 'POST',
	    "url":url,
	    data: $.param(params),
	    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	});
}

function in_array(a,arr){
	var tag = false;
	arr.forEach(function(ele){
		if(ele == a){
			tag = true;
			return;
		}
	});
	return tag;
}

function initPermission(permissions){
	var ele = $("[data-permission]");
	// console.log(permissions);
	for(var i = 0,len = ele.length;i<len;i++){
		var el = ele.eq(i);
		var pers = el.attr('data-permission').trim();
		// console.log(pers);
		if(pers.length > 0){
			var arr = pers.split("/");
			if(arr.length == 1){
				if(!in_array(arr[0],permissions)){
					el.hide();
				}		
			}
			if(arr.length > 1){
				if(!in_array(arr[0],permissions) && !in_array(pers,permissions)){
					// console.log(pers);
					el.hide();
				}
			}
		}
		
	}
}

var dateComponent = {
	initYear:function($scope,othis){
		var year = $(othis).text() || '',
			whole = $scope.whole || '';

		year = year.trim();
		whole = whole.trim();

		var arr = whole.split('/');

		if(arr.length <= 1){
			$scope.whole = year;
		}

		if(arr.length == 2){
			$scope.whole = year+'/'+arr[1];
		}
	},
	initMonth:function($scope,othis){
		var month = $(othis).text() || '',
			whole = $scope.whole || '';

		month = month.trim();
		whole = whole.trim();

		var arr = whole.split('/');

		if(arr.length <= 1){
			var year = new Date().getFullYear();
			$scope.whole = year+"/"+month;
		}

		if(arr.length == 2){
			$scope.whole = arr[0]+'/'+month;
		}
	},
	initWhole:(new Date().getFullYear()+"/"+(new Date().getMonth()+1))
};

function pageit(current,total,fn){
	if(typeof total == 'undefined') return [];
	if(current > total) return [];
	var arr = [];
	for(var i = current-2;i<=current+2;i++){
		if(i > 0 && i <= total){
			arr.push(i);	
		}
	}
	if(arr.length > 0){
		if(arr[0] > 3){
			// arr.splice(0,0,'...');
			arr.splice(0,0,1);
		}
		if(arr[arr.length - 1] < total-2){
			// arr.push("...");
			arr.push(total);
		}
	}
	return arr;
}

function validate(model,str){
	if(typeof str == 'undefined'){
		return false;
	}
	switch(model){
		case 'name':
			return str.match(/.{2,}/);
		case 'pass':
			return str.length > 5;
		case 'phone':
			return str.match(/^1[34578]\d{9}$/);
		case 'tel':
			return str.match(/^0\d{2,3}[-]?\d{7,8}$/);
		case 'email':
			return str.match(/[a-zA-Z0-9_-]{5,18}@[\d\w]{2,6}\.(com|cn|net)$/);
		case 'sms':
			return str.match(/^\d{6}$/);
		case 'qq':
			return str.match(/^\d{6,}$/);
		default:
			return false;
	}
}
/*构建菜单树*/
function buildTree(arr,fn){
	if(!arr || arr.toString() == "false"){
		return document.createElement('p');
	}
	var domSelect = '';
	function itdom(arr,fn){
		if(arr.length > 0){
			var args = arguments;
			var ul = document.createElement('ul');

			for(var i = 0,len = arr.length;i<len;i++){
				var obj = arr[i];
				var li = document.createElement('li');
				var span = document.createElement('span');
				if(obj.sub){
					span.innerHTML = "<i class='fa fa-folder-o'></i>"+obj.name;
				}else{
					span.innerHTML = obj.name;
				}
				(function(span,obj){
					span.onclick = function(){
						var kids = span.parentNode.children;
						if(kids[1]){
							if(kids[1].style.display == 'block'){
								span.children[0].className = "fa fa-folder-o";
								kids[1].style.display = 'none';
							}else{
								kids[1].style.display = 'block';
								span.children[0].className = "fa fa-folder-open-o";
								if(typeof fn == 'function'){
									fn(obj,span);
								}
							}
						}else{
							if(typeof fn == 'function'){
								fn(obj,span);
							}
						}
						if(domSelect){
							domSelect.style.backgroundColor = '#fff';
						}
						span.style.backgroundColor = '#efefef';
						domSelect = span;
					};
				})(span,obj);
				li.appendChild(span);
				if(obj.sub){
					li.appendChild(itdom(obj.sub,fn));
				}
				ul.appendChild(li);
			}
			return ul;
		}
	}
	return itdom(arr,fn);
}
