<?php

	if(defined('_INCLUDED_GLOBAL') ) return;
	define('_INCLUDED_GLOBAL', '1');
	error_reporting(E_ALL ^ E_NOTICE);

	$_include_path	=	ini_get('include_path');
	$_src_root		=	dirname(dirname(__FILE__));
	$_include_path	=	'.'.PATH_SEPARATOR.$_src_root.PATH_SEPARATOR.$_src_root.'/include/pear'.PATH_SEPARATOR.'..'.PATH_SEPARATOR.'' . $_include_path;
	ini_set('include_path', $_include_path);

	if(!isset($_SERVER['REQUEST_URI'])){
		$_SERVER['REQUEST_URI'] = $_SERVER['PHP_SELF'];
		if($_SERVER['QUERY_STRING'] != '')
			$_SERVER['REQUEST_URI'] .= '?'.$_SERVER['QUERY_STRING'];
	}

	$AUTH			=	array();
	$DEBUG			=	array();
	$TB				=	array();
	$PUSANKJS_SYS	=	array();
	$PUSANKJS		=	array();
	$PSKIN			=	array();


	$SKIN_ROOT		=	$_SERVER['DOCUMENT_ROOT'] . '/Form';
	$LIBRARY_ROOT	=	$_SERVER['DOCUMENT_ROOT'] . '/lib';
	$UPLOAD_ROOT	=	$_SERVER['DOCUMENT_ROOT'] . '/UPLOAD';

	require_once $LIBRARY_ROOT.'/global_var.php';
	require_once $LIBRARY_ROOT.'/global.func.php';
	require_once $LIBRARY_ROOT.'/db.func.php';
	require_once $LIBRARY_ROOT.'/auth.func.php';
    require_once $LIBRARY_ROOT.'/common.siso';

	if(!$PUSANKJS_SYS[LC_CTYPE])
		$PUSANKJS_SYS[LC_CTYPE] = 'ko_KR.eucKR';
	@setlocale(LC_CTYPE, $PUSANKJS_SYS[LC_CTYPE]);

	$DEBUG['document']['start'] = getmicrotime();

	@header('P3P: CP="ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC"');
	if (!isset($set_time_limit)) $set_time_limit = 0;
	@set_time_limit($set_time_limit);

	@header("Content-Type: text/html; charset="._CHARSET_);
	$gmnow = gmdate("D, d M Y H:i:s") . " GMT";
	@header("Expires: 0");
	@header("Last-Modified: " . $gmnow);
	@header("Cache-Control: no-store, no-cache, must-revalidate");
	@header("Cache-Control: pre-check=0, post-check=0, max-age=0");
	@header("Pragma: no-cache");

	$DEBUG['session']['start'] = getmicrotime();

	ini_set('session.gc_maxlifetime', $PUSANKJS_SYS['SESSION_GC_MAXLIFETIME']);
	
	if(function_exists("session_cache_limiter")){
		if(in_array(basename($_SERVER['PHP_SELF']), array('download.php'))){
			session_cache_limiter('private');
			session_cache_expire(0);
		}else{
			session_cache_limiter('nocache');
		}
		
	}

	if ($PUSANKJS_SYS['SESSION_SAVE_PATH']){
		session_save_path($PUSANKJS_SYS['SESSION_SAVE_PATH']);
	}else{
		$SESSION_ROOT	=	$UPLOAD_ROOT . '/SESSION';	
		
		if(!is_dir($SESSION_ROOT)){
			mkdir($SESSION_ROOT, 0777);
			chmod($SESSION_ROOT, 0777);
		}

		session_save_path($SESSION_ROOT);
	}

	if($PUSANKJS_SYS['SESSION_DOMAIN']){
		session_set_cookie_params($PUSANKJS_SYS['SESSION_LIFETIME'], $PUSANKJS_SYS['SESSION_PATH'], $PUSANKJS_SYS['SESSION_DOMAIN']);
	}else{
		session_set_cookie_params($PUSANKJS_SYS['SESSION_LIFETIME'], $PUSANKJS_SYS['SESSION_PATH']);
	}

	@session_start();
	
	$DEBUG['session']['exec'] = getmicrotime() - $DEBUG['session']['start'];
	
	$AUTH = $_SESSION;

	//echoAr($siteSettingAr);
	$DBINFO = mysqli_connect($siteSettingAr['DBCONNECTINFO']['HOSTURL'],$siteSettingAr['DBCONNECTINFO']['HOSTID'],$siteSettingAr['DBCONNECTINFO']['HOSTPW'],$siteSettingAr['DBCONNECTINFO']['HOSTDB'],$siteSettingAr['DBCONNECTINFO']['HOSTPORT']);
	mysqli_set_charset($DBINFO, str_replace('-', '', _CHARSET_));

	header("Content-Type: text/html; charset="._CHARSET_);
	$agent			=	$_REQUEST['APPCONNECTCODE']	!=	'' ? ('APP@'.$_REQUEST['APPCONNECTCODE'])	:	('WEB@'.$_SERVER['HTTP_USER_AGENT']);
	
	//$connectMachine	=	$_REQUEST['APPCONNECTCODE']	!=	'' ? 'APP'	:	(preg_match('/iPhone|Mobile|UP.Browser|Android|BlackBerry|Windows CE|Nokia|webOS|Opera Mini|SonyEricsson|opera mobi|Windows Phone|IEMobile|POLARIS/', $_SERVER['HTTP_USER_AGENT']) ? 'MOBILE' : 'WEB');

	if($_REQUEST['CONNECTCODE']	==	'APP'){
		$connectMachine	=	'APP';
	}else if($_REQUEST['CONNECTCODE']	==	'PC'){
		$connectMachine	=	'PC';
    }else if($_REQUEST['CONNECTCODE']	==	'IOS'){
        $connectMachine	=	'IOS';
	}else{
		$connectMachine	=	preg_match('/iPhone|Mobile|UP.Browser|Android|BlackBerry|Windows CE|Nokia|webOS|Opera Mini|SonyEricsson|opera mobi|Windows Phone|IEMobile|POLARIS/', $_SERVER['HTTP_USER_AGENT']) ? 'MOBILE' : 'WEB';
	}
	//echo "connectMachine:".$connectMachine; exit;
	
	$connUrlDomain	=	_ONLY_SITE_URL;//str_replace(array('http://', 'www'), '', strtolower(trim($_REQUEST['siteUrl'])));
	//echo _ONLY_SITE_URL;
