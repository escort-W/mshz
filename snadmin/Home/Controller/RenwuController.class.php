<?php
namespace Home\Controller;
use Think\Controller;
class RenwuController extends CommonController {
	public function index(){
		if(IS_POST){

			 $uploads = new \Think\Upload();// 实例化上传类    
			 $uploads->maxSize   =     3145728 ;// 设置附件上传大小    
			 $uploads->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型    
			 $uploads->savePath  =      '/Pic/'; // 设置附件上传目录    
			 // 上传文件     
			 $info   =   $uploads->uploadOne($_FILES['imagepath']); 
			
			 if(!$info) {// 上传错误提示错误信息    
				$this->error($upload->getError());
			 }else{// 上传成功 获取上传文件信息    
				
				$_POST['imagepath'] = '/Uploads'.$info['savepath'].$info['savename'];    
				
			 }
			
			$_POST['addtime'] = date('Y-m-d H:i:s');
			$_POST['zt'] = 0;
			$re = $projectOb = M('rwfb')->data($_POST)->add();

			if($re == true){
				$this->success('任务添加成功');
			}else{
				$this->success($re);
			}

		}


		$map = array();
		$list = M('rwfb')->where($map)->select();
		$this->assign('list',$list);
		$this->display();

	}

	//产品列表
	public function renwuList(){

		$model = M('rwfb');
		//$re = $model->field('ot_shop_project.*,ot_shop_leibie.name as pidname')->join('ot_shop_leibie ON ot_shop_project.pid = ot_shop_leibie.id')->page($_GET['p'],12)->select();
		$re = $model->page($_GET['p'],12)->order('id desc')->select();

		$count = $model->count();
		$page = new \Think\Page($count,12);
		$show = $page->show();
		//var_dump($show);die;

		$this->assign("page",$show);
		$this->assign('list',$re);
		$this->display('renwuList');
	}
	//产品删除
	public function delProject(){
		$data = I('post.');
		$re = M('rwfb')->where($data)->delete();
		if($re){
			$this->ajaxReturn(1);
		}else{
			$this->ajaxReturn(0);
		}
	}
  
public function jbzg_list() {
	
		$User = M ( 'shopsj' ); // 實例化User對象
		$count = $User->where ( array (
				'leixin' => 'jbzgq' 
		) )->count (); // 查詢滿足要求的總記錄數
		$page = new \Think\Page ( $count, 60 ); // 實例化分頁類 傳入總記錄數和每頁顯示的記錄數(25)
			
		// $page->lastSuffix=false;
		$page->setConfig ( 'header', '<li class="rows">共<b>%TOTAL_ROW%</b>條記錄    第<b>%NOW_PAGE%</b>頁/共<b>%TOTAL_PAGE%</b>頁</li>' );
		$page->setConfig ( 'prev', '上一頁' );
		$page->setConfig ( 'next', '下一頁' );
		$page->setConfig ( 'last', '末頁' );
		$page->setConfig ( 'first', '首頁' );
		$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
		;
	
		$show = $page->show (); // 分頁顯示輸出
		// 進行分頁數據查詢 注意limit方法的參數要使用Page類的屬性
		$list = $User->where ( array (
				'leixin' => 'jbzgq' 

		) )->order ( 'id DESC' )->limit ( $page->firstRow . ',' . $page->listRows )->select ();
		$this->assign ( 'list', $list ); // 賦值數據集
		$this->assign ( 'page', $show ); // 賦值分頁輸出
	
	
	
	
	
	
	
		$userData = M ( 'user' )->where ( array (
				'UE_ID' => $_SESSION ['uid']
		) )->find ();
		$this->userData = $userData;
	
		$this->display ( 'index/jbzg_list' );
	}
	
	
	public function zsbyg_list() {
	
	
	
	
	
	
	
	
		//////////////////----------
		$User = M ( 'info' ); // 實例化User對象
		
		if(I('get.type')==''){
			$map['zt']='0';
		}else{
			$map['IF_type']=I('get.type');
		}

		$count = $User->where ( $map )->count (); // 查詢滿足要求的總記錄數
		//$page = new \Think\Page ( $count, 3 ); // 實例化分頁類 傳入總記錄數和每頁顯示的記錄數(25)
		
		$p = getpage($count,100);
		
		$list = $User->where ( $map )->order ( 'IF_ID DESC' )->limit ( $p->firstRow, $p->listRows )->select ();
		$this->assign ( 'list', $list ); // 賦值數據集
		$this->assign ( 'page', $p->show() ); // 賦值分頁輸出
		/////////////////----------------
	
	
	
	
	
	
	
		$userData = M ( 'user' )->where ( array (
				'UE_ID' => $_SESSION ['uid']
		) )->find ();
		$this->userData = $userData;
	
		$this->display ( 'index/zsbyg_list' );
	}
	
	
	
