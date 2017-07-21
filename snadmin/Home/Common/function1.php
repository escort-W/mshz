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
    		    $data_add['pic']='0';
    		    $data_add['zffs1']=$p_user1['zffs1'];
    		    $data_add['zffs2']=$p_user1['zffs2'];
    		    $data_add['zffs3']=$p_user1['zffs3'];
    		    M('tgbz')->where(array('id'=>$p_id,'zt'=>'0'))->save(array('zt'=>'1'));
    		    M('jsbz')->where(array('id'=>$g_id,'zt'=>'0'))->save(array('zt'=>'1'));
				M('user_jj')->where(array('tgbz_id'=>$p_id))->save(array('zt'=>3));
    		   // echo $p_user1['user'].'<br>';
    		    if(M('ppdd')->add($data_add)){
    		    	//查询接受方用户信息
					$get_user=M('user')->where(array('UE_account'=>$g_user1['user']))->find();
					if($get_user['ue_phone']) sendSMS($get_user['ue_phone'],"您好！您申请帮助的资金：".$g_user1['jb']."元，已匹配成功，请登录网站查看匹配信息！");
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
// $settings = include( dirname( dirname( __FILE__ ) ) . '/Conf/settings.php' );
$settings = include( dirname( APP_PATH ) . '/User/Home/Conf/settings.php' );
 $proall = M('user_jj')->where(array('id'=>$var))->find();
  //对计息方式进行判断
   if($settings['jixi_fangshi']==1){
  	$ppdd_hk=M('ppdd')->where(array('p_user'=>$proall['user'],'id'=>$proall['r_id']))->find();
  	$hktime=$ppdd_hk['date_hk'];
  	if(!empty($hktime)){
  	$aab=strtotime($hktime);
 	$NowTime=date('Y-m-d',$aab);
 	$NowTime2=date('Y-m-d',time());
 	$day1 = $NowTime;
 	$day2 = $NowTime2;
 	$diff = diffBetweenTwoDays1($day1, $day2);//提供帮助时间到现在的时间间隔
	//dump($diff);die;
 	if($diff>$settings['knock_out_day_diff']){
 		$diff =$settings['knock_out_day_diff'];
		
 	}
	   //$hktime=strtotime($time1);
	   //获取当前时间与打款后的时间差
	   //$day3=$hktime;
	   //当前时间
	   //$day2=date("Y-m-d H:i:s",time());
	   //$diff=diffBetweenTwoDays($day2,$day3);
	    //$days1 = ( strtotime( date( 'Y-m-d', time() ) ) - strtotime( date( 'Y-m-d', strtotime( $hktime ) ) ) ) / 3600 / 24;
	    //返回的是打款后的计息方式
	    $diff = $diff*floatval($settings['in_queue_interest'])/100;
	    //return $settings['in_queue_interest'];
	   return $proall['jb']*$diff;
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
    $diff1= $diff1*floatval($settings['in_queue_interest'])/100;
    //return $settings['knock_out_day_diff'];
    return $diff1*$proall['jb'];
  }


	// added by skyrim
 	// purpose: custom interest rate
 	// version: v10.0
 	$ppddxx = M('ppdd')->where(array('id'=>	$proall['r_id']))->find();
 	$pay_order = M('tgbz')->where(array('id'=>$ppddxx['p_id']))->find();
 	$days = ( strtotime( date( 'Y-m-d', time() ) ) - strtotime( date( 'Y-m-d', strtotime( $pay_order['date'] ) ) ) ) / 3600 / 24;
	//$diff-=$days;
	// added ends
	//冻结期利息1%
	if($diff>$settings['withdraw_day_diff']){
		$diff = $diff - $settings['withdraw_day_diff'];
		$cold=$settings['withdraw_day_diff']*1/100;
		$diff = $diff*floatval($settings['in_queue_interest'])/100;
		return $proall['jb']*$diff+$proall['jb']*$cold;
		
	}
	
 	//$diff = $diff*floatval($settings['in_queue_interest'])/100;
 	//if
	// added ends
 	//return $proall['jb']*$diff+$proall['jb']*$cold;

 }
 
 //利息计算,settings里面加上一个jixi_fangshi来判定是排单计息还是打款后计息
 function user_jj_lx1($var){
 	//引入分成文件
 $settings = include( dirname( APP_PATH ) . '/User/Home/Conf/settings.php' );
 $proall = M('user_jj')->where(array('id'=>$var))->find();
  //对计息方式进行判断
   if($settings['jixi_fangshi']==1){
  	$ppdd_hk=M('ppdd')->where(array('p_user'=>$proall['user'],'id'=>$proall['r_id']))->find();
  	$hktime=$ppdd_hk['date_hk'];
  	if(!empty($hktime)){
  	$aab=strtotime($hktime);
 	$NowTime=date('Y-m-d',$aab);
 	$NowTime2=date('Y-m-d',time());
 	$day1 = $NowTime;
 	$day2 = $NowTime2;
 	$diff = diffBetweenTwoDays1($day1, $day2);//提供帮助时间到现在的时间间隔
 	if($diff>$settings['knock_out_day_diff']){
 		$diff =$settings['knock_out_day_diff'];
 	}
	   //$hktime=strtotime($time1);
	   //获取当前时间与打款后的时间差
	   //$day3=$hktime;
	   //当前时间
	   //$day2=date("Y-m-d H:i:s",time());
	   //$diff=diffBetweenTwoDays($day2,$day3);
	    //$days1 = ( strtotime( date( 'Y-m-d', time() ) ) - strtotime( date( 'Y-m-d', strtotime( $hktime ) ) ) ) / 3600 / 24;
	    //返回的是打款后的计息方式
	    $diff = $diff*floatval($settings['in_queue_interest'])/100;
	    //return $settings['in_queue_interest'];
	   return $proall['jb']*$diff;
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
    $diff1= $diff1*floatval($settings['in_queue_interest'])/100;
    //return $settings['knock_out_day_diff'];
    return $diff1*$proall['jb'];
  }


	// added by skyrim
 	// purpose: custom interest rate
 	// version: v10.0
 	$ppddxx = M('ppdd')->where(array('id'=>	$proall['r_id']))->find();
 	$pay_order = M('tgbz')->where(array('id'=>$ppddxx['p_id']))->find();
 	$days = ( strtotime( date( 'Y-m-d', time() ) ) - strtotime( date( 'Y-m-d', strtotime( $pay_order['date'] ) ) ) ) / 3600 / 24;
	//$diff-=$days;
	// added ends
	//冻结期利息1%
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
		$arr = M('user_jj')->select();
		//dump($arr);
		
		$lx_jb = 0;
		foreach($arr as $k=>$v){
			
			$jd_time = $v['date'];
			$aab=strtotime($jd_time);
			
			$NowTime=date('Y-m-d',$aab);
			$NowTime2=date('Y-m-d',time());
		
			$day1 = $NowTime;
			$day2 = $NowTime2;
			
			$diff = diffBetweenTwoDays1($day1, $day2);
			//dump($diff);
			//dump($settings['withdraw_day_diff']);
			//dump($diff);die();
			//dump(empty($diff));
			if($diff){
				
				$lx_jb += user_jj_lx1($v['id']);
				
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
	if(M('ppdd')->add($data_add)){
		//查询支付方用户信息
		$pay_user=M('user')->where(array('UE_account'=>$p_user1['user']))->find();
		if($pay_user['ue_phone']) sendSMS($pay_user['ue_phone'],"您好！您提供帮助的资金：".$p_user1['jb']."元，已匹配成功，请登录网站查看匹配信息，并打款！");
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
function sendSMS($mobile,$content,$mobileids,$time='',$mid='')
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
