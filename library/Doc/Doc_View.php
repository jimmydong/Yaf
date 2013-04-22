<?php
namespace \Yaf\View;
class Simple implements Yaf\View_Interface { 
	/* constants */

	/* properties */
	protected $_tpl_vars = NULL;
	protected $_tpl_dir = NULL;
	protected $_options = NULL;

	/* methods */
	final public function __construct($tempalte_dir, array $options = NULL) {
	}
	public function __isset($name) {
	}
	public function get($name = NULL) {
	}
	public function assign($name, $value = NULL) {
	}
	public function render($tpl, $tpl_vars = NULL) {
	}
	public function _eval($tpl_str, $vars = NULL) { //true name is eval
	}
	public function display($tpl, $tpl_vars = NULL) {
	}
	public function assignRef($name, &$value) {
	}
	public function clear($name = NULL) {
	}
	public function setScriptPath($template_dir) {
	}
	public function getScriptPath() {
	}
	public function __get($name = NULL) {
	}
	public function __set($name, $value = NULL) {
	}
}