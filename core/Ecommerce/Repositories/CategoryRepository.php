<?php

namespace Core\Ecommerce\Repositories;

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

use Core\Ecommerce\Model\Product;
use Illuminate\Http\Request;
use Core\Ecommerce\Model\Category;
use App\Repositories\Contracts\Tag;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Contracts\Contracts;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Core\Ecommerce\Transformer\Admin\CategoryTransformer;

class CategoryRepository implements Contracts, Tag
{
    /**
     * Model
     * @var Category
     */
    private $model;

    /**
     * Transformer
     * @var CategoryTransformer
     */
    public $transformer = CategoryTransformer::class;

    /**
     * Storage
     * @var string
     */
    private $storage;

    /**
     * Construct
     * @param \Core\Ecommerce\Model\Category $category
     */
    public function __construct(Category $category)
    {
        $this->model = $category;
        $this->storage = "category";
    }

    /**
     * Searcher
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder<Category>
     */
    public function search(Request $request)
    {
        $query = $this->model->query();

        // Only product categories
        $query->where('tag', (new Product())->tag);

        $query->with([
            'parent',
            'children',
            'icon',
            'files'
        ]);

        if ($request->filled('except_id')) {
            $query->where('id', "!=", $request->except_id);
        }

        if ($request->filled('name')) {
            $query->whereRaw(
                "LOWER(name) LIKE ?",
                ['%' . strtolower($request->name) . '%']
            );
        }

        if ($request->filled('featured')) {
            $query->where('featured', $request->featured);
        }

        if ($request->filled('published')) {
            $query->where('published', $request->featured);
        }

        return $query;
    }

    /**
     * search for user
     * This method is similar to search but may include additional user-specific logic.
     * It can be used to filter categories based on user preferences or permissions.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder<Category>
     */
    public function searchForUser(Request $request)
    {
        $query = $this->model->query();

        $query->where('tag', (new Product())->tag);

        $query->with([
            'parent',
            'children',
            'icon',
            'files'
        ]);

        // Show only published
        $query->where('published', true);

        //Retrieve the latest categories added
        if ($request->filled('latest')) {
            $date = now()->subDays(filter_var($request->latest, FILTER_VALIDATE_INT))->format('Y-m-d');
            $query->where('created_at', '>=', $date);
        }

        //Do not display submenus as a main menu
        if ($request->has('parent')) {
            $query->whereNull('parent_id');
        }

        // Filter by name
        if ($request->filled('name')) {
            $query->whereRaw(
                "LOWER(name) LIKE ?",
                ['%' . strtolower($request->name) . '%']
            );
        }

        // Filter by featured
        if ($request->filled('featured')) {
            $query->where('featured', $request->featured);
        }

        // Filter as random values
        if ($request->filled('random')) {
            $query->inRandomOrder();
        }

        return $query;
    }


    /**
     * Create new resource
     * @param array $data
     * @return Category
     */
    public function create(array $data)
    {
        $model = $this->model->create($data);
        $this->createIcon($model, $data);
        $this->createImage($model, $data);

        return $model;
    }

    /**
     * Create icon
     * @param \Core\Ecommerce\Model\Category $category
     * @param array $data
     * @return void
     */
    public function createIcon(Category $category, array $data)
    {
        if (!empty($data['icon'])) {
            if ($category->icon) {
                $category->icon->update([
                    'class' => $data['icon'],
                    'origin' => 'material-design',
                ]);
            } else {
                $category->icon()->create([
                    'class' => $data['icon'],
                    'origin' => 'material-design',
                ]);
            }
        }
    }

    /**
     * Create image
     * @param \Core\Ecommerce\Model\Category $model
     * @param array $data
     * @return void
     */
    public function createImage(Category $model, array $data)
    {
        if (!empty($data['images'])) {
            $storagePath = $this->storage . '/' . $model->id;
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
     * Search specific resource
     * @param string $id
     * @param string $tag
     * @return Category|null
     */
    public function findByTag(string $id, string $tag)
    {
        return $this->model->with('products', 'icon')
            ->where('id', $id)
            ->where('tag', $tag)
            ->first();
    }

    /**
     * Search specific resource
     * @param string $id
     * @return Category|null
     */
    public function find(string $id)
    {
        return $this->model->with('products', 'icon')
            ->where('id', $id)
            ->first();
    }

    /**
     * Find by tag and name
     * @param string $name
     * @param string $tag
     * @return Category|null
     */
    public function findByName(string $name)
    {
        return $this->model
            ->with('products')
            ->whereRaw(
                "LOWER(name) = ?",
                [
                    strtolower($name)
                ]
            )->first();
    }

    /**
     * Find by tag and name
     * @param string $name
     * @param string $tag
     * @return Category|null
     */
    public function findByNameTag(string $name, string $tag)
    {
        return $this->model
            ->with('products')
            ->whereRaw(
                "LOWER(name) = ? AND LOWER(tag) = ?",
                [
                    strtolower($name),
                    strtolower($tag)
                ]
            )->first();
    }

    /**
     * Update specific resource
     * @param string $id
     * @param string $model
     * @param array $data
     * @return Category
     */
    public function updateByTag(string $id, string $tag, array $data)
    {
        $model = $this->findByTag($id, $tag);
        $model->update($data);

        $this->createIcon($model, $data);
        $this->createImage($model, $data);
        return $model;
    }

    /**
     * Update specific resource
     * @param string $id
     * @param string $model
     * @param array $data
     * @return Category
     */
    public function update(string $id, array $data)
    {
        $model = $this->find($id);
        $model->update($data);

        $this->createIcon($model, $data);
        $this->createImage($model, $data);

        return $model;
    }

    /**
     * Delete specific resource
     * @param string $id
     * @return Category
     */
    public function delete(string $id)
    {
        $model = $this->find($id);

        if ($model->products()->exists()) {
            throw new ReportError(__('Category cannot be deleted because it has linked products.'), 400);
        }

        if (!empty($model->icon)) {
            $model->icon()->delete();
        }

        $directory = "{$this->storage}/$id";

        if ($model->files()->exists()) {
            foreach ($model->files()->get() as $file) {
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
     * Delete by tag
     * @param string $id
     * @param string $tag
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return Category|null
     */
    public function deleteByTag(string $id, string $tag)
    {
        $model = $this->findByTag($id, $tag);

        if ($model->products()->exists()) {
            throw new ReportError(__('Category cannot be deleted because it has linked products.'), 400);
        }

        if (!empty($model->icon)) {
            $model->icon()->delete();
        }

        $model->delete();

        return $model;
    }
}
