<?php
/**
 * Created by PhpStorm.
 * User: floppita
 * Date: 19/01/19
 * Time: 20:58
 */

use App\Controller\AllergensController;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use GuzzleHttp\Middleware;

class AllergensTest extends WebTestCase
{

    public function testGetDishesAllergensByName()
    {
        $name = 'Allergen1';
        $client = new GuzzleHttp\Client(['base_uri'=>'http://127.0.0.1:8000']);
        $response = $client->request('GET','/api/allergens/'.$name.'/dishes/');
        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testGetIngredientsAction()
    {
        // create our http client (Guzzle)
        $client = new GuzzleHttp\Client(['base_uri'=>'http://127.0.0.1:8000']);

        $response = $client->request('GET','/api/allergens', ['allow_redirects' => false]);

        $this->assertEquals(200, $response->getStatusCode());

    }
}
