<?php
function getpage($count, $pagesize = 10) {
	$p = new Think\Page($count, $pagesize);
	$p->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
	$p->setConfig('prev', '上一页');
	$p->setConfig('next', '下一页');
	$p->setConfig('last', '末页');
	$p->setConfig('first', '首页');
	$p->setConfig('theme', '%FIRST%%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%%END%%HEADER%');
	$p->lastSuffix = false;//最后一页不显示为总页数
	return $p;
}

function cate($var){
		$user = M('user');
		$ztname=$user->where(array('UE_accName'=>$var,'UE_check'=>'1','UE_stop'=>'1'))->getField('ue_account',true);
		$zttj = count($ztname);
		$reg=$ztname;
		$datazs = $zttj;
		if($zttj<=10){
			$s=$zttj;
		}else{
			$s=10;
		}
		if($zttj!=0){

		  for($i=1;$i<$s;$i++){
				if($reg!=''){
					$reg=$user->where(array('UE_accName'=>array('IN',$reg),'UE_check'=>'1','UE_stop'=>'1'))->getField('ue_account',true);
					$datazs +=count($reg);
				}
			}
			
		}
		
	//	$this->ajaxReturn();
		
	return $datazs;
	
	
	
	
}


//下级总人数
 function xiajirenshu($name,&$arr){
	 	$array = M('user')->where(['UE_accName'=>$name])->select();
	 	$num = count($array);
	 	$arr += $num;
	 	foreach($array as $key=>$value){
	 		$name = $value['ue_account'];
	 		if($name){
	 			xiajirenshu($name,$arr); 
	 		}
	 	}
 	
 }
 //团队业绩
 function xiajiyeji($name,&$arr){
	 	$array = M('user')->where(['UE_accName'=>$name])->select();
	 	foreach($array as $key=>$value){
	 		$name = $value['ue_account'];
	 		$num = M('ppdd')->where(['p_user'=>$name,'zt'=>2])->sum('jb');
	 		$arr += $num;
	 		if($name){
	 			xiajirenshu($name,$arr); 
	 		}
	 	}
 	
 }
