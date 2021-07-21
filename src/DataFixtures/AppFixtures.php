<?php

namespace App\DataFixtures;

use App\Entity\AboutMe;
use App\Entity\Illustration;
use App\Entity\Project;
use App\Entity\Techno;
use App\Entity\Timeline;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private $slugger;

    public function __construct(Slugify $slugify)
    {
        $this->slugger = $slugify;
    }
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        $aboutMe = new AboutMe();
        $aboutMe->setTitle('Mickael Garatens')
            ->setFunctions('Développeur Web')
            ->setEmail('garatens.mickael@orange.fr')
            ->setGithubLink('micka260583')
            ->setDescription('Lorem ipsum dolor sit amet. Ea fuga eveniet est dicta voluptas vel eveniet voluptas qui quis iusto et omnis doloribus ut illo deserunt est velit laboriosam. 33 temporibus ipsam in iste quis cum illo autem quibusdam voluptas sed velit iure. Est quibusdam temporibus eos asperiores quos eos accusantium laborum vel laborum nihil. Ut rerum laudantium et cupiditate sunt et labore voluptatem.')
            ->setAvatar('https://picsum.photos/500/300');

            $manager->persist($aboutMe);

        $year = 2017;
        for ($i = 0; $i < 5; $i++) {
            $timeline = new Timeline();
            $timeline->setYear($year + $i)
                ->setDescription($faker->paragraph(5));

            $manager->persist($timeline);
        }

        $technos = ['PHP', 'Javascript', 'Symfony', 'React', 'Node', 'Bootstrap', 'WebPack Encore', 'Methode SCRUM'];
        $technosPersist = [];
        foreach ($technos as $techno) {
            $new = new Techno();
            $new->setName($techno);

            $manager->persist($new);
            $technosPersist[] = $new;
        }

        for ($i = 0; $i < 5; $i++) {
            $project = new Project();
            $project->setTitle($faker->sentence())
                ->setSlug($this->slugger->generate($project->getTitle()))
                ->setPitch($faker->paragraph(1))
                ->setDescription($faker->paragraph(3))
                ->addTechno($faker->randomElement($technosPersist))
                ->addTechno($faker->randomElement($technosPersist))
                ->addTechno($faker->randomElement($technosPersist))
                ->setGithubLink($faker->domainName())
                ->setWebsiteLink($faker->domainName())
                ->setCreatedAt($faker->datetime())
                ->setIllustration("https://picsum.photos/500/300");

            for ($j = 0; $j < 5; $j++) {
                $illustration = new Illustration();
                $illustration->setImage('https://picsum.photos/500/300')
                    ->setProject($project);
                $manager->persist($illustration);

                $project->addGallery($illustration);
            }

            $manager->persist($project);
        }

        $manager->flush();
    }
}
