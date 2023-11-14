<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin/user')]
class AdminUserController extends AbstractController
{

    public function __construct(private UserPasswordHasherInterface $hasher)
    {
        
    }

    #[Route('/', name: 'app_admin_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        $encoder = new JsonEncoder();
        $defaultContext = [ AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function(object $object, string $format, array $context): string {
            return $object->getEmail();
        }];

        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $serialize = new Serializer([$normalizer], [$encoder]);

        return $this->render('back/admin_user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'users_json' => $serialize->serialize($users, 'json')
        ]);
    }

    #[Route('/search', name: 'app_admin_user_list_search', methods: ['GET', 'POST'])]
    public function filteredList(Request $req, UserRepository $userRepo): Response
    {
        $params = array(
            "id"=> $req->query->get('id'),
            "email"=> $req->query->get('email'),
            "roles"=> $req->query->get('roles'),
            "last_name"=> $req->query->get('lname'),
            "first_name"=> $req->query->get('fname'),
        );

        $users = $userRepo->queryFilteredList($params);

        return $this->render('back/admin_user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/new', name: 'app_admin_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword(
                $this->hasher->hashPassword($user, $user->getPassword())
            );
            
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/admin_user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('back/admin_user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/admin_user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