//自动匹配逻辑
function auto_match($where = array(), $timelimit = array(), $str = '', $equal = true, &$num = 0) {
//判断where是否为空 如果为空并且是post请求获取开始时间和结束时间
    if (empty($where)) {
        if (IS_POST) {
            $s = I('post.start');
            $e = I('post.end');
            $start = $s ? $s : 0;
            $end = $e ? $e : time();

            if (empty($s) && empty($e)) {
                $where = array();
            } else {
                $where['UNIX_TIMESTAMP(date)'] = array('between', array(strtotime($start), strtotime($end)));
            }
        }
    }
//zt???
    $where['zt'] = 0;

    if (empty($timelimit)) {
        $res = M('match')->find();
        if (!$res['math_switch']) {
            return $num;
        }
        if ($res && $res['math_switch']) {

            if (!empty($res['supply_timelimit'])) {
                $timelimit['s'] = 'UNIX_TIMESTAMP(date)+' . (3600 * $res['supply_timelimit']);
            }
            if (!empty($res['accept_timelimit'])) {
                $timelimit['a'] = 'UNIX_TIMESTAMP(date)+' . (3600 * $res['accept_timelimit']);
            }
        }
    }

    $tgs = !empty($timelimit['s']) ? " UNIX_TIMESTAMP(now()) > {$timelimit['s']}" : array();

    $jsa = !empty($timelimit['a']) ? " UNIX_TIMESTAMP(now()) > {$timelimit['a']}" : array();
    //判断
    if (!empty($str)) {
        $tgid['id'] = array('not in', trim($str, ','));
    } else {
        $tgid = array();
    }
    $tgbz = M('tgbz')->where($where)->where($tgid)->where($tgs)->order('UNIX_TIMESTAMP(date)')->find();

    if (empty($tgbz)) {
        return $num;
    } else {

        $jsuser['user'] = array('neq', $tgbz['user']);
        $jsbz = M('jsbz')->where($where)->where($jsuser)->where($jsa)->order('UNIX_TIMESTAMP(date)')->find();

        if (empty($jsbz)) {
            $str .= $tgbz['id'] . ',';
            auto_match($where, $timelimit, $str, false, $num);
        } else {
            if ($tgbz['jb'] > $jsbz['jb']) {

                $data = $tgbz;
                $data['jb'] = $jsbz['jb'];
                unset($data['id']);
                $id = M('tgbz')->add($data);

                //未匹配
                $data2 = $data;
                $data2['jb'] = $tgbz['jb'] - $jsbz['jb'];
                $id2 = M('tgbz')->add($data2);

                M('tgbz')->where(array('id' => $tgbz['id']))->delete();
                M('user_jj')->where(array('tgbz_id' => $tgbz['id']))->delete();

                if (ppdd_add($id, $jsbz['id'])) {

                    ++$num;
                    M('tgbz')->where(array('id' => $id))->save(array('cf_ds' => '1'));
                }
            } elseif ($tgbz['jb'] == $jsbz['jb']) {
                if (ppdd_add($tgbz['id'], $jsbz['id'])) {

                    ++$num;
                }
            } elseif ($tgbz['jb'] < $jsbz['jb']) {
                //新匹配jsbz
                $data2 = $jsbz;
                $data2['jb'] = $tgbz['jb'];
                unset($data2['id']);
                $id2 = M('jsbz')->add($data2);

                //新未匹配jsbz
                $data3 = $data2;
                $data3['jb'] = $jsbz['jb'] - $tgbz['jb'];
                $id3 = M('jsbz')->add($data3);

                //删除旧订单
                M('jsbz')->where(array('id' => $jsbz['id']))->delete();

                if (ppdd_add($tgbz['id'], $id2)) {

                    ++$num;
                    M('tgbz')->where(array('id' => $tgbz['id']))->save(array('cf_ds' => '1'));
                }
            }

            auto_match($where, $timelimit, $str, false, $num);
        }
        return $num;
    }
}

	function auto_match_r($where=array()){
		
		$settings = include( $_SERVER['DOCUMENT_ROOT'] . '/User/Home/Conf/settings.php' );
		
		/* if(!$settings['auto_match']){
			return;
		} */
		$where = $where;
		$where['zt'] = 0;
		
		$tgbz_list = M('tgbz')->where ($where)->select();
		if(empty($tgbz_list)) return;
		$jsbz_list = M('jsbz')->where ($where)->select();
		if(empty($tgbz_list)) return;
		
		$pipeit = 0;
    	foreach($tgbz_list as $key=>$val){
			foreach($jsbz_list as $key1=>$val1){
				
				//if条件为金币数量相等，切用户名不同.z
				if($val['jb']==$val1['jb']&&$val['user']<>$val1['user']){//如果匹配成功处理
					
					if(ppdd_add($val['id'],$val1['id'])){
						
						unset($tgbz_list[$key],$jsbz_list[$key1]);
						++$pipeit;
						M('tgbz')->where(array('id'=>$val['id']))->save(array('cf_ds'=>'1'));
						break;
					}
				}
				
			}

    	}
		
		$pipeitss = $pipeits = $pipeit;
		
		reset($tgbz_list);
		print(' ');
		while(list($key,$val) = each($tgbz_list)){
			echo '11';
			ob_flush();
			flush();
		//foreach($tgbz_list as $key=>$val){
			if(empty($jsbz_list) || empty($tgbz_list)) return;
			
			$sum = 0;
			$arr = array();
			
			foreach($jsbz_list as $key1=>$val1){
				
				if($val['user']<>$val1['user']){
					$sum += $val1['jb'];
					$arr[$key1] = $val1;
					if($val['jb']<=$sum) break;
				}
			}
			
			//接受帮助金额不小于提供帮助金额
			if($val['jb']<=$sum){
				
				foreach($arr as $k=>$v){
					
					$val['jb'] = $val['jb']-$v['jb'];
					
					if($val['jb']>=0){

						$id = $val['id'];
						
						if($val['jb']>0){
							//已匹配
							$data = $val;
							$data['jb'] = $v['jb'];
							$data['user_nc'] = '一';
							unset($data['id']);
							$id = M('tgbz')->add($data);
							
							//未匹配
							$data2 = $val;
							$data2['jb'] = $val['jb'];
							$data2['user_nc'] = '七';
							unset($data2['id']);
							$id2 = M('tgbz')->add($data2);
							
							M('tgbz')->where(array('id'=>$val['id']))->delete();
						}
						
							unset($jsbz_list[$k],$tgbz_list[$key]);
							array_push($tgbz_list,$data2);
							$tgbz_list = array_values($tgbz_list);
						
						if(ppdd_add($id,$v['id'],'91')){
							
							++$pipeits;
							M('tgbz')->where(array('id'=>$id))->save(array('cf_ds'=>'1'));
							
						}
					}else{
						
						/* //插入新tgbz
						$data = $val;
						$data['jb'] = $v['jb']-abs($val['jb']);
						$data['user_nc'] = '二';
						unset($data['id']);
						$id = M('tgbz')->add($data); */
						
						//新未匹配jsbz
						$data2 = $v;
						$data2['jb'] = abs($val['jb']);
						$data['user_nc'] = '三';
						unset($data2['id']);
						$id2 = M('jsbz')->add($data2);
						
						//新匹配jsbz
						$data3 = $v;
						$data3['jb'] = $v['jb']-abs($val['jb']);
						$data['user_nc'] = '四';
						unset($data3['id']);
						$id3 = M('jsbz')->add($data3);
						
						$data2['id'] = $id2;
						
						
						//删除旧订单
						M('jsbz')->where(array('id'=>$v['id']))->delete();
						//M('tgbz')->where(array('id'=>$val['id']))->delete();
						
						if(ppdd_add(current($tgbz_list)['id'],$id3,'92')){
							
							unset($jsbz_list[$k],$tgbz_list[$key]);
							
							array_unshift($jsbz_list,$data2);
							++$pipeitss;
							M('tgbz')->where(array('id'=>$id))->save(array('cf_ds'=>'1'));
							break;
						}
					}	
				}
				echo '22';
			ob_flush();
			flush();
			}else{
				echo '33';
			ob_flush();
			flush();
				foreach($arr as $k=>$v){
					
					$val['jb'] = $val['jb']-$v['jb'];
					
					$data = $val;
					$data['jb'] = $v['jb'];
					$data['user_nc'] = '五';
					unset($data['id']);
					
					$id = M('tgbz')->add($data);
					
					if(ppdd_add($id,$v['id'],'93')){
						
						unset($jsbz_list[$k],$tgbz_list[$key]);
						++$pipeits;
						M('tgbz')->where(array('id'=>$id))->save(array('cf_ds'=>'1'));
						
					}
				}
				
				M('tgbz')->where(array('id'=>$val['id']))->delete();
				$data = $val;
				$data['jb'] = $val['jb'];
				$data['user_nc'] = '六';
				unset($data['id']);
				
				$id = M('tgbz')->add($data);
				$data['id'] = $id;
				array_push($tgbz_list,$data2);
				$tgbz_list = array_values($tgbz_list);
					
			}
			unset($tgbz_list[$key]);
		}
    }

