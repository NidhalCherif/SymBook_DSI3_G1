<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Livres;
use App\Form\CategorieType;
use App\Form\LivreAddType;
use App\Repository\LivresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/admin', name: 'admin_')]
final class LivresController extends AbstractController
{
    #[Route('/livres', name: 'livres')]
    public function index(): Response
    {
        return $this->render('livres/lister.html.twig', [
            'controller_name' => 'LivresController',
        ]);
    }
    #[Route('/livres/lister', name: 'livres_lister')]
    public function lister(LivresRepository $rep,PaginatorInterface $paginator, Request $request): Response
    {
            $livres = $paginator->paginate(
                $rep->findAll(), /* query NOT result */
                $request->query->getInt('page', 1), /* page number */
                10 /* limit per page */
            );
        return $this->render('livres/lister.html.twig', [
            'livres' => $livres,
        ]);
    }
    //paramconverter
    #[Route('/livres/show/{id}', name: 'livres_show')]
    public function show(Livres $livre): Response
    {
        return $this->render('livres/show.html.twig', [
            'livre' => $livre,
        ]);
    }

    #[Route('/livres/delete/{id}', name: 'livres_delete')]
    public function delete(Livres $livre,EntityManagerInterface $em): Response
    {
       $em->remove($livre);
       $em->flush();
        return $this->redirectToRoute('livres_lister');
    }
    #[Route('/livres/update/{id}', name: 'livres_update')]
    public function update(Livres $livre,EntityManagerInterface $em): Response
    {  $livre->setPrix($livre->getPrix()+10);

        $em->flush();
        return $this->redirectToRoute('livres_lister');
    }

    #[Route('/livres/add', name: 'livres_add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    { $livre = new Livres();
        $form=$this->createForm(LivreAddType::class,$livre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //Récupérer les données et aliment l'objet vide cat
            try {
                $livre = $form->getData();
                // persister et flush
                $em->persist($livre);
                $em->flush();
                //redirection vers liste des catégories
                $this->addFlash('success', "insertion réussie!!");


                return $this->redirectToRoute('livres_lister');
            }
            catch (\Exception $e){
                $this->addFlash('danger', "une erreur est survenue");
            }
        }
        return $this->render('livres/add.html.twig', ['f'=>$form]);}








}












