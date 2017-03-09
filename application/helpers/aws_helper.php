<?php

if(!function_exists('upload_exercise_to_bucket_server')){
	function upload_exercise_to_bucket_server($file='userfile', $temp = '' , $prepend = '' , $allowed =  array('gif','png','jpg','jpeg')){
		require_once APPPATH.'libraries/s3/S3.php'; 
		$awsAccessKey = 'AKIAIZRC56OYZF4A2YSA';
		$awsSecretKey = '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';
		$path = '../tmp/';
		$bucketName = strtolower(BUCKET);
		$filename2 = $file;
		$ext = pathinfo($filename2, PATHINFO_EXTENSION);
		$filename = $prepend.md5(uniqid(rand(), true)).'.'.$ext;
		if(!in_array($ext,$allowed) ){
			return array(
				'status'  => FALSE,
				'error'   => 'File type is not allowed'
			);
		}else{
			if(move_uploaded_file($temp, $path.$filename)){
				// NOTHING
			}
			else{
				return array(
					'status'  => FALSE,
					'error'   => 'File not moved'
				);
			}
		}
		$uploadFile = $path.$filename;
		if (!file_exists($uploadFile) || !is_file($uploadFile)){
			return array(
				'status'  => FALSE,
				'error'   => 'File not present'
			);
		}
		$s3 = new S3($awsAccessKey, $awsSecretKey);
		if ($s3->putBucket($bucketName, S3::ACL_PUBLIC_READ)) {
			if ($s3->putObjectFile($uploadFile, $bucketName, baseName($uploadFile), S3::ACL_PUBLIC_READ)) {
				return array(
					'status'   => TRUE,
					'filename' => $filename
				);
			}
			else{
				return array(
					'status'  => FALSE,
					'error'   => 'File is not uploaded to server'
				);
			}
		}else{
			return array(
				'status'  => FALSE,
				'error'   => 'File is not uploaded to server'
			);
		}
	} 
}

if(!function_exists('upload_to_bucket_server')){
	function upload_to_bucket_server($file='userfile', $prepend = '' , $allowed =  array('gif','png','jpg','jpeg')){
		require_once APPPATH.'libraries/s3/S3.php'; 
		$awsAccessKey = 'AKIAIZRC56OYZF4A2YSA';
		$awsSecretKey = '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';
		$path = '../tmp/';
		$bucketName = strtolower(BUCKET);
		$filename2 = $_FILES[$file]['name'];
		$ext = pathinfo($filename2, PATHINFO_EXTENSION);
		$filename = $prepend.md5(uniqid(rand(), true)).'.'.$ext;
		if(!in_array($ext,$allowed) ){
			return array(
				'status'  => FALSE,
				'error'   => 'File type is not allowed'
			);
		}else{
			if ($_FILES[$file]["error"] > 0){
				return array(
					'status'  => FALSE,
					'error'   => 'File has errors'
				); 
			}
			else{
				$name = uniqid().time();
				if(move_uploaded_file($_FILES[$file]['tmp_name'], $path.$filename)){
					// NOTHING
				}
				else{
					return array(
						'status'  => FALSE,
						'error'   => 'File not moved'
					);
				}
			}
		}
		$uploadFile = $path.$filename;
		if (!file_exists($uploadFile) || !is_file($uploadFile)){
			return array(
				'status'  => FALSE,
				'error'   => 'File not present'
			);
		}
		$s3 = new S3($awsAccessKey, $awsSecretKey);
		if ($s3->putBucket($bucketName, S3::ACL_PUBLIC_READ)) {
			if ($s3->putObjectFile($uploadFile, $bucketName, baseName($uploadFile), S3::ACL_PUBLIC_READ)) {
				return array(
					'status'   => TRUE,
					'filename' => $filename
				);
			}
			else{
				return array(
					'status'  => FALSE,
					'error'   => 'File is not uploaded to server'
				);
			}
		}else{
			return array(
				'status'  => FALSE,
				'error'   => 'File is not uploaded to server'
			);
		}
	} 
}

if(!function_exists('upload_to_bucket_server_from_app')){
	function upload_to_bucket_server_from_app($filename=''){
		require_once APPPATH.'libraries/s3/S3.php';
		$awsAccessKey = 'AKIAIZRC56OYZF4A2YSA';
		$awsSecretKey = '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';
		$path = '../tmp/';
		$bucketName = strtolower(BUCKET);
		$uploadFile = $path.$filename;
		if (!file_exists($uploadFile) || !is_file($uploadFile)){
			return FALSE;
		}
		$s3 = new S3($awsAccessKey, $awsSecretKey);
		if ($s3->putBucket($bucketName, S3::ACL_PUBLIC_READ)) {
			if ($s3->putObjectFile($uploadFile, $bucketName, baseName($uploadFile), S3::ACL_PUBLIC_READ)) {
				return TRUE;
			}
			else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
	} 
}

if(!function_exists('delete_from_bucket_server')){
	function delete_from_bucket_server($filename='userfile.php'){
		return TRUE;
		if($filename == '' || $filename == '0' || $filename == null){
			return FALSE;
		}
		require_once APPPATH.'libraries/s3/S3.php'; 
		$awsAccessKey = 'AKIAIZRC56OYZF4A2YSA';
		$awsSecretKey = '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';
		$bucketName = strtolower(BUCKET);
		
		$s3 = new S3($awsAccessKey, $awsSecretKey);
		if ($s3->putBucket($bucketName, S3::ACL_PUBLIC_READ)) {
			if ($s3->deleteObject($bucketName, baseName($filename))) {
				return TRUE;
			} else {
				return FALSE;
			}
		}else{
			return FALSE;
		}
	} 
}