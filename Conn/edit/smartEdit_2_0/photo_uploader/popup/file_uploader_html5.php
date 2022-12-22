<?php
 	require $_SERVER['DOCUMENT_ROOT'].'/lib/global.php';

	$sFileInfo = '';
	$headers = array();
	 
	foreach($_SERVER as $k => $v) {
		if(substr($k, 0, 9) == "HTTP_FILE") {
			$k = substr(strtolower($k), 5);
			$headers[$k] = $v;
		} 
	}
	
	$file = new stdClass;
	//$file->name = str_replace("\0", "", rawurldecode($headers['file_name']));
	$file->name = fileReName($headers['file_name']);
	$file->size = $headers['file_size'];
	$file->content = file_get_contents("php://input");
	
	$filename_ext = strtolower(array_pop(explode('.',$file->name)));
	$allow_file = array("jpg", "png", "bmp", "gif"); 
	
	if(!in_array($filename_ext, $allow_file)) {
		echo "NOTALLOW_".$file->name;
	} else {
		$uploadDir = $UPLOAD_ROOT.'/EDIT/';//'../../upload/';
		if(!is_dir($uploadDir)){
			mkdir($uploadDir, 0777);
		}
		
		$newPath = $uploadDir.iconv("utf-8", "cp949", $file->name);
		//echoAr($file);
		if(file_put_contents($newPath, $file->content)) {
			$sFileInfo .= "&bNewLine=true";
			$sFileInfo .= "&sFileName=".$file->name;
			$sFileInfo .= "&sFileURL=/UPLOAD/EDIT/".$file->name;
            //$sFileInfo .= "&sFileURL=http://".$_SERVER['HTTP_HOST']."/UPLOAD/EDIT/".$file->name;
		}
		
		echo $sFileInfo;
		//exit;
	}
?>