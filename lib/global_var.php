<?php
define('_CHARSET_', 'UTF-8');
define('_DB_ERROR_', true);
define('_ONLY_SITE_URL', $_SERVER['HTTP_HOST']);
//define('_ONLY_SITE_URL', preg_replace('/.*([^\.]{1,})((\.co)?\.[a-z0-9]{2,})$/Uis', '\\1\\2', $_SERVER['HTTP_HOST']));
define('_SITE_URL', 'http://'._ONLY_SITE_URL);

define('_THIS_URL', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);

//define('_ADMIN_DIR', '/_adm/');
define('_ADMIN_DIR', '/manager/');
define('_ADMIN_URL', _ADMIN_DIR.'home.siso');
define('_ADMIN_LOGIN_URL', _ADMIN_DIR.'login.siso');


//define('_ADMIN_FULL_DIR', '/admin/');
//define('_ADMIN_FULL_URL', _ADMIN_FULL_DIR.'member.siso');
////define('_ADMIN_FULL_LOGIN_URL', _ADMIN_FULL_DIR.'login.siso');
//define('_ADMIN_LOGIN_URL', _ADMIN_DIR.'login.siso?loginAfterUrl='.urlencode(_THIS_URL));

define('_USER_DIR', '/');
define('_USER_URL', _USER_DIR);

define('_SELL_DIR', '/_sell/');
define('_SELL_URL', _SELL_DIR.'sell.siso');
define('_SELL_LOGIN_URL', _SELL_DIR.'login.siso');

define('_IOS_DIR', '/_ios/');
define('_IOS_URL', _IOS_DIR.'index.siso');
define('_IOS_LOGIN_URL', _IOS_DIR.'login.siso');

define('_APPS_DIR', '/apps/');
define('_APPS_URL', _APPS_DIR.'apps.siso');

define('_SMS_SEND_URL', 'https://marketingmonster.kr');

$siteSettingAr	=	Array(
    'DBCONNECTINFO'	=> Array(
        'HOSTURL'			=>	'123.111.231.19',
        'HOSTID'			=>	'epihaim',
        'HOSTPW'			=>	'epihaim2022!@#$',
        'HOSTDB'			=>	'epihaim_db',
        'HOSTPORT'			=>	'3306',
    )
);

$PSKIN['ver'] = '1.0';	//버젼 체크

if(!$_COOKIE['sessionTimeCookie']){
	@SetCookie('sessionTimeCookie',(int)$sessionTime,-1,'/');
}
if($_COOKIE['sessionTimeCookie']	!=	''){
	$PUSANKJS_SYS['SESSION_LIFETIME']		=	$_COOKIE['sessionTimeCookie']*60;		// 세션 유효시간.  초단위.	사용자 지정시간
}else{
	$PUSANKJS_SYS['SESSION_LIFETIME']		=	0;		// 세션 유효시간.  초단위.
}
$PUSANKJS_SYS['SESSION_PATH']			=	'/';	// 세션 유효경로.
$PUSANKJS_SYS['SESSION_DOMAIN']			=	'';		// 세션을 공유할 경우에만 도메인명 입력.  ex) .xxx.com => www.xxx.com, sub.xxx.com
//$PUSANKJS_SYS['SESSION_GC_MAXLIFETIME'] =	2600000;	// 세션 찌꺼기 제거시간.		ex) 1시간 => 3600, 2시간 => 7200, 30일=>2600000
$PUSANKJS_SYS['SESSION_GC_MAXLIFETIME'] =	3600;	// 세션 찌꺼기 제거시간.		ex) 1시간 => 3600, 2시간 => 7200, 30일=>2600000


$PUSANKJS_SYS['PAGE_ROOT'] = dirname(dirname(__FILE__));

if($PUSANKJS_SYS['PAGE_ROOT'] == dirname(realpath($_SERVER['SCRIPT_FILENAME']))){
	$PUSANKJS_SYS['URL_ROOT'] = dirname($_SERVER['PHP_SELF']);
}else{
	$PUSANKJS_SYS['URL_ROOT'] = dirname(dirname($_SERVER['PHP_SELF']));
}

if(($PUSANKJS_SYS['URL_ROOT'] == '/') || ($PUSANKJS_SYS['URL_ROOT'] == '\\') ) $PUSANKJS_SYS['URL_ROOT'] = '';
$PUSANKJS_SYS['HTTP_URL_ROOT'] = 'http://' . $PUSANKJS_SYS['SITE_NAME'] . $PUSANKJS_SYS['URL_ROOT'];

if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
	$PUSANKJS_SYS['PAGE_ROOT'] = str_replace('\\', '/', $PUSANKJS_SYS['PAGE_ROOT']);
}

