<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\Order;
use App\Repository\OrderRepository;

class OrderController extends AbstractController
{
    public function createOrder(EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $request = $requestStack->getCurrentRequest();
        $data = json_decode($request->getContent());

        $order = new Order();

        $entityManager->persist($order);
        $entityManager->flush();

        return new Response($order->__toString());
    }

    public function readOrder(int $orderID)
    {


        return new Response('reading order '.$orderID);
    }

    public function addProductToOrder(
        OrderRepository $orderRepository,
        EntityManagerInterface $entityManager,
        RequestStack $requestStack
    )
    {
        $request = $requestStack->getCurrentRequest();
        $data = json_decode($request->getContent());

        //dd($data->products);
        foreach($data->products as $product){

        }


        $query = $entityManager->createQuery(

        );


        return new Response('adding products to order');
    }


}