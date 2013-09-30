<?php
/////////////////////////////////////////////////////
/// 函数名称：gen 
/// 函数作用：产生静态的页面的html页面
/// 函数作者: DH
/// 作者地址: http://dhblog.org
/////////////////////////////////////////////////////

header('Content-Type:text/html;charset= UTF-8'); 
require("config.php");
#需要使用的基础函数
require("compressJS.class.php");
set_time_limit(600); 
dh_gen_page();


function dh_gen_page()
{
	global $DH_home_url,$DH_html_path,$DH_output_path,$DH_page_store_deep,$DH_output_html_path;
	if (!file_exists($DH_output_html_path))  
		mkdir($DH_output_html_path,0777);
	
	$DH_input_html  = $DH_html_path . 'page.html';
	$DH_output_content = dh_file_get_contents("$DH_input_html");
	//echo $DH_output_content;
	$DH_output_content = setshare($DH_output_content,'page.js');
	$deep = '';
	for($i= 0;$i<$DH_page_store_deep;$i++)
	{
		$deep .= '../';
	}
	$DH_output_content = str_replace("%deep%",$deep,$DH_output_content);	
	$DH_output_content = str_replace("%home%",$DH_home_url,$DH_output_content);		
	dh_gen_each_page_file($DH_output_html_path,$DH_output_content);
}

function dh_gen_each_page_file($path,$DH_output_content)
{
	global $DH_index_url,$DH_home_url;
	
	$DH_output_file = output_page_path($path,2);
	echo $DH_output_file;
	dh_file_put_contents($DH_output_file,$DH_output_content);
}
?>