<?php
/**
 * @name TestController
 * @author root
 * @desc 测试控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class TestController extends \Yaf\Controller_Abstract {
	/**
	 * 分发器，配合route rewrite按照_a分配到对应的action
	 * 当action返回为true时，调用 controller/action模板进行渲染
	 */
	public function handoutAction(){
		\Debug::log('trac', " Begin of Controller: " . $this->getRequest()->getControllerName());
		//初始化输入输出
		$this->request = new Request($this->getRequest()->getParams());
		$this->response = new Response(array());
		//分发action
		$action =  $this->request->_a;
		if( method_exists($this, $action) ) $re = $this->$action($this->request, $this->response); 
		else{
			$exception = new \Yaf\Exception\LoadFailed\Action('Action ' . $action . ' is not found!');
			throw $exception;	
		} 
		//分发后处理
		if($re){
			//统一模板输出处理
			$tpl = new \Template('defalut');
			$tpl->fit_sprite($this->response);
			$YOKA['output'] = $tpl->r($this->getRequest()->getControllerName() . '/' . $this->request->_a);
			echo $YOKA['output'];
		}
		\Debug::log('trac', " End of Controller: " . $this->getRequest()->getControllerName());
	}
	
	public function index($request, $response){
		var_dump($request);
		return false;
	}
	
	/**
	 * 测试路由、参数、模板、类加载路径是否正常
	 * Enter description here ...
	 * @param object $request 传入参数
	 * @param object $response 传出参数
	 */
	public function test($request, $response) {
		//Use this URL to test: http://localhost/test/index/foo/bar?title=bigtitle
		
		//$m = new \Cap\TestModel();
		//$m->test();
		
		//$test = new Test();
		//$test->test();
		
		//$ns = new \ns1\TestNs();
		//$ns->test();
		
		//add something to response
		$response->title = $request->title;
		$response->foo = $request->foo;
		$response->content = 'This is a demo for how to use smarty in YAF';

		return true;   //如果返回false，则不进行统一模板处理
	}
}