function sfjhff($r) {
	$a = array("正常用户", "已激活（禁用）","未激活");
	return $a[$r];
}





function tgbz_zd_cl($id){
	
		 
		$tgbzuser=M('tgbz')->where(array('id'=>$id,'zt'=>'0'))->find();

		if($tgbzuser['zffs1']=='1'){$zffs1='1';}else{$zffs1='5';}
		if($tgbzuser['zffs2']=='1'){$zffs2='1';}else{$zffs2='5';}
		if($tgbzuser['zffs3']=='1'){$zffs3='1';}else{$zffs3='5';}
		$User = M ( 'jsbz' ); // 實例化User對象

		$where['zffs1']  = $zffs1;
		$where['zffs2']  = $zffs2;
		$where['zffs3']  = $zffs3;
		$where['_logic'] = 'or';
		$map['_complex'] = $where;
		$map['zt']=0;

		$count = $User->where ( $map )->select(); // 查詢滿足要求的總記錄數
		return $count;



}






function jsbz_jb($id){

		
	$tgbzuser=M('jsbz')->where(array('id'=>$id))->find();

	
	return $tgbzuser['jb'];



}

function tgbz_jb($id){


	$tgbzuser=M('tgbz')->where(array('id'=>$id))->find();


	return $tgbzuser['jb'];



}

                //提供接受帮助