//echo $connUrlDomain;
	$connectIP		=	$_SERVER['REMOTE_ADDR'];

	if($connectMachine	==	'APP') {
        $connUrlDomain = str_replace(array('http://', 'www'), '', strtolower(trim($_REQUEST['siteUrl'])));
    }else if($connectMachine	==	'IOS'){
        $connUrlDomain  =   _IOS_SITE_URL;
	}else{
		$connUrlDomain	=	_ONLY_SITE_URL;//$_SERVER['HTTP_HOST'];
	}
//	echo $connUrlDomain;exit;
    //echo "lang:".$_COOKIE['_LANG']; exit;
//    echo $_REQUEST['siteUrl']; exit;
	$getSiteSkin=	getSiteInfo($connUrlDomain);
//	echoAr($getSiteSkin);

//	$_SENDTYPE = $_REQUEST;
    if($getSiteSkin['di_livecheck'] == "N"){
	    $_SENDTYPE = $_REQUEST;
    }else{
	    $_SENDTYPE = $_POST;
    }

	if($connectMachine	==	'APP'){
		//$memberInfo	=	getValue('_MEMBER', " where m_idx = '".$_REQUEST['_APP_MEM_IDX']."' and m_site = '".$getSiteSkin['di_idx']."' ", 'ar', "*, concat(m_expire_date,' ',m_expire_time) as expire_datetime");
		$memberInfo	=	getValue('_MEMBER', " where m_uniq = '".$_REQUEST['_UNIQ']."' and m_site = '".$getSiteSkin['di_idx']."' ", 'ar', "*");
    }else if($connectMachine	==	'IOS') {
        $memberInfo	=	getValue('_MEMBER', " where m_uniq = '".$_REQUEST['_UNIQ']."' and m_site = '".$getSiteSkin['di_idx']."' ", 'ar', "*");
	}else if($connectMachine	==	'PC') {
        $memberInfo = getValue('_MEMBER', " where m_idx = '" . $_REQUEST['_APP_MEM_IDX'] . "' and m_site = '" . $getSiteSkin['di_idx'] . "' /*and (m_class = '" . trim(addslashes(setSqlFilter($_POST['CLASS']))) . "' or m_class='MAIN')*/  ", 'ar', "*");
    //}else if($connectMachine	==	'MOBILE'){
        //$iosmemberInfo	= getValue('_MEMBER', " where m_idx = '".$_SESSION['_IOS_IDX']."' ", 'ar', "*",false);
        //echoAr($iosmemberInfo); exit;
	}else{
		/*
		if($isAdmin)
			$memberInfo	=	getValue('_ADMIN', " where adm_idx = '".getSession('_ADM_IDX')."' and adm_level = 'root' ", 'ar', '*', false);
		elseif($isTm)
			$memberInfo	=	getValue('_ADMIN', " where adm_idx = '".getSession('_TM_IDX')."' and adm_level = 'tm' ", 'ar', '*', false);
		elseif($isStaff)
			$memberInfo	=	getValue('_ADMIN', " where adm_idx = '".getSession('_STAFF_IDX')."' and adm_level = 'job' ", 'ar', '*', false);
		else
			$memberInfo	=	getValue('_MEMBER', " where m_idx = '".getSession('_MEM_IDX')."' and m_site = '".$getSiteSkin['di_idx']."' ", 'ar', "*, concat(m_expire_date,' ',m_expire_time) as expire_datetime");
		*/

		$memberInfo	=	getValue('_MEMBER', " where m_idx = '".getSession('_IDX')."'  and m_site = '".$getSiteSkin['di_idx']."'  ", 'ar', '*', false);

//		if(getSession('_PARTNER_IDX')){
//			$partnerInfo	=	getValue('_PARTNER', " where p_idx = '".getSession('_PARTNER_IDX')."' /*and adm_level = 'root'*/ ", 'ar', '*', false);
//			//echoAr($partnerInfo);
//		}

	}
	//echoAr($memberInfo);

	/*
	$isAdmin	=	$_SESSION['_LEVEL'] == 9 ? true : false;
	$isTm		=	$_SESSION['_LEVEL'] == 8 ? true : false;
	$isStaff	=	$_SESSION['_LEVEL'] == 7 ? true : false;
	$isuser		=	$_SESSION['_LEVEL'] == 1 ? true : false;
	*/
