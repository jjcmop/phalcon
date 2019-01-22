<?php 

namespace Phalcon {

	class Crypt implements \Phalcon\CryptInterface {

		const PADDING_DEFAULT = 0;

		const PADDING_ANSI_X_923 = 1;

		const PADDING_PKCS7 = 2;

		const PADDING_ISO_10126 = 3;

		const PADDING_ISO_IEC_7816_4 = 4;

		const PADDING_ZERO = 5;

		const PADDING_SPACE = 6;

		protected $_key;

		protected $_padding;

		protected $_cipher;

		protected $availableCiphers;

		protected $ivLength;

		protected $hashAlgo;

		protected $useSigning;

		public function __construct($cipher=null, $useSigning=null){ }


		public function setPadding($scheme){ }


		public function setCipher($cipher){ }


		public function getCipher(){ }


		public function setKey($key){ }


		public function getKey(){ }


		public function setHashAlgo($hashAlgo){ }


		public function getHashAlgo(){ }


		public function useSigning($useSigning){ }


		protected function _cryptPadText($text, $mode, $blockSize, $paddingType){ }


		protected function _cryptUnpadText($text, $mode, $blockSize, $paddingType){ }


		public function encrypt($text, $key=null){ }


		public function decrypt($text, $key=null){ }


		public function encryptBase64($text, $key=null, $safe=null){ }


		public function decryptBase64($text, $key=null, $safe=null){ }


		public function getAvailableCiphers(){ }


		public function getAvailableHashAlgos(){ }


		protected function assertCipherIsAvailable($cipher){ }


		protected function assertHashAlgorithmAvailable($hashAlgo){ }


		protected function getIvLength($cipher){ }


		protected function initializeAvailableCiphers(){ }

	}
}
