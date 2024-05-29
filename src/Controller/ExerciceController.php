<?php

namespace App\Controller;

use App\Entity\ExerciceSurPoidsFemme;
use App\Form\ExerciceFemaleSurpoidsType;
use App\Form\ExerciceDeleteType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;


#[Route('/exercise')]
class ExerciceController extends AbstractController
{
    #[Route('/new', name: 'exercise_new')]
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $exercise = new ExerciceSurPoidsFemme();
        $form = $this->createForm(ExerciceFemaleSurpoidsType::class, $exercise);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $existingExercise = $doctrine->getRepository(ExerciceSurPoidsFemme::class)->findOneBy(['title' => $exercise->getTitle()]);
            if ($existingExercise) {
                $this->addFlash('error', 'Exercise already exists.');
                return $this->redirectToRoute('exercise_new');
            } else {
                // Crée un nouvel exercice
                $entityManager = $doctrine->getManager();
                $entityManager->persist($exercise);
                $entityManager->flush();

                $this->addFlash('success', 'Exercise created successfully.');
                return $this->redirectToRoute('exercise_new');
            }
        }

        return $this->render('exercise/new.html.twig', [
            'form' => $form->createView(),
        ]);
}
#[Route('/update', name: 'exercise_update')]
public function update(Request $request, ManagerRegistry $doctrine): Response
{
    $exercise = new ExerciceSurPoidsFemme();
    $form = $this->createForm(ExerciceFemaleSurpoidsType::class, $exercise);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
     
        $existingExercise = $doctrine->getRepository(ExerciceSurPoidsFemme::class)->findOneBy(['title' => $exercise->getTitle()]);
        if ($existingExercise) {
           
            $existingExercise->setDescription($exercise->getDescription());
            $existingExercise->setImage($exercise->getImage());
            $existingExercise->setCategory($exercise->getCategory());
            $doctrine->getManager()->flush();
            //flash
            
            $this->addFlash('success', 'Exercise updated successfully.');
            
            return $this->redirectToRoute('exercise_update');
        } else {
            $this->addFlash('error', 'Exercise does not exist.');
        }
    }
    return $this->render('exercise/update.html.twig', [
        'form' => $form->createView(),
    ]);
}


    #[Route('/search', name: 'search_exercises')]
    public function search(Request $request, ManagerRegistry $doctrine): Response
    {
        $title = $request->query->get('title');
        $exercise = $doctrine->getRepository(ExerciceSurPoidsFemme::class)->findOneBy(['title' => $title]);

        if ($exercise) {
            return $this->redirectToRoute('exercise_show', ['id' => $exercise->getId()]);
        }

        return $this->render('exercise/not_found.html.twig');
    }
   
    #[Route('/delete', name: 'exercise_delete')]
    public function delete(Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(ExerciceDeleteType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $exercise = $doctrine->getRepository(ExerciceSurPoidsFemme::class)->find($data['id']);

            if ($exercise) {
                return $this->redirectToRoute('exercise_delete_confirm', ['id' => $exercise->getId()]);
            }

            $this->addFlash('error', 'Exercise not found.');
        }

        return $this->render('exercise/delete_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/success', name: 'exercise_delete_success')]
    public function successDelete(): Response
    {
        return $this->render('exercise/successdelete.html.twig');
    }
#[Route('/delete/confirm/{id}', name: 'exercise_delete_confirm', methods: ['GET', 'POST'])]
public function deleteConfirm(Request $request, ManagerRegistry $doctrine, int $id): Response
{
    $exercise = $doctrine->getRepository(ExerciceSurPoidsFemme::class)->find($id);

    if (!$exercise) {
        throw new NotFoundHttpException('Exercise not found.');
    }

    if ($request->isMethod('POST')) {
        if ($this->isCsrfTokenValid('delete'.$exercise->getId(), $request->request->get('_token'))) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($exercise);
            $entityManager->flush();

            
            return new RedirectResponse($this->generateUrl('exercise_delete_success'));
        }
    }

    return $this->render('exercise/delete_confirm.html.twig', [
        'exercise' => $exercise,
    ]);
}
#[Route('/', name: 'exercise_index')]
public function index(ManagerRegistry $doctrine): Response
{
    $exercises = $doctrine->getRepository(ExerciceSurPoidsFemme::class)->findAll();

    return $this->render('exercise/index.html.twig', [
        'exercises' => $exercises,
    ]);
}
#[Route('/{id}', name: 'exercise_show')]
public function show(ExerciceSurPoidsFemme $exercise): Response
{
    return $this->render('exercise/show.html.twig', [
        'exercise' => $exercise,
    ]);
}

}