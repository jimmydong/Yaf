<?php
/**
 * @name local.inc.php
 * @desc 本地配置文件 - 注意：本配置文件为了区分开发者个人环境、公用测试环境及线上环境。勿提交SVN。
 * @author caoxd
 * @createtime 2009-02-16 11:25
 * @updatetime
 * @caution 路径和URL请不要加反斜线
 **/

/*---------------------------框架级别常量开始---------------------------------*/
//框架根目录
define('YEPF_PATH', '/YOKA/HTML/_YEPF2.0');
//是否被正确包含
define('YOKA', true);
//强制转义开关,特殊情况下请设置为true,建议为false
define('YEPF_FORCE_CLOSE_ADDSLASHES', false);
/*--------可以被更小产品级覆盖常量开始,覆盖下面的常量请放在init.php程序中-------*/
//是否默认打开调试模式
if(!defined('YEPF_IS_DEBUG'))
{
	//define('YEPF_IS_DEBUG', $_SERVER['YEPF_IS_DEBUG']);
	define('YEPF_IS_DEBUG', 'yoka-inc4');
//	define('YEPF_IS_DEBUG', false);
	
}
//自定义错误级别,只有在调试模式下生效
if(!defined('YEPF_ERROR_LEVEL'))
{
	define('YEPF_ERROR_LEVEL', E_ALL & ~E_NOTICE);
}

/*---------------------------资源配置开发-------------------------------------*/
$CACHE['memcached'] = array(
     'default' => array('server'=>array(array('host'=>$_SERVER['CACHE_DEFAULT_HOST'],'port'=>$_SERVER['CACHE_DEFAULT_PORT'])))
);
$CACHE['mongodb'] = array(
     'default' => array( 'master'=>array(
							array('host'=>$_SERVER['MG_SERVER_AT'],'user'=>$_SERVER['MG_USER_AT'],'password'=>$_SERVER['MG_PASS_AT'],'db'=>$_SERVER['MG_DB_AT'])
						)
		)
);

$CACHE['db'] = array(
    'default'=>array(
		 //主数据库
		 'master' =>array(
		 				array('host'=>$_SERVER['MDB_SERVER_AT'],'user'=>$_SERVER['MDB_USER_AT'], 'password'=>$_SERVER['MDB_PASS_AT'] , 'database'=>$_SERVER['MDB_DB_AT'])
		 			),
        //从数据库
		'slave' => array(
		 				array('host'=>$_SERVER['MDB_SERVER_AT'],'user'=>$_SERVER['MDB_USER_AT'], 'password'=>$_SERVER['MDB_PASS_AT'] , 'database'=>$_SERVER['MDB_DB_AT'])
					),
		),
);

$GLOBALS['mongodb_config'] =  array (  host => $_SERVER['MG_SERVER_AT'],
                                                       database=>$_SERVER['MG_DB_AT']);

$GLOBALS['rabbit_mq_config'] =  array(
	array (
			host => $_SERVER['MMQ_HOST_AT'],
			port => $_SERVER['MMQ_PORT_AT'],
			user_name=> $_SERVER['MMQ_USER_AT'],
			password => $_SERVER['MMQ_PASS_AT'],
			virtual_host => '/',
			insist => False ),
	array (
			host => $_SERVER['SMQ_HOST_AT'],
			port => $_SERVER['SMQ_PORT_AT'],
			user_name=> $_SERVER['SMQ_USER_AT'],
			password => $_SERVER['SMQ_PASS_AT'],
			virtual_host => '/',
			insist => False)
);


/*---------------------------项目级别常量开始---------------------------------*/
//此项目的根目录URL
define('ROOT_DOMAIN','http://jimmy.at.yoka.com');  //如果修改此设置，请勿提交SVN

//此项目绝对地址
define('ROOT_PATH',dirname(__FILE__));

if (function_exists('xhprof_enable') && mt_rand(1, 1) == 1) {
 xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
 define('XHPROF_ON', true);
 /* 下述请配置在controller执行结束处。放置到onshutdown中无效。
		if($GLOBALS['xhprof_on']){
			include('/YOKA/HTML/81/xhprof/xhprof_lib/utils/xhprof_lib.php');
			include('/YOKA/HTML/81/xhprof/xhprof_lib/utils/xhprof_runs.php');
				$xhprof_data = xhprof_disable();
			 	$xhprof_runs = new \XHProfRuns_Default();
			 	$run_id = $xhprof_runs->save_run($xhprof_data, 'at');
			 	\Debug::log('xhprof_id',$run_id,"http://at.yoka.com:81/xhprof/xhprof_html/index.php?run=$run_id&source=at");
		}
 */
}

/*--------下面的常量定义都可以被更小项目中前缀为SUB_的同名常量所覆盖-------*/
//此项目日记文件地址
define('LOG_PATH',ROOT_PATH . '/_LOG');
//模板文件目录
define('TEMPLATE_PATH',ROOT_PATH . '/views');
//模板文件编绎目录
define('COMPILER_PATH',ROOT_PATH . '/tmp');
//默认的模板文件后缀名
define('TEMPLATE_TYPE','html');
//配置文件自动加载目录
//define('AUTOLOAD_CONF_PATH',ROOT_PATH . '/_AUTOLOAD');
//自定义类加载路径
//define('CUSTOM_CLASS_PATH', ROOT_PATH . '/_CUSTOM_CLASS');


/*---------------------------项目开发者环境信息-------------------------------*/
$GLOBALS['THRIFT_SERVER'] = 'jimmy.at.yoka.com'; 			//定义Thrift服务端地址，用于开发者调试。正式代码中请在client程序中显式指明。
$GLOBALS['THRIFT_TRANSPORT'] = 'Local';						//定义Thrift通讯方式。Local:本地类方式(不使用Thrift), CurlClient: TCurlClient(默认),HttpClient:THttpClient, Socket:TSocket(待开发)
//$GLOBALS['THRIFT_TRANSPORT'] = 'CurlClient';						//定义Thrift通讯方式。Local:本地类方式(不使用Thrift), CurlClient: TCurlClient(默认),HttpClient:THttpClient, Socket:TSocket(待开发)

$GLOBALS['THRIFT_DEBUG']  = 2;									//定义调试级别。
$_SERVER['URL_PATH_UPLOAD'] = 'http://jimmy.at.yoka.com/upload';
?>
