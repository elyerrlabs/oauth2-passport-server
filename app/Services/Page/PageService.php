<?php

namespace App\Services\Page;

use App\Services\SiteMapService;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Repositories\Page\PageRepository;

final class PageService extends \App\Services\Page\Service
{

    protected $schema;

    /**
     * Construct
     * @param PageRepository $pageRepository
     */
    public function __construct(protected PageRepository $pageRepository, protected SiteMapService $sitemapService)
    {
        parent::__construct();
        $this->schema = base_path('resources/views/pages/layouts/schema.blade.php');
    }

    /**
     * Draft path generate
     * @param mixed $slug
     * @return string
     */
    public function draftPathGenerate($slug)
    {
        return rtrim($this->realPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "draft" . DIRECTORY_SEPARATOR . $slug . '.blade.php';
    }

    /**
     * Published path generator
     * @param mixed $slug
     * @return string
     */
    public function publishedPathGenerate($slug)
    {
        return rtrim($this->realPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "published" . DIRECTORY_SEPARATOR . $slug . '.blade.php';
    }

    /**
     * search
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder<\App\Models\Page\Page>
     */
    public function search(Request $request)
    {
        $query = $this->pageRepository->query();

        $query->when($request->filled('name'), fn() => $query->whereRaw('LOWER(slug) LIKE ?', ["%" . $request->name . "%"]));

        $query->orderBy($request->filled('order_by') ? $request->order_by : 'name', $request->filled('order_type') ? $request->order_type : 'asc');

        return $query;
    }

    /**
     * Find
     * @param string $slug
     * @return object|\App\Models\Page\Page|\stdClass
     */
    public function findPage(string $slug)
    {
        $page = $this->pageRepository->query()
            ->where('is_published', true)
            ->where('slug', $slug)
            ->firstOrFail();

        return $page;
    }

    /**
     * Edit
     * @param string $id
     * @throws ReportError
     * @return object|\App\Models\Page\Page|\stdClass|null
     */
    public function edit(string $id)
    {
        $page = $this->pageRepository->find($id);

        if (empty($page)) {
            throw new ReportError(__('Page not found'), 404);
        }

        if ($page->is_draft) {

            $drafPath = $this->draftPathGenerate($page->slug);

            if (!File::exists($drafPath)) {
                File::copy($page->path, $drafPath);
            }

            $page->content = file_get_contents($drafPath);

            $page->path = $drafPath;

        } else {
            $publishedPath = $this->publishedPathGenerate($page->slug);

            $page->content = file_get_contents($publishedPath);
        }

        return $page;
    }

    /**
     * Find page
     * @param string $id
     * @return object|\App\Models\Page\Page|\stdClass|null
     */
    public function find(string $id)
    {
        return $this->pageRepository->query()->where('id', $id)->first();
    }

    /**
     * Create page
     * @param array $data
     * @throws \Exception
     * @return \App\Models\Page\Page
     */
    public function create(array $data)
    {
        $slug = Str::slug($data['slug']);

        if (!File::exists($this->realPath)) {
            File::makeDirectory($this->realPath, 0755, true);
        }

        $path = rtrim($this->realPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "published" . DIRECTORY_SEPARATOR . $slug . '.blade.php';

        if (!File::exists($this->schema)) {
            throw new \Exception('Schema template not found');
        }

        if (!File::exists($path)) {
            File::copy($this->schema, $path);
        }

        return $this->pageRepository->create([
            'name' => $data['name'],
            'slug' => $slug,
            'path' => $path,
            'is_draft' => $data['is_draft'] ?? true,
            'is_published' => $data['is_draft'] ?? false,
        ]);
    }

    /**
     * Update page
     * @param string $id
     * @param array $data
     * @throws ReportError
     * @throws \Exception
     * @return object|\App\Models\Page\Page|\stdClass|null
     */
    public function update(string $id, array $data)
    {
        $model = $this->pageRepository->find($id);

        if (empty($model)) {
            throw new ReportError(__("Error Processing Request"), 400);
        }

        $newSlug = Str::slug($data['slug'] ?? $model->slug);
        $currentDraftPath = $this->draftPathGenerate($model->slug);
        $newDraftPath = $this->draftPathGenerate($newSlug);
        $publishedPath = $this->publishedPathGenerate($newSlug);
        $content = $data['content'] ?? null;
        $isDraft = filter_var($data['is_draft'] ?? true, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        $isDraft = $isDraft ?? false;

        if (!File::exists($currentDraftPath)) {
            if (File::exists($this->schema)) {
                File::copy($this->schema, $currentDraftPath);
            }
        }

        if ($currentDraftPath !== $newDraftPath && File::exists($currentDraftPath)) {
            File::move($currentDraftPath, $newDraftPath);
        }

        if ($content !== null) {
            $this->updateFile($newDraftPath, $content);
        }

        if (!$isDraft) {
            if (!File::exists($newDraftPath)) {
                throw new ReportError(__("Draft page cannot be found"), 404);
            }

            File::copy($newDraftPath, $publishedPath);
        }

        $this->pageRepository->update($model, [
            'name' => $data['name'] ?? $model->name,
            'slug' => $newSlug,
            'path' => $publishedPath,
            'is_published' => $data['is_published'] ?? false,
            'is_draft' => $isDraft,
        ]);

        return $model;
    }

    /**
     * Delete
     * @param string $id
     * @throws ReportError
     * @return bool
     */
    public function delete(string $id)
    {
        $model = $this->pageRepository->find($id);

        if (empty($model)) {
            throw new ReportError(__("Page not found"), 400);
        }

        if (!empty($model->path) && File::exists($model->path)) {
            File::delete($model->path);
        }

        if (!empty($draftPath = $this->draftPathGenerate($model->slug)) && File::exists($draftPath)) {
            File::delete($draftPath);
        }


        $model->delete();

        return true;
    }

    /**
     * Update file
     * @param string $path
     * @param   $content
     * @return void
     */
    public function updateFile(string $path, $content)
    {
        if (file_exists($path)) {
            File::put($path, $content);
        }
    }

    /**
     * Load layouts
     * @param string $name
     * @return bool|string
     */
    public function loadLayout(string $name)
    {
        return file_get_contents($this->loadLayoutPath($name));
    }

    public function loadLayoutPath(string $name)
    {
        return rtrim($this->realPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "layouts" . DIRECTORY_SEPARATOR . $name . '.blade.php';
    }

    /**
     * Index pages
     * @return void
     */
    public function indexPages()
    {
        $this->pageRepository->query()->chunk(1000, function ($chunk, $index) {

            $filename = "posts_{$index}.xml";
            // public path
            $path = public_path("sitemaps/{$filename}");
            // sitemap url
            $url = ltrim(config('app.url'), '/') . "/sitemaps/$filename";

            // Remove file and url
            if (\File::exists($path)) {
                $this->sitemapService->remove($url);
                \File::delete($path);
            }

            foreach ($chunk as $page) {
                $this->sitemapService->register(
                    "posts_{$index}",
                    route('pages', $page->slug),
                    null,
                    'weekly',
                    0.5
                );
            }
        });
    }
}
