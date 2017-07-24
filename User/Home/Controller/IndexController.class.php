<?php

namespace Home\Controller;

use Think\Controller;

class IndexController extends CommonController {
	// 首页
	public function index() {
	
		$this->redirect('/Home/Index/home');
	}
	
  public function uploadify()
    {
        if (!empty($_FILES)) {
            //图片上传设置
            $config = array(   
                'maxSize'    =>    3145728, 
                'savePath'   =>    '',  
                'saveName'   =>    array('uniqid',''), 
                'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),  
                'autoSub'    =>    true,   
                'subName'    =>    array('date','Ymd'),
            );
            $upload = new \Think\Upload($config);// 实例化上传类
            $images = $upload->upload();
            //判断是否有图
            if($images){
                $info=$images['file']['savepath'].$images['file']['savename'];
                //返回文件地址和名给JS作回调用
                echo $info;
            }
            else{
                //$this->error($upload->getError());//获取失败信息
            }
        }
    }
	// public function qiandao(){
	// 	$User = M ( 'qiandao' ); // 实例化User对象
	// 	$data['UE_account']=$_SESSION['uname'];
	// 	$data['time']=date('Y-m-d H:i:s');
	// 	$data['money']=rand(1,10);
	// 	$re=$User->add($data);
	// 	if($re){
	// 		 die("<script>alert('签到成功,获得".$data['money']."金币!');history.back(-1);</script>");
	// 	}
	// }
	public function jyxx(){
         $User = M ( 'tgbz' ); // 实例化User对象
		
		$map['user']=$_SESSION['uname'];
		$map['zt']=0;
		$count = $User->where ( $map )->count (); // 查询满足要求的总记录数
		//$page = new \Think\Page ( $count, 3 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
		
		$p = getpage($count,100);
		
		$list = $User->where ( $map )->order ( 'id DESC' )->limit ( $p->firstRow, $p->listRows )->select ();
		$this->assign ( 'list', $list ); // 赋值数据集
		$this->assign ( 'page', $p->show() ); // 赋值分页输出
		/////////////////----------------
		//////////////////----------
		// $User = M ( 'jsbz' ); // 实例化User对象
		
		$map1['user']=$_SESSION['uname'];
		$map1['zt']=0;
		$count1 = M ( 'jsbz' )->where ( $map1 )->count (); // 查询满足要求的总记录数
		//$page = new \Think\Page ( $count, 3 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
		
		$p1 = getpage($count1,100);
		
		$list1 = M ( 'jsbz' )->where ( $map1 )->order ( 'id DESC' )->limit ( $p1->firstRow, $p1->listRows )->select ();
		$this->assign ( 'list1', $list1 ); // 赋值数据集
		$this->assign ( 'page1', $p1->show() ); // 赋值分页输出
		/////////////////----------------
		//////////////////----------
		$User = M ( 'ppdd' ); // 实例化User对象
		
		$map2['p_user']=$_SESSION['uname'];
		$map3['zt']=array('neq',2);
		$count2 = $User->where ( $map2 )->count (); // 查询满足要求的总记录数
		//$page = new \Think\Page ( $count, 3 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
		
		$p2 = getpage($count2,100);
		
		$list2 = $User->where ( $map2 )->order ( 'id DESC' )->limit ( $p2->firstRow, $p2->listRows )->select ();
		$this->assign ( 'list2', $list2 ); // 赋值数据集
		$this->assign ( 'page2', $p2->show() ); // 赋值分页输出
		/////////////////----------------
		$mp['UE_account'] = $_SESSION['uname'];
		$pd = M('user')->where($mp)->find();
		$pdb = $pd['ue_pdb'];
		$this->assign("pdb",$pdb);
		
		//////////////////----------
		$User = M ( 'ppdd' ); // 实例化User对象
		
		$map3['g_user']=$_SESSION['uname'];
		$map3['zt']=array('neq',2);
		$count3 = $User->where ( $map3 )->count (); // 查询满足要求的总记录数
		//$page = new \Think\Page ( $count, 3 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
		
		$p3 = getpage($count3,100);
		
		$list3 = $User->where ( $map3 )->order ( 'id DESC' )->limit ( $p3->firstRow, $p3->listRows )->select ();
		$this->assign ( 'list3', $list3 ); // 赋值数据集
		$this->assign ( 'page3', $p3->show() ); // 赋值分页输出
		$this->display();
	}
	// 已回复留言详情
	public function messageinfo(){
	    $id=I('get.id');
	    $info = M ( 'message' )->where ( array ('MA_ID' => $id) )->find ();
	    $this->assign('info',$info);
		$this->display('index/messageinfo');
	}
	
	public function home() {
		$num = auto_match();		
			 
		$user = $_SESSION['uname'];
		
		$uglist=M('userget')->field('UG_ID,UG_money')->where(array('UG_account'=>$user,'UG_dataType'=>'jlj'))->select();
		foreach($uglist as $v){			
			$money=(int)substr($v['ug_money'],1,1);
			 if($money == 0){
				M('userget')->where(array('UG_ID'=>$v['ug_id']))->delete();
			}
		}
		$jjd['user']=$user;
		$jjd['zt']=array('neq',1);
		$userjl=M('user_jj')->field('id,tgbz_id')->where($jjd)->select();
		foreach($userjl as $v){
			$t=M('tgbz')->where(array('id'=>$v['tgbz_id']))->find();			
			if(empty($t)){
			    M('user_jj')->where(array('id'=>$v['id']))->delete();				
			}
		} 
		
	
		
		
		
		
		//die;
		//////////////////----------
		$User = M ( 'tgbz' ); // 实例化User对象
		
		$map['user']=$_SESSION['uname'];
		$map['zt']=0;
		$count = $User->where ( $map )->count (); // 查询满足要求的总记录数
		//$page = new \Think\Page ( $count, 3 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
		
		$p = getpage($count,100);
		
		$list = $User->where ( $map )->order ( 'id DESC' )->limit ( $p->firstRow, $p->listRows )->select ();
		$this->assign ( 'list', $list ); // 赋值数据集
		$this->assign ( 'page', $p->show() ); // 赋值分页输出
		/////////////////----------------
		//////////////////----------
		// $User = M ( 'jsbz' ); // 实例化User对象
		
		$map1['user']=$_SESSION['uname'];
		$map1['zt']=0;
		$count1 = M ( 'jsbz' )->where ( $map1 )->count (); // 查询满足要求的总记录数
		//$page = new \Think\Page ( $count, 3 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
		
		$p1 = getpage($count1,100);
		
		$list1 = M ( 'jsbz' )->where ( $map1 )->order ( 'id DESC' )->limit ( $p1->firstRow, $p1->listRows )->select ();
		$this->assign ( 'list1', $list1 ); // 赋值数据集
		$this->assign ( 'page1', $p1->show() ); // 赋值分页输出
		/////////////////----------------
		//////////////////----------
		$User = M ( 'ppdd' ); // 实例化User对象
		
		$map2['p_user']=$_SESSION['uname'];
		$map3['zt']=array('neq',2);
		$count2 = $User->where ( $map2 )->count (); // 查询满足要求的总记录数
		//$page = new \Think\Page ( $count, 3 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
		
		$p2 = getpage($count2,100);
		
		$list2 = $User->where ( $map2 )->order ( 'id DESC' )->limit ( $p2->firstRow, $p2->listRows )->select ();
		$this->assign ( 'list2', $list2 ); // 赋值数据集
		$this->assign ( 'page2', $p2->show() ); // 赋值分页输出
		/////////////////----------------
		$mp['UE_account'] = $_SESSION['uname'];
		$pd = M('user')->where($mp)->find();
		$pdb = $pd['ue_pdb'];
		$this->assign("pdb",$pdb);
		
		//////////////////----------
		$User = M ( 'ppdd' ); // 实例化User对象
		
		$map3['g_user']=$_SESSION['uname'];
		$map3['zt']=array('neq',2);
		$count3 = $User->where ( $map3 )->count (); // 查询满足要求的总记录数
		//$page = new \Think\Page ( $count, 3 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
		
		$p3 = getpage($count3,100);
		
		$list3 = $User->where ( $map3 )->order ( 'id DESC' )->limit ( $p3->firstRow, $p3->listRows )->select ();
		$this->assign ( 'list3', $list3 ); // 赋值数据集
		$this->assign ( 'page3', $p3->show() ); // 赋值分页输出
		/////////////////----------------
		
		
		
		
		$this->display ( 'home' );
	}
	// //爱区
	// public function shequ(){
	// 	$this->display('shequ');
	// }
	
	
	// 注册模块
	public function reg() {
		//////////////////----------
		$User = M ( 'user' ); // 实例化User对象
		
		$qrcode = prcode_create('reg',"http://".$_SERVER['SERVER_NAME']."/Home/Login/register?phone=".$this->userData['ue_account']);
        //http://www.hanputun.com/index.php/Home/Login/register.html?15538275590
	
			$map['zcr']=$_SESSION['uname'];
		$count = $User->where ( $map )->count (); // 查询满足要求的总记录数
		//$page = new \Think\Page ( $count, 3 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
		
		$p = getpage($count,20);
		
		$list = $User->where ( $map )->order ( 'UE_ID DESC' )->limit ( $p->firstRow, $p->listRows )->select ();
		$this->assign ( 'list', $list ); // 赋值数据集
		$this->assign ( 'page', $p->show() ); // 赋值分页输出
		/////////////////----------------
		
		$this->email=sprintf("%0".strlen(9)."d", mt_rand(0,99999999999)).'@qq.com';
		
		$pin1=M('pin')->where(array('user'=>$_SESSION['uname'],'zt'=>'0'))->find();
		//dump($pin1);die;
		$this->moren = $_SESSION ['uname'];
		$this->assign('qrcode',$qrcode);
		$this->assign('pin1',$pin1);
		$this->display ( 'reg' );
	}
	
	// 添加会员
	public function regadd() {
		$dqzhxx=M('user')->where(array('UE_account'=>$_SESSION['uname']))->find();
		/* if($dqzhxx['sfjl']==0){
			die("<script>alert('您不是经理,不可注册会员!');history.back(-1);</script>");
		}else{ */
			$data_P = I ( 'post.' );
			
			$acco=M('user')->where(array('UE_account'=>$data_P['email']))->count();
			$uname = M('user')->where(array('UE_account'=>$data_P ['pemail']))->find();			
			if(empty($uname)){
				die("<script>alert('无效的直属上级！');history.back(-1);</script>");
			}
			if($acco>0){
				die("<script>alert('帐号已被注册！');history.back(-1);</script>");
			}
			$pcco=M('user')->where(array('UE_phone'=>$data_P['phone']))->count();
			$zcco=M('user')->where(array('UE_sfz'=>$data_P['sfz']))->count();
			if($pcco>0){
				die("<script>alert('该手机已被注册！');history.back(-1);</script>");
			}
			/* if($zcco>0){
				die("<script>alert('该身份证号已被注册！');history.back(-1);</script>");
			} */
			/* if(!$data_P ['zfb'] || !$data_P['yhmc']||!$data_P ['yhzh']){
				die("<script>alert('请填写完整的信息！');history.back(-1);</script>");
			} */
			
			//$this->ajaxReturn( $data_P ['account1']);
			$data_arr ["UE_account"] = $data_P ['email'];
			$data_arr ["UE_account1"] = $data_P ['email_repeat'];
			$data_arr ["UE_accName"] = $data_P ['pemail'];
			$data_arr ["UE_accName1"] = $data_P ['pemail_repeat'];
			$data_arr ["UE_theme"] = $data_P ['username'];
			$data_arr ["UE_password"] = $data_P ['password'];
			$data_arr ["UE_repwd"] = $data_P ['password2'];
			$data_arr ["pin"] = $data_P ['code'];
			$data_arr ["pin2"] = $data_P ['code2'];
			$data_arr ["UE_secpwd"] = $data_P ['ejmm'];
			$data_arr ["UE_resecpwd"] = $data_P ['ejmm2'];
			
			$data_arr ["UE_status"] = '0'; // 用户状态
			$data_arr ["UE_level"] = '0'; // 用户等级
			$data_arr ["UE_check"] = '0'; // 是否通过验证
			
			//$data_arr ["UE_sfz"] = $data_P ['sfz'];
			$data_arr ["UE_truename"] = $data_P ['username'];
			//$data_arr ["UE_qq"] = $data_P ['qq'];
			$data_arr ["UE_phone"] = $data_P ['phone'];
			//$data_arr ["email"] = $data_P ['email'];
			$data_arr ["UE_regIP"] = I ( 'post.ip' );
			$data_arr ["zcr"] = $_SESSION['uname'];
			$data_arr ["UE_regTime"] = date ( 'Y-m-d H:i:s', time () );
			$data_arr ["UE_regTime1"] = date ( 'Y-m-d H:i:s', time () );
			//$data_arr ["__hash__"] = $data_P ['__hash__'];
			//$this->ajaxReturn($data_arr ["UE_theme"]);die;
			$data_arr ["zfb"] = $data_P ['zfb'];
			$data_arr ["weixin"] = $data_P ['weixin'];
			$data_arr ["yhmc"] = $data_P ['yhmc'];
			$data_arr ["yhzh"] = $data_P ['yhzh'];
			
			$data = D ( User );
			
			
			//dump($data_arr);die;
			
			 
			if ($data->create ( $data_arr )) {
				
				if(I ( 'post.ty' )<>'ye'){
					die("<script>alert('请先勾选,我已完全了解所有风险!');history.back(-1);</script>");
				}else{
				
				if ($data->add ()) {
					//
				if(M('pin')->where(array('pin'=>$data_P ['code']))->save(array('zt'=>'1','sy_user'=>$data_P ['email'],'sy_date'=>date ( 'Y-m-d H:i:s', time () )))){

					//jlsja($data_P ['pemail']);

					die("<script>alert('注册成功!');window.location.href='/Home/Index/reg/';</script>");
					}else{
					    die("<script>alert('注册会员失败,继续注册请刷新页面!');history.back(-1);</script>");
					}
				} else {
				
					die("<script>alert('注册会员失败,继续注册请刷新页面!');history.back(-1);</script>");
		
				}
				}
			} else {
				//$this->success( );
				die("<script>alert('".$data->getError ()."');history.back(-1);</script>");
				//$this->ajaxReturn( array('nr'=>,'sf'=>0) );
			}
		
	}
	
