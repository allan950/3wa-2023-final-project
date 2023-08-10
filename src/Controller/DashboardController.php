<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/dashboard')]
class DashboardController extends AbstractController
{

    public function __construct(private UserRepository $userRepository)
    {
    }

    #[Route('/', name: 'app_dashboard')]
    public function index(): Response
    {


        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    #[Route('/info', name: 'app_dashboard_info')]
    public function accessPersonalInfo()
    {
        $currentUser = $this->userRepository->findOneBy(array("id" => $this->getUser()->getId()));



        return $this->render('dashboard/personal-info/index.html.twig', [
            "user" => $currentUser,
        ]);
    }

    #[Route('/info/update', name: 'app_dashboard_info_update')]
    public function updateInfo(Request $req, UserRepository $userRepository)
    {
        $load = array(
            "user_id" => $this->getUser()->getId(),
            "last_name" => $req->request->get("userLname"),
            "first_name" => $req->request->get("userFname"),
            "city" => $req->request->get("userCity"),
            "address" => $req->request->get("userAddress"),
            "zipcode" => $req->request->get("userZipcode")
        );

        $forbidden = array(
            '<script .*?>.*?<\/script>', '<script>.*?<\/script>',
            '<a .*?>.*?<\/a>', '<a>.*?<\/a>',
            '<head .*?>.*?<\/head>', '<head>.*?<\/head>',
            '<body .*?>.*?<\/body>', '<body>.*?<\/body>',
            '<iframe .*?>.*?<\/iframe>', '<iframe>.*?<\/iframe>',
            '<link .*?>'
        );

        $isThereError = false;

        foreach ($load as $key => $attr) {

            foreach ($forbidden as $val) {
                strip_tags($load[$key]);

                if (preg_match("/^$val+$/", $attr, $matches) == 1) {
                    $isThereError = true;
                    $this->addFlash("notice", "Your data has not been updated! Please review your inputs before submitted your changes");
                    return $this->redirect('/dashboard/info');
                }
            }
        }

        if (!$isThereError) {
            $userRepository->update($load);
        }

        return $this->redirect('/dashboard/info');
    }

    #[Route('/orders', name: 'app_dashboard_orders')]
    public function accessOrders(OrderRepository $orderRepo)
    {

        $orders = $orderRepo->findBy(array("client" => $this->getUser()));
        $modalItems = null;

        return $this->render('dashboard/orders/index.html.twig', [
            "orders" => $orders,
            "modalItems" => $modalItems
        ]);
    }
}