$PUSANKJS_SYS['WEBLOGS_DIR']		=	dirname($PUSANKJS_SYS['PAGE_ROOT']).'/weblogs';
$PUSANKJS_SYS['FILTERTEMP_DIR']		=	dirname($PUSANKJS_SYS['PAGE_ROOT']).'/filtertemp';

//$PUSANKJS_SYS['PWD_ENC']		=	'HEX';		//MD5, MYSQL(기본값은 MD5), HEX	<<= 앱핑 사용 안함
$PUSANKJS_SYS['CHARSET']		=	_CHARSET_;	//언어셋
$PUSANKJS_SYS['LINK_TARGET']	=	'_blank';	//링크
	

$UPLOAD['IMG']		=	'gif|jpg|jpeg|png|bmp';
$UPLOAD['FLASH']	=	'swf';
$UPLOAD['MOVIE']	=	'asx|asf|wmv|wma|mpg|mpeg|mov|avi|mp3';


$PUSANKJS_SYS['DEBUG_TYPE']				=	'HIDDEN';
$PUSANKJS_SYS['DEBUG_QUERY']			=	'HIDDEN';
$PUSANKJS_SYS['DEBUG_QUERY_OVER_TIME']	=	2;
$PUSANKJS_SYS['NO_TAG']					=	'script|iframe';
$PUSANKJS_SYS['COUPON_USE_CNT']			=	'10';

$PUSANKJS['filename']	=	substr(strrchr(urldecode($_SERVER['PHP_SELF']), "/"),1);
//$PUSANKJS['LEVEL']		=	Array('guest' => 0, 'out' => 1, 'wait' => 2, 'user' => 3, 'staff' => 7, 'tm' => 8, 'root' => 9);
$PUSANKJS['LEVEL']						=	Array('guest' => 0, 'recruit' => 1, 'user' => 2, 'sysop' => 5, 'root' => 9, 'super' => 10);
$PUSANKJS['LEVEL_NAME']	=	Array(0 => '손님', 1 => '탈퇴회원', 2 => '가입대기', 3 => '회원', 7 => '직원', 8 => '고객센터', 9 => '슈퍼관리자');
$PUSANKJS['LEVEL_CODE_NAME']			=	Array('guest' => '비회원', 'recruit' => '훈련병', 'user' => '회원', 'sysop' => '운영진', 'root' => '관리자', 'super' => '슈퍼관리자');
$PUSANKJS['LEVEL_CODE_NAME2']			=	Array('recruit' => '훈련병', 'user' => '회원', 'sysop' => '운영진', 'root' => '관리자');
$PUSANKJS['LEVEL_NAME']					=	Array(0 => '비회원', 1 => '훈련병', 2 => '회원', 5 => '운영진', 9 => '관리자', 10 => '슈퍼관리자');

$PUSANKJS['SITE_CODE']	=	Array('nsearcher' => '엔서처');
$PUSANKJS['SITE_PRICE']['nsearcher']	=	Array('LITE' => '200,000', 'PRO'=>'400,000');


$PUSANKJS['PROHIBIT_ID']				=	'admin,administrator,dbmaker,webmaster,sysop,manager,root,su,guest,siso,coreplanet';
//$PUSANKJS['PROHIBIT_EMAIL']				=	'admin@k-buddy.com,administrator@k-buddy.com,관리자@k-buddy.com,운영자@k-buddy.com,어드민@k-buddy.com,주인장@k-buddy.com,webmaster@k-buddy.com,웹마스터@k-buddy.com,sysop@k-buddy.com,시삽@k-buddy.com,시샵@k-buddy.com,manager@k-buddy.com,매니저@k-buddy.com,메니저@k-buddy.com,root@k-buddy.com,루트@k-buddy.com,su@k-buddy.com,guest@k-buddy.com,방문객@k-buddy.com,k-buddy@k-buddy.com,buddy@k-buddy.com,kbuddy@k-buddy.com,케이버디@k-buddy.com';

$bankInfoeAr	=	Array(
	'name'		=>	'ㅇㅇ은행'
	, 'number'	=>	'0000-000-000000'
	, 'per'		=>	'돌직구'
);

$ext_arr = array ('PHP_SELF', '_ENV', '_GET', '_POST', '_FILES', '_SERVER', '_COOKIE', '_SESSION', '_REQUEST', 
			  'HTTP_ENV_VARS', 'HTTP_GET_VARS', 'HTTP_POST_VARS', 'HTTP_POST_FILES', 'HTTP_SERVER_VARS', 
			  'HTTP_COOKIE_VARS', 'HTTP_SESSION_VARS', 'GLOBALS');
$ext_cnt = count($ext_arr);
for($i=0; $i<$ext_cnt; $i++){
	if(isset($_GET[$ext_arr[$i]])) unset($_GET[$ext_arr[$i]]);
}

@extract($_GET);
@extract($_POST);
@extract($_SERVER); 
?>
