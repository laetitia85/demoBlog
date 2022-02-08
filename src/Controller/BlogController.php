<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'blog')]
    public function index(): Response
    {
        // la méthode render() vient de la class AbstractController
        // la méthode render() permet de renvoyer une réponse d'affichage d'une vue
        // elle prend en paramètre 1 un template à afficher
        // un controller renvoie obligatoirement un objet de type Response
        return $this->render('blog/index.html.twig', [
           
        ]);
    }

    #[Route('/', name: 'home')]
    public function home()
    {
        // la méthode render() va chercher les vues automatiquement dans le dossier templates/
        // le paramètre 2 de render() est un tableau associatif permettant d'envoyer des variables à la vue
        return $this->render('blog/home.html.twig', [
            // syntaxe : 
            // nom de la variable dans la vue => valeur de la variable
            'title' => 'Bienvenue sur le blog DemoBlog',
            'age' => 36
        ]);
    }

    #[Route('/blog/12', name: 'blog_show')]
    public function show()
    {
        return $this->render('blog/show.html.twig', [
            
        ]);
    }
}
