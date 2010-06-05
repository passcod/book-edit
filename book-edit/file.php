<?php

function file_array($path, $regex = "/^[^\.].+/i") {
	$path = rtrim($path, "/") . "/";
	$folder_handle = opendir($path);
	$result = array();
	
	while( false !== ($filename = readdir($folder_handle)) ) {
		if( preg_match($regex, $filename) ) {
			$result[] = $filename;
		}
	}
	return $result;
}

if ( $_GET['g'] == 'list' ) {
	$files = file_array('/home/felix/Documents/thebook/', "/\.md$/");
	
	foreach( $files as $f ) {
		echo $f;
		echo $f == $files[count($files)-1] ? '' : ';';
	}
}
elseif ( $_GET['g'] == 'raw' ) {
	$file = $_GET['f'];
	
	$file = file_get_contents('/home/felix/Documents/thebook/' . $file);
	echo $file;
}
elseif ( $_GET['g'] == 'htm' ) {
	$file = $_GET['f'];
	$file = file_get_contents('/home/felix/Documents/thebook/' . $file);
	
	include('thebook/markdown.php');
	echo Markdown($file);
}
elseif ( $_GET['g'] == 'save' ) {
	$file = $_GET['f'];
	$data = $_POST['data'];
	
	echo file_put_contents('/home/felix/Documents/thebook/' . $file, $data);
}

?>
