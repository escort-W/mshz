<?php

namespace Home\Controller;

use Think\Controller;

class CommonController extends Controller {
	
	public function _initialize() {
		header("Content-Type:text/html; charset=utf-8");
		if(isMobile()){
			C("DEFAULT_THEME",'wap');
		} 
		/*
		if(isMobile() && $_SERVER['SERVER_NAME']=='3b.zzjunyi.cn'){
			echo "<script>alert('请访问手机域名:3bwap.zzjunyi.cn');location.href='http://wap3a.zzjunyi.cn'</script>";
		}  */
		
		$zt=M('system')->where(array('SYS_ID'=>1))->find();
		if($zt['zt']<>0){
			$this->error('系统升级中,请稍后访问!','/Home/Login/index');die;
		}
		$usertitle='民生互助';
		$this->usertitle=$usertitle;
        $czmcsy = CONTROLLER_NAME . ACTION_NAME;
		$czmc = ACTION_NAME;
		if($czmcsy<>'Indexindex'){			
	        if (! isset ( $_SESSION ['uid'] )) {
	        	$this->redirect ( 'Login/index' );
	        }
	        $this->checkAdminSession();
		}
		$_SESSION['user_jb'] = 1;
		$userData = M ( 'user' )->where ( array ('UE_ID' => $_SESSION ['uid']) )->find ();
		$this->userData=$userData;
		$settings = include( __ROOT__ . 'User/Home/Conf/settings.php' );

        // 查询匹配完成后但没有打款的单子
        $ppddxx = M('ppdd')->where(array('p_user' => $_SESSION['uname'], 'zt' => 0))->select();
        //dump($ppddxx);die;
        foreach ($ppddxx as $v) {
            $ppdate = $v['date'];
            $ppdate = strtotime($ppdate);
            $time = time();
            $diff = ($time - $ppdate) / 3600;     
            if ($diff > floatval($settings['p_time'])) {     
                //如果在规定的24小时内不打款，封提供帮助账号     cold_type 为2 是24小时未打款
                M('user')->where(array('UE_account' => $v['p_user']))->save(array('cold' => 1, 'cold_type' => 2));
                //删除提供帮助排单记录
                M('tgbz')->where(array('id'=> $v['p_id']))->delete();
                //把接受帮助状态改为未匹配状态,
                M('jsbz')->where(array('id' => $v['g_id']))->save(array('zt' => 0));
                $rid = $v['id'];
                //把产生的静态收益删除
                M('user_jj')->where(array('r_id' => $rid))->delete();
                //删除匹配订单
                M('ppdd')->where(array('id' => $v['id']))->delete();      //在循环中删除没有打款的记录
                //根据匹配订单排单金额自动生成提供帮助
                $money=$v['jb'];
                // auto_tgbz($money);
                //自动匹配接单账号
                $tgbz_mod=M('tgbz');
                $p_user=$settings['auto_user'];
                $p_id=$tgbz_mod->where(array('user'=>$p_user,'jb'=>$money,'zt'=>0))->find()['id'];
                $g_id=$v['g_id'];
                ppdd_add($p_id,$g_id);      //传入提供帮助和接受帮助人的id 匹配成功

               $this->error("账户被冻结了！","/Home/Login/index");
            }

        }

        /**
         ** 超过24小时未确认收款，系统自动确认，账号冻结
         *  create by niekaimin
         *  2017/05/03
         **/
        $ppddxx = M('ppdd')->where(array('zt' => 1, 'ts_zt' =>0))->select();
        if($ppddxx){ 
            foreach ($ppddxx as $v) {
                $ppdate = $v['date_hk'];
                $ppdate = strtotime($ppdate);
                $time = time();
                $diff = ($time - $ppdate) / 3600;
                if ($diff > floatval($settings['s_time'])){
                    //封号   超过24小时未确认收款     'cold_type' =>3  
                    M('user')->where(array('UE_account' => $v['g_user']))->save(array('cold' => 1, 'cold_type' =>3));
                    //系统自动确认
                    M('ppdd')->where(array('id' => $v['id']))->save(array('zt' => 2));
                    M('tgbz')->where(array('id' => $v['p_id']))->save(array('qr_zt' => 1));
                    M('jsbz')->where(array('id' => $v['g_id']))->save(array('qr_zt' => 1));

                }

            } 
         }


        if($userData['cold']==1){

           $this->error("账户已被冻结！","/Home/Login/index"); 
        }
         if($userData['ue_check']==0){

           $this->error("您的账号尚未被审核，请联系后台管理员！","/Home/Login/index"); 
        }

        //收款24小时不排单，就冻结账号   (接受帮助的人)
        $ppddxx = M('ppdd')->where(array('g_user' => $_SESSION['uname'], 'zt' => 2))->order('date_hk1 desc')->group('    date_hk1')->limit(1)->find();
        if($ppddxx){
             if($ppddxx['cold_status'] == 0){
                $time=$ppddxx['date_hk1'];    //确认收款时间
                $time=strtotime($time);
                $time1=time();
                $diff=($time1-$time) /3600;
                if($diff > floatval($settings['bx_pd_time'])){
                    $data['cold']=1;
                    $data['cold_type']=1;
                    M ( 'user' )->where ( array ('UE_ID' => $_SESSION['uid']) )->save($data);
                    M('ppdd')->where(array('id' => $ppddxx['id']))->save(array('cold_status'=>1));
                    //动态奖金清零
                    M ( 'user' )->where ( array ('UE_ID' => $_SESSION['uid']) )->save(array('jl_he'=>0));
                    $this->error("账户已被冻结！","/Home/Login/index");
                }
            }      	
	    }
  }
	public function checkAdminSession() {
		//设置超时为10分
		$nowtime = time();
		$s_time = $_SESSION['logintime'];
		if (($nowtime - $s_time) > 3600000) {
		session_unset();
    	session_destroy();
			$this->error('当前用户登录超时，请重新登录', U('/Home/Login/index'));
		} else {
			$_SESSION['logintime'] = $nowtime;
		}
	}
	
