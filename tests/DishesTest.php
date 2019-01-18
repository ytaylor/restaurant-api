<?php
/**
 * Created by PhpStorm.
 * User: floppita
 * Date: 17/01/19
 * Time: 12:12
 */

use App\Entity\Dishes;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class DishesTest extends WebTestCase
{

    public function testGet()
    {
        // create our http client (Guzzle)
        $client = new GuzzleHttp\Client(['base_uri'=>'http://127.0.0.1:8000']);

        $response = $client->request('GET','/api/dishes', ['allow_redirects' => false]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetId()
    {
        // create our http client (Guzzle)
        $id = '5';
        $client = new GuzzleHttp\Client(['base_uri'=>'http://127.0.0.1:8000']);
        $response = $client->request('GET','/api/dishes/id/'.$id);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testAddDishes()
    {
        try {
            $client = new GuzzleHttp\Client(['base_uri' => 'http://127.0.0.1:8000']);
            $resourceNewDishes = array('name' => 'name', 'description' => 'description', 'calories' => 'calories', 'price' => 'price', 'preparationTime' => 'preparationTime');

            $response = $client->post('/api/dishes/add/', ['json' => $resourceNewDishes]);

            $this->assertEquals(200, $response->getStatusCode());

         } catch (\GuzzleHttp\Exception\ClientException $e) {


           $this->expectException( $e->getResponse()->getBody()->getContents());
        }

    }
}