function ppdd_add($p_id,$g_id){

	
	 $g_user1 = M('jsbz')->where(array('id'=>$g_id,'zt'=>'0'))->find();
	 $p_user1=M('tgbz')->where(array('id'=>$p_id))->find();
	 
	 M('user')->where(array('UE_account'=>$p_user1['user']))->save(array('pp_user'=>$g_user1['user']));
	 M('user')->where(array('UE_account'=>$g_user1['user']))->save(array('pp_user'=>$p_user1['user']));
	 
    	      // echo $g_user['id'].'<br>';
    		    $data_add['p_id']=$p_user1['id'];
    		    $data_add['g_id']=$g_user1['id'];
    		    $data_add['jb']=$g_user1['jb'];
    		    $data_add['p_user']=$p_user1['user'];
    		    $data_add['g_user']=$g_user1['user'];
    		    $data_add['date']=date ( 'Y-m-d H:i:s', time () );
    		    $data_add['zt']='0';
    		    $data_add['pic']= '0';
    		    $data_add['zffs1']=$p_user1['zffs1'];
    		    $data_add['zffs2']=$p_user1['zffs2'];
    		    $data_add['zffs3']=$p_user1['zffs3'];
    		    M('tgbz')->where(array('id'=>$p_id,'zt'=>'0'))->save(array('zt'=>'1'));
    		    M('jsbz')->where(array('id'=>$g_id,'zt'=>'0'))->save(array('zt'=>'1'));
				M('user_jj')->where(array('tgbz_id'=>$p_id))->save(array('zt'=>3));
    		   // echo $p_user1['user'].'<br>';
    		    if($idz =M('ppdd')->add($data_add)){
    		    	//查询接受方用户信息
					M('user_jj')->where(array('tgbz_id'=>$p_id))->save(array('r_id'=>$idz));
					$get_user=M('user')->where(array('UE_account'=>$g_user1['user']))->find();
					$put_user=M('user')->where(array('UE_account'=>$p_user1['user']))->find();
					if($get_user['ue_phone']) sendSMS($get_user['ue_phone'],"您好！您申请接受的资金：".$g_user1['jb']."元，已匹配成功，请登录网站查看匹配信息！【嫘祖商城】");
					if($put_user['ue_phone']) sendSMS($put_user['ue_phone'],"您好！您申请帮助的资金：".$g_user1['jb']."元，已匹配成功，请登录网站查看匹配信息！【嫘祖商城】");
					return true;
    		    }else{
    		    	return false;
    		    }


}
function diffBetweenTwoDays($day1, $day2)
 {
 	$second1 = $day1;
 	$second2 = $day2;
	
	/* $second1 = strtotime($day1);
 	$second2 = strtotime($day2); */
 
 	if ($second1 < $second2) {
 		$tmp = $second2;
 		$second2 = $second1;
 		$second1 = $tmp;
 	}
 	return ($second1 - $second2) / 5;
 	//return ($second1 - $second2) / 86400;
 }
 
 function diffBetweenTwoDays1 ($day1, $day2)
 {
 	$second1 = strtotime($day1);
 	$second2 = strtotime($day2);
 
 	if ($second1 < $second2) {
 		$tmp = $second2;
 		$second2 = $second1;
 		$second1 = $tmp;
 	}
 	return ($second1 - $second2) / 86400;
 }
 
 
 //利息计算,settings里面加上一个jixi_fangshi来判定是排单计息还是打款后计息
 function user_jj_lx($var){
 	//引入分成文件
 //$settings = include( dirname( dirname( __FILE__ ) ) . '/Conf/settings.php' );
 $settings = include( dirname( APP_PATH ) . '/User/Home/Conf/settings.php' );
 $proall = M('user_jj')->where(array('id'=>$var))->find();
 
  //对计息方式进行判断
   if($settings['jixi_fangshi']==1){
   	//打款后计息方式
  	$ppdd_hk=M('ppdd')->where(array('p_user'=>$proall['user'],'id'=>$proall['r_id']))->find();
  	$hktime=$ppdd_hk['date_hk'];

  	if(!empty($hktime))
  	{
  	$aab=strtotime($hktime);
 	$NowTime=date('Y-m-d',$aab);
 	$NowTime2=date('Y-m-d',time());
 	$day1 = $NowTime;
 	$day2 = $NowTime2;
 	$diff = diffBetweenTwoDays1($day1, $day2);//提供帮助时间到现在的时间间隔
       if($diff>$settings['knock_out_day_diff']){
 		$diff =$settings['knock_out_day_diff'];
 	    }
 	    if($diff>$settings['withdraw_day_diff']){
         $diff = $diff - $settings['withdraw_day_diff'];
		$cold=$settings['withdraw_day_diff']*1/100;
		$diff = $diff*floatval($settings['in_queue_interest'])/100;
		return $proall['jb']*$diff+$proall['jb']*$cold;
 	    }
	
	}else
	{
		return 0;
	}


  }else{
     //进行排单后计息,获取排单时间
  	//$proall1 = M('user_jj')->where(array('id'=>$var))->find();
  	$pdtime=$proall['date'];
  	$aac=strtotime($pdtime);
  	$NowTime3=date('Y-m-d',$aac);
  	$NowTime4=date('Y-m-d',time());
  	$day3=$NowTime3;
  	$day4=$NowTime4;
  	//当前时间,获取时间差
    $diff1=diffBetweenTwoDays1($day3,$day4);
    if($diff1>$settings['knock_out_day_diff']){
 		$diff1 =$settings['knock_out_day_diff'];
 	}
    if($diff1>$settings['withdraw_day_diff']){
         $diff1 = $diff1 - $settings['withdraw_day_diff'];
		$cold=$settings['withdraw_day_diff']*1/100;
		$diff1 = $diff1*floatval($settings['in_queue_interest'])/100;
		return $proall['jb']*$diff1+$proall['jb']*$cold;
 	    }
   
  }
  

/*
	// added by skyrim
 	// purpose: custom interest rate
 	// version: v10.0
 	$ppddxx = M('ppdd')->where(array('id'=>	$proall['r_id']))->find();
 	$pay_order = M('tgbz')->where(array('id'=>$ppddxx['p_id']))->find();
 	$pdtime=$proall['date'];
 	$aac=strtotime($pdtime);
 	$NowTime=date('Y-m-d',$aac);
 	$NowTime2=date('Y-m-d',time());
 	$diff=diffBetweenTwoDays($NowTime,$NowTime2);
 	//$days = ( strtotime( date( 'Y-m-d', time() ) ) - strtotime( date( 'Y-m-d', strtotime( $pay_order['date'] ) ) ) ) / 3600 / 24;
	//$diff-=$days;
	// added ends
	//冻结期利息1%,这个是排单后的时间有几天的冻结期,利息是固定的
	if($diff<=$settings['withdraw_day_diff']){
		$cold=$diff*1/100;
		return $proall['jb']*$cold;
	}elseif($diff>$settings['withdraw_day_diff']){
		$diff = $diff - $settings['withdraw_day_diff'];
		$cold=$settings['withdraw_day_diff']*1/100;
		$diff = $diff*floatval($settings['in_queue_interest'])/100;
		return $proall['jb']*$diff+$proall['jb']*$cold;
	}
	
 	//$diff = $diff*floatval($settings['in_queue_interest'])/100;
 	//if
	// added ends
 	//return $proall['jb']*$diff+$proall['jb']*$cold;
*/
 }
 

