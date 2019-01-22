<?php 

namespace Phalcon\Mvc {

	class Application extends \Phalcon\Application implements \Phalcon\Di\InjectionAwareInterface, \Phalcon\Events\EventsAwareInterface {

		protected $_implicitView;

		protected $_sendHeaders;

		protected $_sendCookies;

		public function sendHeadersOnHandleRequest($sendHeaders){ }


		public function sendCookiesOnHandleRequest($sendCookies){ }


		public function useImplicitView($implicitView){ }


		public function handle($uri=null){ }

	}
}
