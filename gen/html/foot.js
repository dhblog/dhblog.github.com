//收藏本站
function AddFavorite(title, url) 
{
    try 
	{
        window.external.addFavorite(url, title);
    }
    catch (e) 
	{
        try 
		{
            window.sidebar.addPanel(title, url, "");
        }
        catch (e) 
		{
            alert("抱歉，您所使用的浏览器无法完成此操作。\n\n加入收藏失败，请使用Ctrl+D进行添加");
        }
    }
};

window.onscroll = function()
{
	var h =document.body.scrollTop,top = document.getElementById('goTopButton');
	if(h>0)
	{
		top.style.display = 'block';
	}
	else
	{
		top.style.display = 'none';
	}
};

function cnzz()
{
	(function() {
		var cnzz = document.createElement('script');
		cnzz.type = 'text/javascript';
		cnzz.src = 'http://s22.cnzz.com/z_stat.php?id=1000362336&web_id=1000362336';
		(document.getElementsByTagName('body')[0]
		||document.getElementsByTagName('head')[0]).appendChild(cnzz);
	})();
};
	
function duoshuo()
{
	//多说
	(function() {
		var ds = document.createElement('script');
		ds.type = 'text/javascript';
		ds.async = false;
		ds.src = 'http://static.duoshuo.com/embed.js';
		ds.charset = 'UTF-8';
		(document.getElementsByTagName('body')[0]
		|| document.getElementsByTagName('head')[0]).appendChild(ds);
	})();
};
	
window.onload = function ()
{
	cnzz();
	duoshuo();
	//iframe_say.window.location.reload();
};