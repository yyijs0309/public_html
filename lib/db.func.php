<?php
/*#################################################################################################
#########	생성일 : 2012-12-13
#########	만든이 : 김종수 부장
#########	연락처 : 010-6368-2650
#########	이메일 : pusankjs@naver.com
#########	파일명 : db.func.php / DATABASE 관련 함수
#################################################################################################*/
if(!defined("_INCLUDED_DB_FUNC")){
	define("_INCLUDED_DB_FUNC", "1");

    function selectBoard($TB, $where=' where 1 ',$orderby='',$f='*',$no=0,$debug=false, $debugExit=true){
        if($where == '') return;
        $sql	=	"SELECT ".$f." FROM ".$TB." ".$where.$orderby." ";
        if($debug == true){
            if($debugExit)
                exit( specialchars_replace($sql) );
            else
                return  $sql;
        }
//        echo "sql:".$sql; exit;
        $rs		=	query($sql);
        $list	=	Array();
        while(@$row = assoc($rs)){
            $row['num'] = $no--;
            $list[] = $row;
        }
        return $list;
    }

	function getValue($TB, $where=' where 1 ', $callType='ar', $f='*', $debug=false, $debugExit=true){

		if($where == '') return;
		$sql	=	"SELECT ".$f." FROM ".$TB." ".$where." ";
		if($debug == true){
			if($debugExit)
				exit( specialchars_replace($sql) );
			else 
				return  $sql;
		}
		$rs		=	query($sql);
		if(strtoupper($callType)	==	'CN'){
			@$cn	=	rows();
			$result	=	$cn;
		}elseif(strtoupper($callType)	==	'AR'){
			@$row	=	assoc($rs);
			$result	=	$row;
		}else{
			@$row	=	assoc($rs);
			$result	=	$row[$callType];
		}

		return $result;
	}

	function getValueRemote($DINFO, $TB, $where=' where 1 ', $callType='ar', $f='*', $debug=false, $debugExit=true){

		if($where == '') return;
		$sql	=	"SELECT ".$f." FROM ".$TB." ".$where." ";
		if($debug == true){
			if($debugExit)
				exit( specialchars_replace($sql) );
			else 
				return  $sql;
		}
		$rs		=	queryRemote($DINFO, $sql);
		if(strtoupper($callType)	==	'CN'){
			@$cn	=	rowsRemote($DINFO);
			$result	=	$cn;
		}elseif(strtoupper($callType)	==	'AR'){
			@$row	=	assoc($rs);
			$result	=	$row;
		}else{
			@$row	=	assoc($rs);
			$result	=	$row[$callType];
		}

		return $result;
	}

	/*
	function getValue($TB, $where=' where 1 ', $callType='ar', $f='*', $debug=false){

		if($where == '') return;
		$sql	=	"SELECT ".$f." FROM ".$TB." ".$where." ";
		if($debug == true) exit( specialchars_replace($sql) );
		
		$rs		=	query($sql);
		if(strtoupper($callType)	==	'CN'){
			@$cn	=	rows();
			$result	=	$cn;
		}elseif(strtoupper($callType)	==	'AR'){
			@$row	=	assoc($rs);
			$result	=	$row;
		}else{
			@$row	=	assoc($rs);
			$result	=	$row[$callType];
		}

		return $result;
	}
	*/

	function query($sql){
		global $DBINFO;
		$result = @mysqli_query($DBINFO, $sql);

		if(!$result) getDBError();
		return $result;
	}

	function queryRemote($DINFO, $sql){
		$result = @mysqli_query($DINFO, $sql);

		if(!$result) getDBError();
		return $result;
	}

	function getRow($sql){
		$rs	=	query($sql);
		return (rows()>0 ? assoc($rs) : '');
	}
	
	function assoc(&$_result){
		return mysqli_fetch_assoc($_result);
	}


	function getResultArray($sql){
		$rs		=	query($sql);
		$list	=	Array();

		while(@$row = assoc($rs)){
			$list[]	=	$row;
		}
		return $list;
	}

	function insert_id(){
		global $DBINFO;
		return mysqli_insert_id($DBINFO);
	}

	function rows(){
		global $DBINFO;
		$row = @mysqli_affected_rows($DBINFO);
		return $row;
	}

	function rowsRemote($DINFO){
		$row = @mysqli_affected_rows($DINFO);
		return $row;
	}

	function getDBError($DESCRIPT=''){
		global $DBINFO;
		if(mysqli_error($DBINFO)){
			if(_DB_ERROR_	==	true){
				$error_msg = mysqli_error($DBINFO).'['.mysqli_errno($DBINFO).']';
				if(_CHARSET_	==	'UTF-8') $error_msg = iconv('EUC-KR', 'UTF-8', $error_msg);
				die( ($DESCRIPT?'('.$DESCRIPT.')':'').$error_msg."<br/>\r\n");
			}
		}
	}

	function tquery($sql){
		global $DBINFO;
		$result = @mysqli_query($DBINFO, $sql);

		if(!$result){
			mysqlrollback();
			getDBError();
			autocommit(true);
		}
		return $result;
	}
	function autocommit($swich){
		global $DBINFO;
		$result = mysqli_autocommit($DBINFO,$swich);
		return $result;
	}
	function mysqlcommit(){
		global $DBINFO;
		mysqli_commit($DBINFO);
	}
	function mysqlrollback(){
		global $DBINFO;
		mysqli_rollback($DBINFO);
	}
}
?>