<?php
/**
 * Created by PhpStorm.
 * User: floppita
 * Date: 17/01/19
 * Time: 12:12
 */

use App\Entity\Dishes;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use GuzzleHttp\Middleware;


class DishesTest extends WebTestCase
{

    public function testGetDishes()
    {
        // create our http client (Guzzle)
        $client = new GuzzleHttp\Client(['base_uri'=>'http://127.0.0.1:8000']);

        $response = $client->request('GET','/api/dishes', ['allow_redirects' => false]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetDishesById()
    {
        // create our http client (Guzzle)
        $id = '5';
        $client = new GuzzleHttp\Client(['base_uri'=>'http://127.0.0.1:8000']);
        $response = $client->request('GET','/api/dishes/id/'.$id);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testAddDishes()
    {
        $client = new GuzzleHttp\Client(['base_uri' => 'http://127.0.0.1:8000']);
        $resourceNewDishes = [
            'name' => 'name4444',
            'description' => 'description2',
            'calories' => 'calories3',
            'price' =>  2.5,
            "dishesIngredients" => [
                    ["name"=> "Ingrediente1",
                        "ingredientsAllergens"=> [["name"=>"Allegenoss"], ["name"=>"Allegenossii"]]],
                    ["name"=> "Ingrediente2"]]
        ];

        $encode= json_encode($resourceNewDishes);
        $response = $client->request('POST', '/api/dishes/add/', [
            'body' => $encode
        ]);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testEditDishes(){
        $client = new GuzzleHttp\Client(['base_uri' => 'http://127.0.0.1:8000']);
        $resourceNewDishes = [
            'name' => 'name2',
            'description' => 'description2',
            'calories' => 'calories3',
            'price' =>  2.5,
        ];
        $encode= json_encode($resourceNewDishes);
        $response = $client->request('PUT', '/api/dishes/1152/', [
            'body' => $encode]);

        $this->assertEquals(200, $response->getStatusCode());

}

    public function testEditDishesAddIngredients()
    {
        $client = new GuzzleHttp\Client(['base_uri' => 'http://127.0.0.1:8000']);

        $resourceNewIngredient =  [
        "name"=> "Pollo asado 2",
		"ingredientsAllergens"=> [[
            "name"=> "gluten"
		], [
            "name"=> "picazon "
		]]
];

        $encode= json_encode($resourceNewIngredient);
        $response = $client->request('PUT', '/api/dishes/1152/addingredient/', [
            'body' => $encode
        ]);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testEditDishesRemoveIngredients()
    {
        $client = new GuzzleHttp\Client(['base_uri' => 'http://127.0.0.1:8000']);

        $resourceRemoveIngredient =  [
            "name"=> "Pollo asado ",
            "ingredientsAllergens"=> [[
                "name"=> "gluten"
            ], [
                "name"=> "picazon "
            ]]
        ];
        $encode= json_encode($resourceRemoveIngredient);
        $response = $client->request('PUT', '/api/dishes/1152/removeingredient/', [
            'body' => $encode
        ]);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteDishes()
    {
        $client = new GuzzleHttp\Client(['base_uri' => 'http://127.0.0.1:8000']);

        $response = $client->request('DELETE', '/api/dishes/1151/');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetAllergensDishesById()
    {
        $id = '1152';
        $client = new GuzzleHttp\Client(['base_uri'=>'http://127.0.0.1:8000']);
        $response = $client->request('GET','/api/dishes/id/'.$id.'allergens/');
        $this->assertEquals(200, $response->getStatusCode());
    }
}