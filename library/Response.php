<?php
/**
 * Resonse.php
 * 保存输出变量
 * @author "jimmy.dong@gmail.com"
 *
 */
class Response extends ArrayObject {
	public function __set($name, $val){
		$this[$name] = $val;
	}
	public function __get($name){
		if(isset($this[$name])) return $this[$name];
		else return null;
	}
}