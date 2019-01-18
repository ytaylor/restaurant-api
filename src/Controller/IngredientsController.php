<?php

namespace App\Controller;

use App\Entity\Allergens;
use App\Entity\Ingredients;
use App\Repository\IngredientsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\FOSRestController;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Controller\Annotations as Rest;;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Security\Core\Exception\DisabledException;


class IngredientsController extends FOSRestController
{


    /**
     * @Rest\Get("/ingredients.{_format}", name="ingredients_list_all", defaults={"_format":"json"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Gets all ingredients."
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description="An error has occurred trying to get all ingredients."
     * )
     *
     * @SWG\Tag(name="Ingredients")
     */
    public function getIngredientsAction(Request $request) {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $ingredients = [];
        $message = "";

        try {
            $code = 200;
            $ingredients = $em->getRepository("App:Ingredients")->findAll();

            if (is_null($ingredients)) {
                $ingredients = [];
            }

        } catch (Exception $ex) {
            $code = 500;
            $message = "An error has occurred trying to get all Ingredients - Error: {$ex->getMessage()}";
        }

        $response = [
            'data' => $code == 200 ? $ingredients : $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }

    /**
     * @Rest\Get("/ingredients/id/{id}.{_format}", name="ingredients_by_id", defaults={"_format":"json"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Gets ingredients info based on passed ID parameter."
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="The ingredients with the passed ID parameter was not found or doesn't exist."
     * )
     *
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="string",
     *     description="The ingredients ID"
     * )
     *
     *
     * @SWG\Tag(name="Ingredients")
     */
    public function getIngredientsById(Request $request, $id) {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $ingredients = [];
        $message = "";

        try {
            $code = 200;

            $ingredient_id = $id;
            $ingredients = $em->getRepository("App:Ingredients")->find($ingredient_id);

            if (is_null( $ingredients)) {
                $code = 500;
                $message = "The dishes does not exist";
            }

        } catch (Exception $ex) {
            $code = 500;
            $message = "An error has occurred trying to get the current Ingredients - Error: {$ex->getMessage()}";
        }

        $response = [
            'data' => $code == 200 ?  $ingredients : $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }


    /**
     * @Rest\Post("/ingredients/add/.{_format}", name="ingredients_add", defaults={"_format":"json"})
     *
     * @SWG\Response(
     *     response=201,
     *     description="Imgredients was added successfully"
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description="An error was occurred trying to add new ingredients"
     * )
     *
     * @SWG\Tag(name="Ingredients")
     */
    public function addIngredientsAction(Request $request) {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $ingredient = new Ingredients();
        $message = "";
        $dataIngredients = json_decode($request->getContent(), true);

        try {
            $code = 201;
            foreach ($dataIngredients as $dataproperty => $valueIngredients) {
                if (property_exists(Ingredients::class, $dataproperty) && method_exists(Ingredients::class, $setmetodo = 'set' . ucfirst($dataproperty))) {
                    $ingredient->$setmetodo($valueIngredients);
                }
                if($dataproperty === "ingredientsAllergens") {

                    $allergen= $this->addAllergen($valueIngredients);
                    $ingredient->addAllergenToIngredient($allergen);
                }

            }
            $em->persist($ingredient);
            $em->flush();

        } catch (Exception $ex) {
            $message = "An error has occurred trying to add new ingredients - Error: {$ex->getMessage()}";
        }

        $response = [
            'data' => $code == 201 ? $ingredient : $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }

    /**
     * @Rest\Put("/ingredients/{name_ingredient}.{_format}", name="ingredients_edit", defaults={"_format":"json"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="The ingredients was edited successfully."
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description="An error has occurred trying to edit the ingredients."
     * )
     * @SWG\Parameter(
     *     name="name_ingredient",
     *     in="body",
     *     type="string",
     *     description="The Dishes name",
     *     schema={}
     * )
     *
     * @SWG\Tag(name="Ingredients")
     */
    public function editIngredientsAction(Request $request, $name_ingredient) {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $message = "";
        $dataIngredients = json_decode($request->getContent(), true);

        try {
            $code = 200;
            $ingredients = $em->getRepository("App:Ingredients")->findOneBy(['name'=>$name_ingredient]);
            foreach ($dataIngredients as $dataproperty => $valueIngredients) {
                if (property_exists(Ingredients::class, $dataproperty) && method_exists(Ingredients::class, $setmetodo = 'set' . ucfirst($dataproperty))) {
                    $ingredients->$setmetodo($valueIngredients);
                }
            }
            $em->persist($ingredients);
            $em->flush();


        } catch (Exception $ex) {
            $message = "An error has occurred trying to edit the current dishes - Error: {$ex->getMessage()}";
        }
        $response = [
            'data' => $code == 200 ? $ingredients : $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }

    /**
     * @Rest\Put("/ingredients/{id}/addallergen/{name_allergen}.{_format}", name="ingredient_edit_add_allergen", defaults={"_format":"json"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="The ingredients was edited successfully."
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description="An error has occurred trying to edit the ingredients."
     * )
     *
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="string",
     *     description="The ingredients ID"
     * )
     *
     * @SWG\Tag(name="Ingredients")
     */
    public function editIngredientsAddAllegernAction(Request $request, $id, $name_allergen)
    {
        $serializer = $this->get('jms_serializer');
        try {
            $code = 200;
            $em = $this->getDoctrine()->getManager();
            $ingredient = $em->getRepository("App:Ingredients")->find($id);
            if (!is_null($ingredient)) {
                $allergen = $this->existOrAddAllegern($name_allergen);
                $ingredient->addAllergenToIngredient($allergen);
                $em->persist($ingredient);
                $em->flush();
            }
        }  catch (Exception $ex) {
            $message = "An error has occurred trying to edit the current ingredients - Error: {$ex->getMessage()}";
        }
        $response = [
            'data' => $code == 200 ? $ingredient : $message,
        ];
        return new Response($serializer->serialize($response, "json"));
    }

    /**
     * @Rest\Put("/ingredients/{id}/removeallergen/{name_allergen}.{_format}", name="ingrdients_edit_remove_allergen", defaults={"_format":"json"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="The ingredients was edited successfully."
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description="An error has occurred trying to edit the ingredients."
     * )
     *
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="string",
     *     description="The Dishes ID"
     *     )
     *
     * @SWG\Tag(name="Ingredients")
     */
    public function editIngredientsRemoveAllergenAction(Request $request, $id, $name_allergen)
    {
        $serializer = $this->get('jms_serializer');
        try {
            $code = 200;
            $em = $this->getDoctrine()->getManager();
            $ingredient = $em->getRepository("App:Ingredients")->find($id);
            if (!is_null($ingredient)) {
                $allergen = $em->getRepository("App:Allergens")->findOneBy(['name' => $name_allergen]);
                if(!is_null($allergen)) {
                    $ingredient->removeIngredientsToDishes($allergen);
                    $em->persist($ingredient);
                    $em->flush();
                }
            }
        }  catch (Exception $ex) {
            $message = "An error has occurred trying to edit the current dishes - Error: {$ex->getMessage()}";
        }

        $response = [
            'data' => $code == 200 ? $ingredient : $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }

    /**
     * @Rest\Delete("/ingredient/{id}.{_format}", name="dishes_remove", defaults={"_format":"json"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Ingredient was successfully removed"
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="An error was occurred trying to remove the Ingredient"
     * )
     *
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="string",
     *     description="The Ingredient ID"
     * )
     *
     * @SWG\Tag(name="Ingredients")
     */
    public function deleteIngredientsAction(Request $request, $id) {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();

        try {
            $ingredients = $em->getRepository("App:Ingredients")->find($id);

            if (!is_null($ingredients)) {
                $em->remove($ingredients);
                $em->flush();
                $message = "The Ingredient was removed successfully!";

            } else {
                $message = "An error has occurred trying to remove the currrent Ingredient - Error: The dishes id does not exist";
            }

        } catch (Exception $ex) {
            $message = "An error has occurred trying to remove the current Ingredient - Error: {$ex->getMessage()}";
        }

        $response = [
            'data' => $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }



    private  function addAllergen(array $allergensAdd)
    {
        foreach ($allergensAdd as $allergenProperty) {
            return $this->existOrAddAllegern($allergenProperty['name']);
        }
    }

    private function existOrAddAllegern($name_allergen)
    {
        $em = $this->getDoctrine()->getManager();
        $allergen = $em->getRepository("App:Allergens")->findOneBy(['name' => $name_allergen]);
        if (is_null($allergen)) {
            $allergen = new Allergens();
            $allergen->setName($name_allergen);
        }
        $em->persist($allergen);
        return $allergen;
    }
}
