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
use App\Repository\ProductRepository;

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

    public function readOrder(
        int $orderId,
        EntityManagerInterface $entityManager,
        OrderRepository $orderRepository,
        ProductRepository $productRepository
    )
    {
        $order = $orderRepository->findOneBy(['id' => $orderId]);

        if(!$order){
            return new Response('Order doesn\'t exists');
        }

        $conn = $entityManager->getConnection();
        $sql = '
            SELECT pio.product_id AS id, pio.quantity AS quantity 
            FROM products_in_orders pio
            WHERE pio.order_id = :orderId
            ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['orderId' => $orderId]);
        $products = $stmt->fetchAll();

        $arr = [
            'id' => $orderId,
            'totalPrice' => sprintf('%.2f', $order->getTotalPrice()),
            'products' => []
        ];
        foreach($products as $product){
            array_push($arr['products'], $product);
        }

        return new Response(json_encode($arr));
    }

    public function addProductToOrder(
        OrderRepository $orderRepository,
        ProductRepository $productRepository,
        EntityManagerInterface $entityManager,
        RequestStack $requestStack
    )
    {
        $request = $requestStack->getCurrentRequest();
        $data = json_decode($request->getContent());

        //Order Object from DB
        $orderInDB = $orderRepository->findOneBy(['id' => $data->id]);
        $orderInDB->setTotalPrice(0);

        $conn = $entityManager->getConnection();
        foreach($data->products as $product){
            //Product Object from DB
            $productInDB = $productRepository->findOneBy(['id' => $product->id]);

            //Update Order Object from DB
            $orderInDB->setTotalPrice(
                $orderInDB->getTotalPrice() +
                $productInDB->getPrice() * $product->quantity
            );

            //Delete row in DB
            $sql = '
                DELETE FROM products_in_orders
                WHERE order_id = :orderId AND product_id = :productId
                ';
            $stmt = $conn->prepare($sql);
            $stmt->execute(['orderId' => $data->id, 'productId' => $product->id]);

            //Add row in DB
            $sql = '
                INSERT INTO products_in_orders (order_id, product_id, quantity)
                VALUES (:orderId, :productId, :quantity)
                ';
            $stmt = $conn->prepare($sql);
            $stmt->execute(['orderId' => $data->id, 'productId' => $product->id, 'quantity' => $product->quantity]);
        }

        $entityManager->persist($orderInDB);
        $entityManager->flush();

        return new Response($this->readOrder($data->id, $entityManager, $orderRepository, $productRepository)
            ->getContent());
    }


}