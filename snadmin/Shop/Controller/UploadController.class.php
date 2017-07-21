<?php 
	namespace Shop\Controller;
	
	class UploadController extends CommonController{
		
		public function uploadFace() {
		
			$upload = $this->_upload('Pic');
			$this->ajaxReturn($upload);
		}
		
		Private function _upload ($path) {
			
			import('ORG.Net.UploadFile');	//引入文件
			$obj = new \Think\Upload();	//实例化上传类
			$obj->maxSize = 20000000;	//文件上传的最大文件大小
			$obj->savePath =  $path . '/';	//文件保存路径，如果留空会取UPLOAD_PATH常量定义的路径
			$obj->saveRule = 'uniqid';	//上传文件的保存规则，必须是一个无需任何参数的函数名
			$obj->uploadReplace = true;	//����ͬ���ļ�
			$obj->exts = array('jpg','jpeg','png','gif');	//允许上传的文件后缀（留空为不限制），使用数组设置，默认为空数组
		
			$obj->autoSub = true;	//ʹ是否使用子目录保存上传文件
			$obj->subType = 'date';	//子目录创建方式，默认为hash，可以设置为hash或者date
			$obj->dateFormat = 'Y_m';	//子目录方式为date的时候指定日期格式
			//$obj->upload();die;
			$info   =   $obj->upload();
			if (!$info) { //上传错误提示错误信息
				return array('status' => 0, 'msg' => $obj->getError());
			} else {
				foreach($info as $file){
					$pic = $file['savepath'].$file['savename'];
				}
				//$pic =  $info[0][savename];
				//echo $pic;die;
				return array(
						'status' => 1,
						'path' => $pic
				);
			}
		}
	}
	