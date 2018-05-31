# HTML5_PHP_AJAX
html5盛行的时代，作为一名php程序猿，怎可以不学习一下H5
入行 以来已有一年半的时长了，我就用文件上传来总结一下自己的变化
  #大学时期
    大学的时候学习文件上传使用最原始的方式,通过文件域file选择文件form指定enctype="multipart/form-data",服务端通过接收$_FILES.....
    那会儿都是跟着老师的节奏走得
  #工作初期
    使用各种上传插件，插件类型大致就是FLASH上传插件(uploadeify) H5上传
    知道form表单enctype的原理:
        是设置表单的MIME编码;
        默认情况:这个编码格式是application/x-www-form-urlencoded，不能用于文件上传；
        只有使用了multipart/form-data,才能完整的传递文件数据，进行下面的操作.
   #Now
    不光会使用，也还是要自己知道原理后也写得出来demo
    
 So，通过baidu,guke 写了一个H5上传文件的demo，目前demo不完善，完整版放在自己用YII2框架搭建的后台管理系统了
 
附上 核心代码:
	var form = document.getElementById('user-form');
	var fd = new FormData(form);
	var ajaxUrl = options.url;
	var fileInputId = options.id;
	var fileInputName = options.name;
	var onlyImage = options.onlyImage;
	var maxNumberOfFiles = options.maxNumberOfFiles;
	var acceptFileTypes = options.acceptFileTypes;
	var acceptFileTypeArr = acceptFileTypes.split(',');
	var flag = true;
	fd.append(fileInputId, file);

	if (!file) {
		layer.alert('请选择文件', {icon: 0});
		return false;
	}
	acceptFileTypes = _trim(acceptFileTypes);
	if (acceptFileTypes != '' && acceptFileTypes != undefined && acceptFileTypes != null ) {
		if (acceptFileTypes == 'image/*') {
			if (!/image\/\w+/.test(file.type)) {
				layer.alert('请选择图片', {icon: 0});
				return false;
			}
		} else if (!in_array(file.type, acceptFileTypeArr)) {
				layer.alert('请选择正确的图片格式(jpg,jpeg,png,gif,bmp)', {icon: 0});
				return false;
		}
	}

	jQuery.ajax({
		type : 'POST',
		data : fd,
		url : ajaxUrl,
		dataType : 'json',
		processData: false,  
		contentType:false,   
		xhr: function() {  // custom xhr  
			myXhr = $.ajaxSettings.xhr();  
			if(myXhr.upload){ // check if upload property exists  
				myXhr.upload.addEventListener('progress',function(evt){  
					evt = window.event || evt;
					var percentComplete = Math.round(evt.loaded*100 / evt.total);  
					console.log(percentComplete);  
				}, false); // for handling the progress of the upload  
			}  
			return myXhr;  
		},  		 
		success : function (data) {
			if (!data) {
				flag = false;
			} else {
/*				var resObj = document.getElementById(res);
				var newObj = document.createElement('div');
				$(newObj).addClass('upload-kit-item done');		
				newObj.style.cssFloat = 'left';
				var newImg = new Image();
				newImg.style.width = '150px';
				newImg.style.height = '150px';	
				$(newObj).append('<span class=\'fa fa-trash remove\' data-id =\' ' + data.id + '\' onclick=\'removeFile(this)\' data-temp =\' '+ fileInputId+ '\' data-resid  = \' '+ res +' \'></span>');							 		
				if (!/image\/\w+/.test(data.filetype)) {//如果不是图片格式
					newImg.src = '/static/img/file.png';
				} else {						 			
					newImg.src = data.filepath;
				}
				
				newObj.appendChild(newImg);
				$("#" + fileInputId).val(data.filepath.replace('\/uploads\/', ''));
				resObj.parentNode.insertBefore(newObj, resObj);	
				resObj.style.display = 'none';*/
				_createImg(res, data, fileInputId);
			}
		},
		error : function(XMLHttpRequest, textStatus, errorThrown) {
			flag = false;
			layer.alert(XMLHttpRequest.status + '<br>' + XMLHttpRequest.readyState + '<br>' + textStatus, {icon: 0});		 	
		}
	});	
   肯定会有许多不做的地方，后续会改的，完整插件会用到我的后台管理系统上
