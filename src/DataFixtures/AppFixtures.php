<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private const PRODUCTS =
    [
        [
            'brand' => 'Samsung',
            'model' => 'S10',
            'price' => 87990,
            'reference' => 'SM-G977U',
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo eveniet quo repellendus, deserunt iste facilis rem saepe quaerat natus perferendis obcaecati veniam soluta quisquam, est pariatur recusandae. Dolorem, laudantium dignissimos.',
        ],
        [
            'brand' => 'Samsung',
            'model' => 'S20',
            'price' => 89990,
            'reference' => 'SM-GR637',
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo eveniet quo repellendus, deserunt iste facilis rem saepe quaerat natus perferendis obcaecati veniam soluta quisquam, est pariatur recusandae. Dolorem, laudantium dignissimos.',
        ],
        [
            'brand' => 'Samsung',
            'model' => 'S21',
            'price' => 99990,
            'reference' => 'SM-MD647',
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo eveniet quo repellendus, deserunt iste facilis rem saepe quaerat natus perferendis obcaecati veniam soluta quisquam, est pariatur recusandae. Dolorem, laudantium dignissimos.',
        ],
        [
            'brand' => 'Samsung',
            'model' => 'S21+',
            'price' => 104990,
            'reference' => 'SM-ZB64B',
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo eveniet quo repellendus, deserunt iste facilis rem saepe quaerat natus perferendis obcaecati veniam soluta quisquam, est pariatur recusandae. Dolorem, laudantium dignissimos.',
        ],
        [
            'brand' => 'Samsung',
            'model' => 'S21 Ultra',
            'price' => 129090,
            'color' => 'Black',
            'reference' => 'SM-2JETE',
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo eveniet quo repellendus, deserunt iste facilis rem saepe quaerat natus perferendis obcaecati veniam soluta quisquam, est pariatur recusandae. Dolorem, laudantium dignissimos.',
        ],
        [
            'brand' => 'Xiaomi',
            'model' => 'Redmi Note 8',
            'price' => 17990,
            'reference' => 'XI-465JD',
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo eveniet quo repellendus, deserunt iste facilis rem saepe quaerat natus perferendis obcaecati veniam soluta quisquam, est pariatur recusandae. Dolorem, laudantium dignissimos.',
        ],
        [
            'brand' => 'Xiaomi',
            'model' => 'Redmi Note 8 Pro',
            'price' => 20990,
            'reference' => 'XI-45RGF',
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo eveniet quo repellendus, deserunt iste facilis rem saepe quaerat natus perferendis obcaecati veniam soluta quisquam, est pariatur recusandae. Dolorem, laudantium dignissimos.',
        ],
        [
            'brand' => 'Xiaomi',
            'model' => 'Mi 10',
            'price' => 55000,
            'reference' => 'XI-RGT6S',
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo eveniet quo repellendus, deserunt iste facilis rem saepe quaerat natus perferendis obcaecati veniam soluta quisquam, est pariatur recusandae. Dolorem, laudantium dignissimos.',
        ], [
            'brand' => 'Xiaomi',
            'model' => 'Mi 10T Pro',
            'price' => 35990,
            'reference' => 'XI-345H7',
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo eveniet quo repellendus, deserunt iste facilis rem saepe quaerat natus perferendis obcaecati veniam soluta quisquam, est pariatur recusandae. Dolorem, laudantium dignissimos.',
        ],
        [
            'brand' => 'Xiaomi',
            'model' => 'Mi 10 Light',
            'price' => 27990,
            'reference' => 'XI-KF84H',
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo eveniet quo repellendus, deserunt iste facilis rem saepe quaerat natus perferendis obcaecati veniam soluta quisquam, est pariatur recusandae. Dolorem, laudantium dignissimos.',
        ],
        [
            'brand' => 'Iphone',
            'model' => 'X',
            'price' => 117990,
            'reference' => 'AP-RG26G',
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo eveniet quo repellendus, deserunt iste facilis rem saepe quaerat natus perferendis obcaecati veniam soluta quisquam, est pariatur recusandae. Dolorem, laudantium dignissimos.',
        ],
    ];

    protected $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Products
        foreach (self::PRODUCTS as $p) {
            $product = new Product();
            $product
                ->setName($p['brand'].' '.$p['model'])
                ->setBrand($p['brand'])
                ->setModel($p['model'])
                ->setPrice($p['price'])
                ->setReference($p['reference'])
                ->setDescription($p['description'])
            ;

            $manager->persist($product);
        }

        $clients = [];
        for ($c = 0; $c <= 10; ++$c) {
            $client = new Client();
            $client->setEmail("client{$c}@gmail.com");
            $client->setPassword($this->hasher->hashPassword($client, 'password'));
            $manager->persist($client);
            $clients[] = $client;
        }

        for ($u = 0; $u <= 100; ++$u) {
            $user = new User();

            $user
                ->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setEmail($faker->email())
                ->setClient($faker->randomElement($clients))
            ;

            $manager->persist($user);
        }
        $manager->flush();
    }
}
