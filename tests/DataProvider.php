<?php declare(strict_types=1);

namespace WyriHaximus\React\Tests\Stream\Base64;

final class DataProvider
{
    public function provideData()
    {
        yield ['a'];
        yield ['abc'];
        yield ['abcdefg'];
        yield ['abcdefghij'];
        yield ['abcdefghijklm'];
        yield ['abcdefghijklmnop'];
        yield ['abcdefghijklmnopqrst'];
        yield ['abcdefghijklmnopqrstuvw'];
        yield ['abcdefghijklmnopqrstuvwxyz'];
        foreach (range(128, 1337) as $size) {
            yield [str_pad('a', $size)];
        }
        yield [str_pad('a', 1337)];
        yield [str_pad('a', 100000)];
        yield [str_pad('a', 1000000)];
        yield [str_pad('a', 10000000)];
    }
}
