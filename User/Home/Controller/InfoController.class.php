<?php

namespace Home\Controller;

use Think\Controller;

class InfoController extends CommonController {
	// 首頁
	public function index() {
		$userData = M ( 'user' )->where ( array ('UE_ID' => $_SESSION ['uid']) )->find ();
		$this->userData = $userData;
		
// 		echo $userData['yhzh'];
		$ip=M ( 'drrz' )->where ( array ('user' => $_SESSION ['uname'],'leixin'=>0) )->order ( 'id DESC' )->limit ( 2 )->select();
		$this->assign("ssid",session_id());
		$this->bcip=$ip[0];
		$this->scip=$ip[1];
		$this->display ( 'grsz' );
	}
	public function ppdd_lists(){
	    $tgbz_list=M('tgbz')->alias('t')->join('ot_ppdd as p on t.id=p.p_id')->where('t.user='.$_SESSION['uname'])->select();
	    $jsbz_list=M('jsbz')->alias('j')->join('ot_ppdd as p on j.id=p.g_id')->where('j.user='.$_SESSION['uname'])->select();
	    // var_dump($tgbz_list);exit();
        $this->assign('tgbz_list',$tgbz_list);
        $this->assign('jsbz_list',$jsbz_list);
		$this->display();
	}
	//短信验证修改资料
	public function yzm(){
		$phone = I('get.phone');

		if($phone == ''){
			$this->ajaxReturn('shoujihao');
			die;
		}
		$rand['val'] = rand(0,9999);
		$rand['active_time'] = 60;
		$rand['create_time'] = time();
		$content = '你好，您的修改资料验证码为'.$rand['val'].'【民生互助】';
		$re = sendSMS($phone,$content);
		if($re == 0){
			session('yzm',$rand);
			$this->ajaxReturn('验证码已发送！');
		}else{
			$this->ajaxReturn('验证码发送失败！');
		}
	}
	public function xgmm() {
		$userData = M ( 'user' )->where ( array (
				'UE_ID' => $_SESSION ['uid']
		) )->find ();
		$this->userData = $userData;
		$this->display ( 'xgmm' );
	}
	public function xgmme() {
		$userData = M ( 'user' )->where ( array (
				'UE_ID' => $_SESSION ['uid']
		) )->find ();
		$this->userData = $userData;
		$this->display ( 'xgmme' );
	}
	public function bdmb() {
		$userData = M ( 'user' )->where ( array (
				'UE_ID' => $_SESSION ['uid']
		) )->find ();
		$this->userData = $userData;
		if($userData['ue_question']==''){
		$this->display ( 'bdmb' );
		}else{
			$this->display ( 'xgmb' );
		}
	}
	public function xgmb() {
		$userData = M ( 'user' )->where ( array (
				'UE_ID' => $_SESSION ['uid']
		) )->find ();
		$this->userData = $userData;
		$this->display ( 'xgmb' );
	}
	public function addskzh() {
		$userData = M ( 'user' )->where ( array (
				'UE_ID' => $_SESSION ['uid']
		) )->find ();
		$this->userData = $userData;
		$this->display ( 'addskzh' );
	}
public function skzh() {
		$userData = M ( 'user' )->where ( array (
				'UE_ID' => $_SESSION ['uid']
		) )->find ();
		$caution = M ( 'userinfo' )->where ( array (
				'UI_userID' => $_SESSION ['uid']
		) )->order ( 'UI_ID DESC' )->select ();
		$this->caution = $caution;
		//dump($caution);die;
		$this->userData = $userData;
		$this->display ( 'skzh' );
	}
	
	public function skzhdl() {
		if (!preg_match ( '/^[0-9]{1,10}$/', I ('get.id') )) {
			$this->success('非法操作,将冻结账号!');
		}else{
			$userinfo = M ( 'userinfo' )->where ( array ('UI_ID' => I ('get.id')) )->find ();
			if ($userinfo['ui_userid']<>$_SESSION ['uid']) {
				$this->success('非法操作,将冻结账号!');
			}else{
				$reg = M ( 'userinfo' )->where(array ('UI_ID' => I ('get.id')))->delete();
				if ($reg) {
					$this->success('刪除成功!');
				}else {
					$this->success('刪除失敗!');
				}
			}
		}
	}
	
