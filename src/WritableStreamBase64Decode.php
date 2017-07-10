<?php declare(strict_types=1);

namespace WyriHaximus\React\Stream\Base64;

use Evenement\EventEmitterTrait;
use React\Stream\Util;
use React\Stream\WritableStreamInterface;

final class WritableStreamBase64Decode implements WritableStreamInterface
{
    use EventEmitterTrait;

    /**
     * @var WritableStreamInterface
     */
    private $stream;

    /**
     * @var string
     */
    private $buffer = '';

    /**
     * WritableStreamHash constructor.
     * @param WritableStreamInterface $stream
     */
    public function __construct(WritableStreamInterface $stream)
    {
        $this->stream = $stream;
        Util::forwardEvents($stream, $this, ['error', 'drain', 'end', 'close']);
    }

    public function isWritable()
    {
        return $this->stream->isWritable();
    }

    public function write($data)
    {
        $this->buffer .= $data;

        return $this->stream->write($this->processBuffer());
    }

    public function end($data = null)
    {
        $this->buffer .= $data;
        $this->stream->end(
            $this->processBuffer() . base64_decode($this->buffer, true)
        );
        $this->buffer = '';
    }

    public function close()
    {
        $this->stream->close();
    }

    private function processBuffer(): string
    {
        $length = strlen($this->buffer);
        $buffer = base64_decode(substr($this->buffer, 0, $length - $length % 4), true);
        $this->buffer = substr($this->buffer, $length - $length % 4);

        return $buffer;
    }
}
