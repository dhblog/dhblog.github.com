<?php
/////////////////////////////////////////////////////
/// 函数名称：gen
/// 函数作用：产生静态的index.html页面
/// 函数作者: DH
/// 作者地址: http://dhblog.org
/////////////////////////////////////////////////////

header('Content-Type:text/html;charset= UTF-8'); 
require("config.php");
#需要使用的基础函数
require("compressJS.class.php");
set_time_limit(600); 
dh_gen_index();

function dh_gen_index()
{
	global $DH_home_url,$DH_html_path,$DH_output_path,$DH_page_store_deep,$DH_output_html_path;
	$DH_input_html  = $DH_html_path . 'index.html';
	$DH_output_content = dh_file_get_contents("$DH_input_html");

	$DH_output_content = setshare($DH_output_content,'index.js');
	$DH_output_content = str_replace("%home%",$DH_home_url,$DH_output_content);
	
	$DH_output_file = $DH_output_path.'index.html';
	echo $DH_output_file;
	dh_file_put_contents($DH_output_file,$DH_output_content);
}	
?>