<?php

namespace App\Controller;

use App\Service\ManageCart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(Request $request, ManageCart $cart): Response
    {
        if ($request->getSession()->get("cart")) {
            $count = $request->getSession()->get("cart")->getCount();
            $items = $request->getSession()->get("cart")->getCart();
            $total = $request->getSession()->get("cart")->getTotal();
            $totalTTC = $request->getSession()->get("cart")->getTotalTTC();

            return $this->render('cart/index.html.twig', [
                'cart_total' => $total,
                'cart_total_ttc' => $totalTTC,
                'cart_products' => $items,
                'cart_count' => $count,
            ]);
        } else {
            return $this->render('cart/index.html.twig', [
                'cart_products' => '',
            ]);
        }
    }

    #[Route('/cart/remove/{id<[0-9]+>}', name: 'app_cart_remove_item')]
    public function removeCartItem(Request $request, $id) {
        $item = $request->getSession()->get("cart")->getCartItem($id);
        
        $request->getSession()->get("cart")->removeItem($item);

        return $this->redirect("/cart");
    }
}