	public function ejmm() {
		$this->display ( 'ejmm' );
	}
	public function ejmmcl() {
		//echo $_SESSION['url'];die;
	if (IS_POST) {
		        $data_P = I ( 'post.' );
	            $addaccount = M ( 'user' )->where ( array (UE_account => $_SESSION ['uname']) )->find ();
	//dump($addaccount['ue_secpwd']);
	//dump(md5($data_P['ejmmqr']));
	//die;
				if ($addaccount['ue_secpwd']<>md5($data_P['ejmmqr'])) {
					$this->error('二級密碼不正確!');
				}else {
					$_SESSION['ejmmyz'] = $addaccount['ue_secpwd'];
				//	echo ;die;
					$this->success('验证成功',$_SESSION['url']);
				}
    	}
    	
	}
	/*
	*修改信息提交方法
	 */
	public function xgzlcl() {
		if (IS_POST) {
			$data_P = I ( 'post.' );
			$tgbztj=M('ppdd')->where(array('p_user'=>$_SESSION['uname'],'zt'=>'2'))->sum('jb');
			//$tgbztj>=600||$userxx['sfjl']==1
			//验证码验证
			/* $yzm = $data_P['yzm'];
			if($yzm != session('yzm.val')){
				$this->error('请输入正确的手机验证码!');
			}; */
			/* if(false){
				$this->success('提供帮助成功后不可修改个人信息和经理人不可自行修改资料!');
			}else{ */
			$userxx=M('user')->where(array('UE_account'=>$_SESSION['uname']))->find();
			$this->assign('userxx',$userxx);
			//dump($userxx);die;
		
				// if($userxx['ue_secpwd']<>md5($data_P['trade_pwd2'])){
				// 	die("<script>alert('二级密码输入有误！');history.back(-1);</script>");
				// }else{
		
					$data_up['weixin'] = $data_P['wechat'];				
					$data_up['zfb'] = $data_P['alipay'];
					$data_up['yhmc'] = $data_P['yhmc'];
					// $data_up['UE_theme'] = $data_P['theme'];
					$data_up['yhzh'] = $data_P['yhzh'];
					$data_up['zhxm'] = $data_P['zhxm'];
					$data_up['UE_truename'] = $data_P['truename'];
					//$data_up['khzh'] = $data_P['khzh'];
					$data_up['UE_phone'] = $data_P['phone'];
					// $data_up['UE_info'] = $data_P['ue_info'];
					$reg = M('user')->where(array('UE_account'=>$_SESSION['uname']))->save($data_up);
					
					
					
					if ($reg) {
						die("<script>alert('修改成功！');history.back(-1);</script>");
					} else {
						die("<script>alert('修改失败！');history.back(-1);</script>");
					}
				// }
			
		
		}
	}
	
	/*
	*修改密码提交
	 */
	public function xgyjmmcl() {
		
		if (IS_POST) {
			$data_P = I ( 'post.' );  	
			if (!preg_match ( '/^[a-zA-Z0-9]{1,15}$/', $data_P ['xmm'] )) {
				// $this->error ('新密码6-12个字符,可以是大小写英文,数字组合,请勿用特殊字符！' );
				die("<script>alert('新密码6-12个字符,可以是大小写英文,数字组合,请勿用特殊字符！');history.back(-1);</script>");
			}elseif ($data_P['xmm']<>$data_P['xmmqr']) {
				// $this->error ('新密码两次输入不一致!');
				die("<script>alert('新密码两次输入不一致！');history.back(-1);</script>");
			}elseif ($data_P['ymm']==$data_P['xmm']) {
				// $this->error ('原密码和新密码不能相同!' );
				die("<script>alert('原密码和新密码不能相同');history.back(-1);</script>");

			} else {
				$addaccount = M ( 'user' )->where ( array (UE_account => $_SESSION ['uname']) )->find ();
				if ($addaccount['ue_password']<>md5($data_P['ymm'])) {
					die("<script>alert('原密码不正确');history.back(-1);</script>");
				}else {
					$reg = M ( 'user' )->where ( array ('UE_ID' => $_SESSION ['uid']) )->save (array('UE_password'=>md5($data_P['xmm'])));		
					if ($reg) {
						die("<script>alert('修改成功!');history.back(-1);</script>");
					} else {
						die("<script>alert('修改失敗！');history.back(-1);</script>");
					}
				}
			}
		}
	}
	
