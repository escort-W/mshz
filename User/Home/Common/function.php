<?php
function cate($var){ 
	//dump($var);
	$proall = M('user')->where(array('UE_accName'=>$var,'UE_Faccount'=>'0','UE_check'=>'1','UE_stop'=>'1'))->count("UE_ID");
 return $proall;
 } 
 
 /**
  * 获取本周开始时间
  */
  function getWeekBeginTime(){
 	$date = date("Y-m-d");
 	$first = 1;
 	$w = date("w");
 	$d = $w ? $w - $first : 6;
 	return strtotime("$date - $d days"); //本周开始时间
 }
 
 /**
  * 获取本周结束时间
  * @return number
  */
 function getWeekEndTime(){
 	
 	return getWeekBeginTime() + (86400 * 7) -1;
 }
 
 
 function remainder_time($var){
	$settings = include( dirname( dirname( __FILE__ ) ) . '/Confttings.php' );
 	$time = $var;
 	$aab=strtotime($time);
 	$a = $settings['play_money_limit_day_chaifen1'];
 	$aab2=$aab+86400*$a;
 	$bba3 = $aab2-time();
	 	if($bba3>0){
	 		return ceil(($aab2-time())/60/60);
	 	}else{
	 		return 0;
	 	}
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
				//echo '22';
			ob_flush();
			flush();
			}else{
			//	echo '33';
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
	
                //提供接受帮助
/*
*$p_id,$g_id,分别为提供帮助者id，接受帮助者id
*
*/
function ppdd_add($p_id,$g_id){	
	 $g_user1 = M('jsbz')->where(array('id'=>$g_id,'zt'=>'0'))->find();
	 $p_user1=M('tgbz')->where(array('id'=>$p_id))->find();
	 //更新user表中，用户的匹配信息，相互写入对方用户名.z
	 M('user')->where(array('UE_account'=>$p_user1['user']))->save(array('pp_user'=>$g_user1['user']));
	 M('user')->where(array('UE_account'=>$g_user1['user']))->save(array('pp_user'=>$p_user1['user']));
	 
	 // echo $g_user['id'].'<br>';
    		    $data_add['p_id']=$p_user1['id'];
    		    $data_add['g_id']=$g_user1['id'];
    		    $data_add['jb']=$g_user1['jb'];
    		    $data_add['p_user']=$p_user1['user'];
    		    $data_add['g_user']=$g_user1['user'];
    		    $data_add['date']=date ( 'Y-m-d H:i:s', time());
    		    $data_add['zt']='0';
    		    $data_add['pic']='0';
    		    $data_add['zffs1']=$p_user1['zffs1'];
    		    $data_add['zffs2']=$p_user1['zffs2'];
    		    $data_add['zffs3']=$p_user1['zffs3'];
    		    
    		    //修改tgbz状态，修改jsbz状态，修改user_jj状态
    		    M('tgbz')->where(array('id'=>$p_id,'zt'=>'0'))->save(array('zt'=>'1'));
    		    M('jsbz')->where(array('id'=>$g_id,'zt'=>'0'))->save(array('zt'=>'1'));
				M('user_jj')->where(array('tgbz_id'=>$p_id))->save(array('zt'=>3));
    		   // echo $p_user1['user'].'<br>';
    		    if(M('ppdd')->add($data_add)){
    		    	//查询接受方用户信息
					$get_user=M('user')->where(array('UE_account'=>$g_user1['user']))->find();
					$put_user=M('user')->where(array('UE_account'=>$p_user1['user']))->find();
					if($get_user['ue_phone']) sendSMS($get_user['ue_phone'],"您好！您申请接受的资金：".$g_user1['jb']."元，已匹配成功，请登录网站查看匹配信息！【嫘祖商城】");
					if($put_user['ue_phone']) sendSMS($put_user['ue_phone'],"您好！您申请帮助的资金：".$g_user1['jb']."元，已匹配成功，请登录网站查看匹配信息！【嫘祖商城】");
    		    	return true;
    		    }else{
    		    	return false;
    		    }
}	
	
	
	
 function isMobile(){
		// 如果有HTTP_X_WAP_PROFILE则一定是移动设备
		//return true;
		if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
		return true;

		//此条摘自TPM智能切换模板引擎，适合TPM开发
		if(isset ($_SERVER['HTTP_CLIENT']) &&'PhoneClient'==$_SERVER['HTTP_CLIENT'])
		return true;
		//如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
		if (isset ($_SERVER['HTTP_VIA']))
		//找不到为flase,否则为true
		return stristr($_SERVER['HTTP_VIA'], 'wap') ? true : false;
		//判断手机发送的客户端标志,兼容性有待提高
		if (isset ($_SERVER['HTTP_USER_AGENT'])) {
		$clientkeywords = array(
		'nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile'
		);
		//从HTTP_USER_AGENT中查找手机浏览器的关键字
		if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
		return true;
		}
		}
		//协议法，因为有可能不准确，放到最后判断
		if (isset ($_SERVER['HTTP_ACCEPT'])) {
		// 如果只支持wml并且不支持html那一定是移动设备
		// 如果支持wml和html但是wml在html之前则是移动设备
		if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
		return true;
		}
		}
		return false;
	}
 
 
