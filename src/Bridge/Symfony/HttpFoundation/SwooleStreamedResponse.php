<?php

declare(strict_types=1);

namespace K911\Swoole\Bridge\Symfony\HttpFoundation;

use Swoole\Coroutine\Channel;
use Swoole\Http\Server;
use Symfony\Component\HttpFoundation\Response;

class SwooleStreamedResponse extends Response
{
    public function __construct(int $status = 200, array $headers = [])
    {
        parent::__construct(null, $status, $headers);
        $this->channel = new Channel(1);
    }

    public function write($data, $timeout = -1) {
        return $this->channel->push($data, $timeout);
    }

    public function isClosed() : bool {
        return $this->channel->errCode === SWOOLE_CHANNEL_CANCELED ||
            $this->channel->errCode === SWOOLE_CHANNEL_CLOSED;
    }

    public function close() {
        $this->channel->close();
        return $this;
    }

    public function run(Server $server, \Swoole\Http\Response $response) {
        $readTimeoutInS = 10;

        while(true) {
            $data = $this->channel->pop($readTimeoutInS);

            // break loop if client disconnected or our channel was closed or cancelled
            if(!$server->exists($response->fd) || $this->isClosed())
                break;

            // retry if read timeout occured
            if($this->channel->errCode === SWOOLE_CHANNEL_TIMEOUT)
                continue;

            if(!empty($data))
                $response->write($data);
        }

        if(!$this->isClosed())
            $this->channel->close();
    }

    public function isCacheable(): bool {
        return false;
    }

    /**
     * {@inheritdoc}
     *
     * This method only sends the headers once.
     *
     * @return $this
     */
    public function sendHeaders()
    {
        // this has no function in swoole stream context
        return $this;
    }

    /**
     * Sends HTTP headers and content.
     *
     * @return $this
     */
    public function send()
    {
        // this has no function in swoole stream context
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * This method only sends the content once.
     *
     * @return $this
     */
    public function sendContent()
    {
        // this has no function in swoole stream context
        return $this;
    }
}