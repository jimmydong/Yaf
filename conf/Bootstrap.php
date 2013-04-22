<?php
/**
 * @name Bootstrap
 * @author root
 * @desc 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * @see http://www.php.net/manual/en/class.yaf-bootstrap-abstract.php
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends \Yaf\Bootstrap_Abstract{

    public function _initConfig() {
		//把配置保存起来
		$arrConfig = \Yaf\Application::app()->getConfig();
		\Yaf\Registry::set('config', $arrConfig);
		//设置项目中的常用参数：
	}

    public function _initDefaultName(\Yaf\Dispatcher $dispatcher) {
        //设置默认的controller和action。注意： 这里为了配合route_map，将默认action设为handout进行分发 
        $dispatcher->setDefaultController("index")->setDefaultAction("handout");
    }

	public function _initPlugin(\Yaf\Dispatcher $dispatcher) {
		//注册一个插件
		$yepf2Plugin = new Yepf2Plugin();
		$dispatcher->registerPlugin($yepf2Plugin);
	}

	public function _initRoute(\Yaf\Dispatcher $dispatcher) {
		/**
		 * 在Rewrite基础上改进的路由规则
		 * 每一个control增加一个路由规则，默认使用handout方法处理传入的_a，调用对应的action
		 * 注意：越下面的路由规则优先级越高，请注意第一个参数的名字勿与index的action冲突
		 */
		$router = \Yaf\Dispatcher::getInstance()->getRouter();

		$r = new \Yaf\Route\Rewrite('/:_a/*',array('controller'=> index));
		$router->addRoute('index', $r);
		$r = new \Yaf\Route\Rewrite('/test/:_a/*',array('controller'=> test));
		$router->addRoute('test', $r);
	}
	
	public function _initView(\Yaf\Dispatcher $dispatcher){
		//置空view，采用自己的输出控制
		$null_view = new NullView();
        \Yaf\Dispatcher::getInstance()->setView($null_view);
	}
}
