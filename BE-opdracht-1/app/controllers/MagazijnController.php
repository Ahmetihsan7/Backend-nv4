<?php

class MagazijnController
{
    private Magazijn $magazijnModel;

    public function __construct(Magazijn $magazijnModel)
    {
        $this->magazijnModel = $magazijnModel;
    }


    public function index(): void
    {
        $producten = $this->magazijnModel->getProducten();

        require __DIR__ . '/../views/magazijn/index.php';
    }


    public function levering(int $productId): void
    {
        $product = $this->magazijnModel->getProduct($productId);

        if (!$product) {
            die('Product niet gevonden');
        }

        $voorraad = $this->magazijnModel->getVoorraad($productId);

        $leveringen = $this->magazijnModel->getLeveringen($productId);

        require __DIR__ . '/../views/magazijn/levering.php';
    }


    public function allergenen(int $productId): void
    {
        $product = $this->magazijnModel->getProduct($productId);

        if (!$product) {
            die('Product niet gevonden');
        }

        $allergenen = $this->magazijnModel->getAllergenen($productId);

        require __DIR__ . '/../views/magazijn/allergenen.php';
    }
}