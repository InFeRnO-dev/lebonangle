<?php

namespace App\Controller\Admin;

use App\Entity\AdminUser;
use App\Entity\Category;
use App\Form\AdminUserType;
use App\Form\CategoryType;
use App\Repository\AdminUserRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


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
    public function addAdminUser(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder): Response
    {
        $adminUser = New AdminUser();
        $form = $this->createForm(AdminUserType::class, $adminUser);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->register($encoder, $adminUser);
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
        $manager->remove($adminUser);
        $manager->flush();
        return $this->redirectToRoute('users_index');
    }
    public function register(UserPasswordEncoderInterface $encoder, AdminUser $adminUser)
    {
        $plainPassword = $adminUser->getPlainPassword();
        $encoded = $encoder->encodePassword($adminUser, $plainPassword);

        $adminUser->setPassword($encoded);
    }
}