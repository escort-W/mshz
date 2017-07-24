<?php

namespace Home\Controller;

use Think\Controller;

class TurnController extends CommonController {
	// 首页
	public function index() {
		$config = M('turn')->find();

        $money=M('user')->where(array('UE_account'=>$_SESSION['uname']))->getField('ue_money');
        //dump($money);
        if($money<$config['consume']){
        	die("<script>alert('您的余额不足，请及时充值！');history.back(-1);</script>");
        }

		$config['turn_v'] = json_decode($config['turn_v']);
		$config['turn_num'] = json_decode($config['turn_num']);
		//var_dump($config);
		$list = M('turn_log')->where(array('uid'=>$_SESSION['uid']))->select();
		
		$this->assign('config',$config);
		$this->assign('list',$list);
		$this->display('turn');
	}
	
	function get_v(){
		$config = M('turn')->find();
		$v = json_decode($config['turn_v']);
		$num = json_decode($config['turn_num']);
		$result['switch'] = $config['switch'];
		
		if($config['switch']){
			$prize_arr = array( 
				'0' => array('id'=>1,'min'=>1,'max'=>29,'prize'=>'一等奖','v'=>$v[0]), 
				'1' => array('id'=>2,'min'=>302,'max'=>328,'prize'=>'二等奖','v'=>$v[1]), 
				'2' => array('id'=>3,'min'=>242,'max'=>268,'prize'=>'三等奖','v'=>$v[2]), 
				'3' => array('id'=>4,'min'=>182,'max'=>208,'prize'=>'四等奖','v'=>$v[3]), 
				'4' => array('id'=>5,'min'=>122,'max'=>148,'prize'=>'五等奖','v'=>$v[4]), 
				'5' => array('id'=>6,'min'=>62,'max'=>88,'prize'=>'六等奖','v'=>$v[5]), 
				'6' => array('id'=>7,'min'=>array(32,92,152,212,272,332), 
				'max'=>array(58,118,178,238,298,358),'prize'=>'七等奖','v'=>$v[6]) 
			); 
			
			foreach ($prize_arr as $key => $val) { 
				$arr[$val['id']] = $val['v']; 
			} 
			
			$rid = getTurnRand($arr); //根据概率获取奖项id 
			 
			$res = $prize_arr[$rid-1]; //中奖项 
			$min = $res['min']; 
			$max = $res['max']; 
			if($res['id']==7){ //七等奖 
				$i = mt_rand(0,5); 
				$result['angle'] = mt_rand($min[$i],$max[$i]); 
			}else{ 
				$result['angle'] = mt_rand($min,$max); //随机生成一个角度 
			} 
			$result['prize'] = $res['prize'];
			
			$data['uid'] = $_SESSION['uid'];
			$data['consume'] = $config['consume'];
			$data['reward_id'] = $rid;
			$data['reward_num'] = $num[($rid-1)];
			$data['addtime'] = time();
			
			M('user')->where(array('uid'=>$_SESSION['uid']))->setInc('UE_money',$num[($rid-1)]);
			M('user')->where(array('uid'=>$_SESSION['uid']))->setDec('UE_money',$config['consume']);
			$turn_res = M('turn_log')->add($data);
		}

		echo json_encode($result); 
	}
	
	
}