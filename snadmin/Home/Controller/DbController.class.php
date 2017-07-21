<?php
namespace Home\Controller;
use Think\Controller;
class DbController extends CommonController {
  
    public function index(){
		$dir = $_SERVER["DOCUMENT_ROOT"]."/db_backup/";
        if(IS_POST){
            
			if(!is_dir($dir)){
				mkdir($dir);
			}
			$filename = date("YmdHis",time());
			//$cmd = '"C:\Program Files\phpStudy\MySQL\bin\mysqldump" -u'.C("DB_USER").' -p'.C("DB_PWD").' '.C("DB_NAME").' > '.$dir.$filename.'.sql';
			$cmd = '"D:\phpStudy\MySQL\bin\mysqldump" -u'.C("DB_USER").' -p'.C("DB_PWD").' '.C("DB_NAME").' > '.$dir.$filename.'.sql';
				
			$res = exec($cmd,$output,$status);
			//$res = passthru($cmd,$status);
			//$res = system($cmd,$output);
			//dump($res);die;
			if($status){
				$this->error("备份失败");
			}else{
				$this->success("备份成功");
			}
        }
		$list = glob($dir."*.sql");
		foreach($list as $k=>$v){
			$v1 = explode(".",$v);
			$time = strtotime(str_replace($dir,'',$v1[0]));
			if(strlen($time) != 10){
				continue;
			}
			$list[$k] = date("Y-m-d H:i:s",$time);
		}
		$this->assign("list",$list);
		$this->display("index/data");
    }
	
	function del(){
		$data = I("item");
		if(!empty($data)){
			$dir = $_SERVER["DOCUMENT_ROOT"]."/db_backup/";
			$file = $dir.date("YmdHis",strtotime($data)).".sql";
			
			if(file_exists($file)){
				if(unlink($file)){
					$this->success("删除成功");
				}else{
					$this->success("删除失败");
				}
			}
			
		}
	}
	
	function recovery(){
		$dir = "db_backup/";
		$data = I("item");
		if(!empty($data)){
			$file = $dir.date("YmdHis",strtotime($data)).".sql";
			$cmd = 'mysql.exe -u'.C("DB_USER").' -p'.C("DB_PWD").' '.C("DB_NAME").' < '.$file;
			
			//$res = exec($cmd,$output,$status);
			$res = system($cmd,$output);
			
			//$res = passthru($cmd,$output);
			
			if($output){
				
				$this->error("恢复失败");
			}else{
				
				$this->success("恢复成功");
			}
		}
	}
	
}