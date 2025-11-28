<?php

namespace Core\Ecommerce\Services;


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

use Illuminate\Support\Str;
use App\Services\FileService;
use Illuminate\Http\Request;
use Core\Ecommerce\Model\Product;
use Core\Ecommerce\Model\Variant;
use Illuminate\Support\Facades\DB;
use Core\Ecommerce\Model\Attribute;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Core\Ecommerce\Repositories\ProductRepository;


class ProductService
{
    /**
     * Product repository
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * FileService
     * @var FileService
     */
    private $fileService;


    /**
     * Construct
     */
    public function __construct()
    {
        $this->productRepository = app(ProductRepository::class);
        $this->fileService = app(FileService::class);
    }

    /**
     * Storage to save image
     * @param string $id
     * @return string
     */
    public function getStorage(string $id = '')
    {
        if (empty($id)) {
            return $this->productRepository->getStorage();
        }
        return $this->productRepository->getStorage() . '/' . $id;
    }

    /**
     * Search resources
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder<Product>
     */
    public function search(Request $request)
    {
        // Query builder
        $query = $this->productRepository->query();

        // except by product id
        if ($request->filled('except_id')) {
            $query->whereNotIn('id', $request->except_id);
        }

        // Search by category
        if ($request->filled('category')) {
            $query->whereHas(
                'category',
                function ($query) use ($request) {
                    $query->whereRaw("LOWER(name) LIKE ?", ['%' . strtolower($request->category) . '%']);
                }
            );
        }

        /**
         * Search by name
         */
        if ($request->filled('name')) {
            $query->whereRaw("LOWER(name) LIKE ?", ['%' . strtolower($request->name) . '%']);
        }

        // Search by stock
        if ($request->filled('stock')) {
            $query->whereHas(
                'variants',
                function ($query) use ($request) {
                    $operator = in_array($request->get('stock_operator'), ['=', '>', '>=', '<', '<=']) ? $request->get('stock_operator') : '=';
                    $query->where('stock', $operator, $request->stock);
                }
            );
        }

        // By price
        if ($request->filled('price')) {
            $operator = in_array($request->get('price_operator'), ['=', '>', '>=', '<', '<=']) ? $request->get('price_operator') : '=';
            $price = (int) str_replace('.', '', $request->price);


            $query->whereHas(
                'variants.price',
                function ($query) use ($price, $operator) {
                    $query->where('amount', $operator, $price);
                }
            );
        }

        // order by last updated
        $query->orderByDesc('updated_at');

        return $query;
    }


