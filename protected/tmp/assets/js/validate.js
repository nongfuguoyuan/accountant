function $json(r){
	r = eval("("+")");
	return jQuery.parseJSON(r);
}
function $post(http,url,params){
	return http({
	    method: 'POST',
	    "url":url,
	    data: $.param(params),
	    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	});
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