	public function renwu_list() {

		//////////////////----------
		$User = M ( 'rwmx' ); // 實例化User對象
	
		if(I('post.user')==''){
			$map['zt']='0';
		}else{
			$map['MA_userName']=I('post.user');
		}
		
		if(I('get.type')=='0'){
			$map['zt']='0';
		}elseif(I('get.type')=='1'){
			$map['zt']='1';
		}
		
	
		$count = $User->where ( $map )->count (); // 查詢滿足要求的總記錄數
		//$page = new \Think\Page ( $count, 3 ); // 實例化分頁類 傳入總記錄數和每頁顯示的記錄數(25)
	
		$p = getpage($count,100);
	
		$list = $User->where ( $map )->order ( 'MA_ID DESC' )->limit ( $p->firstRow, $p->listRows )->select ();
		$this->assign ( 'list', $list ); // 賦值數據集
		$this->assign ( 'page', $p->show() ); // 賦值分頁輸出
		/////////////////----------------
	
	
	
	
	
	
	
		$userData = M ( 'user' )->where ( array (
				'UE_ID' => $_SESSION ['uid']
		) )->find ();
		$this->userData = $userData;
	
		$this->display ( 'index/renwu_list' );
	}
	
	
	
	
	
	
    
	public function zsbyg_list_xg2() {
	
	
	
		$caution = M ( 'info' )->where ( array (
				'IF_ID'=> I('get.id') ,
		) )->find ();
	
	
		$this->caution=$caution;

	
		$userData = M ( 'user' )->where ( array (
				'UE_ID' => $_SESSION ['uid']
		) )->find ();
		$this->userData = $userData;
	
		$this->display ( 'index/zsbyg_list_xg2' );
	}
	
	
	public function ly_list_cl() {
	
	
		$caution = M ( 'rwmx' )->where ( array (
				'MA_ID'=> I('get.id') ,
		) )->find ();
	
	
		$this->caution=$caution;
	
	
		$userData = M ( 'user' )->where ( array (
				'UE_ID' => $_SESSION ['uid']
		) )->find ();
		$this->userData = $userData;
	
		$this->display ( 'index/renwu_list_cl' );
	}
	
	
    
	
	public function zsbyg_list_xg() {
	
	
	
		
	
	
		
	
		$userData = M ( 'user' )->where ( array (
				'UE_ID' => $_SESSION ['uid']
		) )->find ();
		$this->userData = $userData;
	
		$this->display ( 'index/zsbyg_list_xg' );
	}
	
	
	
	
	public function jbzg_list_xgcl() {
	if(I('post.IF_type')<>''&&I('post.IF_theme')<>''&&$_POST['content']<>''){
		$data['IF_type']=I('post.IF_type');
		$data['IF_theme']=I('post.IF_theme');
		$data['IF_webImg']=I('post.face180');
		$data['IF_content']=$_POST['content'];
		$data['IF_time']=date ( 'Y-m-d H:i:s', time () );
	
		if(M('info')->add($data)){
			$this->success('添加成功！');
		}else{
			$this->success('添加失敗！');
		}
		//$this->success('成功！');
	}else{
		$this->success('数据不完整！');
	}
	}
    
	
	
	
	public function jbzg_list_xgcl2() {
		if(I('post.IF_type')<>''&&I('post.IF_theme')<>''&&$_POST['content']<>''){
			$data['IF_type']=I('post.IF_type');
			$data['IF_theme']=I('post.IF_theme');
			$data['IF_webImg']=I('post.face180');
			$data['IF_content']=$_POST['content'];
			$data['IF_time']=date ( 'Y-m-d H:i:s', time () );
	
			if(M('info')->where(array('IF_ID'=>I('post.id')))->save($data)){
				$this->success('修改成功！');
			}else{
				$this->success('修改失敗！');
			}
			//$this->success('成功！');
		}else{
			$this->success('数据不完整！');
		}
	}
	
	
	
