<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    protected $slugger;
    protected $hasher;

    public function __construct(SluggerInterface $slugger, UserPasswordHasherInterface $hasher)
    {
        $this->slugger = $slugger;
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Factory::create('fr_FR');
        $faker->addProvider(new \Liior\Faker\Prices($faker));
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));
        $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider($faker));

        $admin = new User();

        $hash = $this->hasher->hashPassword($admin, 'password');

        $admin
            ->setEmail('admin@gmail.com')
            ->setPassword($hash)
            ->setFullName('Admin')
            ->setRoles(['ROLE_ADMIN'])
        ;

        $manager->persist($admin);

        $users = [];

        for ($u = 0; $u < 5; ++$u) {
            $user = new User();

            $hash = $this->hasher->hashPassword($user, 'password');

            $user
                ->setEmail("user{$u}@gmail.com")
                ->setFullName($faker->name())
                ->setPassword($hash)
            ;

            $users[] = $user;

            $manager->persist($user);
        }

        $products = [];

        for ($c = 0; $c < 3; ++$c) {
            $category = new Category();
            $category
                ->setName($faker->department())
                ->setSlug(strtolower($this->slugger->slug($category->getName())))
            ;

            for ($i = 0; $i < mt_rand(15, 20); ++$i) {
                $product = new Product();
                $product
                    ->setName($faker->productName())
                    ->setPrice($faker->price($min = 1000, $max = 20000, $psychologicalPrice = true, $decimals = false))
                    ->setSlug(strtolower($this->slugger->slug($product->getName())))
                    ->setCategory($category)
                    ->setShortDescription($faker->paragraph())
                    ->setMainPicture($faker->imageUrl(400, 400, true))
            ;
                $products[] = $product;
                $manager->persist($product);
            }

            $manager->persist($category);
        }

        for ($p = 0; $p < mt_rand(20, 40); ++$p) {
            $purchase = new Purchase();
            $purchase
                ->setFullName($faker->name)
                ->setAddress($faker->streetAddress)
                ->setPostalCode($faker->postcode)
                ->setCity($faker->city)
                ->setUser($faker->randomElement($users))
                ->setTotal(mt_rand(2000, 30000))
                ->setPurchasedAt($faker->dateTimeBetween('-6 months'))
            ;

            $selectedProducts = $faker->randomElements($products, mt_rand(3, 5));

            foreach ($selectedProducts as $product) {
                $purchaseItem = new PurchaseItem();
                $purchaseItem
                    ->setProduct($product)
                    ->setQuantity(mt_rand(1, 3))
                    ->setProductName($product->getName())
                    ->setProductPrice($product->getPrice())
                    ->setTotal(
                        $purchaseItem->getProductPrice() * $purchaseItem->getQuantity()
                    )
                    ->setPurchase($purchase)
                ;

                $manager->persist($purchaseItem);
            }

            if ($faker->boolean(90)) {
                $purchase->setStatus(Purchase::STATUS_PAID);
            }

            $manager->persist($purchase);
        }

        $manager->flush();
    }
}
