<?php

namespace Database\Seeders;

/**
 * Copyright (c) 2025 Elvis Yerel Roman Concha
 *
 * This file is part of an open source project licensed under the
 * "NON-COMMERCIAL USE LICENSE - OPEN SOURCE PROJECT" (Effective Date: 2025-08-03).
 *
 * You may use, study, modify, and redistribute this file for personal,
 * educational, or non-commercial research purposes only.
 *
 * Commercial use is strictly prohibited without prior written consent
 * from the author.
 *
 * Combining this software with any project licensed for commercial use
 * (such as AGPL) is not permitted without explicit authorization.
 *
 * This software supports OAuth 2.0 and OpenID Connect.
 *
 * Author Contact: yerel9212@yahoo.es
 *
 * SPDX-License-Identifier: LicenseRef-NC-Open-Source-Project
 */

use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Core\Ecommerce\Services\CategoryService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EcommerceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = app(CategoryService::class);

        // -----------------------------------------
        // 1. Read local images
        // -----------------------------------------
        $uploadDir = storage_path("app/upload");

        if (!is_dir($uploadDir)) {
            throw new \Exception("The folder /storage/app/upload does not exist.");
        }

        $imagePool = collect(scandir($uploadDir))
            ->filter(fn($f) => preg_match('/\.(jpg|jpeg|png|webp)$/i', $f))
            ->map(fn($f) => "{$uploadDir}/{$f}")
            ->values()
            ->toArray();

        if (empty($imagePool)) {
            throw new \Exception("No images found in storage/app/upload.");
        }

        // -----------------------------------------
        // 2. Category Data (ALL IN ENGLISH)
        // -----------------------------------------
        $data = [

            // Existing + Improved
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'tag' => 'tech,gadgets,devices',
                'description' => 'Latest tech products, electronic devices, and smart accessories.',
                'featured' => true,
                'published' => true,
            ],
            [
                'name' => 'Home & Kitchen',
                'slug' => 'home-kitchen',
                'tag' => 'home,kitchen,appliances',
                'description' => 'Home essentials, kitchen tools, cookware, and small appliances.',
                'featured' => false,
                'published' => true,
            ],
            [
                'name' => 'Fashion',
                'slug' => 'fashion',
                'tag' => 'clothing,style,apparel',
                'description' => 'Men, women and kids apparel, footwear, and fashion accessories.',
                'featured' => true,
                'published' => true,
            ],
            [
                'name' => 'Beauty & Personal Care',
                'slug' => 'beauty-personal-care',
                'tag' => 'beauty,skincare,care',
                'description' => 'Cosmetics, skincare, grooming kits, and personal care items.',
                'featured' => false,
                'published' => true,
            ],
            [
                'name' => 'Sports & Outdoors',
                'slug' => 'sports-outdoors',
                'tag' => 'sports,outdoors,fitness',
                'description' => 'Outdoor gear, sports accessories, and fitness equipment.',
                'featured' => false,
                'published' => true,
            ],
            [
                'name' => 'Toys & Games',
                'slug' => 'toys-games',
                'tag' => 'toys,games,children',
                'description' => 'Action figures, board games, educational toys, and puzzles.',
                'featured' => true,
                'published' => true,
            ],
            [
                'name' => 'Automotive',
                'slug' => 'automotive',
                'tag' => 'auto,cars,vehicles',
                'description' => 'Vehicle accessories, car tools, and automotive essentials.',
                'featured' => false,
                'published' => true,
            ],
            [
                'name' => 'Books',
                'slug' => 'books',
                'tag' => 'books,reading,library',
                'description' => 'Fiction, non-fiction, educational books, and e-books.',
                'featured' => false,
                'published' => true,
            ],
            [
                'name' => 'Health & Wellness',
                'slug' => 'health-wellness',
                'tag' => 'health,wellness,vitamins',
                'description' => 'Supplements, wellness products, first aid, and health essentials.',
                'featured' => false,
                'published' => true,
            ],
            [
                'name' => 'Pets',
                'slug' => 'pets',
                'tag' => 'pets,animals,pet-care',
                'description' => 'Pet food, toys, grooming supplies and accessories.',
                'featured' => true,
                'published' => true,
            ],

            // New Categories (All in English, curated and realistic)
            [
                'name' => 'Computers & Accessories',
                'slug' => 'computers-accessories',
                'tag' => 'computers,laptops,pc',
                'description' => 'Laptops, desktops, monitors, and computer accessories.',
                'featured' => true,
                'published' => true,
            ],
            [
                'name' => 'Mobile Phones',
                'slug' => 'mobile-phones',
                'tag' => 'phones,smartphones,mobile',
                'description' => 'Smartphones, cases, chargers, and mobile accessories.',
                'featured' => true,
                'published' => true,
            ],
            [
                'name' => 'Smart Home',
                'slug' => 'smart-home',
                'tag' => 'smart,home,automation',
                'description' => 'Smart bulbs, cameras, sensors, and automation gadgets.',
                'featured' => false,
                'published' => true,
            ],
            [
                'name' => 'Office Supplies',
                'slug' => 'office-supplies',
                'tag' => 'office,work,stationery',
                'description' => 'Office furniture, stationery, organization tools.',
                'featured' => false,
                'published' => true,
            ],
            [
                'name' => 'Gaming',
                'slug' => 'gaming',
                'tag' => 'gaming,consoles,controllers',
                'description' => 'Consoles, gaming accessories, and PC gaming hardware.',
                'featured' => true,
                'published' => true,
            ],
            [
                'name' => 'Baby Products',
                'slug' => 'baby-products',
                'tag' => 'baby,kids,newborn',
                'description' => 'Baby care, strollers, toys, diapers and essentials.',
                'featured' => false,
                'published' => true,
            ],
            [
                'name' => 'Groceries',
                'slug' => 'groceries',
                'tag' => 'food,supermarket,groceries',
                'description' => 'Everyday food items, beverages, snacks, and household goods.',
                'featured' => false,
                'published' => true,
            ],
            [
                'name' => 'Furniture',
                'slug' => 'furniture',
                'tag' => 'furniture,home,decor',
                'description' => 'Living room, bedroom, and office furniture.',
                'featured' => false,
                'published' => true,
            ],
            [
                'name' => 'Jewelry & Accessories',
                'slug' => 'jewelry-accessories',
                'tag' => 'jewelry,accessories,style',
                'description' => 'Rings, necklaces, bracelets, sunglasses and more.',
                'featured' => false,
                'published' => true,
            ],
            [
                'name' => 'Music & Instruments',
                'slug' => 'music-instruments',
                'tag' => 'music,instruments,guitars',
                'description' => 'Guitars, keyboards, audio equipment, and music accessories.',
                'featured' => false,
                'published' => true,
            ],
            [
                'name' => 'Art & Crafts',
                'slug' => 'art-crafts',
                'tag' => 'art,crafts,diy',
                'description' => 'Art supplies, craft kits, and DIY materials.',
                'featured' => false,
                'published' => true,
            ],
            [
                'name' => 'Travel & Luggage',
                'slug' => 'travel-luggage',
                'tag' => 'travel,luggage,bags',
                'description' => 'Luggage, backpacks, and travel accessories.',
                'featured' => false,
                'published' => true,
            ],
            [
                'name' => 'Industrial Tools',
                'slug' => 'industrial-tools',
                'tag' => 'industrial,tools,hardware',
                'description' => 'Professional tools, heavy equipment accessories and hardware.',
                'featured' => false,
                'published' => true,
            ],
            [
                'name' => 'Cleaning Supplies',
                'slug' => 'cleaning-supplies',
                'tag' => 'cleaning,home,essentials',
                'description' => 'Home cleaning products, detergents, and tools.',
                'featured' => false,
                'published' => true,
            ],
        ];

        // -----------------------------------------
        // 3. Create categories with random images
        // -----------------------------------------
        foreach ($data as $item) {

            $selectedImages = [];
            $take = rand(1, 2);

            for ($x = 1; $x <= $take; $x++) {
                $imgPath = $imagePool[array_rand($imagePool)];

                $selectedImages[] = new UploadedFile(
                    $imgPath,
                    "{$item['slug']}_{$x}.jpg",
                    mime_content_type($imgPath),
                    null,
                    true
                );
            }

            $item['images'] = $selectedImages;

            $product->create($item);
        }
    }
}
