<?php
	namespace Arthom\ASKfm;

	use anlutro\cURL\cURL;

	class Bot {
		protected $cURL;

		public function __construct() {
			$this->cURL = new cURL();
		}

		public function getCSRFToken() {
			$request = $this->cURL->newRequest('get', 'http://ask.fm/matejgleza')
				->setOption(CURLOPT_COOKIEJAR, 'cookie.txt')
				->setOption(CURLOPT_COOKIEFILE, 'cookie.txt');
			$response = $request->send();

			preg_match_all('/<input type="hidden" name="authenticity_token" value="(.*)" \/>/', $response->body, $token);
			return $token[1][0];
		}

		public function generateLogin($username, $password) {
			$token = $this->getCSRFToken();
			$postData = array(
				'utf8' => "✓",
				'authenticity_token' => $token,
				'login' => $username,
				'password' => $password,
				'remember_me' => 0
			);
			$request = $this->cURL->newRequest('post', 'http://ask.fm/login', $postData)
				->setOption(CURLOPT_COOKIEJAR, 'cookie.txt')
				->setOption(CURLOPT_COOKIEFILE, 'cookie.txt');
			$response = $request->send();

			if (!empty($response->info['redirect_url']))
				return true;

			return false;
		}

		public function ask($user, $message, $anonymous = true) {
			$token = $this->getCSRFToken();
			$postData = array(
				'utf8' => '✓',
				'authenticity_token' => $token,
				'question[question_text]' => $message,
				'question[anonymous]' => 0,
				'question[anonymous]' => ($anonymous ? 'true' : 'false')
			);
			$url = 'http://ask.fm/'.$user.'/ask';
			$request = $this->cURL->newRequest('post', $url, $postData)
				->setOption(CURLOPT_COOKIEJAR, 'cookie.txt')
				->setOption(CURLOPT_COOKIEFILE, 'cookie.txt');
			$response = $request->send();

			if ($response->info['redirect_url'] === 'http://ask.fm/'.$user)
				return true;

			return false;
		}
	}