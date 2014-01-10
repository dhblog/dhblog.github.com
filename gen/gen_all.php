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
$pages=array();
scan_dir($DH_src_path.'pages');
ksort($lists);
ksort($pages);
//print_r($lists);
//print_r($pages);
//output_all();

//输出到pages
dh_gen_page();
//dh_gen_list();
//输出到各个lists

//将搞定的date和count写入文件保存
$endcount=end($lists);
$maxdate=key($lists);
$maxcount=$begincount+count($lists);
echo $maxdate.":".$maxcount;


function dh_gen_page()
{
	global $pages,$DH_home_url,$DH_html_path,$DH_output_path,$DH_output_html_path,$DH_src_path,$begincount;
	if (!file_exists($DH_output_html_path))  
		mkdir($DH_output_html_path,0777);
	
	$DH_input_html  = $DH_html_path . 'page.html';
	$DH_output_content = dh_file_get_contents("$DH_input_html");
	$DH_output_content = setshare($DH_output_content,'page.js');
	$DH_output_content = str_replace("%home%",$DH_home_url,$DH_output_content);
	//echo $DH_output_content;
	$i=1;
	foreach($pages as $key=>$page)
	{
		$i++;
		if($i>4)
			break;
		print_r($page);		
		preg_match('/<\_T>(.*?)<\/\_T>/s',$page,$matchT);
		preg_match('/<\_b>(.*?)<\/\_b>/s',$page,$matchb);
		preg_match('/<\_d>(.*?)<\/\_d>/s',$page,$matchd);
		preg_match('/<\_a>(.*?)<\/\_a>/s',$page,$matcha);
		preg_match('/<\_c>(.*?)<\/\_c>/s',$page,$matchc);
		preg_match_all('/<\_t>(.*?)<\/\_t>/s',$page,$matchts);
		//print_r($match);
		$tags='';
		print_r($matchts);
		if(!empty($matchts[1]))
		{
			foreach($matchts[1] as $key=>$tag)
			{
				$tags.=$tag.' ';
			}
		}
		$entry="<h1>".$matchT[1]."</h1>"."<div>发表日期：".$matchd[1]." 作者：".$matcha[1]."分类：".$matchc[1]." 标签： ".$tags."</div>".$matchb[1];		
		$DH_output_content_each =  str_replace("%entry%",$entry,$DH_output_content);
		$DH_output_file = output_page_path($DH_output_html_path,$i+$begincount);
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
	global $begindate,$lists,$pages;
	$content = dh_file_get_contents("$filename");
	//echo $content;
	preg_match_all('/<\_e>(.*?)<\/\_e>/s',$content,$entrys);
	//print_r($entrys);
	foreach($entrys[1] as $key=>$entry)
	{
		//取得date
		preg_match('/<\_d>(.*?)<\/\_d>/s',$entry,$match);
		//print_r($match);
		if(!empty($match[1]))
		{
			if($match[1]>$begindate)
				$pages[$match[1]]=$entry;
			
			preg_match('/<\_b>(.*?)<\/\_b>/s',$entry,$match1);
			if(!empty($match1[1]))
			{
				$eachentry = trim($match1[1]);
				//print_r($match1);
				//去除<h>
				$eachentry = preg_replace( '/<h1>(.*?)<\/h1>/s',"",$eachentry);
				$eachentry = preg_replace( '/<h2>(.*?)<\/h2>/s',"",$eachentry);
				//去除回车键
				$eachentry = preg_replace( '/\n/s',"",$eachentry);
				$eachentry = preg_replace( '/\r/s',"",$eachentry);
				//去除<***>
				$eachentry = preg_replace( '/<(.*?)>/s',"",$eachentry);	
				if(mb_strlen($eachentry,'UTF-8')>64)
				{
					$x = mb_substr($eachentry,0,64,'UTF-8');
					$eachentry = preg_replace( '/<\_b>(.*?)<\/\_b>/s',"<_b>$x</_b>",$entry);
				}			
				$lists[$match[1]]=$eachentry;
			}			
		}		
	}
}

function output_all()
{
	global $lists,$DH_src_path,$begincount,$pages;
	$lists_all='';
	$i=1;
	foreach($lists as $key=>$list)
	{
		$lists_add = "\n<_e>\n<_i>".($i).'</_i>'.$list."\n</_e>";
		$lists_all .= $lists_add;
		$lists[$key]= $lists_add;
		$i++;
	}
	dh_file_put_contents($DH_src_path.'tmp/list.xml',$lists_all);

	$pages_all='';
	$i=1;
	foreach($pages as $key=>$page)
	{
		$pages_add = "\n<_e>\n<i>".($i+$begincount).'</_i>'.$page."\n</_e>";
		$pages_all .= $pages_add;
		$pages[$key]= $pages_add;
		$i++;
	}
	dh_file_put_contents($DH_src_path.'tmp/page.xml',$pages_all);	
}
?>