function sfjhff($r) {
 	$a = array("已激活", "未激活");
 	return $a[$r];
 }
 
 
	function getRand($proArr) {
 	$result = '';
 
 	//概率数组的总概率精度
 	$proSum = array_sum($proArr);
 
 	//概率数组循环
 	foreach ($proArr as $key => $proCur) {
 		$randNum = mt_rand(1, $proSum);
 		if ($randNum <= $proCur) {
 			$result = $key;
 			break;
 		} else {
 			$proSum -= $proCur;
 		}
 	}
 	unset ($proArr);
 
 	return $result;
 }
 
 
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
 
 function cx_user($var){
 	//dump($var);
 	$proall = M('user')->where(array('UE_account'=>$var))->find();
 	return $proall['ue_theme'];
 }
 
 
 //计算天数
 function diffBetweenTwoDays ($day1, $day2)
 {
 	$second1 = strtotime($day1);
 	$second2 = strtotime($day2);
 
 	if ($second1 < $second2) {
 		$tmp = $second2;
 		$second2 = $second1;
 		$second1 = $tmp;
 	}
 	return ($second1 - $second2) / 86400;
 	//return ($second1 - $second2) / 2;
 }

 
 /* //利息计算,settings里面加上一个jixi_fangshi来判定是排单计息还是打款后计息
 function user_jj_lx($var){
 	//引入分成文件
 $settings = include( dirname( dirname( __FILE__ ) ) . '/Conf/settings.php' );
 $proall = M('user_jj')->where(array('id'=>$var))->find();
 //加入任务利息奖励
 $jianglilixi = M('user_jj')->where(['id'=>$var])->getField('jianglilixi');
 $jiangli = $proall['jb']*$jianglilixi*0.01;
 //加入任务利息奖励

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
 	$diff = diffBetweenTwoDays($day1, $day2);//提供帮助时间到现在的时间间隔
       if($diff>$settings['knock_out_day_diff']){
 		$diff =$settings['knock_out_day_diff'];
 	    }
 	    if($diff<=$settings['withdraw_day_diff'])
 	    {
        $cold=$diff*$settings['cold_user_lixi']/100;
		return $proall['jb']*$cold+$jiangli;
 	    }elseif($diff>$settings['withdraw_day_diff']){
         $diff = $diff - $settings['withdraw_day_diff'];
		$cold=$settings['withdraw_day_diff']*$settings['cold_user_lixi']/100;
		$diff = $diff*floatval($settings['in_queue_interest'])/100;
		return $proall['jb']*$diff+$proall['jb']*$cold+$jiangli;
 	    }
	
	else
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
    $diff1=diffBetweenTwoDays($day3,$day4);
    if($diff1>$settings['knock_out_day_diff']){
 		$diff1 =$settings['knock_out_day_diff'];
 	}
    if($diff1<=$settings['withdraw_day_diff'])
 	    {
        $cold=$diff1*$settings['cold_user_lixi']/100;
		return $proall['jb']*$cold+$jiangli;
 	    }elseif($diff1>$settings['withdraw_day_diff']){
         $diff1 = $diff1 - $settings['withdraw_day_diff'];
		$cold=$settings['withdraw_day_diff']*$settings['cold_user_lixi']/100;
		$diff1 = $diff1*floatval($settings['in_queue_interest'])/100;
		return $proall['jb']*$diff1+$proall['jb']*$cold+$jiangli;
 	    }
   
  }
   */
   
   
   
   //利息计算,settings里面加上一个jixi_fangshi来判定是排单计息还是打款后计息
 function user_jj_lx($var){
 	//引入分成文件
 $settings = include( dirname( dirname( __FILE__ ) ) . '/Conf/settings.php' );
 $proall = M('user_jj')->where(array('id'=>$var))->find();
 //加入任务利息奖励
 $jianglilixi = M('user_jj')->where(['id'=>$var])->getField('jianglilixi');
 $jiangli = $proall['jb']*$jianglilixi*0.01;
 //加入任务利息奖励
 
	
    //对计息方式进行判断
  
     //进行排单后计息,获取排单时间
  	//$proall1 = M('user_jj')->where(array('id'=>$var))->find();
  	$pdtime=$proall['date'];
  	$aac=strtotime($pdtime);
  	$NowTime3=date('Y-m-d',$aac);
  	$NowTime4=date('Y-m-d',time());
  	$day3=$NowTime3;
  	$day4=$NowTime4;
  	//当前时间,获取时间差
    $diff1=diffBetweenTwoDays($day3,$day4);
    if($diff1>=$settings['withdraw_day_diff']){
 		$diff1 =$settings['withdraw_day_diff'];
 	}
 	//判断是大小爱区,1是大爱区20%
 	if($proall['zhh'] == 2){
 		$t=$settings['queue_interest1'];
 	}elseif($proall['zhh'] == 1){
 		$t=$settings['queue_interest2'];
 	}
 	
    return $diff1*$proall['jb']*$t/100;
   
 
  
}

 
 function user_jj_ts($var){
 
 	$proall = M('user_jj')->where(array('id'=>$var))->find();
 
 	//date('Y-m-d H:i:s',$dayBegin);
 	$NowTime = $proall['date'];
 	$aab=strtotime($NowTime);
 	$NowTime=date('Y-m-d',$aab);
 	$NowTime2=date('Y-m-d',time());
 
 
 	$day1 = $NowTime;
 	$day2 = $NowTime2;
 	$diff = diffBetweenTwoDays($day1, $day2);
 	// noted by skyrim
 	// 天数设置
 	// noted ends
	// deleted by skyrim
 	// if($diff>30){
 	// 	$diff =30;
 	// }
 	// deleted ends
	// added by skyrim
 	// purpose: custom knock out diff days
 	// version: v1.0
 	$settings = include( dirname( dirname( __FILE__ ) ) . '/Conf/settings.php' );
 	if($diff>$settings['knock_out_day_diff']){
 		$diff =$settings['knock_out_day_diff'];
 	}
	// added ends
 	//$diff = $diff/100;
 	return $diff;
 
 }
 
 
 
 function user_jj_tx($var){
 
 	$proall = M('tgbz')->where(array('id'=>$var))->find();
 
 	//date('Y-m-d H:i:s',$dayBegin);
 	$NowTime = $proall['date'];
 	$aab=strtotime($NowTime);
 	$NowTime=date('Y-m-d',$aab);
 	$NowTime2=date('Y-m-d',time());
 
	$day1 = $aab;
 	$day2 = time();
 	/* $day1 = $NowTime;
 	$day2 = $NowTime2; */
 	return $diff = diffBetweenTwoDays($day1, $day2);

 }
 
 function prcode_create($path,$data,$size=10,$level='H'){
		//set it to writable location, a place for temp generated PNG files
		//$PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
		
		$PNG_TEMP_DIR = $_SERVER['DOCUMENT_ROOT'] .'/qrcode/'.$path.'/';
		
		require "./Public/phpqrcode/qrlib.php";    

		//ofcourse we need rights to create temp dir
		if (!file_exists($PNG_TEMP_DIR))
			$re = mkdir($PNG_TEMP_DIR,0755,true);
		
		if (isset($data)) { 
		
			//it's very important!
			if (trim($data) == '')
				die('生成内容为空');				
			// user data
			$filename = $PNG_TEMP_DIR.md5($data.'|'.$level.'|'.$size).'.png';
			QRcode::png($data, $filename, $level, $size, 2);    			
		} else {    		
			die( '内容必须存在');    			
		}    		   
		return basename($filename);
	}
 
 
 
 
 
 function user_jj_sj($var){
 
 	$proall = M('tgbz')->where(array('id'=>$var))->find();
 
 	$user = M ( 'user' )->where ( array (
 			UE_account => $proall ['user']
 	) )->find ();
 	return $user['ue_phone'];
 
 }
 
 
 
 function user_jj_tx1($var){
 
 	$proall = M('jsbz')->where(array('id'=>$var))->find();
 
 	//date('Y-m-d H:i:s',$dayBegin);
 	$NowTime = $proall['date'];
 	$aab=strtotime($NowTime);
 	$NowTime=date('Y-m-d',$aab);
 	$NowTime2=date('Y-m-d',time());
 
 
 	$day1 = $NowTime;
 	$day2 = $NowTime2;
 	return $diff = diffBetweenTwoDays($day1, $day2);
 
 }
 
 
 
 function user_jj_sj1($var){
 
 	$proall = M('jsbz')->where(array('id'=>$var))->find();
 
 	$user = M ( 'user' )->where ( array (
 			UE_account => $proall ['user']
 	) )->find ();
 	return $user['ue_phone'];
 
 }
 
 
 
 
 function user_jj_zt($var){
 
 	$proall = M('user_jj')->where(array('id'=>$var))->find();
 	$proall2 = M('ppdd')->where(array('id'=>$proall['r_id']))->find();
 	//date('Y-m-d H:i:s',$dayBegin);
 	$NowTime = $proall['date'];
 	$aab=strtotime($NowTime);
 	$NowTime=date('Y-m-d',$aab);
 	$NowTime2=date('Y-m-d',time());
 
 
 	$day1 = $NowTime;
 	$day2 = $NowTime2;
 	$diff = diffBetweenTwoDays($day1, $day2);
 	
	$settings = include( dirname( dirname( __FILE__ ) ) . '/Conf/settings.php' );
	//return '1';
 	if($diff>=$settings['withdraw_day_diff']&&$proall2['zt']=='2'){	
 	    return '1';
 	}else{
 		return '0';
 	}
 }
 
 
 
 function user_jj_zt_z($var){
 
 	if(user_jj_zt($var)=='1'){
 		return '可以提现';
 	}else{
 		return '不可提现';
 	}
 }
 
 function user_jj_pipei_z($var){
 	$proall = M('ppdd')->where(array('id'=>$var))->find();
 	if($proall['zt']=='0'){
 		return '未打款';
 	}elseif($proall['zt']=='1'){
 		return '已打款';
 	}elseif($proall['zt']=='2'){
 		return '交易成功';
 	}
 }
 
 
 function user_jj_pipei_z2($var){
 	$proall = M('ppdd')->where(array('id'=>$var))->find();
 	if($proall['zt']=='0'){
 		return '未发放';
 	}elseif($proall['zt']=='1'){
 		return '未发放';
 	}elseif($proall['zt']=='2'){
 		return '已发放';
 	}
 }
 

 //团队奖奖金放入
