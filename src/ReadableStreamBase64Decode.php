<?php declare(strict_types=1);

namespace WyriHaximus\React\Stream\Base64;

use Evenement\EventEmitter;
use React\Stream\ReadableStreamInterface;
use React\Stream\Util;
use React\Stream\WritableStreamInterface;

final class ReadableStreamBase64Decode extends EventEmitter implements ReadableStreamInterface
{
    /**
     * @var WritableStreamInterface
     */
    private $stream;

    /**
     * @var string
     */
    private $buffer = '';

    /**
     * @param ReadableStreamInterface $stream
     */
    public function __construct(ReadableStreamInterface $stream)
    {
        $this->stream = $stream;
        $this->stream->on('data', function ($data): void {
            $this->buffer .= $data;
            $this->emit('data', [$this->processBuffer()]);
        });
        $this->stream->once('close', function (): void {
            $this->emit('data', [\base64_decode($this->buffer, true)]);
            $this->emit('close');
        });
        Util::forwardEvents($stream, $this, ['error', 'end']);
    }

    public function isReadable()
    {
        return $this->stream->isReadable();
    }

    public function pause()
    {
        return $this->stream->pause();
    }

    public function resume()
    {
        return $this->stream->resume();
    }

    public function pipe(WritableStreamInterface $dest, array $options = [])
    {
        return $this->stream->pipe($dest, $options);
    }

    public function close(): void
    {
        $this->stream->close();
    }

    private function processBuffer(): string
    {
        $length = \strlen($this->buffer);
        $buffer = \base64_decode(\substr($this->buffer, 0, $length - $length % 4), true);
        $this->buffer = \substr($this->buffer, $length - $length % 4);

        return $buffer;
    }
}