	public function addskzhcl() {
	
		if (IS_AJAX) {
			$data_P = I ( 'post.' );
			//dump($data_P);
			//$this->ajaxReturn($data_P['ymm']);die;
			//$user = M ( 'user' )->where ( array (
			//		UE_account => $_SESSION ['uname']
			//) )->find ();
				
			$user1 = M ();
			//! $this->check_verify ( I ( 'post.yzm' ) )
			//! $user1->autoCheckToken ( $_POST )
			if (! $this->check_verify ( I ( 'post.yzm' ) )) {
					
				$this->ajaxReturn ( array ('nr' => '驗證碼錯誤!','sf' => 0 ) );
			} elseif (strlen($data_P['skfs']) > 13 || strlen($data_P['skfs']) <6 ) {
				$this->ajaxReturn ( array ('nr' => '請選擇收款方式!','sf' => 0 ) );
			} elseif (!preg_match ( '/^[0-9]{6,30}$/', $data_P ['skzh'] )) {
				$this->ajaxReturn ( array ('nr' => '收款賬號為數字6-30位！','sf' => 0 ) );
			}elseif (strlen($data_P['khh']) > 60 || strlen($data_P['khh']) <6) {
				$this->ajaxReturn ( array ('nr' => '開戶支行中文字數2-20字!','sf' => 0 ) );
			}else {
				
			//	if ($addaccount['ue_password']<>md5($data_P['ymm'])) {
				//	$this->ajaxReturn ( array ('nr' => '原密碼不正確!','sf' => 0 ) );
				if(! $user1->autoCheckToken ( $_POST )){
					$this->ajaxReturn ( array ('nr' => '新勿重複提交,請刷新頁面!','sf' => 0 ) );
				} else {
					$addaccount = M ( 'user' )->where ( array (UE_account => $_SESSION ['uname']) )->find ();
					
					$data_up['UI_userID'] = $_SESSION ['uid'];
					$data_up['UI_time'] = date ( 'Y-m-d H:i:s', time () );
					$data_up['UI_realName'] = $addaccount['ue_truename'];
					$data_up['UI_payaccount'] = $data_P['skzh'];
					$data_up['UI_payStyle'] = $data_P ['skfs'];
					$data_up['UI_isindex'] = I ('post.sfqy',0,'/^[1]{1}$/');
					$data_up['UI_opendress'] = $data_P ['khh'];
					$reg=M ( 'userinfo' )->add ( $data_up );
				//	$reg = M ( 'user' )->where ( array (
				//			'UE_ID' => $_SESSION ['uid']
				//	) )->save (array('UE_password'=>md5($data_P['xmm'])));
	
				//dump($data_up);
	
					if ($reg) {
						$this->ajaxReturn ( '添加成功!' );
					} else {
						$this->ajaxReturn ( '添加失敗!' );
					}
				}
			}
		}
	}
	
public function xgejmmcl() {
		
		if (IS_POST) {
			$data_P = I ( 'post.' );
			if (!preg_match ( '/^[a-zA-Z0-9]{1,15}$/', $data_P ['xejmm'] )) {
				//$this->ajaxReturn ( array ('nr' => '新二级密碼6-12個字元,大小寫英文+數字,請勿用特殊詞符！','sf' => 0 ) );
				die("<script>alert('新二级密吗6-12个字符,英文大小写或数字,请勿用特殊字符！');history.back(-1);</script>");
			}elseif ($data_P['xejmm']<>$data_P['xejmmqr']) {
				//$this->ajaxReturn ( array ('nr' => '新二级密碼兩次輸入不一致!','sf' => 0 ) );
				die("<script>alert('新二级密吗两次输入不一致！');history.back(-1);</script>");
			}elseif ($data_P['yejmm']==$data_P['xejmm']) {
				//$this->ajaxReturn ( array ('nr' => '原二级密碼和新密碼不能相同!','sf' => 0 ) );
				die("<script>alert('原二级密码和新二级密码不能相同！');history.back(-1);</script>");
			} else {
				$addaccount = M ( 'user' )->where ( array (UE_account => $_SESSION ['uname']) )->find ();
	
				if ($addaccount['ue_secpwd']<>md5($data_P['yejmm'])) {
					//$this->ajaxReturn ( array ('nr' => '原二级密碼不正確!','sf' => 0 ) );
					die("<script>alert('原二级密码不正确！');history.back(-1);</script>");
				}else {

					$reg = M ( 'user' )->where ( array ('UE_ID' => $_SESSION ['uid']) )->save (array('UE_secpwd'=>md5($data_P['xejmm'])));						
					if ($reg) {
						//$this->ajaxReturn ( array ('nr' => '修改成功!','sf' => 0 ));
						die("<script>alert('修改成功!');history.back(-1);</script>");
					} else {
						//$this->ajaxReturn ( array ('nr' => '修改失敗!','sf' => 0 ) );
						die("<script>alert('修改失敗！');history.back(-1);</script>");
					}
				}
			}
		}
	}
	
	
	

	
	public function cwmx() {
		
		$user_qb=M('user')->where(array('UE_account'=>$_SESSION['uname']))->field('jl_he,tj_he,UE_money')->find();
	    $this->assign('user_qb',$user_qb);

		$count2l =M('userget')->where(array('UG_account'=>$_SESSION ['uname']/*,'UG_dataType'=>'tjtx'*/))->count (); // 查詢滿足要求的總記錄數

		$p2l = getpage($count2l,10);
		$tjj = M('userget')->where(array('UG_account'=>$_SESSION ['uname']/*,'UG_dataType'=>'tjtx'*/))->order ( 'UG_ID DESC' )->limit ( $p2l->firstRow, $p2l->listRows )->select ();
		
		$this->assign ( 'tjj', $tjj ); // 賦值數據集
		$this->assign ( 'page1', $p2l->show() ); // 賦值分頁輸出
		$this->display('cwmx');
	}
	
