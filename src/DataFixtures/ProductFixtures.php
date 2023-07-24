<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $description = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec hendrerit in ante aliquam maximus. Duis urna nisl, venenatis sit amet placerat sit amet, vestibulum eu ligula. Morbi quis sagittis urna. Phasellus euismod vitae libero quis blandit. Donec semper accumsan odio quis ornare. In et lobortis ex. In cursus, arcu hendrerit ultricies pretium, mi purus laoreet nunc, ac cursus erat ex vitae lectus. Aliquam a libero sit amet eros luctus aliquam. Curabitur euismod ultricies ante, eget pharetra nibh commodo ac.";

        for ($i=0; $i < 10; $i++) {
            $product = new Product();
            $product->setName("Item ".$i."")
            ->setDescription($description)
            ->setPrice(rand(10.00, 100.00))
            ->setCode("00000".$i."")
            ->setUrlimg("https://aniki-shop.com/wp-content/uploads/2022/09/t-shirt-manga-jojos-bizarre-adventure-robe-t-shirt-coton-bio-sur-jotaro-kujho-daddy-issues-teeshirt-manga-jojo-1.png");

            $manager->persist($product);
        }

        $manager->flush();
    }
}
