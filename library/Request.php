<?php
/**
 * @name Request.php
 * @desc 保存原始传入变量。仅允许初始化时一次性添加属性值。
 * @author "jimmy.dong@gmail.com"
 * 
 */
class Request extends ArrayObject {
	public function __get($name){
		if(isset($this[$name])) return $this[$name];
		if(isset($_GET[$name])) return $_GET[$name];
		if(isset($_POST[$name])) return $_POST[$name];
	}
}