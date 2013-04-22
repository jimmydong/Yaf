<?php
/*
 * This View do nothing, only to avoid warnning of "Error Msg:Failed opening template ..."
 */
class NullView implements Yaf\View_Interface { 
	/* constants */

	/* properties */
	protected $_tpl_vars = NULL;
	protected $_tpl_dir = NULL;
	protected $_options = NULL;

	/* methods */
	final public function __construct() {
	}
	public function assign($name, $value = NULL) {
	}
	public function render($tpl, $tpl_vars = NULL) {
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
}