// added by skyrim
// purpose: custom share
// version: 6.0
// function masses_j($a,$b,$c,$i,$jb,$m){
// 	$tgbz_user_xx=M('user')->where(array('UE_account'=>$a))->find(); 
//     $money=$b;
//     // $money1=$money*0.5;
//     // $money2=$money-$money1;
    
// 			$accname_zq=M('user')->where(array('UE_account'=>$tgbz_user_xx['ue_account']))->find();
			
// 			M('user')->where(array('UE_account'=>$tgbz_user_xx['ue_account']))->setInc('jl_he',$money1);
// 			M('user')->where(array('UE_account'=>$tgbz_user_xx['ue_account']))->setInc('shop_money',$money2);
				
// 			$accname_xz=M('user')->where(array('UE_account'=>$tgbz_user_xx['ue_account']))->find();
// 			$record3 ["UG_account"] = $tgbz_user_xx['ue_account']; // 登入轉出賬戶
// 			$record3 ["UG_type"] = 'jb';
// 			$record3 ["UG_allGet"] = $accname_zq['jl_he']; // 金幣
// 			$record3 ["UG_money"] = '+'.$money1; //
// 			$record3 ["UG_balance"] = $accname_xz['jl_he']; // 當前推薦人的金幣餘額
// 			$record3 ["UG_dataType"] = 'jlj'; // 金幣轉出
// 			//$record3 ["UG_note"] = $c.'a'.$i.'a'.$coun.'a'.$count.'a'.$jb.'a'.$m; // 推薦獎說明
// 			$record3 ["UG_note"] = $c;
// 			$record3["UG_getTime"] = date ( 'Y-m-d H:i:s', time () ); //操作時間
// 			$reg3 = M ( 'userget' )->add ( $record3 );

