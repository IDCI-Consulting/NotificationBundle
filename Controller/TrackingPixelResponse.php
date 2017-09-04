<?php

namespace IDCI\Bundle\NotificationBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class TrackingPixelResponse extends Response
{
    /**
     * @var string
     */
    const IMAGE_CONTENT = 'R0lGODlhAQABAJAAAP8AAAAAACH5BAUQAAAALAAAAAABAAEAAAICBAEAOw==';

    /**
     * @var string
     */
    const CONTENT_TYPE = 'image/gif';

    public function __construct()
    {
        $content = base64_decode(self::IMAGE_CONTENT);
        parent::__construct($content);
        $this->headers->set('Content-Type', self::CONTENT_TYPE);
        $this->setPrivate();
        $this->headers->addCacheControlDirective('no-cache', true);
        $this->headers->addCacheControlDirective('must-revalidate', true);
    }
}
