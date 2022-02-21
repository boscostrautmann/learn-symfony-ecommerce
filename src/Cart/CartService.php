<?php

namespace App\Cart;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    protected $requestStack;
    protected $session;
    protected $productRepository;

    public function __construct(RequestStack $requestStack, ProductRepository $productRepository)
    {
        $this->requestStack = $requestStack;
        $this->session = $this->requestStack->getSession();
        $this->productRepository = $productRepository;
    }

    public function add(int $id)
    {
        // 1. Retrouver le panier dans la session (sous forme de tableau)

        // 2. Si le panier n'existe pas encore, alors prendre un tableau vide
        $cart = $this->getCart();

        // 3. Voir si le produit ($id) existe déjà dans le tableau
        // 4. Si le produit existe déjà dans le tableu, augumenter la quantité
        // 5. Sinon, ajouter le produit avec la quantité 1
        if (!array_key_exists($id, $cart)) {
            $cart[$id] = 0;
        }

        ++$cart[$id];

        // 6. Enregistrer le tableau mise a jour dans la saisson
        $this->saveCart($cart);
    }

    public function remove(int $id)
    {
        $cart = $this->getCart();

        unset($cart[$id]);

        $this->saveCart($cart);
    }

    public function decrement(int $id)
    {
        $cart = $this->getCart();

        if (!array_key_exists($id, $cart)) {
            return;
        }

        // Soit le produit est à 1 alors il faut simplement le supprimer
        if (1 === $cart[$id]) {
            $this->remove($id);

            return;
        }

        // Soit le produit est à plus de 1, alors il faut décrémenter
        --$cart[$id];

        $this->saveCart($cart);
    }

    public function getTotal(): int
    {
        $total = 0;

        foreach ($this->getCart() as $id => $qty) {
            $product = $this->productRepository->find($id);

            if (!$product) {
                continue;
            }

            $total += ($product->getPrice() * $qty);
        }

        return $total;
    }

    public function getDetailedCartItems(): array
    {
        $detailedCart = [];
        $total = 0;

        foreach ($this->getCart() as $id => $qty) {
            $product = $this->productRepository->find($id);

            if (!$product) {
                continue;
            }

            $detailedCart[] = new CartItem($product, $qty);
        }

        return $detailedCart;
    }

    protected function getCart(): array
    {
        return $this->session->get('cart', []);
    }

    protected function saveCart(array $cart)
    {
        $this->session->set('cart', $cart);
    }
}
