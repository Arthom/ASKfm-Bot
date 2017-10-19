<?php
	namespace Arthom\ASKfm;

	use anlutro\cURL\cURL;

	class Bot {
		protected $cURL;
		protected $tokenUrl = 'http://ask.fm/matejgleza';
		protected $loginUrl = 'http://ask.fm/login';

		public function __construct(cURL $curl = null, $tokenUrl = '', $loginUrl = '') {
			$this->cURL = $curl ?: new cURL();
			if (trim($tokenUrl) !== '') {
				$this->tokenUrl = $tokenUrl;
			}
			if (trim($loginUrl) !== '') {
				$this->loginUrl = $loginUrl;
			}
		}

		public function getCSRFToken() {
			$request = $this->cURL->newRequest('get', $this->tokenUrl)
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
			$request = $this->cURL->newRequest('post', $this->loginUrl, $postData)
				->setOption(CURLOPT_COOKIEJAR, 'cookie.txt')
				->setOption(CURLOPT_COOKIEFILE, 'cookie.txt');
			$response = $request->send();

			return !empty($response->info['redirect_url']);
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

			return $response->info['redirect_url'] === 'http://ask.fm/'.$user;
		}
	}
