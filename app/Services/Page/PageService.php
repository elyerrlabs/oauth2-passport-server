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

        $query->when($request->filled('name'), fn($q) => $q->whereRaw('LOWER(slug) LIKE ?', ["%" . $request->name . "%"]));

        $query->when($request->filled('index'), fn($q) => $q->where('index', $request->input('index')));

        $query->orderBy($request->filled('order_by') ? $request->order_by : 'created_at', $request->filled('order_type') ? $request->order_type : 'asc');

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
            'index' => $data['index'] ?? false
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
            'index' => $data['index'] ?? false
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
     * Reset file
     * @param string $id
     * @throws ReportError
     * @return void
     */
    public function reset(string $id)
    {
        $model = $this->pageRepository->find($id);

        if (empty($model)) {
            throw new ReportError(__("Page not found"), 404);
        }

        $draft = $this->draftPathGenerate($model->slug);
        $prod_path = $model->path;

        if (!file_exists($prod_path)) {
            throw new ReportError(__("We can't find the production file to reset"), 404);
        }

        File::copy($prod_path, $draft);
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
        $path = rtrim($this->realPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "layouts" . DIRECTORY_SEPARATOR . $name . '.blade.php';

        if (!file_exists($path)) {
            switch ($name) {
                case 'favicon': // Favicon & Icons
                    $templateFavicon = <<<BLADE
                    <!-- Favicon & Browser Icons -->
                    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
                    <!--
                    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
                    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
                    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') 
                    -->
                    <meta name="theme-color" content="#ffffff">
                    <meta name="msapplication-TileColor" content="#ffffff">
                    BLADE;
                    file_put_contents($path, $templateFavicon);
                    break;

                case 'login': // SEO Meta Tags for Login Page
                    $templateLogin = <<<BLADE
                    <!--Title-->
                    @include('layouts.parts.title', ['title' => __('Login')])
                    <!-- SEO Meta Tags - Login Page --> 
                    <meta name="description" content="@yield('description', __('Sign in to your account to access your dashboard'))">
                    <meta name="keywords" content="login, sign in, access, account, authentication">
                    <meta name="robots" content="noindex, nofollow">
                    <meta name="googlebot" content="noindex, nofollow">
                    <meta name="author" content="{{ config('app.name') }}">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <!-- Canonical URL -->
                    <link rel="canonical" href="{{ url()->current() }}">
                    <!-- Open Graph / Facebook -->
                    <meta property="og:type" content="website">
                    <meta property="og:url" content="{{ url()->current() }}">
                    <meta property="og:title" content="@yield('title', __('Login') . ' - ' . config('app.name'))">
                    <meta property="og:description" content="{{ __('Sign in to your account') }}">
                    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">
                    <meta property="og:site_name" content="{{ config('app.name') }}">
                    <!-- Twitter Cards -->
                    <meta name="twitter:card" content="summary">
                    <meta name="twitter:url" content="{{ url()->current() }}">
                    <meta name="twitter:title" content="@yield('title', __('Login') . ' - ' . config('app.name'))">
                    <meta name="twitter:description" content="{{ __('Sign in to your account') }}">
                    <meta name="twitter:image" content="{{ asset('images/twitter-image.jpg') }}">
                    <!-- Security & Additional Meta -->
                    <meta name="csrf-token" content="{{ csrf_token() }}">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <meta name="referrer" content="strict-origin-when-cross-origin">
                    BLADE;
                    file_put_contents($path, $templateLogin);
                    break;

                case 'register': // SEO Meta Tags for Register Page
                    $templateRegister = <<<BLADE
                    <!--Title-->
                    @include('layouts.parts.title', ['title' => __('Register')])
                    <!-- SEO Meta Tags - Register Page -->
                    <title>@yield('title', __('Register') . ' - ' . config('app.name'))</title>
                    <meta name="description" content="@yield('description', __('Create a new account to get started with our services'))">
                    <meta name="keywords" content="register, sign up, create account, join, registration">
                    <meta name="robots" content="noindex, nofollow">
                    <meta name="googlebot" content="noindex, nofollow">
                    <meta name="author" content="{{ config('app.name') }}">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">

                    <!-- Canonical URL -->
                    <link rel="canonical" href="{{ url()->current() }}">

                    <!-- Open Graph / Facebook -->
                    <meta property="og:type" content="website">
                    <meta property="og:url" content="{{ url()->current() }}">
                    <meta property="og:title" content="@yield('title', __('Register') . ' - ' . config('app.name'))">
                    <meta property="og:description" content="{{ __('Join us today and create your account') }}">
                    <meta property="og:image" content="{{ asset('images/og-register.jpg') }}">
                    <meta property="og:site_name" content="{{ config('app.name') }}">

                    <!-- Twitter Cards -->
                    <meta name="twitter:card" content="summary">
                    <meta name="twitter:url" content="{{ url()->current() }}">
                    <meta name="twitter:title" content="@yield('title', __('Register') . ' - ' . config('app.name'))">
                    <meta name="twitter:description" content="{{ __('Create your account now') }}">
                    <meta name="twitter:image" content="{{ asset('images/twitter-register.jpg') }}">

                    <!-- Security -->
                    <meta name="csrf-token" content="{{ csrf_token() }}">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    BLADE;
                    file_put_contents($path, $templateRegister);
                    break;

                case 'forgot-password': // SEO Meta Tags for Forgot Password Page
                    $templateForgotPassword = <<<BLADE
                    <!--Title-->
                    @include('layouts.parts.title', ['title' => __('Forgot password')])
                    <!-- SEO Meta Tags - Forgot Password Page -->
                    <meta name="description" content="@yield('description', __('Reset your password easily. Enter your email to receive password reset instructions.'))">
                    <meta name="keywords" content="forgot password, reset password, recover password, password reset">
                    <meta name="robots" content="noindex, nofollow">
                    <meta name="googlebot" content="noindex, nofollow">
                    <meta name="author" content="{{ config('app.name') }}">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">

                    <!-- Canonical URL -->
                    <link rel="canonical" href="{{ url()->current() }}">

                    <!-- Open Graph / Facebook -->
                    <meta property="og:type" content="website">
                    <meta property="og:url" content="{{ url()->current() }}">
                    <meta property="og:title" content="@yield('title', __('Forgot Password') . ' - ' . config('app.name'))">
                    <meta property="og:description" content="{{ __('Reset your password in just a few steps') }}">
                    <meta property="og:image" content="{{ asset('images/og-password.jpg') }}">
                    <meta property="og:site_name" content="{{ config('app.name') }}">

                    <!-- Twitter Cards -->
                    <meta name="twitter:card" content="summary">
                    <meta name="twitter:url" content="{{ url()->current() }}">
                    <meta name="twitter:title" content="@yield('title', __('Forgot Password') . ' - ' . config('app.name'))">
                    <meta name="twitter:description" content="{{ __('Reset your password instructions') }}">
                    <meta name="twitter:image" content="{{ asset('images/twitter-password.jpg') }}">

                    <!-- Security Meta -->
                    <meta name="csrf-token" content="{{ csrf_token() }}">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    BLADE;
                    file_put_contents($path, $templateForgotPassword);
                    break;

                case 'plans': // SEO Meta Tags for Plans/Pricing Page
                    $templatePlans = <<<BLADE
                    <!--Title-->
                    @include('layouts.parts.title', ['title' => __('Plans')])
                    <!-- SEO Meta Tags - Plans & Pricing Page -->
                    <meta name="description" content="@yield('description', __('Choose the perfect plan for your needs. Compare features and pricing to find the best option.'))">
                    <meta name="keywords" content="plans, pricing, subscription, packages, pricing plans, membership">
                    <meta name="robots" content="index, follow">
                    <meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large">
                    <meta name="author" content="{{ config('app.name') }}">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">

                    <!-- Canonical URL -->
                    <link rel="canonical" href="{{ url()->current() }}">

                    <!-- Open Graph / Facebook -->
                    <meta property="og:type" content="website">
                    <meta property="og:url" content="{{ url()->current() }}">
                    <meta property="og:title" content="@yield('title', __('Plans & Pricing') . ' - ' . config('app.name'))">
                    <meta property="og:description" content="{{ __('Compare our plans and choose the best option for you') }}">
                    <meta property="og:image" content="{{ asset('images/og-pricing.jpg') }}">
                    <meta property="og:image:alt" content="{{ __('Pricing plans comparison') }}">
                    <meta property="og:site_name" content="{{ config('app.name') }}">
                    <meta property="og:price:amount" content="@yield('price', '0')">
                    <meta property="og:price:currency" content="{{ config('app.currency', 'USD') }}">

                    <!-- Twitter Cards -->
                    <meta name="twitter:card" content="summary_large_image">
                    <meta name="twitter:url" content="{{ url()->current() }}">
                    <meta name="twitter:title" content="@yield('title', __('Plans & Pricing') . ' - ' . config('app.name'))">
                    <meta name="twitter:description" content="{{ __('Find the perfect plan for your needs') }}">
                    <meta name="twitter:image" content="{{ asset('images/twitter-pricing.jpg') }}">
                    <meta name="twitter:image:alt" content="{{ __('Pricing plans') }}">

                    <!-- Additional SEO Meta --> 
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <link rel="alternate" hreflang="x-default" href="{{ url()->current() }}">
                    BLADE;
                    file_put_contents($path, $templatePlans);
                    break;
                case 'privacy':
                    $templatePrivacy = <<<BLADE
                    <style nonce="{{ \$nonce }}">
                        .privacy-modal {
                            display: none;
                            position: fixed;
                            bottom: 0;
                            left: 0;
                            right: 0;
                            z-index: 50;
                        }

                        .privacy-modal.active {
                            display: block;
                        }

                        .privacy-modal__container {
                            background: white;
                            width: 100%;
                            padding: 1.25rem 1.5rem;
                            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
                            border-top: 1px solid #e5e7eb;
                        }

                        .dark .privacy-modal__container {
                            background: #1f2937;
                            border-top-color: #374151;
                        }

                        .privacy-modal__content {
                            max-width: 80rem;
                            margin: 0 auto;
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            gap: 1.5rem;
                            flex-wrap: wrap;
                        }

                        .privacy-modal__text {
                            color: #4b5563;
                            font-size: 0.875rem;
                            line-height: 1.5;
                            margin: 0;
                            flex: 1;
                        }

                        .dark .privacy-modal__text {
                            color: #9ca3af;
                        }

                        .privacy-modal__button {
                            background: #2563eb;
                            color: white;
                            padding: 0.5rem 1.5rem;
                            border-radius: 0.5rem;
                            font-weight: 500;
                            font-size: 0.875rem;
                            border: none;
                            cursor: pointer;
                            transition: background 0.2s;
                            white-space: nowrap;
                        }

                        .privacy-modal__button:hover {
                            background: #1d4ed8;
                        }

                        .privacy-modal__button--cancel {
                            background: #6b7280;
                        }

                        .privacy-modal__button--cancel:hover {
                            background: #4b5563;
                        }

                        .privacy-modal__details {
                            margin-top: 0.75rem;
                            font-size: 0.75rem;
                            color: #6b7280;
                        }

                        .dark .privacy-modal__details {
                            color: #6b7280;
                        }

                        .privacy-modal__actions {
                            display: flex;
                            gap: 0.75rem;
                            align-items: center;
                        }

                        @media (max-width: 640px) {
                            .privacy-modal__content {
                                flex-direction: column;
                                text-align: center;
                            }

                            .privacy-modal__actions {
                                width: 100%;
                                flex-direction: column;
                            }

                            .privacy-modal__button {
                                width: 100%;
                            }
                        }
                    </style>

                    <div id="privacyModal" class="privacy-modal">
                        <div class="privacy-modal__container">
                            <div class="privacy-modal__content">
                                <div style="flex: 1;">
                                    <p class="privacy-modal__text">
                                        <strong>{{ __('We use essential cookies for the platform to function properly:') }}</strong>
                                    </p>
                                    <p class="privacy-modal__text" style="margin-top: 0.5rem;">
                                    • <strong>{{config('session.xcsrf-token')}}</strong> —
                                        {{ __('Protects your session against cross-site request forgery attacks. This cookie is required for all form submissions and API requests.') }}
                                    </p>
                                    <p class="privacy-modal__text">
                                        • <strong>{{ config('session.cookie')}}</strong> —
                                        {{ __('Maintains your authenticated session while you navigate the platform. Without it, you would need to log in on every page.') }}
                                    </p>
                                    <p class="privacy-modal__text">
                                        • <strong>{{ config('system.cookie_name')}}</strong> —
                                        {{ __('Essential for API communication between the frontend and Laravel Passport. Enables secure OAuth2 authentication for your own APIs.') }}
                                    </p>
                                    <p class="privacy-modal__text" style="margin-top: 0.75rem;">
                                        {{ __('No personal data is collected, shared, or sold. These cookies are strictly necessary and cannot be disabled.') }}
                                    </p>
                                    <div class="privacy-modal__details">
                                        <span>{{ __('By continuing, you accept the use of these essential cookies.') }}</span>
                                    </div>
                                </div>
                                <div class="privacy-modal__actions">
                                    <button id="privacyCancel" class="privacy-modal__button privacy-modal__button--cancel">
                                        {{ __('Cancel') }}
                                    </button>
                                    <button id="privacyAccept" class="privacy-modal__button">
                                        {{ __('Accept') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script nonce="{{ \$nonce }}">
                        document.addEventListener('DOMContentLoaded', function() {
                            const modal = document.getElementById('privacyModal');
                            const acceptBtn = document.getElementById('privacyAccept');
                            const cancelBtn = document.getElementById('privacyCancel');

                            if (!localStorage.getItem('privacyAccepted')) {
                                setTimeout(() => {
                                    modal.classList.add('active');
                                }, 1000);
                            }

                            acceptBtn.addEventListener('click', function() {
                                modal.classList.remove('active');
                                localStorage.setItem('privacyAccepted', 'true');
                            });

                            cancelBtn.addEventListener('click', function() {
                                window.location.href = 'https://www.google.com';
                            });
                        });
                    </script>
                    BLADE;
                    file_put_contents($path, $templatePrivacy);
                    break;
                case 'fonts':
                    $fonts = <<<BLADE
                    <!-- Google Fonts - Inter -->
                    <link nonce="{{ \$nonce }}" rel="preconnect" href="https://fonts.googleapis.com">
                    <link nonce="{{ \$nonce }}" rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                    <link nonce="{{ \$nonce }}" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
                        rel="stylesheet">

                    <!-- O alternativas modernas -->
                    <!--
                    <link nonce="{{ \$nonce }}" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
                    <link nonce="{{ \$nonce }}" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
                    <link nonce="{{ \$nonce }}" href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
                    --> 
                    BLADE;
                    file_put_contents($path, $fonts);
                    break;
                case 'terms-and-conditions':
                    $content = <<<BLADE
                    <p>Welcome to our website. By accessing or using this website, you agree to comply with and be bound by the following terms and conditions. These terms govern your use of the website and all services provided.</p>
                    <h3>Use of Website</h3>
                    <p>You may use the website for lawful purposes only. You must not engage in any activity that may damage, disable, or interfere with the proper functioning of the website.</p>
                    <h3>User Obligations</h3>
                    <p>All users are expected to provide accurate information, maintain the confidentiality of their account, and avoid prohibited activities such as spamming, hacking, or distributing malware.</p>
                    <h3>Intellectual Property</h3>
                    <p>All content, trademarks, and logos on this website are the property of the company. Unauthorized use or reproduction is strictly prohibited.</p>
                    <h3>Limitation of Liability</h3>
                    <p>The company is not liable for any direct, indirect, or consequential damages arising from the use of this website. Users access and use the site at their own risk.</p>
                    <h3>Changes to Terms</h3>
                    <p>We reserve the right to modify these Terms and Conditions at any time. Changes will be posted on this page, and continued use constitutes acceptance of the new terms.</p>   
                    BLADE;
                    file_put_contents($path, $content);
                    break;
                case 'policies-of-privacy':
                    $content = <<<BLADE
                    <p>We value your privacy and are committed to protecting your personal information. This Privacy Policy explains how we collect, use, and safeguard your data when you visit our website.</p>
                    <h3>Information Collection</h3>
                    <p>We may collect information such as your name, email address, and usage data when you interact with our website. This data is used solely for providing and improving our services.</p>
                    <h3>Use of Cookies</h3>
                    <p>Our website uses essential cookies to maintain active sessions and enable basic functionalities. We do not use cookies to track your behavior or for advertising purposes.</p>
                    <h3>Data Protection</h3>
                    <p>We implement appropriate security measures to protect your personal information from unauthorized access, alteration, disclosure, or destruction.</p>
                    <h3>Third-Party Sharing</h3>
                    <p>We do not sell, trade, or otherwise transfer your personal data to outside parties. Any third-party services integrated with our website comply with their own privacy policies.</p>
                    <h3>Your Rights</h3>
                    <p>You have the right to access, correct, or request deletion of your personal data. To exercise these rights, please contact our support team.</p>
                    <h3>Policy Updates</h3>
                    <p>We may update this Privacy Policy periodically. The latest version will always be available on our website, and continued use indicates acceptance of any changes.</p>
                    BLADE;
                    file_put_contents($path, $content);
                    break;
                case 'policies-of-cookies':
                    $content = <<<BLADE
                    <p class="mb-3">Our website uses cookies to ensure the proper functioning of the site and to maintain your session while you navigate through different pages. These cookies are essential for providing a seamless and secure user experience.</p>
                    <p class="mb-3">We want to emphasize that our website does <strong>not</strong> use any cookies to track your browsing behavior, monitor your activity, or collect personal data for marketing purposes. All cookies implemented are strictly technical and functional in nature.</p>
                    <p class="mb-3">Specifically, the cookies we use are designed to:</p>
                    <ul class="list-disc list-inside mb-3">
                        <li>Keep you logged in during your session.</li>
                        <li>Remember your selections and preferences while navigating the site.</li>
                        <li>Enable basic functionalities such as form submissions and page navigation without errors.</li>
                    </ul>
                    <p class="mb-3">No third-party tracking or advertising cookies are deployed by our system. Your interactions with our website remain private, and no external entity receives data about your behavior on our platform.</p>
                    <p class="mb-3">By using our website, you can rest assured that your privacy is fully respected and that any cookies stored in your browser are strictly necessary for the website's functionality. If you choose to clear cookies from your browser, you may need to log in again or lose temporary preferences.</p>
                    <p class="mb-3">We are committed to transparency and maintaining a safe and secure environment for all users. Should you have any questions regarding our use of cookies or website functionality, please do not hesitate to contact our support team.</p>
                    <p class="text-sm text-gray-500 mt-4">Last updated: September 30, 2025.</p>
                    BLADE;
                    file_put_contents($path, $content);
                    break;
                default:
                    break;
            }
        }

        return $path;
    }

    /**
     * Index pages
     * @return void
     */
    public function indexPages()
    {
        $this->pageRepository->query()->where('index', true)->chunk(1000, function ($chunk, $index) {

            $filename = "posts_{$index}.xml";
            // public path
            $path = public_path("sitemaps/{$filename}");
            // sitemap url
            $url = ltrim(config('app.url'), '/') . "/sitemaps/$filename";

            // Remove file and url
            if (File::exists($path)) {
                $this->sitemapService->remove($url);
                File::delete($path);
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

    public function renderDraftError($e, $page)
    {
        return response()->view('errors.builder', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => collect($e->getTrace())
                ->take(10)
                ->toArray(),
            'page' => $page
        ], 500);
    }
}