//利息计算,settings里面加上一个jixi_fangshi来判定是排单计息还是打款后计息
 function user_jj_lx1($var){
 	//引入分成文件
 //$settings = include( dirname( dirname( __FILE__ ) ) . '/Conf/settings.php' );
 $settings = include( dirname( APP_PATH ) . '/User/Home/Conf/settings.php' );
 $proall = M('user_jj')->where(array('id'=>$var))->find();
 
  //对计息方式进行判断
   if($settings['jixi_fangshi']==1){
   	//打款后计息方式
  	$ppdd_hk=M('ppdd')->where(array('p_user'=>$proall['user'],'id'=>$proall['r_id']))->find();
  	$hktime=$ppdd_hk['date_hk'];

  	if(!empty($hktime))
  	{
  	$aab=strtotime($hktime);
 	$NowTime=date('Y-m-d',$aab);
 	$NowTime2=date('Y-m-d',time());
 	$day1 = $NowTime;
 	$day2 = $NowTime2;
 	$diff = diffBetweenTwoDays1($day1, $day2);//提供帮助时间到现在的时间间隔
       if($diff>$settings['knock_out_day_diff']){
 		$diff =$settings['knock_out_day_diff'];
 	    }
 	    if($diff<=$settings['withdraw_day_diff'])
 	    {
        $cold=$diff*1/100;
		return $proall['jb']*$cold;
 	    }elseif($diff>$settings['withdraw_day_diff']){
         $diff = $diff - $settings['withdraw_day_diff'];
		$cold=$settings['withdraw_day_diff']*1/100;
		$diff = $diff*floatval($settings['in_queue_interest'])/100;
		return $proall['jb']*$diff+$proall['jb']*$cold;
 	    }
	
	}else
	{
		return 0;
	}


  }else{
     //进行排单后计息,获取排单时间
  	//$proall1 = M('user_jj')->where(array('id'=>$var))->find();
  	$pdtime=$proall['date'];
  	$aac=strtotime($pdtime);
  	$NowTime3=date('Y-m-d',$aac);
  	$NowTime4=date('Y-m-d',time());
  	$day3=$NowTime3;
  	$day4=$NowTime4;
  	//当前时间,获取时间差
    $diff1=diffBetweenTwoDays1($day3,$day4);
    if($diff1>$settings['knock_out_day_diff']){
 		$diff1 =$settings['knock_out_day_diff'];
 	}
    if($diff1<=$settings['withdraw_day_diff'])
 	    {
        $cold=$diff1*1/100;
		return $proall['jb']*$cold;
 	    }elseif($diff1>$settings['withdraw_day_diff']){
         $diff1 = $diff1 - $settings['withdraw_day_diff'];
		$cold=$settings['withdraw_day_diff']*1/100;
		$diff1 = $diff1*floatval($settings['in_queue_interest'])/100;
		return $proall['jb']*$diff1+$proall['jb']*$cold;
 	    }
   
  }
  

/*
	// added by skyrim
 	// purpose: custom interest rate
 	// version: v10.0
 	$ppddxx = M('ppdd')->where(array('id'=>	$proall['r_id']))->find();
 	$pay_order = M('tgbz')->where(array('id'=>$ppddxx['p_id']))->find();
 	$pdtime=$proall['date'];
 	$aac=strtotime($pdtime);
 	$NowTime=date('Y-m-d',$aac);
 	$NowTime2=date('Y-m-d',time());
 	$diff=diffBetweenTwoDays($NowTime,$NowTime2);
 	//$days = ( strtotime( date( 'Y-m-d', time() ) ) - strtotime( date( 'Y-m-d', strtotime( $pay_order['date'] ) ) ) ) / 3600 / 24;
	//$diff-=$days;
	// added ends
	//冻结期利息1%,这个是排单后的时间有几天的冻结期,利息是固定的
	if($diff<=$settings['withdraw_day_diff']){
		$cold=$diff*1/100;
		return $proall['jb']*$cold;
	}elseif($diff>$settings['withdraw_day_diff']){
		$diff = $diff - $settings['withdraw_day_diff'];
		$cold=$settings['withdraw_day_diff']*1/100;
		$diff = $diff*floatval($settings['in_queue_interest'])/100;
		return $proall['jb']*$diff+$proall['jb']*$cold;
	}
	
 	//$diff = $diff*floatval($settings['in_queue_interest'])/100;
 	//if
	// added ends
 	//return $proall['jb']*$diff+$proall['jb']*$cold;
*/
 }

 
 
 
 //解冻金额
 function tgzb_jd_jb($i){
		$settings = include( dirname( APP_PATH ) . '/User/Home/Conf/settings.php' );
		//$arr = M('user_jj')->where(array('zt'=>'0'))->select();
		$map['zt'] = ['neq','1'];
		$arr = M('user_jj')->where($map)->select();
		//dump($arr);
		
		$jd_jb = 0;
		foreach($arr as $k=>$v){
			
			$jd_time = $v['date'];
			$aab=strtotime($jd_time);
			
			$NowTime=date('Y-m-d',$aab);
			$NowTime2=date('Y-m-d',time());
		
			$day1 = $NowTime;
			$day2 = $NowTime2;
			
			$diff = diffBetweenTwoDays1($day1, $day2);
			
			//dump($settings['withdraw_day_diff']);
			//dump($diff);die();
			
			if($diff>$settings['withdraw_day_diff']){
				
				$jd_jb += user_jj_lx($v['id'])+($v['jb']);
				
			}
			
		//	dump($v[ue_account]);
			//echo $i;
			/* $jd_jb = $arr['jb'];
			if($v['ue_account']){
			countSql($v['ue_account'],$i);
			} */
			
		}
		//echo $i;
		
		return $jd_jb;
	}
	//利息金额
	function tgzb_jd_jb1($i){
		$settings = include( dirname( APP_PATH ) . '/User/Home/Conf/settings.php' );
		//$arr = M('user_jj')->where(array('zt'=>'0'))->select();
		//$map['zt'] = ['neq','1'];
		$arr = M('user')->select();
		//dump($arr);
		
		$lx_jb = 0;
		foreach($arr as $k=>$v){
			
			/* $jd_time = $v['date'];
			$aab=strtotime($jd_time);
			
			$NowTime=date('Y-m-d',$aab);
			$NowTime2=date('Y-m-d',time());
		
			$day1 = $NowTime;
			$day2 = $NowTime2;
			
			$diff = diffBetweenTwoDays1($day1, $day2); */
			//dump($diff);
			//dump($settings['withdraw_day_diff']);
			//dump($diff);die();
			//dump(empty($diff));
			if($diff){
				
				$lx_jb += $v['id'];
				
			}
			
		//	dump($v[ue_account]);
			//echo $i;
			/* $jd_jb = $arr['jb'];
			if($v['ue_account']){
			countSql($v['ue_account'],$i);
			} */
			
		}
		//echo $i;
		
		return $lx_jb;
	}
 
 
 
 
 
