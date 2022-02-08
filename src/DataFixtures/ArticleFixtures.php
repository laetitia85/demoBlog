<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    // l'objet $manager permet d'insérer nos articles de test
    public function load(ObjectManager $manager): void
    {
        
        for($i = 1; $i <= 10; $i++)
        {
            // nous allons créer 10 articles

            // on instancie la classe Article qui se trouve dans le dossier Entity
            $article = new Article;
            // a ce moment, mon objet $article est vide. Je vais utiliser les setters pour insérer des données dans mon objet

            $article->setTitle("Titre de l'article n°$i")
                    ->setContent("<p>Contenu de l'article n°$i</p>")
                    ->setImage("http://placehold.it/250x150")
                    // on instancie la classe DateTime pour récupérer l'heure et la date d'aujourd'hui dans le bon format
                    // on met un \ devant DateTime afin de récupérer la classe DateTime qui se trouve dans le namespace global
                    ->setCreatedAt(new \DateTime());

            // persist() permet de préparer l'insertion de l'article dans la BDD
            $manager->persist($article);
        }

        // flush() permet de lancer les requêtes SQL préparées
        $manager->flush();
    }
}
