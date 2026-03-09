<?php

namespace App\DataFixtures;

use App\Entity\Hobby;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HobbyFixtures extends Fixture
{
    public const HOBBY_REFERENCE_PREFIX = 'hobby_';

    private array $hobbies = [
        'Hiking', 'Photography', 'Cooking', 'Reading', 'Gaming',
        'Traveling', 'Yoga', 'Painting', 'Music', 'Dancing',
        'Cycling', 'Swimming', 'Running', 'Gardening', 'Fishing',
        'Rock Climbing', 'Kayaking', 'Surfing', 'Skiing', 'Archery',
        'Pottery', 'Knitting', 'Woodworking', 'Birdwatching', 'Astronomy',
        'Volunteering', 'Wine Tasting', 'Coffee Tasting', 'Board Games', 'Chess',
        'Martial Arts', 'Boxing', 'CrossFit', 'Meditation', 'Journaling',
        'Podcasting', 'Blogging', 'Drawing', 'Sculpting', 'Calligraphy',
        'Stand-up Comedy', 'Acting', 'Singing', 'DJing', 'Film Making',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->hobbies as $index => $title) {
            $hobby = new Hobby();
            $hobby->setTitle($title);
            $manager->persist($hobby);
            $this->addReference(self::HOBBY_REFERENCE_PREFIX . $index, $hobby);
        }

        $manager->flush();
    }

    public static function getHobbyCount(): int
    {
        return 45;
    }
}
