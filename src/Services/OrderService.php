<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;

class OrderService
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function orderUpdate(Order $editedOrder, Order $order): void
    {
        empty($editedOrder->getProduct()) ? null : $order->setProduct($editedOrder->getProduct());
        empty($editedOrder->getClientFullName()) ? null : $order->setClientFullName($editedOrder->getClientFullName());
        empty($editedOrder->getCreatedAt()) ? null : $order->setCreatedAt($editedOrder->getCreatedAt());
        $this->entityManager->flush();
    }

}