    /**
     * Search for users
     * @param \Illuminate\Http\Request $request
     * @param string $category
     * @return \Illuminate\Database\Eloquent\Builder<Product>
     */
    public function searchForUsers(Request $request, string $category = '')
    {
        // Set category
        if ($category) {
            $request->merge(['category' => $category]);
        }

        // Query
        $query = $this->productRepository->query();

        // Only published
        $query->where('published', true);

        // Only featured
        if ($request->filled("featured")) {
            $query->where('featured', $request->featured);
        }

        // Retrieve latest product
        if ($request->filled('latest')) {
            $date = now()->subDays(filter_var($request->latest, FILTER_VALIDATE_INT))->format('Y-m-d');
            $query->where('created_at', '>=', $date);
        }

        // Only last seeller
        if ($request->filled('latest_seller')) {
            $query->whereHas(
                'variants',
                function ($query) {
                    $query->whereHas(
                        'orders',
                        function ($query) {
                            $query->whereNotNull('checkout_id');
                        }
                    );
                }
            );
        }

        // By category
        if ($request->filled('category')) {
            $query->whereHas(
                'category',
                function ($query) use ($request) {
                    $search = strtolower($request->category);
                    $query->whereRaw("LOWER(name) LIKE ?", ["%{$search}%"])
                        ->orWhereRaw("LOWER(slug) LIKE ?", ["%{$search}%"]);

                    $query->orWhereHas('parent');
                }
            );
        }

        // Search by product name or slug
        if ($request->filled('name')) {
            $value = '%' . strtolower($request->name) . '%';
            $query->where(function ($q) use ($value) {
                $q->whereRaw('LOWER(name) LIKE ?', [$value])
                    ->orWhereRaw('LOWER(slug) LIKE ?', [$value]);
            });
        }


        // Search by q param
        if ($request->filled('q')) {

            $query->whereRaw(
                "LOWER(name) LIKE ?",
                ['%' . strtolower($request->q) . '%']
            );

            $query->orWhereHas(
                'category',
                function ($query) use ($request) {
                    $query->whereRaw(
                        "LOWER(name) LIKE ?",
                        ['%' . strtolower($request->q)]
                    );
                }
            );
            /*
            $query->orWhereHas(
                'tags',
                function ($query) use ($request) {
                    $query->whereRaw(
                        "LOWER(name) LIKE ?",
                        ['%' . strtolower($request->q)]
                    );
                }
            );*/
        }

        // search by tags
        /*if ($request->filled('tags')) {
            $tags = explode(',', $request->tags);

            $query->whereHas(
                'tags',
                function ($query) use ($tags) {

                    foreach ($tags as $key) {
                        $query->orWhereRaw(
                            "LOWER(name) LIKE ?",
                            ['%' . strtolower($key) . '%']
                        );
                    }
                }
            );
        }*/

        // search by attributes
        if ($request->filled('attrs')) {
            $pairs = explode(',', $request->attrs);
            $attrs = [];

            foreach ($pairs as $pair) {
                [$key, $value] = explode('=', $pair);
                $attrs[] = [
                    'key' => strtolower(trim($key)),
                    'value' => strtolower(trim($value)),
                ];
            }

            $query->whereHas('attributes', function ($q) use ($attrs) {
                $q->where(function ($sub) use ($attrs) {
                    foreach ($attrs as $attr) {
                        $sub->orWhere(function ($cond) use ($attr) {
                            $cond->whereRaw('LOWER(name) = ?', [$attr['key']])
                                ->whereRaw('LOWER(value) LIKE ?', ['%' . $attr['value'] . '%']);
                        });
                    }
                });
            });
        }

        // Search by price
        if ($request->filled('price')) {
            $query->whereHas(
                'variants.price',
                function ($query) use ($request) {
                    $price = explode(',', $request->price);
                    $min = $price[0] == 0 ? 0 : (int) $price[0] * 100;
                    $max = (int) $price[1] * 100;
                    $query->whereBetween('amount', [$min, $max]);
                }
            );
        }

        // Random values
        if ($request->has('random')) {
            $query->inRandomOrder();
        }

        return $query;
    }

    /**
     * Find product by category and product slug
     * @param string $category_slug
     * @param string $product_slug
     * @return \Core\Ecommerce\Model\Product|\Illuminate\Database\Eloquent\Builder<\Core\Ecommerce\Model\Product>|\Illuminate\Database\Eloquent\Model
     */
    public function findProductByCategoryForUser(string $category_slug, string $product_slug)
    {
        $query = $this->productRepository->query();

        $query->where('published', true);

        $query->whereHas(
            'category',
            function ($query) use ($category_slug) {
                $query->where('slug', $category_slug);
            }
        )->where('slug', $product_slug);

        return $query->firstOrFail();
    }

    /**
     * Summary of findProductByCategory
     * @param string $category_slug
     * @param string $product_slug
     * @return Product|\Illuminate\Database\Eloquent\Builder<Product>|\Illuminate\Database\Eloquent\Model
     */
    public function findProductByCategory(string $category_slug, string $product_slug)
    {
        $query = $this->productRepository->query();

        $query->whereHas(
            'category',
            function ($query) use ($category_slug) {
                $query->where('slug', $category_slug);
            }
        )->where('slug', $product_slug);

        return $query->firstOrFail();
    }


