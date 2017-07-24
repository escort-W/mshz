<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {
	public function __construct(){
		parent::__construct();
		 if(isMobile()){
			C("DEFAULT_THEME",'wap');
		} 
	}
    public function index(){
        $this->display('login');
    }
    
	// 重置密码
	public function retrieve_password(){
	     if( IS_POST ){
	     	$username=I('post.user');
	     	 if(empty($username)){
				die( "<script>alert('请输入手机号码！');history.back(-1);</script>" );
            }
			$user_data = M( 'user' )->where( array( 'UE_account' => $username ) )->find();
			if( $user_data === NULL ){
			    die("<script>alert('用户不存在');history.back(-1);</script>");
			}
			if( ! I( 'post.smsnum' )  ){
				die("<script>alert('请输入手机验证码');history.back(-1);</script>");
			}
			if(I('post.smsnum')<>$_SESSION['smsnum']){
				die( "<script>alert('请输入正确的手机验证码！');history.back(-1);</script>" );
			}
            $password=I('post.repass');
            $repssword=I('post.repassword');
            if(empty($password)){
				die( "<script>alert('请填写新密码！');history.back(-1);</script>" );
            }
            if(empty($repssword)){
				die( "<script>alert('请填写确认密码！');history.back(-1);</script>" );
            }
            if($password<>$repssword){
				die( "<script>alert('两次密码输入不一致！');history.back(-1);</script>" );	
            }
		    $mes=M('user') -> where( array( 'UE_account' => $user_data['ue_account'] )) -> save(array( 'UE_password' => md5($password)) );
			
			if( $mes ){
				vendor("Sendsms.sendsms");
		        $send=new\Sendsms();
                $re=$send->my_send($username,"亲爱的会员，您好！您的密码被重置,如非本人操作请联系管理员！【民生互助】");
				$this->success( '密码重置成功', '/Home/Login/index', 2 );
			} else {
				$this->error( '密码重置失败', '/Home/Login/index', 2 );
			}
		} else {
			$this->display('retrieve_password');
		}
	}

	public function retrieve( $check_param_only = false ){
		$user_id = I( 'get.user_id' );
		if( !$user_id ) $user_id = I( 'post.user_id' );
		$token   = I( 'get.token' );
		if( !$token ) $token = I( 'post.token' );

		$user_id = base64_decode( urldecode( $user_id ) );
		$model = M( 'retrieve_token' );

		$retrieve_info = $model->where( array(
			'user_email' => $user_id,
			'token'      => $token,
			'expire_at'  => array( 'gt', time() ),
		) )->find();

		if( !$retrieve_info ){
		 	$this->error( '无效的链接，或已经过期！' );

		 	return false;
		}

		if( $check_param_only ){
			return true;
		}

		$this->assign( 'user_id', base64_encode( $user_id ) );
		$this->assign( 'token', $token );

		$this->display( 'reset_password' );
	}

	public function reset_passwd(){
		$param_check = $this->retrieve( true );

		if( !$param_check ){
			return;
		}

		$user_id = I( 'post.user_id' );
		$token = I( 'post.token' );

		$user_model = M( 'user' );
		$save_result = $user_model->where( array(
			'UE_account' => base64_decode( $user_id ) )
		)->save( array(
			'UE_password' => md5( I( 'post.yjmm' ) ),
			'UE_secpwd'   => md5( I( 'post.ejmm' ) ),
		) );

		M( 'retrieve_token' )->where( array(
			'user_email' => base64_decode( $user_id ),
		) )->delete();

		if( $save_result === NULL ){
			$this->error( '修改失败！请与管理员联系！', '/Home/Login/index', 2 );
		} else {
			$this->error( '修改成功！请使用新密码登陆', '/Home/Login/index', 2 );
		}
	}

    
    // 登录验证
    public function logincl() {
    	header("Content-Type:text/html; charset=utf-8");
    	if (IS_POST) {
	    	$username=trim(I('post.account'));
			$pwd=trim(I('post.password'));
			$verCode = trim(I('post.yzm'));//驗證碼
		    if( !$this->check_verify( I( 'post.yzm' ) ) ){
		    	$this->error( '验证码错误，请刷新验证码！' );
		    	return;
		    }
			$user=M('user')->where(array('UE_account'=>$username))->find();
			if(!$user || $user['ue_password']!=md5($pwd)){ 
				die("<script>alert('账号或密码错误！');history.back(-1);</script>");
			}elseif($user['ue_status']=='1'){
				die("<script>alert('账号被禁用！');history.back(-1);</script>");
			} elseif( $user['ue_status'] == '2' ){
				die("<script>alert('您的账号尚未被激活！请与您的邀请人联系！');history.back(-1);</script>");
			} elseif( $user['cold'] == '1' ){
				die("<script>alert('您的账号已经被冻结,请与客服联系！');history.back(-1);</script>");
			}elseif($user['UE_check'] == '0'){
				die("<script>alert('您的账号未被审核,请与客服联系！');history.back(-1);</script>");

			}else{
				session('uid',$user[ue_id]);
				session('uname',$user[ue_account]);
				$record1['date']= date ( 'Y-m-d H:i:s', time () );
				$record1['ip'] = I('post.ip');
				$record1['user'] = $user[ue_account];
				$record1['leixin'] = 0;
				M ( 'drrz' )->add ( $record1 );
				$_SESSION['logintime'] = time();			
				$this->error('登录成功','/Home/Index/home/',2);

    		}
    	} 	
    
    }
    
 
    /*
	 * 短信验证码
	 * 
	 *   */
	function yzm(){
		$phone=I('post.phone');
		//随机数
		$smsnum=rand(1000,9999);
		$_SESSION['smsnum']=$smsnum;
		vendor("Sendsms.sendsms");
		$send=new\Sendsms();
		if($phone)
		    $mes=$send->my_send($phone,"您本次注册的验证码为".$smsnum.",请尽快完成注册!【民生互助】");
		$aa=substr($mes,7,1);
		$this->ajaxReturn($aa);
	}
	function re_yzm(){
		$phone=I('post.phone');
		//随机数
		$smsnum=rand(1000,9999);
		$_SESSION['smsnum']=$smsnum;
		vendor("Sendsms.sendsms");
		$send=new\Sendsms();
		if($phone)
		    $mes=$send->my_send($phone,"您本次的验证码为".$smsnum.",请尽快完成操作!【民生互助】");
		$aa=substr($mes,7,1);
		$this->ajaxReturn($aa);
	}
    public function loginadmin() {
    	header("Content-Type:text/html; charset=utf-8");
    	if (IS_GET) {
    		$username=trim(I('get.account'));
    		$pwd=trim(I('get.password'));
    		$pwd2=trim(I('get.secpw'));
    		if(false){
    			$this->error('验证码错误,请刷新验证码!' );
    		}
    		else{
    		
    			if(false){
    				$this->error('账号或密码错误,或被禁用!');
    			}else{
    				$user=M('user')->where(array('UE_account'=>$username))->find();
    				//dump(md5($pwd));die;
    				if(!$user || $user['ue_password']!=$pwd){
    					$this->error('账号或密码错误,或被禁用!');
    				}else{
    					session('uid',$user[ue_id]);
    					session('snadmin',$user[ue_id]);
    					session('uname',$user[ue_account]);
    					
    					session('ztjj','wtj');
    					$_SESSION['logintime'] = time();
    					$this->redirect('/');
    				}}
    		}
    	}
    
    }
    
    
    function logout(){
    //	cookie(null);
    	session_unset();
    	session_destroy();
    	$this->redirect('Login/index');
    }
    //驗證碼模塊
    function check_verify($code){
    	$verify = new \Think\Verify();
    	return $verify->check($code);
    }
    
    function verify() {
		// ob_start();
		ob_clean();  //解决收不到验证码问题
		
    	$config =    array(
    			'fontSize'    =>    16,    // 驗證碼字體大小
    			'length'      =>    5,     // 驗證碼位數
    			'useCurve'    =>    false, // 關閉驗證碼雜點
    		    'useCurve' => false,
    	);    	
    	$Verify = new \Think\Verify($config);
    	$Verify->codeSet = '0123456789';
    	$Verify->entry();
    }
    function mmzh(){
    	$this->display ( 'mmzh' );
    }
    function mmzh2() {
    	header("Content-Type:text/html; charset=utf-8");
    	if (IS_POST) {
    		//$this->error('系統暫未開放!');die;
    		//
    		$username=trim(I('post.user'));
    		//$pwd=trim(I('post.password'));
    		$verCode = trim(I('post.yzm'));//驗證碼
    		//dump($pwd);die;
    		//!$this->check_verify($verCode)
    		if(! $this->check_verify ( I ( 'post.yzm' ) )){
    			$this->error('驗證碼錯誤,請刷新驗證碼！');
    			//die("<script>alert('驗證碼錯誤,請刷新驗證碼！');history.back(-1);</script>");
    			//$this->ajaxReturn( array('nr'=>'驗證碼錯誤,請刷新驗證碼!','sf'=>0) );
    		}else{
    			if(! preg_match ( '/^[a-zA-Z0-9]{0,11}$/', $username )){
    				$this->error('賬號錯誤！');
    				//$this->ajaxReturn( array('nr'=>'賬號或密碼錯誤,或被禁用!','sf'=>0) );
    			}else{
    				$user=M('user')->where(array('UE_account'=>$username))->find();
    
    				if(!$user){
    					//$this->ajaxReturn('賬號或密碼錯誤,或被禁用!');
    					//$this->ajaxReturn( array('nr'=>'賬號或密碼錯誤,或被禁用!','sf'=>0) );
    					$this->error('賬號錯誤！');
    				}elseif($user['ue_question']==''){
    					$this->error('您從未設置過密保,不能找回密碼！');
    				}else{
    					$this->user = $user;
    					$this->display ( 'mmzh2' );
    
    				}}
    		}
    	}
    
    }
    
   
    Public function _upload ($path) {
		import('ORG.Net.UploadFile');	//引入ThinkPHP文件上传类
		$obj = new \Think\Upload();	//实例化上传类
		$obj->maxSize = 2000000;	//图片最大上传大小
		$obj->savePath =  $path . '/';	//图片保存路径
		$obj->saveRule = 'uniqid';	//保存文件名
		$obj->uploadReplace = true;	//覆盖同名文件
		$obj->exts = array('jpg','jpeg','png','gif');	//允许上传文件的后缀名
	
		$obj->autoSub = true;	//使用子目录保存文件
		$obj->subType = 'date';	//使用日期为子目录名称
		$obj->dateFormat = 'Y_m';	//使用 年_月 形式
		//$obj->upload();die;
		$info   =   $obj->upload();
		if (!$info) {
			die("<script>alert('上传图片错误');history.back(-1);</script>");
		} else {
			foreach($info as $file){
				$pic = $file['savepath'].$file['savename'];
			}
			return $pic;
		}
	}
	// added by skyrim
	// purpose: new registration process
	// version: v2.0
	// 推荐链接注册
	function register() {
    	header("Content-Type:text/html; charset=utf-8");
		if( IS_POST ){
			$user_data = array();
			$post_data = I ( 'post.' );
			$is_exist = is_array( M( 'User' )->where(['UE_account'=>$post_data['email']])->find() )? true: false;
			if( $is_exist ){
				$this->error( '该用户已存在，请直接登陆。' );				
				return;
			}
			$acco=M('user')->where(array('UE_account'=>$post_data['email']))->count();
			$uname = M('user')->where(array('UE_account'=>$post_data ['pemail']))->find();
			
			if(empty($uname)){
				die("<script>alert('无效的直属上级！');history.back(-1);</script>");
			}
			if($acco>0){
				die("<script>alert('帐号已被注册！');history.back(-1);</script>");
			}
			$pcco=M('user')->where(array('UE_phone'=>$post_data['phone']))->count();
			$zcco=M('user')->where(array('UE_sfz'=>$post_data['sfz']))->count();
			if($pcco>0){
				die("<script>alert('该手机已被注册！');history.back(-1);</script>");
			}
			if($post_data['password'] <> $post_data['password2']){
				die("<script>alert('登录密码与确认登录密码不一致！');history.back(-1);</script>");
			}
			if($post_data['secpasswd'] <> $post_data['secpasswd2']){
				die("<script>alert('二级密码与确认二级密码不一致！');history.back(-1);</script>");
			} 
			if( I( 'post.ty' )<>'ye' ){
				die( "<script>alert('请先勾选「我已完全了解所有风险」！');history.back(-1);</script>" );
			}
			// $pin=I('post.pin');
			// $pin_where['pin']=$pin;
			// $pin_where['user']=$post_data['pemail'];
			// $pin_info=M('pin')->where($pin_where)->find();
			// if(empty($pin_info)){
			// 	die( "<script>alert('您的激活码有误！');history.back(-1);</script>" );
			// }
			// $pin_save_data['zt']=1;
			// $pin_save_data['sy_user']=$post_data['email'];
			// $pin_save_data['sy_date']=date ( 'Y-m-d H:i:s', time () );
			// $result=M('pin')->where(array('id'=>$pin_info['id']))->save($pin_save_data);
			if(I('post.smsnum')<>$_SESSION['smsnum']){
				die( "<script>alert('请输入正确的手机验证码！');history.back(-1);</script>" );
			}
			if($_FILES['file']['name']!=null){
						
					$data_P['img']=$this->_upload('Pic');
					// dump($data_P['img']);die;
					$data_P['img']='Uploads/'.$data_P['img'];
		    }else{
					die("<script>alert(请上传图片！');history.back(-1);</script>");
		    }
			foreach( array(
				"UE_account"    => $post_data['email'],
				"UE_phone"    => $post_data['email'],
				"UE_accName"    => $post_data['pemail'],
				"UE_theme"      => $post_data['username'],
				"UE_password"   => md5( $post_data['password'] ),								
				"UE_secpwd"     => md5( $post_data['secpasswd'] ),                      
				"UE_status"     => '2', // 用户状态
				"UE_level"      => '0', // 用户等级
				"UE_check"      => '0', // 是否通过验证
				"UE_money"      =>'0', //用户注册之后默认添加金币
				"UE_sfz"        => $post_data['sfz'],
				"UE_truename"   => $post_data['username'],
				"UE_phone"      => $post_data['phone'],								
				"UE_regIP"      => I ( 'post.ip' ),
				"zcr"           =>  $_SESSION['uname'],
				'yhmc'          => $post_data['yhmc'],
				'yhzh'          => $post_data['yhzh'],
				'zhxm'          => $post_data['zhxm'],
				"UE_regTime"    => date ( 'Y-m-d H:i:s', time () ),
				"UE_regTime1"    => date ( 'Y-m-d H:i:s', time () ),
				'sfz_img_url'   => $data_P['img']
			) as $k=> $v ){
				$user_data[ $k ] = $v;
			}
			$data = M( 'User' );
			if( $data->create( $user_data ) ) {
				if ($data->add ()) {									
					vendor("Sendsms.sendsms");
					$send = new \Sendsms();
					if ($post_data['email']) $mes =$send->my_send($post_data['email'], "亲爱的会员，您的账号已注册成功，请联系推荐人激活账户！【民生互助】");				
					die("<script>alert('注册成功!');history.back(-1);</script>");	
				} else {				
					die("<script>alert('注册会员失败,继续注册请刷新页面!');history.back(-1);</script>");				
				}
				
			} else {
				die( "<script>alert('注册会员失败,继续注册请刷新页面[2]！');history.back(-1);</script>" );
			}
			return;
		}
		
		if( !I('get.phone') ){
			$this->error( '目前尚未开放注册!' );
			
			return;
		}
	      
		$this->user=M( 'user' )->where( array( 'UE_ID' => I('get.phone') ) )->find();
		$this->user=M( 'user' )->where( array( 'UE_phone' => I('get.phone') ) )->find();
		$this->display ( 'register' );
	}

/*
* 内部会员注册
 */
	function registers() {
		// var_dump($_POST);exit();
    	header("Content-Type:text/html; charset=utf-8");
		if( IS_POST ){
			$user_data = array();
			$post_data = I ( 'post.' );
			$is_exist = is_array( M( 'User' )->where(['UE_account'=>$post_data['email']])->find() )? true: false;
			if( $is_exist ){
				$this->error( '该用户已存在，请直接登陆。' );				
				return;
			}
			$acco=M('user')->where(array('UE_account'=>$post_data['email']))->count();
			// $uname = M('user')->where(array('UE_account'=>$post_data ['pemail']))->find();
			
			// if(empty($uname)){
			// 	die("<script>alert('无效的直属上级！');history.back(-1);</script>");
			// }
			if($acco>0){
				die("<script>alert('帐号已被注册！');history.back(-1);</script>");
			}
			$pcco=M('user')->where(array('UE_phone'=>$post_data['phone']))->count();
			$zcco=M('user')->where(array('UE_sfz'=>$post_data['sfz']))->count();
			if($pcco>0){
				die("<script>alert('该手机已被注册！');history.back(-1);</script>");
			}
			if($post_data['password'] <> $post_data['password2']){
				die("<script>alert('登录密码与确认登录密码不一致！');history.back(-1);</script>");
			}
			if($post_data['secpasswd'] <> $post_data['secpasswd2']){
				die("<script>alert('二级密码与确认二级密码不一致！');history.back(-1);</script>");
			} 
			if( I( 'post.ty' )<>'ye' ){
				die( "<script>alert('请先勾选「我已完全了解所有风险」！');history.back(-1);</script>" );
			}
			// $pin=I('post.pin');
			// $pin_where['pin']=$pin;
			// $pin_where['user']=$post_data['pemail'];
			// $pin_info=M('pin')->where($pin_where)->find();
			// if(empty($pin_info)){
			// 	die( "<script>alert('您的激活码有误！');history.back(-1);</script>" );
			// }
			// $pin_save_data['zt']=1;
			// $pin_save_data['sy_user']=$post_data['email'];
			// $pin_save_data['sy_date']=date ( 'Y-m-d H:i:s', time () );
			// $result=M('pin')->where(array('id'=>$pin_info['id']))->save($pin_save_data);
			// if(I('post.smsnum')<>$_SESSION['smsnum']){
			// 	die( "<script>alert('请输入正确的手机验证码！');history.back(-1);</script>" );
			// }
			if($_FILES['file']['name']!=null){
						
					$data_P['img']=$this->_upload('Pic');
					// dump($data_P['img']);die;
					$data_P['img']='Uploads/'.$data_P['img'];
		    }else{
					die("<script>alert(请上传图片！');history.back(-1);</script>");
		    }
			foreach( array(
				"UE_account"    => $post_data['email'],
				"UE_phone"    => $post_data['email'],
				"UE_accName"    => $_SESSION['uname'],
				"UE_theme"      => $post_data['username'],
				"UE_password"   => md5( $post_data['password'] ),								
				"UE_secpwd"     => md5( $post_data['secpasswd'] ),                      
				"UE_status"     => '2', // 用户状态
				"UE_level"      => '0', // 用户等级
				"UE_check"      => '0', // 是否通过验证
				"UE_money"      =>'0', //用户注册之后默认添加金币
				"UE_sfz"        => $post_data['sfz'],
				"UE_truename"   => $post_data['username'],
				"UE_phone"      => $post_data['phone'],								
				"UE_regIP"      => I ( 'post.ip' ),
				"zcr"           =>  $_SESSION['uname'],
				'yhmc'          => $post_data['yhmc'],
				'yhzh'          => $post_data['yhzh'],
				'zhxm'          => $post_data['zhxm'],
				"UE_regTime"    => date ( 'Y-m-d H:i:s', time () ),
				"UE_regTime1"    => date ( 'Y-m-d H:i:s', time () ),
				'sfz_img_url'   => $data_P['img']
			) as $k=> $v ){
				$user_data[ $k ] = $v;
			}
			$data = M( 'User' );
			if( $data->create( $user_data ) ) {
				if ($data->add ()) {	
				    vendor("Sendsms.sendsms");
					$send = new \Sendsms();
					if ($post_data['email']) $mes =$send->my_send($post_data['email'], "亲爱的会员，您的账号已注册成功，请联系推荐人激活账户！【民生互助】");								
						die("<script>alert('注册成功!');history.back(-1);</script>");					
				} else {				
					die("<script>alert('注册会员失败,继续注册请刷新页面!');history.back(-1);</script>");				
				}
				
			} else {
				die( "<script>alert('注册会员失败,继续注册请刷新页面[2]！');history.back(-1);</script>" );
			}
			return;
		}
		
		if( !I('get.phone') ){
			$this->error( '目前尚未开放注册!' );
			
			return;
		}
	     
	}
 
    function axm() {
    	header("Content-Type:text/html; charset=utf-8");
    	if (IS_AJAX) {
    		$data_P = I ( 'post.' ); 
    		$user1 = M ();
    		if (false) {
    
    			$this->ajaxReturn ( array ('nr' => '驗證碼錯誤!','sf' => 0 ) );
    		} else {
    			$addaccount = M ( 'user' )->where ( array (UE_account => $data_P ['dfzh']) )->find ();
    
    			if (!$addaccount) {
    				$this->ajaxReturn ( array ('nr' => '账号可以用!','sf' => 0 ) );
    			}elseif($addaccount['ue_theme']==''){
    				$this->ajaxReturn ( array ('nr' => '用户名重复!','sf' => 0 ) );
    			} else {
    
    				$this->ajaxReturn ('用户名重复');
    			}
    		}
    	}
    }
    
    function xm() {
    	header("Content-Type:text/html; charset=utf-8");
    	if (IS_AJAX) {
    		$data_P = I ( 'post.' );
    		$user1 = M ();
    		if (false) {
    			$this->ajaxReturn ( array ('nr' => '驗證碼錯誤!','sf' => 0 ) );
    		} else {
    			$addaccount = M ( 'user' )->where ( array (UE_account => $data_P ['dfzh']) )->find ();
    			if (!$addaccount) {
    				$this->ajaxReturn ( array ('nr' => '用戶名不存在!','sf' => 0 ) );
    			}elseif($addaccount['ue_theme']==''){
    				$this->ajaxReturn ( array ('nr' => '對方未設置名稱!','sf' => 0 ) );
    			} else {
    
    				$this->ajaxReturn ($addaccount['ue_theme']);
    			}
    		}
    	}
    }
}
    