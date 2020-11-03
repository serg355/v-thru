<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\Product;
use App\Repository\ProductRepository;

class ProductController extends AbstractController
{
    //const PRECISION = 2;

    public function createProduct(
        ProductRepository $productRepository,
        EntityManagerInterface $entityManager,
        RequestStack $requestStack)
    {

        $request = $requestStack->getCurrentRequest();
        $data = json_decode($request->getContent());

        if($data->price <= 0 || $data->price > 100){
            return new Response('Wrong price. It needs to be in (0, 100].');
        }

        $products = $productRepository->findBy(['name' => $data->name]);
        if(count($products) > 0){
            return new Response('Wrong name. It needs to be unique.');
        }

        $product = new Product();
        $product->setName($data->name);
        //$product->setPrice(round($data->price, self::PRECISION));
        $product->setPrice($data->price);

        $entityManager->persist($product);
        $entityManager->flush();

        return new Response($product->__toString());
    }


}
