<?php

namespace App\Controller\Purchase;

use App\Cart\CartService;
use App\Form\CartConfirmationType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class PurchaseConfirmationController
{
    protected $formFactroy;
    protected $router;
    protected $security;
    protected $cartService;

    public function __construct(FormFactoryInterface $formFactroy, RouterInterface $router, Security $security, CartService $cartService)
    {
        $this->formFactroy = $formFactroy;
        $this->router = $router;
        $this->security = $security;
        $this->cartService = $cartService;
    }

    #[Route('/purchase/confirm', name: 'purchase_confirm')]
    public function confirm(Request $request, FlashBag $flashBag)
    {
        // 1. Nous voulons lire les données du formulaires
        // FormFactoryInterface / Request
        $form = $this->formFactroy->create(CartConfirmationType::class);

        $form->handleRequest($request);

        // 2. Si le formulaire n'a pas été soumis : dégager

        if (!$form->isSubmitted()) {
            // Message Flash puis redirection (FlashBagInterface)
            $flashBag->add('warning', 'Vous devez remplir le formulaire de confirmation');

            return new RedirectResponse($this->router->generate('cart_show'));
        }

        // 3. Si je ne suis pas connecté : dégager (Security)
        $user = $this->security->getUser();

        if (!$user) {
            throw new AccessDeniedException('Vous devez être connecter pour confirmer une commande');
        }

        // 4. Si il n'y a pas de produit dans le panier : dégager (CartService)
        $cartItem = $this->cartService->getDetailedCartItems();

        if (0 === count($cartItem)) {
            $flashBag->add('warning', 'Vous ne pouvez pas confirmer une commande avec un panier vide');

            return new RedirectResponse($this->router->generate('cart_show'));
        }

        // 5. Nous allons créer une Purchase

        // 6. Nous allons la lier avec l'utilisateur actuellement connecté (Security)

        // 7. Nous allons la lier avec les produits qui sont dans le panier (CartService)

        // 8. Nous allons enregistrer la commande (EntityManagerInterface)
    }
}
