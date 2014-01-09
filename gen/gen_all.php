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

//预定义
global $DH_src_path;
$cats=array('美丽'=>array('xx'),'yy','zz','dd','bb','aa','cc');
$tags=array();

//取出已经搞定的日期和数目
$countpath=$DH_src_path.'tmp/count';
$content = dh_file_get_contents("$countpath");
preg_match('/<date>(.*?)<\/date><count>(.*?)<\/count>/s',$content,$match);
print_r($match);
$begindate=$match[1];
$begincount=$match[2];

//扫描所有的文件
$lists=array();
scan_dir($DH_src_path.'pages');
ksort($lists);
print_r($lists);
output_lists();

//输出到pages
dh_gen_page();
//输出到各个lists

//将搞定的date和count写入文件保存
$endcount=end($lists);
$maxdate=key($lists);
$maxcount=$begincount+count($lists);
echo $maxdate.":".$maxcount;





function dh_gen_page()
{
	global $lists,$DH_home_url,$DH_html_path,$DH_output_path,$DH_output_html_path,$DH_src_path;
	if (!file_exists($DH_output_html_path))  
		mkdir($DH_output_html_path,0777);
	
	$DH_input_html  = $DH_html_path . 'page.html';
	$DH_output_content = dh_file_get_contents("$DH_input_html");
	$DH_output_content = setshare($DH_output_content,'page.js');
	$DH_output_content = str_replace("%home%",$DH_home_url,$DH_output_content);
	//echo $DH_output_content;
	$i=0;
	foreach($lists as $key=>$list)
	{
		$i++;
		if($i>4)
			break;
		print_r($list);
		preg_match('/<id>(.*?)<\/id>/s',$list,$match);
		if(empty($match))
			continue;
		$DH_output_file = output_page_path($DH_output_html_path,$match[1]);
		preg_match('/<body>(.*?)<\/body>/s',$list,$match);
		print_r($match);
		if(empty($match))
			continue;
		$DH_output_content_each =  str_replace("%entry%",$match[1],$DH_output_content);
		dh_file_put_contents($DH_output_file,$DH_output_content_each);		
	}
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
	global $begindate,$lists;
	$content = dh_file_get_contents("$filename");
	//echo $content;
	preg_match_all('/<a>(.*?)<\/a>/s',$content,$entrys);
	//print_r($entrys);
	foreach($entrys[1] as $key=>$entry)
	{
		//取得date
		preg_match('/<date>(.*?)<\/date>/s',$entry,$match);
		//print_r($match);
		if(!empty($match[1])&&($match[1]>$begindate))
		{
			$lists[$match[1]]=$entry;
		}		
	}
}

function output_lists()
{
	global $lists,$DH_src_path,$begincount;
	$lists_all='';
	$i=1;
	foreach($lists as $key=>$list)
	{
		$lists_add = "\n<a>\n<id>".($i+$begincount).'</id>'.$list."\n</a>";
		$lists_all .= $lists_add;
		$lists[$key]= $lists_add;
		$i++;
	}
	dh_file_put_contents($DH_src_path.'tmp/list.xml',$lists_all);
}
?>