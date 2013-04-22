<?php
/**
 * AT项目配置文件
 * @name init.php
 * @desc 文件初始化设置,包含此目录包需要的文件及变量声明
 * @author jimmy
 * @updatetime 2012-03-05
 */
if(file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR .'local.inc.php'))
	include dirname(__FILE__) . DIRECTORY_SEPARATOR .'local.inc.php';
include YEPF_PATH . '/global.inc.php';

//路径配置
if($_SERVER['URL_PATH_JS'])		define('URL_PATH_JS', 	$_SERVER['URL_PATH_JS']);else define('URL_PATH_JS', 'http://atp2.yokacdn.com/js');
if($_SERVER['URL_PATH_IMG'])	define('URL_PATH_IMG', 	$_SERVER['URL_PATH_IMG']);else define('URL_PATH_IMG', 'http://atp2.yokacdn.com/images');
if($_SERVER['URL_PATH_CSS'])	define('URL_PATH_CSS', 	$_SERVER['URL_PATH_CSS']);else define('URL_PATH_CSS', 'http://atp2.yokacdn.com/css');
if($_SERVER['URL_PATH_UPLOAD'])	define('URL_PATH_UPLOAD', 	$_SERVER['URL_PATH_UPLOAD']);else define('URL_PATH_UPLOAD', 'http://atp2.yokacdn.com');

//定义网站缓冲设定
define(SiteCacheLevel, 1);				//0: 关闭网站缓冲	 
										//1: 对非用户敏感内容进行缓冲 （自动对匿名用户Smarty::widget进行缓冲）	
										//2: 对匿名用户开启页面级缓冲（注意：如果不做特殊处理，登录页等也被缓存，会导致无法登录）
										//3: 缓冲所有页面，所有用户置为非登录状态。（慎重：仅在极端情况下使用。建议在程序中关闭登录入口。） 
define(SiteCacheTime, 60*1);			//缓冲失效期设置
if($_REQUEST['refresh']==1)
	define(SiteCacheForceRefresh, 1);	//强制刷新缓存
else define(SiteCacheForceRefresh, 0);

/**
 * 工具配置相关
 */
