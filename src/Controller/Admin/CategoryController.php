<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="categories_index", methods={"GET"})
     * @param CategoryRepository $repository
     * @return Response
     */
    public function index(CategoryRepository $repository): Response
    {
        $categories = $repository->findAll();

        return $this->render('admin/category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/add", name="categories_add", methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function addCategory(Request $request, EntityManagerInterface $manager): Response
    {
        $category = New Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($category);
            $manager->flush();
            return $this->redirectToRoute('categories_index');
        }
        return $this->render("admin/category/add.html.twig", [ "form" => $form->createView() ]);
    }

    /**
     * @Route("/{id}/edit", name="categories_edit", methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param Category $category
     * @return Response
     */
    public function editCategory(Request $request, EntityManagerInterface $manager, Category $category): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($category);
            $manager->flush();
            return $this->redirectToRoute('categories_index');
        }
        return $this->render("admin/category/add.html.twig", [ "form" => $form->createView() ]);
    }

    /**
     * @Route("/{id}", name="categories_delete")
     * @param EntityManagerInterface $manager
     * @param Category $category
     * @return Response
     */
    public function deleteCategory(EntityManagerInterface $manager, Category $category): Response
    {
        $manager->remove($category);
        $manager->flush();
        return $this->redirectToRoute('categories_index');
    }
}