<?php

namespace App\DataFixtures;

use App\Entity\LikeDislike;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LikeDislikeFixtures extends Fixture
{
    public const LIKEDISLIKE_REFERENCE_PREFIX = 'likedislike_';

    private array $items = [
        'Coffee', 'Tea', 'Cats', 'Dogs', 'Hot Weather',
        'Cold Weather', 'Crowded Places', 'Loud Music', 'Early Mornings', 'Late Nights',
        'Spicy Food', 'Fast Food', 'Seafood', 'Vegetarian Food', 'Sushi',
        'Horror Movies', 'Romantic Comedies', 'Action Movies', 'Documentaries', 'Reality TV',
        'Sports', 'Politics', 'Social Media', 'Video Games', 'Shopping',
        'Camping', 'City Life', 'Country Life', 'Public Transportation', 'Road Trips',
        'Roller Coasters', 'Karaoke', 'Surprises', 'Routines', 'Small Talk',
        'Rainy Days', 'Crowded Bars', 'Quiet Evenings', 'Working Out', 'Cooking at Home',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->items as $index => $title) {
            $item = new LikeDislike();
            $item->setTitle($title);
            $manager->persist($item);
            $this->addReference(self::LIKEDISLIKE_REFERENCE_PREFIX . $index, $item);
        }

        $manager->flush();
    }

    public static function getItemCount(): int
    {
        return 40;
    }
}
