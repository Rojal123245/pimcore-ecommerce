<?php

namespace App\Command;

use Faker\Factory;
use Pimcore\Console\AbstractCommand;
use Pimcore\Model\DataObject;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:generate-fake-products',
    description: 'Generate fake products for e-commerce'
)]
class GenerateFakeProductsCommand extends AbstractCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Create faker instance with English locale
        $faker = Factory::create('en_US');
        
        // Create product folder if it doesn't exist
        $folder = DataObject\Folder::getByPath('/products');
        if (!$folder) {
            $folder = new DataObject\Folder();
            $folder->setKey('products');
            $folder->setParentId(1);
            $folder->save();
        }

        // First, ensure we have some categories
        $categories = $this->createCategories();

        // Generate 50 fake products
        for ($i = 0; $i < 50; $i++) {
            $product = new DataObject\Product();
            $product->setKey(strtolower($faker->slug));
            $product->setParentId($folder->getId());
            $product->setPublished(true);
            
            // Generate more realistic product names
            $productTypes = ['Laptop', 'Smartphone', 'Headphones', 'Tablet', 'Smartwatch', 'Camera', 'Speaker'];
            $brands = ['Apple', 'Samsung', 'Sony', 'Dell', 'HP', 'LG', 'Asus'];
            $name = $faker->randomElement($brands) . ' ' . 
                   $faker->randomElement($productTypes) . ' ' . 
                   $faker->word;
            
            // Set product data with English content
            $product->setName($name);
            
            // Generate more realistic product description
            $features = [
                'High-performance',
                'Premium quality',
                'Latest technology',
                'Energy efficient',
                'User-friendly interface',
                'Sleek design',
                'Durable construction'
            ];
            
            $description = sprintf(
                "%s\n\nKey Features:\n- %s\n- %s\n- %s\n\n%s",
                $faker->paragraph,
                $faker->randomElement($features),
                $faker->randomElement($features),
                $faker->randomElement($features),
                $faker->paragraph
            );
            
            $product->setDescription($description);
            
            // Set realistic price ranges based on product type
            $priceRanges = [
                'Laptop' => [699, 2499],
                'Smartphone' => [299, 1299],
                'Headphones' => [49, 399],
                'Tablet' => [199, 999],
                'Smartwatch' => [149, 699],
                'Camera' => [299, 2999],
                'Speaker' => [49, 499]
            ];
            
            $productType = explode(' ', $name)[1];
            $priceRange = $priceRanges[$productType] ?? [10, 1000];
            $product->setPrice($faker->randomFloat(2, $priceRange[0], $priceRange[1]));
            
            // Generate realistic SKU
            $sku = strtoupper(substr($brands[array_rand($brands)], 0, 2)) . 
                   date('Y') . 
                   $faker->randomNumber(6, true);
            $product->setSku($sku);
            
            // Set a random category from our created categories
            $randomCategory = $faker->randomElement($categories);
            $product->setCategory($randomCategory);
            
            // Set quantity (stock) - more realistic distribution
            $product->setQuantity($faker->numberBetween(0, 150));
            
            // Set featured (0 or 1) - 20% chance of being featured
            $product->setFeatured($faker->boolean(20) ? 1 : 0);
            
            // Set rating (1-5) with realistic distribution
            $ratings = [5, 5, 5, 4, 4, 4, 4, 3, 3, 2, 1]; // Weighted towards higher ratings
            $product->setRating($ratings[array_rand($ratings)]);
            
            $product->save();
            $output->writeln(sprintf(
                "Created product: %s (SKU: %s, Price: $%.2f)",
                $product->getName(),
                $product->getSku(),
                $product->getPrice()
            ));
        }

        return 0;
    }

    private function createCategories(): array
    {
        $categories = [];
        $categoryData = [
            'Electronics' => 'Consumer electronics and digital devices',
            'Computers' => 'Laptops, desktops, and computer accessories',
            'Mobile Devices' => 'Smartphones, tablets, and accessories',
            'Audio' => 'Headphones, speakers, and sound equipment',
            'Photography' => 'Cameras, lenses, and photography gear',
            'Wearables' => 'Smartwatches and fitness trackers',
            'Gaming' => 'Gaming consoles and accessories'
        ];
        
        // Create category folder if it doesn't exist
        $folder = DataObject\Folder::getByPath('/categories');
        if (!$folder) {
            $folder = new DataObject\Folder();
            $folder->setKey('categories');
            $folder->setParentId(1);
            $folder->save();
        }

        foreach ($categoryData as $name => $description) {
            // Check if category already exists
            $list = new DataObject\Category\Listing();
            $list->setCondition("name = ?", [$name]);
            $existing = $list->current();

            if ($existing) {
                $categories[] = $existing;
                continue;
            }

            // Create new category
            $category = new DataObject\Category();
            $category->setKey(strtolower(str_replace(' ', '-', $name)));
            $category->setParentId($folder->getId());
            $category->setPublished(true);
            $category->setName($name);
            $category->setDescription($description);
            $category->setFeatured(0);
            $category->save();
            
            $categories[] = $category;
        }

        return $categories;
    }
}
