<?php
/**
 * Copyright 2008-2017 Horde LLC (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (BSD). If you
 * did not receive this file, see http://www.horde.org/licenses/bsd.
 *
 * @author   James Pepin <james@bluestatedigital.com>
 * @category Horde
 * @license  http://www.horde.org/licenses/bsd BSD
 * @package  Controller
 */
namespace Horde\Http\Server;
use Psr\Http\Message\ResponseInterface;

/**
 * Transform a Response object to HTTP output
 *
 * @author    James Pepin <james@bluestatedigital.com>
 * @author    Ralf Lang <lang@b1-systems.de>
 * @category  Horde
 * @copyright 2008-2017 Horde LLC
 * @license   http://www.horde.org/licenses/bsd BSD
 * @package   Controller
 */
class ResponseWriterWeb implements ResponseWriterInterface
{
    public function writeResponse(ResponseInterface $response)
    {
        header(
            sprintf(
                'HTTP/%s %d %s',
                $response->getProtocolVersion(),
                $response->getStatusCode(),
                $response->getReasonPhrase()
            )
        );
        foreach ($response->getHeaders() as $key => $value) {
            header("$key: $value");
        }
        $body = $response->getBody();
        if (is_resource($body)) {
            stream_copy_to_stream($body, fopen('php://output', 'a'));
        } else {
            echo $body;
        }
    }
}
