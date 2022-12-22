<div class="jquery-accordion-menu Swiper">
    <ul class="swiper-wrapper">
		<?php
			require $_SERVER['DOCUMENT_ROOT'].'/lib/global.php';

			$_WHERE =   " WHERE 1 AND M.m_menu_state = 'Y' AND m_menu_depth = 1 ";
			$_TABLE =   "_MENU M ";
			$_TABLE .=  "INNER JOIN (
                    SELECT ma_m_idx FROM _MEMBER M LEFT JOIN _AUTH A ON A.a_authgroup_code = M.m_auth_level LEFT JOIN _MENU_AUTH H ON H.ma_a_idx = A.a_idx WHERE M.m_idx = '".$memberInfo['m_idx']."' AND H.ma_state='Y'
                 ) A ON M.idx = A.ma_m_idx";
			$_ORDER =   "ORDER BY m_menu_order ";

			$titlelist = selectBoard($_TABLE,$_WHERE,$_ORDER,'*',$no,false);
			$titlelistCnt = count($titlelist);

			for($i=0; $i<$titlelistCnt; $i++){
				$_Jnav = substr($titlelist[$i]['m_menu_url'], 0, strrpos($titlelist[$i]['m_menu_url'], "."));
				$_Jnav2 =  $titlelist[$i]['m_menu_file'];
				$_JnavForm =  $_Jnav.".form";
				$_JnavView =  $_Jnav.".view";

				//echo "aaa:".$PageName;
				//echo "BCODE:".$BCODE;
				//echo "MAINNAME:".$MAINNAME;
				$_MainID = $titlelist[$i]['m_menu_main_id'];

				if($titlelist[$j]['m_menu_file'] != ''){
					$_mainurl =  $titlesublist[$j]['m_menu_url'].'?MCODE='.$titlesublist[$j]['m_menu_id'].'&MNAME='.$titlesublist[$j]['m_menu_main_name'].'&BCODE='.$titlesublist[$j]['m_menu_file'];
				}else{
					$_mainurl =  $titlesublist[$j]['m_menu_url'].'?MCODE='.$titlesublist[$j]['m_menu_id'].'&MNAME='.$titlesublist[$j]['m_menu_main_name'].'&BCODE=';
				}

				$_WHERE2 =   " WHERE 1 AND M.m_menu_state = 'Y' AND m_menu_depth = 2 AND m_menu_main_id = '".$titlelist[$i]['m_menu_id']."'";
				$titlesublist = selectBoard($_TABLE,$_WHERE2,$_ORDER,'*',$no,false);
				$titlesublistCnt = count($titlesublist);

				?>

				<?php if($titlelist[$i]['m_menu_folder'] == 'Y'){ ?>
					<?// echo "MNAME:".$MNAME."/m_menu_main_name:".$titlelist[$i]['m_menu_main_name']?>
                    <li class="jmenu jmenu2 <?php if (($MNAME==$titlelist[$i]['m_menu_main_name'])) echo 'active'; ?> swiper-slide">
						<?//=$titlelist[$i]['m_menu_url']?>
                        <a href="<?=$titlelist[$i]['m_menu_url']?>?MCODE=<?=$titlelist[$i]['m_menu_id']?>&MNAME=<?=$titlelist[$i]['m_menu_main_name']?>&BCODE=<?=$titlelist[$i]['m_menu_file']?>" class="<?php if(($MNAME==$titlelist[$i]['m_menu_main_name'])) echo 'submenu-indicator-minus'; ?>" >
                            <i class="<?=$titlelist[$i]['m_menu_class']?>"></i><span><?=$titlelist[$i]['m_menu_name']?><span class="nav_arrow"></span></span>
                        </a>
                        <ul class="submenu <?php if(($MNAME==$titlelist[$i]['m_menu_main_name'])) echo 'active'; ?>">
							<?php
								for($j=0; $j<$titlesublistCnt; $j++){
									$_JnavSub = substr($titlesublist[$j]['m_menu_url'], 0, strrpos($titlesublist[$j]['m_menu_url'], "."));
									$_JnavSub2 =  $titlesublist[$j]['m_menu_file'];
									$_JnavSubForm =  $_JnavSub.".form";
									$_JnavSubView =  $_JnavSub.".view";
									if($titlesublist[$j]['m_menu_file'] != ''){
										$_suburl =  $titlesublist[$j]['m_menu_url'].'?MCODE='.$titlesublist[$j]['m_menu_id'].'&MNAME='.$titlesublist[$j]['m_menu_main_name'].'&BCODE='.$titlesublist[$j]['m_menu_file'];
									}else{
										$_suburl =  $titlesublist[$j]['m_menu_url'].'?MCODE='.$titlesublist[$j]['m_menu_id'].'&MNAME='.$titlesublist[$j]['m_menu_main_name'].'&BCODE=';
									}

									?>
                                    <li class="jmenu <?php if($titlesublist[$j]['m_menu_file']!="") { if ((($PageName==$_JnavSub)&&($BCODE==$_JnavSub2)) || (($PageName==$_JnavSubForm)&&($BCODE==$_JnavSub2)) || (($PageName==$_JnavSubView)&&($BCODE==$_JnavSub2))) echo 'active'; ?>
                            <?php } else { if (($PageName==$_JnavSub) || ($PageName==$_JnavSubForm) || ($PageName==$_JnavSubView)) echo 'active'; ?>
                            <?php }?>
            ">
										<?// echo $_suburl ?>
                                        <a href="<?=$_suburl?>">
											<?=$titlesublist[$j]['m_menu_name']?>
                                        </a>
                                        <!--                                <ul class="subsubmenu">-->
                                        <!--                                    <li>-->
                                        <!--                                        <a href=""></a>-->
                                        <!--                                    </li>-->
                                        <!--                                </ul>-->
                                    </li>

								<?php } ?>
                        </ul>
                    </li>
				<?php }else{ ?>
                    <li class="swiper-slide jmenu <?php if($BCODE!="") { if ((($PageName==$_Jnav)&&($BCODE==$_Jnav2)) || (($PageName==$_JnavForm)&&($BCODE==$_Jnav2)) || (($PageName==$_JnavView)&&($BCODE==$_Jnav2))) echo 'active'; ?>
                 <?php } else { if (($PageName==$_Jnav) || ($PageName==$_JnavForm) || ($PageName==$_JnavView)) echo 'active'; ?>
                 <?php }?>
    ">
                        <a href="<?=$titlelist[$i]['m_menu_url']?>?MCODE=<?=$titlelist[$i]['m_menu_id']?>&MNAME=<?=$titlelist[$i]['m_menu_main_name']?>&BCODE=<?=$titlelist[$i]['m_menu_file']?>">
                            <i class="<?=$titlelist[$i]['m_menu_class']?>"></i><?=$titlelist[$i]['m_menu_name']?>
                        </a>
                    </li>
				<?php } ?>
			<?php } ?>
        <!--		<li class="jmenu  ">-->
        <!--			<a href="">1차</a>-->
        <!--			<ul class="submenu ">-->
        <!--				<li class="jmenu active">-->
        <!--					<a href="">2차</a>-->
        <!--					<ul class="submenu2">-->
        <!--						<li class="kmenu">-->
        <!--							<a href="">3차_1</a>-->
        <!--						</li>-->
        <!--						<li class="kmenu ">-->
        <!--							<a href="">3차_2</a>-->
        <!--						</li>-->
        <!--					</ul>-->
        <!--				</li>-->
        <!--				<li class="jmenu ">-->
        <!--					<a href="">2차_2</a>-->
        <!--				</li>-->
        <!--				<li class="jmenu">-->
        <!--					<a href="">2차_3</a>-->
        <!--					<ul class="submenu2">-->
        <!--						<li class="kmenu">-->
        <!--							<a href="">3차_1</a>-->
        <!--						</li>-->
        <!--						<li class="kmenu ">-->
        <!--							<a href="">3차_2</a>-->
        <!--						</li>-->
        <!--					</ul>-->
        <!--				</li>-->
        <!--			</ul>-->
        <!--		</li>-->
    </ul>
</div>




