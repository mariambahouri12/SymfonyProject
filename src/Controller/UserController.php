<?php

namespace App\Controller;

use App\Entity\USER;
use App\Entity\ExerciceSurPoidsFemme;
use App\Entity\SousPoids;
use App\Form\LoginType;
use App\Form\UserType;
use App\Controller\ExerciceController;
use App\controleer\SousController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function login(Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(LoginType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Vérifiez si les données correspondent aux critères requis
            if ($data['username'] === 'mariam' && $data['mail'] === 'mariambahouri12@gmail.com') {
                // Rediriger vers la page d'administration
                return $this->redirectToRoute('admin');
            } else {
                // Rechercher l'utilisateur dans la base de données
                $entityManager = $doctrine->getManager();
                $user = $entityManager->getRepository(User::class)->findOneBy(['username' => $data['username'], 'mail' => $data['mail']]);

                if ($user) {
                    // Rediriger en fonction du genre et du poids de l'utilisateur
                    return $this->redirectToRoute('user.fitness_start', ['id' => $user->getId()]);
                } else {
                    // Rediriger vers la page d'ajout d'utilisateur
                    return $this->redirectToRoute('user.add');
                }
            }
        }

        return $this->render('login.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin', name: 'admin')]
    public function admin(): Response
    {
        return $this->render('Admin1.html.twig');
    }
    
    #[Route('/admin1', name: 'admin1')]
    public function admin1(): Response
    {
        return $this->render('Admin.html.twig');
    }

    #[Route('/user/add', name: 'user.add')]
    public function addUser(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $user = new USER();
        $form = $this->createForm(UserType::class, $user);
        
        // Gérer la soumission du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Traitement après soumission valide du formulaire
            $entityManager->persist($user);
            $entityManager->flush();
            // Ajouter un message flash (optionnel)
            $this->addFlash('success', 'Utilisateur ajouté avec succès');

            // Redirection vers la page de bienvenue
            return $this->redirectToRoute('user.welcome', ['id' => $user->getId()]);
        }
    
        return $this->render('user/add-user.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/welcome/{id}', name: 'user.welcome')]
    public function welcome(int $id, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $user = $entityManager->getRepository(USER::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $poidsIdeal = $this->calculerPoidsIdeal($user);

        return $this->render('welcome.html.twig', [
            'user' => $user,
            'poidsIdeal' => $poidsIdeal,
        ]);
    }

    private function calculerPoidsIdeal(USER $user): float
    {
        $taille = $user->getHeight();
        $poidsIdeal = 0.0;

        if ($user->getGender() === 'male') {
            $poidsIdeal = $taille - 100 - (($taille - 150) / 4);
        } elseif ($user->getGender() === 'female') {
            $poidsIdeal = $taille - 100 - (($taille - 150) / 2.5);
        }

        return $poidsIdeal;
    }

    #[Route('/user/fitness/{id}', name: 'user.fitness')]
    public function fitnessProgram(int $id, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $user = $entityManager->getRepository(USER::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('No user found for id ' . $id);
        }

        return $this->render('user/fitness.html.twig', [
            'user' => $user
        ]);
    } 

    #[Route('/user/fitness/start/{id}', name: 'user.fitness_start')]
    public function startFitnessProgram(int $id, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $user = $entityManager->getRepository(USER::class)->find($id);
    
        if (!$user) {
            throw $this->createNotFoundException('No user found for id ' . $id);
        }
    
        $poidsIdeal = $this->calculerPoidsIdeal($user);
        $exercises = [];
        $exercises1 = [];
    
        // Récupérer tous les exercices
        $exercises = $entityManager->getRepository(ExerciceSurPoidsFemme::class)->findAll();
        $exercises1 = $entityManager->getRepository(SousPoids::class)->findAll();
    
        $path = '';
    
        if ($user->getWeight() > $poidsIdeal) {
            $path = 'user/Surpoids.html.twig';
        } elseif ($user->getWeight() < $poidsIdeal) {
                // Charger le template SousPoidsFemme.html.twig ici
                return $this->render('user/SousPoids.html.twig', [
                    'user' => $user,
                    'exercises' => $exercises,
                    'exercises1' => $exercises1, 
                ]);
            } 
         else 
        {
                $path = 'user/PoidsNormal.html.twig';
        
        }
    
        return $this->render($path, [
            'user' => $user,
            'exercises' => $exercises,
            'exercises1' => $exercises1,
        ]);
    }
    
}

