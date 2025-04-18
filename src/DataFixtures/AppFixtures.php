<?php

namespace App\DataFixtures;

use App\Entity\Cadeau;
use App\Entity\GroupeCadeau;
use App\Entity\ListeCadeau;
use Faker;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {}

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 1; $i <= 80; $i++) {
            $nom=$faker->lastName();
            $prenom=$faker->firstName();

            $user[$i]= new User;
            $user[$i]   ->setPseudo($nom.$faker->randomNumber(2,false))
                        ->setEmail($prenom.".".$nom."@gmail.com")
                        ->addRole('ROLE_AUTHENTIFIE');

            $hashedPassword = $this->passwordHasher->hashPassword(
                $user[$i],"password"
            );
            $user[$i]->setPassword($hashedPassword);

            $manager->persist($user[$i]);
            
        }
        
        //$j permet de bouger sur l'util
        //$u permet de bouger sur le groupe
        $j=0;
        $u=10;
        for ($i = 1; $i <= 8; $i++) {
            
            dump("dollard i $i");
            
            $groupe= new GroupeCadeau;
            $groupe->setNomGroupe($faker->word());
            
            
            while ($j<$u) {
                dump($j);
                $j++;
                $groupe->addUtilisateursConcerne($user[$j]);
                
                $listeCadeau=new ListeCadeau;
                for ($k = 1; $k <=8; $k++) {
                    $cadeau=new Cadeau;
                    $cadeau->setTitre($faker->word());
                    $cadeau->setPrix($faker->randomNumber(3,false));
                    $listeCadeau->addCadeaux($cadeau);

                    $manager->persist($cadeau);
                }
                $listeCadeau->setUtilisateur($user[$j]);
                $groupe->addListeCadeaux($listeCadeau);
                $manager->persist($listeCadeau);
            }
            $u+=10;
            $manager->persist($groupe);
        }
        $manager->flush();
    }
}
