<?php
require $_SERVER['DOCUMENT_ROOT'].'/lib/global.php';
// default redirection
$url = $_REQUEST["callback"].'?callback_func='.$_REQUEST["callback_func"];
$bSuccessUpload = is_uploaded_file($_FILES['Filedata']['tmp_name']);

// SUCCESSFUL
if(bSuccessUpload) {
	$tmp_name = $_FILES['Filedata']['tmp_name'];
	$name = $_FILES['Filedata']['name'];
	
	$filename_ext = strtolower(array_pop(explode('.',$name)));
	$allow_file = array("jpg", "png", "bmp", "gif");
	
	if(!in_array($filename_ext, $allow_file)) {
		$url .= '&errstr='.$name;
	} else {
		//$uploadDir = '../../upload/';
		$uploadDir = $UPLOAD_ROOT.'/EDIT/';//'../../upload/';
		if(!is_dir($uploadDir)){
			mkdir($uploadDir, 0777);
		}
		
		$fileName	=	fileReName($_FILES['Filedata']['name']);
		$newPath	= $uploadDir.$fileName;
		
		@move_uploaded_file($tmp_name, $newPath);
		
		$url .= "&bNewLine=true";
		$url .= "&sFileName=".urlencode(urlencode($name));
		//$url .= "&sFileURL=upload/".urlencode(urlencode($name));
		$url .= "&sFileURL=/UPLOAD/EDIT/".$fileName;
        //$url .= "&sFileURL=".$newPath;
	}
}
// FAILED
else {
	$url .= '&errstr=error';
}
	
header('Location: '. $url);
?>