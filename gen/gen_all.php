<?php
/////////////////////////////////////////////////////
/// 函数名称：gen_all
/// 函数作用：产生静态的页面的html页面
/// 函数作者: DH
/// 作者地址: http://dhblog.org
/////////////////////////////////////////////////////

header('Content-Type:text/html;charset= UTF-8'); 
require("config.php");
#需要使用的基础函数
require("compressJS.class.php");
set_time_limit(600); 
//dh_gen_page();

global $DH_src_path;
scan_dir($DH_src_path.'pages');

$cats=array('xx','yy','zz','dd','bb','aa','cc');
$tags=array('tag1','tag2','tag3','tag4','tag5','tag6','tag7');

function dh_gen_page()
{
	global $DH_home_url,$DH_html_path,$DH_output_path,$DH_page_store_deep,$DH_output_html_path,$DH_src_path;
	if (!file_exists($DH_output_html_path))  
		mkdir($DH_output_html_path,0777);
	
	$DH_input_html  = $DH_html_path . 'page.html';
	$DH_output_content = dh_file_get_contents("$DH_input_html");
	//echo $DH_output_content;

	scan_dir($DH_src_path.'pages');
	return;
	
	$files = scandir($DH_src_path.'pages',1);
	if($files==false)
		return;
	echo "<b>Files in " . $DH_src_path . ":</b><br/>\n";
	print_r($files);
 
	foreach($files as $key=>$file)
	{
		scandir($file,1);
		$ext=strrchr($file,'.');
		if($ext!='.html')
			continue;
		echo $ext."\n";
		continue;
		// 选出是哪个类型的sitemap
		if(strstr($file,'sitemap_'))
		{
			echo "sitemap mouth :$file<br/>";
			$siteindex2 .='<li><a href="'.$DH_home_url.'sitemaphtml/'.$file.'">'.$file.'</a></li>';
		}
		else
		{
			echo "sitemap 10000 :$file<br/>";
			$siteindex1 .='<li><a href="'.$DH_home_url.'sitemaphtml/'.$file.'">'.$file.'</a></li>';		
		}
	}

//	$files = scandir($DH_output_path.'sitemapxml',1);
//	echo "<b>Files in " . $DH_output_path . ":</b><br/>\n";
//	foreach($files as $key=>$file)
//	{
//		$ext=strrchr($file,'.');
//		if($ext!='.xml')
//			continue;
//			
//		// 选出是哪个类型的sitemap
//		if(strstr($file,'sitemap_baidu'))
//		{
//			echo "sitemap baidu xml :$file<br/>";
//			$siteindex4 .='<li><a href="'.$DH_home_url.'sitemapxml/'.$file.'">'.$file.'</a></li>';
//		}
//		else
//		{
//			echo "sitemap google xml :$file<br/>";
//			$siteindex3 .='<li><a href="'.$DH_home_url.'sitemapxml/'.$file.'">'.$file.'</a></li>';	
//		}
//	}
	
	return;
	
	$DH_output_content = setshare($DH_output_content,'page.js');
	$DH_output_content = str_replace("%home%",$DH_home_url,$DH_output_content);		
	dh_gen_each_page_file($DH_output_html_path,$DH_output_content);
}


function scan_dir($dir)
{
	echo $dir.":\n";		

	$files = scandir($dir,1);
	print_r($files);
	foreach($files as $key=>$file)
	{
		//echo $file."\n";
		if(strstr($file,'.'))
			continue;
		if(strstr($file,'..'))
			continue;
		$down = $dir.'/'.$file;
		echo $down."\n";
		if(is_dir($down))
		{
			scan_dir($down);
		}
		else
			echo 'dd'."\n";
			//echo $down."\n";
	}
	return;
}

function dh_gen_each_page_file($path,$DH_output_content)
{
	global $DH_index_url,$DH_home_url;
	
	$DH_output_file = output_page_path($path,2);
	echo $DH_output_file;
	dh_file_put_contents($DH_output_file,$DH_output_content);
}
?>