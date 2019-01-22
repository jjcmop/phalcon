<?php 

namespace Phalcon\Http\Response {

	class Cookies implements \Phalcon\Http\Response\CookiesInterface, \Phalcon\Di\InjectionAwareInterface {

		protected $_dependencyInjector;

		protected $_registered;

		protected $_useEncryption;

		protected $_cookies;

		protected $signKey;

		public function __construct($useEncryption=null, $signKey=null){ }


		public function setSignKey($signKey=null){ }


		public function setDI(\Phalcon\DiInterface $dependencyInjector){ }


		public function getDI(){ }


		public function useEncryption($useEncryption){ }


		public function isUsingEncryption(){ }


		public function set($name, $value=null, $expire=null, $path=null, $secure=null, $domain=null, $httpOnly=null){ }


		public function get($name){ }


		public function has($name){ }


		public function delete($name){ }


		public function send(){ }


		public function reset(){ }

	}
}