	public function tgbz_tx_cl() {
		$settings = include(APP_PATH . 'Home/Conf/settings.php');
		if(I('get.id')<>''){
			$varid=I('get.id');
			$proall = M('user_jj')->where(array('id'=>$varid))->find();
			if(user_jj_zt($varid)=='0'||$proall['zt']=='1'){
				die("<script>alert('转出失败,时间没有大于20天或交易未完成！');history.back(-1);</script>");
			}else{
				
				$lx_he=user_jj_lx($varid);
				
				$user_zq=M('user')->where(array('UE_ID'=>$_SESSION['uid']))->find();
				M('user')->where(array('UE_ID'=>$_SESSION['uid']))->setInc('UE_money',$lx_he);
				$user_xz=M('user')->where(array('UE_ID'=>$_SESSION['uid']))->find();
				M('user_jj')->where(array('id'=>$varid))->save(array('zt'=>'1'));
				
				$note3 = "提供帮助利息";
				$record3 ["UG_account"] = $_SESSION['uname']; // 登入轉出賬戶
				$record3 ["UG_type"] = 'jb';
				$record3 ["UG_allGet"] = $user_zq['ue_money']; // 金幣
				$record3 ["UG_money"] = '+'.$lx_he; //
				$record3 ["UG_balance"] = $user_xz['ue_money']; // 當前推薦人的金幣餘額
				$record3 ["UG_dataType"] = 'tgbz'; // 金幣轉出
				$record3 ["UG_note"] = $note3; // 推薦獎說明
				$record3["UG_getTime"]		= date ( 'Y-m-d H:i:s', time () ); //操作時間
				$reg4 = M ( 'userget' )->add ( $record3 );
				
				
				
				die("<script>alert('提现转出成功.请刷新查看你的账户余额！');history.back(-1);</script>");
				
			}
			
			
		}
	
	}
	
