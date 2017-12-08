<?php
define('UPLOAD_PATH', str_replace("\\", '/', dirname(__FILE__) . '/upload/'));
session_start();
function _Post($post) {
	if (isset($_POST['GLOBALS'])) {
		exit('系统错误');
	}

	$post = daddslashes($post);

	return $post;
}

function _Get($get) {
	if (isset($_GET['GLOBALS'])) {
		exit('系统错误');
	}

	$get = daddslashes($get);

	return $get;
}

function _Files($files) {
	if (isset($_FILES['GLOBALS'])) {
		exit('系统错误');
	}

	$files = daddslashes($files);

	return $files;
}

/**
 *	@function 数据过滤
 *	@param string
 *	@return array or string
 */
function daddslashes($string) {
	if (is_array($string)) {
		$keys = array_keys($string);
		foreach ($keys as $key) {
			$val = $string[$key];
			unset($string[$key]);
			$string[addslashes($key)] = daddslashes($val);
		}
	} else {
		$string = addslashes(trim($string));
	}

	return $string;
}

function base64ToImg($base64String, $outputFile) {

	$base64String = explode(',', $base64String); //data:image/jpeg;base64,
	$imgInfo = explode(';', $base64String[0]); //[data:image/jpeg,base64]
	$imgInfo = explode(':', $imgInfo[0]); //[data,image/jpeg]
	$imgInfo = explode('/', end($imgInfo));
	$fileExt = end($imgInfo);
	$outputFile = $outputFile . '.' . $fileExt;
	file_put_contents($outputFile, base64_decode(end($base64String))); //返回的是字节数

	return $outputFile;

}

function makeDir($pathName) {
	if (!is_dir(UPLOAD_PATH)) {
		mkdir(UPLOAD_PATH, 0700);
	}

	if (!is_dir(UPLOAD_PATH . $pathName)) {
		mkdir(UPLOAD_PATH . $pathName);
	}

	return UPLOAD_PATH . $pathName;
}

$_POST = _Files($_POST);
$pathName = makeDir(date('Ym'));
$files = $_POST['files'];
$newFiles = [];
if (count($files)) {
	foreach ($files as $key => $file) {
		$outputFile = $pathName . '/' . md5(time() . ($key + 1));
		$newFiles[] = base64ToImg($file, $outputFile);
	}
}
$_SESSION['message'] = ['status' => 'success', 'msg' => '上传成功'];
header('Location:/test/');
// header("content-type:application/json; charset=utf-8");
// header("content-type:text/json; charset=utf-8");

// return ['fileLists', $newFiles];