	//注册并激活后72小时内不排单进场自动冻结账号

	public function reg2() {
	
			$this->data_P = I ( 'get.' );
			$this->display('reg2');
			
	}
	
	
	// 新闻列表页
	public function news() {
		$User = M ( 'info' ); // 实例化User对象
		$count = $User->where ( array (
				'IF_type' => 'news' 
		) )->count (); // 查询满足要求的总记录数
		$page = new \Think\Page ( $count, 20 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
		                                       
		// $page->lastSuffix=false;
		$page->setConfig ( 'header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录    第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>' );
		$page->setConfig ( 'prev', '上一页' );
		$page->setConfig ( 'next', '下一页' );
		$page->setConfig ( 'last', '末页' );
		$page->setConfig ( 'first', '首页' );
		$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
		;
		
		$show = $page->show (); // 分页显示输出
		                        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $User->where ( array (
				'IF_type' => 'news' 
		) )->order ( 'IF_ID DESC' )->limit ( $page->firstRow . ',' . $page->listRows )->select ();
		$this->assign ( 'list', $list ); // 赋值数据集
		$this->assign ( 'page', $show ); // 赋值分页输出
		
		
		
		
		//////////////////----------
		$User = M ( 'info' ); // 实例化User对象
		
		$map1['IF_type']='bzzx';
		$count1 = $User->where ( $map1 )->count (); // 查询满足要求的总记录数
		//$page = new \Think\Page ( $count, 3 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
		
		$p1 = getpage($count1,100);
		
		$list1 = $User->where ( $map1 )->order ( 'IF_ID DESC' )->limit ( $p1->firstRow, $p1->listRows )->select ();
		$this->assign ( 'list1', $list1 ); // 赋值数据集
		$this->assign ( 'page1', $p1->show() ); // 赋值分页输出
		
		
		
		
		$this->display ( 'news' ); // 输出模板
	}
	// 新闻内页
	public function newsPage() {
		header ( "Content-Type:text/html; charset=utf-8" );
		$id = I ( 'id' );
		$data = M ( 'info' )->where ( array (
				'IF_ID' => $id 
		) )->find ();
		$this->data = $data;
		$this->display ( 'news_xx' );
	}
	// 帮助中心
	public function helpCenter() {
		header ( "Content-Type:text/html; charset=utf-8" );
		$User = M ( 'infoweb' ); // 实例化User对象
		$count = $User->where ( array (
				'IW_type' => 'bzzx' 
		) )->count (); // 查询满足要求的总记录数
		$page = new \Think\Page ( $count, 20 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
		                                       
		// $page->lastSuffix=false;
		$page->setConfig ( 'header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录    第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>' );
		$page->setConfig ( 'prev', '上一页' );
		$page->setConfig ( 'next', '下一页' );
		$page->setConfig ( 'last', '末页' );
		$page->setConfig ( 'first', '首页' );
		$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
		;
		
		$show = $page->show (); // 分页显示输出
		                        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $User->where ( array (
				'IW_type' => 'bzzx' 
		) )->order ( 'IW_ID DESC' )->limit ( $page->firstRow . ',' . $page->listRows )->select ();
		$this->assign ( 'list', $list ); // 赋值数据集
		$this->assign ( 'page', $show ); // 赋值分页输出
		$this->display ( 'bzzx' ); // 输出模板
	}
	// 帮助中心内页
	public function helpCenterPage() {
		header ( "Content-Type:text/html; charset=utf-8" );
		$id = I ( 'id' );
		$data = M ( 'infoweb' )->where ( array (
				'IW_ID' => $id 
		) )->find ();
		$this->data = $data;
		$this->display ( 'bzzx_xx' );
	}
	// 新手入门
	public function novice() {
		header ( "Content-Type:text/html; charset=utf-8" );
		$data = M ( 'infoweb' )->where ( array (
				'IW_ID' => 11 
		) )->find ();
		$this->data = $data;
		$this->display ( 'bzzx_xx' );
	}
	// 安全中心
	public function safe() {
		
		$this->mbzt = M ( 'user' )->where ( array (UE_account => $_SESSION ['uname']) )->find ();
		
		$this->display ( 'zhaq' );
	}
	// 一键收币
	
	// 金币明细
	public function jbmx() {
		header ( "Content-Type:text/html; charset=utf-8" );
		$User = M ( 'userget' ); // 实例化User对象
		$date1 = I ( 'post.date1', '', '/^(?:(?!0000)[0-9]{4}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-8])|(?:0[13-9]|1[0-2])-(?:29|30)|(?:0[13578]|1[02])-31)|(?:[0-9]{2}(?:0[48]|[2468][048]|[13579][26])|(?:0[48]|[2468][048]|[13579][26])00)-02-29)$/' );
		$date2 = I ( 'post.date2', '', '/^(?:(?!0000)[0-9]{4}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-8])|(?:0[13-9]|1[0-2])-(?:29|30)|(?:0[13578]|1[02])-31)|(?:[0-9]{2}(?:0[48]|[2468][048]|[13579][26])|(?:0[48]|[2468][048]|[13579][26])00)-02-29)$/' );
		$map ['UG_account'] = $_SESSION ['uname'];
		$map ['UG_type'] = 'jb';
		//$map ['UG_dataType'] = array('IN',array('mrfh','tjj','kdj','mrldj','glj'));
		
		if (! empty ( $date1 ) && ! empty ( $date2 )) {
			$map ['UG_getTime'] = array (
					array (
							'gt',
							$date1 
					),
					array (
							'lt',
							$date2 
					),
					'and' 
			);
		}
		$count = $User->where ( $map )->count (); // 查询满足要求的总记录数
		$page = new \Think\Page ( $count, 12 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
		                                        
		// $page->lastSuffix=false;
		$page->setConfig ( 'header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录    第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>' );
		$page->setConfig ( 'prev', '上一页' );
		$page->setConfig ( 'next', '下一页' );
		$page->setConfig ( 'last', '末页' );
		$page->setConfig ( 'first', '首页' );
		$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
		;
		
		$show = $page->show (); // 分页显示输出
		                        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $User->where ( $map )->order ( 'UG_ID DESC' )->limit ( $page->firstRow . ',' . $page->listRows )->select ();
		$this->assign ( 'list', $list ); // 赋值数据集
		$this->assign ( 'page', $show ); // 赋值分页输出
		
		
		$ztj1 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'tjj'))->sum('UG_money');
		$ztj2 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'tjj'))->sum('UG_integral');
		$this->ztj = $ztj1+$ztj2;
		
		
		$bdj1 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'kdj'))->sum('UG_money');
		$bdj2 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'kdj'))->sum('UG_integral');
		$this->bdj = $bdj1+$bdj2;
		
		$fhj1 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'mrfh'))->sum('UG_money');
		$fhj2 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'mrfh'))->sum('UG_integral');
		$this->fhj = $fhj1+$fhj2;
		
		$ldj1 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'mrldj'))->sum('UG_money');
		$ldj2 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'mrldj'))->sum('UG_integral');
		$this->ldj = $ldj1+$ldj2;
		
		
		$glj1 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'glj'))->sum('UG_money');
		$glj2 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'glj'))->sum('UG_integral');
		$this->glj = $glj1+$glj2;
		
		
		
		
		$this->display ( 'jbmx' ); // 输出模板
	}
	
	public function ybmx() {
		header ( "Content-Type:text/html; charset=utf-8" );
		$User = M ( 'userget' ); // 实例化User对象
		$date1 = I ( 'post.date1', '', '/^(?:(?!0000)[0-9]{4}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-8])|(?:0[13-9]|1[0-2])-(?:29|30)|(?:0[13578]|1[02])-31)|(?:[0-9]{2}(?:0[48]|[2468][048]|[13579][26])|(?:0[48]|[2468][048]|[13579][26])00)-02-29)$/' );
		$date2 = I ( 'post.date2', '', '/^(?:(?!0000)[0-9]{4}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-8])|(?:0[13-9]|1[0-2])-(?:29|30)|(?:0[13578]|1[02])-31)|(?:[0-9]{2}(?:0[48]|[2468][048]|[13579][26])|(?:0[48]|[2468][048]|[13579][26])00)-02-29)$/' );
		$map ['UG_account'] = $_SESSION ['uname'];
		$map ['UG_type'] = 'yb';
		//$map ['UG_dataType'] = array('IN',array('mrfh','tjj','kdj','mrldj','glj'));
	
		if (! empty ( $date1 ) && ! empty ( $date2 )) {
			$map ['UG_getTime'] = array (
					array (
							'gt',
							$date1
					),
					array (
							'lt',
							$date2
					),
					'and'
			);
		}
		$count = $User->where ( $map )->count (); // 查询满足要求的总记录数
		$page = new \Think\Page ( $count, 12 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
	
		// $page->lastSuffix=false;
		$page->setConfig ( 'header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录    第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>' );
		$page->setConfig ( 'prev', '上一页' );
		$page->setConfig ( 'next', '下一页' );
		$page->setConfig ( 'last', '末页' );
		$page->setConfig ( 'first', '首页' );
		$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
		;
	
		$show = $page->show (); // 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $User->where ( $map )->order ( 'UG_ID DESC' )->limit ( $page->firstRow . ',' . $page->listRows )->select ();
		$this->assign ( 'list', $list ); // 赋值数据集
		$this->assign ( 'page', $show ); // 赋值分页输出
	
	
		$ztj1 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'tjj'))->sum('UG_money');
		$ztj2 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'tjj'))->sum('UG_integral');
		$this->ztj = $ztj1+$ztj2;
	
	
		$bdj1 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'kdj'))->sum('UG_money');
		$bdj2 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'kdj'))->sum('UG_integral');
		$this->bdj = $bdj1+$bdj2;
	
		$fhj1 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'mrfh'))->sum('UG_money');
		$fhj2 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'mrfh'))->sum('UG_integral');
		$this->fhj = $fhj1+$fhj2;
	
		$ldj1 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'mrldj'))->sum('UG_money');
		$ldj2 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'mrldj'))->sum('UG_integral');
		$this->ldj = $ldj1+$ldj2;
	
	
		$glj1 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'glj'))->sum('UG_money');
		$glj2 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'glj'))->sum('UG_integral');
		$this->glj = $glj1+$glj2;
	
	
	
	
		$this->display ( 'ybmx' ); // 输出模板
	}
	
	public function zsbmx() {
		header ( "Content-Type:text/html; charset=utf-8" );
		$User = M ( 'userget' ); // 实例化User对象
		$date1 = I ( 'post.date1', '', '/^(?:(?!0000)[0-9]{4}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-8])|(?:0[13-9]|1[0-2])-(?:29|30)|(?:0[13578]|1[02])-31)|(?:[0-9]{2}(?:0[48]|[2468][048]|[13579][26])|(?:0[48]|[2468][048]|[13579][26])00)-02-29)$/' );
		$date2 = I ( 'post.date2', '', '/^(?:(?!0000)[0-9]{4}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-8])|(?:0[13-9]|1[0-2])-(?:29|30)|(?:0[13578]|1[02])-31)|(?:[0-9]{2}(?:0[48]|[2468][048]|[13579][26])|(?:0[48]|[2468][048]|[13579][26])00)-02-29)$/' );
		$map ['UG_account'] = $_SESSION ['uname'];
		$map ['UG_type'] = 'zsb';
		//$map ['UG_dataType'] = array('IN',array('mrfh','tjj','kdj','mrldj','glj'));
	
		if (! empty ( $date1 ) && ! empty ( $date2 )) {
			$map ['UG_getTime'] = array (
					array (
							'gt',
							$date1
					),
					array (
							'lt',
							$date2
					),
					'and'
			);
		}
		$count = $User->where ( $map )->count (); // 查询满足要求的总记录数
		$page = new \Think\Page ( $count, 12 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
	
		// $page->lastSuffix=false;
		$page->setConfig ( 'header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录    第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>' );
		$page->setConfig ( 'prev', '上一页' );
		$page->setConfig ( 'next', '下一页' );
		$page->setConfig ( 'last', '末页' );
		$page->setConfig ( 'first', '首页' );
		$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
		;
	
		$show = $page->show (); // 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $User->where ( $map )->order ( 'UG_ID DESC' )->limit ( $page->firstRow . ',' . $page->listRows )->select ();
		$this->assign ( 'list', $list ); // 赋值数据集
		$this->assign ( 'page', $show ); // 赋值分页输出
	
	
		$ztj1 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'tjj'))->sum('UG_money');
		$ztj2 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'tjj'))->sum('UG_integral');
		$this->ztj = $ztj1+$ztj2;
	
	
		$bdj1 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'kdj'))->sum('UG_money');
		$bdj2 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'kdj'))->sum('UG_integral');
		$this->bdj = $bdj1+$bdj2;
	
		$fhj1 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'mrfh'))->sum('UG_money');
		$fhj2 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'mrfh'))->sum('UG_integral');
		$this->fhj = $fhj1+$fhj2;
	
		$ldj1 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'mrldj'))->sum('UG_money');
		$ldj2 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'mrldj'))->sum('UG_integral');
		$this->ldj = $ldj1+$ldj2;
	
	
		$glj1 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'glj'))->sum('UG_money');
		$glj2 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'glj'))->sum('UG_integral');
		$this->glj = $glj1+$glj2;
	
	
	
	
		$this->display ( 'zsbmx' ); // 输出模板
	}
	
	
	// 奖金明细
	public function jjjl() {
		header ( "Content-Type:text/html; charset=utf-8" );
		$User = M ( 'userget' ); // 实例化User对象
		$date1 = I ( 'post.date1', '', '/^(?:(?!0000)[0-9]{4}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-8])|(?:0[13-9]|1[0-2])-(?:29|30)|(?:0[13578]|1[02])-31)|(?:[0-9]{2}(?:0[48]|[2468][048]|[13579][26])|(?:0[48]|[2468][048]|[13579][26])00)-02-29)$/' );
		$date2 = I ( 'post.date2', '', '/^(?:(?!0000)[0-9]{4}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-8])|(?:0[13-9]|1[0-2])-(?:29|30)|(?:0[13578]|1[02])-31)|(?:[0-9]{2}(?:0[48]|[2468][048]|[13579][26])|(?:0[48]|[2468][048]|[13579][26])00)-02-29)$/' );
		$map ['UG_account'] = $_SESSION ['uname'];
		$map ['UG_dataType'] = array('IN',array('mrfh','tjj','tjj1','tjj2','tjj3','bdj','mrldj'));
	
		if (! empty ( $date1 ) && ! empty ( $date2 )) {
			$map ['UG_getTime'] = array (
					array (
							'gt',
							$date1
					),
					array (
							'lt',
							$date2
					),
					'and'
			);
		}
		
		//$map  = array('tjj','kdj','glj');
		//	$map['UE_Faccount']  = $_SESSION ['uname']
		//$ljtc1 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>array('IN',$map)))->sum('UG_money');
		
		$count = $User->where ( $map )->count (); // 查询满足要求的总记录数
		$page = new \Think\Page ( $count, 12 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
	
		// $page->lastSuffix=false;
		$page->setConfig ( 'header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录    第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>' );
		$page->setConfig ( 'prev', '上一页' );
		$page->setConfig ( 'next', '下一页' );
		$page->setConfig ( 'last', '末页' );
		$page->setConfig ( 'first', '首页' );
		$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
		;
	
		$show = $page->show (); // 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $User->where ( $map )->order ( 'UG_ID DESC' )->limit ( $page->firstRow . ',' . $page->listRows )->select ();
		$this->assign ( 'list', $list ); // 赋值数据集
		$this->assign ( 'page', $show ); // 赋值分页输出
		
		
// 		$ztj1 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'tjj'))->sum('UG_money');
// 		$ztj2 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'tjj'))->sum('UG_integral');
// 		$this->ztj = $ztj1+$ztj2;
		
		
// 		$bdj1 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'kdj'))->sum('UG_money');
// 		$bdj2 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'kdj'))->sum('UG_integral');
// 		$this->bdj = $bdj1+$bdj2;
		
// 		$fhj1 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'mrfh'))->sum('UG_money');
// 		$fhj2 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'mrfh'))->sum('UG_integral');
// 		$this->fhj = $fhj1+$fhj2;
		
// 		$ldj1 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'mrldj'))->sum('UG_money');
// 		$ldj2 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'mrldj'))->sum('UG_integral');
// 		$this->ldj = $ldj1+$ldj2;
		
		
// 		$glj1 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'glj'))->sum('UG_money');
// 		$glj2 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'glj'))->sum('UG_integral');
// 		$this->glj = $glj1+$glj2;
		
		
		
		$this->display ( 'jjjl' ); // 输出模板
	}
	
	// 金币转账
	public function jbzz() {
		header ( "Content-Type:text/html; charset=utf-8" );
		$userData = M ( 'user' )->where ( array (
				'UE_account' => $_SESSION ['uname'] 
		) )->find ();
		$this->userData = $userData;
		$this->display ( 'jbzz' );
	}
	// 金币转账处理
public function jbzzcl() {
		if (IS_POST) {
			$pin_zs=M('pin')->where ( array('user'=>$_SESSION['uname'],'zt'=>0) )->count ();
			$data_P = I ( 'post.' );
			//$user = M ( 'user' )->where ( array (UE_account => $_SESSION ['uname']) )->find ();
			//$user1 = M ();
			$user_df = M ( 'user' )->where ( array (UE_account => $data_P['user']) )->find ();
			//! $this->check_verify ( I ( 'post.yzm' ) )
			//! $user1->autoCheckToken ( $_POST )
			$userxx=M('user')->where(array('UE_account'=>$_SESSION['uname']))->find();
				
			//dump($userxx);die;
				
			//$userxx['ue_secpwd']<>md5($data_P['ejmm'])
			if(false){
				die("<script>alert('二级密码输入有误！');history.back(-1);</script>");
			}else{
			
			
			$jbhe=$data_P ['sh'];
			if (! preg_match ( '/^[0-9]{1,10}$/', $data_P ['sh'] )||!$data_P ['sh']>0) {
				die("<script>alert('数量输入有勿！');history.back(-1);</script>");
			} elseif ($pin_zs < $jbhe) {
				die("<script>alert('激活码不足！');history.back(-1);</script>");
			}elseif (!$user_df) {
				die("<script>alert('对方账号不存在！');history.back(-1);</script>");
			}/*elseif ($user_df['sfjl']=='0') {
				die("<script>alert('对方不是经理,不可转出！');history.back(-1);</script>");
			} */else {
				
				$pin_zs_df=M('pin')->where ( array('user'=>$data_P['user'],'zt'=>0) )->count ();
				
				
				for ($i=0;$i<$data_P ['sh'];$i++){
					
					$pin_xx=M('pin')->where ( array('user'=>$_SESSION['uname'],'zt'=>0) )->find();
						
					M('pin')->where ( array('id'=>$pin_xx['id'],'zt'=>0) )->save(array('user'=>$data_P['user']));
				}
				
				$pin_zs_xz=M('pin')->where ( array('user'=>$_SESSION['uname'],'zt'=>0) )->count ();
				$pin_zs_df_xz=M('pin')->where ( array('user'=>$data_P['user'],'zt'=>0) )->count ();
				
				$note3 = "激活码转出";
				$record3 ["UG_account"] = $_SESSION['uname']; // 登入转出账户
				$record3 ["pdb_js_zh"] = $data_P['user']; // 登入转出账户
				$record3 ["UG_type"] = 'mp';
				$record3 ["UG_allGet"] = $pin_zs; // 金币
				$record3 ["UG_money"] = '-'.$jbhe; //
				$record3 ["UG_balance"] = $pin_zs_xz; // 当前推荐人的金币馀额
				$record3 ["UG_dataType"] = 'jbzc'; // 金币转出
				$record3 ["UG_note"] = $note3; // 推荐奖说明
				$record3["UG_getTime"]		= date ( 'Y-m-d H:i:s', time () ); //操作时间
				$reg4 = M ( 'userget' )->add ( $record3 );
				
				$note3 = "激活码转入";
				$record3 ["UG_account"] = $data_P['user']; 
				$record3 ["pdb_js_zh"] = $_SESSION['uname']; // 登入转出账户
				$record3 ["UG_type"] = 'mp';
				$record3 ["UG_allGet"] = $pin_zs_df; // 金币
				$record3 ["UG_money"] = '+'.$jbhe; //
				$record3 ["UG_balance"] = $pin_zs_df_xz; // 当前推荐人的金币馀额
				$record3 ["UG_dataType"] = 'jbzr'; // 金币转出
				$record3 ["UG_note"] = $note3; // 推荐奖说明
				$record3["UG_getTime"]		= date ( 'Y-m-d H:i:s', time () ); //操作时间
				$reg4 = M ( 'userget' )->add ( $record3 );
				
				$get_user=M('user')->where(array('UE_account'=>$_SESSION['uname']))->find();
				$put_user=M('user')->where(array('UE_account'=>$data_P['user']))->find();
				// if($get_user['ue_phone']) sendSMS($get_user['ue_phone'],"您好！您转让的激活码：".$jbhe."个，已转让，请登录网站查看相关信息！【民生互助】");
				// if($put_user['ue_phone']) sendSMS($put_user['ue_phone'],"您好！您申请的激活码：".$jbhe."个，已到账，请登录网站查看相关信息！【民生互助】");
				vendor("Sendsms.sendsms");
				$send = new \Sendsms();
				$get_user=M('user')->where(array('UE_account'=>$ppddxx['p_user']))->find();
				// if ($get_user['ue_account']) $mes =$send->my_send($get_user['ue_account'], "尊敬的客户，您的账户资金有变动，请登录网站确认！【民生互助】");
			    if($get_user['ue_phone'])$send->my_send($get_user['ue_phone'],"您好！您转让的激活码：".$jbhe."个，已转让，请登录网站查看相关信息！【民生互助】");
				if($put_user['ue_phone'])$send->my_send($put_user['ue_phone'],"您好！您申请的激活码：".$jbhe."个，已到账，请登录网站查看相关信息！【民生互助】");
				
				$this->success('转让成功!');
			}
			}
		}
	}
	public function ldtj() {
		if(IS_AJAX){
			//$this->ajaxReturn ( array ('nr' => '验证码错误!','sf' => 0 ) );
			if (false) {
				$this->ajaxReturn ( array ('nr' => '验证码错误!','sf' => 0 ) );
			}else {
			
		$user = M('user');
		$ztname=$user->where(array('UE_accName'=>$_SESSION ['uname'],'UE_Faccount'=>'0','UE_check'=>'1','UE_stop'=>'1'))->getField('ue_account',true);
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
					$reg=$user->where(array('UE_accName'=>array('IN',$reg),'UE_Faccount'=>'0','UE_check'=>'1','UE_stop'=>'1'))->getField('ue_account',true);
					$datazs +=count($reg);
				}
			}
			
		}
		
		$this->ajaxReturn(array ('nr' => $datazs,'sf' => 0 ) );
			}
		}
		
	}
	public function zzjl() {
		$User = M ( 'userjyinfo' ); // 实例化User对象
		
	$map ['UJ_usercount'] = $_SESSION ['uname'];
	$map ['UJ_dataType'] = 'zs';
	
	
	
	
	$count = $User->where ( $map )->count (); // 查询满足要求的总记录数
	//dump($var)
	$page = new \Think\Page ( $count, 12 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
	
	// $page->lastSuffix=false;
	$page->setConfig ( 'header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录    第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>' );
	$page->setConfig ( 'prev', '上一页' );
	$page->setConfig ( 'next', '下一页' );
	$page->setConfig ( 'last', '末页' );
	$page->setConfig ( 'first', '首页' );
	$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
	;
	
	$show = $page->show (); // 分页显示输出
	// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
	$list = $User->where ( $map )->order ( 'UJ_ID DESC' )->limit ( $page->firstRow . ',' . $page->listRows )->select ();
	
	//dump($list);die;
	
	$this->assign ( 'list', $list ); // 赋值数据集
	$this->assign ( 'page', $show ); // 赋值分页输出
	$this->display ( 'zzjl' );
	}
	
	
	
	public function tm(){
		$d=date('Y-m-d',time());
		$fd=date('Y-m-d 00:00:00',getWeekBeginTime());
		$dd=date('Y-m-d 23:59:59',getWeekEndTime());
		echo $d.$fd.$dd;
	}
	
	public function tgbzto(){
		$id=$_REQUEST['tid'];
		//echo $id;die;
		$settings = include( APP_PATH . 'Home/Conf/settings.php' );
		$lmoney=$settings['supply_money_lower_limit'.$id];
		$tmoney=$settings['supply_money_upper_limit'.$id];
		$cmoney=$settings['supply_money_limit'.$id];
		//echo $lmoney;die;
		$this->assign('lower',$lmoney);
		$this->assign('upper',$tmoney);
		$this->assign('chu',$cmoney);
		$this->assign('tid',$id);
		$this->display('tgbzto');
	}
	
	
	// public function tgbzcl() {
	// 	if (IS_POST) {
 //                if(date('w')==0){
 //                	die("<script>alert('排单日期为周一至周六');history.back(-1);</script>");
 //                }
	// 	        $settings = include( APP_PATH . 'Home/Conf/settings.php' );
	// 			$data_P = I ( 'post.' );
	// 			//dump($data_P);die;
	// 			$user = M ( 'user' )->where ( array (
	// 					UE_account => $_SESSION ['uname']
	// 			) )->find ();
				
	// 			/* if(empty($user['zfb']) && empty($user['yhzh'])){
	// 				die("<script>alert('请完善资料后再进行提供帮助！');history.back(-1);</script>");
	// 			} */
	// 			if( $user['ue_check'] == '0' ){
	// 				die("<script>alert('您的账号未被审核,请与客服联系！');history.back(-1);</script>");
	// 			}
				
				
	// 			$user1 = M ();
				
	// 			$chine = $data_P ['amount'];
	// 			//1000表示排单的金额除以1000的倍数等于消耗爱心币，有余数采用进一法
	// 			//$num = ceil($chine / $settings['pd_unm']);
	// 			//密码
	// 			$pin = $data_P ['ejmm'];
	// 			if(md5($pin) != $user['ue_secpwd']){
	// 				die("<script>alert('密码不正确！');history.back(-1);</script>");
	// 			}
	// 			//类型
	// 			$tid=$data_P ['tid'];
	// 			if($tid == 1){
	// 				$data2['note']='大爱区';
	// 				$num=2;
	// 			}else{
	// 				$data2['note']='小爱区';
	// 				$num=1;
	// 			}
							
	// 			 $countpp=M('tgbz')->where(array('user'=>$_SESSION['uname'],'qr_zt'=>0))->count();
	// 			if($countpp>0){
	// 				die("<script>alert('你有未完成的单子！');history.back(-1);</script>");
	// 			} 
				
				
	// 			/* if ($data_P ['zffs1']<>'1'&&$data_P ['zffs2']<>'1'&&$data_P ['zffs3']<>'1') {
	// 				die("<script>alert('至少选择一个收款方式！');history.back(-1);</script>");
	// 			// deleted by skyrim
	// 			// purpose: upper/lower limit
	// 			// version: 3.0
	// 			}else */if ($user['ue_pdb']<$num) {
 //                    die("<script>alert('爱心币余额不足');history.back(-1);</script>");
	// 			/* } elseif ($user['ue_pdb']<$settings['pdb_lost']) {
 //                die("<script>alert('爱心币余额不足');history.back(-1);</script>"); */
	// 			} elseif ($data_P ['amount']<$settings['supply_money_lower_limit'.$tid]||$data_P ['amount']>$settings['supply_money_upper_limit'.$tid]) {
	// 				die("<script>alert('帮助金额".$settings['supply_money_lower_limit'.$tid]."-".$settings['supply_money_upper_limit'.$tid].",并且是".$settings['supply_money_limit'.$tid]."的倍数！');history.back(-1);</script>");
	// 			}elseif ($data_P ['amount']% $settings['supply_money_limit'.$tid] > 0) {
	// 					die("<script>alert('帮助金额".$settings['supply_money_lower_limit'.$tid]."-".$settings['supply_money_upper_limit'.$tid].",并且是".$settings['supply_money_limit'.$tid]."的倍数！');history.back(-1);</script>");
	// 			}/* elseif ($data_P ['amount'] < 500 ) {
	// 				die("<script>alert('帮助金额500-10000,并且是500的倍数！');history.back(-1);");
	// 			} */ else {
													
	// 			    $data['zffs1']='1';
	// 			    $data['zffs2']='1';
	// 			    $data['zffs3']='1';
	// 			    $data['user']=$user['ue_account'];
	// 				$data['jb']=$data_P ['amount'];
	// 				$data['user_nc']=$user['ue_theme'];
	// 				$data['user_tjr']=$user['ue_accname'];
	// 				$data['date']=date ( 'Y-m-d H:i:s', time () );
	// 				$data['zt']=0;
	// 				$data['qr_zt']=0;
	// 				$data['zhh']=$tid;
	// 				if($tgbz_id=M('tgbz')->add($data)){
	// 					M("user")->where(array("UE_account"=>$user['ue_account']))->setDec("ue_pdb",$num);
	// 					//转换
	// 					$pd=$num*$settings['pd_unm'];
	// 					M("user")->where(array("UE_account"=>$user['ue_account']))->setInc('shop_money',$pd);
	// 					//增加利息数据
	// 					$data2['user']=$data['user'];
	// 					$data2['zt']=2;
	// 					$data2['date']=$data['date'];						
	// 					$data2['jb']=$data['jb']; 
	// 					$data2['tgbz_id']=$tgbz_id; 
	// 					$data2['zhh']=$tid;
	// 					M('user_jj')->add($data2);
						
	// 					die("<script>alert('提交成功！');window.location.href='/';</script>");
	// 				}else{
	// 					die("<script>alert('提交失败！');history.back(-1);</script>");
	// 				}
				
	// 			}
	// 	}
	// }
	/*
	* 20170712
	 */ 
    public function tgbzcl() {
        $settings = include( APP_PATH . 'Home/Conf/settings.php' );
		if (IS_POST) {
		    $data_P = I ( 'post.' );
			$user = M ( 'user' )->where ( array (UE_account => $_SESSION ['uname']))->find ();
			if(date('w')==0){
              	die("<script>alert('排单日期为周一至周六');history.back(-1);</script>");
            }
            if($user['ue_check'] == 0){
                die("<script>alert('请激活后再进行操作！');history.back(-1);</script>");
            }
            if(!$user['weixin'] || !$user['zfb'] ){
                die("<script>alert('请完善个人资料后再进行操作');history.back(-1);</script>");
            }
            if(strtotime($user['check_time'])+8*3600*24>time()){
                die("<script>alert('首次排单需要等待7-12个工作日');history.back(-1);</script>");
            }
            if($data_P['zffs1'] != 1 && $data_P['zffs2'] != 1 && $data_P['zffs3'] != 1){
               die("<script>alert('请至少选择一种支付方式！');history.back(-1);</script>");
            }
            if($data_P['amount'] <= 0 || (string)(int)$data_P['amount'] != (string)$data_P['amount']){
                die("<script>alert('金额填写有误，请输入数字！');history.back(-1);</script>");
            }
            if($data_P['amount']<1000){
                die("<script>alert('投资额度不能低于1000元！');history.back(-1);</script>");
            }
		    $tgcount=M('tgbz')->where(array('user'=>$_SESSION['uname'],'zt'=>0))->count();
			$ppcount=M('ppdd')->where(array('p_user'=>$_SESSION['uname'],'zt'=>0))->count();
		    if($tgcount>0 || $ppcount>0){
	     		die("<script>alert('您有未匹配或未打款的订单，请完成后再操作!');history.go(-1);</script>");
		    }
		    $tgbz_date=M('tgbz')->where(array('user'=>$_SESSION['uname']))->order('id desc')->getField('date');
		    if(strtotime($tgbz_date)+15*3600*24>time()){
	     		die("<script>alert('您当前的排单周期还未完成（排单周期为15个工作日），不能进行操作!');history.go(-1);</script>");
		    }
            if($user['ue_secpwd'] != md5($data_P['pwd'])){
                die("<script>alert('支付密码错误!');history.back(-1);</script>");
            }
			if($data_P['amount']<=10000){
                 $pdb=$settings['pdb1'];
			}elseif(10000<$data_P['amount'] && $data_P['amount']<=30000 ){
				 $pdb=$settings['pdb2'];
			}elseif(30000<$data_P['amount'] && $data_P['amount']<=$settings['apply_money_upper_limit']){
                 $pdb=$settings['pdb3'];
			}else{
                die("<script>alert('您的投资金额过大！');history.back(-1);</script>");
			}
            if ($user['ue_pdb'] < $pdb) {
               die("<script>alert('预约币不足，请充值预约币！');history.back(-1);</script>");
            }

            //排单额度只能大于等于上一单
            $tgbz_mod = M('tgbz');
            $last_time = $tgbz_mod->where(array('user='=>$_SESSION ['uname']))->order('date desc')->limit(1)->getfield('date');
            // if(time()-strtotime($last_time) < 86400*16){
            //     die("<script>alert('15个工作日内只提供帮助一次');history.back(-1);</script>");
            // }
            $tgbzjb = $tgbz_mod->where("user='".$_SESSION ['uname']."'")->order('date desc')->group('date')->limit(1)->getfield('sum(jb)');
            if(intval($data_P ['amount']) < $tgbzjb){
                die("<script>alert('排单额度只能大于或等于上一单');history.back(-1);</script>");
            }

                 
			 //生成订单
		     $data1['user']=$user['ue_account'];
		     $data1['jb']=$data_P ['amount'];
		     $data1['user_nc']=$user['ue_theme'];
		     $data1['user_tjr']=$user['ue_accname'];
		     $data1['date']=date ( 'Y-m-d H:i:s', time () );
		     $data1['zt']=0;
		     $data1['qr_zt']=0;
		     $data1['date1']=date('Y-m-d H:i:s',time());
		     $data1['date2']=date('Y-m-d H:i:s',time());
		     $data1['zffs1']=1;
		     $data1['zffs2']=1;
		     $data1['zffs3']=1;
		     $tgbz_id1=M('tgbz')->add($data1);

			 if($tgbz_id1){
                M('user')->where(array('UE_account' => $user['ue_account']))->setDec('ue_pdb', $pdb);
                if($data_P['amount']<=3000){
                   M('user')->where(array('UE_account' => $user['ue_account']))->setField('li_lv', $settings['jt_li1']);
                }elseif(3000<$data_P['amount'] && $data_P['amount']<=10000){
                   M('user')->where(array('UE_account' => $user['ue_account']))->setField('li_lv', $settings['jt_li2']);
                }elseif(10000<$data_P['amount'] && $data_P['amount']<=50000){
                   M('user')->where(array('UE_account' => $user['ue_account']))->setField('li_lv', $settings['jt_li3']);
                }
                //增加利息数据
                $data2['user'] = $data1['user'];
                $data2['zt'] = 2;
                $data2['date'] = $data1['date'];
                $data2['note'] = '提供帮助';
                $data2['jb'] = $data1['jb'];
                $data2['tgbz_id'] = $tgbz_id1;
                $data2['dataType'] = 'tgbz';
                M('user_jj')->add($data2);   
				die("<script>alert('预约成功！');history.back(-1);</script>");
			 }else{
				die("<script>alert('预约失败！');history.back(-1);</script>");
			 }
		}
	}


	public function jsbzcl() {
		if (IS_POST) {
			$settings = include( APP_PATH . 'Home/Conf/settings.php' );
		    // echo $settings['jinglimit'];
		    // echo $settings['jing'];
			$data_P = I ( 'post.' );
			$user = M ( 'user' )->where ( array ('UE_account' => $_SESSION ['uname']) )->find ();
            if($user['ue_check'] == 0 && $user['ue_stop'] != 1){
                die("<script>alert('请激活用户后再进行操作！');history.back(-1);</script>");
            }
            if(!$user['weixin'] && !$user['zfb'] && !$user['ue_yhzh'] && $user['ue_stop'] != 1){
                die("<script>alert('请完善个人资料后再进行操做');history.back(-1);</script>");
            }
            if($data_P['zffs1'] != 1 && $data_P['zffs2'] != 1 && $data_P['zffs3'] != 1){
               die("<script>alert('请至少选择一种支付方式！');history.back(-1);</script>");
            }
            if($user['ue_secpwd'] != md5($data_P['pwd'])){
                die("<script>alert('支付密码错误');history.back(-1);</script>");
            }
            if($data_P['get_amount'] <= 0 || (string)(int)$data_P['get_amount'] != (string)$data_P['get_amount']){
                die("<script>alert('金额填写有误！');history.back(-1);</script>");
            }
	        $tgcount=M('jsbz')->where(array('user'=>$_SESSION['uname'],'zt'=>0,'qb'=>$data_P['moneytype']))->order('date desc')->find();
		    if(time()-strtotime($tgcount['date']) <= 86400*17 && $user['ue_stop'] != 1){
                  die("<script>alert('15个工作日内只能接受帮助一次！');history.back(-1);</script>");
            }
            $user_xz=M('user')->where(array('UE_ID'=>$_SESSION['uid']))->find();
			if($data_P['moneytype'] == 1){
				/*
				* 静态钱包提现
				 */
		       // if($data_P['get_amount'] <1000){
		       //     die("<script>alert('静态钱包提现最低不能低于1000元');history.back(-1);</script>");
		       // }
			   if($user['ue_money'] < $data_P ['get_amount'] && $user['ue_stop'] != 1){
			   	   die("<script>alert('余额不足！');history.back(-1);</script>");
			   }
               $jing = $settings['jt_qb_bs'];
               if(((int)$data_P['get_amount']) % ((int)$jing) != 0 || $data_P['get_amount'] < $jinglimit){
                   die("<script>alert('接受帮助金额不低于".$settings['jt_qb']."元，同时要为".$settings['jt_qb_bs']."的倍数!');history.back(-1);</script>");
               }
               $note3 = '静态提现';
			   $record3 ["UG_allGet"] = $user_zq['ue_money'];
			   M('user')->where(array('UE_account' => $_SESSION ['uname']))->setDec('UE_money',$data_P ['get_amount']);

			   $record3 ["UG_balance"] = $user_xz['ue_money'];
               $type = 2;
			}elseif($data_P['moneytype'] == 2){
                 /*
				* 动态钱包提现
				 */
			    if($user['jl_he'] < $data_P ['get_amount'] && $user['ue_stop'] != 1){
					die("<script>alert('余额不足！');history.back(-1);</script>");
			    }

                $jings = $settings['dt_qb_bs'];
                // $jingslimit = $settings['jinglimit'];
                if(((int)$data_P['get_amount']) % ((int)$jings) != 0 || $data_P['get_amount'] <= $jingslimit){
                    die("<script>alert('接受帮助金额不低于".$settings['dt_qb']."元，同时要为".$settings['dt_qb_bs']."的倍数!');history.back(-1);</script>");
                }
                $note3 = '动态提现';
                $type = 4;
			    $record3 ["UG_allGet"] = $user_zq['jl_he'];
			    M('user')->where(array('UE_account' => $_SESSION ['uname']))->setDec('jl_he',$data_P ['get_amount']);
                // M('user')->where(array('UE_account' => $_SESSION ['uname']))->setInc('UE_money',$data_P ['get_amount']);
			}
            $data['user']=$user['ue_account'];
			$data['jb']=$data_P ['get_amount'];
			$data['user_nc']=$user['ue_theme'];
			$data['user_tjr']=$user['ue_accname'];
            $data['zffs1']=$data_P['zffs1'];
            $data['zffs2']=$data_P['zffs2'];
            $data['zffs3']=$data_P['zffs3'];
			$data['date']=date ( 'Y-m-d H:i:s', time () );
			$data['zt']=0;
			$data['qr_zt']=0;
            $data['qb']=$type;
	    	
			$record3 ["UG_account"] = $_SESSION['uname']; // 登入转出账户
            $record3 ["UG_type"] = 'jb';
			//$record3 ["UG_allGet"] = $user_zq['ue_money']; // 金币
			$record3 ["UG_money"] = '-'.$data_P ['get_amount']; //
			//$record3 ["UG_balance"] = $user_xz['ue_money']; // 当前推荐人的金币馀额
			$record3 ["UG_dataType"] = 'jsbz'; // 金币转出
			$record3 ["UG_note"] = $note3; // 推荐奖说明
			$record3 ["type"] = $type ? : 0; // 推荐奖说明
			$record3["UG_getTime"]	= date ( 'Y-m-d H:i:s', time () ); //操作时间
			M ( 'userget' )->add ( $record3 );
            if(M('jsbz')->add($data)){
                die("<script>alert('提交成功！');history.back(-1);</script>");
            }else{
                die("<script>alert('提交失败！');history.back(-1);</script>");
            }

		}
	}


	
	public function tqcl() {
		if (IS_POST) {
			$data_P = I ( 'post.' );
			
			$user = M ( 'user' )->where ( array (
					UE_account => $_SESSION ['uname']
			) )->find ();
			$money=$data_P['get_amount'];
			$settings = include( APP_PATH . 'Home/Conf/settings.php' );
			if ($data_P ['get_amount']<$settings['tqje_low']) {
				die("<script>alert('金额".$settings['tqje_low']."起,并且是".$settings['tqje_bei']."的倍数！');history.back(-1);</script>");
			}elseif ($data_P ['get_amount']% $settings['tqje_bei'] > 0) {
				die("<script>alert('金额".$settings['tqje_low']."起,并且是".$settings['tqje_bei']."的倍数！');history.back(-1);</script>");			
			}elseif($user['jl_he'] < $data_P['get_money']){
				die("<script>alert('团队奖不足');</script>");
			}
		    
			$model = M('user');
			$model->startTrans();
			$r1 = $model->where(array('UE_account' => $_SESSION['uname']))->setDec("jl_he",$money);
			$r2 = $model->where(array(UE_account => $_SESSION['uname']))->setInc("UE_money",$money);
			if($r1 && $r2){
				$model->commit();
			
				
				$userxxx = M('user')->where(array('UE_account' => $_SESSION['uname']))->find();
				$tm=time();
				$note3 = "团队奖提现";
				$record3 ["UG_account"] = $_SESSION['uname']; // 登入转出账户
				$record3 ["UG_type"] = 'jb';
				$record3 ["UG_allGet"] = $user['jl_he']; // 金币
				$record3 ["UG_money"] = '-'.$money; //
				$record3 ["UG_balance"] = $userxxx['jl_he']; // 当前推荐人的金币馀额
				$record3 ["UG_dataType"] = 'tx'; // 金币转出
				$record3 ["UG_note"] = $note3; // 推荐奖说明
				$record3["UG_getTime"]		= date ( 'Y-m-d H:i:s', $tm); //操作时间
				$reg4 = M ( 'userget' )->add ( $record3 );
			
				$note3 = "团队奖提现";
				$record3 ["UG_account"] = $_SESSION['uname']; // 登入转出账户
				$record3 ["UG_type"] = 'jb';
				$record3 ["UG_allGet"] = $user['ue_money']; // 金币
				$record3 ["UG_money"] = '+'.$money; //
				$record3 ["UG_balance"] = $userxxx['ue_money']; // 当前推荐人的金币馀额
				$record3 ["UG_dataType"] = 'tx'; // 金币转出
				$record3 ["UG_note"] = $note3; // 推荐奖说明
				$record3["UG_getTime"]		= date ( 'Y-m-d H:i:s', $tm); //操作时间
				$reg4 = M ( 'userget' )->add ( $record3 );
			
				
				$this->success('提现成功!');
			}else{
				$model->rollback();
				$this->error('提现失败!');
			}
				
			
			
			
		}
	}
	
	
	// public function jsbzcl() {
	// 	if (IS_POST) {
	// 		$settings = include( APP_PATH . 'Home/Conf/settings.php' );
	// 		//echo $settings['accept_money_lower_limit'];  echo $settings['accept_money_limit'];
	// 		//die;
	// 		$data_P = I ( 'post.' );
	// 		//dump($data_P);die;
	// 		$user = M ( 'user' )->where ( array (
	// 				UE_account => $_SESSION ['uname']
	// 		) )->find ();
	// 		$user1 = M ();
	// 		//dump($data_P);die;
	// 		/*  $time1=date('Y-m-d 00:00:00',time());
	// 		$time2=date('Y-m-d 23:59:59',time());
	// 		$map['date']=array(array('egt',$time1),array('elt',$time2));
	// 		$jcount=M('jsbz')->where(array('date'=>$map['date'],'user'=>$_SESSION['uname'],'qb'=>3))->count();
	// 		// echo $jcount;die;
	// 		if($jcount >= 1 && $data_P['moneytype'] == 3){
	// 			die("<script>alert('团队奖一天之内只能提取一次！');history.back(-1);</script>");				
	// 		} 	 */		
	// 		/* if($data_P['moneytype'] != 4){
	// 			$countpd=M('ppdd')->where(array('p_user'=>$_SESSION['uname'],'zt'=>0))->count();
	// 			$countpd1=M('ppdd')->where(array('p_user'=>$_SESSION['uname'],'zt'=>1))->count();
	// 			$countpp=M('jsbz')->where(array('user'=>$_SESSION['uname'],'zt'=>0))->count();
	// 			if($countpd>0||$countpp>0 || $countpd1>0){
	// 				die("<script>alert('你有未完成的单子！');history.back(-1);</script>");
	// 			}
	// 		} */
			
						
	// 		if ($data_P ['zffs1']<>'1'&&$data_P ['zffs2']<>'1'&&$data_P ['zffs3']<>'1') {
	// 			die("<script>alert('至少选择一种收款方式！');history.back(-1);</script>");
	// 		} elseif ($data_P ['get_amount']<$settings['accept_money_lower_limit']) {
	// 				die("<script>alert('接受帮助金额".$settings['accept_money_lower_limit']."起并且是".$settings['accept_money_limit']."的倍数！');history.back(-1);</script>");
	// 		} elseif ($data_P ['get_amount']% $settings['accept_money_limit'] > 0) {
	// 				die("<script>alert('接受帮助金额".$settings['accept_money_lower_limit']."起并且是".$settings['accept_money_limit']."的倍数！');history.back(-1);</script>");
	// 		} /* elseif ($user['ue_money'] <= $data_P ['get_amount']) {
	// 			die("<script>alert('余额不足！');history.back(-1);</script>");
	// 		} */ else {
	// 			if($data_P ['zffs1']=='1'){$data['zffs1']='1';}else{$data['zffs1']='0';}
	// 			if($data_P ['zffs2']=='1'){$data['zffs2']='1';}else{$data['zffs2']='0';}
	// 			if($data_P ['zffs3']=='1'){$data['zffs3']='1';}else{$data['zffs3']='0';}
	// 			/* $data['user']=$user['ue_account'];
	// 			$data['jb']=$data_P ['get_amount'];
	// 			$data['user_nc']=$user['ue_theme'];
	// 			$data['user_tjr']=$user['ue_accname'];
	// 			$data['date']=date ( 'Y-m-d H:i:s', time () );
	// 			$data['zt']=0;
	// 			$data['qr_zt']=0; */
				
	// 			$user_zq=M('user')->where(array('UE_ID'=>$_SESSION['uid']))->find();
	// 			if($data_P['moneytype'] == 1){
	// 				if($user['ue_money'] < $data_P ['get_amount']){
	// 					die("<script>alert('余额不足！');history.back(-1);</script>");
	// 				}
	// 				$record3 ["UG_allGet"] = $user_zq['ue_money'];
	// 				M('user')->where(array('UE_account' => $_SESSION ['uname']))->setDec('UE_money',$data_P ['get_amount']);
	// 				$user_xz=M('user')->where(array('UE_ID'=>$_SESSION['uid']))->find();
	// 				$record3 ["UG_balance"] = $user_xz['ue_money'];
	// 				$data['qb']=1;
	// 			}elseif($data_P['moneytype'] == 2){
	// 				if($user['tj_he'] < $data_P ['get_amount']){
	// 					die("<script>alert('余额不足！');history.back(-1);</script>");
	// 				}
	// 				$record3 ["UG_allGet"] = $user_zq['tj_he'];
	// 				M('user')->where(array('UE_account' => $_SESSION ['uname']))->setDec('tj_he',$data_P ['get_amount']);
	// 				$user_xz=M('user')->where(array('UE_ID'=>$_SESSION['uid']))->find();
	// 				$record3 ["UG_balance"] = $user_xz['tj_he'];
	// 				$data['qb']=2;
	// 			}elseif($data_P['moneytype'] == 3){
	// 				if($user['jl_he'] < $data_P ['get_amount']){
	// 					die("<script>alert('余额不足！');history.back(-1);</script>");
	// 				}
	// 				$record3 ["UG_allGet"] = $user_zq['jl_he'];
	// 				M('user')->where(array('UE_account' => $_SESSION ['uname']))->setDec('jl_he',$data_P ['get_amount']);
	// 				$user_xz=M('user')->where(array('UE_ID'=>$_SESSION['uid']))->find();
	// 				$record3 ["UG_balance"] = $user_xz['jl_he'];
	// 				$data['qb']=3;
	// 			}elseif($data_P['moneytype'] == 4){
	// 				if($user['jl_he1'] < $data_P ['get_amount']){
	// 					die("<script>alert('余额不足！');history.back(-1);</script>");
	// 				}
	// 				$record3 ["UG_allGet"] = $user_zq['jl_he1'];
	// 				M('user')->where(array('UE_account' => $_SESSION ['uname']))->setDec('jl_he1',$data_P ['get_amount']);
	// 				$user_xz=M('user')->where(array('UE_ID'=>$_SESSION['uid']))->find();
	// 				$record3 ["UG_balance"] = $user_xz['jl_he1'];
	// 				$data['qb']=4;
	// 			}
	// 			$data['user']=$user['ue_account'];
	// 			$data['jb']=$data_P ['get_amount'];
	// 			$data['user_nc']=$user['ue_theme'];
	// 			$data['user_tjr']=$user['ue_accname'];
	// 			$data['date']=date ( 'Y-m-d H:i:s', time () );
	// 			$data['zt']=0;
	// 			$data['qr_zt']=0;
				
	// 			//M('user')->where(array('UE_account' => $_SESSION ['uname']))->setDec('UE_money',$data_P ['get_amount']);				
				
	// 			$user_xz=M('user')->where(array('UE_ID'=>$_SESSION['uid']))->find();
	// 			$note3 = "接受帮助扣款";
	// 			$record3 ["UG_account"] = $_SESSION['uname']; // 登入转出账户
	// 			$record3 ["UG_type"] = 'jb';
	// 			//$record3 ["UG_allGet"] = $user_zq['ue_money']; // 金币
	// 			$record3 ["UG_money"] = '-'.$data_P ['get_amount']; //
	// 			//$record3 ["UG_balance"] = $user_xz['ue_money']; // 当前推荐人的金币馀额
	// 			$record3 ["UG_dataType"] = 'jsbz'; // 金币转出
	// 			$record3 ["UG_note"] = $note3; // 推荐奖说明
	// 			$record3["UG_getTime"]		= date ( 'Y-m-d H:i:s', time () ); //操作时间
	// 			$reg4 = M ( 'userget' )->add ( $record3 );
				
	// 			if(M('jsbz')->add($data)){
	// 				die("<script>alert('提交成功！');window.location.href='/';</script>");
	// 			}else{
	// 				die("<script>alert('提交失败！');history.back(-1);</script>");
	// 			}
	// 		}
				
	// 	}
	// }
	public function jsbzcl1() {
		if (IS_POST) {
				
			$data_P = I ( 'post.' );
			//dump($data_P);die;
			$user = M ( 'user' )->where ( array (
					UE_account => $_SESSION ['uname']
			) )->find ();
			$user1 = M ();
			//! $this->check_verify ( I ( 'post.yzm' ) )
			//! $user1->autoCheckToken ( $_POST )
			if ($data_P ['zffs1']<>'1'&&$data_P ['zffs2']<>'1'&&$data_P ['zffs3']<>'1') {
				die("<script>alert('至少选择一种收款方式！');history.back(-1);</script>");
			}  elseif ($data_P ['get_amount']<100) {
					die("<script>alert('接受帮助金额100起并且是100的倍数！');history.back(-1);</script>");
			} elseif ($data_P ['get_amount']% 100 > 0) {
					die("<script>alert('接受帮助金额100起并且是100的倍数！！');history.back(-1);</script>");
			} elseif ($user['jl_he']<$data_P ['get_amount']) {
				die("<script>alert('余额不足！');history.back(-1);</script>");
			} else {
				
				$timea=time ();
				$kssj=strtotime($user['tx_date'])+86400*7;
				$startTime = date('Y-m-d H:i:s',$kssj);
				if($user['tx_leiji']=='0'||$timea>=$kssj){
					M('user')->where(array(UE_account => $_SESSION ['uname']))->save(array('tx_date'=>date('Y-m-d H:i:s',$timea),'tx_leiji'=>'0'));
					$user = M ( 'user' )->where ( array (UE_account => $_SESSION ['uname']) )->find ();
				}
				
				if($user['tx_leiji']+$data_P ['get_amount']>14000){
					die("<script>alert('经理奖金本周提现超过14000RMB,请在".$startTime."以后在试！');history.back(-1);</script>");
				}else{
					M('user')->where(array(UE_account => $_SESSION ['uname']))->setInc('tx_leiji',$data_P ['get_amount']);
				
				if($data_P ['zffs1']=='1'){$data['zffs1']='1';}else{$data['zffs1']='0';}
				if($data_P ['zffs2']=='1'){$data['zffs2']='1';}else{$data['zffs2']='0';}
				if($data_P ['zffs3']=='1'){$data['zffs3']='1';}else{$data['zffs3']='0';}
				$data['user']=$user['ue_account'];
				$data['jb']=$data_P ['get_amount'];
				$data['user_nc']=$user['ue_theme'];
				$data['user_tjr']=$user['ue_accname'];
				$data['date']=date ( 'Y-m-d H:i:s', time () );
				$data['zt']=0;
				$data['qr_zt']=0;
				$data['qb']=1;
				$user_zq=M('user')->where(array('UE_ID'=>$_SESSION['uid']))->find();
	
	
	
				M('user')->where(array('UE_account' => $_SESSION ['uname']))->setDec('jl_he',$data_P ['get_amount']);
	
				$user_xz=M('user')->where(array('UE_ID'=>$_SESSION['uid']))->find();
				$note3 = "接受帮助扣款";
				$record3 ["UG_account"] = $_SESSION['uname']; // 登入转出账户
				$record3 ["UG_type"] = 'jb';
				$record3 ["UG_allGet"] = $user_zq['jl_he']; // 金币
				$record3 ["UG_money"] = '-'.$data_P ['get_amount']; //
				$record3 ["UG_balance"] = $user_xz['jl_he']; // 当前推荐人的金币馀额
				$record3 ["UG_dataType"] = 'jsbz'; // 金币转出
				$record3 ["UG_note"] = $note3; // 推荐奖说明
				$record3["UG_getTime"]		= date ( 'Y-m-d H:i:s', time () ); //操作时间
				$reg4 = M ( 'userget' )->add ( $record3 );
	
				if(M('jsbz')->add($data)){
					die("<script>alert('提交成功！');window.location.href='/';</script>");
				}else{
					die("<script>alert('提交失败！');history.back(-1);</script>");
				}
			}
			}
		}
	}
	
	public function jsbzc2() {
		if (IS_POST) {
	
			$data_P = I ( 'post.' );
			//dump($data_P);die;
			$user = M ( 'user' )->where ( array (
					UE_account => $_SESSION ['uname']
			) )->find ();
			$user1 = M ();
			//! $this->check_verify ( I ( 'post.yzm' ) )
			//! $user1->autoCheckToken ( $_POST )
			if ($data_P ['zffs1']<>'1'&&$data_P ['zffs2']<>'1'&&$data_P ['zffs3']<>'1') {
				die("<script>alert('至少选择一种收款方式！');history.back(-1);</script>");
			}  elseif ($data_P ['get_amount']<100) {
					die("<script>alert('接受帮助金额100起并且是100的倍数！');history.back(-1);</script>");
			} elseif ($data_P ['get_amount']% 100 > 0) {
					die("<script>alert('接受帮助金额100起并且是100的倍数！！');history.back(-1);</script>");
			} elseif ($user['tj_he']<$data_P ['get_amount']) {
				die("<script>alert('余额不足！');history.back(-1);</script>");
			} else {
				if($data_P ['zffs1']=='1'){$data['zffs1']='1';}else{$data['zffs1']='0';}
				if($data_P ['zffs2']=='1'){$data['zffs2']='1';}else{$data['zffs2']='0';}
				if($data_P ['zffs3']=='1'){$data['zffs3']='1';}else{$data['zffs3']='0';}
				$data['user']=$user['ue_account'];
				$data['jb']=$data_P ['get_amount'];
				$data['user_nc']=$user['ue_theme'];
				$data['user_tjr']=$user['ue_accname'];
				$data['date']=date ( 'Y-m-d H:i:s', time () );
				$data['zt']=0;
				$data['qr_zt']=0;
				$data['qb']=2;
				$user_zq=M('user')->where(array('UE_ID'=>$_SESSION['uid']))->find();
	
	
	
				M('user')->where(array('UE_account' => $_SESSION ['uname']))->setDec('tj_he',$data_P ['get_amount']);
	
				$user_xz=M('user')->where(array('UE_ID'=>$_SESSION['uid']))->find();
				$note3 = "接受帮助扣款";
				$record3 ["UG_account"] = $_SESSION['uname']; // 登入转出账户
				$record3 ["UG_type"] = 'jb';
				$record3 ["UG_allGet"] = $user_zq['tj_he']; // 金币
				$record3 ["UG_money"] = '-'.$data_P ['get_amount']; //
				$record3 ["UG_balance"] = $user_xz['tj_he']; // 当前推荐人的金币馀额
				$record3 ["UG_dataType"] = 'jsbz'; // 金币转出
				$record3 ["UG_note"] = $note3; // 推荐奖说明
				$record3["UG_getTime"]		= date ( 'Y-m-d H:i:s', time () ); //操作时间
				$reg4 = M ( 'userget' )->add ( $record3 );
	
				if(M('jsbz')->add($data)){
					die("<script>alert('提交成功！');window.location.href='/';</script>");
				}else{
					die("<script>alert('提交失败！');history.back(-1);</script>");
				}
			}
	
		}
	}
	public function tgbz_del() {
		if (!preg_match ( '/^[0-9]{1,10}$/', I ('get.id') )) {
			$this->success('非法操作,将冻结账号!');
		}else{
			$userinfo = M ( 'tgbz' )->where ( array ('id' => I ('get.id'),'zt'=>'0') )->find ();
			//dump(I ('get.id'));
			//dump($userinfo['ue_accname']);die;
			if ($userinfo['user']<>$_SESSION ['uname']) {
				$this->success('订单当前状态不可取消!');
			}else{
				//lkdsjfsdfj($userinfo['user'],'-'.$userinfo['jb']);
				$reg = M ( 'tgbz' )->where(array ('id' => I ('get.id')))->delete();
				
				if ($reg) {
					M ( 'user_jj' )->where(array ('tgbz_id' => I ('get.id')))->delete();
					$t=$userinfo['jb']/500;
					M('user')->where(array('UE_account' => $userinfo['user']))->setInc('ue_pdb',$t);
					die("<script>alert('取消成功!');window.location.href='/';</script>");
				}else {
					die("<script>alert('取消失败!');window.location.href='/';</script>");
				}
			}
		}
	}
	
	public function jsbz_del() {
		//die("<script>alert('不可取消!');window.location.href='/';</script>");
		if (!preg_match ( '/^[0-9]{1,10}$/', I ('get.id') )) {
			$this->success('非法操作,将冻结账号!');
		}else{
			$userinfo = M ( 'jsbz' )->where ( array ('id' => I ('get.id'),'zt'=>'0') )->find ();
			//$userinfo1 = M ( 'jsbz' )->where ( array ('id' => I ('get.id'),'zt'=>'0','qb'=>'1') )->find ();
			//dump(I ('get.id'));
			//dump($userinfo['ue_accname']);die;
			$jl_tj = $userinfo['qb'];

			
			if ($userinfo['user']<>$_SESSION ['uname']) {
				$this->success('订单当前状态不可取消!');
			}elseif($jl_tj == 0){
				$reg = M ( 'jsbz' )->where(array ('id' => I ('get.id')))->delete();
				$a = M('user')->where(array('UE_account' => $userinfo['user']))->setInc('UE_money',$userinfo['jb']);
			}elseif($jl_tj == 1){
				$reg = M ( 'jsbz' )->where(array ('id' => I ('get.id')))->delete();
				$a = M('user')->where(array('UE_account' => $userinfo['user']))->setInc('jl_he',$userinfo['jb']);
			}elseif($jl_tj == 2){
				$reg = M ( 'jsbz' )->where(array ('id' => I ('get.id')))->delete();
				$a = M('user')->where(array('UE_account' => $userinfo['user']))->setInc('tj_he',$userinfo['jb']);
			}
			if ($reg&&$a) {
				die("<script>alert('取消成功!');window.location.href='/';</script>");
			}else {
				die("<script>alert('取消失败!');window.location.href='/';</script>");
			}
		}
	}
	
	
	
	public function pipei() {
		
		$xypipeije=10;
		$data=array(1,2,3,4,5,6,7,8);
		$tj=count($data);
		$sf_tcpp='0';
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
	
	public function home_ddxx(){
		 
		// $ppddxx=M('ppdd')->where(array('id'=>I('get.id')))->find();
		// $g_user=M('user')->where(array('UE_account'=>$ppddxx['g_user']))->find();
		// $g_user_t=M('user')->where(array('UE_account'=>$g_user['ue_accname']))->find();
		// $p_user=M('user')->where(array('UE_account'=>$ppddxx['p_user']))->find();
		// $p_user_t=M('user')->where(array('UE_account'=>$p_user['ue_accname']))->find();
		
		// $this->ppddxx=$ppddxx;
		// $this->g_user=$g_user;
		// $this->p_user=$p_user;
		
		// $this->g_user_t=$g_user_t;
		// $this->p_user_t=$p_user_t;
		// $this->display('home_ddxx');
	    $settings = include( APP_PATH . 'Home/Conf/settings.php' );
       // dump($settings['bx_pd_time']);die;
		$ppddxx=M('ppdd')->where(array('id'=>I('get.id')))->find();
		$g_user=M('user')->where(array('UE_account'=>$ppddxx['g_user']))->find();
		$g_user_t=M('user')->where(array('UE_account'=>$g_user['ue_accname']))->find();
		$p_user=M('user')->where(array('UE_account'=>$ppddxx['p_user']))->find();
		$p_user_t=M('user')->where(array('UE_account'=>$p_user['ue_accname']))->find();
        // if(!$ppddxx['date2'] && $ppddxx['zt'] == 0){
//            echo 1;
            $ppddxx['times'] = intval($settings['bx_pd_time'])*3600 + strtotime($ppddxx['date']);
            $ppddxx['times'] = $ppddxx['times'] <= 0 ? 0 : $ppddxx['times'].'000';
            $ppddxx['timetype'] = 1;
            // var_dump($ppddxx);exit();
//         }else{
// //            echo 2;
//             if($ppddxx['zt'] != 2){
// //                dump(intval($settings['cold3_user_time'])*86400);
// //                dump(time()-strtotime($ppddxx['date']));
//                 $ppddxx['times'] = intval($settings['cold3_user_time'])*3600 + strtotime($ppddxx['date']);
//                 $ppddxx['times'] = $ppddxx['times'] <= 0 ? 0 : $ppddxx['times'].'000';
//                 $ppddxx['timetype'] = 2;
//             }
//         }
// //        dump($ppddxx);die;
		$this->ppddxx=$ppddxx;
		$this->g_user=$g_user;
		$this->p_user=$p_user;
		$this->g_user_t=$g_user_t;
		$this->p_user_t=$p_user_t;
		$this->display('home_ddxx');
	}
	
	public function home_ddxx_ly(){
		$ppddxx=M('ppdd')->where(array('id'=>I('get.id')))->find();;
		$this->ppddxx=$ppddxx;
		
		
		
		//////////////////----------
		$User = M ( 'ppdd_ly' ); // 实例化User对象
		
		$map['ppdd_id']=I('get.id');
		$list = $User->where ( $map )->select ();
		$this->assign ( 'list', $list ); // 赋值数据集
		//dump($list);die;
		/////////////////----------------
		
		
		
		$this->display('home_ddxx_ly');
	}
	
	
	public function home_ddxx_ly_cl(){
		 
		$data_P = I ( 'post.' );
		//echo strlen(trim($data_P['mesg']));die;
		$ppddxx=M('ppdd')->where(array('id'=>$data_P['id']))->find();
		
		$user1 = M ();
		if ($ppddxx['p_user']<>$_SESSION['uname']&&$ppddxx['g_user']<>$_SESSION['uname']) {
		
			die("<script>alert('非法操作！');history.back(-1);</script>");
		} elseif( strlen(trim($data_P['mesg']))<1) {
		    die("<script>alert('留言内容不能为空！');history.back(-1);</script>");
		}else {
			$userData = M ( 'user' )->where ( array (
					'UE_ID' => $_SESSION ['uid']
			) )->find ();
		
			$record['ppdd_id'] = $ppddxx['id'];
			$record['user']	= $_SESSION['uname'];
			$record['user_nc']	= $userData['ue_theme'];
			$record['nr']	= $data_P['mesg'];
			$record['date']		= date ( 'Y-m-d H:i:s', time () );;
		
			$reg = M ( 'ppdd_ly' )->add ( $record );
				
		
		
		
			if ($reg) {
				$this->success( '留言成功!' );
			} else {
				$this->success( '留言失败!' );
			}
		
		}
		
	}
	
	public function home_ddxx_pcz(){
		
	
	$this->id = I ( 'get.id' );
    $this->assign('sid',session_id());
	
		$this->display('home_ddxx_pcz');
	}
	
	
//确认打款
	public function home_ddxx_pcz_cl()
    {
        $settings = include( APP_PATH . 'Home/Conf/settings.php' );
        $data_P = I('post.');
 // var_dump($data_P);exit();
//        dump($data_P);
        //echo strlen(trim($data_P['mesg']));die;
        $ppddxx = M('ppdd')->where(array('id' => $data_P['id'], 'zt' => '0'))->find();
//        dump($ppddxx);dump($_SESSION);die;
        if ($ppddxx['p_user'] <> $_SESSION['uname']) {
            die("<script>alert('非法操作！');history.back(-1);</script>");
        } elseif ($data_P['comfir2'] <> '1') {
            die("<script>alert('请选择,我已完成打款！');history.back(-1);</script>");
        } elseif (empty($data_P['content'])) {
            die("<script>alert('请填写留言信息');history.back(-1);</script>"); 
        }else {
            if ($data_P['comfir2'] == '1') {
                if ($_FILES['img']['name'] != null) {
                    $data_P['img'] = $this->_upload('Pic');
                    $data_P['img'] = 'Uploads/'.$data_P['img'];
                } else
                {
                    $data_P['img'] = '';
                    die("<script>alert('亲爱的，请上传真实汇款证明截图，上传假图可能被封号额');history.back(-1);</script>");
                }
                 // $tgbz = M('tgbz')->where(['id'=>$ppddxx['p_id']])->find();
                // switch($tgbz['waitday']){
                //     case 1:
                //         $lixi = (float)$settings['one'];
                //         $sxf = (float)$settings['ones'];
                //         break;
                //     case 3:
                //         $lixi = (float)$settings['three'];
                //         $sxf = (float)$settings['threes'];
                //         break;
                //     case 7:
                //         $lixi = (float)$settings['seven'];
                //         $sxf = (float)$settings['sevens'];
                //         break;
                // }
                // if(time()-strtotime($ppddxx['date']) > intval($settings['cuttime'])*3600){
                //     $ly1 = $ppddxx['jb']*$lixi*0.5/100;
                // }else{
                //     $ly1 = $ppddxx['jb']*$lixi/100;
                // }
                // $sxf = $sxf*$ppddxx['jb']/100;
                M('ppdd')->where(array('id' => $data_P['id'], 'zt' => '0'))->save(
                    array(
                        'pic'      => $data_P['img'],
                        'zt'       => '1',
                        'date_hk'  => date('Y-m-d H:i:s', time()),
                        'ly'=>$data_P['content'],
                        // 'ly2'=>$sxf
                    )
                );
            }
//            if ($data_P['content'] <> '') {
//                $userData = M('user')->where(array('UE_ID' => $_SESSION['uid']))->find();
//                $record['ppdd_id'] = $ppddxx['id'];
//                $record['user'] = $_SESSION['uname'];
//                $record['user_nc'] = $userData['ue_theme'];
//                $record['nr'] = $data_P['content'];
//                $record['date'] = date('Y-m-d H:i:s', time());
//                $reg = M('ppdd_ly')->add($record);
//                M('ppdd')->where([''])->setField('date1',date('Y-m-d H:i:s', time()));
//            }

            //发送短信
            vendor("Sendsms.sendsms");
            $send = new \Sendsms();
            $get_user = M('user')->where(array('UE_account' => $ppddxx['g_user']))->find();

            if ($get_user['ue_account']) {
                $mes = $send->my_send($get_user['ue_phone'], "您好！您申请提现的资金：".$ppddxx['jb']."元，对方已打款。请查收！【民生互助】");
            }
            die("<script>alert('提交成功,请联系对方确认收款！');parent.location.reload();</script>");
            // $this->success('提交成功,请联系对方确认收款！',U('index/index'));


        }
        die("<script>alert('提交失败,请联系管理员！');history.back(-1);</script>");

    }
	
	public function home_ddxx_gcz(){
	
	
		$this->id = I ( 'get.id' );
	
		$this->display('home_ddxx_gcz');
	}
	
	public function t(){
		$tgbztjmax=M('tgbz')->where("user='".$_SESSION ['uname']."'")->max('jb');
		dump($tgbztjmax);die;
				//echo $tgbztj['jb'].$data_P ['amount'];die;
		if ($tgbztjmax['jb'] > $data_P ['amount']) {
			die("<script>alert('帮助金额不能大于最大排单金额');history.back(-1);</script>");
		}
	}
	
	//确认收款

	public function home_ddxx_gcz_cl(){
		$data_P = I ( 'post.' );
		$ppddxx=M('ppdd')->where(array('id'=>$data_P['id'],'zt'=>'1'))->find();	
		// if ($ppddxx['g_user']<>$_SESSION['uname']) {
		// 	die("<script>alert('非法操作！');history.back(-1);</script>");
		// }elseif($data_P['comfir']<>'1'&&$data_P['comfir']<>'2'&&$data_P['comfir']<>'3') {
		// 	die("<script>alert('请选择,确认收款或未收到款投诉！');history.back(-1);</script>");
		// }elseif($ppddxx['ts_zt']=='3') {
		// 	die("<script>alert('6小时未确认收款,已被投诉！');history.back(-1);</script>");
		// }else {
	    if ($ppddxx['g_user']<>$_SESSION['uname']) {
			die("<script>alert('非法操作！');history.back(-1);</script>");
		}else {
		    if($data_P['comfir']=='1'){
				/* NOTED BY SKYRIM: 确认收款 */
				// added by skyrim 
				// purpose: calc pre-deposit interest
				// version: 9
				$settings = include( APP_PATH . 'Home/Conf/settings.php' );

                M('ppdd')->where(array('id'=>$data_P['id'],'zt'=>'1'))->save(array('zt'=>'2','pic2'=>$data_P['img'],'date_hk1'=>date('Y-m-d H:i:s')));//更新此订单状态
                $txyqr=M('ppdd')->where(array('g_id'=>$ppddxx['g_id'],'zt'=>'2'))->sum('jb');

				$txzs=M('jsbz')->where(array('id'=>$ppddxx['g_id']))->find();
				if($txzs['jb']==$txyqr){
					M('jsbz')->where(array('id'=>$ppddxx['g_id']))->save(array('qr_zt'=>'1'));//提现订单已确认
				}

				/* NOTED BY SKYRIM: P - 充值订单 */
				$czyqr=M('ppdd')->where(array('p_id'=>$ppddxx['p_id'],'zt'=>'2'))->sum('jb');
				$czzs=M('tgbz')->where(array('id'=>$ppddxx['p_id']))->find();
				if($czzs['jb']==$czyqr){
					M('tgbz')->where(array('id'=>$ppddxx['p_id']))->save(array('qr_zt'=>'1'));//提现订单已确认
				}
			    $tgbz_user_xx=M('user')->where(array('UE_account'=>$ppddxx['p_user']))->find();//充值人详细
			    if($tgbz_user_xx['jh']==0){
					M('user')->where(array('UE_account'=>$ppddxx['p_user']))->save(array('jh'=>1));
				}
                M('user')->where(array('UE_account'=>$ppddxx['p_user']))->setInc('UE_money',$tgbz_user_xx['li_lv']*$ppddxx['jb']/100);
                M('user')->where(array('UE_account'=>$ppddxx['p_user']))->setInc('UE_money',$ppddxx['jb']);
                $lx_data ["UG_account"] = $tgbz_user_xx['ue_account']; // 登入转出账户
                $lx_data ["UG_type"] = 'jb';
			    $lx_data ["UG_money"] = '+'.($tgbz_user_xx['li_lv']*$ppddxx['jb']/100); //
			    $lx_data ["UG_dataType"] = 'pdxizr'; // 金币转出
			    $lx_data ["UG_note"] = '排单利息转入'; 
			    $lx_data["UG_getTime"]	= date ( 'Y-m-d H:i:s', time () ); //操作时间
		     	M ( 'userget' )->add ( $lx_data );
                
                $lx_data ["UG_account"] = $tgbz_user_xx['ue_account']; // 登入转出账户
                $lx_data ["UG_type"] = 'jb';
			    $lx_data ["UG_money"] = '+'.$ppddxx['jb']; //
			    $lx_data ["UG_dataType"] = 'tgbz'; // 金币转出
			    $lx_data ["UG_note"] = '提供帮助本金转入'; 
			    $lx_data["UG_getTime"]	= date ( 'Y-m-d H:i:s', time () ); //操作时间
		     	M ( 'userget' )->add ( $lx_data );
               
                /**推荐奖金
                 * 一代6%
                 * 二代2%
                 * 四代1%
                 **/
                if ($tgbz_user_xx['ue_accname'] <> ''){
                    $this_node = $tgbz_user_xx['ue_accname'];
                    $i = $settings['max_user_level'];
                    $shaoshang = $settings['shaoshang'];
                    while ($i --){
                        $uname = M('user')->where(array('UE_account' => $this_node))->find();
                        if ($this_node && strlen($this_node)){
                              //烧伤
                              if ($shaoshang == 1) {
                                  $redaxiao = M('tgbz')->where(['user' => $this_node])->order('date desc')->group('date')->limit(1)->getField('sum(jb)');
                                  if ($redaxiao) {
                                      if ($redaxiao < $ppddxx['jb']) {
                                          $ppddmoney = $redaxiao;
                                      } else {
                                          $ppddmoney = $ppddxx['jb'];
                                      }
                                  } else {
                                      $ppddmoney = 0;
                                  }
                              }else{
                                  $ppddmoney = $ppddxx['jb'];
                              }
                              $num = M('user')->where(['UE_accName'=>$uname])->select();
                              //不烧伤
                              if (($settings['max_user_level'] - $i) == 1){
                                  $this_node = masses_j($this_node, $ppddmoney * floatval($settings['td_j'.($settings['max_user_level'] - $i)]/100), '一代奖' . (floatval($settings['td_j'.($settings['max_user_level'] - $i)] ). '%'),$ppddmoney,2);
                              } elseif (($settings['max_user_level'] - $i) == 2){
                                  $this_node = masses_j($this_node, $ppddmoney * floatval($settings['td_j'.($settings['max_user_level'] - $i)]/100),  '二代奖' . (floatval($settings['td_j'.($settings['max_user_level'] - $i)] ). '%'),$ppddmoney,2);
                              } elseif (($settings['max_user_level'] - $i) == 3){
                                  $tgbz_user_xj=M('user')->where(array('UE_account'=>$this_node))->find();
                            	  $this_node = $tgbz_user_xj['ue_accname'];
                              }elseif (($settings['max_user_level'] - $i) == 4){
                                  $this_node = masses_j($this_node, $ppddmoney *floatval($settings['td_j'.($settings['max_user_level'] - $i)]/100),  (floatval($settings['td_j'.($settings['max_user_level'] - $i)] ). '%'),$ppddmoney,2);
                              }else{
                                  $this_node = $uname['ue_accname'];
                              }
                        }else{
                            break;
                        }

                    }
                }
                //发送短信
					vendor("Sendsms.sendsms");
					$send = new \Sendsms();
					$get_user=M('user')->where(array('UE_account'=>$ppddxx['p_user']))->find();
					if ($get_user['ue_account']) $mes =$send->my_send($get_user['ue_account'], "尊敬的客户，您的账户资金有变动，请登录网站确认！【民生互助】");
				die("<script>alert('此次交易成功！');parent.location.reload();</script>");
                 // $this->success('此次交易成功！',U('index/index'));
				
	
				
			}elseif($data_P['comfir']=='2'){
				if($ppddxx['ts_zt']=='2'){
					die("<script>alert('您已经投诉过了,请等待管理员审核');history.back(-1);</script>");
				}else{				
					if($data_P['img']==''){
						die("<script>alert('请上传截图！');history.back(-1);</script>");
					}else{
					    M('ppdd')->where(array('id'=>$data_P['id'],'zt'=>'1'))->save(array('ts_zt'=>'2','pic2'=>$data_P['img']));
					    die("<script>alert('投诉成功,等待管理员审核,如果在审核过程中你收到款了,您还可以确认收款！');history.back(-1);</script>");
					}
				}
			}
		}
	}

	public function home_ddxx_pic_no(){
	
	
		$this->id = I ( 'get.id' );
	
		$this->display('home_ddxx_pic_no');
	}
	
	public function home_ddxx_g_wdk(){
	
	
		$this->id = I ( 'get.id' );
	
		$this->display('home_ddxx_g_wdk');
	}
	public function home_ddxx_g_wqr(){
	
	
		$this->id = I ( 'get.id' );
	
		$this->display('home_ddxx_g_wqr');
	}
	
	public function home_ddxx_g_wdk_cl(){
	
		$data_P = I ( 'post.' );
		
		
		
		
// 		$NowTime = '2015-07-01 01:56:17';
// 		$aab=strtotime($NowTime);
// 		$aab2=$aab+86400+86400;
		
// 		echo "Today:",date('Y-m-d H:i:s',$aab),"<br>";
// echo "Tomorrow:",date('Y-m-d H:i:s',$aab2);die;
	
		

		
		
		
		
		//echo strlen(trim($data_P['mesg']));die;
		$ppddxx=M('ppdd')->where(array('id'=>$data_P['id'],'zt'=>'0'))->find();
		$NowTime = $ppddxx['date'];
		$aab=strtotime($NowTime);
		$aab2=$aab+86400+86400;
		$bba = date('Y-m-d H:i:s',time());
		$bba2=strtotime($bba);
	
		if ($ppddxx['g_user']<>$_SESSION['uname']) {
	
			die("<script>alert('非法操作！');history.back(-1);</script>");
		}elseif($aab2>$bba2) {
			die("<script>alert('汇款时间未超过48时小,暂不能投诉,如未打款,请与提供帮助者取得联系！');history.back(-1);</script>");
		}elseif($data_P['comfir']<>'1'&&$data_P['comfir']<>'2') {
			die("<script>alert('请选择,确认投诉！');history.back(-1);</script>");
		}elseif($ppddxx['ts_zt']=='1'&&$data_P['comfir']<>'2') {
			die("<script>alert('您已经投诉过了,请等待管理员处理！');history.back(-1);</script>");
		}else {
			
			//echo '成功';die;
			if($data_P['comfir']=='1'){
				M('ppdd')->where(array('id'=>$data_P['id'],'zt'=>'0'))->save(array('ts_zt'=>'1'));
				die("<script>alert('投诉提交成功,请等待管理员审核通过！');parent.location.reload();</script>");
			}elseif($data_P['comfir']=='2'){
				M('ppdd')->where(array('id'=>$data_P['id'],'zt'=>'0'))->save(array('ts_zt'=>'0'));
				die("<script>alert('投诉取消成功,卖家可以继续汇款！');parent.location.reload();</script>");
			}
	
	
	
	
			
	
	
		}
	}
	
	
	
	
	public function home_ddxx_g_wqr_cl(){
	
		$data_P = I ( 'post.' );
	
	//dump($data_P);die;
	
	
		// 		$NowTime = '2015-07-01 01:56:17';
		// 		$aab=strtotime($NowTime);
		// 		$aab2=$aab+86400+86400;
	
		// 		echo "Today:",date('Y-m-d H:i:s',$aab),"<br>";
		// echo "Tomorrow:",date('Y-m-d H:i:s',$aab2);die;
	
	
	
	
	
	
	
		//echo strlen(trim($data_P['mesg']));die;
		$ppddxx=M('ppdd')->where(array('id'=>$data_P['id'],'zt'=>'1'))->find();
		$NowTime = $ppddxx['date_hk'];
		$aab=strtotime($NowTime);
		$aab2=$aab+86400+86400;
		$bba = date('Y-m-d H:i:s',time());
		$bba2=strtotime($bba);
	
		if ($ppddxx['p_user']<>$_SESSION['uname']) {
	
			die("<script>alert('非法操作！');history.back(-1);</script>");
		}elseif($aab2>$bba2) {
			die("<script>alert('确认时间未超过48时小,暂不能投诉,如未确认,请与对方取得联系！');history.back(-1);</script>");
		}elseif($data_P['comfir']<>'1'&&$data_P['comfir']<>'2') {
			die("<script>alert('请选择,确认或取消！');history.back(-1);</script>");
		}elseif($ppddxx['ts_zt']=='2') {
			die("<script>alert('您已被对方投诉,请与对方取得联系！');history.back(-1);</script>");
		}else{
			
			
			
			
			
			
			
			
			
		
			//dump($data_P);die;
			//echo strlen(trim($data_P['mesg']));die;
			
			
			
			
				if($data_P['comfir']=='1'){
			
					M('ppdd')->where(array('id'=>$data_P['id'],'zt'=>'1'))->save(array('zt'=>'2'));//更新此订单状态
			
					$txyqr=M('ppdd')->where(array('g_id'=>$ppddxx['g_id'],'zt'=>'2'))->sum('jb');
			
			
					$txzs=M('jsbz')->where(array('id'=>$ppddxx['g_id']))->find();
					if($txzs['jb']==$txyqr){
						M('jsbz')->where(array('id'=>$ppddxx['g_id']))->save(array('qr_zt'=>'1'));//提现订单已确认
					}
			
			
					$czyqr=M('ppdd')->where(array('p_id'=>$ppddxx['p_id'],'zt'=>'2'))->sum('jb');
			
			
					$czzs=M('tgbz')->where(array('id'=>$ppddxx['p_id']))->find();
					if($czzs['jb']==$czyqr){
						M('tgbz')->where(array('id'=>$ppddxx['p_id']))->save(array('qr_zt'=>'1'));//提现订单已确认
					}
			
			
			
					////更新提现订单状态
			
					//M('tgbz')->where(array('id'=>$ppddxx['p_id']))->setInc('jycg_ds',1);
			
					// 			    $tgbzcs=M('tgbz')->where(array('id'=>$ppddxx['p_id']))->find();
					// 			    if($tgbzcs['cf_ds']==$tgbzcs['jycg_ds']){
					// 			    	M('tgbz')->where(array('id'=>$ppddxx['p_id']))->save(array('qr_zt'=>'1'));//更新充值订单状态
					// 			    }
			
					//推荐奖10%
					 
					$tgbz_user_xx=M('user')->where(array('UE_account'=>$ppddxx['p_user']))->find();//充值人详细
					//echo $ppddxx['p_id'];die;
					if($tgbz_user_xx['ue_accname']<>''){
						$money=$ppddxx['jb']*0.1;
						$accname_zq=M('user')->where(array('UE_account'=>$tgbz_user_xx['ue_accname']))->find();
						M('user')->where(array('UE_account'=>$tgbz_user_xx['ue_accname']))->setInc('UE_money',$money);
						$accname_xz=M('user')->where(array('UE_account'=>$tgbz_user_xx['ue_accname']))->find();
						 //$note3 = "推荐奖10%";
						// added by skyrim
						// purpose: custom share
						// version: 6.0
						$settings = include( APP_PATH . 'Home/Conf/settings.php' );
						$note3 = "推荐奖" . ( floatval($settings['tjr_share']) ) . "%";
						$money=$ppddxx['jb']*10*floatval($settings['tjr_share'])/100;
						// added ends
						$record3 ["UG_account"] = $tgbz_user_xx['ue_accname']; // 登入转出账户
						$record3 ["UG_type"] = 'jb';
						$record3 ["UG_allGet"] = $accname_zq['ue_money']; // 金币
						$record3 ["UG_money"] = '+'.$money; //
						$record3 ["UG_balance"] = $accname_xz['ue_money']; // 当前推荐人的金币馀额
						$record3 ["UG_dataType"] = 'tjj'; // 金币转出
						$record3 ["UG_note"] = $note3; // 推荐奖说明
						$record3["UG_getTime"]		= date ( 'Y-m-d H:i:s', time () ); //操作时间
						$reg4 = M ( 'userget' )->add ( $record3 );
						 
						//$money_jlj1=;
						 
						// added by skyrim
						// purpose: custom share
						// version: 6.0
						$this_node = $tgbz_user_xx['ue_accname'];
						$i = $settings['max_jl_level'];
						while( $i -- ){
							if( $this_node && strlen( $this_node ) ){
							 $this_node = masses_j( $this_node, $ppddxx['jb']*floatval($settings['masses_share'][1+$settings['max_user_level']-$i]));
							}
						}
						
						$this_node = $tgbz_user_xx['ue_accname'];
						$i = $settings['max_jl_level'];
						while( $i -- ){
							if( $this_node && strlen( $this_node ) ){
								$this_node = jlj( $this_node, $ppddxx['jb']*floatval($settings['jl_share'][1+$settings['max_jl_level']-$i]), '经理奖' . ( floatval($settings['jl_share'][1+$settings['max_jl_level']-$i]) * 100 ) . '%' );
							}
						}
						// added ends
						// deleted by skyrim
						// purpose: custom share
						// version: 6.0
						// if($tgbz_user_xx['zcr']<>''){
						// 	$zcr2=jlj($tgbz_user_xx['zcr'],$ppddxx['jb']*0.05,'经理奖5%');
						// 	if($zcr2<>''){
						// 		$zcr3=jlj($zcr2,$ppddxx['jb']*0.03,'经理奖3%');
						// 		//echo $ppddxx['p_user'].'sadfsaf';die;
						// 		if($zcr3<>''){
						// 			$zcr4=jlj($zcr3,$ppddxx['jb']*0.01,'经理奖1%');
						// 			if($zcr4<>''){
						// 				$zcr5=jlj($zcr4,$ppddxx['jb']*0.0025,'经理奖0.25%');
						// 				if($zcr5<>''){
						// 					jlj($zcr5,$ppddxx['jb']*0.001,'经理奖0.1%');
						// 				}
						// 			}
						// 		}
						// 	}
						// }
						// deleted ends
						 
						 
						 
						 
						 
					}
					 
					 
					 
					 
					 
					 
					die("<script>alert('系统自动处理成功！');parent.location.reload();</script>");
			
			
				}
			
			
			
					

			
			
			
	
	
	
	
	
				
	
	
		}
	}
	
	public function tgbz_list_cf(){
	
	
		$User = M ( 'tgbz' ); // 实例化User对象
		$data = I ( 'post.user' );
	
		$this->z_jgbz=$User->sum('jb');
		$this->z_jgbz2=$User->where(array('qr_zt'=>'1'))->sum('jb');
		$this->z_jgbz3=$User->where(array('qr_zt'=>array('neq','1')))->sum('jb');
		//$map ['UG_dataType'] = array('IN',array('mrfh','tjj','kdj','mrldj','glj'));
	
		$map['zt']=0;
	
		if(I ( 'get.cz' )==1){
			$map['zt']=1;
		}
		if($data<>''){
			$map['user']=$data;
		}
		$count = $User->where ( $map )->count (); // 查询满足要求的总记录数
		//$page = new \Think\Page ( $count, 3 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
	
		$p = getpage($count,20);
	
		$list = $User->where ( $map )->order ( 'id DESC' )->limit ( $p->firstRow, $p->listRows )->select ();
		//dump($list);die;
		$this->assign ( 'list', $list ); // 赋值数据集
		$this->assign ( 'page', $p->show() ); // 赋值分页输出
	
	
	
		$this->display('index/tgbz_list_cf');
	}
	
	
	public function jsbz_list_cf(){
	
	
	
		$User = M ( 'jsbz' ); // 实例化User对象
		$data = I ( 'post.user' );
	
		$this->z_jgbz=$User->sum('jb');
		$this->z_jgbz2=$User->where(array('qr_zt'=>'1'))->sum('jb');
		$this->z_jgbz3=$User->where(array('qr_zt'=>array('neq','1')))->sum('jb');
		//$map ['UG_dataType'] = array('IN',array('mrfh','tjj','kdj','mrldj','glj'));
	
		$map['zt']=0;
	
		if(I ( 'get.cz' )==1){
			$map['zt']=1;
		}
		if($data<>''){
			$map['user']=$data;
		}
		$count = $User->where ( $map )->count (); // 查询满足要求的总记录数
		//$page = new \Think\Page ( $count, 3 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
	
		$p = getpage($count,20);
	
		$list = $User->where ( $map )->order ( 'id DESC' )->limit ( $p->firstRow, $p->listRows )->select ();
		//dump($list);die;
		$this->assign ( 'list', $list ); // 赋值数据集
		$this->assign ( 'page', $p->show() ); // 赋值分页输出
	
	
	
		$this->display('index/jsbz_list_cf');
	}
	
	public function tgbz_list_cf_cl(){
		$data=I('post.');
		$p_user=M('tgbz')->where(array('id'=>$data['pid']))->find();
		if (! preg_match ( '/^[0-9,]{1,100}$/', I('post.arrid') )) {
			$this->error( '格式不对!' );
			die;
		}
		$arr = explode(',',I('post.arrid'));
		//dump($arr);
		if(array_sum($arr)<>$p_user['jb']){
			$this->error( '拆分金额不对!' );
			die;
		}
	
    	// added by skyrim
    	// purpose: check money in range
    	// version: 4
		$settings = include( dirname( APP_PATH ) . '/User/Home/Conf/settings.php' );
		
		foreach( $arr as $q ){
			if( $settings['supply_money_upper_limit'] < $q || $q < $settings['supply_money_lower_limit'] ){
				$this->error('金额不在范围内!');
				
				return;
			}
		}
    	// added ends
	
	
	
		$p_user1=M('tgbz')->where(array('id'=>$data['pid']))->find();
	
		$pipeits=0;
		foreach($arr as $value){
			if($value<>''){
				$data2['zffs1']=$p_user1['zffs1'];
				$data2['zffs2']=$p_user1['zffs2'];
				$data2['zffs3']=$p_user1['zffs3'];
				$data2['user']=$p_user1['user'];
				$data2['jb']=$value;
				$data2['user_nc']=$p_user1['user_nc'];
				$data2['user_tjr']=$p_user1['user_tjr'];
				$data2['date']=$p_user1['date'];
				$data2['zt']=$p_user1['zt'];
				$data2['qr_zt']=$p_user1['qr_zt'];
				$varid = M('tgbz')->add($data2);
				$pipeits++;
			}
			 
	
		}
	
		M('tgbz')->where(array('id'=>$data['pid']))->delete();
	
	
	
	
		$this->success('匹配成功!拆分成'.$pipeits.'条订单!');
	}
	
	public function jsbz_list_cf_cl(){
		$data=I('post.');
		$p_user=M('jsbz')->where(array('id'=>$data['pid']))->find();
		if (! preg_match ( '/^[0-9,]{1,100}$/', I('post.arrid') )) {
			$this->error( '格式不对!' );
			die;
		}
		$arr = explode(',',I('post.arrid'));
		//dump($arr);
		if(array_sum($arr)<>$p_user['jb']){
			$this->error( '拆分金额不对!' );
			die;
		}
    	// added by skyrim
    	// purpose: check money in range
    	// version: 4
		$settings = include( dirname( APP_PATH ) . '/User/Home/Conf/settings.php' );
		
		foreach( $arr as $q ){
			if( $settings['supply_money_upper_limit'] < $q || $q < $settings['supply_money_lower_limit'] ){
				$this->error('金额不在范围内!');
				
				return;
			}
		}
    	// added ends
		 
		 
		 
		 
		$p_user1=M('jsbz')->where(array('id'=>$data['pid']))->find();
		 
		$pipeits=0;
		foreach($arr as $value){
			if($value<>''){
				$data2['zffs1']=$p_user1['zffs1'];
				$data2['zffs2']=$p_user1['zffs2'];
				$data2['zffs3']=$p_user1['zffs3'];
				$data2['user']=$p_user1['user'];
				$data2['jb']=$value;
				$data2['user_nc']=$p_user1['user_nc'];
				$data2['user_tjr']=$p_user1['user_tjr'];
				$data2['date']=$p_user1['date'];
				$data2['zt']=$p_user1['zt'];
				$data2['qr_zt']=$p_user1['qr_zt'];
				$varid = M('jsbz')->add($data2);
				$pipeits++;
			}
	
			 
		}
		 
		M('jsbz')->where(array('id'=>$data['pid']))->delete();
		 
		 
		 
		 
		$this->success('匹配成功!拆分成'.$pipeits.'条订单!');
	}
	 
	
}