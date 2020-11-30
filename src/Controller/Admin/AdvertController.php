<?php

namespace App\Controller\Admin;

use App\Entity\Advert;
use App\Entity\Category;
use App\Repository\AdvertRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Workflow\WorkflowInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/advert")
 */
class AdvertController extends AbstractController
{

    /**
     * @Route("/", name="adverts_index", methods={"GET"})
     * @param AdvertRepository $repository
     * @return Response
     */
    public function index(AdvertRepository $repository): Response
    {
        $adverts = $repository->findAll();

        return $this->render('admin/advert/index.html.twig', [
            'controller_name' => 'AdvertController',
            'adverts' => $adverts,
        ]);
    }
    /**
     * @Route("/{id}", name="advert_show", methods={"GET"})
     * @param AdvertRepository $repository
     * @return Response
     */
    public function showAdvert(AdvertRepository $repository, Advert $advert , Category $category): Response
    {
        return $this->render('admin/advert/show.html.twig', [
            'controller_name' => 'AdvertController',
            'advert' => $advert,
            'category' => $category,
        ]);
    }

    /**
     * @Route("/{id}/change-state/{transition}", name="advert_change_state", methods={"GET"})
     * @param Advert $advert
     * @param string $transition
     * @param WorkflowInterface $advertStateMachine
     * @param EntityManagerInterface $manager
     * @return Response
     */

    public function changeState(Advert $advert, string $transition, WorkflowInterface $advertStateMachine, EntityManagerInterface $manager): Response
    {
        if ($advertStateMachine->can($advert, $transition)) {
            $advertStateMachine->apply($advert, $transition);
            $manager->flush();

            $this->addFlash('success', sprintf('"%s" transition applied', $transition));
        } else {
            $this->addFlash('error', sprintf('"%s" transition can\'t be applied to advert "%s"', $transition, $advert->getId()));
        }
        return $this->redirectToRoute('adverts_index');
    }
}