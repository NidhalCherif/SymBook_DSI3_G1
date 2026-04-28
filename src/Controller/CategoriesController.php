<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Form\CategorieType;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CategoriesController extends AbstractController
{
    #[Route('/categories', name: 'categories')]
    public function lister(CategoriesRepository $rep): Response
    { $cats = $rep->findAll();
        return $this->render('categories/lister.html.twig', [
            'categories' => $cats,
        ]);
    }
    #[Route('/categories/add', name: 'categories_add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    { $cat = new Categories();
        $form=$this->createForm(CategorieType::class,$cat);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //Récupérer les données et aliment l'objet vide cat
            try {
                $cat = $form->getData();
                // persister et flush
                $em->persist($cat);
                $em->flush();
                //redirection vers liste des catégories
                $this->addFlash('success', "insertion réussie!!");
                $this->addFlash('success', "un email de confirmation est envoyé");
                $this->addFlash('info', "Passer à l'étape suivante");

                return $this->redirectToRoute('categories');
            }
            catch (\Exception $e){
                $this->addFlash('danger', "une erreur est survenue");
            }
        }
        return $this->render('categories/add.html.twig', ['f'=>$form]);





    }


}
