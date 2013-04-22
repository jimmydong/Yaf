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
	 * Enter description here ...
	 * @param array $args
	 */
//备注： 杂志短域名通过 404.php 处理，不经过此函数，备忘。
//备注： /list  /list/1 /liset/1/20121102  为程序直接输出，不经过此函数。备忘。 jimmy.dong@gmail.com 2012.11.02
	function template_url_encode($args){
		//\Debug::log('mvc_url_encode',$args,__FILE__.':'.__LINE__);
		if(1 && is_array($args)){	//地址静态化
			ksort($args);
			$must_dynamic = false;
			foreach($args as $key=>$val){
				if($val === '' || $val === null){
					$must_dynamic = true;
				}
				$keystring.=$key.',';
			}
			extract($args);
			if($_a == 'index') $_a = '';
			$page = intval($page);
			if($must_dynamic != true){
				if($_c == 'index' || $_c == '' || $_c == null)switch ($keystring){
					//首页
					case 'page,':					
					case '_c,page,':
					case '_a,_c,':
					case '_a,_c,page,':
						$page = intval($page);
						$re =  ROOT_DOMAIN . DIRECTORY_SEPARATOR . 'index_'.$c.'_'.$_a.'_'.$page.'.html';
						//RewriteRule /index_([^_]*)_([^_]*)_([0-9]+)\.html /index.php?_c=$1&_a=$2&page=$3&uw=1&%{QUERY_STRING} [L]
					break;
					//推广内容
					case '_a,word,':
					case '_a,aid,word,':
					case '_a,_c,aid,word,':
					case '_a,_c,word,':
						$re =  ROOT_DOMAIN . DIRECTORY_SEPARATOR . 'word_'.$_a.'_'.$aid.'_'.$word.'.html';
						//RewriteRule /word_([^_]*)_([^_]*)_([^_]*)\.html /index.php?_a=$1&aid=$2&word=$3&uw=1&%{QUERY_STRING} [L]
					break;
					case '_a,page,since,word,':
					case '_a,_c,page,since,word,':
						$re =  ROOT_DOMAIN . DIRECTORY_SEPARATOR . 'word_'.$_a.'_'.$aid.'_'.$word.'_'.$page.'_'.$since.'.html';
						//RewriteRule /word_([^_]*)_([^_]*)_([^_]*)_([0-9]+)_([a-f0-9]+)\.html /index.php?_a=$1&aid=$2&word=$3&page=$4&since=$5&uw=1&%{QUERY_STRING} [L]
					break;
					default:
						$must_dynamic = true;
					break;
				}
				if($_c == 'magazine')switch ($keystring){					
					//杂志
					case '_c,short_url':
						//if(!in_array($short_url,array('demo','html','services','tasks','test','upload')))	$re = ROOT_DOMAIN . DIRECTORY_SEPARATOR . $short_url . DIRECTORY_SEPARATOR;
						break;
					case '_a,_c,_id,':
					case '_c,_id,page,':
						$re = ROOT_DOMAIN . DIRECTORY_SEPARATOR . 'mag_'.$_a.'_'.$_id.'_'.$page.'.html';
						//RewriteRule /mag_([^_]*)_([a-f0-9]+)_([0-9]+)\.html /index.php?_c=magazine&_a=$1&_id=$2&page=$3&uw=1&%{QUERY_STRING} [L]
						break;
					case '_a,_c,_id,mag_id,':
						if($_a == 'detail')$re = ROOT_DOMAIN . DIRECTORY_SEPARATOR . 'detail_'.$mag_id.'_'.$_id.'.html';
						//RewriteRule /detail_([a-f0-9]+)_([a-f0-9]+)\.html /index.php?_c=magazine&_a=detail&mag_id=$1&_id=$2&uw=1&%{QUERY_STRING} [L]
					break;
					default:
						$must_dynamic = true;
					break;
				}
				if($_c == 'topic')switch ($keystring){					
					//主题
					case '_c,_id,':
					case '_a,_c,_id,':
					case '_c,_id,page,':
					case '_a,_c,_id,page,':
						if($_a == 'detail') $re = ROOT_DOMAIN . DIRECTORY_SEPARATOR . 'url_'.$_id.'.html';
						//RewriteRule /url_([a-f0-9]+)\.html /index.php?_c=topic&_a=detail&_id=$1&uw=1&%{QUERY_STRING} [L]
						else $re = ROOT_DOMAIN . DIRECTORY_SEPARATOR . 'topic_'.$_a.'_'.$_id.'_'.$page.'.html';
						//RewriteRule /topic_([^_]*)_([a-f0-9]+)_([0-9]+)\.html /index.php?_c=topic&_a=$1&_id=$2&page=$3&uw=1&%{QUERY_STRING} [L]
					break;
					default:
						$must_dynamic = true;
					break;
				}
				if($_c == 'user')switch ($keystring){					
					//用户 
					case '_c,user_id,':
					case '_a,_c,user_id,':
						$re = ROOT_DOMAIN . DIRECTORY_SEPARATOR . 'user_'.$_a.'_'.$user_id.'.html';
						//RewriteRule /user_([^_]*)_([a-f0-9]+)\.html /index.php?_c=user&_a=$1&user_id=$2&uw=1&%{QUERY_STRING} [L]
					break;
					case '_c,user_id,page,since,':
					case '_a,_c,user_id,page,since,':
						$re = ROOT_DOMAIN . DIRECTORY_SEPARATOR . 'user_'.$_a.'_'.$user_id.'_'.$page.'_'.$since.'.html';
						//RewriteRule /user_([^_]*)_([a-f0-9]+)_([0-9]+)_([a-f0-9]+)\.html /index.php?_c=user&_a=$1&user_id=$2&page=$3&since=$4&uw=1&%{QUERY_STRING} [L]
					break;
					default:
						$must_dynamic = true;
					break;
				}
				if($_c == 'activity')switch ($keystring){
					//活动
					/*
					RewriteRule /hd_([^_]*)_([0-9]+)\.html /index.php?_c=activity&_a=$1&actid=$2&%{QUERY_STRING} [L]
					RewriteRule /hd_([^_]*)_([0-9]+)_([0-9]+)\.html /index.php?_c=activity&_a=$1&actid=$2&dyp=$3&%{QUERY_STRING} [L]
					*/
					case '_a,_c,actid,':
						$re = ROOT_DOMAIN . DIRECTORY_SEPARATOR . 'hd_'.$_a.'_'.$actid.'.html';
						break;
					case '_a,_c,actid,dyp,':
						$re = ROOT_DOMAIN . DIRECTORY_SEPARATOR . 'hd_'.$_a.'_'.$actid.'_'.$dyp.'.html';
						break;
				}
				if($_c == 'catalog'){
					//频道
					/*
					RewriteRule /c/wedding/([0-9]+) /index.php?_c=catalog&cata_id=11&page=$1&uw=1&%{QUERY_STRING} [L]
					RewriteRule /c/wedding /index.php?_c=catalog&cata_id=11&uw=1&%{QUERY_STRING} [L]
					RewriteRule /c/models/([0-9]+) /index.php?_c=catalog&cata_id=10&page=$1&uw=1&%{QUERY_STRING} [L]
					RewriteRule /c/models /index.php?_c=catalog&cata_id=10&uw=1&%{QUERY_STRING} [L]
					RewriteRule /c/illustration/([0-9]+) /index.php?_c=catalog&cata_id=12&page=$1&uw=1&%{QUERY_STRING} [L]
					RewriteRule /c/illustration /index.php?_c=catalog&cata_id=12&uw=1&%{QUERY_STRING} [L]
					RewriteRule /c/travel/([0-9]+) /index.php?_c=catalog&cata_id=2&page=$1&uw=1&%{QUERY_STRING} [L]
					RewriteRule /c/travel /index.php?_c=catalog&cata_id=2&uw=1&%{QUERY_STRING} [L]
					RewriteRule /c/streetsnap/([0-9]+) /index.php?_c=catalog&cata_id=3&page=$1&uw=1&%{QUERY_STRING} [L]
					RewriteRule /c/streetsnap /index.php?_c=catalog&cata_id=3&uw=1&%{QUERY_STRING} [L]
					RewriteRule /c/fashion/([0-9]+) /index.php?_c=catalog&cata_id=1&page=$1&uw=1&%{QUERY_STRING} [L]
					RewriteRule /c/fashion/ index.php?_c=catalog&cata_id=1&uw=1&%{QUERY_STRING} [L]
					RewriteRule /c/pets/([0-9]+) /index.php?_c=catalog&cata_id=7&page=$1&uw=1&%{QUERY_STRING} [L]
					RewriteRule /c/pets /index.php?_c=catalog&cata_id=7&uw=1&%{QUERY_STRING} [L]
					RewriteRule /c/photography/([0-9]+) /index.php?_c=catalog&cata_id=4&page=$1&uw=1&%{QUERY_STRING} [L]
					RewriteRule /c/photography /index.php?_c=catalog&cata_id=4&uw=1&%{QUERY_STRING} [L]
					
					RewriteRule /catalog_([^_]*)_([0-9]+)_([0-9]+).html /index.php?_c=catalog&_a=$1&cata_id=$2&page=$3&%{QUERY_STRING} [L]
					*/
					$channel_defined = array( 1=>'fashion'
						,2=>'travel'
						,3=>'streetsnap'
						,4=>'photography'
						,7=>'pets'
						,10=>'models'
						,11=>'wedding'
						,12=>'illustration');
					switch ($keystring){
						case '_c,cata_id,' : 
						case '_c,cata_id,page,' :
							$re = ROOT_DOMAIN . DIRECTORY_SEPARATOR . 'c/' . $channel_defined[$cata_id];
							if($page) $re .= '/' . $page;
							break;
						case '_a,_c,cata_id,' :
						case '_a,_c,cata_id,page,' :
							$re = ROOT_DOMAIN . DIRECTORY_SEPARATOR . 'catalog_' . $_a . '_' . $cata_id . '_' . $page . '.html';
							break;
						default: break;
					}
				}
				if($re == '') {
				/*通用转换。支持0-4个参数。对应control中应增加对$request->param的判断和处理。
				 	RewriteRule /common_([^_]*)_([^_]*)_([0-9]+)\.html /index.php?_c=$1&_a=$2&page=$3&%{QUERY_STRING} [L]
					RewriteRule /common_([^_]*)_([^_]*)_([^_]*)_([0-9]+)\.html /index.php?_c=$1&_a=$2&page=$4&param[]=$3&%{QUERY_STRING} [L]
					RewriteRule /common_([^_]*)_([^_]*)_([^_]*)_([^_]*)_([0-9]+)\.html /index.php?_c=$1&_a=$2&page=$5&param[]=$3&param[]=$4&%{QUERY_STRING} [L]
					RewriteRule /common_([^_]*)_([^_]*)_([^_]*)_([^_]*)_([^_]*)_([0-9]+)\.html /index.php?_c=$1&_a=$2&page=$6&param[]=$3&param[]=$4&param[]=$5&%{QUERY_STRING} [L]
					RewriteRule /common_([^_]*)_([^_]*)_([^_]*)_([^_]*)_([^_]*)_([^_]*)_([0-9]+)\.html /index.php?_c=$1&_a=$2&page=$7&param[]=$3&param[]=$4&param[]=$5&param[]=$6&%{QUERY_STRING} [L]
				 */
					if(in_array($_c, array('app360','addtopic','addurl','export','detail','letter','longinat','loginyoka','message','search','search2','upload','url','urlplugin'))) $must_dynamic = true; //注意，不能使用通用转化的control，需要在这里进行注册
					else{
						$param = array();
						foreach($args as $key=>$val){
							if($key == '_a' || $key == '_c' || $key == 'page') continue;
							$param[] = urlencode($val);
						}
						$re = ROOT_DOMAIN . DIRECTORY_SEPARATOR . 'common_' . $_c . '_' . $_a;
						foreach($param as $val) $re .= '_' . $val;
						$re.= '_' . $page . '.html';
					}			
				}
			}
			if($must_dynamic != true){
				//\Debug::log('UW:'.$re,$args);
				return $re;
			}
		}
		//\Debug::log('UW: not match',$args);
		
		//动态地址
		$t = '';
		if(is_array($args))foreach($args as $key=>$val){
			$t .= urlencode($key) . '=' . urlencode($val) .'&';
		}else{
			$t = $args;
		}
		if(substr($t,-1,1)=='&')$t=substr($t,0,strlen($t)-1);
		//\Debug::log('mvc_url_encode',ROOT_DOMAIN . DIRECTORY_SEPARATOR . "index.php?".$t, __FILE__.':'.__LINE__);
		return ROOT_DOMAIN . DIRECTORY_SEPARATOR . "index.php?".$t;
		
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