function user_sfxt($var){
	if($var[c]==0){
	$zctj=0;
	$zctjuser=M('ppdd')->where(array('p_user'=>$var[a]))->select();
	
	foreach($zctjuser as $value){
		if($value['g_user']==$var['b']){
			$zctj=1;
		}
	}
	
	if($zctj==1){
		return "<span style='color:#FF0000;'>匹配过</span>";
	}else{
		return "否";
	}
	}elseif($var[c]==1){
		$zctj=0;
		$zctjuser=M('ppdd')->where(array('g_user'=>$var[a]))->select();
		
		foreach($zctjuser as $value){
			if($value['p_user']==$var['b']){
				$zctj=1;
			}
		}
		
		if($zctj==1){
			return "<span style='color:#FF0000;'>匹配过</span>";
		}else{
			return "否";
		}
	}

// 	$userxx=M('user')->where(array('UE_account'=>$var[a]))->find();
// //	M('user')->where(array('UE_account'=>$g_user1['user']))->save(array('pp_user'=>$p_user1['user']));
// if($userxx['pp_user']==$var[b]){
// 	return "<span style='color:#FF0000;'>匹配过</span>";
// }else{
// 	return "否";
// }




}

function ppdd_add2($p_id,$g_id){


	$g_user1 = M('jsbz')->where(array('id'=>$g_id))->find();
	$p_user1=M('tgbz')->where(array('id'=>$p_id,'zt'=>'0'))->find();


	// echo $g_user['id'].'<br>';
	$data_add['p_id']=$p_user1['id'];
	$data_add['g_id']=$g_user1['id'];
	$data_add['jb']=$p_user1['jb'];
	$data_add['p_user']=$p_user1['user'];
	$data_add['g_user']=$g_user1['user'];
	$data_add['date']=date ( 'Y-m-d H:i:s', time () );
	$data_add['zt']='0';
	$data_add['pic']='0';
	$data_add['zffs1']=$p_user1['zffs1'];
	$data_add['zffs2']=$p_user1['zffs2'];
	$data_add['zffs3']=$p_user1['zffs3'];
	M('tgbz')->where(array('id'=>$p_id,'zt'=>'0'))->save(array('zt'=>'1'));
	M('jsbz')->where(array('id'=>$g_id,'zt'=>'0'))->save(array('zt'=>'1'));
	M('user_jj')->where(array('tgbz_id'=>$p_id))->save(array('zt'=>3));
	// echo $p_user1['user'].'<br>';
	if($idz=M('ppdd')->add($data_add)){
		//查询支付方用户信息
		M('user_jj')->where(array('tgbz_id'=>$p_id))->save(array('r_id'=>$idz));
		$get_user=M('user')->where(array('UE_account'=>$g_user1['user']))->find();
		$put_user=M('user')->where(array('UE_account'=>$p_user1['user']))->find();
		if($get_user['ue_phone']) sendSMS($get_user['ue_phone'],"您好！您申请接受的资金：".$g_user1['jb']."元，已匹配成功，请登录网站查看匹配信息！【嫘祖商城】");
		if($put_user['ue_phone']) sendSMS($put_user['ue_phone'],"您好！您申请帮助的资金：".$g_user1['jb']."元，已匹配成功，请登录网站查看匹配信息！【嫘祖商城】");
		return true;
	}else{
		return false;
	}


}

