<?php declare(strict_types=1);

namespace WyriHaximus\React\Tests\Stream\Base64;

use function Clue\React\Block\await;
use PHPUnit\Framework\TestCase;
use React\EventLoop\Factory;
use function React\Promise\Stream\buffer;
use React\Stream\ThroughStream;
use WyriHaximus\React\Stream\Base64\WritableStreamBase64Decode;

/**
 * @internal
 */
final class WritableStreamBase64DecodeTest extends TestCase
{
    /**
     * @dataProvider WyriHaximus\React\Tests\Stream\Base64\DataProvider::provideData
     */
    public function testHash(string $data): void
    {
        $loop = Factory::create();
        $throughStream = new ThroughStream();
        $stream = new WritableStreamBase64Decode($throughStream);
        $loop->addTimer(0.001, function () use ($stream, $data): void {
            $data = \base64_encode($data);
            $chunks = \str_split($data);
            $last = \count($chunks) - 1;
            for ($i = 0; $i < $last; $i++) {
                $stream->write($chunks[$i]);
            }
            $stream->end($chunks[$last]);
        });
        self::assertSame($data, await(buffer($throughStream), $loop));
    }
}
