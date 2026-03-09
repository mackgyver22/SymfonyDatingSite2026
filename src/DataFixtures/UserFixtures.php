<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\State;
use App\Entity\User;
use App\Entity\UserHobby;
use App\Entity\UserLikeDislike;
use App\Entity\UserProfile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private Connection $connection,
    ) {}

    public function getDependencies(): array
    {
        return [
            HobbyFixtures::class,
            LikeDislikeFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        // Load city IDs and their state IDs from the existing data
        $cityRows = $this->connection->fetchAllAssociative(
            'SELECT c.id AS city_id, c.state_id FROM city c ORDER BY c.id'
        );
        $cityCount = count($cityRows);

        $hobbyCount = HobbyFixtures::getHobbyCount();
        $ldCount    = LikeDislikeFixtures::getItemCount();

        // 4 groups × 75 users = 300 total
        $groups = [
            ['gender' => 'male',   'looking_for' => 'female'],
            ['gender' => 'male',   'looking_for' => 'male'],
            ['gender' => 'female', 'looking_for' => 'male'],
            ['gender' => 'female', 'looking_for' => 'female'],
        ];

        $maleFirstNames = [
            'James', 'John', 'Robert', 'Michael', 'William', 'David', 'Richard', 'Joseph',
            'Thomas', 'Charles', 'Christopher', 'Daniel', 'Matthew', 'Anthony', 'Mark',
            'Donald', 'Steven', 'Paul', 'Andrew', 'Joshua', 'Kenneth', 'Kevin', 'Brian',
            'George', 'Timothy', 'Ronald', 'Edward', 'Jason', 'Jeffrey', 'Ryan',
            'Jacob', 'Gary', 'Nicholas', 'Eric', 'Jonathan', 'Stephen', 'Larry', 'Justin',
            'Scott', 'Brandon', 'Benjamin', 'Samuel', 'Raymond', 'Gregory', 'Frank',
            'Alexander', 'Patrick', 'Jack', 'Dennis', 'Jerry', 'Tyler', 'Aaron', 'Jose',
            'Adam', 'Nathan', 'Henry', 'Douglas', 'Zachary', 'Peter', 'Kyle',
            'Walter', 'Ethan', 'Jeremy', 'Harold', 'Keith', 'Christian', 'Roger', 'Noah',
            'Gerald', 'Carl', 'Terry', 'Sean', 'Austin', 'Arthur', 'Lawrence',
        ];

        $femaleFirstNames = [
            'Mary', 'Patricia', 'Jennifer', 'Linda', 'Barbara', 'Elizabeth', 'Susan',
            'Jessica', 'Sarah', 'Karen', 'Lisa', 'Nancy', 'Betty', 'Margaret', 'Sandra',
            'Ashley', 'Dorothy', 'Kimberly', 'Emily', 'Donna', 'Michelle', 'Carol',
            'Amanda', 'Melissa', 'Deborah', 'Stephanie', 'Rebecca', 'Sharon', 'Laura',
            'Cynthia', 'Kathleen', 'Amy', 'Angela', 'Shirley', 'Anna', 'Brenda',
            'Pamela', 'Emma', 'Nicole', 'Helen', 'Samantha', 'Katherine', 'Christine',
            'Debra', 'Rachel', 'Carolyn', 'Janet', 'Catherine', 'Maria', 'Heather',
            'Diane', 'Julie', 'Joyce', 'Victoria', 'Ruth', 'Virginia', 'Lauren',
            'Kelly', 'Christina', 'Joan', 'Evelyn', 'Judith', 'Andrea', 'Olivia',
            'Cheryl', 'Megan', 'Hannah', 'Jacqueline', 'Martha', 'Beverly',
            'Denise', 'Amber', 'Madison', 'Diana', 'Brittany',
        ];

        $lastNames = [
            'Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis',
            'Rodriguez', 'Martinez', 'Hernandez', 'Lopez', 'Gonzalez', 'Wilson', 'Anderson',
            'Thomas', 'Taylor', 'Moore', 'Jackson', 'Martin', 'Lee', 'Perez', 'Thompson',
            'White', 'Harris', 'Sanchez', 'Clark', 'Ramirez', 'Lewis', 'Robinson',
            'Walker', 'Young', 'Allen', 'King', 'Wright', 'Scott', 'Torres', 'Nguyen',
            'Hill', 'Flores', 'Green', 'Adams', 'Nelson', 'Baker', 'Hall', 'Rivera',
            'Campbell', 'Mitchell', 'Carter', 'Roberts', 'Gomez', 'Phillips', 'Evans',
            'Turner', 'Diaz', 'Parker', 'Cruz', 'Edwards', 'Collins', 'Reyes',
            'Stewart', 'Morris', 'Morales', 'Murphy', 'Cook', 'Rogers', 'Gutierrez',
            'Ortiz', 'Morgan', 'Cooper', 'Peterson', 'Bailey', 'Reed', 'Kelly', 'Howard',
        ];

        $userIndex = 0;

        foreach ($groups as $groupIndex => $group) {
            $firstNames = $group['gender'] === 'male' ? $maleFirstNames : $femaleFirstNames;
            $fnCount    = count($firstNames);
            $lnCount    = count($lastNames);

            for ($i = 0; $i < 75; $i++) {
                $firstName = $firstNames[$i % $fnCount];
                $lastName  = $lastNames[($i + $groupIndex * 19) % $lnCount];
                $email     = strtolower($firstName . '.' . $lastName . ($userIndex + 1) . '@example.com');

                $user = new User();
                $user->setName($firstName . ' ' . $lastName);
                $user->setEmail($email);
                $user->setRoles(['ROLE_USER']);
                $user->setPassword($this->passwordHasher->hashPassword($user, 'password123'));
                $manager->persist($user);

                // Deterministic but spread-out city selection
                $cityRow    = $cityRows[($userIndex * 193 + $groupIndex * 47) % $cityCount];
                $cityEntity = $manager->getReference(City::class, $cityRow['city_id']);
                $stateEntity = $manager->getReference(State::class, $cityRow['state_id']);

                // Age 18–55, stored as Unix timestamp
                $ageYears = 18 + ($userIndex % 38);
                $dob = (new \DateTimeImmutable("- {$ageYears} years"))->getTimestamp();

                $profile = new UserProfile();
                $profile->setUser($user);
                $profile->setGender($group['gender']);
                $profile->setLookingFor($group['looking_for']);
                $profile->setCity($cityEntity);
                $profile->setState($stateEntity);
                $profile->setDob($dob);
                $manager->persist($profile);

                // 2 distinct hobbies per user
                $h1 = ($userIndex * 7) % $hobbyCount;
                $h2 = ($userIndex * 7 + 3) % $hobbyCount;
                if ($h2 === $h1) {
                    $h2 = ($h1 + 1) % $hobbyCount;
                }

                foreach ([$h1, $h2] as $hIdx) {
                    $userHobby = new UserHobby();
                    $userHobby->setUser($user);
                    $userHobby->setHobby(
                        $this->getReference(HobbyFixtures::HOBBY_REFERENCE_PREFIX . $hIdx, \App\Entity\Hobby::class)
                    );
                    $manager->persist($userHobby);
                }

                // 2 distinct like/dislikes per user
                $ld1 = ($userIndex * 11) % $ldCount;
                $ld2 = ($userIndex * 11 + 5) % $ldCount;
                if ($ld2 === $ld1) {
                    $ld2 = ($ld1 + 1) % $ldCount;
                }

                foreach ([$ld1, $ld2] as $ldOrder => $ldIdx) {
                    $userLd = new UserLikeDislike();
                    $userLd->setUser($user);
                    $userLd->setLikeRelation(
                        $this->getReference(LikeDislikeFixtures::LIKEDISLIKE_REFERENCE_PREFIX . $ldIdx, \App\Entity\LikeDislike::class)
                    );
                    // Vary likes vs dislikes naturally across users
                    $userLd->setDoesLike(($userIndex + $ldOrder) % 2 === 0);
                    $manager->persist($userLd);
                }

                $userIndex++;
            }
        }

        $manager->flush();
    }
}
