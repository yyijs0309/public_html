<?php
$target_Dir = $_SERVER['DOCUMENT_ROOT'].$_GET['target']."/";
  $file = $_GET['filename'];
  $down = $target_Dir.$file;
  $filesize = filesize($down);
  
  if(file_exists($down)){
    header("Content-Type:application/octet-stream");
    header("Content-Disposition:attachment;filename=$file");
    header("Content-Transfer-Encoding:binary");
    header("Content-Length:".filesize($target_Dir.$file));
    header("Cache-Control:cache,must-revalidate");
    header("Pragma:no-cache");
    header("Expires:0");
    if(is_file($down)){
        $fp = fopen($down,"r");
        while(!feof($fp)){
          $buf = fread($fp,8096);
          $read = strlen($buf);
          print($buf);
          flush();
        }
        fclose($fp);
    }
  } else{
    ?><script>alert("존재하지 않는 파일입니다.")</script><?
  }
?>