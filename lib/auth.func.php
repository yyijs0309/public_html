<?php
/*#################################################################################################
#########	생성일 : 2013-01-02
#########	만든이 : 김종수 부장
#########	연락처 : 010-6368-2650
#########	이메일 : pusankjs@naver.com
#########	파일명 : auth.func.php / 권한 관련 함수
#################################################################################################*/

	//메뉴별 권한처리
	function menu_auth_limit($menu_code='',$level_key=''){
		global $_SESSION,$isuser,$isSuper,$isAdmin;
		$base_filename = basename($_SERVER['PHP_SELF']); //현재 페이지 파일명

		$menu_check = getValue("_MENU","WHERE m_menu_url='".$base_filename."'","CNT","COUNT(*) CNT");
		if($menu_check>0){
			$TABLE = "_MENU M";
			$JOINTABLE = " INNER JOIN _MENU_AUTH MA ON M.idx = MA.ma_m_idx INNER JOIN _AUTH A ON A.a_idx = MA.ma_a_idx ";
			//$chkMenuAuth = getValue($TABLE.$JOINTABLE,"WHERE A.a_authgroup_code = '".$_SESSION['_AUTH_LEVEL']."' AND m_menu_id = '".$menu_code."' AND M.m_menu_url = '".$base_filename."' AND MA.ma_state = 'Y' ","CNT","COUNT(*) CNT",false);
			$chkMenuAuth = getValue($TABLE.$JOINTABLE,"WHERE A.a_authgroup_code = '".$_SESSION['_AUTH_LEVEL']."' AND M.m_menu_url = '".$base_filename."' AND MA.ma_state = 'Y' ","CNT","COUNT(*) CNT",false);

			//페이지 권한처리
			if($chkMenuAuth < 1){
//            msg('접근권한이 없습니다.', '', 'history.back();');
				msg('접근권한이 없습니다.', '/manager/home.siso', '');
			}
		}else{
			if($level_key	==	'super'){
//            if(!$isSuper) msg('접근불가!', '', 'history.back();');
				if(!$isSuper) msg('접근불가!', '/manager/home.siso', '');
			}elseif($level_key	==	'root'){
				if(!$isAdmin) msg('', _ADMIN_LOGIN_URL);
			}elseif($level_key	==	'guest'){

			}else{
				if(!$isuser){
					msg('로그인 이후 가능한 페이지입니다', '/');
				}
			}
		}
	}
//권한 설정
function auth_limit($level_key='', $type='NORMAL'){
	global $PUSANKJS, $_SESSION, $isSuper, $isAdmin, $isSell, $isuser, $isIOS;

	//페이지 권한처리
//	echo 'level key : '.$level_key.' ::: ',$isAdmin;exit;

//    echo "111:".$level_key; exit;

	if($level_key	==	'super'){
		//exit('superis : '.$isSuper);
		if(!$isSuper) msg('접근불가!', '', 'history.back();');
	}elseif($level_key	==	'root'){
		if(!$isAdmin) msg('', _ADMIN_LOGIN_URL);
	}elseif($level_key	==	'sell'){
		//echo _SELL_LOGIN_URL;exit;
		if(!$isSell) msg('', _SELL_LOGIN_URL);
    }elseif($level_key	==	'ios'){
        //echo _IOS_LOGIN_URL;exit;
        //echo $isIOS; exit;
        if(!$isIOS) msg('', _IOS_LOGIN_URL);
	}elseif($level_key	==	'guest'){

	}else{
		//echo $isuser;exit;
		if(!$isuser){
			msg('로그인 이후 가능한 페이지입니다', '/');
			//define('_ADMIN_LOGIN_URL', );
			//msg('', _USER_DIR.'login.siso?loginAfterUrl='.base64_encode(_THIS_URL));
		}
	}
}

function board_auth_limit($level_key=''){
	global $memberInfo, $PUSANKJS, $isAdmin, $issysop;
	//echo $level_key.':::'.$memberInfo['m_level'].'<br>';
	//echo $PUSANKJS['LEVEL'][$level_key].':::'.$PUSANKJS['LEVEL'][$memberInfo['m_level']];exit();
	//echo $PUSANKJS['LEVEL'][$memberInfo['m_level']];	echo $level_key;exit;
	if($PUSANKJS['LEVEL'][$level_key]	>	$PUSANKJS['LEVEL'][$memberInfo['m_level']]){
		msg('접근 권한이 없습니다.\n접근 가능 등급 : '.$PUSANKJS['LEVEL_CODE_NAME'][$level_key], '/');
	}
	
	if($isAdmin || $issysop){
		;
	}else{
		if($level_key	==	'recruit' && $PUSANKJS['LEVEL'][$level_key]	!=	$PUSANKJS['LEVEL'][$memberInfo['m_level']]){
			msg('접근 권한이 없습니다.\n접근 가능 등급 : '.$PUSANKJS['LEVEL_CODE_NAME'][$level_key], '', 'history.back()');
		}
	}

	/*
	if($level_key	==	'super'){
		msg('', '', 'history.back()');
	}

	if($level_key	==	'sysop'){
		msg('', '', 'history.back()');
	}

	if($level_key	==	'user'){
		msg('', '', 'history.back()');
	}

	if($level_key	==	'recruit'){
		msg('', '', 'history.back()');
	}
	*/
}

