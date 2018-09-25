<?php

namespace ShowcaseBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use ShowcaseBundle\Entity\Item;

class ItemFixtures implements ORMFixtureInterface
{
    private const COUNT_OF_ITEMS = 20;

    /** @var Factory */
    private $faker;

    /**
     * ItemFixtures constructor.
     */
    public function __construct()
    {
        $this->faker = Factory::create();
    }

    /**
     * Load fixture
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < self::COUNT_OF_ITEMS; $i++) {
            $item = new Item();
            $item->setName(ucfirst(implode(' ', $this->faker->words(3))));
            $item->setDescription($this->faker->realText(300));
            $item->setImage($this->faker->imageUrl());
            $item->setPrice(mt_rand(10, 1000));
            $item->setCount(mt_rand(1, 100));
            $manager->persist($item);
        }

        $manager->flush();
    }
}