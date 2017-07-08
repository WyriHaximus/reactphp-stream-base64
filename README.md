# On the fly base64 encoder & decoder for [ReactPHP](https://github.com/reactphp/) streams

[![Linux Build Status](https://travis-ci.org/WyriHaximus/reactphp-stream-base64.png)](https://travis-ci.org/WyriHaximus/reactphp-stream-base64)
[![Latest Stable Version](https://poser.pugx.org/WyriHaximus/react-stream-base64/v/stable.png)](https://packagist.org/packages/WyriHaximus/react-stream-base64)
[![Total Downloads](https://poser.pugx.org/WyriHaximus/react-stream-base64/downloads.png)](https://packagist.org/packages/WyriHaximus/react-stream-base64/stats)
[![Code Coverage](https://scrutinizer-ci.com/g/WyriHaximus/reactphp-stream-base64/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/WyriHaximus/reactphp-stream-base64/?branch=master)
[![License](https://poser.pugx.org/WyriHaximus/react-stream-base64/license.png)](https://packagist.org/packages/wyrihaximus/react-stream-base64)
[![PHP 7 ready](http://php7ready.timesplinter.ch/WyriHaximus/reactphp-stream-base64/badge.svg)](https://travis-ci.org/WyriHaximus/reactphp-stream-base64)

### Installation ###

To install via [Composer](http://getcomposer.org/), use the command below, it will automatically detect the latest version and bind it with `^`.

```
composer require wyrihaximus/react-stream-base64 
```

## Usage ##

All streams in this package are decorators about the stream you want to encode or decode.

Readable stream decode:

```php
$streamToDecode = new ThroughStream();
$stream = new ReadableStreamBase64Decode($streamToDecode);
$stream->on('data', function ($data) {
    echo $data; // foo.bar
});
$streamToDecode->write('Zm9vLmJhcg==');
```

Readable stream encode:

```php
$streamToEncode = new ThroughStream();
$stream = new ReadableStreamBase64Encode($streamToEncode);
$stream->on('data', function ($data) {
    echo $data; // Zm9vLmJhcg==
});
$streamToEncode->write('foo.bar');
```

Writable stream decode:

```php
$streamToDecode = new ThroughStream();
$stream = new WritableStreamBase64Decode($streamToDecode);
$stream->write('Zm9vLmJhcg=='); // Writes foo.bar to $streamToDecode
```

Writable stream encode:

```php
$streamToEncode = new ThroughStream();
$stream = new WritableStreamBase64Encode($streamToEncode);
$stream->write('foo.bar'); // Writes Zm9vLmJhcg== to $streamToEncode
```

## Contributing ##

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License ##

Copyright 2017 [Cees-Jan Kiewiet](http://wyrihaximus.net/)

Permission is hereby granted, free of charge, to any person
obtaining a copy of this software and associated documentation
files (the "Software"), to deal in the Software without
restriction, including without limitation the rights to use,
copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following
conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.
