<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\UniqueConstraintViolationException;
use Core\Ecommerce\Services\ProductService;
use Core\Ecommerce\Model\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class EcommerceProductSeeder extends Seeder
{
    public function run(): void
    {
        $productService = app(ProductService::class);

        // ---------------------------------
        // 1. Load all images from /upload
        // ---------------------------------
        $uploadDir = storage_path('app/upload');

        if (!is_dir($uploadDir)) {
            throw new \Exception("Upload directory does not exist: {$uploadDir}");
        }

        $imagePool = collect(scandir($uploadDir))
            ->filter(fn($f) => preg_match('/\.(jpg|jpeg|png|webp)$/i', $f))
            ->map(fn($f) => $uploadDir . '/' . $f)
            ->values()
            ->toArray();

        if (empty($imagePool)) {
            throw new \Exception("No images found in /upload.");
        }

        // ---------------------------------
        // 2. Product Base Names (English Only)
        // ---------------------------------
        $baseNames = [
            'Pro Laptop',
            'UltraThin Laptop',
            'Gaming Laptop RTX',
            'Business Laptop Series',
            'Mini PC Intel Edition',
            'Mini PC AMD Edition',
            'Workstation Tower',
            'Gaming PC Tower',
            'All-in-One Desktop',
            'Gaming Mouse RGB',
            'Wireless Mouse',
            'Ergonomic Vertical Mouse',
            'Mechanical Keyboard RGB',
            'Wireless Keyboard Slim',
            'Keyboard & Mouse Combo',
            'Extended Mouse Pad',
            'Full HD Webcam',
            '4K Streaming Camera',
            'Studio USB Microphone',
            'Podcast Microphone Pro',
            'Wireless Headphones',
            'HD Headphones',
            'Over-Ear Headphones',
            'Gaming Surround Headset',
            'NVMe SSD',
            'SATA SSD',
            'Portable HDD 1TB',
            'Portable HDD 2TB',
            'Compact External HDD',
            'USB Flash Drive 128GB',
            'USB Flash Drive 64GB',
            'SDXC Card 128GB',
            'MicroSD Card 256GB',
            'Intel CPU Series',
            'AMD CPU Series',
            'ATX Motherboard',
            'Micro-ATX Motherboard',
            'RAM 8GB Module',
            'RAM 16GB Module',
            'RAM 32GB Module',
            'Power Supply 650W',
            'Modular PSU 750W',
            'Liquid Cooler RGB',
            'Ryzen CPU Cooler',
            'RGB PC Case',
            'Compact MicroATX Case',
            'Monitor 24-inch',
            'Monitor 27-inch',
            'Curved Monitor 32-inch',
            'UltraWide Monitor',
            'Gaming Monitor 144Hz',
            'Smart TV 50-inch',
            'Smart TV 55-inch',
            'OLED Smart TV 65-inch',
            'AX3000 WiFi Router',
            'Gaming Router',
            'Dual Band Router',
            'WiFi Modem',
            'WiFi Repeater',
            'Network Switch 8 Ports',
            'Network Switch 16 Ports',
            'USB Bluetooth Adapter',
            'USB WiFi Adapter',
            'Tablet Pro X',
            'Tablet 10-inch HD',
            'Android Tablet HD',
            'Kids Tablet Edition',
            'Smartphone Pro Max',
            'Smartphone Lite',
            'Smartwatch Pro',
            'Fitness Smartwatch',
            'Smart Fitness Band',
            'Fast USB-C Charger',
            'Wireless Charger',
            'Power Bank 10000mAh',
            'Power Bank 20000mAh',
            'USB-C Braided Cable',
            'Lightning Cable Original',
            'HDMI 4K Cable',
            'Laptop Stand Adjustable',
            'Phone Desk Stand',
            'Protective Phone Case',
            'Tempered Glass Screen Protector',
            'LED Desk Lamp',
            'RGB Gaming Lamp',
            'Bluetooth Speaker',
            'Smart Speaker',
            'Home Theater Sound Bar',
            'Compact Subwoofer',
            'Waterproof Portable Speaker',
            'WiFi Security Camera',
            'Smart Door Lock',
            'Smart Plug WiFi',
            'Smart LED Bulb',
            'Smart Motion Sensor',
            'Gaming Chair',
            'Ergonomic Office Chair',
            'Gaming Desk',
            'Adjustable Standing Desk',
            'Cable Management Kit',
            'Anti-Slip Floor Mat',
            'Metal File Cabinet',
            'WiFi Printer',
            'Laser Printer',
            'All-in-One Printer',
            'Black Ink Cartridge XL',
            'Color Ink Cartridge XL',
            'Compatible Toner',
            'USB Game Controller',
            'Bluetooth Gamepad',
            'USB Fingerprint Reader',
            '3-Axis Gimbal Stabilizer',
            'Mini Drone HD',
            'Professional Drone 4K',
            'Automatic Coffee Machine',
            'Pro Blender 1200W',
            'Stainless Steel Mixer',
            'Robot Vacuum Cleaner',
            'Portable Handheld Vacuum',
            'Silent Table Fan',
            'Air Purifier',
            'LED Humidifier',
            'Smart Scale',
            'Sport Watch',
            'Compact Treadmill',
            'Foldable Exercise Bike',
        ];

        // ---------------------------------
        // 3. Generate Products
        // ---------------------------------
        $count = 1000;

        for ($i = 1; $i <= $count; $i++) {

            $base = $baseNames[array_rand($baseNames)];
            $name = "{$base} Model {$i}";
            $slug = Str::slug($name);

            // Random images
            $take = rand(1, 2);
            $selectedImages = [];

            for ($x = 1; $x <= $take; $x++) {
                $original = $imagePool[array_rand($imagePool)];

                $selectedImages[] = new UploadedFile(
                    $original,
                    basename($original),
                    mime_content_type($original),
                    null,
                    true
                );
            }

            // Variants
            $variants = [];
            for ($v = 1; $v <= 4; $v++) {
                $variants[] = [
                    "name" => "Variant {$v}",
                    "stock" => rand(10, 200),
                    "price" => rand(20000, 100000),
                    "currency" => "USD",
                    "description" => "Automatic variant {$v} of product {$name}.",
                ];
            }

            // Final Data
            $data = [
                "name" => $name,
                "slug" => $slug,
                "short_description" => "<p>Short description for product {$name}.</p>",
                "description" => "<p>Detailed description for product {$name}, including features and technical attributes.</p>",
                "specification" => "<ul><li>General technical specifications for {$name}.</li></ul>",
                "category" => Category::inRandomOrder()->first()->id,
                "published" => "true",
                "featured" => "false",
                "variants" => $variants,
                "attributes" => [
                    [
                        "name" => "material",
                        "type" => "string",
                        "value" => "plastic",
                        "widget" => "checkbox",
                        "multiple" => "false",
                        "stock" => "0",
                    ]
                ],
                "images" => $selectedImages,
            ];

            try {
                $productService->create($data);
            } catch (UniqueConstraintViolationException $e) {
                // Continue without breaking
            }
        }
    }
}
