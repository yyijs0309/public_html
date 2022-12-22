<?php
require $_SERVER['DOCUMENT_ROOT'].'/lib/global.php';
auth_limit($thisConnAuth != '' ?  $thisConnAuth : 'root');

//print_r($_GET);
//$_WHERE	=	" WHERE 1 ";

$type=$_GET["type"];
if ($type=="" || $type=="member")
{
    $_WHERE	=	" WHERE 1 ";
    $_WHERE	.=	" and m_idx <> 1 ";         // 최종admin 관리자(1)는 노출되지 않도록 처리
    $_WHERE	.=	" and m_level = 'user' ";
//if($_REQUEST['word']	!=	''){
    if($_GET['keyword']	!=	'' && $_GET['word']	!=	''){
        $_WHERE	.=	" AND ".$_GET['keyword']." LIKE '%".$_GET['word']."%' ";
        $_TAGS	.=	'&keyword='.$_GET['keyword'];
        $_TAGS	.=	'&word='.$_GET['word'];
    }

    if($_GET['cate'] == ''){
        $cate = "all";
    }

//신규가입자
    if($_GET['cate'] == 'todayjoin'){
        $_WHERE	.=	" AND DATE(m_regdate) = DATE(NOW()) ";
    }
//오늘유료결제회원
    if($_GET['cate'] == 'todaypay'){
        $_WHERE	.=	" AND DATE(m_pay_datetime) = DATE(NOW()) ";
    }
//종료2일전
    if($_GET['cate'] == '2daybefore'){
        $_WHERE	.=	" AND DATE(m_expire_datetime) = DATE(NOW()) + 2 ";
    }
//종료1일전
    if($_GET['cate'] == '1daybefore'){
        $_WHERE	.=	" AND DATE(m_expire_datetime) = DATE(NOW()) + 1 ";
    }
//오늘종료
    if($_GET['cate'] == 'todaybefore'){
        $_WHERE	.=	" AND DATE(m_expire_datetime) = DATE(NOW()) ";
    }
//접속자
    if($_GET['cate'] == 'access'){
        $_TABLE_JOIN = " LEFT OUTER JOIN _MEMBER_DEVICE MD ON M.M_IDX = MD.M_IDX ";
        $_GROUPBY = " GROUP BY M.m_idx";
        $_WHERE	.=	" AND MD.m_state = 'Y' ";
    }
//유료회원
    if($_GET['cate'] == 'paymember'){
        $_WHERE	.=	" AND DATEDIFF(m_expire_datetime, NOW()) >= 0 ";
    }
//기간만료회원
    if($_GET['cate'] == 'expiremember'){
        $_WHERE	.=	" AND DATEDIFF(m_expire_datetime, NOW()) < 0 ";
    }
//블랙리스트
    if($_GET['cate'] == 'blacklist'){
        $_WHERE	.=	" AND m_option6 = 'Y' ";
    }
    $_SELECT		=	'*';
    $_TABLE	=	"_MEMBER";
    $_ORDER	=	" ORDER BY m_regdate DESC ";
}

$onePageCount	=	20;
$pagenum		=	$_REQUEST['pagenum'] ? $_REQUEST['pagenum'] : 1;
$offset			=	($pagenum-1) * $onePageCount;
//$total			=	getValue($_TABLE1, $_WHERE, 'CNT', 'COUNT(*) AS CNT');
$total			=	getValue($_TABLE, $_WHERE, 'CNT', 'COUNT(*) AS CNT');
$no				=	$total	 -	($pagenum-1) * $onePageCount;

//$useCnt			=	getValue('_IP_LIST', " where ip_ing_tr_pc = 'N' ", 'cnt', 'count(*) as cnt');
$sql	= "select ".$_SELECT." FROM ".$_TABLE." ".$_WHERE." ".$_ORDER." limit ".$offset.", ".$onePageCount;
//	echo $sql;
//	echo $sql;exit;
$rs		=	query($sql);
if(rows() > 0)
{
    for($i=0; $row = assoc($rs); $i++)
    {
        $row['num'] = $no--;
        $list[$i] = $row;
    }
}

//if($total > 0)
//    $toalPageCn	=	ceil($total/$onePageCount);
//else
//    $toalPageCn	=	1;
//$paging		=	disPgCount($toalPageCn, $pagenum, $_TAGS, 1);

//print_r($list);
header("Content-type: application/vnd.ms-excel");
header("Content-type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=".$type."_".date("YmdHi").".xls");
header("Content-Description: PHP4 Generated Data");

if ($type=="member")
{
    //테이블 상단 만들기
    $EXCEL_STR = "
			<table morder='1'>
				<tr>
					<td>번호</td>
					<td>사용자ID</td>
					<td>비밀번호</td>
					<td>메신저 아이디</td>
					<td>사용기기수</td>
					<td>유료서비스 종료일</td>
					<td>계정상태</td>
				</tr>
		";
    for($i=0;count($list)>$i;$i++)
    {
        $codename = codename(substr($list[$i]['m_option1'],0,4),substr($list[$i]['m_option1'],4,4),'C');

        $EXCEL_STR .= "
				<tr>
					<td>".$list[$i]["num"]."</td>
					<td>".$list[$i]["m_id"]."</td>
					<td>".$list[$i]["m_pass"]."</td>
					<td>".$codename[0]['code_name'].$list[$i]['m_option2']."</td>
					<td>".$list[$i]["m_option3"]."</td>
					                    
	    ";

        if($list[$i]['m_expire_datetime'] == '0000-00-00 00:00:00'){
            $EXCEL_STR .= "<td>0000-00-00</td>";
        }else{
            $EXCEL_STR .= "<td>".setOutput(date('Y-m-d',strtotime($list[$i]['m_expire_datetime'])))."</td>";
        }

        if($list[$i]['m_use'] == 'Y'){
            $EXCEL_STR .= "<td>사용중</td>";
        }else if($list[$i]['m_use'] == 'T'){
           $EXCEL_STR .= "<td>사용정지</td>";
        }else{
            $EXCEL_STR .= "<td>미사용자</td>";
        }
        $EXCEL_STR .= "</tr>";
    }
}

$EXCEL_STR .= "</table>";
echo "<meta content=\"application/vnd.ms-excel; charset=UTF-8\" name=\"Content-type\"> ";
echo $EXCEL_STR;
?>
