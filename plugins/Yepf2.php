<?php
/**
 * @name YEPF2.0插件
 * @desc 引入YEPF2.0框架
 * @author jimmy.dong@gmail.com 20130422
 */
class Yepf2Plugin extends \Yaf\Plugin_Abstract {
	var $SiteCache_mkey = '';
	var $SiteCache_exclude = false;

	public function routerStartup(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response) {
		//在路由之前触发
		include(APPLICATION_PATH . '/local.inc.php');
		include(APPLICATION_PATH . '/YEPF2.inc.php');
	}

	public function routerShutdown(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response) {
		//路由结束之后触发
		\Debug::log('trac', "routerShutDone: ");
		//$router = \Yaf\Dispatcher::getInstance()->getRouter();
		//var_dump($router);
		
		/**
		 * 缓存处理
		 */
		if(!ISLOGIN && (SiteCacheLevel==2 || SiteCacheLevel==3)){ //开启页面级缓冲
			if(SiteCacheForceRefresh) unset($_GET['refresh']);
			$this->SiteCache_mkey = 'SiteCache.' . $request->getRequestUri() . '.' .md5(serialize($_GET).serialize($_POST));
			//部分页面不做缓存，请列举在下面
			if( preg_match('/login/i',$request->getParam('_c'))
				|| preg_match('/thirdcallback/', $request->getRequestUri())
			)$this->SiteCache_exclude = true;
			
			if(!SiteCacheForceRefresh && !$this->SiteCache_exclude){
				$re = false;
				$re = $this->getCache($this->SiteCache_mkey);
				if($re){
					echo $re;
					\Debug::log('SiteCache:OK',$this->SiteCache_mkey);					
					exit;
				}
				\Debug::log('SiteCache:Fail',$this->SiteCache_mkey);					
			}
			ob_start();
		}
	}

	public function dispatchLoopStartup(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response) {
		//分发循环开始之前被触发
		\Debug::log('trac', "dispatchLoopStartup: ");
	}

	public function preDispatch(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response) {
		//如果在一个请求处理过程中, 发生了forward, 则这个事件会被触发多次
		\Debug::log('trac', "preDispatch: ");
	}

	public function postDispatch(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response) {
		//此时动作已经执行结束, 视图也已经渲染完成. 和preDispatch类似, 此事件也可能触发多次
		\Debug::log('trac', "postDispatch: ");
	}

	public function dispatchLoopShutdown(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response) {
		//所有的业务逻辑都已经运行完成, 但是响应还没有发送
		//echo "\n<br/>dispatchLoopShutdown: ";
		/**
		 * 缓存处理
		 */
		if(!ISLOGIN && (SiteCacheLevel==2 || SiteCacheLevel==3) && !$this->SiteCache_exclude){ //保存页面级缓冲
			$re = ob_get_contents();
			ob_end_clean();
			echo $re;
			$this->setCache($this->SiteCache_mkey, $re);
			\Debug::log('SiteCache:set',$this->SiteCache_mkey);
		}
		if(XHPROF_ON){
			include('/YOKA/HTML/81/xhprof/xhprof_lib/utils/xhprof_lib.php');
			include('/YOKA/HTML/81/xhprof/xhprof_lib/utils/xhprof_runs.php');
				$xhprof_data = xhprof_disable();
			 	$xhprof_runs = new \XHProfRuns_Default();
			 	$run_id = $xhprof_runs->save_run($xhprof_data, 'at');
			 	\Debug::log('xhprof_id',$run_id,"http://at.yoka.com:81/xhprof/xhprof_html/index.php?run=$run_id&source=at");
		}
		
	}
	
	private function getCache($mkey){
		if((SiteCacheLevel == 1 || SiteCacheLevel == 2 || SiteCacheLevel == 3) && !SiteCacheForceRefresh){
			$m = \Cache::getInstance('default');
			if($re = $m->get($mkey)){
				$re = unserialize($re);
				\Debug::log('Base:getCache',$mkey);
				return $re;
			}
		}
		return false;
	}
	private function setCache($mkey, $content ,$cacheTime=60){
		if(SiteCacheLevel == 1 || SiteCacheLevel == 2 || SiteCacheLevel == 3 || SiteCacheForceRefresh){
			$m = \Cache::getInstance('default');
			$re = $m->set($mkey, serialize($content), intval($cacheTime));
			\Debug::log('Base:setCache '.$re,$mkey);
			return $re;
		}else{
			return false;
		}
	}
	
}
