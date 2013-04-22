<?php
namespace \Yaf\Response;
class Http extends Yaf\Response_Abstract { 
	/* constants */
	const DEFAULT_BODY = content;

	/* properties */
	protected $_header = NULL;
	protected $_body = NULL;
	protected $_sendheader = "1";
	protected $_response_code = "200";

	/* methods */
	public function __construct() {
	}
	public function __destruct() {
	}
	private function __clone() {
	}
	public function __toString() {
	}
	public function setBody($body, $name = NULL) {
	}
	public function appendBody($body, $name = NULL) {
	}
	public function prependBody($body, $name = NULL) {
	}
	public function clearBody($name = NULL) {
	}
	public function getBody($name = NULL) {
	}
	public function setHeader() {
	}
	public function setAllHeaders() {
	}
	public function getHeader() {
	}
	public function clearHeaders() {
	}
	public function setRedirect($url) {
	}
	public function response() {
	}
}

class Cli extends Yaf\Response_Abstract { 
	/* constants */
	const DEFAULT_BODY = content;

	/* properties */
	protected $_header = NULL;
	protected $_body = NULL;
	protected $_sendheader = "";

	/* methods */
	public function __construct() {
	}
	public function __destruct() {
	}
	private function __clone() {
	}
	public function __toString() {
	}
	public function setBody($body, $name = NULL) {
	}
	public function appendBody($body, $name = NULL) {
	}
	public function prependBody($body, $name = NULL) {
	}
	public function clearBody($name = NULL) {
	}
	public function getBody($name = NULL) {
	}
	public function setHeader() {
	}
	public function setAllHeaders() {
	}
	public function getHeader() {
	}
	public function clearHeaders() {
	}
	public function setRedirect($url) {
	}
	public function response() {
	}
}