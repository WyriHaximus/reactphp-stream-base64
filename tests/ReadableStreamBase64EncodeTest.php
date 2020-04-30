<?php declare(strict_types=1);

namespace WyriHaximus\React\Tests\Stream\Base64;

use function Clue\React\Block\await;
use PHPUnit\Framework\TestCase;
use React\EventLoop\Factory;
use function React\Promise\Stream\buffer;
use React\Stream\ThroughStream;
use WyriHaximus\React\Stream\Base64\ReadableStreamBase64Encode;

/**
 * @internal
 */
final class ReadableStreamBase64EncodeTest extends TestCase
{
    /**
     * @dataProvider WyriHaximus\React\Tests\Stream\Base64\DataProvider::provideData
     */
    public function testHash(string $data): void
    {
        $loop = Factory::create();
        $pipeThroughStream = new ThroughStream();
        $throughStream = new ThroughStream();
        $stream = new ReadableStreamBase64Encode($throughStream);
        $stream->pipe($pipeThroughStream);
        $loop->addTimer(0.001, function () use ($throughStream, $data): void {
            $chunks = \str_split($data);
            $last = \count($chunks) - 1;
            for ($i = 0; $i < $last; $i++) {
                $throughStream->write($chunks[$i]);
            }
            $throughStream->end($chunks[$last]);
        });
        self::assertSame(\base64_encode($data), await(buffer($pipeThroughStream), $loop));
    }
}
