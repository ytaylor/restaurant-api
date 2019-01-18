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
    public function getIngredientsAction(Request $request) {
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
}
