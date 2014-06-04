<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiNotifierConfigurationControllerTest extends WebTestCase
{
    public function testGetNotifierconfigurationsActionWithInvalidParameters()
    {
        $client = static::createClient();
        $crawler = $client->request(
            'GET',
            '/api/notifierconfigurations?limit=invalidLimit&offset=invalideOffset'
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testGetNotifierconfigurationActionWithInvalidId()
    {
        $client = static::createClient();
        $crawler = $client->request(
            'GET',
            '/api/notifierconfigurations/invalidId'
        );

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}