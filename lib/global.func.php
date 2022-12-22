<?php
/*#################################################################################################
#########	생성일 : 2012-12-13
#########	만든이 : 김종수 부장
#########	연락처 : 010-6368-2650
#########	이메일 : pusankjs@naver.com
#########	파일명 : global.func.php / 공용함수
#################################################################################################*/

include_once('/home/epihaim/public_html/vendor/autoload.php');
use Firebase\JWT\JWT;

if(!defined("_INCLUDED_FUNC")){
	define("_INCLUDED_FUNC", "1");

	function socketPost($_url="", $_data='', $_referer='', $_port="80"){
		$url	=	$_url;
		$uinfo	=	parse_url($url);

		$send_str = "POST {$uinfo[path]} HTTP/1.0\r\n".
			"Host: {$uinfo[host]}\r\n".
			"User-Agent: Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.2; .NET CLR 3.0.04506.648; .NET CLR 3.5.21022)\r\n".
			"Referer: {$_referer}\r\n". 
			"Content-Type: application/x-www-form-urlencoded\r\n".
			"Content-length: ".strlen($_data)."\r\n".
			"Connection: close\r\n\r\n".
			$_data;
		$fp = fsockopen($uinfo[host], $_port);
		fputs($fp,$send_str);
		while(!feof($fp)) $source .= fgets($fp,4086);
		fclose($fp);
		return preg_replace("/^HTTP.*\r\n\r\n/Uis", '', $source);
	}


	function socketGet($_url, $_data='', $_referer='', $_port="80"){
		$uinfo	=	parse_url($_url);
		$path	=	$uinfo['path'].'?'.$_data;
		$send	=	"GET {$path} HTTP/1.0\r\n". 
			"Host: {$uinfo['host']}\r\n". 
			"User-Agent: Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.2; .NET CLR 3.0.04506.648; .NET CLR 3.5.21022)\r\n". 
			"Referer: {$_referer}\r\n". 
			"Content-Type: text/html; charset=UTF-8\r\n".
			"Accept: */*\r\n". 
			"Accept-Language: ko\r\n". 
			"Connection: Close\r\n\r\n";
		
		$fp = fsockopen($uinfo['host'], $_port, $errno, $errstr, 30);
		if($fp){
			fwrite($fp, $send);
			while (!feof($fp)) $source .= fgets($fp,4096);
			fclose($fp);
		}
		return preg_replace("/.*charset.*\r\n\r\n/Uis", '', $source);
	}

	function getUniqueAr($ar){
		$uniqueSiteCodeAr	=	array_unique($ar);
		return array_values($uniqueSiteCodeAr);
	}

	function getLastWeek($POSITION='START'){
		if($POSITION	==	'') return;
		$todayWeekNum		=	date('w', time());
		$todayWeekStateDay	=	date('Y-m-d', strtotime('-'.$todayWeekNum.' day', time()));
		$lastWeekStartDay	=	date('Y-m-d', strtotime('-6 day', strtotime($todayWeekStateDay)));
		$lastWeekEndDay		=	date('Y-m-d', strtotime('+6 day', strtotime($lastWeekStartDay)));
		if($POSITION	==	'START')
			return $lastWeekStartDay;
		else if($POSITION	==	'END')
			return $lastWeekEndDay;
		else
			return;
	}

	function specialchars_replace($str, $len=0) {
		if($len){
			$str = substr($str, 0, $len);
		}

		$str = preg_replace("/&/", "&amp;", $str);
		$str = preg_replace("/</", "&lt;", $str);
		$str = preg_replace("/>/", "&gt;", $str);
		return $str;
	}

	function getYoutubeMovieLink($artist, $music, $type='web'){
		$artistEx	=	explode('(', urldecode($artist));		
		$musicEx	=	explode('(', urldecode($music));		
		$keyword	= (trim($artistEx[0]) == '' ? '' : trim($artistEx[0]).' ').trim($musicEx[0]);
		$getYoutubeUrl	=	file_get_contents('https://gdata.youtube.com/feeds/api/videos?q='.urlencode($keyword).'&alt=json');
		$getYoutube		=	json_decode($getYoutubeUrl, true);
		$android		=	'';
		for($i=0; $i < count($getYoutube); $i++){
			$android	=	$getYoutube['feed']['entry'][$i]['media$group']['media$content'][1]['url'];
			if($android	!=	'') break;
		}
		
		$web			=	'';
		for($i=0; $i < count($getYoutube); $i++){
			$web		=	$getYoutube['feed']['entry'][$i]['media$group']['media$content'][0]['url'];
			if($web	!=	'') break;
		}

		$duration		=	'';
		for($i=0; $i < count($getYoutube); $i++){
			$duration	=	$getYoutube['feed']['entry'][$i]['media$group']['media$content'][0]['duration'];
			if($duration	!=	'') break;
		}

		if($type	==	'web')
			return $web;
		else if($type	==	'android')
			return $android;
		else if($type	==	'duration')
			return $duration;
	}
	
	function pSkinSet($kind, $file, $layout='self', $role='normal'){
		global $PSKIN, $SKIN_ROOT, $PUSANKJS_SYS, $_SESSION, $modView;
		
		$layerEx	=	explode('|', $layout);

		
		if($layout == 'adm')
			define('PUSANKJS_SKIN_MAIN_CONTENTS_FILE', pSkinDir($kind, false, 'ROOT').'/'.$file);
		elseif($layout == 'tm')
			define('PUSANKJS_SKIN_MAIN_CONTENTS_FILE', pSkinDir($kind, false, 'TM').'/'.$file);
		elseif($layout == 'staff')
			define('PUSANKJS_SKIN_MAIN_CONTENTS_FILE', pSkinDir($kind, false, 'STAFF').'/'.$file);
		else{ 
			//exit('w : ' .$layerEx[1]);
			if($layerEx[1] == 'adm')
				define('PUSANKJS_SKIN_MAIN_CONTENTS_FILE', pSkinDir($kind, false, 'ROOT').'/'.$file);
			elseif($layerEx[1] == 'tm')
				define('PUSANKJS_SKIN_MAIN_CONTENTS_FILE', pSkinDir($kind, false, 'TM').'/'.$file);
			elseif($layerEx[1] == 'staff')
				define('PUSANKJS_SKIN_MAIN_CONTENTS_FILE', pSkinDir($kind, false, 'STAFF').'/'.$file);
			else
				define('PUSANKJS_SKIN_MAIN_CONTENTS_FILE', ($role == 'adm' ? pSkinDir($kind, false, 'ROOT') : pSkinDir($kind)).'/'.$file);
		}

		if($layerEx[0] == 'self'){
			$PSKIN['view_layout'] = pSkinDir('main').'/'.$role.'.main.html';
		}elseif($layerEx[0] == 'adm'){
			$PSKIN['view_layout'] = pSkinDir('main', false, 'ROOT').'/'.$role.'.main.html';
		}elseif($layerEx[0] == 'tm'){
			$PSKIN['view_layout'] = pSkinDir('main', false, 'TM').'/'.$role.'.main.html';
		}elseif($layerEx[0] == 'staff'){
			$PSKIN['view_layout'] = pSkinDir('main', false, 'STAFF').'/'.$role.'.main.html';
		}elseif($layerEx[0] == 'window_open'){
			$PSKIN['view_layout'] = pSkinDir('main').'/'.$role.'.window.html';	
		}elseif ($layerEx[0] == 'layer'){

			$PSKIN['view_layout'] = ($role == 'adm' ? pSkinDir('main', false, ($layerEx[1] == '' ? 'ROOT' : strtoupper($layerEx[1]))) : pSkinDir('main')).'/'.$role.'.layer.html';

		}elseif ($layerEx[0] == 'onlylayer'){
			$PSKIN['view_layout'] = pSkinDir('main').'/'.$role.'.layer.only.html';	
		}elseif ($layerEx[0] == 'iframe'){
			$PSKIN['view_layout'] = pSkinDir('main').'/'.$role.'.iframe.html';	
		}else{
			msg("pSkinSet() 함수에서 세번째 입력값인 layout 에는 self, window_open, iframe, main, layer값만 입력할 수 있습니다.입력된 값 : ".$layout, '', 'history.back();return;');
		}
	}
	
	function getSkinSet($rootDir, $dir, $file, $layout='main', $singleMode=false, $rootIs=false){

		global $PSKIN, $SKIN_ROOT, $PUSANKJS_SYS, $_SESSION, $modView, $getSiteSkin, $_COOKIE, $LANGUAGE;

		$file	=	str_replace(array('.siso', '.php'), '.html', $file);

		//echo "lang:".$_COOKIE['_LANG']; exit;
        //$langform = 'basic_'.($_COOKIE['_LANG'] ? $_COOKIE['_LANG'] : 'kr');
        $langform = 'lang_'.($_COOKIE['_LANG'] ? $_COOKIE['_LANG'] : 'kr');
        $LANGUAGE = $langform;

		$rootDir	=	$rootDir == '_guest/' ? ($rootDir.($getSiteSkin['di_skin'] ? $getSiteSkin['di_skin'] : 'basic').'/') : $rootDir;
		//$rootDir	=	$rootDir == '_ios/' ? ($rootDir.($getSiteSkin['di_skin'] ? $getSiteSkin['di_skin'] : 'basic').'/') : $rootDir;
        //$rootDir	=	$rootDir == '_guest/' ? ($rootDir.($langform ? $langform : 'basic').'/') : $rootDir;

		define('PUSANKJS_SKIN_MAIN_CONTENTS_FILE', getSkinDir($rootDir.$dir, $singleMode, $rootIs).'/'.$file);
        //define('PUSANKJS_SKIN_MAIN_CONTENTS_FILE', getSkinDir($rootDir.$dir, $singleMode, $rootIs).'/'.$file.'?lang='.$lang_value);

		$PSKIN['view_layout'] = getSkinDir($rootDir.'main', $singleMode, $rootIs).'/'.$layout.'.html';

//		echo $PSKIN['view_layout'];exit;
		$allowLayout	=	array('main', 'layer', 'blank', 'login');
		if(!in_array($layout, $allowLayout)){
			msg("pSkinSet() 함수에서 세번째 입력값인 layout 에는 [self, window_open, iframe, main, layer]값만 입력할 수 있습니다.입력된 값 : ".$layout, '', 'history.back();');
		}
	}

	function getSkinDir($dir, $singleMode=false, $isroot=false){
		global $PSKIN, $PUSANKJS_SYS, $SKIN_ROOT;
		//exit($SKIN_ROOT);
		//exit($isroot);

		$path = $SKIN_ROOT.'/'.($singleMode != true ? $PUSANKJS_SYS['SITE_SKIN'].'/' : '').$dir;
		if($isroot){
			$path = '/Form/'.$dir;
		}
//		exit($path);

		return $path;
	}

	function pSkinDir($kind, $isroot=false, $isadmin=false){
		global $PSKIN, $PUSANKJS_SYS, $SKIN_ROOT, $_SERVER;
		switch($isadmin){
			case 'TM' :
				$SKINDIR	=	'T_SITE_SKIN';
			break;
			case 'STAFF' :
				$SKINDIR	=	'S_SITE_SKIN';
			break;
			default : 
				$SKINDIR	=	'A_SITE_SKIN';
			break;
		}

		if($isadmin){
			$path	= $SKIN_ROOT.'/'.$PUSANKJS_SYS[$SKINDIR].'/'.$kind;
			if($_SERVER['HTTP_HOST']	==	'n-protect.co.kr')
				$mpath	=	$SKIN_ROOT.'/'.$PUSANKJS_SYS[$SKINDIR].'/'.$kind;
			else
				$mpath	=	$SKIN_ROOT.'/'.$PUSANKJS_SYS[$SKINDIR].'_m'.'/'.$kind;
			if(preg_match('/(lgtelecom|android|blackberry|iphone|ipad|samsung|symbian|sony|SCH-|SPH-|SHW-)/Uis', $_SERVER['HTTP_USER_AGENT'])){
				if(is_dir($mpath)){
					$path	=	$mpath;
				}else{
					$path	=	$path;
				}
			}
			
		}else
			$path = $SKIN_ROOT.'/'.$PUSANKJS_SYS['SITE_SKIN'].'/'.$kind;
		
		
		if($isroot){
			if($isadmin){
				$path = '/Form/'.$PUSANKJS_SYS[$SKINDIR].'/'.$kind;
				$mpath = '/Form/'.$PUSANKJS_SYS[$SKINDIR].'_m/'.$kind;
				if(preg_match('/(lgtelecom|android|blackberry|iphone|ipad|samsung|symbian|sony|SCH-|SPH-|SHW-)/Uis', $_SERVER['HTTP_USER_AGENT'])){
					//echo $_SERVER['DOCUMENT_ROOT'].$mpath;
					if(is_dir($_SERVER['DOCUMENT_ROOT'].$mpath)){
						$path	=	$mpath;
					}else{
						$path	=	$path;
					}
				}
				
			}else
				$path = '/Form/'.$PUSANKJS_SYS['SITE_SKIN'].'/'.$kind;
		}

		return $path;
	}

	function getmicrotime(){
		list($usec, $sec) = explode(" ",microtime());
		return ((float)$usec + (float)$sec);
	}

	function pXmlSet($dir=''){
		global $PSKIN, $PUSANKJS_SYS, $_SESSION;
		$getXmlDir	=	$PUSANKJS_SYS['URL_ROOT'].'/XML/'.$dir;
		return $getXmlDir;
	}

	function kSkinDirRoot($kind){
		return pSkinDir($kind, true);
	}

	function userSkinDir($kind, $type=true){
		global $mobileSiteIS;
		$skindir	=	'_guest/';
		return getSkinDir($skindir.$kind, true, $type);
	}
	function iosSkinDir($kind, $type=true){
		global $mobileSiteIS;
		$skindir	=	'_ios/';
		return getSkinDir($skindir.$kind, true, $type);
	}

	function adminSkinDirRoot($kind, $type='ROOT'){
		return pSkinDir($kind, true, $type);
	}

	function echoAr($ar, $exit=true, $pre=true){
		if($pre){
			echo '<pre>';
			print_r($ar);
			echo '</pre>';
		}else
			print_r($ar);

		if($exit)
			exit();

	}
	
	function getSubCateFirstIdx($MAINCATE){
		return getValue('_CATE', " where c_p_idx = '".$MAINCATE."' order by c_rank asc limit 1 ", 'c_idx', 'c_idx');
	}

	function getCateIdxToName($IDX){
		return getValue('_CATE', " where c_idx = '".$IDX."' ", 'c_name', 'c_name');
	}

	function strcut_utf8($str, $len, $checkmb=true, $tail='...'){
		preg_match_all('/[\xEA-\xED][\x80-\xFF]{2}|./', $str, $match); 
		$m    = $match[0]; 
		$slen = strlen($str);
		$tlen = strlen($tail);
		$mlen = count($m);
		if($slen <= $len) return $str; 
		if(!$checkmb && $mlen <= $len) return $str; 

		$ret  = array(); 
		$count = 0; 

		for($i=0; $i < $len; $i++){ 
			$count += ($checkmb && strlen($m[$i]) > 1)?2:1; 
			if ($count + $tlen > $len) break; 
			$ret[] = $m[$i]; 
		} 
		return join('', $ret).$tail; 
	}

	function getOnlyDomainInfo($url, $type=1){
		$urlCut		=	 parse_url($url);
		if($type	==	1){
			$pattern = "/[a-z0-9A-Z가-힣-]+\.([a-z0-9A-Z가-힣]|co\.kr)+$/i"; 
			preg_match_all($pattern, $urlCut['host'], $matches);
			return trim($matches[0][0]);
		}else{
			return trim($urlCut['host']);
		}
	}

	function validatePin($_pin){
		$pin = str_replace('-', '', $_pin);
		$state = preg_match('/^[0-9]{13}$/', $pin);
		if($state==true){
			$sum = 0;
			$chkNum = substr($pin,6,1);
			if(!ereg('[1-4]', $chkNum)) return false;
			if(!is_numeric($pin)) return false;
			$arrNo = Array(2, 3, 4, 5, 6, 7, 8, 9, 2, 3, 4, 5);
			for ($i=0;$i<12;$i++) $sum += substr($pin,$i,1) * $arrNo[$i];
			$num = (11-$sum%11)%10;
			if($num == substr($pin,12,1) ) return true;
			else return false;
		}
		return $state;
	}

	function setSession($NAME, $VALUE){
		if(PHP_VERSION < '5.3.0')
			session_register($NAME);
		$$NAME = $_SESSION["$NAME"] = $VALUE;
	}

	function getSession($NAME){
		return $_SESSION[$NAME];
	}
	
	function getImgSize($img){
		if (!file_exists($img)) return;
		return getimagesize($img);
	}

	function setNoImage($img){
		global $_SERVER;
		$size	=	getImgSize($_SERVER['DOCUMENT_ROOT'].$img);
		$noImageSize	=	strstr($img, '/50/') == '' ? 240 : 50;
		if($size[0]	!=	'') return _SITE_URL.$img;
		else return _SITE_URL.kSkinDirRoot('images').'/noimg'.$noImageSize.'.png';
	}

	function msg($_msg='', $_url='', $_script=''){
		
		$charset = _CHARSET_;
		$msg = ($_msg?"alert('{$_msg}');":'');
		$redirect = ($_url?"location.replace('{$_url}');":'');
		$script = ($_script?"{$_script}":'');
		//echo $redirect;exit;
		$html = <<<_HTML_
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset={$charset}" />
</head><body>
<script type="text/javascript">
{$msg}
{$script}
{$redirect}
</script>
</body></html>
_HTML_;

		exit($html);
	}

	function getSiteInfo($url){
		if($url	==	'') return;
		return getValue('_DOMAIN_INFO', "where di_domain = '".str_replace(array('http://', 'www.'), '', $url)."' ", 'ar', '*');
	}

	
	/*
	function disPgCount($whlPgCount, $reqPage, $strTrans, $return=0){

		$szBlock = 10;

		$hfSzBlock = (int)($szBlock / 2);

		$strFile = $_SERVER['PHP_SELF'];
		$sPage;
		$ePage;
		$iCount;
		$value;

		if($reqPage - ($hfSzBlock + 1) > 0 && $reqPage + $hfSzBlock <= $whlPgCount){
			$sPage = $reqPage - $hfSzBlock;
			$ePage = $reqPage + ($hfSzBlock - 1);
		}else{
			$sPage = ($reqPage - ($hfSzBlock + 1)) < 1 ? 1 : $whlPgCount - ($szBlock - 1);
			$ePage = ($reqPage - ($hfSzBlock + 1)) < 1 ? ($szBlock <= $whlPgCount ? $szBlock : $whlPgCount) : $whlPgCount;
		}

		if($sPage < 1) $sPage = 1;		// 마이너스 페이지가 나오는것 예방.

		if($reqPage > 1){
			$value .= "<a href = '$strFile?pagenum=1$strTrans'> 처음 </a>\n";
			$value .= "<a href = '$strFile?pagenum=".(string)($reqPage - 10)."$strTrans'> < </a>\n";
		}else{
			$value .= " 처음 \n";
			$value .= " < \n";
		}

		for($iCount = $sPage; $iCount <= $ePage; $iCount++){
			if ($reqPage == $iCount) $value .= "<b style='color:#FF8400'>$iCount</b>\n";
			else $value .= "<a href = '$strFile?pagenum=$iCount$strTrans' style='font-size:12px;font-family:돋움;text-decoration:none;padding:0 2px 0 2px;color:#333;font-weight:bold'>$iCount</a>\n";
		}

		if($reqPage < $whlPgCount){
			$value .= "<a href = '$strFile?pagenum=".(string)($reqPage + 10)."$strTrans'>> </a>\n";
			$value .= "<a href = '$strFile?pagenum=$whlPgCount$strTrans'> 끝 </a>\n";
		}else{
			$value .= " > \n";
			$value .= " 끝 \n";
		}

		if($return){
			return($value);
		}else{
			echo($value);
		}	// if()
	}
	*/

	function disPgCount($whlPgCount, $reqPage, $strTrans, $return=0){

		$reqPage	=	trim($reqPage);
		$strTrans	=	trim($strTrans);

		$szBlock = 10;

		$hfSzBlock = (int)($szBlock / 2);

		$strFile = $_SERVER['PHP_SELF'];
		$sPage;
		$ePage;
		$iCount;
		$value;

		if($reqPage - ($hfSzBlock + 1) > 0 && $reqPage + $hfSzBlock <= $whlPgCount){
			$sPage = $reqPage - $hfSzBlock;
			$ePage = $reqPage + ($hfSzBlock - 1);
		}else{
			$sPage = ($reqPage - ($hfSzBlock + 1)) < 1 ? 1 : $whlPgCount - ($szBlock - 1);
			$ePage = ($reqPage - ($hfSzBlock + 1)) < 1 ? ($szBlock <= $whlPgCount ? $szBlock : $whlPgCount) : $whlPgCount;
		}

		if($sPage < 1) $sPage = 1;		// 마이너스 페이지가 나오는것 예방.
		//$value .= "<div class='btn-group'>";
		$value	=	'';
		$value .= "<div class='board_page_all_wrap'><div class='board_page_wrap'>";
		if($reqPage > 1){
			$value .= "<div class='board_page_prev_wrap'>";
			$value .= "<span class='board_page_first'><a href='javascript:void(0)' onclick=\"go('$strFile?pagenum=1$strTrans')\">처음</a></span>";
			$value .= "<span class='board_page_prev'><a href='javascript:void(0)' onclick=\"go('$strFile?pagenum=".(string)($reqPage - 1)."$strTrans')\">이전</a></span>";
			$value .= "</div>";
		}else{
			$value .= "<div class='board_page_prev_wrap'>";
			$value .= "<span class='board_page_first'><a href='javascript:void(0)'>처음</a></span>";
			$value .= "<span class='board_page_prev'><a href='javascript:void(0)'>이전</a></span>";
			$value .= "</div>";
		}
		$value .= "<div class='board_page_num_wrap'>";
		for($iCount = $sPage; $iCount <= $ePage; $iCount++){
			if ($reqPage == $iCount) $value .= "<span class='board_page_num'><b>$iCount</b></span>";
			else $value .= "<span class='board_page_num'><a href='javascript:void(0)' onclick=\"go('$strFile?pagenum=".trim($iCount)."".trim($strTrans)."')\">$iCount</a></span>";
		}
		$value .= "</div>";
		if($reqPage < $whlPgCount){
			$value .= "<div class='board_page_next_wrap'>";
			$value .= "<span class='board_page_next'><a href='javascript:void(0)' onclick=\"go('$strFile?pagenum=".(string)($reqPage + 1)."$strTrans')\">다음</a></span>";
			$value .= "<span class='board_page_last'><a href='javascript:void(0)' onclick=\"go('$strFile?pagenum=$whlPgCount$strTrans')\">마지막</a></span>";
			$value .= "</div>";
			//$value .= "<button type='button' class='btn btn-default btn-sm'><i class='fa fa-chevron-right'></i><i class='fa fa-chevron-right'></i></button>";
		}else{
			$value .= "<div class='board_page_next_wrap'>";
			$value .= "<span class='board_page_next'><a href='javascript:void(0)'>다음</a></span>";
			$value .= "<span class='board_page_last'><a href='javascript:void(0)'>마지막</a></span>";
			$value .= "</div>";
		}
		$value .= "</div></div>";

		if($return){
			return($value);
		}else{
			echo($value);
		}	// if()
	}

	function disPgCountUser($whlPgCount, $reqPage, $strTrans, $return=0){

		$reqPage	=	trim($reqPage);
		$strTrans	=	trim($strTrans);

		$szBlock = 10;

		$hfSzBlock = (int)($szBlock / 2);

		$strFile = $_SERVER['PHP_SELF'];
		$sPage;
		$ePage;
		$iCount;
		$value;

		if($reqPage - ($hfSzBlock + 1) > 0 && $reqPage + $hfSzBlock <= $whlPgCount){
			$sPage = $reqPage - $hfSzBlock;
			$ePage = $reqPage + ($hfSzBlock - 1);
		}else{
			$sPage = ($reqPage - ($hfSzBlock + 1)) < 1 ? 1 : $whlPgCount - ($szBlock - 1);
			$ePage = ($reqPage - ($hfSzBlock + 1)) < 1 ? ($szBlock <= $whlPgCount ? $szBlock : $whlPgCount) : $whlPgCount;
		}

		if($sPage < 1) $sPage = 1;		// 마이너스 페이지가 나오는것 예방.
		//$value .= "<div class='btn-group'>";
		$value	=	'';
		$value .= "<div class='board_page_all_wrap'><div class='board_page_wrap'>";
		if($reqPage > 1){
			$value .= "<div class='board_page_prev_wrap'>";
			$value .= "<span class='board_page_first'><a href='javascript:void(0)' onclick=\"go('$strFile?pagenum=1$strTrans')\">처음</a></span>";
			$value .= "<span class='board_page_prev'><a href='javascript:void(0)' onclick=\"go('$strFile?pagenum=".(string)($reqPage - 1)."$strTrans')\">이전</a></span>";
			$value .= "</div>";
		}else{
			$value .= "<div class='board_page_prev_wrap'>";
			$value .= "<span class='board_page_first'><a href='javascript:void(0)'>처음</a></span>";
			$value .= "<span class='board_page_prev'><a href='javascript:void(0)'>이전</a></span>";
			$value .= "</div>";
		}
		$value .= "<div class='board_page_num_wrap'>";
		for($iCount = $sPage; $iCount <= $ePage; $iCount++){
			if ($reqPage == $iCount) $value .= "<span class='board_page_num'><b>$iCount</b></span>";
			else $value .= "<span class='board_page_num'><a href='javascript:void(0)' onclick=\"go('$strFile?pagenum=".trim($iCount)."".trim($strTrans)."')\">$iCount</a></span>";
		}
		$value .= "</div>";
		if($reqPage < $whlPgCount){
			$value .= "<div class='board_page_next_wrap'>";
			$value .= "<span class='board_page_next'><a href='javascript:void(0)' onclick=\"go('$strFile?pagenum=".(string)($reqPage + 1)."$strTrans')\">다음</a></span>";
			$value .= "<span class='board_page_last'><a href='javascript:void(0)' onclick=\"go('$strFile?pagenum=$whlPgCount$strTrans')\">마지막</a></span>";
			$value .= "</div>";
			//$value .= "<button type='button' class='btn btn-default btn-sm'><i class='fa fa-chevron-right'></i><i class='fa fa-chevron-right'></i></button>";
		}else{
			$value .= "<div class='board_page_next_wrap'>";
			$value .= "<span class='board_page_next'><a href='javascript:void(0)'>다음</a></span>";
			$value .= "<span class='board_page_last'><a href='javascript:void(0)'>마지막</a></span>";
			$value .= "</div>";
		}
		$value .= "</div></div>";

		if($return){
			return($value);
		}else{
			echo($value);
		}	// if()
	}

	function disPgCount2($whlPgCount, $reqPage, $strTrans, $return=0){

		$reqPage	=	trim($reqPage);
		$strTrans	=	trim($strTrans);

		$szBlock = 10;

		$hfSzBlock = (int)($szBlock / 2);

		$strFile = $_SERVER['PHP_SELF'];
		$sPage;
		$ePage;
		$iCount;
		$value;

		if($reqPage - ($hfSzBlock + 1) > 0 && $reqPage + $hfSzBlock <= $whlPgCount){
			$sPage = $reqPage - $hfSzBlock;
			$ePage = $reqPage + ($hfSzBlock - 1);
		}else{
			$sPage = ($reqPage - ($hfSzBlock + 1)) < 1 ? 1 : $whlPgCount - ($szBlock - 1);
			$ePage = ($reqPage - ($hfSzBlock + 1)) < 1 ? ($szBlock <= $whlPgCount ? $szBlock : $whlPgCount) : $whlPgCount;
		}

		if($sPage < 1) $sPage = 1;		// 마이너스 페이지가 나오는것 예방.
		$value .= "<div class='indecator'>";
		if($reqPage > 1){
			//$value .= "<button type='button' class='btn btn-default btn-sm' onclick=\"go('$strFile?pagenum=1$strTrans')\"><i class='fa fa-chevron-left'></i><i class='fa fa-chevron-left'></i></button>";
			$value .= "<i class='fa fa-chevron-left' onclick=\"go('$strFile?pagenum=".(string)($reqPage - 1)."$strTrans')\"></i>";
		}else{
			//$value .= "<button type='button' class='btn btn-default btn-sm' disabled><i class='fa fa-chevron-left'></i><i class='fa fa-chevron-left'></i></button>";
			$value .= "<i class='fa fa-chevron-left' disabled></i>";
		}

		for($iCount = $sPage; $iCount <= $ePage; $iCount++){
			if ($reqPage == $iCount) $value .= "<a><span class='active'>$iCount</span></a>";
			else $value .= "<a><span onclick=\"go('$strFile?pagenum=".trim($iCount)."".trim($strTrans)."')\">$iCount</span></a>";
		}

		if($reqPage < $whlPgCount){
			$value .= "<i class='fa fa-chevron-right' onclick=\"go('$strFile?pagenum=".(string)($reqPage + 1)."$strTrans')\"></i>";
			//$value .= "<button type='button' class='btn btn-default btn-sm' onclick=\"go('$strFile?pagenum=$whlPgCount$strTrans')\"><i class='fa fa-chevron-right'></i><i class='fa fa-chevron-right'></i></button>";
		}else{
			$value .= "<i class='fa fa-chevron-right' disabled></i>";
			//$value .= "<button type='button' class='btn btn-default btn-sm' disabled><i class='fa fa-chevron-right'></i><i class='fa fa-chevron-right'></i></button>";
		}
		$value .= "</div>";

		if($return){
			return($value);
		}else{
			echo($value);
		}	// if()
	}


    function disPgCountPopup($whlPgCount, $reqPage, $strTrans, $return=0){

        $reqPage	=	trim($reqPage);
        $strTrans	=	trim($strTrans);

        $szBlock = 5;

        $hfSzBlock = (int)($szBlock / 2);

        $strFile = $_SERVER['PHP_SELF'];
        $sPage;
        $ePage;
        $iCount;
        $value;

        if($reqPage - ($hfSzBlock + 1) > 0 && $reqPage + $hfSzBlock <= $whlPgCount){
            $sPage = $reqPage - $hfSzBlock;
            $ePage = $reqPage + ($hfSzBlock - 1);
        }else{
            $sPage = ($reqPage - ($hfSzBlock + 1)) < 1 ? 1 : $whlPgCount - ($szBlock - 1);
            $ePage = ($reqPage - ($hfSzBlock + 1)) < 1 ? ($szBlock <= $whlPgCount ? $szBlock : $whlPgCount) : $whlPgCount;
        }

        if($sPage < 1) $sPage = 1;		// 마이너스 페이지가 나오는것 예방.
        //$value .= "<div class='btn-group'>";
        $value	=	'';
        $value .= "<div style='width: 150%;' class='board_page_all_wrap'><div class='board_page_wrap'>";
        if($reqPage > 1){
            $value .= "<div class='board_page_prev_wrap POP'>";
            $value .= "<span class='board_page_first'><a href='javascript:void(0)' onclick=\"go('$strFile?pagenum=1$strTrans')\"><<</a></span>";
            $value .= "<span class='board_page_prev'><a href='javascript:void(0)' onclick=\"go('$strFile?pagenum=".(string)($reqPage - 1)."$strTrans')\"><</a></span>";
            $value .= "</div>";
        }else{
            $value .= "<div class='board_page_prev_wrap POP'>";
            $value .= "<span class='board_page_first'><a href='javascript:void(0)'><<</a></span>";
            $value .= "<span class='board_page_prev'><a href='javascript:void(0)'><</a></span>";
            $value .= "</div>";
        }
        $value .= "<div class='board_page_num_wrap'>";
        for($iCount = $sPage; $iCount <= $ePage; $iCount++){
            if ($reqPage == $iCount) $value .= "<span class='board_page_num'><b>$iCount</b></span>";
            else $value .= "<span class='board_page_num'><a href='javascript:void(0)' onclick=\"go('$strFile?pagenum=".trim($iCount)."".trim($strTrans)."')\">$iCount</a></span>";
        }
        $value .= "</div>";
        if($reqPage < $whlPgCount){
            $value .= "<div class='board_page_next_wrap POP'>";
            $value .= "<span class='board_page_next'><a href='javascript:void(0)' onclick=\"go('$strFile?pagenum=".(string)($reqPage + 1)."$strTrans')\">></a></span>";
            $value .= "<span class='board_page_last'><a href='javascript:void(0)' onclick=\"go('$strFile?pagenum=$whlPgCount$strTrans')\">>></a></span>";
            $value .= "</div>";
            //$value .= "<button type='button' class='btn btn-default btn-sm'><i class='fa fa-chevron-right'></i><i class='fa fa-chevron-right'></i></button>";
        }else{
            $value .= "<div class='board_page_next_wrap POP'>";
            $value .= "<span class='board_page_next'><a href='javascript:void(0)'>></a></span>";
            $value .= "<span class='board_page_last'><a href='javascript:void(0)'>>></a></span>";
            $value .= "</div>";
        }
        $value .= "</div></div>";

        if($return){
            return($value);
        }else{
            echo($value);
        }	// if()
    }

	function fileReName($f){
		global $_SERVER;
		$chars_array	=	array_merge(range(0,9), range('a','z'), range('A','Z'));
		shuffle($chars_array);
		$shuffle		=	implode("", $chars_array);
		return abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.str_replace('%', '', urlencode(str_replace(' ', '_', $f)));		
	}

	function fileUpload($file, $dir, $dirDelim=''){
		global $UPLOAD_ROOT, $_FILES;

		if(!is_dir($UPLOAD_ROOT.'/'.$dir)){
			mkdir($UPLOAD_ROOT.'/'.$dir, 0777);
			chmod($UPLOAD_ROOT.'/'.$dir, 0777);
		}
		
		if($dirDelim != ''){
			if(!is_dir($UPLOAD_ROOT.'/'.$dir.'/'.$dirDelim)){
				mkdir($UPLOAD_ROOT.'/'.$dir.'/'.$dirDelim, 0777);
				chmod($UPLOAD_ROOT.'/'.$dir.'/'.$dirDelim, 0777);
			}
		}
		
		$uploadDir		=	$UPLOAD_ROOT.'/'.$dir.'/'.($dirDelim != '' ? $dirDelim.'/' : '');
		//ECHO $uploadDir;EXIT();
		$fileChange		=	fileReName($_FILES[$file]['name']);
		$saveDir		=	$uploadDir.$fileChange;
		if(move_uploaded_file($_FILES[$file]['tmp_name'], $saveDir)){
			;
		}else{
			return;
		}
		return $fileChange;
	}

	function AppfileUpload($file, $dir, $dirDelim='', $p_idx='', $change=''){
		global $UPLOAD_ROOT, $_FILES;

		if(!is_dir($UPLOAD_ROOT.'/'.$dir)){
			mkdir($UPLOAD_ROOT.'/'.$dir, 0777);
			chmod($UPLOAD_ROOT.'/'.$dir, 0777);
		}
		
		if($dirDelim != ''){
			if(!is_dir($UPLOAD_ROOT.'/'.$dir.'/'.$dirDelim)){
				mkdir($UPLOAD_ROOT.'/'.$dir.'/'.$dirDelim, 0777);
				chmod($UPLOAD_ROOT.'/'.$dir.'/'.$dirDelim, 0777);
			}
		}
		if($p_idx != ''){
			$idx_num	=	$p_idx.'_';
		}
		$uploadDir		=	$UPLOAD_ROOT.'/'.$dir.'/'.($dirDelim != '' ? $dirDelim.'/' : '');
		if($change == 'Y'){
			$fileChange		=	$idx_num.fileReName($_FILES[$file]['name']);
		}else{
			$fileChange		=	$p_idx.$_FILES[$file]['name'];//fileReName($_FILES[$file]['name']);
		}
		$saveDir		=	$uploadDir.$fileChange;
		if(move_uploaded_file($_FILES[$file]['tmp_name'], $saveDir)){
			;
		}else{
			return;
		}
		return $fileChange;
	}
	
	function setOutput($v, $t='txt', $delimTxt='', $cut='0', $txt='*'){
		$unit	=	explode('|', $t);
		if($unit[0])
			if($unit[0]	==	'money' || $unit[0]	==	'round') if($v	==	'') return '0'.$delimTxt;
			if($v	==	'')	return '';

		switch($unit[0]){
			case 'money' : 
				$changeValue	=	number_format($v);
			break;
			case 'nl2br' : 
				$changeValue	=	nl2br($v);
			break;
			case 'round' : 
				$changeValue	=	round($v, $unit[1]);
			break;
			case 'img' : 
				$changeValue	=	"<img src='".$v."' ".$unit[1].">";
			break;
			/*
			case 'tel' : 
				$changeValue	=	setTelType($v);
			break;
			*/
			default : 
				$changeValue	=	$v;
			break;
		}
		
		$returnTxt	=	trim(stripslashes($changeValue));
		if($cut > 0){
			$changeTxt	=	'';
			for($i=0;$i<$cut;$i++) $changeTxt .= $txt;
			$returnTxt	=	str_replace(substr($v,-$cut), $changeTxt, $v);
		}
		return $returnTxt.$delimTxt;
	}

	function setOutput2($v, $t='txt', $delimTxt='', $cut='0', $txt='*'){
		$unit	=	explode('|', $t);
		if($unit[0])
			if($unit[0]	==	'money' || $unit[0]	==	'round') if($v	==	'') return '0'.$delimTxt;
			if($v	==	'')	return '';

		switch($unit[0]){
			case 'money' : 
				$changeValue	=	number_format($v);
			break;
			case 'nl2br' : 
				$changeValue	=	nl2br($v);
			break;
			case 'round' : 
				$changeValue	=	round($v, $unit[1]);
			break;
			case 'img' : 
				$changeValue	=	"<img src='".$v."' ".$unit[1].">";
			break;
			/*
			case 'tel' : 
				$changeValue	=	setTelType($v);
			break;
			*/
			default : 
				$changeValue	=	$v;
			break;
		}
		
		$returnTxt	=	trim(addslashes($changeValue));
		if($cut > 0){
			$changeTxt	=	'';
			for($i=0;$i<$cut;$i++) $changeTxt .= $txt;
			$returnTxt	=	str_replace(substr($v,-$cut), $changeTxt, $v);
		}
		return $returnTxt.$delimTxt;
	}

	function setTelType($obj, $delim='-'){
		$telNumber	=	str_replace('-', '', $obj);
		if(strlen($telNumber)	==	8){
			$modifyTelNumber	=	substr($telNumber,0,4).$delim.substr($telNumber,4,4);
		}elseif(strlen($telNumber)	==	10){
			$modifyTelNumber	=	substr($telNumber,0,3).$delim.substr($telNumber,3,3).$delim.substr($telNumber,6,4);
		}elseif(strlen($telNumber)	==	11){
			$modifyTelNumber	=	substr($telNumber,0,3).$delim.substr($telNumber,3,4).$delim.substr($telNumber,7,4);
		}
		return $modifyTelNumber;
	}

	function getAdminMenuSearch($link, $returnKind='detail',$key=0){
		global $menu;
		$onLyFile	=	strtolower(substr(strrchr(urldecode($link), "/"),1));
		for($i=0; $i < count($menu['admin']); $i++){
			if(count($menu['admin'][$i][2]) > 0){
				$subMenuEx	=	'';
				for($j=0; $j < count($menu['admin'][$i][2]); $j++){
					$subMenuEx	=	explode('?', $menu['admin'][$i][2][$j][1]);
					if($subMenuEx[0] == $onLyFile){
						$main	=	$menu['admin'][$i][$key];
						$detail	=	$menu['admin'][$i][2][$j][$key];
						break;
					}else{
						;
					}
				}
			}
		}
		return $returnKind	==	'main' ? $main : $detail;
	}
	
	function searchFont($search, $title, $changeColor='#FF6C00'){
		$src = array("/", "|");
		$dst = array("\/", "\|");

		if (!trim($search)) return $title;

		$s = explode(" ", $search);

		$pattern = "";
		$bar = "";
		for ($m=0; $m<count($s); $m++) {
			if (trim($s[$m]) == "") continue;
			$tmp_str = quotemeta($s[$m]);
			$tmp_str = str_replace($src, $dst, $tmp_str);
			$pattern .= $bar . $tmp_str . "(?![^<]*>)";
			$bar = "|";
		}

		$replace = "<strong style='font-weight:bold; color:".$changeColor."'>\\1</strong>";

		return preg_replace("/($pattern)/i", $replace, $title);
	}
	
	function getDir($folder, $where='folder', $sort='asc', $excSkinDirAr=''){
		$result_array = array();
		$dirname = $folder;
		$handle = opendir($dirname);
		
		while($file = readdir($handle)){
			if($file == "."||$file == "..") continue;
			if($where == 'folder'){
				if(in_array($file, $excSkinDirAr)) continue;
				if(is_dir($dirname.$file)) $result_array[] = $file;
			}elseif($where =='file'){
				if(is_dir($dirname.$file) == false) $result_array[] = $file;
			}
		}
		closedir($handle);
		
		if($sort == 'asc')
			sort($result_array);
		elseif($sort == 'desc')
			rsort($result_array);
		return $result_array;
	}

	function setCookieData($_cookie_key, $_value, $_limit = 0){
		return setCookie($_cookie_key, encrypt(_DF_SECURE_KEY_, $_value), $_limit, '/', _DF_COOKIE_DOMAIN_);
	}

	function getCookieData($_cookie_key){
		$cookie_value = $_COOKIE[$_cookie_key];
		if($cookie_value) $cookie_value = decrypt(_DF_SECURE_KEY_, $cookie_value);
		return $cookie_value;
	}

	//# 암호화 - 암호화 모듈 : RIJNDAEL
	function encrypt($_securekey, $_string){
		if(function_exists('mcrypt_create_iv')){
			$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
			return rawurlencode(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, trim($_securekey), strrev(trim($_string)), MCRYPT_MODE_ECB, $iv)));
		}else return false;
	}

	//# 복호화 - 복호화 모듈 : RIJNDAEL
	function decrypt($_securekey, $_string){
		if(function_exists('mcrypt_create_iv')){
			$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
			return strrev(trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, trim($_securekey), base64_decode(rawurldecode($_string)), MCRYPT_MODE_ECB, $iv)));
		}else return false;
	}

	function getVisitFn($type, $agent){
		global $_SERVER;
		include_once $_SERVER['DOCUMENT_ROOT'].'/lib/visit.func.php';
		$agentAr	=	explode('@', $agent);
		if($agentAr[0]	==	'WEB'){
			switch($type){
				case 'OS' : 
					$v	=	get_os($agentAr[1]);
				break;
				case 'BR' : 
					$v	=	get_brow($agentAr[1]);
				break;
				case 'MADE' : 
					$v	=	get_made($agentAr[1]);
				break;
			}
		}elseif($agentAr[0]	==	'APP'){
			//APP@samsung/SHW-M110S/SHW-M110S:2.3.5/GINGERBREAD/VJ04:user/release-keys
			$vAr	=	explode('/', $agentAr[1]);
			switch($type){
				case 'OS' :		//버젼(맞출려고)
					$v	=	$vAr[3];
				break;
				case 'BR' :		//모델(맞출려고)
					$v	=	$vAr[1];//.' / '.getPhoneProductName($vAr[1]);
				break;
				case 'MADE' : 	//제조사(맞출려고)
					$v	=	$vAr[0];
				break;
			}
		}elseif($agentAr[0]	==	'MOBILEAPP'){
			$a			=	explode(')', $agentAr[1]);
			$b			=	explode('(', $a[0]);
			$vAr		=	explode(';', $b[1]);
			$BRAR		=	explode(' ', trim($vAr[4]));
			$iosBRAR	=	explode(' ', trim($vAr[1]));
			
			switch($type){
				case 'OS' :		//버젼(맞출려고)
					if($iosBRAR[5]	==	'Mac')
						$v	=	$iosBRAR[5] . ' '.$iosBRAR[6];
					else if($iosBRAR[0]	==	'Android')
						$v	=	$vAr[1];
					else if($iosBRAR[4]	==	'Mac')
						$v	=	$iosBRAR[4] . ' '.$iosBRAR[5];	//아이패드
					else if(strstr($vAr[0], 'Windows'))
						$v	=	$vAr[0];	//윈도우
					else
						$v	=	$vAr[2] == '' ? (strstr(strtolower($vAr[1]), 'bot') ? 'ROBOT' : $vAr[1]) : $vAr[2];
				break;
				case 'BR' :		//모델(맞출려고)
					if($iosBRAR[5]	==	'Mac')
						$v	=	$iosBRAR[1].' '.$iosBRAR[2].' '.$iosBRAR[3];
					else if($iosBRAR[0]	==	'Android')
						$v	=	$vAr[2];
					else if(strstr($vAr[0], 'Windows'))
						$v	=	$vAr[0];	//윈도우
					else if($iosBRAR[4]	==	'Mac')
						$v	=	$vAr[0] . ' '.$iosBRAR[1] . ' '.$iosBRAR[2];	//아이패드
					else
						$v	=	$BRAR[0];
				break;
				case 'MADE' : 	//제조사(맞출려고)
					$v	=	get_made($agentAr[1]).'/'.get_brow($agentAr[1]);
				break;
			}
		}
		return $v;
	}
	
	//아이피 국가 가져오기
	function getConCityName($IP, $type=1){
		/*
			1: country_code		:	KR
			2: country_code3	:	KOR
			3: country_name		:	Korea, Republic of
			4: city				:	Seoul
		*/
		include_once $_SERVER['DOCUMENT_ROOT']."/Conn/data/ip/geoipcity.inc";
		include_once $_SERVER['DOCUMENT_ROOT']."/Conn/data/ip/geoipregionvars.php";

		$gi = geoip_open($_SERVER['DOCUMENT_ROOT']."/Conn/data/ip/GeoLiteCity.dat",GEOIP_STANDARD);
		$record = geoip_record_by_addr($gi,$IP);
		
		switch($type){
			case '1' :
				$val	=	$record->country_code;
			break;
			case '2' :
				$val	=	$record->country_code3;
			break;
			case '3' :
				$val	=	$record->country_name;
			break;
			case '4' :
				$val	=	$record->city;
			break;
			default :
				$val	=	'Not';
			break;
		}


		geoip_close($gi);
		return $val;
	}

	function getPhoneProductName($ID){
		$urlParsor	=	socketPost("http://ko.m.wikipedia.org/wiki/".$ID);
		$urlToHtml	=	 "<xmp>".$urlParsor."</xmp>";
		$s			=	explode('<title>',"".$urlToHtml."");
		$s2			=	explode('</title>', $s[1]);
		$lastExAr	=	explode('-', $s2[0]); 
		//return trim($lastExAr[0]).'(추측)';
		return trim($lastExAr[0]);
	}

	function unDisCarrierCode($CODE){
		switch($CODE){
			case '0000' :
				$company	=	'SKT';
			break;
			case '0001' :
				$company	=	'KTF';
			break;
			case '0002' :
				$company	=	'LGT';
			break;
			default : 
				$company	=	$CODE;
			break;
		}
		return $company;
	}

	function getCarrierCode($CODE){
		switch($CODE){
			case 'SKT' :
				$company	=	'0000';
			break;
			case 'KTF' :
				$company	=	'0001';
			break;
			case 'LGT' :
				$company	=	'0002';
			break;
			default : 
				$company	=	$CODE;
			break;
		}
		return $company;
	}

	function getOnlyNumFn($val){
		return preg_replace("/[^0-9]*/s", "", $val);
	}

	function getFilesize($size){
		if($size >= 1048576){
			$size = number_format($size/1048576, 1) . "M";
		}elseif($size >= 1024){
			$size = number_format($size/1024, 1) . "K";
		}else{
			$size = number_format($size, 0) . "byte";
		}
		return $size;
	}

	function superDownMemRegi($ID, $PW, $RANK=1){
		$superDownRegi	=	socketGet('http://superdown.co.kr/aff/sitecode8/mig.php', '&sitecode=nk01&mb_id='.$ID.'&mb_pw='.$PW);
		$ID2	=	'';
		$returnValue	=	'';
		if($superDownRegi	==	'Y')
			$returnValue	=	$ID;
		else{
			$superDownIDCheck	=	socketGet('http://superdown.co.kr/aff/sitecode8/idchk.php', '&sitecode=nk01&mb_id='.$ID);
			$englishAr	=	Array('','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
			if($superDownIDCheck	!=	'Y'){
				$ID2	=	$englishAr[$RANK].substr($ID,0,-1);
				$superDownRegi	=	socketGet('http://superdown.co.kr/aff/sitecode8/mig.php', '&sitecode=nk01&mb_id='.$ID2.'&mb_pw='.$PW);
				if($superDownRegi	==	'Y'){
					$returnValue	=	$ID2;
				}
			}
		}

		$RANK++;
		
		if($RANK	>	26)
			return 'NN';
		
		if($returnValue	!=	''){
			return $returnValue;
		}else{
			unset($returnValue);
			return superDownMemRegi($ID, $PW, $RANK);
		}
	}

	function superDownPaymentRegi($ID, $RANK=1){
		$superDownPayIs	=	socketGet('http://superdown.co.kr/aff/sitecode8/payok.php', '&sitecode=nk01&mb_id='.$ID.'&day=30');
		if($superDownPayIs	==	'OK')
			$returnValue	=	'Y';

		$RANK++;
		
		if($RANK	>	26)
			return 'NN';
		
		if($returnValue	!=	''){
			return $returnValue;
		}else{
			unset($returnValue);
			return superDownPaymentRegi($ID, $RANK);
		}
	}

	function siteCodeToTxt($obj){
		global $siteInfo;
		$siteGroupKey	=	array_search($obj, explode('|', $siteInfo['s_site_group']));
		$siteGroupTxt	=	explode('|', $siteInfo['s_site_group_txt']);
		return $siteGroupTxt[$siteGroupKey];
	}

	function getFileSizeByHTTPHeader($url){
	   $ch = curl_init($url);
	   curl_setopt($ch, CURLOPT_NOBODY, true);
	   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	   curl_setopt($ch, CURLOPT_HEADER, true);
	   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	   $ret = curl_exec($ch);
	   curl_close($ch);
	   
	   if ($ret === false) return false;

	   if (preg_match('/^HTTP\/1\.[01] (\d\d\d)/', $ret, $matches)) $status = (int)$matches[1];
	   if (preg_match('/Content-Length: (\d+)/', $ret, $matches)) $fsize = (int)$matches[1];
	   
	   if ($status != 200) return '0';
	   return $fsize;
	}
	
	function getViewLogTitle($SITECODE, $LOGIDX){
		if($SITECODE >= 1 && $SITECODE <= 10)
			$title	=	getValue('_GET_MNET_LIST', " where gml_idx = '".$LOGIDX."' ", 'gml_music_name', 'gml_music_name');
		else if($SITECODE >= 31 && $SITECODE <= 90)
			$title	=	getValue('_GET_SP_LIST', " where sl_idx = '".$LOGIDX."' ", 'Bbstitle', 'Bbstitle');
		else
			$title	=	'';
		return $title;
	}

	//푸시 함수
	function push_data($headers,$arr){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,    'https://android.googleapis.com/gcm/send');
		curl_setopt($ch, CURLOPT_HTTPHEADER,  $headers);
		curl_setopt($ch, CURLOPT_POST,    true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($arr));
		$response = curl_exec($ch);
		curl_close($ch);


		//echoAr($response);
		//echoAr($arr);

		query(
		"
			insert into _PUSH_HISTORY set 
			ph_title		=	'".$arr['data']['title']."'
			, ph_msg		=	'".$arr['data']['msg']."'
			, ph_cn			=	'".count($arr['registration_ids'])."'
			, ph_system_is	=	'Y'
			, ph_chat_is	=	'N'
		"
		);
	}
	
	
	function isAdult($socialnumber){
		$sType	=	substr($socialnumber,6,1);
		$year	=	substr($socialnumber,0,2);
		$month	=	substr($socialnumber,2,2);
		$day	=	substr($socialnumber,4,2);

		if($sType=='1' || $sType=='2'){
			$year= '19'.$year;
		}else{
			$year ='20'.$year;
		}

		$a_year		=	date('Y')-$year;
		$a_month	=	date('m');
		$a_day		=	date('d');


		if(($month <= $a_month) && ($day <= $a_day)){
			$age = $a_year;
		}else{
			$age = ($a_year-1);
		}
		if($age < 20){
			return 'N';
		}else{
			return 'Y';
		}
	}

	function resnoCheck($resno1, $resno2){
		$resno = $resno1 . $resno2;  
		// 형태 검사: 총 13자리의 숫자, 7번째는 1..4의 값을 가짐  
		if(!ereg('^[[:digit:]]{6}[1-4][[:digit:]]{6}$', $resno))  
			return 'N';  
		// 날짜 유효성 검사  
		$birthYear = ('2' >= $resno[6]) ? '19' : '20';  
		$birthYear += substr($resno, 0, 2);  
		$birthMonth = substr($resno, 2, 2);  
		$birthDate = substr($resno, 4, 2);  
		if(!checkdate($birthMonth, $birthDate, $birthYear))  
			return 'N';
		// Checksum 코드의 유효성 검사  
		for($i = 0; $i < 13; $i++) $buf[$i] = (int) $resno[$i];  
		$multipliers = array(2,3,4,5,6,7,8,9,2,3,4,5);  
		for($i = $sum = 0; $i < 12; $i++) $sum += ($buf[$i] *= $multipliers[$i]);  
		if((11 - ($sum % 11)) % 10 != $buf[12])  
			return 'N';
		// 모든 검사를 통과하면 유효한 주민등록번호임  
		return 'Y';
	}

	function getBeforeWeek($d){
		$todayWeekNum		=	date('w', strtotime($d));
		$todayWeekStateDay	=	date('Y-m-d', strtotime('-'.$todayWeekNum.' day', strtotime($d)));
		$todayWeeklastDay	=	date('Y-m-d', strtotime('+6 day',strtotime($todayWeekStateDay)));
		$lastWeekStartDay	=	date('Y-m-d', strtotime('-6 day', strtotime($todayWeekStateDay)));
		$lastWeekEndDay		=	date('Y-m-d', strtotime('+6 day', strtotime($lastWeekStartDay)));
		$dayAr['lastWeekStartDay']	=	$lastWeekStartDay;
		$dayAr['todayWeekStateDay']	=	$todayWeekStateDay;
		$dayAr['todayWeeklastDay']	=	$todayWeeklastDay;
		$dayAr['lastWeekEndDay']	=	$lastWeekEndDay;
		return $dayAr;
	}

	function paymentStateCodeToTxt($CODE){
		switch($CODE){
			case 'EXT_Y' : 
				$TXT	=	'결제중';
			break;
			case 'EXT_N' : 
				$TXT	=	'연장결제취소';
			break;
			case 'EXT_CANCEL' : 
				$TXT	=	'결제취소';
			break;
			case 'PG_CANCEL' : 
				$TXT	=	'PG취소';
			break;
		}
		return $TXT;
	}

	function getMarketUrl($MARKET, $MARKETIDX){
		global $_SERVER, $connType;
		switch($MARKET){
			case 'G' :
				$dbDelim	=	"google as marketType";
			break;
			case 'S' :
				$dbDelim	=	"tstore as marketType";
			break;
			case 'K' :
				$dbDelim	=	"olleh as marketType";
			break;
			case 'U' :
				$dbDelim	=	"uplus as marketType";
			break;
			case 'N' :
				$dbDelim	=	"nstore as marketType";
			break;
			case 'ALL' :
				$dbDelim	=	"*";
			break;
			default : 
				$dbDelim	=	'google as marketType';
			break;
		}

		if($MARKET	==	'ALL')
			return getValue('_DOMAIN_INFO', " where di_idx = '".$MARKETIDX."' ", 'ar', $dbDelim, false);
		else
			$marketUrl	=	getValue('_DOMAIN_INFO', " where di_idx = '".$MARKETIDX."' ", 'marketType', $dbDelim, false);

		if(!$marketUrl)	return '';
		//echo $marketUrl;
		//exit( $marketUrl );
		//exit("<script>location.href='market://details?id=".$package."';</script>");
		if(strstr($marketUrl, 'details?id=')){
			$uinfo	=	parse_url($marketUrl);
			parse_str($uinfo['query'], $output);

			$mobileUrl	=	$marketUrl;

			$webUrl		=	'https://play.google.com/store/apps/details?id='.$output['id'];
		}else{
			
			$webUrl		=	"http://".(substr($marketUrl,0,2) == 'm.' ? substr($marketUrl,2): $marketUrl);
			$mobileUrl	=	"http://".$marketUrl;
		}

		//echo $mobileUrl;exit;

		if($connType	==	'WEB')
			$mobileUrl	=	'https://play.google.com/store/apps/details?id='.$output['id'];
		else
			$mobileUrl	=	$mobileUrl;
			
		

		if(preg_match('/(lgtelecom|android|blackberry|iphone|ipad|samsung|symbian|sony|SCH-|SPH-|SHW-)/Uis', $_SERVER['HTTP_USER_AGENT'])){
			$redirectUrl	=	$mobileUrl;		
		}else{
			$redirectUrl	=	$webUrl;
		}

		//echo $redirectUrl;exit;
		return $redirectUrl;
	}

	function setPaymentStatisticalFn($IDX='', $SITECODE, $SITECODEG, $link, $i){
		global $_SERVER, $dbInfoAr, $siteInfoAr;
		if($IDX	==	'') return;

		//$SITECODE	=	strtolower(preg_replace('/.*([^\.]{1,})((\.co)?\.[a-z0-9]{2,})$/Uis', '\\1', $_SERVER['HTTP_HOST']));
		//$SITECODEG	=	'ADULT';
		$APPIS		=	'N';
		$EXTENDIS	=	'Y';
		$DBINFO2		=	mysqli_connect('58.120.227.239','musicappdb','abwlrdoq2012','adult_db','3306');
		mysqli_set_charset($DBINFO2, UTF-8);

		//exit($SITECODE);

		$sql	=	"
			select 
				date_format(pdat_pay_datetime, '%Y-%m-%d %H') as hourTime
				, date_format(pdat_pay_datetime, '%Y-%m-%d') as dayTime
				, date_format(pdat_pay_datetime, '%Y-%m') as monthTime
				, date_format(pdat_pay_datetime, '%Y') as yearTime 
				, pdat_app as pdat_app 
				, pdat_state as pdat_state 
				, pdat_price as pdat_price 
				, ".$dbInfoAr[$siteInfoAr[$i]]['PAYF']." as pdat_seq 
			from 
				/*adult_db._TOTAL_PAYMENT_DATA */
				".$dbInfoAr[$siteInfoAr[$i]]['PAYDB']." 
			where 
				".$dbInfoAr[$siteInfoAr[$i]]['PAYF']." = '".$IDX."' 
		";
		//echo $sql;exit;
		$rs		=	mysqli_query($link, $sql);
		//exit($rs);
		//if($this->db->rows()>0){
			while($row=mysqli_fetch_assoc($rs)){
				//exit();
				//결제 상태 : EXT_Y-연장결제,EXT_N-자동연장취소,EXT_CANCEL-연장결제취소,NOR_Y-일반결제,NOR_CANCEL-일반결제취소
				$hourTime			=	$row['hourTime'];
				$dayTime			=	$row['dayTime'];
				$monthTime			=	$row['monthTime'];
				$yearTime			=	$row['yearTime'];
				$price				=	$row['pdat_price'];
				
				$isApp				=	0;
				$isMobile			=	0;
				$isWeb				=	0;
				$cancelCount		=	0;
				$webPrice			=	0;
				$mobilePrice		=	0;
				$appPrice			=	0;
				//$webPrice			=	0;
				$mobilePrice		=	0;
				$appPrice			=	0;
				$webCancelCnt		=	0;
				$webCancelPrice		=	0;
				$mobileCancelCnt	=	0;
				$mobileCancelPrice	=	0;
				$appCancelCnt		=	0;
				$appCancelPrice		=	0;
				
				if($row['pdat_state']=='EXT_CANCEL' || $row['pdat_state']=='NOR_CANCEL'	||	$row['pdat_state']=='PG_CANCEL'){
					$cancelCount	=	1;
				}
				
				if($row['pdat_app']	==	'Y'){
					$isApp		=	1;
					$isMobile	=	0;
					$isWeb		=	0;
					$webPrice	=	0;
					$mobilePrice=	0;
					$appPrice	=	$price;
					if($cancelCount>0){
						$webCancelCnt		=	0;
						$webCancelPrice		=	0;
						$mobileCancelCnt	=	0;
						$mobileCancelPrice	=	0;
						$appCancelCnt		=	1;
						$appCancelPrice		=	$price;
					}
				}elseif($row['pdat_app']	==	'N'){
					$isApp		=	0;
					$isMobile	=	1;
					$isWeb		=	0;
					$webPrice	=	0;
					$mobilePrice=	$price;
					$appPrice	=	0;
					if($cancelCount>0){
						$webCancelCnt		=	0;
						$webCancelPrice		=	0;
						$mobileCancelCnt	=	1;
						$mobileCancelPrice	=	$price;
						$appCancelCnt		=	0;
						$appCancelPrice		=	0;
					}
				}elseif($row['pdat_app']	==	'W'){
					$isApp		=	0;
					$isMobile	=	0;
					$isWeb		=	1;
					$webPrice	=	$price;
					$mobilePrice=	0;
					$appPrice	=	0;
					if($cancelCount>0){
						$webCancelCnt		=	1;
						$webCancelPrice		=	$price;
						$mobileCancelCnt	=	0;
						$mobileCancelPrice	=	0;
						$appCancelCnt		=	0;
						$appCancelPrice		=	0;
					}
				}
				

				$getHourDbRow	=	mysqli_fetch_assoc(mysqli_query($DBINFO2, "select s_date from adult_db._TOTAL_STATISTICAL_HOUR where s_date = '".$hourTime."' and s_site = '".$SITECODE."' "));
				//exit("select s_date from adult_db._TOTAL_STATISTICAL_HOUR where s_date = '".$hourTime."' and s_site = '".$SITECODE."' ");
				$getHourDb		=	$getHourDbRow['s_date'];
				if(!$getHourDb){
					
					$hourSql	=	
						"
							insert into 
								adult_db._TOTAL_STATISTICAL_HOUR 
							set 
								s_date					=	'".$hourTime."'
								, s_site				=	'".$SITECODE."'
								, s_site_group			=	'".$SITECODEG."'
								, s_web_price			=	'".$webPrice."'
								, s_web_cnt				=	'".$isWeb."'
								, s_web_cancel_price	=	'".$webCancelPrice."'
								, s_web_cancel_cnt		=	'".$webCancelCnt."'
								
								, s_web_total_price		=	'".($webPrice-$webCancelPrice)."'
								, s_web_total_cnt		=	'".($isWeb-$webCancelCnt)."'

								, s_mobile_price		=	'".$mobilePrice."'
								, s_mobile_cnt			=	'".$isMobile."'
								, s_mobile_cancel_price	=	'".$mobileCancelPrice."'
								, s_mobile_cancel_cnt	=	'".$mobileCancelCnt."'
								
								, s_mobile_total_price	=	'".($mobilePrice-$mobileCancelPrice)."'
								, s_mobile_total_cnt	=	'".($isMobile-$mobileCancelCnt)."'

								, s_app_price			=	'".$appPrice."'
								, s_app_cnt				=	'".$isApp."'
								, s_app_cancel_price	=	'".$appCancelPrice."'
								, s_app_cancel_cnt		=	'".$appCancelCnt."'

								, s_app_total_price		=	'".($appPrice-$appCancelPrice)."'
								, s_app_total_cnt		=	'".($isApp-$appCancelCnt)."'
								
								, s_total_price			=	'".($webPrice+$mobilePrice+$appPrice)."'
								, s_total_cnt			=	'".($isWeb+$isMobile+$isApp)."'
								, s_total_cancel_price	=	'".($webCancelPrice+$mobileCancelPrice+$appCancelPrice)."'
								, s_total_cancel_cnt	=	'".($webCancelCnt+$mobileCancelCnt+$appCancelCnt)."'
								
								, s_total_total_price	=	'".(($webPrice+$mobilePrice+$appPrice)-($webCancelPrice+$mobileCancelPrice+$appCancelPrice))."'
								, s_total_total_cnt		=	'".(($isWeb+$isMobile+$isApp)-($webCancelCnt+$mobileCancelCnt+$appCancelCnt))."'
						";
				}else{
					$hourSql	=	
						"
							update 
								adult_db._TOTAL_STATISTICAL_HOUR 
							set 
								s_site_group			=	'".$SITECODEG."'
								, s_web_price			=	s_web_price+'".$webPrice."'
								, s_web_cnt				=	s_web_cnt+'".$isWeb."'
								, s_web_cancel_price	=	s_web_cancel_price+'".$webCancelPrice."'
								, s_web_cancel_cnt		=	s_web_cancel_cnt+'".$webCancelCnt."'
								
								, s_web_total_price		=	s_web_total_price+'".($webPrice-$webCancelPrice)."'
								, s_web_total_cnt		=	s_web_total_cnt+'".($isWeb-$webCancelCnt)."'

								, s_mobile_price		=	s_mobile_price+'".$mobilePrice."'
								, s_mobile_cnt			=	s_mobile_cnt+'".$isMobile."'
								, s_mobile_cancel_price	=	s_mobile_cancel_price+'".$mobileCancelPrice."'
								, s_mobile_cancel_cnt	=	s_mobile_cancel_cnt+'".$mobileCancelCnt."'
								
								, s_mobile_total_price	=	s_mobile_total_price+'".($mobilePrice-$mobileCancelPrice)."'
								, s_mobile_total_cnt	=	s_mobile_total_cnt+'".($isMobile-$mobileCancelCnt)."'

								, s_app_price			=	s_app_price+'".$appPrice."'
								, s_app_cnt				=	s_app_cnt+'".$isApp."'
								, s_app_cancel_price	=	s_app_cancel_price+'".$appCancelPrice."'
								, s_app_cancel_cnt		=	s_app_cancel_cnt+'".$appCancelCnt."'

								, s_app_total_price		=	s_app_total_price+'".($appPrice-$appCancelPrice)."'
								, s_app_total_cnt		=	s_app_total_cnt+'".($isApp-$appCancelCnt)."'
								
								, s_total_price			=	s_total_price+'".($webPrice+$mobilePrice+$appPrice)."'
								, s_total_cnt			=	s_total_cnt+'".($isWeb+$isMobile+$isApp)."'
								, s_total_cancel_price	=	s_total_cancel_price+'".($webCancelPrice+$mobileCancelPrice+$appCancelPrice)."'
								, s_total_cancel_cnt	=	s_total_cancel_cnt+'".($webCancelCnt+$mobileCancelCnt+$appCancelCnt)."'
								
								, s_total_total_price	=	s_total_total_price+'".(($webPrice+$mobilePrice+$appPrice)-($webCancelPrice+$mobileCancelPrice+$appCancelPrice))."'
								, s_total_total_cnt		=	s_total_total_cnt+'".(($isWeb+$isMobile+$isApp)-($webCancelCnt+$mobileCancelCnt+$appCancelCnt))."' 
							where 
								s_date					=	'".$hourTime."' 
							and 
								s_site					=	'".$SITECODE."'
						";
				}

				mysqli_query($DBINFO2, $hourSql);

				$getDayDbRow	=	mysqli_fetch_assoc(mysqli_query($DBINFO2, "select s_date from adult_db._TOTAL_STATISTICAL_DAY where s_date = '".$dayTime."' and s_site = '".$SITECODE."' "));
				$getDayDb		=	$getDayDbRow['s_date'];
				if(!$getDayDb){
					
					$daySql	=	
						"
							insert into 
								adult_db._TOTAL_STATISTICAL_DAY 
							set 
								s_date					=	'".$dayTime."'
								, s_site				=	'".$SITECODE."'
								, s_site_group			=	'".$SITECODEG."'
								, s_web_price			=	'".$webPrice."'
								, s_web_cnt				=	'".$isWeb."'
								, s_web_cancel_price	=	'".$webCancelPrice."'
								, s_web_cancel_cnt		=	'".$webCancelCnt."'
								
								, s_web_total_price		=	'".($webPrice-$webCancelPrice)."'
								, s_web_total_cnt		=	'".($isWeb-$webCancelCnt)."'

								, s_mobile_price		=	'".$mobilePrice."'
								, s_mobile_cnt			=	'".$isMobile."'
								, s_mobile_cancel_price	=	'".$mobileCancelPrice."'
								, s_mobile_cancel_cnt	=	'".$mobileCancelCnt."'
								
								, s_mobile_total_price	=	'".($mobilePrice-$mobileCancelPrice)."'
								, s_mobile_total_cnt	=	'".($isMobile-$mobileCancelCnt)."'

								, s_app_price			=	'".$appPrice."'
								, s_app_cnt				=	'".$isApp."'
								, s_app_cancel_price	=	'".$appCancelPrice."'
								, s_app_cancel_cnt		=	'".$appCancelCnt."'

								, s_app_total_price		=	'".($appPrice-$appCancelPrice)."'
								, s_app_total_cnt		=	'".($isApp-$appCancelCnt)."'
								
								, s_total_price			=	'".($webPrice+$mobilePrice+$appPrice)."'
								, s_total_cnt			=	'".($isWeb+$isMobile+$isApp)."'
								, s_total_cancel_price	=	'".($webCancelPrice+$mobileCancelPrice+$appCancelPrice)."'
								, s_total_cancel_cnt	=	'".($webCancelCnt+$mobileCancelCnt+$appCancelCnt)."'
								
								, s_total_total_price	=	'".(($webPrice+$mobilePrice+$appPrice)-($webCancelPrice+$mobileCancelPrice+$appCancelPrice))."'
								, s_total_total_cnt		=	'".(($isWeb+$isMobile+$isApp)-($webCancelCnt+$mobileCancelCnt+$appCancelCnt))."'
						";
				}else{
					$daySql	=	
						"
							update 
								adult_db._TOTAL_STATISTICAL_DAY 
							set 
								s_site_group			=	'".$SITECODEG."'
								, s_web_price			=	s_web_price+'".$webPrice."'
								, s_web_cnt				=	s_web_cnt+'".$isWeb."'
								, s_web_cancel_price	=	s_web_cancel_price+'".$webCancelPrice."'
								, s_web_cancel_cnt		=	s_web_cancel_cnt+'".$webCancelCnt."'
								
								, s_web_total_price		=	s_web_total_price+'".($webPrice-$webCancelPrice)."'
								, s_web_total_cnt		=	s_web_total_cnt+'".($isWeb-$webCancelCnt)."'

								, s_mobile_price		=	s_mobile_price+'".$mobilePrice."'
								, s_mobile_cnt			=	s_mobile_cnt+'".$isMobile."'
								, s_mobile_cancel_price	=	s_mobile_cancel_price+'".$mobileCancelPrice."'
								, s_mobile_cancel_cnt	=	s_mobile_cancel_cnt+'".$mobileCancelCnt."'
								
								, s_mobile_total_price	=	s_mobile_total_price+'".($mobilePrice-$mobileCancelPrice)."'
								, s_mobile_total_cnt	=	s_mobile_total_cnt+'".($isMobile-$mobileCancelCnt)."'

								, s_app_price			=	s_app_price+'".$appPrice."'
								, s_app_cnt				=	s_app_cnt+'".$isApp."'
								, s_app_cancel_price	=	s_app_cancel_price+'".$appCancelPrice."'
								, s_app_cancel_cnt		=	s_app_cancel_cnt+'".$appCancelCnt."'

								, s_app_total_price		=	s_app_total_price+'".($appPrice-$appCancelPrice)."'
								, s_app_total_cnt		=	s_app_total_cnt+'".($isApp-$appCancelCnt)."'
								
								, s_total_price			=	s_total_price+'".($webPrice+$mobilePrice+$appPrice)."'
								, s_total_cnt			=	s_total_cnt+'".($isWeb+$isMobile+$isApp)."'
								, s_total_cancel_price	=	s_total_cancel_price+'".($webCancelPrice+$mobileCancelPrice+$appCancelPrice)."'
								, s_total_cancel_cnt	=	s_total_cancel_cnt+'".($webCancelCnt+$mobileCancelCnt+$appCancelCnt)."'
								
								, s_total_total_price	=	s_total_total_price+'".(($webPrice+$mobilePrice+$appPrice)-($webCancelPrice+$mobileCancelPrice+$appCancelPrice))."'
								, s_total_total_cnt		=	s_total_total_cnt+'".(($isWeb+$isMobile+$isApp)-($webCancelCnt+$mobileCancelCnt+$appCancelCnt))."' 
							where 
								s_date					=	'".$dayTime."' 
							and 
								s_site					=	'".$SITECODE."'
						";
				}

				mysqli_query($DBINFO2, $daySql);


				$getMonthDbRow	=	mysqli_fetch_assoc(mysqli_query($DBINFO2, "select s_date from adult_db._TOTAL_STATISTICAL_MONTH where s_date = '".$monthTime."' and s_site = '".$SITECODE."' "));
				$getMonthDb		=	$getMonthDbRow['s_date'];
				if(!$getMonthDb){
					
					$monthSql	=	
						"
							insert into 
								adult_db._TOTAL_STATISTICAL_MONTH 
							set 
								s_date					=	'".$monthTime."'
								, s_site				=	'".$SITECODE."'
								, s_site_group			=	'".$SITECODEG."'
								, s_web_price			=	'".$webPrice."'
								, s_web_cnt				=	'".$isWeb."'
								, s_web_cancel_price	=	'".$webCancelPrice."'
								, s_web_cancel_cnt		=	'".$webCancelCnt."'
								
								, s_web_total_price		=	'".($webPrice-$webCancelPrice)."'
								, s_web_total_cnt		=	'".($isWeb-$webCancelCnt)."'

								, s_mobile_price		=	'".$mobilePrice."'
								, s_mobile_cnt			=	'".$isMobile."'
								, s_mobile_cancel_price	=	'".$mobileCancelPrice."'
								, s_mobile_cancel_cnt	=	'".$mobileCancelCnt."'
								
								, s_mobile_total_price	=	'".($mobilePrice-$mobileCancelPrice)."'
								, s_mobile_total_cnt	=	'".($isMobile-$mobileCancelCnt)."'

								, s_app_price			=	'".$appPrice."'
								, s_app_cnt				=	'".$isApp."'
								, s_app_cancel_price	=	'".$appCancelPrice."'
								, s_app_cancel_cnt		=	'".$appCancelCnt."'

								, s_app_total_price		=	'".($appPrice-$appCancelPrice)."'
								, s_app_total_cnt		=	'".($isApp-$appCancelCnt)."'
								
								, s_total_price			=	'".($webPrice+$mobilePrice+$appPrice)."'
								, s_total_cnt			=	'".($isWeb+$isMobile+$isApp)."'
								, s_total_cancel_price	=	'".($webCancelPrice+$mobileCancelPrice+$appCancelPrice)."'
								, s_total_cancel_cnt	=	'".($webCancelCnt+$mobileCancelCnt+$appCancelCnt)."'
								
								, s_total_total_price	=	'".(($webPrice+$mobilePrice+$appPrice)-($webCancelPrice+$mobileCancelPrice+$appCancelPrice))."'
								, s_total_total_cnt		=	'".(($isWeb+$isMobile+$isApp)-($webCancelCnt+$mobileCancelCnt+$appCancelCnt))."'
						";
				}else{
					$monthSql	=	
						"
							update 
								adult_db._TOTAL_STATISTICAL_MONTH 
							set 
								s_site_group			=	'".$SITECODEG."'
								, s_web_price			=	s_web_price+'".$webPrice."'
								, s_web_cnt				=	s_web_cnt+'".$isWeb."'
								, s_web_cancel_price	=	s_web_cancel_price+'".$webCancelPrice."'
								, s_web_cancel_cnt		=	s_web_cancel_cnt+'".$webCancelCnt."'
								
								, s_web_total_price		=	s_web_total_price+'".($webPrice-$webCancelPrice)."'
								, s_web_total_cnt		=	s_web_total_cnt+'".($isWeb-$webCancelCnt)."'

								, s_mobile_price		=	s_mobile_price+'".$mobilePrice."'
								, s_mobile_cnt			=	s_mobile_cnt+'".$isMobile."'
								, s_mobile_cancel_price	=	s_mobile_cancel_price+'".$mobileCancelPrice."'
								, s_mobile_cancel_cnt	=	s_mobile_cancel_cnt+'".$mobileCancelCnt."'
								
								, s_mobile_total_price	=	s_mobile_total_price+'".($mobilePrice-$mobileCancelPrice)."'
								, s_mobile_total_cnt	=	s_mobile_total_cnt+'".($isMobile-$mobileCancelCnt)."'

								, s_app_price			=	s_app_price+'".$appPrice."'
								, s_app_cnt				=	s_app_cnt+'".$isApp."'
								, s_app_cancel_price	=	s_app_cancel_price+'".$appCancelPrice."'
								, s_app_cancel_cnt		=	s_app_cancel_cnt+'".$appCancelCnt."'

								, s_app_total_price		=	s_app_total_price+'".($appPrice-$appCancelPrice)."'
								, s_app_total_cnt		=	s_app_total_cnt+'".($isApp-$appCancelCnt)."'
								
								, s_total_price			=	s_total_price+'".($webPrice+$mobilePrice+$appPrice)."'
								, s_total_cnt			=	s_total_cnt+'".($isWeb+$isMobile+$isApp)."'
								, s_total_cancel_price	=	s_total_cancel_price+'".($webCancelPrice+$mobileCancelPrice+$appCancelPrice)."'
								, s_total_cancel_cnt	=	s_total_cancel_cnt+'".($webCancelCnt+$mobileCancelCnt+$appCancelCnt)."'
								
								, s_total_total_price	=	s_total_total_price+'".(($webPrice+$mobilePrice+$appPrice)-($webCancelPrice+$mobileCancelPrice+$appCancelPrice))."'
								, s_total_total_cnt		=	s_total_total_cnt+'".(($isWeb+$isMobile+$isApp)-($webCancelCnt+$mobileCancelCnt+$appCancelCnt))."' 
							where 
								s_date					=	'".$monthTime."' 
							and 
								s_site					=	'".$SITECODE."'
						";
				}

				mysqli_query($DBINFO2, $monthSql);


				$getYearDbRow	=	mysqli_fetch_assoc(mysqli_query($DBINFO2, "select s_date from adult_db._TOTAL_STATISTICAL_YEAR where s_date = '".$yearTime."' and s_site = '".$SITECODE."' "));
				$getYearDb		=	$getYearDbRow['s_date'];
				if(!$getYearDb){
					
					$yearSql	=	
						"
							insert into 
								adult_db._TOTAL_STATISTICAL_YEAR 
							set 
								s_date					=	'".$yearTime."'
								, s_site				=	'".$SITECODE."'
								, s_site_group			=	'".$SITECODEG."'
								, s_web_price			=	'".$webPrice."'
								, s_web_cnt				=	'".$isWeb."'
								, s_web_cancel_price	=	'".$webCancelPrice."'
								, s_web_cancel_cnt		=	'".$webCancelCnt."'
								
								, s_web_total_price		=	'".($webPrice-$webCancelPrice)."'
								, s_web_total_cnt		=	'".($isWeb-$webCancelCnt)."'

								, s_mobile_price		=	'".$mobilePrice."'
								, s_mobile_cnt			=	'".$isMobile."'
								, s_mobile_cancel_price	=	'".$mobileCancelPrice."'
								, s_mobile_cancel_cnt	=	'".$mobileCancelCnt."'
								
								, s_mobile_total_price	=	'".($mobilePrice-$mobileCancelPrice)."'
								, s_mobile_total_cnt	=	'".($isMobile-$mobileCancelCnt)."'

								, s_app_price			=	'".$appPrice."'
								, s_app_cnt				=	'".$isApp."'
								, s_app_cancel_price	=	'".$appCancelPrice."'
								, s_app_cancel_cnt		=	'".$appCancelCnt."'

								, s_app_total_price		=	'".($appPrice-$appCancelPrice)."'
								, s_app_total_cnt		=	'".($isApp-$appCancelCnt)."'
								
								, s_total_price			=	'".($webPrice+$mobilePrice+$appPrice)."'
								, s_total_cnt			=	'".($isWeb+$isMobile+$isApp)."'
								, s_total_cancel_price	=	'".($webCancelPrice+$mobileCancelPrice+$appCancelPrice)."'
								, s_total_cancel_cnt	=	'".($webCancelCnt+$mobileCancelCnt+$appCancelCnt)."'
								
								, s_total_total_price	=	'".(($webPrice+$mobilePrice+$appPrice)-($webCancelPrice+$mobileCancelPrice+$appCancelPrice))."'
								, s_total_total_cnt		=	'".(($isWeb+$isMobile+$isApp)-($webCancelCnt+$mobileCancelCnt+$appCancelCnt))."'
						";
				}else{
					$yearSql	=	
						"
							update 
								adult_db._TOTAL_STATISTICAL_YEAR 
							set 
								s_site_group			=	'".$SITECODEG."'
								, s_web_price			=	s_web_price+'".$webPrice."'
								, s_web_cnt				=	s_web_cnt+'".$isWeb."'
								, s_web_cancel_price	=	s_web_cancel_price+'".$webCancelPrice."'
								, s_web_cancel_cnt		=	s_web_cancel_cnt+'".$webCancelCnt."'
								
								, s_web_total_price		=	s_web_total_price+'".($webPrice-$webCancelPrice)."'
								, s_web_total_cnt		=	s_web_total_cnt+'".($isWeb-$webCancelCnt)."'

								, s_mobile_price		=	s_mobile_price+'".$mobilePrice."'
								, s_mobile_cnt			=	s_mobile_cnt+'".$isMobile."'
								, s_mobile_cancel_price	=	s_mobile_cancel_price+'".$mobileCancelPrice."'
								, s_mobile_cancel_cnt	=	s_mobile_cancel_cnt+'".$mobileCancelCnt."'
								
								, s_mobile_total_price	=	s_mobile_total_price+'".($mobilePrice-$mobileCancelPrice)."'
								, s_mobile_total_cnt	=	s_mobile_total_cnt+'".($isMobile-$mobileCancelCnt)."'

								, s_app_price			=	s_app_price+'".$appPrice."'
								, s_app_cnt				=	s_app_cnt+'".$isApp."'
								, s_app_cancel_price	=	s_app_cancel_price+'".$appCancelPrice."'
								, s_app_cancel_cnt		=	s_app_cancel_cnt+'".$appCancelCnt."'

								, s_app_total_price		=	s_app_total_price+'".($appPrice-$appCancelPrice)."'
								, s_app_total_cnt		=	s_app_total_cnt+'".($isApp-$appCancelCnt)."'
								
								, s_total_price			=	s_total_price+'".($webPrice+$mobilePrice+$appPrice)."'
								, s_total_cnt			=	s_total_cnt+'".($isWeb+$isMobile+$isApp)."'
								, s_total_cancel_price	=	s_total_cancel_price+'".($webCancelPrice+$mobileCancelPrice+$appCancelPrice)."'
								, s_total_cancel_cnt	=	s_total_cancel_cnt+'".($webCancelCnt+$mobileCancelCnt+$appCancelCnt)."'
								
								, s_total_total_price	=	s_total_total_price+'".(($webPrice+$mobilePrice+$appPrice)-($webCancelPrice+$mobileCancelPrice+$appCancelPrice))."'
								, s_total_total_cnt		=	s_total_total_cnt+'".(($isWeb+$isMobile+$isApp)-($webCancelCnt+$mobileCancelCnt+$appCancelCnt))."' 
							where 
								s_date					=	'".$yearTime."' 
							and 
								s_site					=	'".$SITECODE."'
						";
				}

				mysqli_query($DBINFO2, $yearSql);


				mysqli_query($DBINFO2, "update _XTUBE_PAYMENT_DATA set mbr_s_move_is = 'Y' where pdat_seq = '".$row['pdat_seq']."' ");

		}
	}
	function getStaffIs($NUMBER){
		$getStaffPhoneSet	=	socketPost('http://apping.or.kr/appadmin/get.staff.member.php');
		$getStaffPhone		=	json_decode($getStaffPhoneSet, true);
		//echo '<pre>';print_r($getStaffPhone);echo '</pre>';
		settype($getStaffPhone, 'array');
		
		if(in_array($NUMBER, $getStaffPhone)){
			$val	=	'Y';
		}else{
			$val	=	'N';
		}
		return $val;
		
	}


	function fileUploadTmp($file, $dir, $dirDelim=''){
		global $_FILES;
		$UPLOAD_ROOT	=	$_SERVER['DOCUMENT_ROOT'] . '/UPLOAD';

		if(!is_dir($UPLOAD_ROOT.'/'.$dir)){
			mkdir($UPLOAD_ROOT.'/'.$dir, 0777);
			chmod($UPLOAD_ROOT.'/'.$dir, 0777);
		}
		
		if($dirDelim != ''){
			if(!is_dir($UPLOAD_ROOT.'/'.$dir.'/'.$dirDelim)){
				mkdir($UPLOAD_ROOT.'/'.$dir.'/'.$dirDelim, 0777);
				chmod($UPLOAD_ROOT.'/'.$dir.'/'.$dirDelim, 0777);
			}
		}
		
		$uploadDir		=	$UPLOAD_ROOT.'/'.$dir.'/'.($dirDelim != '' ? $dirDelim.'/' : '');
		$fileChange		=	fileReName($_FILES[$file]['name']);
		//echo $_FILES[$file]['name'];
		//exit($uploadDir);
		$saveDir		=	$uploadDir.$fileChange;
		//exit( $saveDir );
		
		//echo $_FILES[$file]['tmp_name'];exit;
		if(move_uploaded_file($_FILES[$file]['tmp_name'], $saveDir)){
			//$return	=	true;
		}else{
			return;
		}
		return $fileChange;
	}

	function remoteFileUpload($file, $dir, $tmpDir, $oldFile=''){
		//echoAr($_FILES);
		if($_FILES[$file]['name'] != ''){
			$ALLImgUp		=	fileUploadTmp($file, $tmpDir);
			$saveFile		=	$_SERVER['DOCUMENT_ROOT']."/UPLOAD/".$tmpDir."/".$ALLImgUp;
			//echo $saveFile;exit;
			$remote_url		=	"http://push.sorachat.biz/resource.upload.php?action=upload&key=dkjwijklj2213dkjd_pushupload&dir=".$dir.'&oldFile='.$oldFile;
			$postData['Filedata']= "@".$saveFile;
			//$postData = array('action'=>'upload', 'site'=>'smartcore', 'Filedata'=>'@'.$saveFile);
			
			$ch = curl_init(); //세션초기화
			curl_setopt($ch, CURLOPT_URL, $remote_url);
			curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch);
			//echo $result;exit;
			unlink($saveFile);
			curl_close($ch);
			$resultAr	=	json_decode($result, true);
			settype($resultAr, 'array');
			//echoAr($resultAr);
			return $resultAr;
		}
		return array('result'=>'N', 'message'=>'첨부파일이 지정되지 않았습니다.', 'file'=>'', 'fileSize'=>'0');
	}

	function remoteFileDelete($oldFile=''){
		if($oldFile != ''){
			$result		=	socketPost('http://push.sorachat.biz/resource.upload.php', '&action=delete&key=dkjwijklj2213dkjd_pushupload&oldFile='.$oldFile);
			$resultAr	=	json_decode($result, true);
			settype($resultAr, 'array');

			return $resultAr;
		}else{
			return array('result'=>'N', 'message'=>'첨부파일이 지정되지 않았습니다.', 'file'=>'', 'fileSize'=>'0');
		}
	}

	function setCodeToEncodeFn($CODE){
		$pwd_enc	=	getRow("SELECT HEX(AES_ENCRYPT('".$CODE."', 'nhw2821!^&')) as PW;");
		return $pwd_enc['PW'];
	}

	function setCodeToDecodeFn($CODE){
		$pwd_dec	=	getRow("SELECT AES_DECRYPT(UNHEX('".$CODE."'), 'nhw2821!^&') as PW; ");
		return $pwd_dec['PW'];
	}

	function getExpireYearDate($date, $year=1){
		$getDate	=	getRow("SELECT DATE_ADD('".$date."', INTERVAL ".$year." YEAR) as da;");
		//echo $getDate['da'].'<br>';
		$exUnixTime	=	strtotime("-1day", strtotime($getDate['da']));
		//echo date('Y-m-d H:i:s', $exUnixTime);
		return $exUnixTime;
	}

	function getExpire2YearDate($date, $year=2){
		$getDate	=	getRow("SELECT DATE_ADD('".$date."', INTERVAL ".$year." YEAR) as da;");
		//echo $getDate['da'].'<br>';
		$exUnixTime	=	strtotime("-1day", strtotime($getDate['da']));
		//echo date('Y-m-d H:i:s', $exUnixTime);
		return $exUnixTime;
	}

	function getExpire3YearDate($date, $year=3){
		$getDate	=	getRow("SELECT DATE_ADD('".$date."', INTERVAL ".$year." YEAR) as da;");
		//echo $getDate['da'].'<br>';
		$exUnixTime	=	strtotime("-1day", strtotime($getDate['da']));
		//echo date('Y-m-d H:i:s', $exUnixTime);
		return $exUnixTime;
	}

	function getExpireMonthDate($date, $month=1){
		$getDate	=	getRow("SELECT DATE_ADD('".$date."', INTERVAL ".$month." MONTH) as da;");
		//echo $getDate['da'].'<br>';
		$exUnixTime	=	strtotime("-1day", strtotime($getDate['da']));
		//echo date('Y-m-d H:i:s', $exUnixTime);
		return $exUnixTime;
	}

	function getExpire3MonthDate($date, $month=3){
		$getDate	=	getRow("SELECT DATE_ADD('".$date."', INTERVAL ".$month." MONTH) as da;");
		//echo $getDate['da'].'<br>';
		$exUnixTime	=	strtotime("-1day", strtotime($getDate['da']));
		//echo date('Y-m-d H:i:s', $exUnixTime);
		return $exUnixTime;
	}

	function getExpire6MonthDate($date, $month=6){
		$getDate	=	getRow("SELECT DATE_ADD('".$date."', INTERVAL ".$month." MONTH) as da;");
		//echo $getDate['da'].'<br>';
		$exUnixTime	=	strtotime("-1day", strtotime($getDate['da']));
		//echo date('Y-m-d H:i:s', $exUnixTime);
		return $exUnixTime;
	}

	function getExpireWeekDate($date, $week=1){
		$getDate	=	getRow("SELECT DATE_ADD('".$date."', INTERVAL ".$week." WEEK) as da;");
		//echo $getDate['da'].'<br>';
		$exUnixTime	=	strtotime("-1day", strtotime($getDate['da']));
		//echo date('Y-m-d H:i:s', $exUnixTime);
		return $exUnixTime;
	}

	function getExpireDayDate($date, $day=5){
		$getDate	=	getRow("SELECT DATE_ADD('".$date."', INTERVAL ".$day." DAY) as da;");
		//echo $getDate['da'].'<br>';
		$exUnixTime	=	strtotime("-1day", strtotime($getDate['da']));
		//echo date('Y-m-d H:i:s', $exUnixTime);
		return $exUnixTime;
	}

	function getExpirePaymentMonthDate($date, $month=1){
		$getDate	=	getRow("SELECT DATE_ADD('".$date."', INTERVAL ".$month." MONTH) as da;");
		$exUnixTime	=	strtotime("+1day", strtotime($getDate['da']));
		return $exUnixTime;
	}

	function sumImg($fname, $img, $w2=145, $h2=145, $t='', $w='', $h='', $q=80){
		//global $dirPahName;
		$sumFolder	=	$fname.'sum/';
		echo '<BR>test : '.$fname;
		//return;
		if(!is_dir($sumFolder)){
			@mkdir($sumFolder, 0707);
			@chmod($sumFolder, 0707);
		}
		$wonImg = $fname.$img;	//원래이미지
		$bakImg = $sumFolder.'sum_'.$img;//복사할이미지
		
		if (file_exists($bakImg)) return;
		if (!file_exists($wonImg)) return;
		if(!$t) {
			$style = @getimagesize($wonImg);
			$w = $style[0]; $h = $style[1]; $t = $style[2];
		}
		//echo '형식 : '.$t;
		switch($t) {
			case 1: $src = imagecreatefromgif($wonImg);  break;
			case 2: $src = imagecreatefromjpeg($wonImg); break;
			case 3: $src = imagecreatefrompng($wonImg);  break;
			//case 6: $src = imagecreatefromwbmp($wonImg);  break;
			default: return;
		}

		$rate = $w2 / $w;
		$height = (int)($h * $rate);

		if ($height < $h2)
			$dst = imagecreatetruecolor($w2, $height);
		else
			$dst = imagecreatetruecolor($w2, $h2);

		/*
			0. getimagesize 에서 gif 일 경우에만 아래 적용
			1. imageCreate : resource 생성, imageCreateTrueColor 쓰면 안되는듯
			2. imageColorAllocate : resouce image를 블랙 등으로 할당
			3. imageColorTransparent : 2에서 할당한 색을 투명으로 바꿔줌
			4. imageCopyResized : 소스 이미지를 카피&리사이징
			5. imageGIF 로 최종 이미지 생성
		*/

		@imagecopyresampled($dst, $src, 0, 0, 0, 0, $w2, $height, $w, $h);
		@imagepng($dst, $bakImg, $q);
		@imagejpeg($dst, $bakImg, $q);
		chmod($bakImg, 0606);

		return 'sum_'.$img;
	}

	function ChangeTitle($IDX){
		$GETTITLE	=	getRow("SELECT di_site_name FROM _DOMAIN_INFO WHERE di_idx = '".$IDX."' ;");
		return $GETTITLE['di_site_name'];
	}


	//2016-12-07 SH 로그인코드 수정 푸쉬
	function And_send($PUSHKEY, $REGI, $Cidx='', $LOGCODE='', $UNIQ='', $TESTMODE=''){
		GLOBAL	$connUrlDomain;


		$headers = array(
			'Content-Type:application/json',
			'Authorization:key='.$PUSHKEY
		);

		//$GETCHATDATA	=	getValue('_CHATTING', "WHERE ch_idx = '".$Cidx."' ", 'ar', '*');
		//$GETPRFDATA		=	getValue('_PROFILE', "WHERE p_m_idx = '".$GETCHATDATA['ch_m_idx']."' ", 'ar', 'p_name, p_img1, p_sum_img, p_typing, p_sex, p_site');

		$Idxdata					=	explode("|", $Pidx);
		$arr						=	array();
		$arr['data']				=	array();
		$arr['registration_ids']	=	array();
		$arr['data']['type']		=	'logout';
		$arr['data']['uniq']		=	$UNIQ;
		$arr['data']['msg']			=	"[".$LOGCODE."] 로그인코드가 수정되었습니다.";


		if($MODE == 'FIRST_CHATTING') $arr['data']['CHAT_TYPE'] = 'FIRST_CHATTING';

		$arr['registration_ids'][0]	=	$REGI;

		
		if($TESTMODE == 'P'){
			//echo "POSTPUSH"; return;
			$POSTARR	=	urlencode(serialize($arr));
			$POSTPUSH	=	socketPost('http://push.sorachat.biz/chat.push.php', "&headers=".$headers."&arr=".$POSTARR);
			echo $POSTPUSH; exit;
		}else{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,    'https://android.googleapis.com/gcm/send');
			curl_setopt($ch, CURLOPT_HTTPHEADER,  $headers);
			curl_setopt($ch, CURLOPT_POST,    true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($arr));
			$response = curl_exec($ch);
			curl_close($ch);

		}
	}

	function autoHypenPhoneFn($tel){
		/*
		$_HP	=	str_replace(array("+", "8210"), array("", "010"), $hp);
		return trim(preg_replace("/(0(?:2|[0-9]{2}))([0-9]+)([0-9]{4}$)/", "\\1-\\2-\\3", $_HP));
		*/
		$tel = preg_replace("/[^0-9]/", "", $tel);    // 숫자 이외 제거
		if (substr($tel,0,2)=='02')
			return preg_replace("/([0-9]{2})([0-9]{3,4})([0-9]{4})$/", "\\1-\\2-\\3", $tel);
		else if (strlen($tel)=='8' && (substr($tel,0,2)=='15' || substr($tel,0,2)=='16' || substr($tel,0,2)=='18'))
			// 지능망 번호이면
			return preg_replace("/([0-9]{4})([0-9]{4})$/", "\\1-\\2", $tel);
		else
			return preg_replace("/([0-9]{3})([0-9]{3,4})([0-9]{4})$/", "\\1-\\2-\\3", $tel);
	}

	function photoUploadNew($file, $dir, $call='', $oldfile='', $compulsoryis=true, $onlyPhotois=true){
		global $UPLOAD_ROOT, $_FILES, $_POST;

		$FILEINFO	=	array();
		if($_FILES[$file]['name'] != ''){
			if($onlyPhotois	==	true){

				$file_ext	=	(strrchr($_FILES[$file]['name'], ".")) ?  strtolower(substr(strrchr(urldecode($_FILES[$file]['name']), "."),1)) : '';
				if(!in_array($file_ext, explode('|', $UPLOAD['IMG']))){
					;
				}else{
					exit('{"result":"N","message":"파일업로드는 gif,jpg,png 파일형식만 지원합니다.","url":"", "code":""}');
				}
			}

			if($oldfile	!=	''){
				//exit('fiel : '.$_SERVER['DOCUMENT_ROOT'].$oldfile);
				@unlink($_SERVER['DOCUMENT_ROOT'].$oldfile);
			}

			$_SAVEIMGDIR			=	$dir;
			$FILEINFO['_UPLOADFILE']=	fileUpload($file, $_SAVEIMGDIR."/");
			$FILEINFO['_DIR']		=	'/UPLOAD/'.$_SAVEIMGDIR.'/';
			$FILEINFO['_FILE']		=	$FILEINFO['_DIR'].$FILEINFO['_UPLOADFILE'];
			
			$FILEINFO['_ORGNAME_']	=	$_FILES[$file]['name'];
			$FILEINFO['_SIZE_']		=	$_FILES[$file]['size'];

			$FILEINFO['_WHERE']		=	", ".$file."		=	'".$FILEINFO['_DIR'].$FILEINFO['_UPLOADFILE']."' ";
			$FILEINFO['_ORGNAME']	=	", ".$file."_org	=	'".$_FILES[$file]['name']."'  ";
			$FILEINFO['_SIZE']		=	", ".$file."_size	=	'".$_FILES[$file]['size']."'  ";
		}else{
			if($compulsoryis	==	true){
				if($oldfile	==	'')
					exit('{"result":"N","message":"['.$file.']이미지는 반드시 첨부하시기 바랍니다.","url":"", "code":""}');
			}
		}
		//exit('teset : '.$FILEINFO[$call]);
		if($call	==	'ar'){
			//exit('x : '.$FILEINFO[$call]);
			return $FILEINFO;
		}else{
			//exit('e : '.$FILEINFO[$call]);
			return $FILEINFO[$call];
		}
	}

    function apkUploadNew($file, $dir, $call='', $oldfile='', $compulsoryis=true){
        global $UPLOAD_ROOT, $_FILES, $_POST;

        $FILEINFO	=	array();
        if($_FILES[$file]['name'] != ''){

            if($oldfile	!=	''){
                //exit('fiel : '.$_SERVER['DOCUMENT_ROOT'].$oldfile);
                @unlink($_SERVER['DOCUMENT_ROOT'].$oldfile);
            }

            $_SAVEAPKDIR			=	$dir;
            $FILEINFO['_UPLOADFILE']=	fileUpload($file, $_SAVEAPKDIR."/");
            $FILEINFO['_DIR']		=	'/UPLOAD/'.$_SAVEAPKDIR.'/';
            $FILEINFO['_FILE']		=	$FILEINFO['_DIR'].$FILEINFO['_UPLOADFILE'];

            $FILEINFO['_ORGNAME_']	=	$_FILES[$file]['name'];
            $FILEINFO['_SIZE_']		=	$_FILES[$file]['size'];

            $FILEINFO['_WHERE']		=	", ".$file."		=	'".$FILEINFO['_DIR'].$FILEINFO['_UPLOADFILE']."' ";
            $FILEINFO['_ORGNAME']	=	", ".$file."_org	=	'".$_FILES[$file]['name']."'  ";
            $FILEINFO['_SIZE']		=	", ".$file."_size	=	'".$_FILES[$file]['size']."'  ";
        }else{
            if($compulsoryis	==	true){
                if($oldfile	==	'')
                    exit('{"result":"N","message":"['.$file.']이미지는 반드시 첨부하시기 바랍니다.","url":"", "code":""}');
            }
        }
        //exit('teset : '.$FILEINFO[$call]);
        if($call	==	'ar'){
            //exit('x : '.$FILEINFO[$call]);
            return $FILEINFO;
        }else{
            //exit('e : '.$FILEINFO[$call]);
            return $FILEINFO[$call];
        }
    }

	function setSqlFilter($str){
//		if (!get_magic_quotes_gpc()) $str = addslashes($str);
		//$search		=	array("--","#",";");
		//$replace	=	array("\--","\#","\;");
		if(function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()) {
			$str = addslashes($str);
		}
        $search		=	array("--",";");
        $replace	=	array("\--","\;");
		$str		=	str_replace($search, $replace, $str);
		return $str;
	}

	function setGuideTypeCodeToText($obj){
		switch($obj){
			case 'PLAN' :
				$txt	=	'요금제';
			break;
			case 'CARD' :
				$txt	=	'카드';
			break;
			case 'MOB_PLAN' :
				$txt	=	'유선요금제';
			break;
			case 'FORMATION' :
				$txt	=	'TV편성표';
			break;
		}
		return $txt;
	}

	function getFormTypeCodeToText($v, $type){
		if($type	==	'TYPE'){
			if($v == 'AUTO'){
				$text	=	'<span class="text-blue" style="font-weight:700">자동서식지</span>';
			}elseif($v == 'EMPTY'){
				$text	=	'<span class="text-red" style="font-weight:700">빈서식지</span>';
			}
		}
		
		if($type	==	'PRICE'){
			if($v == 'BACK'){
				$text	=	'공시지원';
			}elseif($v == 'CHOICE'){
				$text	=	'선택약정';
			}else{
				$text	=	'모두';
			}
		}
		
		if($type	==	'PAY'){
			if($v == 'CASH'){
				$text	=	'현금';
			}elseif($v == 'PLAN'){
				$text	=	'할부';
			}else{
				$text	=	'모두';
			}
		}

		if($type	==	'MODE'){
			if($v == 'ADULT'){
				$text	=	'성인';
			}elseif($v == 'YOUTH'){
				$text	=	'청소년';
			}elseif($v == 'CHANGE'){
				$text	=	'기변';
			}elseif($v == 'YOUTH_CHANGE'){
				$text	=	'청소년기변';
			}elseif($v == 'ALL'){
				$text	=	'전체';
			}else{
				$text	=	'모두';
			}
		}

		if($type	==	'GUIDE'){
			if($v == 'PEN'){
				$text	=	'형광펜';
			}elseif($v == 'LINE'){
				$text	=	'밑줄';
			}elseif($v == 'NONE'){
				$text	=	'원본';
			} else {
				$text	=	'';
			}
		}
			
		if($type	==	'SCOPE'){
			if($v == 'SIMPLE'){
				$text	=	'고객기입부분만';
			}elseif($v == 'ALL'){
				$text	=	'고객기입+판매자기입';
			}elseif($v == 'NONE'){
				$text	=	'원본';
			} else {
				$text	=	'';
			}
		}

		return $text;
	}

	function getFormAgeTypeCodeToText($obj){
		switch($obj){
			case 'ADULT' :
				$text = '성인';
			break;
			case 'YOUTH' :
				$text = '청소년';
			break;
			case 'ALL' :
				$text = '전체';
			break;
			default :
				$text = '모두';
			break;
		}
		return $text;
	}

	function email_valid($temp_email,$message=''){

		if(preg_match("/^([0-9a-zA-Z_-]+)@([0-9a-zA-Z_-]+)(\.[0-9a-zA-Z_-]+){1,2}$/", trim($temp_email))){
			;
		}else{
//			exit('{"result":"N","message":"이메일 형식이 올바르지 않습니다.","url":""}');
			useExit('{"result":"N","message":"'.$message.'","ErrCODE":"MEMBER04"}');
		}
		//return ereg("^[0-9a-zA-Z_-]+(\.[0-9a-zA-Z_-]+)*@[0-9a-zA-Z_-]+(\.[0-9a-zA-Z_-]+)+$", $temp_email);
	}

	function reserveUserID($ID){
		global $PUSANKJS;
		if(preg_match("/[\,]?{$ID}/i", $PUSANKJS['PROHIBIT_ID']))
			exit('{"result":"N","message":"이미 예약된 단어로 사용할 수 없습니다.","url":""}');
		/*
		else
			return "";
		*/
	}

	function reserveUserEMAIL($ID){
		global $PUSANKJS;
		if(preg_match("/[\,]?{$ID}/i", $PUSANKJS['PROHIBIT_EMAIL']))
			exit('{"result":"N","message":"이미 예약된 단어로 사용할 수 없는 회원 이메일 입니다.","url":""}');
		/*
		else
			return "";
		*/
	}

	function getPartnerPermission($CODE){
		$allPermissionSum	=	getValue('cp_pay_request', " where seller = '".$CODE."' and pay_state = 4 ", 'price', 'SUM(permission) as price');
		return $allPermissionSum;
	}

	function getPartnerSales($CODE){
		$allPermissionSum	=	getValue('_SALES', " where s_partner_idx = '".$CODE."' and s_state <> 'N' ", 'price', 'SUM(s_price) as price');
		return $allPermissionSum;
	}

	function getSalesState($CODE){
		switch($CODE){
			case 'W' :
				$txt	=	'출금대기';
			break;
			case 'Y' :
				$txt	=	'출금완료';
			break;
			case 'N' :
				$txt	=	'출금신청 취소';
			break;
		}
		return $txt;
	}

	function getPaymentSum($CLASS, $FILED, $CODE){
		$CLASSAR	=	explode('|', $CLASS);
		$FILEDAR	=	explode('|', $FILED);

		$_WHERE	=	' where 1 ';
		if($CODE)
			$_WHERE	.=	" and seller = '".$CODE."' ";
		if($CLASSAR[0]	==	'TODAY'){
			$_WHERE	.=	" and date_format(reg_date, '%Y-%m-%d') = curdate() ";
		}else if($CLASSAR[0]	==	'WEEK'){
			$_WHERE	.=	" AND date_format(reg_date, '%Y-%m-%d') >= '".$CLASSAR['1']."' AND date_format(reg_date, '%Y-%m-%d') <= '".$CLASSAR['2']."' ";
		}else if($CLASSAR[0]	==	'MONTH'){
			$_WHERE	.=	" and date_format(reg_date, '%Y-%m') = '".$CLASSAR['1']."' ";
		}else if($CLASSAR[0]	==	'ALL'){
			$_WHERE	.=	" ";
		}

		return getValue('cp_pay_request', $_WHERE." and pay_state = 4 ", 'price', $FILEDAR[0].'('.$FILEDAR[1].') as price', false);
	}
	
	function naverSmartEditCall($id, $no=1){
		$html = <<<_HTML_
<script type="text/javascript">
var oEditors{$no} = [];

// 추가 글꼴 목록
//var aAdditionalFontSet = [["MS UI Gothic", "MS UI Gothic"], ["Comic Sans MS", "Comic Sans MS"],["TEST","TEST"]];

nhn.husky.EZCreator.createInIFrame({
	oAppRef: oEditors{$no},
	elPlaceHolder: "{$id}",
	sSkinURI: "/Conn/edit/smartEdit_2_0/SmartEditor2Skin.html",	
	htParams : {
		bUseToolbar : true,							// 툴바 사용 여부 (true:사용/ false:사용하지 않음)
		bUseVerticalResizer : false,				// 입력창 크기 조절바 사용 여부 (true:사용/ false:사용하지 않음)
		bUseModeChanger : true,						// 모드 탭(Editor | HTML | TEXT) 사용 여부 (true:사용/ false:사용하지 않음)
		//aAdditionalFontList : aAdditionalFontSet,	// 추가 글꼴 목록
		fOnBeforeUnload : function(){
			//alert("완료!");
		}
	}, //boolean
	fOnAppLoad : function(){
		//예제 코드
		//oEditors{$no}.getById["{$id}"].exec("PASTE_HTML", ["로딩이 완료된 후에 본문에 삽입되는 text입니다."]);
	},
	fCreator: "createSEditor2"
});
</script>
_HTML_;
		echo($html);
	}

	function tinymceEditCall(){
		$html = <<<_HTML_
tinymce.init({
selector:'textarea.tinymce',
allow_conditional_comments: false,
forced_root_block : false,
force_br_newlines : true,
force_p_newlines : false,
plugins: [
		"advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
		"searchreplace visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
		"table contextmenu directionality emoticons template textcolor paste textcolor  "
],
toolbar1: " bold italic underline strikethrough | alignleft aligncenter alignright alignjustify ",
toolbar2: " forecolor backcolor",
//toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft",
menubar: false,
toolbar_items_size: 'small',
style_formats: [
	{title: 'Bold text', inline: 'b'},
	{title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
	{title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
	{title: 'Example 1', inline: 'span', classes: 'example1'},
	{title: 'Example 2', inline: 'span', classes: 'example2'},
	{title: 'Table styles'},
	{title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
],
templates: [
	{title: 'Test template 1', content: '<span style="color:#cc0099">Test 1</span>'},
	{title: 'Test template 2', content: 'Test 2'}
],
textcolor_map: [
            "000000", "Black",
            "993300", "Burnt orange",
            "333300", "Dark olive",
            "003300", "Dark green",
            "ff0006", "color",
			"5677fc", "blue"
    ]
});
_HTML_;
		echo($html);
	}

	function urlLink($str){
		$str = preg_replace("/&lt;/", "\t_lt_\t", $str);
		$str = preg_replace("/&gt;/", "\t_gt_\t", $str);
		$str = preg_replace("/&amp;/", "&", $str);
		$str = preg_replace("/&quot;/", "\"", $str);
		$str = preg_replace("/&nbsp;/", "\t_nbsp_\t", $str);
		$str = preg_replace("/([^(http:\/\/)]|\(|^)(www\.[^[:space:]]+)/i", "\\1<A HREF=\"http://\\2\" TARGET='_blank'>\\2</A>", $str);
		$str = preg_replace("/([^(HREF=\"?'?)|(SRC=\"?'?)]|\(|^)((http|https|ftp|telnet|news|mms):\/\/[a-zA-Z0-9\.-]+\.[\xA1-\xFEa-zA-Z0-9\.:&#=_\?\/~\+%@;\-\|\,]+)/i", "\\1<A HREF=\"\\2\" TARGET='_blank'>\\2</A>", $str);
		$str = preg_replace("/(([a-z0-9_]|\-|\.)+@([^[:space:]]*)([[:alnum:]-]))/i", "<a href='mailto:\\1'>\\1</a>", $str);
		$str = preg_replace("/\t_nbsp_\t/", "&nbsp;" , $str);
		$str = preg_replace("/\t_lt_\t/", "&lt;", $str);
		$str = preg_replace("/\t_gt_\t/", "&gt;", $str);
		
		return $str;
	}
	
	function getDatediffFn($s, $t){
		date_default_timezone_set('Asia/Seoul');
		$startDate	=	date_create($s); // 오늘 날짜입니다.
		$targetDate	=	date_create($t); // 타겟 날짜를 지정합니다.
		$interval	=	date_diff($startDate, $targetDate);
		return $interval->days;
	}


	function getMacAdressbookGroup($name, $memberCODE=''){
		global $memberInfo, $getSiteSkin;
		if(trim($name)	==	'') $name	=	'미분류';

		$MCODE	=	$memberCODE ? $memberCODE : $memberInfo['m_idx'];
		$row	=	getValue('_MEMBER_ADDRESS_BOOK_GROUP', " where mabg_name = '".setOutput($name)."' and mabg_user_idx = '".$MCODE."' and  mabg_site = '".$getSiteSkin['di_idx']."' ", 'ar', 'mabg_idx');
		if($row['mabg_idx']){
			$return	=	$row['mabg_idx'];
		}else{
			$sql	=	"
				insert into 
					_MEMBER_ADDRESS_BOOK_GROUP 
				set 
					mabg_site			=	'".setOutput($getSiteSkin['di_idx'])."'
					, mabg_user_idx		=	'".setOutput($MCODE)."'
					, mabg_name			=	'".setOutput($name)."'
					, mabg_regdate		=	now()
					, mabg_regagent		=	'".$connectIP."'
					, mabg_reghost		=	'".$connectMachine."'
			";
			$rs		=	query($sql);
			$return	=	insert_id();
		}
		return $return;
	}

	function mmsImg($img, $w2=145, $h2=145, $t='', $w='', $h='', $q=80){
		global $_SERVER, $UPLOAD_ROOT;
		$sumFolder	=	$UPLOAD_ROOT.'/MMSFILE/';
		if(!is_dir($sumFolder)){
			@mkdir($sumFolder, 0707);
			@chmod($sumFolder, 0707);
		}

		$uploadDir		=	$UPLOAD_ROOT.'/MMSFILE/ORG/';
		$fileChange		=	fileReName($_FILES[$img]['name']);
		$saveDir		=	$uploadDir.$fileChange;
		$wonImg			=	$saveDir;	//원래이미지
		if(move_uploaded_file($_FILES[$img]['tmp_name'], $saveDir)){
			;
		}else{
			return;
		}

		$bakImg = $sumFolder.$fileChange;				//복사할이미지
		$bakImg2 = '/UPLOAD/MMSFILE/'.$fileChange;		//복사할이미지(저장하는거)
		
		//echo 'bakImg : '.$bakImg.', wonImg : '.$wonImg;exit;
		if(file_exists($bakImg)) return;
		if(!file_exists($wonImg)) return;
		if(!$t) {
			$style = @getimagesize($wonImg);
			$w = $style[0]; $h = $style[1]; $t = $style[2];
		}
		//exit($w .':::'. $w2 .'^^'. $h .':::'. $h2);
		if($w <= $w2 && $h <= $h2){
			//copy($wonImg, $wonImg);
			//move_uploaded_file($_FILES[$img]['tmp_name'], $saveDir);
			//$saveDir2		=	$sumFolder.$fileChange;
			//move_uploaded_file($_FILES[$img]['tmp_name'], $saveDir2);
			$bakImg2 = '/UPLOAD/MMSFILE/ORG/'.$fileChange;		//복사할이미지(저장하는거)
			//exit('bakImg2 : '.$bakImg2);
			return $bakImg2;
		}
		//echo '형식 : '.$t;
		switch($t) {
			case 1: $src = imagecreatefromgif($wonImg);  break;
			case 2: $src = imagecreatefromjpeg($wonImg); break;
			case 3: $src = imagecreatefrompng($wonImg);  break;
			//case 6: $src = imagecreatefromwbmp($wonImg);  break;
			default: return;
		}

		$rate = $w2 / $w;
		$height = (int)($h * $rate);

		if ($height < $h2)
			$dst = imagecreatetruecolor($w2, $height);
		else
			$dst = imagecreatetruecolor($w2, $h2);

		/*
			0. getimagesize 에서 gif 일 경우에만 아래 적용
			1. imageCreate : resource 생성, imageCreateTrueColor 쓰면 안되는듯
			2. imageColorAllocate : resouce image를 블랙 등으로 할당
			3. imageColorTransparent : 2에서 할당한 색을 투명으로 바꿔줌
			4. imageCopyResized : 소스 이미지를 카피&리사이징
			5. imageGIF 로 최종 이미지 생성
		*/

		@imagecopyresampled($dst, $src, 0, 0, 0, 0, $w2, $height, $w, $h);
		@imagepng($dst, $bakImg, $q);
		@imagejpeg($dst, $bakImg, $q);
		chmod($bakImg, 0606);

		return $bakImg2;
	}

	function setImgDpiChange($img, $dpi=96){
		global $_SERVER, $UPLOAD_ROOT;
		$sumFolder	=	$UPLOAD_ROOT.'/MMSFILE/';
		if(!is_dir($sumFolder)){
			@mkdir($sumFolder, 0707);
			@chmod($sumFolder, 0707);
		}
		


		$uploadDir		=	$UPLOAD_ROOT;
		$uploadDir2		=	'/MMSFILE/';
		$uploadDir3		=	'/ORG/';

		$fileChange		=	fileReName($_FILES[$img]['name']);
		$saveDir		=	$uploadDir.$uploadDir2.$uploadDir3.$fileChange;
		//$wonImg			=	$saveDir;	//원래이미지
		if(move_uploaded_file($_FILES[$img]['tmp_name'], $saveDir)){
			;
		}else{
			return;
		}
		
		$imagick = new Imagick($saveDir);
		$data = $imagick->identifyimage();
		//echo $data['resolution']['x'];exit;
		compress($saveDir, $saveDir, 60);
		$getImgSize	=	img_resize_only($saveDir, 700, 700);

		if($data['resolution']['x'] <= $dpi || $data['resolution']['y'] <= $dpi){
			//echo 'this';exit;
			return '/UPLOAD/'.$uploadDir2.$uploadDir3.$fileChange;
		}else{
			$imagick = new Imagick(); 
			$imagick->readImage($saveDir); 
			$imagick->setImageUnits(imagick::RESOLUTION_PIXELSPERINCH); 
			$imagick->setImageResolution($dpi,$dpi); 
			//$imagick->resizeImage($data['geometry']['width'],$data['geometry']['height'],Imagick::FILTER_LANCZOS,1); 
			$imagick->resizeImage($getImgSize[0],$getImgSize[1],Imagick::FILTER_LANCZOS,1); 
			$imagick->writeImage($uploadDir.$uploadDir2.$fileChange);
			return '/UPLOAD/'.$uploadDir2.$fileChange;

		}
	}

	function img_resize_only($img, $maxwidth, $maxheight){
		if($img) {
			// $img는 이미지의 경로(예:./images/phplove.gif) 
			$imgsize = getimagesize($img); 
			if($imgsize[0]>$maxwidth || $imgsize[1]>$maxheight) { 
				// 가로길이가 가로limit값보다 크거나 세로길이가세로limit보다 클경우 
				$sumw = (100*$maxheight)/$imgsize[1]; 
				$sumh = (100*$maxwidth)/$imgsize[0]; 
				if($sumw < $sumh) { 
				// 가로가 세로보다 클경우 
				$img_width = ceil(($imgsize[0]*$sumw)/100); 
				$img_height = $maxheight; 
				} else { 
				// 세로가 가로보다 클경우 
				$img_height = ceil(($imgsize[1]*$sumh)/100); 
				$img_width = $maxwidth; 
				} 
			} else { 
				// limit보다 크지 않는 경우는 원본 사이즈 그대로..... 
				$img_width = $imgsize[0]; 
				$img_height = $imgsize[1]; 
			} 
			
			$imgsize[0] = $img_width;
			$imgsize[1] = $img_height;
		} else {
			$imgsize[0] = $maxwidth;
			$imgsize[1] = $maxheight;
		}
		return $imgsize;
	}

	 function compress($source, $destination, $quality) {
 
        $info = getimagesize($source);
 
        if ($info['mime'] == 'image/jpeg') 
            $image = imagecreatefromjpeg($source);
 
        elseif ($info['mime'] == 'image/gif') 
            $image = imagecreatefromgif($source);
 
        elseif ($info['mime'] == 'image/png') 
            $image = imagecreatefrompng($source);
 
        imagejpeg($image, $destination, $quality);
 
        return $destination;
    }

    function setImgResize($file, $dir, $dirDelim=''){

	    global $UPLOAD_ROOT, $_FILES;

	    if(!is_dir($UPLOAD_ROOT.'/'.$dir)){
		    mkdir($UPLOAD_ROOT.'/'.$dir, 0777);
		    chmod($UPLOAD_ROOT.'/'.$dir, 0777);
	    }

	    if($dirDelim != ''){
		    if(!is_dir($UPLOAD_ROOT.'/'.$dir.'/'.$dirDelim)){
			    mkdir($UPLOAD_ROOT.'/'.$dir.'/'.$dirDelim, 0777);
			    chmod($UPLOAD_ROOT.'/'.$dir.'/'.$dirDelim, 0777);
		    }
	    }

	    $uploadDir		=	$UPLOAD_ROOT.'/'.$dir.'/'.($dirDelim != '' ? $dirDelim.'/' : '');
	    //ECHO $uploadDir;EXIT();
	    $fileChange		=	fileReName($_FILES[$file]['name']);
	    $saveDir		=	$uploadDir.$fileChange;
	    if(move_uploaded_file($_FILES[$file]['tmp_name'], $saveDir)){
		    ;
	    }else{
		    return;
	    }

	    compress($saveDir, $saveDir, 60);
	    $getImgSize	=	img_resize_only($saveDir, 100,100);

        return $fileChange;

//        return '/UPLOAD/'.$uploadDir2.$uploadDir3.$fileChange;

    }

	

	function setPointAdminFn($memberIDX, $point, $useIDX, $useIS, $method, $class){
		global $getSiteSkin, $connectIP, $agent;

		$sql = "
			insert into 
				_PAYMENT  
			SET 
				p_site			=	'".$getSiteSkin['di_idx']."'
				, p_user_idx	=	'".trim(addslashes($memberIDX))."'
				, p_class		=	'".trim(addslashes($class))."'
				
				, p_point		=	'".trim(addslashes($point))."'
				, p_use_idx		=	'".addslashes(trim($useIDX))."'
				, p_use_is		=	'".addslashes(trim($useIS))."'
				, p_method		=	'".addslashes(trim($method))."'
				
				, p_charge_is	=	'".addslashes(trim('Y'))."'
				
				, p_regdate		=	now()
				, p_reghost		=	'".$connectIP."'
				, p_regagent	=	'".$agent."'
		";
		//query("insert into _TEST set t_txt = '".trim(addslashes($sql))."' ");
		$rs	=	query($sql);
		return insert_id();
	}

	function getAllPointSum($CODE, $CLASS='CAL'){
		global $memberInfo;
		if($memberInfo['m_idx'] == '') return 0;
		if(!getValue('_MEMBER', " where m_idx = '".$CODE."' ", 'm_idx', 'm_idx')){
			return 0;
		}
		switch($CLASS){
			case 'PLUS' : 
				$_WHERE	=	" and p_type = '+' and p_use_is = 'Y' ";
				$_FILED	=	' sum(p_point) ';
			break;
			default : 
				$_WHERE	=	" and IF(p_type='+', p_use_is = 'Y', p_use_is <> 'N') ";
				$_FILED	=	" sum(CONCAT(p_type, p_point)) ";
			break;
		}

		/*
		if($CLASS	==	'PLUS'){
			$_WHERE	=	' ';
		}
		*/

		return (int)getValue('_POINT', " where p_user_idx = '".$CODE."'  ".$_WHERE." ", 'sum', $_FILED.' as sum');
	}

	function unique_multidim_array($array, $key){ 
		$temp_array = array(); 
		$i = 0; 
		$key_array = array(); 
		
		foreach($array as $val){
			if(!in_array($val[$key], $key_array)){
				$key_array[$i] = $val[$key]; 
				$temp_array[$i] = $val; 
			}
			$i++; 
		} 
		return $temp_array; 
	} 

	function getMemberInfoFn($memberIDX, $callType='ar', $f='*'){
		global $getSiteSkin;
		$memberInfo	=	getValue('_MEMBER', " where m_idx = '".$memberIDX."' /*and m_site = '".$getSiteSkin['di_idx']."'*/ ", $callType, $f);
		return $memberInfo;
	}
	

	function getGoogleGeoFn($addr1, $addr2=''){
		$addr1Ar		=	explode(')', $addr1);
		//echoAr('address='.urlencode(trim($addr1Ar[0]).' '.trim($addr2)).'&language=ko&sensor=false&output=json');
		$xyJson			=	socketGet('http://maps.googleapis.com/maps/api/geocode/json', 'address='.urlencode(trim($addr1Ar[0]).' '.trim($addr2)).'&language=ko&sensor=false&output=json');
		$xyAr			=	json_decode($xyJson, true);
		settype($xyAr, 'array');
		//echoAr($xyAr);

		if($xyAr['status']	==	'OK'){
			$result['addr1']		=	$addr1;
			$result['addr2']		=	$addr2;
			$result['lat']			=	$xyAr['results'][0]['geometry']['location']['lat'];
			$result['lng']			=	$xyAr['results'][0]['geometry']['location']['lng'];
			$newaddrAr				=	'';
			$result['newaddr1']		=	'';
			$result['newaddr2']		=	'';
		}else{
			$result['newaddr1']		=	$addr1;
			$result['newaddr2']		=	$addr2;
			$result['lat']			=	'';
			$result['lng']			=	'';
			$newaddrAr				=	'';
			$result['addr1']		=	'';
			$result['addr2']		=	'';
		}

		return $result;
	}


//	function fcm_push($message,$server_key,$receive_id){
//		$header = array(
//		'Authorization: key='.$server_key,
//		'Content-Type: application/json'
//		);
//
//		$body = array(
//			"priority"     => "high",
//			"data"         => $message
//		);
//
//		if(is_array($receive_id)){
//			$body['registration_ids'] = $receive_id;
//		}else{
//			$body['to']               = $receive_id;
//		}
//
//		$url = "https://fcm.googleapis.com/fcm/send";
//        //$url = "http://fcm.googleapis.com/fcm/send";
//
//		$data=json_encode($body);
//
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_POST, true);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//        $result = curl_exec($ch);
//        if ($result === FALSE) {
//            die('Curl failed: ' . curl_error($ch));
//        }
//        curl_close($ch);
//        return $result;
//
////		echo "url:".$url;
////        echo ",server_key:".$server_key;
////        echo ",data:".$data;
//
//		//return socketPostPush($url,$server_key,$data);
//	}

    // pushy 모듈 사용(TERRA VPN전용)
    function fcm_push($data,$to,$options){

        // Insert your Secret API Key here
//        $apiKey = '34d52376b58583e8a66c2f5c3b946ee7813e254ede2aa7fa2c088e2c44dcf950';
        $apiKey = '4f695adf98bca66e99b9ff4bc1ea8e2477332a84250c9a34a93eb4329246d48f';

        // Default post data to provided options or empty array
        $post = $options ?: array();

        // Set notification payload and recipients
        $post['to'] = $to;
        $post['data'] = $data;
//        echoAr($post);

        // Set Content-Type header since we're sending JSON
        $headers = array(
            'Content-Type: application/json'
        );

        // Initialize curl handle
        $ch = curl_init();

        // Set URL to Pushy endpoint
        curl_setopt($ch, CURLOPT_URL, 'https://api.pushy.me/push?api_key=' . $apiKey);

        // Set request method to POST
        curl_setopt($ch, CURLOPT_POST, true);

        // Set our custom headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Get the response back as string instead of printing it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Set post data as JSON
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post, JSON_UNESCAPED_UNICODE));

        // Actually send the push
        $result = curl_exec($ch);

        // Display errors
        if (curl_errno($ch)) {
            echo curl_error($ch);
        }

        // Close curl handle
        curl_close($ch);

        // Attempt to parse JSON response
        $response = @json_decode($result);

        // Throw if JSON error returned
//        if (isset($response) && isset($response->error)) {
//            throw new Exception('Pushy API returned an error: ' . $response->error);
//        }

        return $response->$result;

    }

    // pushy 모듈 사용(TERRA VPN전용)
    function ios_fcm_push($data,$to,$options){

        // Insert your Secret API Key here
//        $apiKey = 'b386ee7345e0bb7d49c80eb93fea6cd56815f308a53d0fc535a657fe84a0883c';
        $apiKey = '4f695adf98bca66e99b9ff4bc1ea8e2477332a84250c9a34a93eb4329246d48f';

        // Default post data to provided options or empty array
        $post = $options ?: array();

        // Set notification payload and recipients
        $post['to'] = $to;
        $post['data'] = $data;
//        echoAr("type:".$data['type']);
        if($data['type'] == "endofdate" || $data['type'] == "expire" || $data['type'] == "userstop" || $data['type'] == "logout"){
            $post['content_available'] = true;
        }
        //type == "endofdate"
        //type == "expire"
        //type == "userstop"
        //type == "logout"
//        echoAr($post);

        // Set Content-Type header since we're sending JSON
        $headers = array(
            'Content-Type: application/json'
        );

        // Initialize curl handle
        $ch = curl_init();

        // Set URL to Pushy endpoint
        curl_setopt($ch, CURLOPT_URL, 'https://api.pushy.me/push?api_key=' . $apiKey);

        // Set request method to POST
        curl_setopt($ch, CURLOPT_POST, true);

        // Set our custom headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Get the response back as string instead of printing it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Set post data as JSON
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post, JSON_UNESCAPED_UNICODE));

        // Actually send the push
        $result = curl_exec($ch);

        // Display errors
        if (curl_errno($ch)) {
            echo curl_error($ch);
        }

        // Close curl handle
        curl_close($ch);

        // Attempt to parse JSON response
        $response = @json_decode($result);

        // Throw if JSON error returned
