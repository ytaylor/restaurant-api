<?php

namespace App\Controller;

use App\Entity\Allergens;
use App\Form\AllergensType;
use App\Repository\AllergensRepository;
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



class AllergensController extends FOSRestController
{
    /**
     * @Rest\Get("/allergens.{_format}", name="allergens_list_all", defaults={"_format":"json"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Gets all allergens."
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description="An error has occurred trying to get all allergens."
     * )
     *
     * @SWG\Tag(name="Allergens")
     */
    public function getIngredientsAction(Request $request)
    {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $allergens = [];
        $message = "";

        try {
            $code = 200;
            $allergens = $em->getRepository("App:Allergens")->findAll();

            if (is_null($allergens)) {
                $allergens = [];
            }

        } catch (Exception $ex) {
            $code = 500;
            $message = "An error has occurred trying to get all Allergens - Error: {$ex->getMessage()}";
        }

        $response = [
            'data' => $code == 200 ? $allergens : $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }

    /**
     * @Rest\Get("/allergens/{name_allergen}/dishes.{_format}", name="dishes__allergens_by_name", defaults={"_format":"json"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Gets dishes info based on passed name of allergens."
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="The allergens with the passed ID parameter was not found or doesn't exist."
     * )
     *
     * @SWG\Tag(name="Dishes")
     */
    public function getDishesAllergensByName(Request $request, $name_allergen)
    {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $dishes = [];
        $message = "";

        try {
            $code = 200;
            $allergen = $em->getRepository("App:Allergens")->findOneBy(['name' => $name_allergen]);

            if (is_null($allergen)) {
                $code = 500;
                $message = "The allergen does not exist";
            } else {
                $dataDishes = $em->getRepository('App:Allergens')->findDishesByAllergen($name_allergen);
                foreach ($dataDishes as $dish) {
                    $dishes[] = $dish;
                }

            }
        } catch (Exception $ex) {
            $code = 500;
            $message = "An error has occurred trying to get the current allergens pf dish - Error: {$ex->getMessage()}";
        }

        $response = [
            'data' => $code == 200 ? $dishes : $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }


}
