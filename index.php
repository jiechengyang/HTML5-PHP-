<?php
session_start();
function isAjax() {
	if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		return true;
	}

	return false;
}
if (isAjax()) {
	unset($_SESSION['message']);
	return true;
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>HTML5(FileReader)+php图片上传</title>
	<meta charset="utf-8"/>
	<meta name="description" content="HTML5(FileReader)+php图片上传"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<style type="text/css">
		.form {
			width: 50%;
			height: 200px;
			background-color: #fff;
			margin: 0 auto;
		}
		.btn {
			border-radius: 3px;
			-webkit-box-shadow: none;
			box-shadow: none;
			border: 1px solid transparent;
			display: inline-block;
			margin-bottom: 0;
			text-align: center;
			vertical-align: middle;
			touch-action: manipulation;
			cursor: pointer;
			background-image: none;
			white-space: nowrap;
			padding: 12px;
		}
		.btn-success
		{
			color: #fff;
		    background-color: #00a65a;
		    border-color: #008d4c;
		}
		.btn-block
		{
		    display: block;
		    width: 100%;
		}
		.form-group
		{
    		margin-bottom: 15px;
		}
		.upload-kit-input
		{
			height: 150px;
			width: 150px;
			position: relative;
			color: #999999;
			border: 2px dashed #999999;
			border-radius: 7px;
			float: left;
			margin-right: 10px;
			margin-bottom: 10px;
			background-size: cover;
			background-position: center center;
			background-origin: border-box;
			list-style: none;
			background-image: url('upload_logo.jpg');
		}
		.upload-kit-input input[type=file]
		{
			width: 100%;
			height: 100%;
			position: absolute;
			display: block;
			opacity: 0;
			cursor: pointer;
			z-index: 2;
		}
		.alert-msginfo
		{
			position: fixed;
			width: 478px;
			background-color: white;
			border-radius: 5px;
			text-align: center;
			left: 50%;
			top: 50%;
			overflow: hidden;
			z-index: 999999;
			margin-left: -296px;
			margin-top: -64px;
			display: none;

		}
		@media all and (max-width: 540px)
		{
			.alert-msginfo
			{
				width: auto;
				margin-left: 0;
				margin-right: 0;
				left: 15px;
				right: 15px;
			}
		}
		.alert-icon
		{
			width: 110px;
			height: 110px;
			border: 4px solid gray;
			-webkit-border-radius: 55px;
			border-radius: 55px;
			border-radius: 50%;
			margin: 20px auto;
			padding: 0;
			position: relative;
			box-sizing: content-box;
		}
		.alert-icon.alert-icon-success
		{
			border-color: #A5DC86;
		}
		.alert-msginfo .sa-icon.sa-success .sa-line.sa-tip
		{
			width: 25px;
			left: 14px;
			top: 46px;
			-webkit-transform: rotate(45deg);
			transform: rotate(45deg);
		}
		.alert-msginfo .sa-icon.sa-success .sa-line
		{
			height: 5px;
			background-color: #A5DC86;
			display: block;
			border-radius: 2px;
			position: absolute;
			z-index: 2;
		}
		.alert-msginfo .sa-icon.sa-success .sa-placeholder
		{
			width: 80px;
			height: 80px;
			border: 4px solid rgba(165, 220, 134, 0.2);
			-webkit-border-radius: 40px;
			border-radius: 40px;
			border-radius: 50%;
			box-sizing: content-box;
			position: absolute;
			left: -4px;
			top: -4px;
			z-index: 2;
		}
		.alert-msginfo  .sa-icon.sa-success .sa-fix
		{
			width: 5px;
			height: 90px;
			background-color: white;
			position: absolute;
			left: 28px;
			top: 8px;
			z-index: 1;
			-webkit-transform: rotate(-45deg);
			transform: rotate(-45deg);
		}
		.alert-msginfo .alert-msgcontent span
		{
		    display: block;
		    font-size: 16px;
		    position: absolute;
		    font-weight: bold;
		    top: 43%;
		    left: 43%;
		    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
		}
		.sweet-overlay {
			 -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=40)";
			background-color: rgba(0, 0, 0, 0.4);
			position: fixed;
			left: 0;
			right: 0;
			top: 0;
			bottom: 0;
			z-index: 10000;
			opacity: 1.09;
			width: 100%;
			height: 100%;
			display: none;
		}
		.wrap
		{
		    margin-right: auto;
		    margin-left: auto;
		    padding-right: 15px;
		    padding-left: 15px;
		}
		@media (min-width: 768px)
		{
		    .wrap
		    {
		        width: 750px;
		    }
		}
		@media (min-width: 992px)
		{
		    .wrap
		    {
		        width: 970px;
		    }
		}
		@media (min-width: 1200px)
		{
		    .wrap
		    {
		        width: 1170px;
		    }
		}
	</style>
