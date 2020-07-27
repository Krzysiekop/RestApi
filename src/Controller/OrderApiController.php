<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use App\Services\OrderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class OrderApiController extends AbstractController
{
    /**
     * @Route("/add-order", name="api_add_order", methods={"POST"})
     */
    public function addOrder(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {

        $newOrder = $serializer->deserialize($request->getContent(), Order::class, 'json');

        if (empty($newOrder->getProduct()) || empty($newOrder->getClientFullName()) || empty($newOrder->getCreatedAt())) {
            return new JsonResponse('Provide all data', Response::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($newOrder);
        $entityManager->flush();

        return new JsonResponse('Order created', Response::HTTP_OK);
    }

    /**
     * @Route("/show-orders", name="show_orders",methods={"GET"})
     */
    public function showOrders(Request $request, EntityManagerInterface $entityManager, OrderRepository $orderRepository, SerializerInterface $serializer): JsonResponse
    {
        $orders = $orderRepository->findAll();
        if (empty($orders)) {
            return new JsonResponse('orders not found', Response::HTTP_NOT_FOUND);
        }

        $ordersJson = $serializer->serialize($orders, 'json');

        return new JsonResponse(json_decode($ordersJson), Response::HTTP_OK);
    }

    /**
     * @Route("/show-order/{id}", name="show_order",methods={"GET"})
     */
    public function showOrder(int $id, OrderRepository $orderRepository, SerializerInterface $serializer): JsonResponse
    {
        $order = $orderRepository->findOneBy(['id' => $id]);
        if ($order === null) {
            return new JsonResponse('order id: ' . $id . ' not found', Response::HTTP_NOT_FOUND);
        }

        $orderJson = $serializer->serialize($order, 'json');

        return new JsonResponse(json_decode($orderJson), Response::HTTP_OK);
    }

    /**
     * @Route("/delete-order/{id}", name="delete_order",methods={"DELETE"})
     */
    public function deleteOrder(int $id, OrderRepository $orderRepository, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $order = $orderRepository->findOneBy(['id' => $id]);
        if ($order === null) {
            return new JsonResponse('order id: ' . $id . ' not found', Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($order);
        $entityManager->flush();

        return new JsonResponse('order id: ' . $id . ' deleted', Response::HTTP_OK);
    }

    /**
     * @Route("/edit-order/{id}", name="edit_order",methods={"PUT"})
     */
    public function editOrder(int $id, OrderRepository $orderRepository, SerializerInterface $serializer, EntityManagerInterface $entityManager, Request $request, OrderService $orderService): JsonResponse
    {
        $order = $orderRepository->findOneBy(['id' => $id]);
        if ($order === null) {
            return new JsonResponse('order id: ' . $id . ' not found', Response::HTTP_NOT_FOUND);
        }

        $editedOrder = $serializer->deserialize($request->getContent(), Order::class, 'json');

        $orderService->orderUpdate($editedOrder, $order);

        return new JsonResponse('order id: ' . $id . ' edited', Response::HTTP_OK);
    }
}
