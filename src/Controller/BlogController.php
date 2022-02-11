<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'blog')]
    public function index(ArticleRepository $repo): Response
    {
        /* on créer une variable $repo dans laquelle on récupère notre repository de l'entity Article pour récupérer les articles en BDD
        Je demande à Doctrine de me fournir le repository de la classe Article */
        // $repo = $this->getDoctrine()->getRepository(Article::class);

        // la méthode findAll() du repository permet de récupérer tous les articles de la table
        $articles = $repo->findAll();

        // la méthode render() vient de la class AbstractController
        // la méthode render() permet de renvoyer une réponse d'affichage d'une vue
        // elle prend en paramètre 1 un template à afficher
        // un controller renvoie obligatoirement un objet de type Response
        return $this->render('blog/index.html.twig', 
        [
            // j'envoie le tableau $articles à la vue
           'articles' => $articles
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

    // {} permet d'indiquer que c'est un paramètre
    #[Route('/blog/show/{id}', name: 'blog_show')]
    /* Nous utilisons le ParamConverter de Symfony : Symfony voit qu'on a besoin d'un article et d'un id, donc il va chercher l'article correspondant à cet id et l'envoyer dans la méthode show() */
    public function show(Article $article)
    {
        /* on créer une variable $repo dans laquelle on récupère notre repository de l'entity Article pour récupérer les articles en BDD
        Je demande à Doctrine de me fournir le repository de la classe Article */
        // $repo = $this->getDoctrine()->getRepository(Article::class);

        // find() est une méthode magique du repository permettant de récupérer un seul article en fonction de son id
        // $article = $repo->find($id);


        return $this->render('blog/show.html.twig', [
             // j'envoie l'article $article à la vue
            'article' => $article
        ]);
    }

    #[Route('/blog/new', name: 'blog_create')]
    #[Route('/blog/edit/{id}', name: 'blog_edit')]
    // la classe Request contient toutes les données véhiculées par les superglobales ($_POST, $_GET, $_FILES, ect...)
    /* par défaut mon objet $article est null car si il va sur la route /blog/new l'article n'existe pas par contre s'il va sur la route /blog/edit/{id} , l'objet $article sera celui lié à l'id */
    public function form(Request $rq, EntityManagerInterface $manager, Article $article = null)
    {
        // dump($rq);

        // // si nous avons bien saisi des données dans le formulaire
        // // l'objet request contenu dans la classe Request contient les données de la superglobale $_POST
        // if($rq->request->count() > 0) 
        // {
        //     // on créer un objet article vide
        //     $article = new Article;
        //     // puis on le rempli grâce au setters
        //     // depuis l'instance de la classe request, je récupère la saisie du champ title
        //     // set permet de modifier la valeur
        //     // get permet de récupérer la nouvel valeur
        //     $article->setTitle($rq->request->get('title'))
        //             ->setContent($rq->request->get('content'))
        //             ->setImage($rq->request->get('image'))
        //             // on instancie la classe DateTime pour récupérer l'heure et la date d'aujourd'hui dans le bon format
        //             // on met un \ devant DateTime afin de récupérer la classe DateTime qui se trouve dans le namespace global
        //             ->setCreatedAt(new \DateTime);

        //     // persist() permet de préparer l'insertion de l'article dans la BDD
        //     // il garde en mémoire l'objet $article
        //      $manager->persist($article);

        //     // flush() permet de lancer les requêtes SQL préparées
        //     // j'insère l'article
        //     $manager->flush();

        //     // après l'insertion de l'article, on redirige l'utilisateur sur la page blog_show grâce à l'id de cet article qu'on récupère avec  'id' => $article->getId()
        //     return $this->redirectToRoute('blog_show', [
        //         'id' => $article->getId()
        //     ]);
        // }

        // si l'objet $article est null, nous sommes dans la création d'article 
        if(!$article) 
        {
            // donc on créer un nouvel objet $article vide
            $article = new Article;
        }
     
        // createFormBuilder() permet de construire un formulaire en lui ajoutant des champs
        // on lui passe l'objet $article pour lier le formulaire à l'article
        // $form = $this->createFormBuilder($article)
        //             // add() est une fonction permettant d'ajouter un champ au formulaire
        //             ->add('title')
        //             ->add('content')
        //             ->add('image')
        //             // getForm() permet de récupérer le formulaire sous forme d'objet. C'est cette fonction qui renvoie le formulaire construit.
        //             ->getForm();

        // après soumission du formulaire, nous remarquons que les données saissies sont liées à l'objet $article 
        /* la méthode handleRquest() permet de faire plusieurs choses : 
            - elle lie les champs du formulaire aux champs de l'objet $article
            - elle fait des vérifications sur les superGlobales (quel est le type du formulaire ? est-ce que les champs sont remplis ? ) */

        /* createForm() permet de récupérer le formulaire créer grâce à la commande make:form
        Ce formulaire se trouve dans le dossier src/Form/
        Cette méthode permet aussi de lier mon objet $article au formulaire */
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($rq);
        dump($article);

        // si le formulaire est soumis et valide
        if($form->isSubmitted() && $form->isValid()) 
        {
            // si l'article n'as pas d'id, nous sommes dans la création
            if(!$article->getId())
            {
                // nous devons donc lui donner une date de création
                $article->setCreatedAt(new \DateTime());
            }
          
            // on prépare l'insertion
            $manager->persist($article);
            // on insère
            $manager->flush();

           // après l'insertion de l'article, on redirige l'utilisateur sur la page blog_show grâce à l'id de cet article qu'on récupère avec  'id' => $article->getId()
           return $this->redirectToRoute('blog_show', [
               'id' => $article->getId()
            ]);
        }

        return $this->render('blog/create.html.twig', [
            // createView() renvoie un objet permettant l'affichage du formulaire dans notre vue
            'formArticle' => $form->createView(),

            /* si l'objet $article n'a pas d'id, editMode est à 0, on est dans l'édition 
            sinon si l'objet $article a un id, editMode est à 1, on est dans la création */
            'editMode' => $article->getId() !== NULL
        ]);
    }

}
