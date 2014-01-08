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
$lists=array();
scan_dir($DH_src_path.'pages');
ksort($lists);
print_r($lists);
output_lists($lists);


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
	//echo $dir.":\n";		
	$files = scandir($dir,1);
	//print_r($files);
	foreach($files as $key=>$file)
	{
		//echo "search ".$file."\n";
		if(strcmp($file,'.')===0)
			continue;
		if(strcmp($file,'..')===0)
			continue;
		$down = $dir.'/'.$file;
		if(is_dir($down))
		{
			scan_dir($down);
		}
		else
		{
			echo $down."\n";
			get_entry($down);
		}
	}
	return;
}

function get_entry($filename)
{
	$content = dh_file_get_contents("$filename");
	//echo $content;
	preg_match_all('/<a>(.*?)<\/a>/s',$content,$entrys);
	//print_r($entrys);
	foreach($entrys[1] as $key=>$entry)
	{
		//取得summary
		//preg_match('/<body>(.*?)<\/body>/s',$entry,$match);
		////print_r($match);
		//if(!empty($match[1])&&mb_strlen($match[1],'UTF-8')>64)
		//{
		//	$x = mb_substr($match[1],0,64,'UTF-8');
		//	$entry = preg_replace( '/<body>(.*?)<\/body>/s',"<body>$x\n</body>",$entry);
		//}
		insert_lists($entry);		
	}
}

function insert_lists($entry)
{
	global $lists;
	preg_match('/<date>(.*?)<\/date>/s',$entry,$match);
	//print_r($match);
	$insert_entry=array();
	//array_push($insert_entry,$match[1],$entry);
	//print_r($insert_entry);
	//array_push($lists,$insert_entry);
	//array_push($lists,$match[1]=>$entry);
	$lists[$match[1]]=$entry;
}

function output_lists($lists)
{
	global $DH_src_path;
	$lists_all='';
	$i=1;
	foreach($lists as $key=>$list)
	{
		$lists_all.="\n<a>\n<id>".$i.'</id>'.$list."\n</a>";
		$i++;
	}
	dh_file_put_contents($DH_src_path.'list.xml',$lists_all);	
}

function dh_gen_each_page_file($path,$DH_output_content)
{
	global $DH_index_url,$DH_home_url;
	
	$DH_output_file = output_page_path($path,2);
	echo $DH_output_file;
	dh_file_put_contents($DH_output_file,$DH_output_content);
}
?>