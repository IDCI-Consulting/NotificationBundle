<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: GPL
 */

namespace IDCI\Bundle\NotificationBundle\Tests\Controller;

use IDCI\Bundle\NotificationBundle\Controller\MirrorLinkController;

class MirrorLinkControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testPurgeMirrorLink()
    {
        $content = 'Nothing to change';
        $this->assertEquals(
            $content,
            MirrorLinkController::purgeMirrorLink($content)
        );

        $content = '<html><body><p>Nothing to change</p></body></html>';
        $this->assertEquals(
            $content,
            MirrorLinkController::purgeMirrorLink($content)
        );

        $content = '<html><body><p>Nothing to change</p><a href="https://www.4chan.org">4 Chan</a></body></html>';
        $this->assertEquals(
            $content,
            MirrorLinkController::purgeMirrorLink($content)
        );

        $content = '<html><body><p>Someting to change</p><a href="[[mirrorlink]]">mirror</a></body></html>';
        $this->assertEquals(
            '<html><body><p>Someting to change</p></body></html>',
            MirrorLinkController::purgeMirrorLink($content)
        );

        $content = '<html><body><p>Someting to change</p><a class="dummy" href="[[mirrorlink]]" id="mirror">The mirror <strong>link</strong></a></body></html>';
        $this->assertEquals(
            '<html><body><p>Someting to change</p></body></html>',
            MirrorLinkController::purgeMirrorLink($content)
        );
    }
}
