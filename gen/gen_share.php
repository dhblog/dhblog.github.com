<?php
/////////////////////////////////////////////////////
/// 函数名称：gen 
/// 函数作用：产生foot head side文件
/// 函数作者: DH
/// 作者地址: http://dhblog.org/ 
/////////////////////////////////////////////////////

//header('Content-Type:text/html;charset= UTF-8'); 

#需要使用的基础函数
//include("config.php");
//include("compressJS.class.php");

//dh_gen_share(array(),20);

function dh_gen_share($tags,$countpages)
{
	global $DH_html_path,$DH_index_url,$DH_output_path,$DH_input_path,$DH_home_url,$DH_page_store_deep,$conn;
	
	$DH_share_output_path = $DH_input_path.'gen/top/';
	if (!file_exists($DH_share_output_path))  
	{   
		mkdir($DH_share_output_path,0777);
	}	
	
	$DH_input_html  = $DH_html_path . 'foot.html';
	$DH_output = dh_file_get_contents($DH_input_html);
	$DH_output = str_replace("%home%",$DH_home_url,$DH_output);	
	$DH_output_file = $DH_share_output_path. 'foot.html';
	dh_file_put_contents($DH_output_file,$DH_output);
	echo "gen foot success !</br>\n";
	$DH_cse_foot=$DH_output;

	$DH_input_html  = $DH_html_path . 'head.html';
	$DH_output = dh_file_get_contents($DH_input_html);
	$DH_output = str_replace("%home%",$DH_home_url,$DH_output);	
	$DH_output_file = $DH_share_output_path. 'head.html';
	dh_file_put_contents($DH_output_file,$DH_output);		
	echo "gen head success !</br>\n";
	$DH_cse_head=$DH_output;
	
	$DH_input_html  = $DH_html_path . 'meta.html';
	$DH_output = dh_file_get_contents($DH_input_html);	
	$DH_input_html  = $DH_html_path . 'meta.js';
	$DH_meta_js = dh_file_get_contents($DH_input_html);	
	$DH_meta_js = str_replace("%home%",$DH_home_url,$DH_meta_js);
	$myPacker = new compressJS($DH_meta_js);	
	$DH_meta_js = $myPacker->pack();
	$DH_output = str_replace("%metajs%",$DH_meta_js,$DH_output);	
	$DH_output = str_replace("%home%",$DH_home_url,$DH_output);
	//$DH_output = higrid_compress_html($DH_output);
	$DH_output_file = $DH_share_output_path. 'meta.html';
	dh_file_put_contents($DH_output_file,$DH_output);	
	echo "gen meta success !</br>\n";
	
	$DH_input_html  = $DH_html_path . 'side_each.html';
	$DH_side_each = dh_file_get_contents($DH_input_html);
	
	$DH_input_html  = $DH_html_path . 'side_each2.html';
	$DH_side_each2 = dh_file_get_contents($DH_input_html);	
	//广告
	$DH_side_ad= str_replace("%title%",'广告',$DH_side_each);
	$DH_side_ad= str_replace("%more%",'',$DH_side_ad);
	$DH_side_ad= str_replace("%content%",'',$DH_side_ad);
	
	//网站统计
	$DH_side_tongji= str_replace("%title%",'网站统计',$DH_side_each2);
	$DH_side_tongji= str_replace("%more%",'',$DH_side_tongji);
	$diffecho = '';
	$datetoday = strtotime(date("Y-m-d"));
	$datebegin = strtotime('2011-02-08');
	$diff = round(($datetoday - $datebegin)/86400);
	$year = floor($diff / 360);
	if($year>0)
		$diffecho .= $year.'年';
	$monthc = $diff % 360;
	$month = floor($monthc/30);
	if($month>0)
		$diffecho .= $month.'月';	
	$days = $monthc % 30;
	if($days>0)
		$diffecho .= $days.'天';		
	$tongji='<li>运行时间: <span style="font-size:12px;color:#555;">'.$diff.'天</span></li>';
	
	$tongji.='<li>博文总数: <span style="font-size:12px;color:#555;">'.$countpages.'篇</span></li>';
	
	$tongji.='<li>最近更新: <span style="font-size:12px;color:#555;">'.$countpages.'篇</span></li>';
	
	$tongji.='<li>标签数目: <span style="font-size:12px;color:#555;">'.$countpages.'篇</span></li>';
	
	$tongji.='<li>分类数目: <span style="font-size:12px;color:#555;">'.$countpages.'篇</span></li>';
    
	//$datetoday =date("Y-m-d");
	//$sql="select count(*) from page where updatetime >= '$datetoday'";
	//$results=dh_mysql_query($sql);	
	//$count = mysql_fetch_array($results);	
	//$tongji.="\n".'<li><span class="lt2v0">最新更新:</span>'.'<span class="rt2v0 cred">'.$count[0].' 部</span></li>';

	$tongji = "<ul>".$tongji.'</ul>';
	$DH_side_tongji= str_replace("%content%",$tongji,$DH_side_tongji);
	
	//友情链接
	$DH_side_fl= str_replace("%title%",'友情链接',$DH_side_each);
	$DH_side_fl= str_replace("%content%",'',$DH_side_fl);
	
	//$DH_side_content=$DH_side_ad.$DH_side_hotmovie.$DH_side_hottv.$DH_side_tongji;
	
	$DH_input_html  = $DH_html_path . 'side_eacht.html';
	$DH_side_eacht = dh_file_get_contents($DH_input_html);
	$tagsall='';
	foreach($tags as $key=>$tag)
	{
		$urlcode = rawurlencode($key);
		$urlcode = 't'.str_replace("%",'',$urlcode);	
		$tagsall.="<a href=\"$DH_index_url/$urlcode/1.html\" title=\"共有文章 $tag 篇\">$key</a>";
	}	
	$DH_side_eacht= str_replace("%content%",$tagsall,$DH_side_eacht);	
	
	$DH_side=$DH_side_ad.$DH_side_eacht.$DH_side_tongji;	
	
	$DH_output_file = $DH_share_output_path. 'side.html';
	dh_file_put_contents($DH_output_file,$DH_side);	
	echo "gen side success !</br>\n";
	
	echo "gen cse success !</br>\n";	
}
?>