///////////////////////////////////////////////////////////////////////////////////
// SMARTY使用函数
// 注册到Smarty。注意： 需修改YEPF2的Template类
///////////////////////////////////////////////////////////////////////////////////

	/**
	 * 取Url
	 * 为Yaf定制的逆向URL静态化生成函数
	 * @param array $args
	 * eg: <{url _c=foo _a=bar name=jimmy page=3}>  ==> http://localhost/foo/bar/name/jimmy/page/3
	 */
	function template_url_encode($args){
		//\Debug::log('template_url_encode',$args,__FILE__.':'.__LINE__);
		if(!is_array($args)) return 'param error!';
		$_c = $args['_c'] ? $args['_c'] : 'index';
		$_a = $args['_a'] ? $args['_a'] : 'index';
		if(!in_array($_c, array('user','api'))){
			//静态化
			if($_c == 'index')$re = ROOT_DOMAIN . DIRECTORY_SEPARATOR . $_a;
			else $re = ROOT_DOMAIN . DIRECTORY_SEPARATOR . $_c . DIRECTORY_SEPARATOR . $_a;
			ksort($args);
			foreach($args as $key=> $val){
				if($key == '_c' || $key == '_a')continue;
				$re .= DIRECTORY_SEPARATOR . urlencode($key) . DIRECTORY_SEPARATOR . urlencode($val);
			}
			//\Debug::log('template_url_encode',ROOT_DOMAIN . DIRECTORY_SEPARATOR . "index.php?".$t, __FILE__.':'.__LINE__);
			return $re;
		}else{
			//动态地址
			$t = '';
			if(is_array($args))foreach($args as $key=>$val){
				$t .= urlencode($key) . '=' . urlencode($val) .'&';
			}else{
				$t = $args;
			}
			if(substr($t,-1,1)=='&')$t=substr($t,0,strlen($t)-1);
			//\Debug::log('template_url_encode',ROOT_DOMAIN . DIRECTORY_SEPARATOR . "index.php?".$t, __FILE__.':'.__LINE__);
			return ROOT_DOMAIN . DIRECTORY_SEPARATOR . "index.php?".$t;
		}		
	}
	
	/**
	 * 取缩略图
	 * Enter description here ...
	 * @param array $params = array('url'=>$url, 'width'=>$width, 'height'=>$height, 'wt'=>$wt, 'cut'=>$cut)
	 * @param wt, cut 兼容外网图片缩略服务使用
	 * @新增功能： width = 'A'  or  height = 'A'  自适应宽度或高度。注意：resize_img.php 中max_width和max_height的设置
	 * eg:
	 * 标准使用： RewriteRule /upload/([0-9]+)/([a-f0-9]+)/([a-f0-9]+)_([0-9]+)x([0-9]+)\.([a-z]+)$ /upload/resize_img.php?f=$1/$2/$3.$6&w=$4&h=$5&%{QUERY_STRING} [L]
	 * 自适应宽： RewriteRule /upload/([0-9]+)/([a-f0-9]+)/([a-f0-9]+)_(A)x([0-9]+)\.([a-z]+)$ /upload/resize_img.php?f=$1/$2/$3.$6&w=$4&h=$5&%{QUERY_STRING} [L]
	 * 自适应高： RewriteRule /upload/([0-9]+)/([a-f0-9]+)/([a-f0-9]+)_([0-9]+)x(A)\.([a-z]+)$ /upload/resize_img.php?f=$1/$2/$3.$6&w=$4&h=$5&%{QUERY_STRING} [L]
	 * 强制压缩比： RewriteRule /upload/([0-9]+)/([a-f0-9]+)/([a-f0-9]+)_([0-9]+)x([0-9]+)_([0-9]+)\.([a-z]+)$ /upload/resize_img.php?f=$1/$2/$3.$7&w=$4&h=$5&jpg=$6&%{QUERY_STRING} [L]
	 */
	function template_thumb_encode($params){
			$url = $wt = 0;
			extract($params);
			$width = $width?$width:200;
			$height = $height?$height:200;
			
			//功能恢复。 （之前关闭的原因不可考）
			//$face =	false;
			$face = $face?$face:false;
			if($face){
				if(strpos($url,'app.qlogo.cn')){
					//qq
					$url	=	str_replace('/50','/100',$url);
				}else if(strpos($url,'sinaimg.cn')){
					//sina
					$url	=	str_replace('/50','/180',$url);
				}else if(strpos($url,'douban.com')){
					//douban
					$url	=	str_replace('/u','/ul',$url);
				}else if(strpos($url,'ydstatic.com')){
					//163
					$url	=	str_replace('w=48&h=48','w=128&h=128',$url);
				}else if(strpos($url,'yoka.com')){
					//yoka
					$url	=	str_replace('_small.jpg','_big.jpg',$url);
				}else if(strpos($url,'itc.cn')){
					//sohu
					$url	=	str_replace('/m_','/',$url);
				}else if(strpos($url,'xnimg.cn')){
					//xiaonei  本来就是100*100
					$url	=	str_replace('/u','/ul',$url);
				}
			}
			

			if($url){
				if(preg_match('/http:\/\/at\.yoka\.com\/upload\//i',$url) || preg_match('/http:\/\/atp[1-4]\.yokacdn\.com\//i', $url)){ //AT项目存储
					$pathinfo = pathinfo($url);
					$re = $pathinfo['dirname'] .'/'. $pathinfo['filename'] ."_{$width}x{$height}.". $pathinfo['extension'];
					if($cut === 0) $re = $re . '?cut=0';
					return $re;
				}
				else{
					if(!$cut) $cut = 0;
					return	'http://thumb2.yokacdn.com/p?w='.$width.'&h='.$height.'&cut='.$cut.'&wt='.$wt.'&f='.$url; //兼容外网地址
				} 
			}else{
				switch($width){
					case 100:
						return 'http://p1.yokacdn.com/pic/div/2012/products/at2/iframe/img/100_100.png';
						break;
					case 50:
						return 'http://p1.yokacdn.com/pic/div/2012/products/at2/iframe/img/50_50.png';
						break;
					default:
						return false;
						break;
				}
			}
	}
	/**
	 * 取Widget数据
	 * Enter description here ...
	 * @param array $params = array(type=>$type, key=>$key)
 	 */
	function template_widget_encode($params){
		if(is_array($params['key']))$params['key'] = json_encode($params['key']);
		if(strlen($params['key']) > 24) $mkey = "SiteCache.widget.{$params['type']}.".substr(md5($params['key']),0,16);
		else $mkey = "SiteCache.widget.{$params['type']}.{$params['key']}";
		$re = false;
		if(SiteCacheLevel == 1 && !SiteCacheForceRefresh){
			$m = \Cache::getInstance('default');
			if($t = $m->get($mkey)){
				$re = unserialize($t);
//				\Debug::log("get widget cache: $mkey" , $re);
			}
		}
		if(!$re){
			$client = \ThriftClient::getInstance('WidgetService');
			$client ->name = (string)$params['type'];
			$client ->param = (string)$params['key'];
			$re = $client ->getWidget();
			if(SiteCacheLevel == 1){
				$m = \Cache::getInstance('default');
				$m->set($mkey, serialize($re), SiteCacheTime);
//				\Debug::log("set widgt cache: $mkey", $re);
			}
		}
		return $re;
	}
	
	/**
	 * 传入AtTools::Millseconds()输出友好格式时间
	 * Enter description here ...
	 * @param array $params = array('time'=>$datetime) 毫秒时间戳格式
	 */
	function template_nicetime_encode($params){
		extract($params);
		$now = time();
		$diff = $now - ceil($time/1000);
		$hours = ceil($diff/3600);
//		\Debug::log('nicetime', "$time - $now = $diff", __FILE__.":".__LINE__);
		if($diff<300)
		{
			return '刚刚';
		}
		if($diff<3600 && $diff>=300)
		{
			return ceil($diff/60).'分钟前';
		}
		else if($hours <24)
		{
			return $hours.'小时前';
		}
		else if($hours<=(3*24) && $hours>=24)
		{
			return round($hours/24).'天前';
		}
		else
		{
			return date('Y年n月j日',ceil($time/1000));
		}
	}
	
	/**
	 * UTF8切字符（按照指定宽度，ASCII每字符宽度为1，非ASCII宽度为2）
	 * Enter description here ...
	 * @param array $params = array('str'=>$str, 'length'=>$len, 'suffix'=>'...')
	 */
	function template_cutstr_encode($params)
	{
		extract($params);
		if(mb_strwidth($str,'utf8') < $length) return $str;
		if($suffix == '...') return mb_strimwidth($str,0,$length-2,'','utf8') . $suffix;
		else return mb_strimwidth($str,0,$length,$suffix,'utf8');
	}
	
	/**
	 * 数据微调
	 * @param array $params = array('number'=>原始数据, 'skip'=>起跳值, 'rate'=>放大率)
	 */
	function template_nicenumber_encode($params){
		extract($params);
		if($number < 1) return 0;
		if($skip < 1) $skip = 12;
		if($rate < 1) $rate = 1.314;
		if($number < $skip) return $number;
		$tmp = intval( ($number - $skip) * $rate + $skip );
		return $tmp;
	}
