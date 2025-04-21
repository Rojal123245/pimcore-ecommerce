<?php

namespace App\Command;

use Faker\Factory;
use Pimcore\Console\AbstractCommand;
use Pimcore\Model\DataObject;
use Pimcore\Model\Asset;
use Pimcore\Model\Asset\Image;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:generate-fake-products',
    description: 'Generate fake products for e-commerce with images'
)]
class GenerateFakeProductsCommand extends AbstractCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $faker = Factory::create('en_US');

        // Create product folder
        $productFolder = DataObject\Folder::getByPath('/products') ?: 
            $this->createFolder('/products');

        // Create asset folder for images
        $assetFolder = Asset\Service::createFolderByPath('/product-images');

        $categories = $this->createCategories();

        for ($i = 0; $i < 50; $i++) {
            $product = new DataObject\Product();
            $product->setKey($faker->unique()->slug);
            $product->setParentId($productFolder->getId());
            $product->setPublished(true);

            // Generate product name
            $brands = ['Apple', 'Samsung', 'Sony', 'Dell', 'HP', 'LG', 'Asus'];
            $productTypes = ['Laptop', 'Smartphone', 'Headphones', 'Tablet', 'Smartwatch', 'Camera', 'Speaker'];
            $name = sprintf('%s %s %s',
                $faker->randomElement($brands),
                $faker->randomElement($productTypes),
                $faker->word
            );
            $product->setName($name);

            // Handle image creation
            try {
                $tempDir = '/tmp';  // Use explicit /tmp directory
                if (!is_dir($tempDir)) {
                    mkdir($tempDir, 0777, true);
                }
                
                // Generate a solid color image instead of using Faker's image generator
                $width = 800;
                $height = 600;
                $image = imagecreatetruecolor($width, $height);
                
                // Generate random color
                $red = rand(0, 255);
                $green = rand(0, 255);
                $blue = rand(0, 255);
                $color = imagecolorallocate($image, $red, $green, $blue);
                
                // Fill the image
                imagefill($image, 0, 0, $color);
                
                // Add some text
                $textColor = imagecolorallocate($image, 255 - $red, 255 - $green, 255 - $blue);
                $text = $name;
                $fontSize = 5;
                $x = 10;
                $y = $height / 2;
                imagestring($image, $fontSize, $x, $y, $text, $textColor);
                
                // Save the image
                $tempImagePath = $tempDir . '/' . uniqid() . '.jpg';
                imagejpeg($image, $tempImagePath, 90);
                imagedestroy($image);

                // Generate safe filename
                $nameSlug = preg_replace('/[^a-z0-9]/i', '-', strtolower($name));
                $nameSlug = trim($nameSlug, '-');
                $nameSlug = $nameSlug ?: 'product'; // Fallback if empty
                $filename = sprintf('%s-%s.jpg', $nameSlug, uniqid());

                // Create image asset
                $image = new Image();
                $image->setParentId($assetFolder->getId());
                $image->setFilename($filename);
                $image->setData(file_get_contents($tempImagePath));
                $image->save();
                $product->setImages($image);
                unlink($tempImagePath);
            } catch (\Exception $e) {
                $output->writeln("Image error for {$name}: " . $e->getMessage());
            }

            // Set product properties
            $product->setDescription($this->generateDescription($faker));
            $product->setPrice($this->generatePrice($name));
            $product->setSku($this->generateSku($brands));
            $product->setCategory($faker->randomElement($categories));
            $product->setQuantity($faker->numberBetween(0, 150));
            $product->setFeatured($faker->boolean(20) ? 1 : 0);
            $product->setRating($this->generateRating());
            
            $product->save();

            $output->writeln(sprintf(
                "Created %s: %s - $%.2f | Stock: %d | Rating: %d/5",
                $product->getSku(),
                $product->getName(),
                $product->getPrice(),
                $product->getQuantity(),
                $product->getRating()
            ));
        }

        return 0;
    }

    private function createFolder(string $path): DataObject\Folder
    {
        $folder = new DataObject\Folder();
        $folder->setKey(basename($path));
        $folder->setParentId(1);
        $folder->save();
        return $folder;
    }

    private function generateDescription($faker): string
    {
        $features = [
            'High-performance', 'Premium quality', 'Latest technology',
            'Energy efficient', 'User-friendly interface', 'Sleek design'
        ];
        
        return sprintf("%s\n\nKey Features:\n- %s\n- %s\n- %s\n\n%s",
            $faker->paragraph,
            $faker->randomElement($features),
            $faker->randomElement($features),
            $faker->randomElement($features),
            $faker->paragraph
        );
    }

    private function generatePrice(string $productName): float
    {
        $priceRanges = [
            'Laptop' => [699, 2499], 'Smartphone' => [299, 1299],
            'Headphones' => [49, 399], 'Tablet' => [199, 999],
            'Smartwatch' => [149, 699], 'Camera' => [299, 2999],
            'Speaker' => [49, 499]
        ];

        $type = explode(' ', $productName)[1];
        $range = $priceRanges[$type] ?? [10, 1000];
        return (Factory::create())->randomFloat(2, $range[0], $range[1]);
    }

    private function generateSku(array $brands): string
    {
        $faker = Factory::create();
        return strtoupper(substr($brands[array_rand($brands)], 0, 2)).
            date('Y').
            $faker->randomNumber(6, true);
    }

    private function generateRating(): int
    {
        $ratings = [5, 5, 5, 4, 4, 4, 4, 3, 3, 2, 1];
        return $ratings[array_rand($ratings)];
    }

    private function createCategories(): array
    {
        $categoryData = [
            'Electronics' => 'Consumer electronics',
            'Computers' => 'Laptops and desktops',
            'Mobile Devices' => 'Smartphones and tablets',
            'Audio' => 'Headphones and speakers',
            'Photography' => 'Cameras and gear',
            'Wearables' => 'Smartwatches',
            'Gaming' => 'Gaming equipment'
        ];

        $folder = DataObject\Folder::getByPath('/categories') ?: 
            $this->createFolder('/categories');

        $categories = [];
        foreach ($categoryData as $name => $desc) {
            $key = strtolower(str_replace(' ', '-', $name));
            
            // Try to load existing category
            $existingCategory = DataObject\Category::getByPath('/categories/' . $key);
            
            if ($existingCategory instanceof DataObject\Category) {
                $categories[] = $existingCategory;
                continue;
            }

            // Create new category only if it doesn't exist
            $category = new DataObject\Category();
            $category->setKey($key);
            $category->setParentId($folder->getId());
            $category->setPublished(true);
            $category->setName($name);
            $category->setDescription($desc);
            $category->save();
            $categories[] = $category;
        }

        return $categories;
    }
}
