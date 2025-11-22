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

use Core\Ecommerce\Model\Product;
use Illuminate\Http\Request;
use Core\Ecommerce\Model\Category;
use Illuminate\Support\Facades\Storage;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Core\Ecommerce\Repositories\CategoryRepository;

final class CategoryService
{

    /**
     * Category repository
     * @var CategoryRepository
     */
    private $categoryRepository;


    public function __construct()
    {
        $this->categoryRepository = app(CategoryRepository::class);
    }


    /**
     * Searcher
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder<Category>
     */
    public function search(Request $request)
    {
        // Start query
        $query = $this->categoryRepository->query();

        // Except ID 
        if ($request->filled('except_id')) {
            $query->where('id', "!=", $request->except_id);
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

        // Filter by published
        if ($request->filled('published')) {
            $query->where('published', $request->published);
        }

        $query->orderByDesc('updated_at');

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
        $query = $this->categoryRepository->query();

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
     * Details
     * @param string $id
     * @throws ReportError
     * @return \Core\Ecommerce\Repositories\TModel|\Core\Ecommerce\Repositories\TValue|null
     */
    public function details(string $id)
    {
        try {
            return $this->categoryRepository->find($id);
        } catch (\Throwable $th) {
            throw new ReportError(__("Error retrieving category details."), 404);
        }
    }

    /**
     * Create new category
     * @param array $data
     * @return \Core\Ecommerce\Model\Category
     */
    public function create(array $data)
    {
        $data['tag'] = Product::getTag();
        $model = $this->categoryRepository->create($data);
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
            $storagePath = $this->categoryRepository->getStorage() . '/' . $model->id;
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
     * Find by tag and name
     * @param string $name
     * @param string $tag
     * @return Category|null
     */
    public function findByName(string $name)
    {
        return $this->categoryRepository->query()
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
        return $this->categoryRepository->query()
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
    public function update(string $id, array $data)
    {
        $model = $this->categoryRepository->update($id, $data);

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
        $model = $this->categoryRepository->find($id);

        if (empty($model)) {
            throw new ReportError(__('Category not be found'), 404);
        }

        // Deny if has linked products
        if ($model->products()->exists()) {
            throw new ReportError(__('Category cannot be deleted because it has linked products.'), 400);
        }

        // Delete icon if exists
        if (!empty($model->icon)) {
            $model->icon()->delete();
        }

        // Delete storage directory
        $directory = "{$this->categoryRepository->getStorage()}/$id";

        // Delete files if exists
        if ($model->files()->exists()) {
            foreach ($model->files()->get() as $file) {
                $file->delete();
            }
        }

        // Delete directory from storage
        if (Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->deleteDirectory($directory);
        }

        // Finally delete category
        $model->delete();

        return $model;
    }
}
