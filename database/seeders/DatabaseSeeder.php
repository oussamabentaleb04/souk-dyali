<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Region;
use App\Models\SellerProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Users (password for all: "password")
        $admin = User::create(['name' => 'Admin', 'email' => 'admin@soukdyali.ma', 'password' => Hash::make('password'), 'phone' => '0600000001', 'role' => 'admin']);
        $sellerUser = User::create(['name' => 'Fatima Zahra', 'email' => 'seller@soukdyali.ma', 'password' => Hash::make('password'), 'phone' => '0600000002', 'role' => 'seller']);
        User::create(['name' => 'Test Buyer', 'email' => 'buyer@soukdyali.ma', 'password' => Hash::make('password'), 'phone' => '0600000003', 'role' => 'buyer']);

        // Regions
        $regions = [];
        foreach (['Fès', 'Marrakech', 'Essaouira', 'Chefchaouen', 'Rabat', 'Tetouan', 'Ouarzazate'] as $name) {
            $regions[$name] = Region::create(['name' => $name, 'slug' => Str::slug($name)]);
        }

        // Categories
        $categories = [];
        $catData = [
            'Pottery & Ceramics' => '🏺',
            'Leather Goods' => '👜',
            'Argan & Cosmetics' => '🧴',
            'Spices & Food' => '🌶️',
            'Jewelry & Accessories' => '💍',
            'Textiles & Rugs' => '🧵',
            'Home Decor' => '🏠',
            'Woodwork' => '🪵',
            'Art & Crafts' => '🎨',
        ];
        foreach ($catData as $name => $icon) {
            $categories[$name] = Category::create(['name' => $name, 'slug' => Str::slug($name), 'icon' => $icon]);
        }

        // One approved seller profile
        $seller = SellerProfile::create([
            'user_id' => $sellerUser->id,
            'shop_name' => 'Atelier Fatima Zahra',
            'slug' => 'atelier-fatima-zahra',
            'description' => 'Handmade pottery and leather goods from Fès, passed down through three generations of artisans.',
            'region' => 'Fès',
            'status' => 'approved',
        ]);

        // Demo products: title, category, region, price, stock, description
        $products = [
            ['Blue Fès Tagine', 'Pottery & Ceramics', 'Fès', 320, 15, 'Hand-painted ceramic tagine in the traditional Fès blue pattern.'],
            ['Marrakech Leather Pouf', 'Leather Goods', 'Marrakech', 450, 8, 'Genuine leather pouf, hand-stitched, stuffed with natural fibers.'],
            ['Pure Argan Oil 250ml', 'Argan & Cosmetics', 'Essaouira', 120, 40, 'Cold-pressed argan oil, sourced directly from Essaouira cooperatives.'],
            ['Ras el Hanout Spice Mix', 'Spices & Food', 'Marrakech', 45, 60, 'Traditional 20-spice blend, ground fresh in small batches.'],
            ['Berber Silver Necklace', 'Jewelry & Accessories', 'Ouarzazate', 280, 12, 'Handcrafted silver necklace with traditional Amazigh motifs.'],
            ['Beni Ourain Rug (small)', 'Textiles & Rugs', 'Fès', 1800, 4, 'Authentic wool Beni Ourain rug, hand-woven in the Atlas mountains.'],
            ['Moroccan Tea Glass Set', 'Home Decor', 'Fès', 90, 25, 'Set of 6 gold-painted mint tea glasses.'],
            ['Thuya Wood Jewelry Box', 'Woodwork', 'Essaouira', 150, 10, 'Hand-carved thuya wood box with inlaid marquetry.'],
            ['Handwoven Koffa Basket', 'Art & Crafts', 'Marrakech', 60, 30, 'Colorful handwoven palm-leaf basket, made by local artisans.'],
        ];

        foreach ($products as [$title, $catName, $regionName, $price, $stock, $desc]) {
            Product::create([
                'seller_profile_id' => $seller->id,
                'category_id' => $categories[$catName]->id,
                'region_id' => $regions[$regionName]->id,
                'title' => $title,
                'slug' => Str::slug($title),
                'description' => $desc,
                'price' => $price,
                'stock' => $stock,
                'is_active' => true,
            ]);
        }
    }
}