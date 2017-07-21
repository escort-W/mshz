<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>UploadiFive Test</title>
<script src="./uploadfive/jquery.min.js" type="text/javascript"></script>
<script src="./uploadfive/jquery.uploadifive.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="./uploadfive/uploadifive.css">
<style type="text/css">
body {
	font: 13px Arial, Helvetica, Sans-serif;
}
.uploadifive-button {
	float: left;
	margin-right: 10px;
}
#queue {
	border: 1px solid #E5E5E5;
	height: 177px;
	overflow: auto;
	margin-bottom: 10px;
	padding: 0 3px 3px;
	width: 300px;
}
</style>
</head>

<body>
	<form>
		<div id="queue" style=''></div>
		<input id="file_upload" name="file_upload" type="file" multiple="true">
		
	</form>

	<script type="text/javascript">
		<?php $timestamp = time();?>
		$(function() {
			$('#file_upload').uploadifive({
				'auto'             : true,
				'multi'             : false,
				'buttontext'       : "选择文件",
				'removeCompleted'  : true, 
				'checkScript'      : '',
				'formData'         : {
									   'timestamp' : '<?php echo $timestamp;?>',
									   'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
				                     },
				'queueID'          : 'queue',
				'uploadScript'     : 'uploadifive.php',
				'onUploadComplete' : function(file, data) { $($("form")).append("<img src='"+data+"' />") }
			});
		});
	</script>
</body>
</html>