/*
function auth_limit($level_key='', $type='NORMAL'){
	global $PUSANKJS, $_SESSION, $isAdmin, $isTm, $isStaff;
	
	//간단하게 그냥 변경.. 여기 정도는 이게 젤 정확할듯 ... [2013-01-28 pusankjs]
	if($level_key	==	'root'){
		if(!$isAdmin) msg('', _ADMIN_LOGIN_URL);
	}elseif($level_key	==	'tm'){
		if(!$isTm) msg('', _TM_LOGIN_URL);
	}elseif($level_key	==	'sta'){
		if(!$isStaff) msg('', _STAFF_LOGIN_URL);
	}else{
		if(!$isuser) msg('', '/index.php');
	}



	//exit();

	//일단 관리자만 체크(관리자를 회원 테이블에 넣고자 했는데.......복사 뜨고 할때.. 별도 디비로 가는게 좋을것 같음 [2013-01-02] pusankjs)
	if($type	==	'REVERSE'){
		//if($level_key == 'root')
	}else{
		if(($level_key == 'root' ? $_SESSION['_ADM_IDX'] : $_SESSION['_MEM_IDX'])	==	''){		// 로그인했을때 레벨확인
			$returnUrl	=	$level_key == 'root' ? _ADMIN_LOGIN_URL : '/index.php';
			//msg('잘못된 접근입니다.', $returnUrl);
			msg('', $returnUrl);
		}

		//exit($level_key);
		//exit('test : '.$PUSANKJS['LEVEL'][$level_key]);
		if(!strcmp($PUSANKJS['LEVEL'][$level_key], NULL)){
			if(!strcmp($PUSANKJS['LEVEL_NAME'][$level_key], NULL)){	
				msg('level_key 에서 다음 항목은 존재하지 않습니다.','', 'history.back();return;');// guest 가 0이므로 strcmp() 로 비교함.
			}else{
				$level = $level_key;
			}
		}else{
			$level = $PUSANKJS['LEVEL'][$level_key];
		}
		//exit($type);
		
		//exit('test : '.$_SESSION['_LEVEL']);
		//echo $_SESSION['_LEVEL'] < $level ? 'true' : 'false';exit();
		if(($level_key == 'root' ? $_SESSION['_ADM_IDX'] : $_SESSION['_MEM_IDX'])){		// 로그인했을때 레벨확인
			switch($type){
				case 'NORMAL':		// 사용자레벨이 제한레벨보다 높아야 접근가능
					//if($_SESSION['_LEVEL'] < $level)
					//	msg('현재 페이지에 접근 권한이 없습니다.\\n\\n다음 레벨이상만 접근이 가능합니다.\\n\\n=> ' . $PUSANKJS['LEVEL_NAME'][$level], '', 'history.back(); return;');
				break;

				case 'REVERSE':		// 사용자레벨이 제한레벨보다 낮아야 접근가능
					if($_SESSION['_LEVEL'] > $level)
						msg('현재 페이지에 접근 권한이 없습니다.\\n\\n다음 레벨이하만 접근이 가능합니다.\\n\\n=> ' . $PUSANKJS['LEVEL_NAME'][$level], '', 'history.back(); return;');
				break;

				case 'ONLY':		// 사용자레벨이 제한레벨과 같아야 접근가능
					if($_SESSION['_LEVEL'] != $level)
						msg('현재 페이지에 접근 권한이 없습니다.\\n\\n다음 레벨일때만 접근이 가능합니다.\\n\\n=> ' . $PUSANKJS['LEVEL_NAME'][$level], '', 'history.back(); return;');
				break;

				default:
					msg('레벨제한모드에서 다음 항목은 존재하지 않습니다.<br>=> ' . $type);
				break;

			}
			

			// [2007-05-29]보안 - 쿠키에 저장된 세션ID가 사고로 유출되더라도 다른 IP에서 불법적인 접근을 할 수 없도록 제한함.
			// 패치를 적용할때 접속중인 사용자가 로그아웃되는 것을 방지하기 위해 auth_m_loginhost 세션이 생성되었을때만 체크함.
			// [2008-02-28]IP보안 사용자 선택

			if ($_SESSION["_AUTH_IS_IP_CHECK"] == 'Y' && $_SESSION["_AUTH_M_LOGINHOST"] && $_SESSION["_AUTH_M_LOGINHOST"] != $_SERVER["REMOTE_ADDR"]){
				// [2007-09-19] 경고 문구 및 에러로그 남김.
				$msg = "[보안경고]접근하신 IP(".$_SERVER["REMOTE_ADDR"].")가 로그인한 IP(".$_SESSION["_AUTH_M_LOGINHOST"].")와 다릅니다.";
				msg("[".$_SESSION["_NAME"]. "]님의 로그인한 IP(".$_SESSION["_AUTH_M_LOGINHOST"].")와 접근한 IP(".$_SERVER["REMOTE_ADDR"].")가 다르므로 강제 로그아웃되었습니다.", '', 'YES');	
				loginSeverUpFn($_SESSION['_MEM_IDX'], 'LOGOUT', $msg);
				auth_proc('LOGOUT');
								

				
				//msg($msg, 0);
				
			}	// if()


		}else if($level != $PUSANKJS['LEVEL']['guest']){		// 로그인 하지 않았고 제한레벨이 손님이상이 아닐때 전체로그인창 출력
			//msg('', '', 'history.back(); return;');
		}
	}
	//exit;

}
*/
?>