	function check_verify($code) {
		$verify = new \Think\Verify ();
		return $verify->check ( $code );
	}
	
	
	public function getTreeBaseInfo($id) {
		if (! $id)
			return;
		$r = M ( "user" )->where ( array (
				'UE_account' => $id 
		) )->find ();
		$arr = 0;
		xiajirenshu($id,$arr);
		xiajiyeji($id,$yeji);
		$arr++;
		$yeji+= M('ppdd')->where(['p_user'=>$id,'zt'=>2])->sum('jb');
		if ($r)
			return array (
					"id" => $r ['ue_account'],
					"pId" => $r ['ue_accname'],
					"name" => $r ['ue_account'] . "[" .sfjhff($r['ue_status']).",". $r ['ue_truename'] . "," . $r ['ue_activetime'] . "] 团队人数：" .$arr."团队业绩".$yeji
			);
		return;
	}
	
	
	
	public function getTreeInfo($id) {
			
		static $trees = array ();
		$ids = self::get_childs ( $id );
		if (! $ids){
			return $trees;
		}

		$_SESSION['user_jb']++;
		//echo $_SESSION['user_jb'].'<br>';
		foreach ( $ids as $v ) {
			
			$trees [] = $this->getTreeBaseInfo ( $v );
			$this->getTreeInfo ( $v );
		
		}

		return $trees;
	}
	public static function get_childs($id) {

		if (! $id)
			return null;
		
		$childs_id = array ();
		$childs = M ( "user" )->field ( "UE_account" )->where ( array (
				'UE_accName' => $id 
		) )->select ();
		
		foreach ( $childs as $v ) {
			$childs_id [] = $v ['ue_account'];
		}
		
		if ($childs_id)
			return $childs_id;
		return 0;
	}
	public function getTree() {
		// if (!$this->uid) {
		// echo json_encode(array("status" => 1));
		// return ;
		// }
		$base = $this->getTreeBaseInfo ( $_SESSION ['uname'] );
		$znote = $this->getTreeInfo ( $_SESSION ['uname'] );
		$znote [] = $base;
		// dump($znote);die;
		/*
		 * $znote = array(array("id" => 1, "pId" => 0, "name"=>"1000001"), array("id" => 2, "pId" => 1, "name"=>"1000002"), array("id" => 3, "pId" => 2, "name"=>"1000003"), array("id" => 5, "pId" => 2, "name"=>"1000003"), array("id" => 4, "pId" => 1, "name"=>"1000004") );
		 */
		
		echo json_encode ( array ("status" => 0,"data" => $znote ) );
	}
	
    public function getTreeso() {	
		if(I('post.user')<>''){		
	        if(! preg_match ( '/^[a-zA-Z0-9@.]{1,120}$/', I('post.user') )){ 	
	        	echo json_encode ( array ("status" => 1,"data" => '用戶名格式不對!' ) );    	
	        }else{
	            if(!M('user')->where(array('UE_account'=>I('post.user')))->find()){
	            	echo json_encode ( array ("status" => 1,"data" => '用戶不存在!' ) );
	            }else{			
	            	$base = $this->getTreeBaseInfo ( I('post.user') );
	            	$znote = $this->getTreeInfo ( I('post.user') );
	            	$znote [] = $base;
	            	echo json_encode ( array ("status" => 0,"data" => $znote ) );				
	            
	            }
	        }
		}else{
			$base = $this->getTreeBaseInfo ('admin@qq.com');
			$znote = $this->getTreeInfo ('admin@qq.com');
			$znote [] = $base;			
			echo json_encode ( array ("status" => 0,"data" => $znote ) );		
		}
	}
	
	
	
	// public function uploadFace() {
	// 	$upload = $this->_upload('Pic');
	// 	$this->ajaxReturn($upload);
	// }
	
	
	
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
		}else{
			foreach($info as $file){
				$pic = $file['savepath'].$file['savename'];
			}
			return $pic;
		}
	}
	
	
	
}