	public function tj_tx(){
		$amount=I('post.amount');
		if($amount<500){		
          $this->ajaxReturn(array('status'=>0,'message'=>'提现金额最低500元'));
		}
		$map1['UE_account']=$_SESSION['uname'];
		$tj_qb=M('user')->where($map1)->getField('tj_he');
        if(floatval($amount)>floatval($tj_qb)){
        	$this->ajaxReturn(array('status'=>0,'message'=>'提现金额不能大于钱包余额'));
        }
        $res=M('user')->where($map1)->setDec('tj_he',floatval($amount));
        if($res){
		    $re=M('user')->where($map1)->setInc('UE_money',floatval($amount));
		    if($re){
		    	$note3 = "推荐奖提现转入静态钱包";
		    	$record3 ["UG_account"] = $_SESSION['uname']; // 登入轉出賬戶
		    	$record3 ["UG_type"] = 'jb';
		    	$record3 ["UG_allGet"] = $user_zq['ue_money']; // 金幣
		    	$record3 ["UG_money"] = '+'.$amount; //
		    	// $record3 ["UG_balance"] = $user_xz['ue_money']; // 當前推薦人的金幣餘額
		    	$record3 ["UG_dataType"] = 'tjtx'; // 金幣轉出
		    	$record3 ["UG_note"] = $note3; // 推薦獎說明
		    	$record3["UG_getTime"]		= date ( 'Y-m-d H:i:s', time () ); //操作時間
		    	$reg4 = M ( 'userget' )->add ( $record3 );



		  //   	$note3 = "推荐奖提现转入";
				// $record2 ["UG_account"] = $_SESSION['uname']; // 登入轉出賬戶
				// $record2 ["UG_type"] = 'jb';
				// $record2 ["UG_allGet"] = $user_zq['ue_money']; // 金幣
				// $record2 ["UG_money"] = '+'.$amount; //
				// // $record3 ["UG_balance"] = $user_xz['ue_money']; // 當前推薦人的金幣餘額
				// $record2 ["UG_dataType"] = 'tjtx'; // 金幣轉出
				// $record2 ["UG_note"] = $note3; // 推薦獎說明
				// $record2["UG_getTime"]		= date ( 'Y-m-d H:i:s', time () ); //操作時間
				// $reg4 = M ( 'userget' )->add ( $record2 );
		    	$this->ajaxReturn(array('status'=>1,'message'=>'提现成功'));
		    }else{
		    	$this->ajaxReturn(array('status'=>0,'message'=>'提现失败'));
		    }
	    }else{
	    	$this->ajaxReturn(array('status'=>0,'message'=>'提现失败'));
	    }
	}
	
	
	public function pin() {
		$User = M ( 'pin' ); // 實例化User對象
		$map1['user']=$_SESSION['uname'];
		$count1 = $User->where ( $map1 )->count (); // 查詢滿足要求的總記錄數
		$p1 = getpage($count1,10);
		$list1 = $User->where ( $map1 )->order ( 'sy_date DESC' )->limit ( $p1->firstRow, $p1->listRows )->select ();
		$this->assign ( 'list1', $list1 ); // 賦值數據集
		$this->assign ( 'page1', $p1->show() ); // 賦值分頁輸出
		// 激活码交易记录
		$map['UG_account']=$_SESSION['uname'];
		$map["UG_type"] = 'mp';
		$map["UG_dataType"] = 'jbzc'; 
		$count = M('userget')->where ( $map)->count (); // 查詢滿足要求的總記錄數
		$p = getpage($count,10);
		$list = M('userget')->where ( $map )->order ( 'UG_ID DESC' )->limit ( $p1->firstRow, $p1->listRows )->select ();
		$this->assign ( 'list1', $list1 ); // 賦值數據集
		$this->assign ( 'page1', $p1->show() ); // 賦值分頁輸出
		$this->assign ( 'list', $list ); // 賦值數據集
		$this->assign ( 'page', $p->show() ); // 賦值分頁輸出
		$this->pin_zs=M('pin')->where ( array('user'=>$_SESSION['uname'],'zt'=>0) )->count ()+0;
		$this->display('pin');
	}
	// 会员激活页面
	public function jihuo(){
		  $list=M('user')->where(array('UE_accName'=>$_SESSION['uname']/*,'UE_status'=>2*/))->field('ue_id,ue_account,ue_regtime,ue_status,liyou,ue_check')->order('UE_status desc')->select();
		  // var_dump($list);
          $this->assign('list',$list);
          $this->display('hyjh');
	}
	public function user_jihuo(){
		$settings = include(APP_PATH . 'Home/Conf/settings.php');
		$id=I('get.id');
		$user_info=M('user')->where(array('UE_ID'=>$id))->field('UE_account,UE_accName,ue_check,liyou')->find();
		if($user_info['ue_check']==0){
            $this->error('该账号尚未被审核，请联系后台管理员审核，审核后才能被激活');
		}elseif($user_info['ue_check']==2){
            $this->error('该账号未审核通过,未通过理由：'.$user_info['liyou'].'请完善信息后重新注册','',6);
		}
		$pin_id=M('pin')->where(array('user'=>$user_info['ue_accname'],'zt'=>0))->getField('id');
		if(!$pin_id){
			$this->error('激活码不足，请联系管理员购买激活码！');
		}else{
		    M('user')->where(array('UE_ID'=>$id))->setField('UE_status',0);
		    vendor("Sendsms.sendsms");
					$send = new \Sendsms();
					if ($post_data['email']) $mes =$send->my_send($user_info['ue_accname'], "亲爱的会员，您的账号已激活成功，请登录平台完善个人信息！【民生互助】");
		    $data=array(
                'zt'=>1,
                'sy_date'=>date ( 'Y-m-d H:i:s', time () ),
                'sy_user'=>$user_info['ue_account']
		    	);
		    $re=M('pin')->where(array('id'=>$pin_id))->save($data);
		    if($re){
		    	 $tjr_name=M('user')->where(array('UE_ID'=>$id))->getField('UE_accName');
                 M('user')->where(array('UE_account' => $tjr_name ))->setInc('tj_he',floatval($settings['tjj']));
                 $note3 = "推荐奖转入";
                 $record3 ["UG_account"] = $user_info['ue_accname']; // 登入轉出賬戶
                 $record3 ["UG_type"] = 'tjj';
                 $record3['UG_money']='+'.$settings['tjj'];
                 $record3 ["UG_dataType"] = 'tjj'; // 金幣轉出
                 $record3 ["UG_note"] = $note3; // 推薦獎說明
                 $record3["UG_getTime"]      = date ( 'Y-m-d H:i:s', time () ); //操作時間
                 $reg4 = M ( 'userget' )->add ( $record3 );
                 if($reg4){
					if ($post_data['email']) $mess =$send->my_send($user_info['ue_accname'], "亲爱的会员，您推荐的用户已激活成功，推荐奖励已发，请注意查收！【民生互助】");
                 }
		    	 $this->success('激活成功');
		    }else{
		    	$this->error('激活失败');
		    }
	    }
	}
	public function aab() {

		
		
		$arr = array(1,2,3,4,5,6,7,8,9,10);
		$p=0;
		$tj=count($arr);
		
		//$tj1=$tj;
		//$bba=array_slice($arr,0,1);
		//dump($bba);
		//die;
		//0,4
		
	
		for ($m=0;$m<$tj;$m++){
				
		
			for ($p=2;$p+$m<$tj;$p++){
				if($tj-$m<$p){break;}//1,4  5
				$bba=array_slice($arr,$m,2);
				
				//echo $arr[$p].'</br>';
				$bba[]=$arr[$p+$m];
				
				foreach ($bba as $var)
					echo $var.'+';
				
				//dump($bba);
				echo '='.array_sum($bba).'<br/>';
				//$bba=array();
			}
			//$tj1--;
			//$a=
			//$tj2=$tj1-1;
			//echo '------------<br>';
				
				
		}
		
		
		//die;
		
		
		
		for ($m=0;$m<$tj;$m++){
			

			for ($p=2;$p<=$tj;$p++){
				if($tj-$m<$p){break;}//1,4  5
		        $bba=array_slice($arr,$m,$p);
		       // dump($bba);
		        foreach ($bba as $var)
		        	echo $var.'+';
		        
		        echo '='.array_sum($bba).'<br/>';
		        //$bba=array();
			}
			//$tj1--;
			//$a= 
			//$tj2=$tj1-1;
			//echo '------------<br>';
			
			
		}
		
		
		die;

		sort($arr); //保证初始数组是有序的
		$last = count($arr) - 1; //$arr尾部元素下标
		$x = $last;
		$count = 1; //组合个数统计
		echo implode(',', $arr), "\n"; //输出第一种组合
		echo "<br/>";
		while (true) {
		$y = $x--; //相邻的两个元素
		if ($arr[$x] < $arr[$y]) { //如果前一个元素的值小于后一个元素的值
		$z = $last;
		while ($arr[$x] > $arr[$z]) { //从尾部开始，找到第一个大于 $x 元素的值
		$z--;
		}
		/* 交换 $x 和 $z 元素的值 */
		list($arr[$x], $arr[$z]) = array($arr[$z], $arr[$x]);
		/* 将 $y 之后的元素全部逆向排列 */
		for ($i = $last; $i > $y; $i--, $y++) {
		list($arr[$i], $arr[$y]) = array($arr[$y], $arr[$i]);
		}
		 echo implode(',', $arr), "\n"; //输出组合
 echo "<br/>";
		$x = $last;
		$count++;
		}
		if ($x == 0) { //全部组合完毕
		break;
		}
		}
		echo '组合个数： ', $count, "\n";
		//输出结果为：3628800个
		
		
		die;

		
		$xypipeije=16;
		$data=array(1,2,3,4,5,6,7,8);
		$tj=count($data);
		$sf_tcpp='0';
		
		for ($m=0;$m<$tj;$m++){
			
			for ($p=0;$p<$tj-$m;$p++){
			$data1[$m][$p]=$data[$m];

			}
		}
		$adsfdsaf=$data1[0];
		dump($adsfdsaf);die;
		
		for ($v=0;$v<$tj;$v++){
			
			for ($c=0;$c<$tj;$c++){
		        echo $data[$v]+$data[$c+1].'<br>';
		
			}
		}
		
		die;
		
		
        for ($b=0;$b<$tj;$b++){
			
			
			
			
			
			
			if($sf_tcpp=='1'){break;}
			$tj_j=$tj-1;
			echo '===========<br>';
			for ($i=0;$i<$tj;$i++){
				if($b<$i){
					$pipeihe=$data[$b]+$data[$tj_j];
					if($pipeihe==$xypipeije){
						echo $data[$b].'+'.$data[$tj_j].'<br>';$sf_tcpp='1';break;
					}
		
						
						
					$tj_j--;
				}
			}
		}
	
		
		
		
		
		
		
	
	}
	
	
	
}