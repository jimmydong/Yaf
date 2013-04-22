<?php
namespage \Yaf\Config;
final class Ini extends Yaf\Config_Abstract implements Iterator, Traversable, ArrayAccess, Countable { 
	/* constants */

	/* properties */
	protected $_config = NULL;
	protected $_readonly = "1";

	/* methods */
	public function __construct($config_file, $section = NULL) {
	}
	public function __isset($name) {
	}
	public function get($name = NULL) {
	}
	public function set($name, $value) {
	}
	public function count() {
	}
	public function rewind() {
	}
	public function current() {
	}
	public function next() {
	}
	public function valid() {
	}
	public function key() {
	}
	public function toArray() {
	}
	public function readonly() {
	}
	public function offsetUnset($name) {
	}
	public function offsetGet($name) {
	}
	public function offsetExists($name) {
	}
	public function offsetSet($name, $value) {
	}
	public function __get($name = NULL) {
	}
	public function __set($name, $value) {
	}
}

final class Simple extends Yaf\Config_Abstract implements Iterator, Traversable, ArrayAccess, Countable { 
	/* constants */

	/* properties */
	protected $_config = NULL;
	protected $_readonly = "";

	/* methods */
	public function __construct($config_file, $section = NULL) {
	}
	public function __isset($name) {
	}
	public function get($name = NULL) {
	}
	public function set($name, $value) {
	}
	public function count() {
	}
	public function offsetUnset($name) {
	}
	public function rewind() {
	}
	public function current() {
	}
	public function next() {
	}
	public function valid() {
	}
	public function key() {
	}
	public function readonly() {
	}
	public function toArray() {
	}
	public function __set($name, $value) {
	}
	public function __get($name = NULL) {
	}
	public function offsetGet($name) {
	}
	public function offsetExists($name) {
	}
	public function offsetSet($name, $value) {
	}
}