    /**
     * Create new resource
     * @param array $data
     */
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {

            // Save image in the tmp directory
            $images = $this->fileService->processImage($data['images'] ?? []);

            $model = $this->productRepository->create([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'short_description' => $data['short_description'],
                'description' => $data['description'] ?? null,
                'specification' => $data['specification'] ?? null,
                'featured' => $data['featured'] ?? false,
                'published' => $data['published'] ?? true,
                'category_id' => $data["category"],
            ]);

            $this->createOrUpdateVariants($model, $data);
            $this->createAttributes($model, $data);
            // $this->createImage($model, $data);
            //$this->createTags($model, $data);
            $this->addChildren($model, $data);

            // Save image
            $this->fileService->saveImage(
                $model,
                $images,
                $this->getStorage($model->id)
            );

            return $model;
        });
    }

    /**
     * Add children to the product
     * @param  Product $product
     * @param array $data
     * @return void
     */
    public function addChildren(Product $product, array $data)
    {
        if (!empty($data['children_id'])) {
            try {
                $product->children()->syncWithoutDetaching($data['children_id']);

            } catch (\Throwable $th) {
                Log::error($th->getMessage(), $th->getTrace());
            }
        }
    }

    /**
     * Search specific resource
     * @param string $id
     * @return Product|null
     */
    public function find(string $id)
    {
        try {
            return $this->productRepository->find($id);

        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
        }

        return null;
    }

    /**
     * Find by name
     * @param string $name
     * @return object|\Illuminate\Database\Eloquent\Builder<Product>|\Illuminate\Database\Eloquent\Model|null
     */
    public function findByName(string $name)
    {
        return $this->productRepository->findByName($name);
    }

    /**
     * Create image
     * @param \Core\Ecommerce\Model\Product $model
     * @param array $data
     * @return void
     */
    public function createImage(Product $model, array $data)
    {
        if (!empty($data['images'])) {
            $storagePath = $this->productRepository->getStorage() . '/' . $model->id;
            $visibility = 'public';

            foreach ($data['images'] as $file) {
                if ($file instanceof \Illuminate\Http\UploadedFile) {
                    $path = $file->store($storagePath, $visibility);

                    $model->files()->create([
                        'name' => pathinfo($path, PATHINFO_FILENAME),
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getClientMimeType(),
                        'extension' => $file->getClientOriginalExtension(),
                        'disk' => config('filesystems.default'),
                        'size' => $file->getSize(),
                        'visibility' => $visibility,
                        'path' => $path,
                    ]);
                }
            }
        }
    }

    /**
     * Create or update price
     * @param \Core\Ecommerce\Model\Product $product
     * @param array $data
     * @return void
     */
    public function createOrUpdateVariants(Product $product, array $data)
    {
        foreach ($data['variants'] as $key => $value) {
            // Create variant o update
            $variant = $product->variants()->updateOrCreate(
                [
                    'id' => $value['id'] ?? null
                ],
                [
                    'name' => $value['name'],
                    'stock' => $value['stock'],
                    'description' => $value['description']
                ]
            );

            // generate price data
            $price = [
                'billing_period' => config('billing.period.one_time.name'),
                'currency' => $value['currency'],
                'amount' => $value['price'],
            ];

            // Check if price exists and update
            if (!empty($variant->price)) {
                $variant->price()->updateOrCreate([
                    'id' => $variant->price->id
                ], $price);
            }

            // Create price
            $variant->price()->updateOrCreate($price);
        }
    }

    /**
     * Add attributes
     * @param \Core\Ecommerce\Model\Product $product
     * @param array $data
     * @return void
     */
    public function createAttributes(Product $product, array $data)
    {
        if (count($data['attributes'])) {
            foreach ($data['attributes'] as $key => $value) {

                $data = [
                    'name' => strtolower($value['name']),
                    'slug' => Str::slug($value['name']),
                    'type' => strtolower($value['type']),
                    'value' => strtolower($value['value']),
                    'widget' => strtolower($value['widget']),
                    'multiple' => filter_var($value['multiple'], FILTER_VALIDATE_BOOL),
                ];

                $attribute = Attribute::updateOrCreate($data);

                $product->attributes()->syncWithoutDetaching($attribute->id);
            }
        }
    }

    /**
     * Verify the stock
     * @param string $id
     * @param int $stock
     * @return Variant
     */
    public function verifyStock(string $id, int $requestedQuantity)
    {
        $model = Variant::find($id);

        if (!$model) {
            throw new ReportError(__("The product does not exist."), 404);
        }

        if ($model->stock < $requestedQuantity) {
            throw new ReportError(__(
                "Insufficient stock for :name . Available: :available, requested: :requested.",
                [
                    'name' => $model->name,
                    'available' => $model->stock,
                    'requested' => $requestedQuantity,
                ]
            ), 409);
        }

        return $model;
    }

    /**
     * Add attributes
     * @param \Core\Ecommerce\Model\Product $product
     * @param array $data
     * @return void
     */
    public function createTags(Product $product, array $data)
    {
        if (count($data['tags'])) {
            foreach ($data['tags'] as $key) {
                $data = [
                    'name' => strtolower($key['name']),
                    'slug' => Str::slug($key['name'])
                ];

                $product->tags()->updateOrCreate($data, $data);
            }
        }
    }

    /**
     * Update specific resource
     * @param string $id
     * @param array $data
     * @return Product
     */
    public function update(string $id, array $data)
    {
        DB::transaction(function () use ($id, $data) {

            $images = $this->fileService->processImage($data['images']);

            $data['category_id'] = $data['category'];

            $model = $this->find($id);
            $model->update($data);

            $this->createOrUpdateVariants($model, $data);
            $this->createAttributes($model, $data);
            //$this->createImage($model, $data);
            // $this->createTags($model, $data);
            $this->addChildren($model, $data);

            $this->fileService->saveImage(
                $model,
                $images,
                $this->getStorage($model->id)
            );

            return $model;
        });

        return $this->find($id);
    }

    /**
     * Delete specific resource
     * @param string $id
     * @return Product
     */
    public function delete(string $id)
    {
        // Retrieve object model
        $model = $this->find($id);

        // Set directory
        $directory = "{$this->productRepository->getStorage()}/$id";

        // Verify related products and remove
        if (count($model->children)) {
            $model->children()->detach();
        }

        // Delete attributes
        if (count($model->attributes)) {
            $model->attributes()->detach();
        }

        // Remove variants and prices
        if (count($model->variants)) {
            foreach ($model->variants as $variant) {

                if (!empty($variant->price)) {
                    $variant->price->delete();
                }
                $variant->delete();
            }
        }

        // Verify if it the files exists and remove
        if (count($model->files)) {
            foreach ($model->files as $file) {
                $file->delete();
            }
        }

        if (Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->deleteDirectory($directory);
        }

        $model->delete();

        return $model;
    }

    /**
     * Delete tag of the product
     * @param string $product_id
     * @param string $tag_id
     * @return Product
     */
    public function deleteTag(string $product_id, string $tag_id)
    {
        $model = $this->find($product_id);
        $model->tags()->detach($tag_id);

        return $model;
    }

    /**
     * Delete attribute of the product
     * @param string $product_id
     * @param string $attribute_id
     * @return Product
     */
    public function deleteAttribute(string $product_id, string $attribute_id)
    {
        $model = $this->find($product_id);
        $model->attributes()->detach($attribute_id);

        return $model;
    }

    /**
     * Delete variant
     * @param string $product_id
     * @param string $variant_id
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return Variant
     */
    public function deleteVariant(string $product_id, string $variant_id)
    {
        $variant = Variant::where('id', $variant_id)
            ->where('variantable_id', $product_id)
            ->first();

        if (empty($variant)) {
            throw new ReportError(__('Variant cannot be removed'), 404);
        }

        $variant->price->delete();

        $variant->delete();

        return $variant;
    }

    /**
     * Delete the child product from the product parent
     * @param string $product_id
     * @param string $children_id
     * @return Product|null
     */
    public function deleteProductRelated(string $product_id, string $children_id)
    {
        $parent = $this->find($product_id);
        $parent->children()->detach($children_id);

        return $parent;
    }
}