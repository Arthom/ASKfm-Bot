ASKfm Bot
===================

Simple ASKfm bot in PHP.

----------


Requirements
-------------
- \>= PHP 5.4.0
- cURL
- Composer


Installation:
-------------
Download this git repository and install required composer packages.

    composer install

Example:
-------------
**Create new ASKfm Bot instance:**

	$bot = new Arthom\ASKfm\Bot();

**Login**

	$bot->generateLogin('username', 'password');

**Asking a new question**
    
	$bot->ask('user', 'message', true/false);

`Third parameter is anonymous message.`


All functions will return `true`, or `false` if
your response was unsuccessful.

License:
-------------
This bot is under MIT license.

	Copyright (c) 2016 Matej Gleza / Arthom

	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in all
	copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
	SOFTWARE.
