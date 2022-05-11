<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Form\PersonneType;
use App\Repository\PersonneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HttpController extends AbstractController
{
    /**
     * @Route("/", name="app")
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {   
        $personne = new Personne();
        $form = $this->createForm(PersonneType::class, $personne);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $personne = $form->getData();

            $now = new \DateTime();
            $interval = $now->diff($personne->getDateNaissance());
            $entityManager->persist($personne);
            $entityManager->flush();
        }

        

        return $this->render('http/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show", name="show")
     */
    public function show(PersonneRepository $personneRepository) {
        $personnes = $personneRepository->findBy(array(), array('nom' => 'ASC'));
        return $this->render('http/show.html.twig', [
            'personnes' => $personnes,
        ]);

    }
}