// 		//商城币分成奖励
		
// 		/* $c1=$c.'转换为商城币';
// 		$record3 = array();
// 		$record3 ["UG_account"] = $tgbz_user_xx['ue_account']; // 登入轉出賬戶
// 		$record3 ["UG_type"] = 'jb';
// 		$record3 ["UG_allGet"] = $accname_zq['shop_money']; // 金幣
// 		$record3 ["UG_money"] = '+'.$money2; //
// 		$record3 ["UG_balance"] = $accname_xz['shop_money']; // 當前推薦人的金幣餘額
// 		$record3 ["UG_dataType"] = 'jlj'; // 金幣轉出
// 		$record3 ["UG_note"] = $c1; // 推薦獎說明
// 		$record3["UG_getTime"] = date ( 'Y-m-d H:i:s', time () ); //操作時間
		
// 		$reg4 = M ( 'userget' )->add ( $record3 );  */
	
// 	return $tgbz_user_xx['ue_accname'];
// }
function masses_j($a,$b,$c,$d,$e){
	    $tgbz_user_xx=M('user')->where(array('UE_account'=>$a))->find();
		$money=$b;
		
		// if($e== 1){
		// 	/* $qmoney=$accname_zq['tj_he'];
		// 	$tmoney=$accname_zq['tj_he']+$money; */
		// 	$accname_zq=M('user')->where(array('UE_account'=>$tgbz_user_xx['ue_account']))->find();
		// 	$aaa11 = M('user')->where(array('UE_account'=>$tgbz_user_xx['ue_account']))->setInc('tj_he',$money);
		// 	$accname_xz=M('user')->where(array('UE_account'=>$tgbz_user_xx['ue_account']))->find();
		// 	$record3 ["UG_allGet"] = $accname_zq['tj_he']; // 金幣
		// 	$record3 ["UG_balance"] = $accname_xz['tj_he']; // 金幣
		// 	$record3 ["UG_othraccount"] = 1;
		// }else{
			/* $qmoney=$accname_zq['jl_he'];
			$tmoney=$accname_zq['jl_he']+$money; */
			$accname_zq=M('user')->where(array('UE_account'=>$tgbz_user_xx['ue_account']))->find();
			$aaa11 = M('user')->where(array('UE_account'=>$tgbz_user_xx['ue_account']))->setInc('jl_he',$money);
			$accname_xz=M('user')->where(array('UE_account'=>$tgbz_user_xx['ue_account']))->find();
			$record3 ["UG_allGet"] = $accname_zq['jl_he']; // 金幣
			$record3 ["UG_balance"] = $accname_xz['jl_he']; // 金幣
			$record3 ["UG_othraccount"] = 2;
		// }
		$time=date('Y-m-d H:i:s',time());
		$jdtime=date('Y-m-d H:i:s',strtotime("$time +30 days"));
		$record3 ["UG_account"] = $tgbz_user_xx['ue_account']; // 登入轉出賬戶
		$record3 ["UG_type"] = 'jb';
		//$record3 ["UG_allGet"] = jl_he; // 金幣
		$record3 ["UG_money"] = $money; //
		$record3 ["UG_dataType"] = 'jlj'; // 金幣轉出
		//$record3 ["UG_balance"] = $tmoney; // 金幣
		$record3 ["money"]=$d;
		$record3 ["UG_note"] = $c; // 推薦獎說明
		$record3["UG_getTime"]		= date ( 'Y-m-d H:i:s', time () ); //操作時間
		$record3["jd_date"]=$jdtime;
		$record3['dtzt']=0;
		$reg3 = M ( 'userget' )->add ( $record3 );
	
	return $tgbz_user_xx['ue_accname'];
}
/* function tuandu($i){
	switch($i){
		case 1:
			return  1;
			break;
		case 2:
			return  1;
			break;
		case 3:
			return  1;
			break;
		case 4:
			return  1;
			break;
		case 5:
			return  1;
			break;
		case 0:
			return  1;
			break;
		
	}
} */

