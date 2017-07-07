<?php declare(strict_types=1);

namespace WyriHaximus\React\Tests\Stream\Base64;

use PHPUnit\Framework\TestCase;
use React\EventLoop\Factory;
use React\Stream\ThroughStream;
use WyriHaximus\React\Stream\Base64\WritableStreamBase64Encode;
use function Clue\React\Block\await;
use function React\Promise\Stream\buffer;

final class WritableStreamBase64EncodeTest extends TestCase
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

    /**
     * @dataProvider provideData
     */
    public function testHash(string $data)
    {
        $loop = Factory::create();
        $throughStream = new ThroughStream();
        $stream = new WritableStreamBase64Encode($throughStream);
        $loop->futureTick(function () use ($stream, $data) {
            $chunks = str_split($data);
            $last = count($chunks) - 1;
            for ($i = 0; $i < $last; $i++) {
                $stream->write($chunks[$i]);
            }
            $stream->end($chunks[$last]);
        });
        self::assertSame(base64_encode($data), await(buffer($throughStream), $loop));
    }
}
