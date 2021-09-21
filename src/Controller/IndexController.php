<?php
    namespace App\Controller;

    //use App\Service\RandomNumberGenerator;
    use App\Service\DateCalculator;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;

    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Psr\Log\LoggerInterface;
    use App\Entity\Hotel;
    
    class IndexController extends AbstractController
    {

        /*
            To use annotations, run the following command 
            > composer require annotations
            
            To use dependency injections (i.e. DateCalculator and RandomNumberGenerator services)
            > composer require symfony/dependency-injection

            To use reinstall Doctrine/ORM,
            > composer require symfony/orm-pack
            > composer require --dev symfony/maker-bundle

            Use the following command to make database in mysql. It will create symfony database based on .env definition.
            > php bin/console doctrine:database:create

            To generate SQL statement from the database create. It will generate php versioned file under migrations folder.
            > php bin/console make:migration

            To push migration SQL to database. It pushes migration code to the database
            > php bin/console doctrine:migrations:migrate

        */
        
        private const HOTEL_OPENED= 1969; 

        /**
         * 
         * @Route ("/")
         */

        public function home(LoggerInterface $logger, DateCalculator $dateCalculator)
        {
            $logger->info('Homepage Loaded.');
            
            //randomizing # years of hotel established
            $year = random_int(0,100);
            
            //Using custom service
            //$year = $randomNumberGenerator->getRandomNumber();

            //Using custom date service
            $year = $dateCalculator->yearDifference(self::HOTEL_OPENED);

            //Using repository. VS Code intellisense will warn the findAllBelowPrice is undefined method. This is a bug. It's still working
            $hotels = $this->getDoctrine()
                ->getRepository(Hotel::class)
                ->findAllBelowPrice(750);
                //->findAll();

            //Using twig templates to display array of images
            $images = [
                [ 'url' => 'images/hotel/intro_room.jpg', 'class' => ''],
                [ 'url' => 'images/hotel/intro_pool.jpg', 'class' => ''],
                [ 'url' => 'images/hotel/intro_dining.jpg', 'class' => ''],
                [ 'url' => 'images/hotel/intro_attractions.jpg', 'class' => ''],
                [ 'url' => 'images/hotel/intro_wedding.jpg', 'class' => 'hidesm']
            ];

            return $this->render('index.html.twig', 
                ['year'=> $year, 'images' => $images, 'hotels' => $hotels]
        );

            /* Returning simple Response string
            return new Response(
                '<h1>My first Symfony page</h1>'
            );
            */
        }
    }

?>