function tuandu($i){
	switch($i){
		case 1:
			return  16;
			break;
		case 2:
			return  11;
			break;
		case 3:
			return  6;
			break;
		case 4:
			return  3;
			break;
		case 5:
			return  1;
			break;
		case 0:
			return  31;
			break;

	}
}

// added ends
//经理奖奖金放入
 function jlj($a,$b,$c){
 	jlsja($a);
 	$tgbz_user_xx=M('user')->where(array('UE_account'=>$a))->find();
 	//echo $ppddxx['p_id'];die;
if($tgbz_user_xx['sfjl']==1){
 		$money=$b;
 		$accname_zq=M('user')->where(array('UE_account'=>$tgbz_user_xx['ue_account']))->find();
 		M('user')->where(array('UE_account'=>$tgbz_user_xx['ue_account']))->setInc('jl_he',$money);
		//M('user')->where(array('UE_account'=>$tgbz_user_xx['ue_account']))->setDec('jl_he1',$money);
 		$accname_xz=M('user')->where(array('UE_account'=>$tgbz_user_xx['ue_account']))->find();
 		$record3 ["UG_account"] = $tgbz_user_xx['ue_account']; // 登入轉出賬戶
 		$record3 ["UG_type"] = 'jb';
 		$record3 ["UG_allGet"] = $accname_zq['jl_he']; // 金幣
 		$record3 ["UG_money"] = '+'.$money; //
 		$record3 ["UG_balance"] = $accname_xz['jl_he']; // 當前推薦人的金幣餘額
 		$record3 ["UG_dataType"] = 'jlj'; // 金幣轉出
 		$record3 ["UG_note"] = $c; // 推薦獎說明
 		$record3["UG_getTime"]		= date ( 'Y-m-d H:i:s', time () ); //操作時間
 		$reg4 = M ( 'userget' )->add ( $record3 );
}
        return $tgbz_user_xx['ue_accname'];
 
 }
 
 

 function jlj2($a,$b,$c,$d,$e){
 	$tgbz_user_xx=M('user')->where(array('UE_account'=>$a))->find();
 	if($tgbz_user_xx['sfjl']==1){
 	$ppddxx=M('ppdd')->where(array('id'=>$e))->find();
 	$peiduidate=M('tgbz')->where(array('id'=>$ppddxx['p_id'],'user'=>$ppddxx['p_user']))->find();
				$data2['user']=$a;
				$data2['r_id']=$ppddxx['id'];
				$data2['date']=$peiduidate['date'];
				$data2['note']='经理奖第'.$d.'代';
				$data2['jb']=$ppddxx['jb'];
				$data2['jj']=$b;
				$data2['ds']=$d;
				M('user_jl')->add($data2);
 	}
	return $tgbz_user_xx['ue_accname'];
 }
 /* 推荐奖
  * $a,推荐人
  * $b,百分比
  * $c,说明
  * $d,1
  * $e,ppdd的id
  *  */
 function jlj3($a,$b,$c,$d,$e){
 	$settings = include( dirname( dirname( __FILE__ ) ) . '/Conf/settings.php' );
 	$tgbz_user_xx=M('user')->where(array('UE_account'=>$a))->find();
 	$ppddxx=M('ppdd')->where(array('id'=>$e))->find();
 	$peiduidate=M('tgbz')->where(array('id'=>$ppddxx['p_id'],'user'=>$ppddxx['p_user']))->find();
 	$money=$ppddxx['jb']*$settings['cyj_share']/100;
 	$data2['user']=$a;
 	$data2['r_id']=$ppddxx['id'];
 	$data2['date']=$peiduidate['date'];
 	$data2['note']=$c;
 	$data2['jb']=$ppddxx['jb'];
 	$data2['jj']=$b;
 	$data2['ds']=$d;
 	M('user_jl')->add($data2);
 	$tgbz_user_xx=M('user')->where(array('UE_account'=>$a))->setInc('jl_he1',$money);
 	return $tgbz_user_xx['ue_accname'];
 }
 function jlj4($a,$b){
 	$tgbz_user_xx=M('user')->where(array('UE_account'=>$a))->find();
 	
 	M('user')->where(array('UE_account' => $a))->setInc('tj_he1',$b);
 	
 	
 	return $tgbz_user_xx['ue_accname'];
 }
