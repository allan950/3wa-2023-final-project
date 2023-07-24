<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

//\Stripe\Stripe::setApiKey();

class CheckoutController extends AbstractController
{
    #[Route('/checkout', name: 'app_checkout')]
    public function index(Request $req): Response
    {
        $items = $req->getSession()->get("cart")->getCart();

        if ($this->getUser()) {

            if ($items) {

                \Stripe\Stripe::setApiKey($this->getParameter("api_key_stripe"));

                $order = [];
                $tokenProvider = $this->container->get('security.csrf.token_manager');
                $token = $tokenProvider->getToken('stripe_token')->getValue();

                foreach ($items as $key => $item) {
                    $order[$key] = [
                        'price_data' => [
                            'currency' => 'eur',
                            'product_data' => [
                                'name' => $item["name"],
                            ],
                            'unit_amount' => intval($item["price"]) * 100,
                        ],
                        'quantity' => $item["quantity"],
                    ];
                }

                //dd($order);

                $hostname = $req->headers->get('host');

                $session = \Stripe\Checkout\Session::create([
                    'line_items' => [$order],
                    'mode' => 'payment',
                    'success_url' => "http://$hostname/checkout_success/" . $token,
                    'cancel_url' => "http://$hostname/checkout_error",
                ]);

                $req->getSession()->set("order_payment_id", $session->id);

                return $this->redirect($session->url, 303);
            } else {
                return $this->redirect('/product/list');
            }
        } else {
            return $this->redirect("/login");
        }
    }

    #[Route('/checkout_success/{token}', name: 'app_checkout_success')]
    public function successfulCheckout(Request $req, $token, OrderRepository $orderRepository, UserRepository $userRepository)
    {

        if ($this->isCsrfTokenValid("stripe_token", $token)) {
            \Stripe\Stripe::setApiKey($this->getParameter("api_key_stripe"));

            $session = \Stripe\Checkout\Session::retrieve($req->getSession()->get("order_payment_id"));

            if ($session) {
                $req->getSession()->set("order_payment_id", "");
            }

            $currentUser = $userRepository->findOneBy( array("id" => $this->getUser()->getId()));

            $order = new Order();
            $order->setOrderDate(new DateTime())
                ->setClient($this->getUser())
                ->setTotalPriceHt($session->amount_subtotal / 100)
                ->setTotalPriceTtc($session->amount_total / 100)
                ->setItems($req->getSession()->get("cart")->getCart())
                ->setShipAddr($currentUser->getAddress())
                ->setShipCity($currentUser->getCity())
                ->setShipZipcode($currentUser->getZipcode())
                ->setShipLname($currentUser->getLastName())
                ->setShipFname($currentUser->getFirstName())
                ->setBillAddr($currentUser->getAddress())
                ->setBillCity($currentUser->getCity())
                ->setBillZipcode($currentUser->getZipcode())
            ;

            $orderRepository->save($order, true);

            $req->getSession()->set("cart", "");

            return $this->render("checkout/success.html.twig", []);
        }

        return $this->redirect('/');
    }

    #[Route('/checkout_error', name: 'app_checkout_error')]
    public function failedCheckout() {
        return $this->render('checkout/failed.html.twig', []);
    }
}
