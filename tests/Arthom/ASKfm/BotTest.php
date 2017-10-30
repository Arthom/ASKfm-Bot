<?php

namespace Arthom\ASKfm\tests;

class BotTest extends \PHPUnit_Framework_TestCase
{
    public function testGetCSRFToken()
    {
        $tokenUrl = 'token_url_' . mt_rand();
        $token = 'token_' . mt_rand();
        $response = new \stdClass;
        $response->body = '<input type="hidden" name="authenticity_token" value="' . $token . '" />';

        $curl = $this->getMockBuilder('\anlutro\cURL\cURL')->getMock();

        $request = $this->getMockBuilder('\stdClass')
            ->setMethods(['setOption', 'send'])
            ->getMock();
        $request->method('setOption')->will($this->returnValue($request));
        $request->method('send')->will($this->returnValue($response));

        $curl->method('newRequest')
            ->will($this->returnCallback(function ($method, $url) use ($tokenUrl, $request) {
                return mb_strtolower($method) === 'get' && $url === $tokenUrl
                    ? $request
                    : null;
            }));

        $bot = new \Arthom\ASKfm\Bot($curl, $tokenUrl);

        $this->assertSame(
            $token,
            $bot->getCSRFToken()
        );
    }

    public function testGenerateLogin()
    {
        $tokenUrl = 'token_url_' . mt_rand();
        $loginUrl = 'login_url_' . mt_rand();
        $token = 'token_' . mt_rand();
        $login = 'login_' . mt_rand();
        $password = 'password_' . mt_rand();
        $responseToken = new \stdClass;
        $responseToken->body = '<input type="hidden" name="authenticity_token" value="' . $token . '" />';
        $responseLogin = new \stdClass;
        $responseLogin->info = ['redirect_url' => 'redirect_url_' . mt_rand()];

        $curl = $this->getMockBuilder('\anlutro\cURL\cURL')->getMock();

        $requestToken = $this->getMockBuilder('\stdClass')
            ->setMethods(['setOption', 'send'])
            ->getMock();
        $requestToken->method('setOption')->will($this->returnValue($requestToken));
        $requestToken->method('send')->will($this->returnValue($responseToken));

        $requestLogin = $this->getMockBuilder('\stdClass')
            ->setMethods(['setOption', 'send'])
            ->getMock();
        $requestLogin->method('setOption')->will($this->returnValue($requestLogin));
        $requestLogin->method('send')->will($this->returnValue($responseLogin));

        $curl->method('newRequest')
            ->will($this->returnCallback(function ($method, $url, $data) use ($tokenUrl, $loginUrl, $requestToken, $requestLogin, $token, $login, $password) {
                if (mb_strtolower($method) === 'get' && $url === $tokenUrl) {
                    return $requestToken;
                }
                if (
                    mb_strtolower($method) === 'post'
                    && $url === $loginUrl
                    && isset($data['authenticity_token']) && $data['authenticity_token'] === $token
                    && isset($data['login']) && $data['login'] === $login
                    && isset($data['password']) && $data['password'] === $password
                ){
                    return $requestLogin;
                }
                return null;
            }));

        $bot = new \Arthom\ASKfm\Bot($curl, $tokenUrl, $loginUrl);

        $this->assertSame(
            true,
            $bot->generateLogin($login, $password)
        );
    }

    public function testAsk()
    {
        $tokenUrl = 'token_url_' . mt_rand();
        $loginUrl = 'login_url_' . mt_rand();
        $token = 'token_' . mt_rand();
        $user = 'luser_' . mt_rand();
        $message = 'message_' . mt_rand();
        $anonymous = 'true';
        $responseToken = new \stdClass;
        $responseToken->body = '<input type="hidden" name="authenticity_token" value="' . $token . '" />';
        $responseAsk = new \stdClass;
        $responseAsk->info = ['redirect_url' => 'http://ask.fm/' . $user];

        $curl = $this->getMockBuilder('\anlutro\cURL\cURL')->getMock();

        $requestToken = $this->getMockBuilder('\stdClass')
            ->setMethods(['setOption', 'send'])
            ->getMock();
        $requestToken->method('setOption')->will($this->returnValue($requestToken));
        $requestToken->method('send')->will($this->returnValue($responseToken));

        $requestAsk = $this->getMockBuilder('\stdClass')
            ->setMethods(['setOption', 'send'])
            ->getMock();
        $requestAsk->method('setOption')->will($this->returnValue($requestAsk));
        $requestAsk->method('send')->will($this->returnValue($responseAsk));

        $curl->method('newRequest')
            ->will($this->returnCallback(function ($method, $url, $data) use ($tokenUrl, $loginUrl, $requestToken, $requestAsk, $user, $token, $message, $anonymous) {
                if (mb_strtolower($method) === 'get' && $url === $tokenUrl) {
                    return $requestToken;
                }
                if (
                    mb_strtolower($method) === 'post'
                    && mb_strpos($url, $user) !== false
                    && isset($data['authenticity_token']) && $data['authenticity_token'] === $token
                    && isset($data['question[question_text]']) && $data['question[question_text]'] === $message
                    && isset($data['question[anonymous]']) && $data['question[anonymous]'] === $anonymous
                ){
                    return $requestAsk;
                }
                return null;
            }));

        $bot = new \Arthom\ASKfm\Bot($curl, $tokenUrl);

        $this->assertSame(
            true,
            $bot->ask($user, $message, true)
        );
    }
}