//	echoAr($memberInfo); exit;
	//echoAr($_SESSION);
	//echo $_SESSION['_LEVEL'];exit;
	$isSuper	=	$memberInfo['m_idx'] && $_SESSION['_LEVEL'] == 10 ? true : false;
	$isAdmin	=	$memberInfo['m_idx'] && $_SESSION['_LEVEL'] >= 9 ? true : false;
	$isuser		=	$memberInfo['m_idx'] && $_SESSION['_LEVEL'] >= 1 ? true : false;
	//echo $isuser;exit;

	$isSell		=	$partnerInfo['p_idx'] ? true : false;

    if($_SESSION["CONNECTCODE"] == "IOS"){
        $iosmemberInfo  =   getValue("_MEMBER m","WHERE M_IDX = '".$_SESSION['_IOS_IDX']."'","ar","m.*,DATEDIFF(m.m_expire_datetime, NOW()) as checkdate");
        //$iosmemberInfo  =   getIOSLogin($_SESSION['_IOS_IDX'],$_SESSION["m_uniq"],$_SESSION["fcm"],$_SESSION["os"]);
    }
    $isIOS		    =	$iosmemberInfo['m_idx'] ? true : false;

	//$payCount		=	getValue('_PAYMENT_DATA', "where pdat_phone = '".$memberInfo['m_hp']."' and pdat_site = '".$getSiteSkin['di_idx']."' ", 'cnt', 'count(pdat_seq) as cnt');

	$SITE_VER		=	'1.0';
	$PC_VER['ALL']	=	'1.1.1';
	$PC_VER['LITE']	=	'1.0.0';

    $SITE_TITLE		=	'EPIHAIM 관리자';
    $SITE_LICENSE	=	"Copyright ⓒ 2022 All rights Reserved";
    $SITE_SKIN		=	'musicPot';
    $SITE_NAME		=	'EPIHAIM';
    $META_TITLE		=	'EPIHAIM';
    $META_CONTENTS	=	'EPIHAIM';

	//웹사이트 정보
	$PUSANKJS_SYS['SITE_VER']		=	'1.0';
	$PUSANKJS_SYS['SITE_TITLE']		=	$SITE_TITLE;
	$PUSANKJS_SYS['SITE_LICENSE']	=	$SITE_LICENSE;
	$PUSANKJS_SYS['SITE_SKIN']		=	$SITE_SKIN == '' ? 'musicPot' : $SITE_SKIN;

//	$PUSANKJS_SYS['A_SITE_VER']		=	'1.0';
//	$PUSANKJS_SYS['A_SITE_TITLE']	=	':::: 앱 통합 관리자 페이지 ::::';
//	$PUSANKJS_SYS['A_SITE_LICENSE']	=	'Copyright ⓒ 2021 ㈜MONKEYVPN All rights Reserved';
//	$PUSANKJS_SYS['A_SITE_SKIN']	=	'_adm';
//
//	$PUSANKJS_SYS['T_SITE_VER']		=	'1.0';
//	$PUSANKJS_SYS['T_SITE_TITLE']	=	':::: 앱 통합 고객센터 페이지 ::::';
//	$PUSANKJS_SYS['T_SITE_LICENSE']	=	'Copyright ⓒ 2013 ㈜COREPLANET All rights Reserved';
//	$PUSANKJS_SYS['T_SITE_SKIN']	=	'_tm';
//
//	$PUSANKJS_SYS['S_SITE_VER']		=	'1.0';
//	$PUSANKJS_SYS['S_SITE_TITLE']	=	':::: 앱 통합 직원 전용 페이지 ::::';
//	$PUSANKJS_SYS['S_SITE_LICENSE']	=	'Copyright ⓒ 2013 ㈜COREPLANET All rights Reserved';
//	$PUSANKJS_SYS['S_SITE_SKIN']	=	'_staff';
	
	//$siteInfo		=	getValue('_SITE', " where 1 ");

	define('_DF_SECURE_KEY_', ucwords(str_replace('.', ' ', $connUrlDomain)) );
	define('_DF_COOKIE_DOMAIN_', '.'.$connUrlDomain);

	//if($_CLASS)	$_GETAPPINFO	=	getValue('_SETTING', " where s_class = '".$_CLASS."'  ");

	//$myPointSumValue	=	getAllPointSum($memberInfo['m_idx']);


    // HTTPS 체크 및 URL 리턴
//    if (!isset($_SERVER["HTTPS"])) {
//        header('Location: https://' . $_SERVER["HTTP_HOST"] . $_SERVER['REQUEST_URI']);
//    }

?>