</head>
<body>
<div class="wrap">
	<div class="sweet-overlay"></div>
	<div class="alert-msginfo">
		<div class="alert-msgcontent">
			<div class="alert-icon alert-icon-success">
				<span class="sa-line sa-tip"></span>
				<span class="sa-line sa-long"></span>
				<div class="sa-placeholder"></div>
				<div class="sa-fix"></div>
			</div>
			<span class="span-content"></span>
		</div>
	</div>
	<form action="ajaxUpload.php" method="post" enctype="multipart/form-data" class="form">
		<div class="upload-kit-input" id="res">
			<input type="file" name="ajaxfile" accept="image/*" onchange="ajaxUpload(this,'res')" value="" />
		</div>
		<div class="form-group">
			<input type="hidden" name="Ajax[attachment]"/>
			<button type="submit" class="btn btn-block btn-success">提交</button>
		</div>
	</form>
</div>
<script src="jquery.min.js"></script>
<script type="text/javascript">
	function ajaxUpload(obj, res)
	{
		var file =  obj.files[0];
		if (file=='') {
			alertMsg('red', '请选择文件', false);
			return;
		}

		if (!/image\/\w+/.test(file.type)) {
			alertMsg('red', '只能上传图片', false);
			return;
		}
		
		//console.log(file);return;
		var reader = new FileReader();//提供HTML5 FileReader 说明 https://www.cnblogs.com/access520/p/5672435.html
		//将文件以Data URL 形式读入页面
		reader.readAsDataURL(file);
		reader.onload = function(e) {
			//如果这里使用Ajax异步上传也是可以的，逻辑代码就是将提交的代码复制过来
			var resObj = document.getElementById(res);
			var newObj = document.createElement('div');
			newObj.style.cssFloat = 'left';
			newObj.style.paddingRight = '10px';
			newObj.innerHTML = '<img src=\'' + this.result + '\' width=\'150\' height=\'150\'/><input type=\'hidden\' name=\'files[]\' value=\'' + this.result + '\'/>';
			resObj.parentNode.insertBefore(newObj,resObj);
		}
	}
	var timer = '';
	var index = 1;
	
	function alertMsg(color, content,clearsession)
	{
		console.log(color, content);
		$(".alert-msgcontent .span-content").css('color', color);
		$(".alert-msgcontent .span-content").text(content);
		$(".sweet-overlay").show();
		$(".alert-msginfo").show();
		timer = setInterval("timeClick("+clearsession+")",600);
	}

	function timeClick(clearsession)
	{
		if (index++ == 3) {
			index = 1;
			if (clearsession) {
				jQuery.ajax({
					type:'GET',
					data:{},
					url:"index.php",
					success:function(da){
						$(".alert-msginfo").fadeOut();
						$(".sweet-overlay").hide();
						clearInterval(timer);
					}
				});
			} else {
				$(".alert-msginfo").fadeOut();
				$(".sweet-overlay").hide();
				clearInterval(timer);
			}
		}
	}
</script>

<?php
if (isset($_SESSION['message'])) {
	$msgColor = $_SESSION['message']['status'] == 'success' ? 'green' : 'red';
	echo '<script>alertMsg("' . $msgColor . ' ", "' . $_SESSION['message']['msg'] . '", true)</script>';
}

?>

</body>
</html>