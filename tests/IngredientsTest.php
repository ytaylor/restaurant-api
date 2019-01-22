<?php
/**
 * Created by PhpStorm.
 * User: floppita
 * Date: 19/01/19
 * Time: 20:19
 */


use App\Controller\IngredientsController;
use App\Entity\Ingredients;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use GuzzleHttp\Middleware;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;


class IngredientsTest extends WebTestCase
{

    public function testEditIngredientsRemoveAllergenAction()
    {
        $client = new GuzzleHttp\Client(['base_uri' => 'http://127.0.0.1:8000']);
        $resourceRemoveIngredient = [
            "name"=> "8732",
            "ingredientsAllergens"=> [["name"=>"Gluten"], ["name"=>"Crustaceo"]]
        ];
        $encode= json_encode($resourceRemoveIngredient);
        $response = $client->request('PUT', '/api/ingredient/1152/removeallergen/', [
            'body' => $encode
        ]);
        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testEditIngredientsAction()
    {
        $client = new GuzzleHttp\Client(['base_uri' => 'http://127.0.0.1:8000']);
        $resourceEditIngredient = [
            "name"=> "Pollo Asado",
            "ingredientsAllergens"=> [["name"=>"Gluten"], ["name"=>"Crustaceo"]]
        ];
        $encode= json_encode($resourceEditIngredient);
        $response = $client->request('PUT', '/api/ingredients/Ingredient1/', [
            'body' => $encode]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testAddIngredientAction()
    {
        $client = new GuzzleHttp\Client(['base_uri' => 'http://127.0.0.1:8000']);
        $resourceNewIngredient = [
            "name"=> "8732",
            "ingredientsAllergens"=> [["name"=>"Gluten"], ["name"=>"Crustaceo"]]
        ];
        $encode= json_encode($resourceNewIngredient);
        $response = $client->request('POST', '/api/ingredients/add/', [
            'body' => $encode
        ]);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testEditIngredientsAddAllegernAction()
    {
        $client = new GuzzleHttp\Client(['base_uri' => 'http://127.0.0.1:8000']);

        $resourceNewIngredient =  [
                "name"=> "Huevo"
        ];

        $encode= json_encode($resourceNewIngredient);
        $response = $client->request('PUT', '/api/ingredients/1152/addallergen/', [
            'body' => $encode
        ]);
        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testDeleteIngredientsAction()
    {
        $client = new GuzzleHttp\Client(['base_uri' => 'http://127.0.0.1:8000']);

        $response = $client->request('DELETE', '/api/ingredient/369/');
        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testGetIngredientsAction()
    {
        // create our http client (Guzzle)
        $client = new GuzzleHttp\Client(['base_uri'=>'http://127.0.0.1:8000']);
        $response = $client->request('GET','/api/ingredients', ['allow_redirects' => false]);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetIngredientsById()
    {
        $id = '371';
        $client = new GuzzleHttp\Client(['base_uri'=>'http://127.0.0.1:8000']);
        $response = $client->request('GET','/api/ingredients/id/'.$id);
        $this->assertEquals(200, $response->getStatusCode());
    }


}
