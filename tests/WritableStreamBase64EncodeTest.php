<?php declare(strict_types=1);

namespace WyriHaximus\React\Tests\Stream\Base64;

use function Clue\React\Block\await;
use PHPUnit\Framework\TestCase;
use React\EventLoop\Factory;
use function React\Promise\Stream\buffer;
use React\Stream\ThroughStream;
use WyriHaximus\React\Stream\Base64\WritableStreamBase64Encode;

/**
 * @internal
 */
final class WritableStreamBase64EncodeTest extends TestCase
{
    /**
     * @dataProvider WyriHaximus\React\Tests\Stream\Base64\DataProvider::provideData
     */
    public function testHash(string $data): void
    {
        $loop = Factory::create();
        $throughStream = new ThroughStream();
        $stream = new WritableStreamBase64Encode($throughStream);
        $loop->addTimer(0.001, function () use ($stream, $data): void {
            $chunks = \str_split($data);
            $last = \count($chunks) - 1;
            for ($i = 0; $i < $last; $i++) {
                $stream->write($chunks[$i]);
            }
            $stream->end($chunks[$last]);
        });
        self::assertSame(\base64_encode($data), await(buffer($throughStream), $loop));
    }
}
