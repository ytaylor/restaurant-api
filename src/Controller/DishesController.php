<?php

namespace App\Controller;

use App\Entity\Allergens;
use App\Entity\Dishes;
use App\Entity\Ingredients;
use App\Form\DishesType;
use App\Repository\DishesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Security\Core\Exception\DisabledException;


class DishesController extends FOSRestController
{

    /**
     * @Rest\Get("/dishes.{_format}", name="dishes_list_all", defaults={"_format":"json"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Gets all dishes."
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description="An error has occurred trying to get all dishes."
     * )
     *
     * @SWG\Tag(name="Dishes")
     */
    public function getDishesAction(Request $request) {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $dishes = [];
        $message = "";

        try {
            $code = 200;

            $dishes = $em->getRepository("App:Dishes")->findAll();

            if (is_null($dishes)) {
                $dishes = [];
            }

        } catch (Exception $ex) {
            $code = 500;
            $message = "An error has occurred trying to get all Dishes - Error: {$ex->getMessage()}";
        }

        $response = [
            'data' => $code == 200 ? $dishes : $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }

    /**
     * @Rest\Get("/dishes/id/{id}.{_format}", name="dishes_by_id", defaults={"_format":"json"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Gets dishes info based on passed ID parameter."
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="The dishes with the passed ID parameter was not found or doesn't exist."
     * )
     *
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="string",
     *     description="The Dishes ID"
     * )
     *
     *
     * @SWG\Tag(name="Dishes")
     */
    public function getDishesById(Request $request, $id) {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $dishes = [];
        $message = "";

        try {
            $code = 200;

            $dishes_id = $id;
            $dishes = $em->getRepository("App:Dishes")->find($dishes_id);

            if (is_null($dishes)) {
                $code = 500;
                $message = "The dishes does not exist";
            }

        } catch (Exception $ex) {
            $code = 500;
            $message = "An error has occurred trying to get the current Dishes - Error: {$ex->getMessage()}";
        }

        $response = [
            'data' => $code == 200 ? $dishes : $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }

    /**
     * @Rest\Post("/dishes/add/.{_format}", name="dishes_add", defaults={"_format":"json"})
     *
     * @SWG\Response(
     *     response=201,
     *     description="Dishes was added successfully"
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description="An error was occurred trying to add new dishes"
     * )
     *
     * @SWG\Parameter(
     *     name="name",
     *     in="body",
     *     type="string",
     *     description="The dishes name",
     *     schema={}
     * )
     *
     * @SWG\Tag(name="Dishes")
     */
    public function addDishesAction(Request $request) {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $dishes = new Dishes();
        $message = "";
        $dataDishes = json_decode($request->getContent(), true);

        try {
            $code = 201;
            foreach ($dataDishes as $dataproperty => $valueDishes) {
                if (property_exists(Dishes::class,$dataproperty )  && method_exists(Dishes::class, $setmetodo = 'set'. ucfirst($dataproperty))                       ) {
                    $dishes->$setmetodo($valueDishes);
                }

                if($dataproperty === "dishesIngredients") {
                    foreach ($valueDishes as $ingredientProperty) {
                        $ingredient= $this->existOrAddIngredient($ingredientProperty);
                        $dishes->addIngredientsToDishes($ingredient);
                    }
                }
            }
            $em->persist($dishes);
            $em->flush();

        } catch (Exception $ex) {
            $message = "An error has occurred trying to add new dishes - Error: {$ex->getMessage()}";
        }

        $response = [
            'data' => $code == 201 ? $dishes : $message,
        ];
        return new Response($serializer->serialize($response, "json"));
    }

    /**
     * @Rest\Put("/dishes/{id}.{_format}", name="dishes_edit", defaults={"_format":"json"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="The dishes was edited successfully."
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description="An error has occurred trying to edit the dishes."
     * )
     *
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="string",
     *     description="The Dishes ID"
     * )
     *
     * @SWG\Parameter(
     *     name="name",
     *     in="body",
     *     type="string",
     *     description="The Dishes name",
     *     schema={}
     * )
     *
     *
     * @SWG\Tag(name="Dishes")
     */
    public function editDishesAction(Request $request, $id) {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $message = "";
        $dataDishes = json_decode($request->getContent(), true);

        try {
            $code = 200;
            $dishes = $em->getRepository("App:Dishes")->find($id);
            foreach ($dataDishes as $dataproperty => $valueDishes) {
                if (property_exists(Dishes::class, $dataproperty) && method_exists(Dishes::class, $setmetodo = 'set' . ucfirst($dataproperty))) {
                    $dishes->$setmetodo($valueDishes);
                }

            }
            $em->persist($dishes);
            $em->flush();


        } catch (Exception $ex) {
            $message = "An error has occurred trying to edit the current dishes - Error: {$ex->getMessage()}";
        }

        $response = [
            'data' => $code == 200 ? $dishes : $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }

    /**
     * @Rest\Put("/dishes/{id}/addingredient/.{_format}", name="dishes_edit_add_ingredient", defaults={"_format":"json"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="The dishes was edited successfully."
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description="An error has occurred trying to edit the dishes."
     * )
     *
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="string",
     *     description="The Dishes ID"
     * )
     *
     * @SWG\Parameter(
     *     name="name",
     *     in="body",
     *     type="string",
     *     description="The dishes name",
     *     schema={}
     * )
     *
     * @SWG\Tag(name="Dishes")
     */
    public function editDishesAddIngredientsAction(Request $request, $id)
    {
        $dataIngredient = json_decode($request->getContent(), true);
        $serializer = $this->get('jms_serializer');
        try {
            $code = 200;
            $em = $this->getDoctrine()->getManager();
            $dishes = $em->getRepository("App:Dishes")->find($id);
            if (!is_null($dishes)) {
                $ingredient = $this->existOrAddIngredient($dataIngredient);
                $dishes->addIngredientsToDishes($ingredient);
                $em->persist($dishes);
                $em->flush();
            }
        }  catch (Exception $ex) {
            $message = "An error has occurred trying to edit the current dishes - Error: {$ex->getMessage()}";
        }
        $response = [
            'data' => $code == 200 ? $dishes : $message,
        ];
        return new Response($serializer->serialize($response, "json"));
    }

    /**
     * @Rest\Put("/dishes/{id}/removeingredient/.{_format}", name="dishes_edit_remove_ingredient", defaults={"_format":"json"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="The dishes was edited successfully."
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description="An error has occurred trying to edit the dishes."
     * )
     *
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="string",
     *     description="The Dishes ID"
     * )
     *
     * @SWG\Parameter(
     *     name="name",
     *     in="body",
     *     type="string",
     *     description="The dishes name",
     *     schema={}
     * )
     *
     * @SWG\Tag(name="Dishes")
     */
    public function editDishesRemoveIngredientsAction(Request $request, $id)
    {
        $dataIngredient = json_decode($request->getContent(), true);
        $serializer = $this->get('jms_serializer');
        try {
            $code = 200;
            $em = $this->getDoctrine()->getManager();
            $dishes = $em->getRepository("App:Dishes")->find($id);
            if (!is_null($dishes)) {
                $ingredient = $em->getRepository("App:Ingredients")->findOneBy(['name' => $dataIngredient['name']]);
                if(!is_null($ingredient)) {
                    $dishes->removeIngredientsToDishes($ingredient);
                    $em->persist($dishes);
                    $em->flush();
                }
            }
        }  catch (Exception $ex) {
            $message = "An error has occurred trying to edit the current dishes - Error: {$ex->getMessage()}";
        }

        $response = [
            'data' => $code == 200 ? $dishes : $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }

    /**
     * @Rest\Delete("/dishes/{id}.{_format}", name="dishes_remove", defaults={"_format":"json"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Dishes was successfully removed"
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="An error was occurred trying to remove the dishes"
     * )
     *
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="string",
     *     description="The Dishes ID"
     * )
     *
     * @SWG\Tag(name="Dishes")
     */
    public function deleteDishesAction(Request $request, $id) {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();

        try {
            $code = 200;
            $dishes = $em->getRepository("App:Dishes")->find($id);

            if (!is_null($dishes)) {
                $em->remove($dishes);
                $em->flush();
                $message = "The dishes was removed successfully!";

            } else {
                $message = "An error has occurred trying to remove the currrent dishes - Error: The dishes id does not exist";
            }

        } catch (Exception $ex) {
            $message = "An error has occurred trying to remove the current dishes - Error: {$ex->getMessage()}";
        }

        $response = [
            'data' => $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }

    /**
     * @Rest\Get("/dishes/{id}/allergens.{_format}", name="allergens_dishes_by_id", defaults={"_format":"json"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Gets allergens info based on passed ID parameter of dishes."
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="The dishes with the passed ID parameter was not found or doesn't exist."
     * )
     *
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="string",
     *     description="The Dishes ID"
     * )
     *
     * @SWG\Tag(name="Dishes")
     */
    public function getAllergensDishesById(Request $request, $id) {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $allergens = [];
        $message = "";

        try {
            $code = 200;
            $dishes_id = $id;
            $dishes = $em->getRepository("App:Dishes")->find($dishes_id);

            if (is_null($dishes)) {
                $code = 500;
                $message = "The dishes does not exist";
            }
            else{
                foreach ($dishes->getIngredientsToDishes() as $ingredient){
                    foreach ($ingredient->getIngredientsAllergens() as $allergen)
                        $allergens[]=$allergen;
                }
            }
        } catch (Exception $ex) {
            $code = 500;
            $message = "An error has occurred trying to get the current allergens pf dish - Error: {$ex->getMessage()}";
        }

        $response = [
            'data' => $code == 200 ? $allergens : $message,
        ];
        return new Response($serializer->serialize($response, "json"));
    }


    private function existOrAddIngredient($ingredientExist)
    {
        $em = $this->getDoctrine()->getManager();
        $ingredient = $em->getRepository("App:Ingredients")->findOneBy(['name' => $ingredientExist['name']]);
        if (is_null($ingredient)) {
            $ingredient = new Ingredients();
            $ingredient->setName($ingredientExist['name']);
            if (!empty($ingredientExist['ingredientsAllergens'])) {
                foreach ($ingredientExist['ingredientsAllergens'] as $allergen) {
                    $ingredient->addAllergenToIngredient($this->existOrAddAllergen($allergen['name']));

                }
            }
            $em->persist($ingredient);
            $em->flush();
        }
        return $ingredient;
    }

    private function existOrAddAllergen($allergen_name)
    {
        $em = $this->getDoctrine()->getManager();
        $allergen = $em->getRepository("App:Allergens")->findOneBy(['name' => $allergen_name]);
        if (is_null($allergen)) {
            $allergen = new Allergens();
            $allergen->setName($allergen_name);
            $em->persist($allergen);
            $em->flush();
        }
        return $allergen;
    }
}