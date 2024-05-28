<?php

namespace App\Controller;

use App\Entity\SousPoids;
use App\Form\SousPoidsType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;


#[Route('/sous')]
class SousController extends AbstractController
{
    #[Route('/sousNew', name: 'exercise_sousnew')]
   public function new(Request $request, ManagerRegistry $doctrine): Response
{
    $exercise = new SousPoids();
    $form = $this->createForm(SousPoidsType::class, $exercise);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $existingExercise = $doctrine->getRepository(SousPoids::class)->findOneBy(['title' => $exercise->getTitle()]);
        if (!$existingExercise){
        // Crée un nouvel exercice
            $entityManager = $doctrine->getManager();
            $entityManager->persist($exercise);
            $entityManager->flush();

            $this->addFlash('success', 'Exercise created successfully.');

            return $this->redirectToRoute('exercise_show', ['id' => $exercise->getId()]);
    }}

    return $this->render('sous/sousnew.html.twig', [
        'form' => $form->createView(),
    ]);
}

    #[Route('/{id}', name: 'exercise_sousshow')]
    public function show(ExerciceSurPoidsFemme $exercise): Response
    {
        return $this->render('sous/sousshow.html.twig', [
            'exercise' => $exercise,
        ]);
    }

}