// added by skytim
// purpose: calc masses share
// version: 5.0
 function hupkehuj5($a,$b){
	$tgbz_user_xx=M('user')->where(array('UE_account'=>$a))->find();
	if($tgbz_user_xx['sfjl']==0){
		M('user')->where(array('UE_account' => $a))->setInc('jl_he1',$b);
	}

	return $tgbz_user_xx['ue_accname'];
 }
// added ends
 function jlj5($a,$b){
 	$tgbz_user_xx=M('user')->where(array('UE_account'=>$a))->find();
 	if($tgbz_user_xx['sfjl']==1){
 		M('user')->where(array('UE_account' => $a))->setInc('jl_he1',$b);
 	}
 
 	return $tgbz_user_xx['ue_accname'];
 }
 
 function datedqsj($var){
 	$settings = include( dirname( dirname( __FILE__ ) ) . '/Conf/settings.php' );
 	$dk=$settings['cold2_user_time']*60*60;
 	$NowTime = $var;
 	$aab=strtotime($NowTime);
 	$aab2=$aab+$dk;
	return date('Y-m-d H:i:s',$aab2);
}

function datedqsj_2($var){
	$settings = include( dirname( dirname( __FILE__ ) ) . '/Conf/settings.php' );
	$dk=$settings['cold2_user_time']*60*60;
	$NowTime = $var;
 	$aab=strtotime($NowTime);
 	$aab2=$aab+$dk;
	return date('Y/m/d H:i:s',$aab2);
}
function datedqsjt($var){
	$settings = include( dirname( dirname( __FILE__ ) ) . '/Conf/settings.php' );
	$dk=$settings['cold3_user_time']*60*60;
	$NowTime = $var;
	$aab=strtotime($NowTime);
	$aab2=$aab+$dk;
	return date('Y-m-d H:i:s',$aab2);
}

function datedqsj_2t($var){
	$settings = include( dirname( dirname( __FILE__ ) ) . '/Conf/settings.php' );
	$dk=$settings['cold3_user_time']*60*60;
	$NowTime = $var;
	$aab=strtotime($NowTime);
	$aab2=$aab+$dk;
	return date('Y/m/d H:i:s',$aab2);
}

 function hk($var){
 
 
 	
 
 	return $var.'RMB';
 
 }
 
 function datedqsj2($var){
 
 
 	$NowTime = $var;
 	$aab=strtotime($NowTime);
 	/* NOTED BY SKYRIM: 86400 = 1day */
 	$aab2=$aab+86400+86400;
 	$bba3 = date('Y-m-d H:i:s',time());
 	$bba4=strtotime($bba3);
 
 if($aab2>$bba4){
 	return "style='display:none;'";
 }
 }
 
 function datedqsj3($var){
 
 
 	$NowTime = $var;
 	$aab=strtotime($NowTime);
 	$aab2=$aab+86400+86400;
 	$bba3 = date('Y-m-d H:i:s',time());
 	$bba4=strtotime($bba3);
 
 	if($aab2>$bba4){
 		return "style='display:none;'";
 	}
 }
 
 /* NOTED BY SKYRIM: 经理升级 */
 /* NOTED BY SKYRIM: 条件：下线>10 且 统共帮助金额>7000 */
