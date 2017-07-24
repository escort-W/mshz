<?php    
    function prcode_create(){
		//set it to writable location, a place for temp generated PNG files
		$PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;

		//html PNG location prefix
		$PNG_WEB_DIR = 'temp/';

		include "qrcode/qrlib.php";    
		
		//ofcourse we need rights to create temp dir
		if (!file_exists($PNG_TEMP_DIR))
			mkdir($PNG_TEMP_DIR);
		
		
		$filename = $PNG_TEMP_DIR.'test.png';
		
		//processing form input
		//remember to sanitize user input in real-life solution !!!
		$errorCorrectionLevel = 'H'; 

		$matrixPointSize = 10;
		if (isset($_REQUEST['size']))
			$matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);

		$_REQUEST['data'] = 'www.baidu.com';
		if (isset($_REQUEST['data'])) { 
		
			//it's very important!
			if (trim($_REQUEST['data']) == '')
				die('data cannot be empty! <a href="?">back</a>');
				
			// user data
			$filename = $PNG_TEMP_DIR.'test'.md5($_REQUEST['data'].'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
			QRcode::png($_REQUEST['data'], $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
			
		} else {    
		
			//default data
			echo 'You can provide data in GET parameter: <a href="?data=like_that">like that</a><hr/>';    
			QRcode::png('PHP QR Code :)', $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
			
		}    
		$logo = $PNG_TEMP_DIR.'login.png';//准备好的logo图片   
		$QR = $filename;//已经生成的原始二维码图
		if ($logo !== FALSE) {   
			$QR = imagecreatefromstring(file_get_contents($QR));   
			$logo = imagecreatefromstring(file_get_contents($logo));   
			$QR_width = imagesx($QR);//二维码图片宽度   
			$QR_height = imagesy($QR);//二维码图片高度   
			$logo_width = imagesx($logo);//logo图片宽度   
			$logo_height = imagesy($logo);//logo图片高度   
			$logo_qr_width = $QR_width / 3;   
			$scale = $logo_width/$logo_qr_width;   
			$logo_qr_height = $logo_height/$scale;   
			$from_width = ($QR_width - $logo_qr_width) / 2;   
			//重新组合图片并调整大小   
			imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,   
			$logo_qr_height, $logo_width, $logo_height);   
		}   
		//输出图片   
		imagepng($QR, $filename);       
		//display generated file
		//echo '<img src="'.$PNG_WEB_DIR.basename($filename).'" /><hr/>';
		return $filename;
	}
    