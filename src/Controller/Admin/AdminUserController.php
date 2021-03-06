<?php

namespace App\Controller\Admin;

use App\Entity\AdminUser;
use App\Form\AdminUserType;
use App\Repository\AdminUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin/user")
 */
class AdminUserController extends AbstractController
{
    /**
     * @Route("/", name="users_index", methods={"GET"})
     * @param AdminUserRepository $repository
     * @return Response
     */
    public function index(AdminUserRepository $repository): Response
    {
        $adminUsers = $repository->findAll();

        return $this->render('admin/adminuser/index.html.twig', [
            'controller_name' => 'AdminUserController',
            'adminUsers' => $adminUsers,
        ]);
    }

    /**
     * @Route("/add", name="users_add", methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function addAdminUser(Request $request, EntityManagerInterface $manager): Response
    {
        $adminUser = New AdminUser();
        $form = $this->createForm(AdminUserType::class, $adminUser);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($adminUser);
            $manager->flush();
            return $this->redirectToRoute('users_index');
        }
        return $this->render("admin/adminuser/add.html.twig", [ "form" => $form->createView() ]);
    }

    /**
     * @Route("/{id}/edit", name="users_edit", methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param AdminUser $adminUser
     * @return Response
     */
    public function editAdminUser(Request $request, EntityManagerInterface $manager, AdminUser $adminUser): Response
    {
        $form = $this->createForm(AdminUserType::class, $adminUser);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($adminUser);
            $manager->flush();
            return $this->redirectToRoute('users_index');
        }
        return $this->render("admin/adminuser/add.html.twig", [ "form" => $form->createView() ]);
    }

    /**
     * @Route("/{id}", name="users_delete")
     * @param EntityManagerInterface $manager
     * @param AdminUser $adminUser
     * @return Response
     */
    public function deleteAdminUser(EntityManagerInterface $manager, AdminUser $adminUser): Response
    {
        $user = $this->getUser();
        if($user === $adminUser){
            return new Response('Vous ne pouvez pas supprimer cet utilisateur');
        }
        else{
            $manager->remove($adminUser);
            $manager->flush();
            return $this->redirectToRoute('users_index');
        }

    }
}