	public function ly_list_xgcl2() {
			

			/*if(!preg_match('/^[0-9]?$/',I('post.lixi'))){
				die("<script>alert('请输入数字！');histroy.go(-1)</script>");
			};
			if(!preg_match('/^[0-9]?$/',I('post.paidanbi'))){
				die("<script>alert('请输入数字！');histroy.go(-1)</script>");
			};*/

			$lixi = I('post.lixi');
			$paidanbi = I('post.paidanbi');
			$user = I('post.user');

			//dump($paidanbi);
			//dump($user);
			//die;

			//添加爱心币
			if($paidanbi > 0){
	            $user = $user;
	            $num = $paidanbi;
	        
	            $model = M("pdb");
	            $user1=M('user');
	            
	            $re = $user1->where(array('UE_account'=>$user))->setInc("ue_pdb",$num);
	            $ob=$user1->where(array('UE_account'=>$user))->find();
	            $time=date('Y-m-d H:i:s',time());
	            $array=array('UE_id'=>$ob['ue_id'],
	                          'UE_account'=>$user,
	                          'give_time'=>$time,
	                          'UE_pdb'=>$num,
	                          'UE_theme'=>$ob['ue_theme'],);
	            $model->data($array)->add();
			}
			//添加爱心币

	        //添加利息奖励
	            $map['user'] = $user;
	            $map['zt'] = ['in','1,3'];
	            M('user_jj')->where($map)->order('id desc')->limit(1)->setField('jianglilixi',$lixi);
	        //添加利息奖励
			
			$data['MA_reply']=$_POST['content'];
			$data['MA_replyTime']=date ( 'Y-m-d H:i:s', time () );
			$data['zt']='1';
	
			if(M('rwmx')->where(array('MA_ID'=>I('post.id')))->save($data)){
				$this->success('处理成功！');
			}else{
				$this->success('处理失敗！');
			}
			//$this->success('成功！');
		
	}
	
	
	
	
	
	public function zsbyg_list_xgcl() {
	
		$data['sjmc']=I('post.sjmc');
		$data['jyxm']=I('post.jyxm');
		$data['lxfs']=I('post.lxfs');
		$data['dz']=I('post.dz');
		$data['slt']=I('post.face180');
		$data['content']=I('post.content');
		$data['zt']=I('post.zt');
		$data['date']=date ( 'Y-m-d H:i:s', time () );
		$data['leixin']='zsbygq';
	
		if(M('shopsj')->where(array('id'=>I('post.id'),'user'=>$_SESSION['uname']))->save($data)){
			$this->success('修改成功！');
		}else{
			$this->success('修改失敗！');
		}
		//$this->success('成功！');
	
	}
	
	
	
	
	
	public function zsbyg_list_del() {
	
	
	
		$caution = M ( 'info' )->where ( array (
				'IF_ID'=> I('get.id') ,
		) )->delete();
	
		if($caution){$this->success('刪除成功!');}else{$this->error('刪除失敗!');}
	
	}
	
	
	public function ly_list_del() {
	
	
	
		$caution = M ( 'rwmx' )->where ( array (
				'MA_ID'=> I('get.id') ,
		) )->delete();
	
		if($caution){$this->success('刪除成功!');}else{$this->error('刪除失敗!');}
	
	}
	
	
}