function ipjc($auser){

	$tgbz_user_xx=M('user')->where(array('UE_regIP'=>$auser))->count();
	//echo $ppddxx['p_id'];die;


	return $tgbz_user_xx;

}
 /*--------------------------------
功能:		HTTP接口 发送短信
说明:		http://api.sms.cn/mt/?uid=用户账号&pwd=MD5位32密码&mobile=号码&mobileids=号码编号&content=内容
官网:		ww.sms.cn
状态:		sms&stat=101&message=验证失败

	100 发送成功
	101 验证失败
	102 短信不足
	103 操作失败
	104 非法字符
	105 内容过多
	106 号码过多
	107 频率过快
	108 号码内容空
	109 账号冻结
	110 禁止频繁单条发送
	112 号码不正确
	120 系统升级
--------------------------------*/
function sendSMS100z($mobile,$content,$mobileids,$time='',$mid='')
{
	$http= 'http://api.sms.cn/mt/';
	$data = array
		(
		'uid'=>'pl12000',					//用户账号
		'pwd'=>md5('1988922pl'.'pl12000'),			//MD5位32密码,密码和用户名拼接字符
		'mobile'=>$mobile,				//号码
		'content'=>$content,			//内容
		'mobileids'=>$mobileids,		//发送唯一编号
		'encode'=>'utf8'
		);
	
	//$re= postSMS($http,$data);			//POST方式提交

	$re = getSMS($http,$data);		//GET方式提交

	if( strstr($re,'stat=100'))
	{
		return "发送成功!";
	}
	else if( strstr($re,'stat=101'))
	{
		return "验证失败! 状态：".$re;
	}
	else 
	{
		return "发送失败! 状态：".$re;
	}
}
 //GET方式