//        if (isset($response) && isset($response->error)) {
//            throw new Exception('Pushy API returned an error: ' . $response->error);
//        }

        return $response->$result;

    }

	function socketPostPush($url="",$server_key='', $data='', $referer='', $port="80"){
		$uinfo	=	parse_url($url);

		$send_str = "POST {$uinfo['path']} HTTP/1.1\r\n".
			"Host: {$uinfo['host']}\r\n".
			"User-Agent: Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.2; .NET CLR 3.0.04506.648; .NET CLR 3.5.21022)\r\n".
			"Referer: {$referer}\r\n".
			"Content-Type: application/json\r\n".
		"Authorization: key=".$server_key."\r\n".
			"Content-length: ".strlen($data)."\r\n".
			"Connection: close\r\n\r\n".
			$data;
		$fp = fsockopen($uinfo['host'], $port);
		fputs($fp,$send_str);
		$source='';
		while(!feof($fp)){
		$source .= fgets($fp,4096);
		}
		fclose($fp);
		return preg_replace("/^HTTP.*\r\n\r\n/Uis", '', $source);
	}

    function sendMail($SUBJECT, $CONTENT, $MAILTO, $MAILTONAME, $RETURNIS=true){
		//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/PHPMailer_5.2.4/class.phpmailer.php';
        include_once $_SERVER['DOCUMENT_ROOT'].'/lib/PHPMailer/class.phpmailer.php';
		$mail             = new PHPMailer();
		$body             = $CONTENT;

		$mail->IsSMTP(); // telling the class to use SMTP
		$mail->Host       = "smtp.naver.com"; // SMTP server
		$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
												   // 1 = errors and messages
												   // 2 = messages only
		$mail->CharSet    = "utf-8";
		$mail->SMTPAuth   = true;                  // enable SMTP authentication
		$mail->SMTPSecure = "tls";                 // sets the prefix to the servier
		$mail->Host       = "smtp.naver.com";      // sets GMAIL as the SMTP server
		$mail->Port       = 587;             // set the SMTP port for the GMAIL server
		$mail->Username   = "seesoft1@naver.com";    // GMAIL username
		$mail->Password   = "see1228!@";         // GMAIL password

        $EMAIL = "seesoft1@naver.com";    // naver username
        $NAME = "EPIHAIM";

		$mail->SetFrom($EMAIL, $NAME);
		$mail->AddReplyTo($EMAIL, $NAME);
		//$mail->AddCC('cc@example.com');// 참조
		//$mail->AddBCC('bcc@example.com');//숨은참조
		//$mail->AddAttachment($_SERVER['DOCUMENT_ROOT'].'/img01_about_img.jpg');         // Add attachments   첨부 파일이 있을 경우
		//$mail->AddAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name  첨부 파일과 첨부파일 명
		$mail->IsHTML(true);                                  // Set email format to HTML  메일을 보낼 때 html 형식으로 메일을 보낼 경우 true

		$mail->Subject    = $SUBJECT;

		$mail->MsgHTML($body);

		$address = $MAILTO;
		$mail->AddAddress($address, $MAILTONAME);

		if(!$mail->Send()){
			
			if($RETURNIS){
				return('{"result":"N","message":"메일을 전송하지 못하였습니다.\n(사유 : '.$mail->ErrorInfo.').","CODE":"", "NAME":""}');
			}
		  //echo "Mailer Error: " . $mail->ErrorInfo;
		}else{
			//	exit('this');
			if($RETURNIS)
				return('{"result":"Y","message":"성공적으로 메일전송이 완료 하였습니다.","ID":""}');
		  //echo "Message sent!";
		}
	}

	function useExit($txt=''){
		global $DBINFO;
		mysqli_close($DBINFO);
		exit($txt);

	}

	function setPoint($type, $member_idx, $point, $use_is, $contents, $admCheck=''){
		global $memberInfo, $getSiteSkin, $connectIP, $agent;
		$sql	=	"
			insert into  
				_POINT  
			SET 
				p_site				=	'".$getSiteSkin['di_idx']."'
				, p_user_idx		=	'".setSqlFilter($member_idx)."'
				, p_type			=	'".setSqlFilter($type)."'
				, p_point			=	'".setSqlFilter($point)."'
				, p_use_is			=	'".setSqlFilter($use_is)."'
				, p_contents		=	'".setSqlFilter($contents)."'
				, p_admin_check		=	'".setSqlFilter($admCheck)."'
				, p_regdate			=	now()
				, p_regagent		=	'".$agent."'
				, p_reghost			=	'".$connectIP."'
		";
		$rs		=	query($sql);
		$id		=	insert_id();
		if($rs){
			return '{"result":"Y","message":"성공적으로 등록하였습니다.","CODE":"'.$id.'"}';
		}else{
			return '{"result":"N","message":"일시적 오류입니다.","url":"","CODE":""}';
		}
	}

	function filedown(){
        ob_start();
        $downfile="KakaoTalk_20200902_131201772.png";
        $path="/home/MONKEYvpn/public_html/UPLOAD/".$downfile;

        if(file_exists($path) && is_file($path)){
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.$downfile);
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: '.filesize($path));
            ob_clean();
            flush();
            readfile($path);
        }else{
            ?>
            <script>
                alert('파일이 없습니다.');
                history.back();
            </script>
            <?php
        }
    }

    function web_push($token='',$text=''){

        $tokens = $token;
        $url = 'https://fcm.googleapis.com/fcm/send';

        $fields = array(
            'to' => $tokens,
            'notification' => array(
                'title' => $text,
                'body' => 'Web Push Message',
                'click_action' => 'https://monkey.adamstore.co.kr/'
            )
        );
        $fcm_sever_key = 'AAAAoNrwqPw:APA91bHE3xQZ9AGu169d0uijaeT1poOtdGUZEMBVs4z0-taDpKu1EmwhfIkCwm6bDXE-T8hmYm5EQKktWjmw5VXbrbH0n2NTxvNhZvbVb7Pkg4gufnnm7RNFKDeicEqj6_zvUvMqD3sD';

        $headers = array(
            'Authorization:key =' . $fcm_sever_key,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);

        if ($result === FALSE) {
            die('Curl Failed: ' . curl_error($ch));
        }

        //$log = print_r($result);
        //echo ("<script language=javascript> console.log('log:'+$log);</script>");

        curl_close($ch);
    }


    function encodeJWTToken($m_uniq,$id){
        $tokenId = base64_encode("monstervpn".$id);
        $issuedAt = time();
        $notBefore = $issuedAt;
//        $expire = $notBefore + 60*60;       //1시간 만료
        $expire = $notBefore + 60*10000;       //7일 만료
        $serverName = "monstervpn";
        $secret_key = "monstervpn";

        $data = array(
            'iat' => $issuedAt,
            'jti' => $tokenId,
            'iss' => $serverName,
            'nbf' => $notBefore,
            'exp' => $expire,
            'info' => [
                'acco_id' => $id
               ,'uniq_id' => $m_uniq
            ]
        );

        try {
            $jwt = JWT::encode($data, $secret_key);
            return $jwt;
        } catch (Exception $e) {
            return "Z";
        }

        return $jwt;
    }

    function decodeJWTToken($TokenID){

        $secret_key = "monstervpn";
        try {
            $decoded = JWT::decode($TokenID, $secret_key, array('HS256'));

            $jsonList	=	array('result'=>'Y', 'data'=>$decoded, 'message'=>'성공');
            $json = json_encode( $jsonList);
            return $json;
        } catch (Exception $e) {

            $jsonList	=	array('result'=>'N', 'data'=>'인증에 실패하였습니다.','message'=>'인증에 실패하였습니다.');
            $json = json_encode( $jsonList);
            return $json;
        }
    }

    function checkTokenID($TokenID){
        $checkToken = decodeJWTToken($TokenID);
        $obj = json_decode($checkToken);
        if($obj->result == "Y"){
            return $obj;
        }else{
            useExit('{"result":"Z","data":"인증실패","message":"인증실패"}');
        }
    }

	function getMemberInfo($TokenID){
		$checkToken = decodeJWTToken($TokenID);

		$obj = json_decode($checkToken);
		if($obj->result == "Y"){
			$uniq_check = getValue("_MEMBER M","WHERE M.m_id = '".$obj->data->info->acco_id."'","m_idx","m_idx",false);
			if($uniq_check == ""){
				useExit('{"result":"Z","data":"로그인 정보를 확인할수 없습니다.","message":"로그인 정보를 확인할수 없습니다."}');
			}
			$json_info = json_encode($obj->data->info);
			$info = json_decode($json_info,true);
			return $info;
		}else{
			useExit('{"result":"Z","data":"인증실패","message":"인증실패"}');
		}
	}

    function getMemberID($TokenID){
        $checkToken = decodeJWTToken($TokenID);

        $obj = json_decode($checkToken);
        if($obj->result == "Y"){
            $uniq_check = getValue("_MEMBER M LEFT OUTER JOIN _MEMBER_DEVICE MD ON M.m_idx = MD.m_idx","WHERE M.m_id = '".$obj->data->info->acco_id."' AND MD.m_uniq='".$obj->data->info->uniq_id."' ","md_idx","md_idx",false);
            if($uniq_check == ""){
                useExit('{"result":"Z","data":"등록된 디바이스가 아닙니다.","message":"등록된 디바이스가 아닙니다."}');
            }
            return $obj->data->info->acco_id;
        }else{
            useExit('{"result":"Z","data":"인증실패","message":"인증실패"}');
        }
    }

	function fcm_topic_push($message_string = [],$server_key,$receive_id){
		$header = array(
			'Authorization: key='.$server_key,
			'Content-Type: application/json'
		);

		$body = array(
			"to" => $receive_id,
			"data" => $message_string,
		);

		$url = "https://fcm.googleapis.com/fcm/send";

		$data=json_encode($body);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$result = curl_exec($ch);
		if ($result === FALSE) {
			die('Curl failed: ' . curl_error($ch));
		}
		curl_close($ch);
		return $result;
	}
}


?>