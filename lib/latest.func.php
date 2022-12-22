<?php
/*#################################################################################################
#########	생성일 : 2012-12-26
#########	만든이 : 김종수 부장
#########	연락처 : 010-6368-2650
#########	이메일 : pusankjs@naver.com
#########	파일명 : latest.func.php / 최신글 함수
#################################################################################################*/
if(!defined("_INCLUDED_LATEST")){
	define("_INCLUDED_LATEST", "1");	
	function getMnetMusicLatestList($_MAIN_CATE, $_SUB_CATE, $_GROUP_CATE, $_PAGE_ROW='10', $_SKIN='get.one.photo.10.html', $_OPTION='', $_PAGE_GROUP='1', $_RANK_GROUP='1-50'){
		global $PSKIN, $CATE_GP;
		
		require_once $_SERVER['DOCUMENT_ROOT'].'/lib/global.php';
		$setMainCate	=	getCateIdxToName($MAIN_CATE);
		$setSubCate		=	getCateIdxToName($_SUB_CATE);
		$setGroupCate	=	$CATE_GP;//$_GROUP_CATE;
		$setIDX			=	$_GET['getIDX'];
		$setpageGroup	=	$_PAGE_GROUP;
		$setPageRow		=	$_PAGE_ROW;
		$setRankGroup	=	$_RANK_GROUP;

		$idxSql		=	'';
		if($getIDX	!=	'')
			$idxSql	=	" and gml_idx = '".$setIDX."' ";

		$pageSql	=	'';
		if($setpageGroup	!=	'' && $setPageRow != '')
			$pageSql	=	" limit ".(($setpageGroup-1)*$setPageRow).", ".$setPageRow;

		$rankGroupSql	=	'';
		if($setRankGroup	!=	'')
			$rankGroupSql	=	" and gml_rank_group = '".$setRankGroup."' ";

		$groupCateSql	=	'';
		if($setGroupCate	!=	'')
			$groupCateSql	=	" and gml_cate_group = '".$setGroupCate."' ";

		$cateSql	=	'';
		if($setMainCate	!=	'')
			$cateSql	=	" and gml_cate = '".str_replace($cateWonAr, $cateReAr, $setMainCate)."' ";


		$subCateSql	=	'';
		if($setSubCate	!=	'')
			$subCateSql	=	" and gml_cate_sub = '".$setSubCate."' ";


		$setDate		=	getValue('_GET_MNET_LIST', " where 1 ".$groupCateSql.$cateSql.$subCateSql.$idxSql.$rankGroupSql." order by gml_get_date_s desc limit 1", 'gml_get_date_s', 'gml_get_date_s');

		$whereSql	=	$groupCateSql.$cateSql.$subCateSql." and gml_get_date_s like  '".$setDate."%' ".$idxSql.$rankGroupSql." group by gml_rank order by gml_recommend_is asc, gml_rank asc ";

		$_OPTIONEx	=	explode('|', $_OPTION);
		if($_OPTIONEx[1]	==	'rand')
			$whereSql	=	" order by rand() ";
		
		$sql	=	"select * from _GET_MNET_LIST  where 1 ".$whereSql."  ".$pageSql;
		//exit( $sql );
		$rs		=	query($sql);
		if(rows() > 0){
			for($i=0; $row = assoc($rs); $i++){
				$MUSICNAMESEARCH	=	'';
				if($row['gml_cate']	==	'아티스트 차트'){
					$artistEx	=	explode('(', $row['gml_artist']);
					$MUSICNAMESEARCH	=	getValue('_GET_MNET_LIST', "where gml_music_name <> '' and gml_artist like '%".$artistEx[0]."%' order by gml_get_date_s desc, gml_rank asc limit 1", 'gml_music_name', 'gml_music_name');
				}

				$row['sumimg']		=	_SITE_URL.'/'.$row['gml_img_dir'].'/50/'.$row['gml_img'];
				//$row['mainimg']		=	_SITE_URL.'/'.$row['gml_img_dir'].'/240/'.$row['gml_img'];
				$row['mainimg']		=	_SITE_URL.'/'.$row['gml_img_dir'].'/240/'.$row['gml_img'];
				$row['musicname']	=	$row['gml_music_name'] == '' ? $MUSICNAMESEARCH : $row['gml_music_name'];
				$list[]				=	$row;
			}
			//echoAr($list);
		}

		//echo count($list);
		//exit(($_OPTIONEx[0] == 'onlylayer' ? 'onlylayer' : 'layer'));
		//echo 'latest :: '. $_SKIN;
		$_OPTION	=	$_OPTIONEx[2]	!=	'' ? $_OPTIONEx[2] : $_OPTION;
		pSkinSet('latest', $_SKIN, ($_OPTIONEx[0] == 'onlylayer' ? 'onlylayer' : 'layer'));
		if($_OPTIONEx[0] == 'onlylayer'){
			//include($_SKIN);
			//echo pSkinDir('latest').'/'.$_SKIN;
			include(pSkinDir('latest').'/'.$_SKIN);
		}else{
			include($PSKIN['view_layout']);
		}
		//echo $_SKIN;
		//echo$PSKIN['view_layout'];
		//include($PSKIN['view_layout']);
	}

	//정재욱 사원 작업중
	function getBoardLatestList($_B_ID, $_PAGE_ROW='10', $_SKIN='get.6.list.html', $_OPTION=''){
		global $PSKIN, $CATE_GP, $getSiteSkin;
		
		require_once $_SERVER['DOCUMENT_ROOT'].'/lib/global.php';

		$idSql	=	'';
		if($_B_ID	!=	'')
			$idSql	=	" and b_id = '".$_B_ID."' ";

		$cateSql	=	" and b_cate like '%|".$getSiteSkin['di_idx']."|%' ";

		$whereSql	=	$idSql.$cateSql." order by regdate desc limit 1, ".$_PAGE_ROW;

		$_OPTIONEx	=	explode('|', $_OPTION);
		if($_OPTIONEx[1]	==	'rand')
			$whereSql	=	" order by rand() ";
		
		$sql	=	"select * from _BOARD where 1 ".$whereSql;
		$rs		=	query($sql);
		if(rows() > 0){
			for($i=0; $row = assoc($rs); $i++){
				$row['b_title']	=	$row['b_title'];
				$list[]	=	$row;
			}
			//print_r($list);
		}

		$_OPTION	=	$_OPTIONEx[2]	!=	'' ? $_OPTIONEx[2] : $_OPTION;
		pSkinSet('latest', $_SKIN, ($_OPTIONEx[0] == 'onlylayer' ? 'onlylayer' : 'layer'));
		if($_OPTIONEx[0] == 'onlylayer'){
			include(pSkinDir('latest').'/'.$_SKIN);
		}else{
			include($PSKIN['view_layout']);
		}
	}

	function getSDLatest($CATE, $SUB_CATE, $PAGE_S_ROW='1', $_PAGE_ROW='10', $_SKIN='get.6.list.html', $TXT_LEN=40, $_OPTION=''){
		global $_SERVER;
		require_once $_SERVER['DOCUMENT_ROOT'].'/lib/global.php';
		$view			=	'onlyar';
		$arDeclareIsNo	=	true;
		if($_OPTION	==	'rand'){
			$PAGE_S_ROW_	=	rand(1,5);
			$QUERY_SQL_		=	'order by rand()';
		}else{
			$PAGE_S_ROW_	=	$PAGE_S_ROW;
			$QUERY_SQL_		=	'';
		}

		$ck	=	getValue('_ADD_DATA', "where ad_cate = '".$CATE."' ", 'cn', 'ad_idx');
		
		if($ck > 0){
			$sql	=	"
				select 
					ad_images as  Imgurl
					, ad_title as Bbstitle
					, ad_size as Size
					, ad_contents as Contents
					, ad_subcate as SubCategory
				from 
					_ADD_DATA  ".$QUERY_SQL_." limit 0, ".$_PAGE_ROW;
			$rs		=	query($sql);
			for($i=0; $row = assoc($rs); $i++){
				$list[]	=	$row;
			}
		}else{
			require $_SERVER['DOCUMENT_ROOT'].'/getContentsList/get.contents.php';
			//echoAr($earlyAr[0]['List']['Contents']);
			$list	=	$earlyAr[0]['List']['Contents'];
			if($_OPTION	==	'rand'){
				shuffle($list);
			}
		}
		//print_r($list);

		//echo $list[0]['Imgurl'];
		//echoAr($list[0]);
		//echo($list[0]['Imgurl']);
		/*
			[Imgurl] => 이미지 주소
			[Isrights] => 제휴구분 (사용안함)
			[Bbstitle] => 게시물제목
			[Category] => 게시물 카테고리
			[SubCategory] => 2차 카페고리
			[Seller] => 판매자아이디 (사용안함)
			[Point] => 구매포인트 (사용안함)
			[Links] => 상세페이지 주소( 슈퍼다운 주소 - 모바일만 되며 우리가 사용할일은 없음/앱은 사용함)
			[Bbsinfo] => 해당게시물에 등록된 상세 이미지들(보통 한 5시 정도 됨)
			[Size] => 파일크기 Byte임
		*/
		//echo $_SKIN;
		include(pSkinDir('latest').'/'.$_SKIN);

	}

	function getSDBestLatest($CATE, $SUB_CATE, $PAGE_S_ROW='1', $_PAGE_ROW='10', $_SKIN='get.6.list.html', $TXT_LEN=40, $_OPTION=''){
		global $_SERVER;
		require_once $_SERVER['DOCUMENT_ROOT'].'/lib/global.php';
		$view			=	'onlyar';
		$arDeclareIsNo	=	true;
		if($_OPTION	==	'rand'){
			$PAGE_S_ROW_	=	rand(1,5);
		}else{
			$PAGE_S_ROW_	=	$PAGE_S_ROW;
		}

		require $_SERVER['DOCUMENT_ROOT'].'/getContentsList/get.contents.php';
		//echoAr($earlyAr[0]['List']['Contents']);
		$list	=	$earlyAr[0]['List']['Contents'];
		if($_OPTION	==	'rand'){
			shuffle($list);
		}
		//echoAr($list[0]);
		//echo($list[0]['Imgurl']);
		/*
			[Imgurl] => 이미지 주소
			[Isrights] => 제휴구분 (사용안함)
			[Bbstitle] => 게시물제목
			[Category] => 게시물 카테고리
			[SubCategory] => 2차 카페고리
			[Seller] => 판매자아이디 (사용안함)
			[Point] => 구매포인트 (사용안함)
			[Links] => 상세페이지 주소( 슈퍼다운 주소 - 모바일만 되며 우리가 사용할일은 없음/앱은 사용함)
			[Bbsinfo] => 해당게시물에 등록된 상세 이미지들(보통 한 5시 정도 됨)
			[Size] => 파일크기 Byte임
		*/
		//echo $_SKIN;
		include(pSkinDir('latest').'/'.$_SKIN);
	}
}
?>