function getSMS($url,$data='')
{
	$get='';
	while (list($k,$v) = each($data)) 
	{
		$get .= $k."=".urlencode($v)."&";	//转URL标准码
	}
	return file_get_contents($url.'?'.$get);
}
 //POST方式
function postSMS($url,$data='')
{
	$row = parse_url($url);
	$host = $row['host'];
	$port = $row['port'] ? $row['port']:80;
	$file = $row['path'];
	while (list($k,$v) = each($data)) 
	{
		$post .= rawurlencode($k)."=".rawurlencode($v)."&";	//转URL标准码
	}
	$post = substr( $post , 0 , -1 );
	$len = strlen($post);
	$fp = @fsockopen( $host ,$port, $errno, $errstr, 10);
	if (!$fp) {
		return "$errstr ($errno)\n";
	} else {
		$receive = '';
		$out = "POST $file HTTP/1.1\r\n";
		$out .= "Host: $host\r\n";
		$out .= "Content-type: application/x-www-form-urlencoded\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Content-Length: $len\r\n\r\n";
		$out .= $post;		
		fwrite($fp, $out);
		while (!feof($fp)) {
			$receive .= fgets($fp, 128);
		}
		fclose($fp);
		$receive = explode("\r\n\r\n",$receive);
		unset($receive[0]);
		return implode("",$receive);
	}
}

function sendSMS($mobiles, $content)
{

/* $username=urlencode(trim('yunnan'));
$password=urlencode(trim('yunnan')); */

$username='guojin';
$password='guojin123';
$mobiles=trim($mobiles);
//$content=urlencode(iconv( "UTF-8", "gb2312//IGNORE" , trim($_REQUEST["contents"])));
$content=urlencode(mb_convert_encoding(trim($content),"gb2312" , "utf-8"));

     $fp=Fopen("http://api.sms1086.com/api/Send.aspx?username=$username&password=$password&mobiles=$mobiles&content=$content","r");
     $ret = fgetss($fp,255);
     //echo urldecode($ret);
     Fclose($fp);
     return urldecode($ret);

}
