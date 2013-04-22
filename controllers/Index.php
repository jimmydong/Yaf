<?php
/**
 * @name IndexController
 * @author root
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class IndexController extends \Yaf\Controller_Abstract {
	/**
	 * 分发器，配合route rewrite按照_a分配到对应的action
	 */
	public function handoutAction(){
		echo "\n<br/> Begin of Controller: " . $this->getRequest()->getControllerName();
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
		echo "\n<br/> End of Controller: " . $this->getRequest()->getControllerName();
	}
	
	/** 
     * 默认动作
     */
	public function index($request, $response) {
		echo "\n<br/> Action : index。 Full test: http://localhost/test/index/foo/bar?title=bigtitle";
	}
	
	public function other($request, $response){
		echo "\n<br/> Action : other";
	}
}
