<?php declare(strict_types=1);

namespace WyriHaximus\React\Stream\Base64;

use Evenement\EventEmitterTrait;
use React\Stream\WritableStreamInterface;

final class WritableStreamBase64Encode implements WritableStreamInterface
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
            $this->processBuffer() . base64_encode($this->buffer)
        );
        $this->buffer = '';
    }

    public function close()
    {
        $this->stream->close();
    }

    private function processBuffer(): string
    {
        $buffer = $this->buffer;
        $this->buffer = '';
        while (strlen($buffer) % 3 !== 0 && strlen($buffer) > 0) {
            $this->buffer = substr($buffer, -1) . $this->buffer;
            $buffer = substr($buffer, 0, -1);
        }
        $buffer = base64_encode($buffer);

        return $buffer;
    }
}
