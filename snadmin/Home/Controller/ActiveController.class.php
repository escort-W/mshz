<?php
namespace Home\Controller;
use Think\Controller;
class ActiveController extends CommonController {
  
    public function turn(){
		$model = M('turn');
		$res = $model->find();
		if($res){
			$res['turn_num'] = json_decode($res['turn_num']);
			$res['turn_v'] = json_decode($res['turn_v']);
		}
		
		if(IS_POST){
			
			$data = I("post.");
			
			$data['turn_num'] = json_encode($data['turn_num']);
			$data['turn_v'] = json_encode($data['turn_v']);
			if($res){
				$model->where(array('id'=>$res['id']))->save($data);
				$res = $model->find();
				if($res){
					$res['turn_num'] = json_decode($res['turn_num']);
					$res['turn_v'] = json_decode($res['turn_v']);
				}
			}else{
				if(!empty($data)){
					var_dump($data);
					$model->add($data);
				}
			}
		}
		$this->assign('result',$res);
    	$this->display('index/turn');
    }
    
    

}