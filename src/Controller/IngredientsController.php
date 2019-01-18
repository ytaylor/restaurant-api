<?php

namespace App\Controller;

use App\Entity\Ingredients;
use App\Form\IngredientsType;
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
     * @SWG\Parameter(
     *     name="name",
     *     in="body",
     *     type="string",
     *     description="The ingredients name",
     *     schema={}
     * )
     *
     * @SWG\Tag(name="Ingredients")
     */
    public function addIngredientsAction(Request $request) {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $ingredients = new Ingredients();
        $message = "";
        $dataIngredients = json_decode($request->getContent());

        try {
            $code = 201;
            foreach ($dataIngredients as $dataproperty => $valueIngredients) {
                if (property_exists(Dishes::class, $dataproperty) && method_exists(Dishes::class, $setmetodo = 'set' . ucfirst($dataproperty))) {
                    $ingredients->$setmetodo($valueIngredients);
                }
            }
            $em->persist($ingredients);
            $em->flush();

        } catch (Exception $ex) {
            $message = "An error has occurred trying to add new dishes - Error: {$ex->getMessage()}";
        }

        $response = [
            'data' => $code == 201 ? $ingredients : $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }
}
