<?php

namespace App\DataFixtures;

use App\Entity\Livres;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class LivresFixtures extends Fixture
{
    public function load(ObjectManager $em): void
    {  $faker=Factory::create('fr_FR');
        for($i=1;$i<=100;$i++) {
        $livre = new Livres();
        $livre->setTitre($faker->name)
            ->setIsbn($faker->isbn13())
            ->setImage('https://picsum.photos/400/?id='.$i)
            ->setQte(random_int(1, 100))
            ->setResume($faker->paragraph(10))
            ->setSlug(strtolower(str_replace(' ', '-', $livre->getTitre())))
            ->setDateEdition($faker->dateTimeBetween('-5 years', 'now'))
            ->setEditeur($faker->company())
            ->setPrix($faker->randomFloat(3,10,100));


        $em->persist($livre);
    }

        $em->flush();
    }
}
