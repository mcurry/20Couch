About
=====
20Couch - A Google Reader like Twitter Client

[20Couch.com](http://www.20couch.com)

By Matt Curry <matt@pseudocoder.com>

Install
=======
* Download and unpack the latest [CakePHP 1.3.x](http://github.com/cakephp/cakephp/downloads)
* Download and unpack the latest [20Couch](http://github.com/mcurry/20couch/downloads)
* Copy the contents of 20Couch into the CakePHP folder.  This will replace the entire app dir.
* Create a database for 20Couch on your web server, as well as a MySQL user who has all privileges for accessing and modifying it.
* Create an empty file "install" in /app/tmp.  This will allow the installer to run.
* Place the 20Couch and CakePHP files in a desirable location on your web server.  Other apps should be jealous of this location.
* Install by going to the 20Couch installer in your least favorite browser.
	* If you installed 20Couch in the root directory, you should visit: http://20couch.example.com/install
	* If you installed 20Couch in its own subdirectory called "unicorns" (for example) you should visit: http://example.com/unicorns/install
	
Optional
========
* Add a cron entry to run the updated behind the scenes (* * * * * sh /path/to/20couch/20couch-update.sh)
* Set your webroot to /path/to/20couch/app/webroot instead of just /path/to/20couch

Requirements
============

License
=======
Copyright (c) 2010 Matt Curry

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.