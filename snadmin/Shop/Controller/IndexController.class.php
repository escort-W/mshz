<?php
namespace Shop\Controller;
use Think\Controller;
class IndexController extends CommonController {
  
    public function index(){
		$model = M('shop_leibie');
		if(IS_POST){
			$data = I();
			$data['addtime'] = time();
			if($model->where(array("name"=>$data['name']))->find()){
				$this->error("类别已存在");
			}
			$re = $model->add($data);
			$this->show_mes($re);
		}
		$list = $model->page($_GET['p'],12)->select();
		$this->assign("list",$list);
		$count = $model->count();
		$page = new \Think\Page($count,12);
		$show = $page->show();
		$this->assign("page",$show);
    	$this->display();
    }
    function toggleShop(){
    	if(IS_POST){
    		$order = I('post.toggle');
    		if($order == 1){
    			$re = M('system')->where(['SYS_ID'=>'1'])->setField('toggleshop',1);
    		}elseif($order == 0){
    			$re = M('system')->where(['SYS_ID'=>'1'])->setField('toggleshop',0);
    		}else{
    			die('非法操作！');
    		}
    		if($re){
    			$this->success('设置成功！');
    		}else{
    			$this->error('设置失败！');
    		}
    	}
    }

	function show_mes($data){
		if($data){
				$this->success("操作成功！");
			}else{
				$this->error("操作失败！");
			}
	}
	
	function ajax_mes($data){
		if($data){
				$this->ajaxReturn("1");
			}else{
				$this->ajaxReturn("0");
			}
	}
	
	function edit(){
		if(IS_POST){
			$model = M("shop_leibie");
			$data['name'] = I("post.name");
			$id = I("post.id");
			$re = $model->where(array("id"=>$id))->save($data);
			$this->ajax_mes($re);
		}
	}
	
	function del(){
		if(IS_POST){
			$id = I("id");
			if(!empty($id)){
				$model = M("shop_leibie");
				$re = $model->where(array("id"=>$id))->delete();
				$this->ajax_mes($data);
			}
		}
	}
	public function listOrderform(){
		$model = M('shop_orderform');
		$re = $model->page($_GET['p'],12)->select();

		$count = $model->count();
		$page = new \Think\Page($count,12);
		$show = $page->show();
		//var_dump($show);die;
		// $mo = $model->select();
		foreach($re as $k=>$v){
			$user = M("user")->where(array('UE_account'=>$re[$k]['user']))->find();
			// dump($user);
			$re[$k]['lx_weixin'] = $user['lx_weixin'];
		}
		// dump($re);
		// $list1 = $user['lx_weixin'];
		$this->assign("page",$show);
		// $this->assign('list1',$list1);
		$this->assign('list',$re);
		$this->display();
	}
	//产品订单
	public function delOrderform(){
		$data = I('post.');
		$re = M('shop_orderform')->where($data)->delete();
		if($re){
			$this->ajaxReturn(1);
		}else{
			$this->ajaxReturn(0);
		}
	}
	//确认发货
	public function delivery(){
		$id = I('post.id');
		$result = M('shop_orderform')->where(array('id'=>$id))->getField('zt');
		
		if($result == '0'){
			$re = M('shop_orderform')->where(array('id'=>$id))->save(array('zt'=>'1'));
		}elseif($result == '1'){
			$re = M('shop_orderform')->where(array('id'=>$id))->save(array('zt'=>'0'));
		}
		if($re){
			$this->ajaxReturn(1);
		}else{
			$this->ajaxReturn(0);
		}
	}



}