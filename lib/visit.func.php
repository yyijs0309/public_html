<?php
/*#################################################################################################
#########	생성일 : 2013-01-16
#########	만든이 : 김종수 부장
#########	연락처 : 010-6368-2650
#########	이메일 : pusankjs@naver.com
#########	파일명 : visit.func.php / 방문자 함수
#########	첫개발 : SIR.CO.KR	/	수정 개발자 : pusankjs
#################################################################################################*/
if(!defined("_INCLUDED_VISITFUNC")){
	define("_INCLUDED_VISITFUNC", "1");
	//참고 : http://www.sir.co.kr/bbs/board.php?bo_table=g4_tiptech&wr_id=29055 [2013-01-16 pusankjs]
	function get_brow($agent){ 
		$agent = strtolower($agent); 

		if (preg_match("/msie 5.0[0-9]*/", $agent))			{ $s = "IE 5.0"; } 
		else if(preg_match("/inapp/", $agent))				{ $s = "NAVER Apps"; } //네이버어플
		else if(preg_match("/navercafe/", $agent))			{ $s = "NAVER CAFE Apps"; } //네이버 카페어플
		else if(preg_match("/daumapps/", $agent))			{ $s = "DAUM Apps"; } //다음어플
		else if(preg_match("/natebrowser/", $agent))		{ $s = "NATE Apps"; } //네이트어플
		else if(preg_match("/9b176/", $agent))				{ $s = "Google Apps"; } //구글어플
		//브라우저 어플 끝 
		else if(preg_match("/maxthon/", $agent))			{ $s = "Maxthon"; } //Maxthon
		else if(preg_match("/apple-pubsub/", $agent))		{ $s = "Apple-PubSub"; } //Safari Feed Reader
		else if(preg_match("/mqqbrowser/", $agent))			{ $s = "MQQBrowser"; } //MQQBrowser
		else if(preg_match("/diigobrowser/", $agent))		{ $s = "DiigoBrowser"; } //DiigoBrowser
		else if(preg_match("/avant browser/", $agent))		{ $s = "Avant Browser"; } //Avant Browser
		else if(preg_match("/embedded/", $agent))			{ $s = "Embedded Web Browser"; } //Embedded Web Browser
		else if(preg_match("/browserng/", $agent))			{ $s = "BrowserNG"; } //BrowserNG
		else if(preg_match("/smarthub|smart-tv|smarttv/", $agent))			{ $s = "Smart TV"; } //Smart TV
		else if(preg_match("/flipboardproxy/", $agent))		{ $s = "FlipboardProxy"; } //FlipboardProxy
		else if(preg_match("/wosbrowser/", $agent))			{ $s = "wOSBrowser"; } //wOSBrowser
		//기타 브라우저 끝 
		else if(preg_match("/google/", $agent))				{ $s = "Google Robot"; } //구글로봇
		else if(preg_match("/mediapartners/", $agent))		{ $s = "Google AdSense"; } //구글애드센스
		else if(preg_match("/-mobile/", $agent))			{ $s = "Google-Mobile Robot"; } //구글모바일로봇
		else if(preg_match("/naver blog/", $agent))			{ $s = "NAVER Blog Rssbot"; } //네이버블로그로봇
		else if(preg_match("/naver|yeti/", $agent))			{ $s = "NAVER Robot"; } //네이버로봇
		else if(preg_match("/daum/", $agent))				{ $s = "DAUM Robot"; } //다음로봇
		else if(preg_match("/yahoo/", $agent))				{ $s = "Yahoo! Robot"; } //야후!로봇
		else if(preg_match("/empas|nate/", $agent))			{ $s = "NATE Robot"; } //네이트로봇
		else if(preg_match("/bing/", $agent))				{ $s = "Bing Robot"; } //Bing로봇
		else if(preg_match("/msn/", $agent))				{ $s = "MSN Robot"; } //MSN로봇
		else if(preg_match("/zum/", $agent))				{ $s = "Zum Robot"; } //Zum로봇
		else if(preg_match("/qrobot/", $agent))				{ $s = "Qrobot"; } //Qrobot로봇
		else if(preg_match("/archive|ia_archiver/", $agent)){ $s = "Archive Robot"; } //아카이브로봇
		else if(preg_match("/twitter/", $agent))			{ $s = "Twitter Robot"; } //Twitter로봇
		else if(preg_match("/facebook/", $agent))			{ $s = "Facebook Robot"; } //Facebook로봇
		else if(preg_match("/whois/", $agent))				{ $s = "Whois Search Robot"; } //Whois Search로봇
		else if(preg_match("/checkprivacy/", $agent))		{ $s = "KISA"; } //한국인터넷진흥원
		//robots.txt로 차단 안되는 로봇 
		else if(preg_match("/mj12/", $agent))				{ $s = "MJ12bot"; } //MJ12bot
		else if(preg_match("/baidu/", $agent))				{ $s = "Baiduspider"; } //Baiduspider
		else if(preg_match("/yandex/", $agent))				{ $s = "YandexBot"; } //YandexBot로봇
		else if(preg_match("/Sogou/", $agent))				{ $s = "Sogou web spider"; } //Sogou로봇
		else if(preg_match("/tweetedtimes/", $agent))		{ $s = "TweetedTimes Bot"; } //TweetedTimes Bot
		else if(preg_match("/discobot/", $agent))			{ $s = "Discoveryengine Robot"; } //Discoveryengine로봇
		else if(preg_match("/twiceler/", $agent))			{ $s = "Twiceler Robot"; } //Twiceler로봇
		else if(preg_match("/ezooms/", $agent))				{ $s = "Ezooms Robot"; } //Ezooms로봇
		else if(preg_match("/wbsearch/", $agent))			{ $s = "WBSearchBot"; } //WBSearchBot
		else if(preg_match("/proximic/", $agent))			{ $s = "proximic"; } //proximic로봇
		else if(preg_match("/GTWek/", $agent))				{ $s = "GTWek"; } //GTWek로봇
		else if(preg_match("/java|python|axel|dalvik|greatnews|hmschnl|huawei|jakarta|netcraft|parrotsite|readability|unwind|pagepeeker|shunix|crystalsemantics|turnitin|komodia|siteIntel|apercite|butterfly/", $agent))										{ $s = "Unknown Robot"; } //Unknown로봇
		else if(preg_match("/cron/", $agent))				{ $s = "WebCron"; } //WebCron
		else if(preg_match("/capture/", $agent))			{ $s = "WebCapture"; } //WebCapture로봇
		else if(preg_match("/w3c/", $agent))				{ $s = "W3C Validator"; } //W3C Validator
		else if(preg_match("/wget/", $agent))				{ $s = "Wget Validator"; } //Wget
		else if(preg_match("/hanrss/", $agent))				{ $s = "HanRSS"; } //HanRSS
		else if(preg_match("/fetcher/", $agent))			{ $s = "Feed Fetcher"; } //Feed Fetcher
		else if(preg_match("/feed|reader|rss|greatnews/", $agent))			{ $s = "Feed Reader"; } //Feed Reader
		else if(preg_match("/bot|slurp|scrap|spider|crawl|curl/", $agent))	{ $s = "Robot"; }
		else if(preg_match("/msie 5.5[0-9]*/", $agent))		{ $s = "IE 5.5"; } 
		else if(preg_match("/msie 6.0[0-9]*/", $agent))		{ $s = "IE 6.0"; } 
		else if(preg_match("/msie 7.0[0-9]*/", $agent))		{ $s = "IE 7.0"; } 
		else if(preg_match("/msie 8.0[0-9]*/", $agent))		{ $s = "IE 8.0"; } 
		else if(preg_match("/msie 9.0[0-9]*/", $agent))		{ $s = "IE 9.0"; } 
		else if(preg_match("/msie 10.0[0-9]*/", $agent))	{ $s = "IE 10.0"; } 
		else if(preg_match("/msie 4.[0-9]*/", $agent))		{ $s = "IE 4.x"; } 
		else if(preg_match("/firefox/", $agent))			{ $s = "FireFox"; } 
		else if(preg_match("/chrome/", $agent))				{ $s = "Chrome"; } 
		else if(preg_match("/x11/", $agent))				{ $s = "Netscape"; } 
		else if(preg_match("/opera/", $agent))				{ $s = "Opera"; } 
		else if(preg_match("/safari/", $agent))				{ $s = "Safari"; } 
		else if(preg_match("/netFront/", $agent))			{ $s = "NetFront"; } 
		else if(preg_match("/gec/", $agent))				{ $s = "Gecko"; } 
		else if(preg_match("/internet explorer/", $agent))	{ $s = "IE"; } 
		else if(preg_match("/mozilla/", $agent))			{ $s = "Mozilla"; } 
		else { $s = "기타"; } 

		return $s; 
	}

	function get_os($agent){
		$agent = strtolower($agent);

		if (preg_match("/windows 98/", $agent))                 { $s = "Windows 98"; }
		else if(preg_match("/windows 95/", $agent))             { $s = "Windows 95"; }
		else if(preg_match("/windows nt 4\.[0-9]*/", $agent))   { $s = "Windows NT"; }
		else if(preg_match("/windows nt 5\.0/", $agent))        { $s = "Windows 2000"; }
		else if(preg_match("/windows nt 5\.1/", $agent))        { $s = "Windows XP"; }
		else if(preg_match("/windows nt 5\.2/", $agent))        { $s = "Windows 2003"; }
		else if(preg_match("/windows nt 6\.0/", $agent))        { $s = "Windows Vista"; }
		else if(preg_match("/windows nt 6\.1/", $agent))        { $s = "Windows7"; }
		else if(preg_match("/windows 9x/", $agent))             { $s = "Windows ME"; }
		else if(preg_match("/windows ce/", $agent))             { $s = "Windows CE"; }
		else if(preg_match("/mac/", $agent))                    { $s = "Macintosh"; }
		else if(preg_match("/linux/", $agent))                  { $s = "Linux"; }
		else if(preg_match("/sunos/", $agent))                  { $s = "sunOS"; }
		else if(preg_match("/irix/", $agent))                   { $s = "IRIX"; }
		else if(preg_match("/phone/", $agent))                  { $s = "Phone"; }
		else if(preg_match("/bot|slurp/", $agent))              { $s = "Robot"; }
		else if(preg_match("/internet explorer/", $agent))      { $s = "IE"; }
		else if(preg_match("/mozilla/", $agent))                { $s = "Mozilla"; }
		else { $s = "기타"; }

		return $s;
	}

	function get_made($agent){ 
		$agent = strtolower($agent); 

		if (preg_match("/msie 5.0[0-9]*/", $agent))			{ $s = "Microsoft"; } 
		else if(preg_match("/inapp/", $agent))				{ $s = "NAVER"; } //네이버어플
		else if(preg_match("/navercafe/", $agent))			{ $s = "NAVER"; } //네이버 카페어플
		else if(preg_match("/daumapps/", $agent))			{ $s = "DAUM"; } //다음어플
		else if(preg_match("/natebrowser/", $agent))		{ $s = "NATE"; } //네이트어플
		else if(preg_match("/9b176/", $agent))				{ $s = "Google"; } //구글어플
		//브라우저 어플 끝 
		else if(preg_match("/maxthon/", $agent))			{ $s = "maxthon"; } //Maxthon
		else if(preg_match("/apple-pubsub/", $agent))		{ $s = "Apple"; } //Safari Feed Reader
		else if(preg_match("/mqqbrowser/", $agent))			{ $s = "MQQ"; } //MQQBrowser
		else if(preg_match("/diigobrowser/", $agent))		{ $s = "Diigo"; } //DiigoBrowser
		else if(preg_match("/avant browser/", $agent))		{ $s = "Avant"; } //Avant Browser
		else if(preg_match("/embedded/", $agent))			{ $s = "Embedded"; } //Embedded Web Browser
		else if(preg_match("/browserng/", $agent))			{ $s = "NG"; } //BrowserNG
		else if(preg_match("/smarthub|smart-tv|smarttv/", $agent))			{ $s = "Smart TV"; } //Smart TV
		else if(preg_match("/flipboardproxy/", $agent))		{ $s = "Flipboard"; } //FlipboardProxy
		else if(preg_match("/wosbrowser/", $agent))			{ $s = "wOS"; } //wOSBrowser
		//기타 브라우저 끝 
		else if(preg_match("/google/", $agent))				{ $s = "Google"; } //구글로봇
		else if(preg_match("/mediapartners/", $agent))		{ $s = "Google"; } //구글애드센스
		else if(preg_match("/-mobile/", $agent))			{ $s = "Google"; } //구글모바일로봇
		else if(preg_match("/naver blog/", $agent))			{ $s = "NAVER"; } //네이버블로그로봇
		else if(preg_match("/naver|yeti/", $agent))			{ $s = "NAVER"; } //네이버로봇
		else if(preg_match("/daum/", $agent))				{ $s = "DAUM"; } //다음로봇
		else if(preg_match("/yahoo/", $agent))				{ $s = "Yahoo"; } //야후!로봇
		else if(preg_match("/empas|nate/", $agent))			{ $s = "NATE"; } //네이트로봇
		else if(preg_match("/bing/", $agent))				{ $s = "Microsoft"; } //Bing로봇
		else if(preg_match("/msn/", $agent))				{ $s = "Microsoft"; } //MSN로봇
		else if(preg_match("/zum/", $agent))				{ $s = "Estsoft"; } //Zum로봇
		else if(preg_match("/qrobot/", $agent))				{ $s = "Q"; } //Qrobot로봇
		else if(preg_match("/archive|ia_archiver/", $agent)){ $s = "Archive"; } //아카이브로봇
		else if(preg_match("/twitter/", $agent))			{ $s = "Twitter"; } //Twitter로봇
		else if(preg_match("/facebook/", $agent))			{ $s = "Facebook"; } //Facebook로봇
		else if(preg_match("/whois/", $agent))				{ $s = "Whois"; } //Whois Search로봇
		else if(preg_match("/checkprivacy/", $agent))		{ $s = "KISA"; } //한국인터넷진흥원
		//robots.txt로 차단 안되는 로봇 
		else if(preg_match("/mj12/", $agent))				{ $s = "MJ12"; } //MJ12bot
		else if(preg_match("/baidu/", $agent))				{ $s = "Baiduspider"; } //Baiduspider
		else if(preg_match("/yandex/", $agent))				{ $s = "YandexBot"; } //YandexBot로봇
		else if(preg_match("/Sogou/", $agent))				{ $s = "Sogou"; } //Sogou로봇
		else if(preg_match("/tweetedtimes/", $agent))		{ $s = "TweetedTimes"; } //TweetedTimes Bot
		else if(preg_match("/discobot/", $agent))			{ $s = "Discovery"; } //Discoveryengine로봇
		else if(preg_match("/twiceler/", $agent))			{ $s = "Twiceler"; } //Twiceler로봇
		else if(preg_match("/ezooms/", $agent))				{ $s = "Ezooms"; } //Ezooms로봇
		else if(preg_match("/wbsearch/", $agent))			{ $s = "WBSearchBot"; } //WBSearchBot
		else if(preg_match("/proximic/", $agent))			{ $s = "proximic"; } //proximic로봇
		else if(preg_match("/GTWek/", $agent))				{ $s = "GTWek"; } //GTWek로봇
		else if(preg_match("/java|python|axel|dalvik|greatnews|hmschnl|huawei|jakarta|netcraft|parrotsite|readability|unwind|pagepeeker|shunix|crystalsemantics|turnitin|komodia|siteIntel|apercite|butterfly/", $agent))										{ $s = "Unknown"; } //Unknown로봇
		else if(preg_match("/cron/", $agent))				{ $s = "WebCron"; } //WebCron
		else if(preg_match("/capture/", $agent))			{ $s = "WebCapture"; } //WebCapture로봇
		else if(preg_match("/w3c/", $agent))				{ $s = "W3C"; } //W3C Validator
		else if(preg_match("/wget/", $agent))				{ $s = "Wget"; } //Wget
		else if(preg_match("/hanrss/", $agent))				{ $s = "HanRSS"; } //HanRSS
		else if(preg_match("/fetcher/", $agent))			{ $s = "Feed Fetcher"; } //Feed Fetcher
		else if(preg_match("/feed|reader|rss|greatnews/", $agent))			{ $s = "Feed Reader"; } //Feed Reader
		else if(preg_match("/bot|slurp|scrap|spider|crawl|curl/", $agent))	{ $s = "Robot"; }
		else if(preg_match("/msie 5.5[0-9]*/", $agent))		{ $s = "Microsoft"; } 
		else if(preg_match("/msie 6.0[0-9]*/", $agent))		{ $s = "Microsoft"; } 
		else if(preg_match("/msie 7.0[0-9]*/", $agent))		{ $s = "Microsoft"; } 
		else if(preg_match("/msie 8.0[0-9]*/", $agent))		{ $s = "Microsoft"; } 
		else if(preg_match("/msie 9.0[0-9]*/", $agent))		{ $s = "Microsoft"; } 
		else if(preg_match("/msie 10.0[0-9]*/", $agent))	{ $s = "Microsoft"; } 
		else if(preg_match("/msie 4.[0-9]*/", $agent))		{ $s = "Microsoft"; } 
		else if(preg_match("/firefox/", $agent))			{ $s = "Mozilla"; } 
		else if(preg_match("/chrome/", $agent))				{ $s = "Google"; } 
		else if(preg_match("/x11/", $agent))				{ $s = "Netscape"; } 
		else if(preg_match("/opera/", $agent))				{ $s = "Opera"; } 
		else if(preg_match("/safari/", $agent))				{ $s = "Apple"; } 
		else if(preg_match("/netFront/", $agent))			{ $s = "NetFront"; } 
		else if(preg_match("/gec/", $agent))				{ $s = "Gecko"; } 
		else if(preg_match("/internet explorer/", $agent))	{ $s = "Microsoft"; } 
		else if(preg_match("/mozilla/", $agent))			{ $s = "Mozilla"; } 
		else { $s = "기타"; } 

		return $s; 
	}
	

	function appLinkcoreVisitFn($KEY){
		$sql	=	"
						select 
							A.agent as agent
							, A.at_type as at_type
							, A.at_visit_cnt as at_visit_cnt
							, A.at_site_only as at_site_only
							, date_format(A.at_regdate, '%Y-%m-%d %H') as hourTime
							, date_format(A.at_regdate, '%Y-%m-%d') as dayTime
							, date_format(A.at_regdate, '%Y-%m') as monthTime
							, date_format(A.at_regdate, '%Y') as yearTime 
						from 
							app_linkcore_db._APP_LINK as A 
						where 
							at_idx	=	'".$KEY."'
						order by at_regdate asc
					";
		//echo $sql;exit;
		$rs		=	query($sql);
		if(rows() > 0){
			for($i=0; $row = assoc($rs); $i++){
				//echo $row['hourTime'].'<br>';
				$siteCode	=	$row['at_site_only'];
				$hourTime	=	$row['hourTime'];
				$dayTime	=	$row['dayTime'];
				$monthTime	=	$row['monthTime'];
				$yearTime	=	$row['yearTime'];
				
				$visitCnt	=	$row['at_visit_cnt'];

				$isWeb		=	$row['at_type']	==	'WEB' ? 1 : 0;
				$isMobile	=	$row['at_type']	==	'MOBILE' ? 1 : 0;
				$isApp		=	$row['at_type']	==	'APP' ? 1 : 0;

				$traffic	=	getVisitFn('MADE', str_replace('WEB@', 'MOBILEAPP@', $row['agent']));
				$os			=	getVisitFn('OS', str_replace('WEB@', 'MOBILEAPP@', $row['agent']));
				//echo $os;

				$isAndroid		=	0;
				$isIos			=	0;
				$isWindows		=	0;
				$isLinux		=	0;
				$isRobot		=	0;
				$isEtc			=	0;

				if(strstr(strtolower($traffic), 'bot')){
					$isAndroid		=	0;
					$isIos			=	0;
					$isWindows		=	0;
					$isLinux		=	0;
					$isRobot		=	1;
					$isEtc			=	0;
				}else{
					if(strstr(strtolower($os), 'android')){
						$isAndroid		=	1;
						$isIos			=	0;
						$isWindows		=	0;
						$isLinux		=	0;
						$isRobot		=	0;
						$isEtc			=	0;
					}else if(strstr(strtolower($os), 'mac')){
						$isAndroid		=	0;
						$isIos			=	1;
						$isWindows		=	0;
						$isLinux		=	0;
						$isRobot		=	0;
						$isEtc			=	0;
					}else if(strstr(strtolower($os), 'windows')){
						$isAndroid		=	0;
						$isIos			=	0;
						$isWindows		=	1;
						$isLinux		=	0;
						$isRobot		=	0;
						$isEtc			=	0;
					}else if(strstr(strtolower($os), 'linux')){
						$isAndroid		=	0;
						$isIos			=	0;
						$isWindows		=	0;
						$isLinux		=	1;
						$isRobot		=	0;
						$isEtc			=	0;
					}else{
						$isAndroid		=	0;
						$isIos			=	0;
						$isWindows		=	0;
						$isLinux		=	0;
						$isRobot		=	0;
						$isEtc			=	1;
					}
				}
				
				

				$getHourDb	=	getValue("app_linkcore_db._TRAFFIC_HOUR", " where th_date = '".$hourTime."' and th_site = '".$siteCode."'  ", 'th_date', 'th_date');
				if(!$getHourDb){
					$hourSql	=	
						"
							insert into 
								app_linkcore_db._TRAFFIC_HOUR 
							set 
								th_date		=	'".$hourTime."'
								, th_web	=	".$isWeb."
								, th_mobile	=	".$isMobile."
								, th_app	=	".$isApp."
								, th_android=	".$isAndroid."
								, th_ios	=	".$isIos."
								, th_windows=	".$isWindows."
								, th_linux	=	".$isLinux."
								, th_robot	=	".$isRobot."
								, th_etc	=	".$isEtc."
								, th_cnt	=	1
								, th_site	=	'".$siteCode."'
						";
				}else{
					$hourSql	=	
						"
							update 
								app_linkcore_db._TRAFFIC_HOUR 
							set 
								th_web		=	th_web+".$isWeb."
								, th_mobile	=	th_mobile+".$isMobile."
								, th_app	=	th_app+".$isApp."
								, th_android=	th_android+".$isAndroid."
								, th_ios	=	th_ios+".$isIos."
								, th_windows=	th_windows+".$isWindows."
								, th_linux	=	th_linux+".$isLinux."
								, th_robot	=	th_robot+".$isRobot."
								, th_etc	=	th_etc+".$isEtc."
								, th_cnt	=	th_cnt+1
								
							where 
								th_date		=	'".$hourTime."' 
							and th_site = '".$siteCode."'
						";
				}
				query($hourSql);



				$getDayDb	=	getValue("app_linkcore_db._TRAFFIC_DAY", " where td_date = '".$dayTime."' and td_site = '".$siteCode."' ", 'td_date', 'td_date');
				if(!$getDayDb){
					$daySql	=	
						"
							insert into 
								app_linkcore_db._TRAFFIC_DAY 
							set 
								td_date		=	'".$dayTime."'
								, td_web	=	".$isWeb."
								, td_mobile	=	".$isMobile."
								, td_app	=	".$isApp."
								, td_android=	".$isAndroid."
								, td_ios	=	".$isIos."
								, td_windows=	".$isWindows."
								, td_linux	=	".$isLinux."
								, td_robot	=	".$isRobot."
								, td_etc	=	".$isEtc."
								, td_cnt	=	1
								, td_site	=	'".$siteCode."'
						";
				}else{
					$daySql	=	
						"
							update 
								app_linkcore_db._TRAFFIC_DAY 
							set 
								td_web		=	td_web+".$isWeb."
								, td_mobile	=	td_mobile+".$isMobile."
								, td_app	=	td_app+".$isApp."
								, td_android=	td_android+".$isAndroid."
								, td_ios	=	td_ios+".$isIos."
								, td_windows=	td_windows+".$isWindows."
								, td_linux	=	td_linux+".$isLinux."
								, td_robot	=	td_robot+".$isRobot."
								, td_etc	=	td_etc+".$isEtc."
								, td_cnt	=	td_cnt+1
								
							where 
								td_date		=	'".$dayTime."'
							and td_site = '".$siteCode."'
						";
				}
				query($daySql);
				

				$getMonthDb	=	getValue("app_linkcore_db._TRAFFIC_MONTH", " where tm_date = '".$monthTime."' and tm_site = '".$siteCode."' ", 'tm_date', 'tm_date');
				if(!$getMonthDb){
					$monthSql	=	
						"
							insert into 
								app_linkcore_db._TRAFFIC_MONTH 
							set 
								tm_date		=	'".$monthTime."'
								, tm_web	=	".$isWeb."
								, tm_mobile	=	".$isMobile."
								, tm_app	=	".$isApp."
								, tm_android=	".$isAndroid."
								, tm_ios	=	".$isIos."
								, tm_windows=	".$isWindows."
								, tm_linux	=	".$isLinux."
								, tm_robot	=	".$isRobot."
								, tm_etc	=	".$isEtc."
								, tm_cnt	=	1
								, tm_site	=	'".$siteCode."'
						";
				}else{
					$monthSql	=	
						"
							update 
								app_linkcore_db._TRAFFIC_MONTH 
							set 
								tm_web		=	tm_web+".$isWeb."
								, tm_mobile	=	tm_mobile+".$isMobile."
								, tm_app	=	tm_app+".$isApp."
								, tm_android=	tm_android+".$isAndroid."
								, tm_ios	=	tm_ios+".$isIos."
								, tm_windows=	tm_windows+".$isWindows."
								, tm_linux	=	tm_linux+".$isLinux."
								, tm_robot	=	tm_robot+".$isRobot."
								, tm_etc	=	tm_etc+".$isEtc."
								, tm_cnt	=	tm_cnt+1
								
							where 
								tm_date		=	'".$monthTime."'
							and tm_site = '".$siteCode."'
						";
				}
				query($monthSql);


				$getYearDb	=	getValue("app_linkcore_db._TRAFFIC_YEAR", " where ty_date = '".$yearTime."' and ty_site = '".$siteCode."' ", 'ty_date', 'ty_date');
				if(!$getYearDb){
					$yearSql	=	
						"
							insert into 
								app_linkcore_db._TRAFFIC_YEAR 
							set 
								ty_date		=	'".$yearTime."'
								, ty_web	=	".$isWeb."
								, ty_mobile	=	".$isMobile."
								, ty_app	=	".$isApp."
								, ty_android=	".$isAndroid."
								, ty_ios	=	".$isIos."
								, ty_windows=	".$isWindows."
								, ty_linux	=	".$isLinux."
								, ty_robot	=	".$isRobot."
								, ty_etc	=	".$isEtc."
								, ty_cnt	=	1
								, ty_site	=	'".$siteCode."'
						";
				}else{
					$yearSql	=	
						"
							update 
								app_linkcore_db._TRAFFIC_YEAR 
							set 
								ty_web		=	ty_web+".$isWeb."
								, ty_mobile	=	ty_mobile+".$isMobile."
								, ty_app	=	ty_app+".$isApp."
								, ty_android=	ty_android+".$isAndroid."
								, ty_ios	=	ty_ios+".$isIos."
								, ty_windows=	ty_windows+".$isWindows."
								, ty_linux	=	ty_linux+".$isLinux."
								, ty_robot	=	ty_robot+".$isRobot."
								, ty_etc	=	ty_etc+".$isEtc."
								, ty_cnt	=	ty_cnt+1
								
							where 
								ty_date		=	'".$yearTime."'
							and ty_site = '".$siteCode."'
						";
				}
				query($yearSql);
			}
		}

		//
		query("update app_linkcore_db._APP_LINK set at_move_is = 'Y' where at_idx = '".$KEY."' ");
	}
}
?>