function jlsja($var){
$settings = include( dirname( dirname( __FILE__ ) ) . '/Conf/settings.php' );
 $zctj=0;
 //dump($settings);
 /* NOTED BY SKYRIM: UE_accName - 推荐人账号 */
 $zctjuser=M('user')->where(array('UE_accName'=>$var))->select();//提供帮助的人
 //dump($zctjuser);die;
 /* NOTED BY SKYRIM: zctjuser - 充值账户所有的下线 */
 foreach($zctjuser as $value){
	 //dump($value['ue_account']);
 	$tgbztjl=M('ppdd')->where(array('p_user'=>$value['ue_account'],'zt'=>2))->sum('jb');
	//dump($tgbztjl);
 /* NOTED BY SKYRIM: 这里的700 要秀嘎！ */
 // deleted by skrim
 // purpose: for custom knock_out_day_diff
 // version: 10.0
 //	if($tgbztj1>=700){
 // deleted ends
 // added by skrim
 // purpose: for custom knock_out_day_diff
 // version: 10.0
	//$settings = include( APP_PATH . 'Home/Conf/settings.php' );
	
	
	
	
 	//if($tgbztjl>=$settings['supply_money_lower_limit']){
 // added 
 		$zctj++;
 	//}
	
 }
 /* NOTED BY SKYRIM: zctj - 所有金额>700的已完成订单个数 */
//die;

 $tgbztj=M('ppdd')->where(array('p_user'=>$var,'zt'=>'2'))->sum('jb');
 /* NOTED BY SKYRIM: tgbztj */
 // deleted by skrim
 // purpose: for custom knock_out_day_diff
 // version: 9.0
 // if($zctj>=10&&$tgbztj>=7000){
 // deleted ends
 // added by skrim
 // purpose: for custom knock_out_day_diff
 // version: 9.0
	//$settings = include( APP_PATH . 'Home/Conf/settings.php' );
 if($zctj>=$settings['up_to_jl_threshold']&&$tgbztjl>=$settings['up_to_jl_yeji']){
 // added ends
 	M('user')->where(array('UE_account'=>$var))->save(array('sfjl'=>1));
 }
 }
 
 
 
 
 function lkdsjfsdfj($var1,$jb){
 
 	$ppddxx['p_user']=$var1;
 	$ppddxx['jb']=$jb;
 //经理奖金订单
 $tgbz_user_xx=M('user')->where(array('UE_account'=>$ppddxx['p_user']))->find();//充值人详细
 // added by skrim
 // purpose: for different share
 // version: 1.0
	$settings = include( APP_PATH . 'Home/Conf/settings.php' );
 // added ends
 // deleted by skrim
 // purpose: for different share
 // version: 1.0
 //jlj4($tgbz_user_xx['ue_accname'],$ppddxx['jb']*0.1);
 // deleted ends
 // added by skrim
 // purpose: for different share
 // version: 1.0
 jlj4($tgbz_user_xx['ue_accname'],$ppddxx['jb']*floatval($settings['tjr_share']));
 // added ends
 // deleted ends
 // added by skrim
 // purpose: for different share
 // version: 1.0
 // if($tgbz_user_xx['zcr']<>''){
 // 	$zcr2=jlj5($tgbz_user_xx['zcr'],$ppddxx['jb']*0.05);
 // 	if($zcr2<>''){
 // 		$zcr3=jlj5($zcr2,$ppddxx['jb']*0.03);
 // 		//echo $ppddxx['p_user'].'sadfsaf';die;
 // 		if($zcr3<>''){
 // 			$zcr4=jlj5($zcr3,$ppddxx['jb']*0.01);
 // 			if($zcr4<>''){
 // 				$zcr5=jlj5($zcr4,$ppddxx['jb']*0.005);
 // 				if($zcr5<>''){
 // 					$zcr6=jlj5($zcr5,$ppddxx['jb']*0.003);
 // 					if($zcr6<>''){
 // 						$zcr7=jlj5($zcr6,$ppddxx['jb']*0.001);
 // 						if($zcr7<>''){
 // 							$zcr8=jlj5($zcr7,$ppddxx['jb']*0.0005);
 // 							if($zcr8<>''){
 // 								$zcr9=jlj5($zcr8,$ppddxx['jb']*0.0003);
 // 
 // 								if($zcr9<>''){
 // 									jlj5($zcr9,$ppddxx['jb']*0.0001);
 // 								
 // 										
 // 								}
 // 							}
 // 						}
 // 					}
 // 				}
 // 			}
 // 		}
 // 	}
 // }
 // deleted ends
// added by skytim
// purpose: calc masses share
// version: 5.0
$this_node = $tgbz_user_xx['ue_accname'];
$i = $settings['max_user_level'];
while( $i -- ){
	if( $this_node && strlen( $this_node ) ){
	 $this_node = hupkehuj5( $this_node, $ppddxx['jb']*floatval($settings['masses_share'][$settings['max_user_level']-$i]));
	}
}
// added ends
 // deleted ends
// added by skytim
// purpose: calc jl share
// version: 5.0
 $this_node = $tgbz_user_xx['ue_accname'];
 $i = $settings['max_jl_level'];
 while( $i -- ){
 	 if( $this_node && strlen( $this_node ) ){
 		 $this_node = jlj5( $this_node, $ppddxx['jb']*floatval($settings['jl_share'][$settings['max_jl_level']-$i]));
 	 }
 }
// added ends
// deleted by skyrim
// purpose: rewrite calc jl share algorithym
 /*
 // added by skrim
 // purpose: for different share
 // version: 1.0
 if($tgbz_user_xx['zcr']<>''){
 	$zcr2=jlj5($tgbz_user_xx['zcr'],$ppddxx['jb']*floatval($settings['jl_share'][1]));
 	if($zcr2<>''){
 		$zcr3=jlj5($zcr2,$ppddxx['jb']*floatval($settings['jl_share'][2]));
 		//echo $ppddxx['p_user'].'sadfsaf';die;
 		if($zcr3<>''){
 			$zcr4=jlj5($zcr3,$ppddxx['jb']*floatval($settings['jl_share'][3]));
 			if($zcr4<>''){
 				$zcr5=jlj5($zcr4,$ppddxx['jb']*floatval($settings['jl_share'][4]));
 				if($zcr5<>''){
 					$zcr6=jlj5($zcr5,$ppddxx['jb']*floatval($settings['jl_share'][5]));
 					if($zcr6<>''){
 						$zcr7=jlj5($zcr6,$ppddxx['jb']*floatval($settings['jl_share'][6]));
 						if($zcr7<>''){
 							$zcr8=jlj5($zcr7,$ppddxx['jb']*floatval($settings['jl_share'][7]));
 							if($zcr8<>''){
 								$zcr9=jlj5($zcr8,$ppddxx['jb']*floatval($settings['jl_share'][8]));
 								if($zcr9<>''){
 									jlj5($zcr9,$ppddxx['jb']*floatval($settings['jl_share'][9]));
 								}
 							}
 						}
 					}
 				}
 			}
 		}
 	}
 }
 */
// deleted ends
 // added ends
 	
 //经理奖金订单
 
 }
 //奖金分成【新、未启用】
 function tc_jiang($UE_account,$money)
 {
 	$settings = include( dirname( dirname( __FILE__ ) ) . '/Conf/settings.php' );//获取分成配置
	//循环经理分成配置，如果用户是普通用户则使用普通用户比例，是经理用户则使用经理用户比例
	for($i=1;$i<=$settings['max_jl_level'];$i++)
	{
		$user_info=M('user')->where(array('UE_account'=>$UE_account))->find();
		if($user_info['sfjl']==0) $share=$settings['masses_share'][$i];
		else $share=$settings['jl_share'][$i];
		$tc_money=$money*$share;
		M('user')->where(array('UE_account'=>$UE_account))->setInc('jl_he',$money);
		$accname_xz=M('user')->where(array('UE_account'=>$UE_account))->find();
		$record3 ["UG_account"] = $UE_account; // 登入轉出賬戶
		$record3 ["UG_type"] = 'jb';
		$record3 ["UG_allGet"] = $user_info['jl_he']; // 金幣
		$record3 ["UG_money"] = '+'.$tc_money; //
		$record3 ["UG_balance"] = $accname_xz['jl_he']; // 當前推薦人的金幣餘額
		$record3 ["UG_dataType"] = 'jlj'; // 金幣轉出
		$record3 ["UG_note"] = "分成奖励"; // 推薦獎說明
		$record3["UG_getTime"]		= date ( 'Y-m-d H:i:s', time () ); //操作時間
		M ( 'userget' )->add ( $record3 );
		
		if(empty($user_info['ue_accname'])) break;
		else $UE_account=$user_info['ue_accname'];
	}
 }
 
 function getTurnRand($proArr) { 
		$result = ''; 
	 
		//概率数组的总概率精度 
		$proSum = array_sum($proArr); 
	 
		//概率数组循环 
		foreach ($proArr as $key => $proCur) { 
			$randNum = mt_rand(1, $proSum); 
			if ($randNum <= $proCur) { 
				$result = $key; 
				break; 
			} else { 
				$proSum -= $proCur; 
			} 
		} 
		unset ($proArr); 
	 
		return $result; 
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
function sendSMS0000z($mobile,$content,$mobileids,$time='',$mid='')
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

$username=urlencode(trim('guojin'));
$password=urlencode(trim('guojin123'));
$mobiles=trim($mobiles);
//$content=urlencode(iconv( "UTF-8", "gb2312//IGNORE" , trim($_REQUEST["contents"])));
$content=urlencode(mb_convert_encoding(trim($content),"gb2312" , "utf-8"));

     $fp=Fopen("http://api.sms1086.com/api/Send.aspx?username=$username&password=$password&mobiles=$mobiles&content=$content","r");
     $ret = fgetss($fp,255);
     //echo urldecode($ret);
     Fclose($fp);
     return urldecode($ret);

}

 
?>