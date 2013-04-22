<?php
namespage \Yaf\Route;
final class Simple implements Yaf\Route_Interface { 
	/* constants */

	/* properties */
	protected $controller = NULL;
	protected $module = NULL;
	protected $action = NULL;

	/* methods */
	public function __construct($module_name, $controller_name, $action_name) {
	}
	public function route($request) {
	}
}

final class Supervar implements Yaf\Route_Interface { 
	/* constants */

	/* properties */
	protected $_var_name = NULL;

	/* methods */
	public function __construct($supervar_name) {
	}
	public function route($request) {
	}
}

final class Rewrite extends Yaf\Route_Interface implements Yaf\Route_Interface { 
	/* constants */

	/* properties */
	protected $_route = NULL;
	protected $_default = NULL;
	protected $_verify = NULL;

	/* methods */
	public function __construct($match, array $route, array $verify = NULL) {
	}
	public function route($request) {
	}
}

final class Regex extends Yaf\Route_Interface implements Yaf\Route_Interface { 
	/* constants */

	/* properties */
	protected $_route = NULL;
	protected $_default = NULL;
	protected $_maps = NULL;
	protected $_verify = NULL;

	/* methods */
	public function __construct($match, array $route, array $map = NULL, array $verify = NULL) {
	}
	public function route($request) {
	}
}

final class Map implements Yaf\Route_Interface { 
	/* constants */

	/* properties */
	protected $_ctl_router = "";
	protected $_delimeter = NULL;

	/* methods */
	public function __construct($controller_prefer = NULL, $delimiter = NULL) {
	}
	public function route($request) {
	}
}