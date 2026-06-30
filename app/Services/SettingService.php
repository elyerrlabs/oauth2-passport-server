<?php

namespace App\Services;

/**
 * OAuth2 Passport Server — a centralized, modular authorization server
 * implementing OAuth 2.0 and OpenID Connect specifications.
 *
 * Copyright (c) 2026 Elvis Yerel Roman Concha
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 *
 * Author: Elvis Yerel Roman Concha
 * Contact: yerel9212@yahoo.es
 *
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

use Elyerr\ApiResponse\Exceptions\ReportError;
use App\Support\CacheKeys;
use Core\User\Model\Scope;
use App\Models\OAuth\Token;
use Illuminate\Support\Str;
use App\Models\OAuth\Client;
use Illuminate\Http\Request;
use App\Models\OAuth\AuthCode;
use Laravel\Passport\Passport;
use App\Models\OAuth\RefreshToken;
use App\Repositories\SettingRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\QueryException;

class SettingService
{
    public function __construct(protected SettingRepository $settingRepository)
    {
    }

    /**
     * Cache key
     * @return string
     */
    public function cacheKey()
    {
        return "settings";
    }

    /**
     * Restore the all config keys
     * @return void
     */
    public function resetConfigKeys()
    {
        file_put_contents($this->pid(), time());
    }

    /**
     * Set a pid to reset cache
     * @return string
     */
    public function pid()
    {
        return base_path('.cache.pid');
    }

    public function ping()
    {
        Cache::store('redis')->get('ping');
    }

    /**
     * Load configs
     */
    public function loadConfigKeys()
    {
        try {
            if (file_exists($this->pid())) {
                Cache::forget(CacheKeys::config());
                unlink($this->pid());
            }

            $settings = Cache::remember(
                CacheKeys::config(),
                now()->addDays(intval(config('cache.expires', 90))),
                function () {
                    return $this->settingRepository->getConfig();
                }
            );

            foreach ($settings as $key => $value) {

                // Ignore empty values
                if ($value === null || $value === '') {
                    continue;
                }

                // Convert string numbers to string
                if (filter_var($value, FILTER_VALIDATE_INT) !== false) {
                    $value = intval($value);
                }
                // Convert to float from string number
                elseif (is_numeric($value)) {
                    $value = floatval($value);
                }

                // Set the new config
                Config::set($key, $value);
            }
        } catch (\Throwable $th) {
            Log::error('Error loading config : ' . $th->getMessage(), $th->getTrace());
        }
    }

    /**
     * Set default values
     * @return void
     */
    public function getDefaultSetting()
    {
        URL::forceScheme(env('APP_URL_SCHEME', 'https'));

        $this->loadConfigKeys();

        //Horizon cache settings
        Config::set('database.redis.horizon.url', config('database.redis.cache.url', null));
        Config::set('database.redis.horizon.host', config('database.redis.cache.host', '127.0.0.1'));
        Config::set('database.redis.horizon.username', config('database.redis.cache.username', null));
        Config::set('database.redis.horizon.password', config('database.redis.cache.password', null));
        Config::set('database.redis.horizon.port', intval(config('database.redis.cache.port', 6379)));
        Config::set('database.redis.horizon.database', intval(config('database.redis.cache.database', 0)));

        $this->getPassportSetting();
    }

    /**
     * Add default setting into the system
     * @return void
     */
    public function setDefaultKeys()
    {
        //App name
        $this->settingRepository->load('app.name', 'Oauth2 Server');
        $this->settingRepository->load('app.org_name', 'Server org');

        //expires time for reset password
        $this->settingRepository->load('auth.passwords.users.expire', 10);
        //expires time to try another request
        $this->settingRepository->load('auth.passwords.users.throttle', 10);

        $this->settingRepository->load('auth.password_timeout', 10800);

        //------------------------REDIS CONFIGURATION-------------------//
        //redis default settings
        $this->settingRepository->load('database.redis.default.url', null);
        $this->settingRepository->load('database.redis.default.host', '127.0.0.1');
        $this->settingRepository->load('database.redis.default.username', null);
        $this->settingRepository->load('database.redis.default.password', null);
        $this->settingRepository->load('database.redis.default.port', '6379');
        $this->settingRepository->load('database.redis.default.database', 0);

        //redis cache settings
        $this->settingRepository->load('database.redis.cache.url', null);
        $this->settingRepository->load('database.redis.cache.host', '127.0.0.1');
        $this->settingRepository->load('database.redis.cache.username', null);
        $this->settingRepository->load('database.redis.cache.password', null);
        $this->settingRepository->load('database.redis.cache.port', '6379');
        $this->settingRepository->load('database.redis.cache.database', 1);
        //------------------------END REDIS CONFIGURATION-------------------//

        //------------------------CACHE CONFIGURATION-------------------//
        $this->settingRepository->load('cache.default', 'file');
        $this->settingRepository->load('cache.expires', 30);
        $this->settingRepository->load('cache.prefix', Str::slug((string) env('APP_NAME', 'laravel')) . '-cache-');

        $this->settingRepository->load('cache.stores.database.connection', null);
        $this->settingRepository->load('cache.stores.database.table', 'cache');

        $this->settingRepository->load('cache.stores.redis.connection', 'cache');
        $this->settingRepository->load('cache.stores.redis.lock_connection', 'default');

        $this->settingRepository->load('cache.stores.memcached.persistent_id', null);
        $this->settingRepository->load('cache.stores.memcached.sasl.username', null);
        $this->settingRepository->load('cache.stores.memcached.sasl.password', null);
        $this->settingRepository->load('cache.stores.memcached.servers.0.host', '127.0.0.1');
        $this->settingRepository->load('cache.stores.memcached.servers.0.port', 11211);
        $this->settingRepository->load('cache.stores.memcached.servers.0.weight', 100);

        $this->settingRepository->load('cache.stores.dynamodb.key', null);
        $this->settingRepository->load('cache.stores.dynamodb.secret', null);
        $this->settingRepository->load('cache.stores.dynamodb.region', 'us-east-1');
        $this->settingRepository->load('cache.stores.dynamodb.table', 'cache');
        $this->settingRepository->load('cache.stores.dynamodb.endpoint', null);
        //------------------------END CACHE CONFIGURATION-------------------//


        //---------------------QUEUES CONFIG--------------------///
        //default queues
        $this->settingRepository->load('queue.default', 'database');

        //Sync setting
        //$this->settingRepository->load('queue.connections.sync.driver', 'sync');

        //Database settings
        //$this->settingRepository->load('queue.connections.database.driver', 'database');
        $this->settingRepository->load('queue.connections.database.table', 'jobs');
        $this->settingRepository->load('queue.connections.database.queue', 'default');
        $this->settingRepository->load('queue.connections.database.retry_after', 90);
        $this->settingRepository->load('queue.connections.database.after_commit', false);

        //beanstalkd Settings
        //$this->settingRepository->load('queue.connections.beanstalkd.driver', 'beanstalkd');
        $this->settingRepository->load('queue.connections.beanstalkd.host', 'localhost');
        $this->settingRepository->load('queue.connections.beanstalkd.queue', 'default');
        $this->settingRepository->load('queue.connections.beanstalkd.retry_after', 90);
        $this->settingRepository->load('queue.connections.beanstalkd.block_for', 0);
        $this->settingRepository->load('queue.connections.beanstalkd.after_commit', false);

        //AWS settings
        //$this->settingRepository->load('queue.connections.sqs.driver', 'sqs');
        $this->settingRepository->load('queue.connections.sqs.key', null);
        $this->settingRepository->load('queue.connections.sqs.secret', null);
        $this->settingRepository->load('queue.connections.sqs.prefix', 'https://sqs.us-east-1.amazonaws.com/your-account-id');
        $this->settingRepository->load('queue.connections.sqs.queue', 'default');
        $this->settingRepository->load('queue.connections.sqs.suffix', null);
        $this->settingRepository->load('queue.connections.sqs.region', 'us-east-1');
        $this->settingRepository->load('queue.connections.sqs.after_commit', false);

        //Redis Settings
        //$this->settingRepository->load('queue.connections.redis.driver', 'redis');
        $this->settingRepository->load('queue.connections.redis.connection', 'default');
        $this->settingRepository->load('queue.connections.redis.queue', 'default');
        $this->settingRepository->load('queue.connections.redis.retry_after', 90);
        $this->settingRepository->load('queue.connections.redis.block_for', null);
        $this->settingRepository->load('queue.connections.redis.after_commit', false);

        //Fail queue settings
        //$this->settingRepository->load('queue.failed.driver', 'database-uuids');
        //$this->settingRepository->load('queue.failed.database', 'pgsql');
        //$this->settingRepository->load('queue.failed.table', 'failed_jobs');
        //---------------------END QUEUES CONFIG--------------------///

        //----------FILESYSTEM SETTINGS------------------------------------------
        $this->settingRepository->load('filesystems.default', 'local');
        $this->settingRepository->load('filesystems.disks.local.driver', 'local');
        $this->settingRepository->load('filesystems.disks.local.root', storage_path('app'));
        $this->settingRepository->load('filesystems.disks.local.throw', false);

        //$this->settingRepository->load('filesystems.disks.public.driver', 'local');
        $this->settingRepository->load('filesystems.disks.public.root', storage_path('app/public'));
        $this->settingRepository->load('filesystems.disks.public.url', config('app.url', null) . '/storage');
        $this->settingRepository->load('filesystems.disks.public.visibility', 'public');
        $this->settingRepository->load('filesystems.disks.public.throw', false);

        //$this->settingRepository->load('filesystems.disks.s3.driver', 's3');
        $this->settingRepository->load('filesystems.disks.s3.key', null);
        $this->settingRepository->load('filesystems.disks.s3.secret', null);
        $this->settingRepository->load('filesystems.disks.s3.region', null);
        $this->settingRepository->load('filesystems.disks.s3.bucket', null);
        $this->settingRepository->load('filesystems.disks.s3.url', null);
        $this->settingRepository->load('filesystems.disks.s3.endpoint', null);
        $this->settingRepository->load('filesystems.disks.s3.use_path_style_endpoint', false);
        $this->settingRepository->load('filesystems.disks.s3.throw', false);

        $this->settingRepository->load('filesystems.links.public', public_path('storage'));
        $this->settingRepository->load('filesystems.links.storage', storage_path('app/public'));


        //-------EMAIL SETTINGS -------------------------
        $this->settingRepository->load('mail.default', 'smtp');

        //$this->settingRepository->load('mail.mailers.smtp.transport', 'smtp');
        $this->settingRepository->load('mail.mailers.smtp.host', 'smtp.mailgun.org');
        $this->settingRepository->load('mail.mailers.smtp.port', 587);
        $this->settingRepository->load('mail.mailers.smtp.encryption', 'tls');
        $this->settingRepository->load('mail.mailers.smtp.username', null);
        $this->settingRepository->load('mail.mailers.smtp.password', null);
        $this->settingRepository->load('mail.mailers.smtp.timeout', null);
        $this->settingRepository->load('mail.mailers.smtp.local_domain', null);

        //$this->settingRepository->load('mail.mailers.ses.transport', 'ses');
        //$this->settingRepository->load('mail.mailers.mailgun.transport', 'mailgun');
        //$this->settingRepository->load('mail.mailers.postmark.transport', 'postmark');

        //$this->settingRepository->load('mail.mailers.sendmail.transport', 'sendmail');

        //$this->settingRepository->load('mail.mailers.log.transport', 'log');
        //$this->settingRepository->load('mail.mailers.log.channel', 'MAIL_LOG_CHANNEL');

        //$this->settingRepository->load('mail.mailers.array.transport', 'array');

        //$this->settingRepository->load('mail.mailers.failover.transport', 'failover');
        //$this->settingRepository->load('mail.mailers.failover.mailers', ['smtp', 'log']);

        $this->settingRepository->load('mail.from.address', 'hello@example.com');
        $this->settingRepository->load('mail.from.name', 'Example');

        //---------Setting services ---------------
        $this->settingRepository->load('services.mailgun.domain', null);
        $this->settingRepository->load('services.mailgun.secret', null);
        $this->settingRepository->load('services.mailgun.endpoint', 'api.mailgun.net');
        $this->settingRepository->load('services.mailgun.scheme', 'https');

        $this->settingRepository->load('services.passport.token', null);

        $this->settingRepository->load('services.ses.key', null);
        $this->settingRepository->load('services.ses.secret', null);
        $this->settingRepository->load('services.ses.region', null);

        $this->settingRepository->load('services.captcha.driver', "hcaptcha");
        $this->settingRepository->load('services.captcha.enabled', false);

        $this->settingRepository->load('services.captcha.providers.turnstile.api', 'https://challenges.cloudflare.com/turnstile/v0/siteverify');
        $this->settingRepository->load('services.captcha.providers.turnstile.secret', null);
        $this->settingRepository->load('services.captcha.providers.turnstile.sitekey', null);

        $this->settingRepository->load('services.captcha.providers.hcaptcha.api', 'https://hcaptcha.com/siteverify');
        $this->settingRepository->load('services.captcha.providers.hcaptcha.secret', null);
        $this->settingRepository->load('services.captcha.providers.hcaptcha.sitekey', null);

        //Payment settings
        $this->settingRepository->load('billing.methods.stripe.name', 'Credit Card (Stripe)');
        $this->settingRepository->load('billing.methods.stripe.icon', 'mdi-credit-card-outline');
        $this->settingRepository->load('billing.methods.stripe.enable', true);
        $this->settingRepository->load('services.stripe.secret', null);
        $this->settingRepository->load('services.stripe.key', null);
        $this->settingRepository->load('services.stripe.webhook_secret', null);

        $this->settingRepository->load('billing.methods.offline.name', 'Offline');
        $this->settingRepository->load('billing.methods.offline.icon', 'mdi-cash-register');
        $this->settingRepository->load('billing.methods.offline.enable', true);

        $this->settingRepository->load('billing.renew.enable', false);
        $this->settingRepository->load('billing.renew.hours_before', 10);
        $this->settingRepository->load('billing.renew.bonus_enabled', false);
        $this->settingRepository->load('billing.renew.grace_period_days', 5);

        //System settings
        $this->settingRepository->load('system.home_page', "/");
        $this->settingRepository->load('system.cookie_name', "oauth2_server");
        $this->settingRepository->load('system.passport_token_services', null);
        $this->settingRepository->load('system.verify_account_time', 5);
        $this->settingRepository->load('system.disable_create_user_by_command', false);
        $this->settingRepository->load('system.destroy_user_after', 30);
        $this->settingRepository->load('system.code_2fa_email_expires', 5);
        $this->settingRepository->load('system.csp_enabled', false);
        $this->settingRepository->load('system.privacy_url', null);
        $this->settingRepository->load('system.terms_url', null);
        $this->settingRepository->load('system.policy_cookies', null);
        $this->settingRepository->load('system.birthday.active', false);
        $this->settingRepository->load('system.birthday.limit', 18);
        $this->settingRepository->load('system.demo.enabled', false);
        $this->settingRepository->load('system.demo.email', null);
        $this->settingRepository->load('system.demo.password', null);
        $this->settingRepository->load('system.legal.terms_and_condition', null);
        $this->settingRepository->load('system.legal.policies_of_privacy', null);
        $this->settingRepository->load('system.legal.policies_of_cookies', null);



        //Session settings
        //$this->settingRepository->load('session.driver', 'database');
        $this->settingRepository->load('session.lifetime', 7200);
        $this->settingRepository->load('session.expire_on_close', false);
        $this->settingRepository->load('session.encrypt', false);
        $this->settingRepository->load('session.table', 'sessions');
        $this->settingRepository->load('session.cookie', 'oauth2_session');
        $this->settingRepository->load('session.xcsrf-token', 'oauth2_csrf');
        $this->settingRepository->load('session.path', '/');
        $this->settingRepository->load('session.secure', false);
        $this->settingRepository->load('session.http_only', true);
        $this->settingRepository->load('session.partitioned', false);
    }


    /**
     * Setting for laravel passport
     * @return void
     */
    public function getPassportSetting()
    {
        Passport::authorizationView('vendor.passport.authorize');
        Passport::loadKeysFrom(base_path('secrets/oauth'));

        //Cookies names
        Passport::cookie(config('system.cookie_name'));

        try {

            $scopes = config('openid.passport.tokens_can');

            $dbScopes = Cache::remember(
                'passport:scopes',
                now()->addDays(intval(config('cache.expires', 90))),
                function () {

                    return Scope::with('role')
                        ->where('active', true)
                        ->get()
                        ->mapWithKeys(function ($scope) {
                            return [$scope->gsr_id => $scope->role->description];
                        })
                        ->toArray();
                }
            );

            $scopes += $dbScopes;

            Passport::tokensCan($scopes);
        } catch (QueryException $th) {
            Log::error($th->getMessage(), $th->getTrace());
        }

        /**
         * Custom models for laravel passport
         */
        Passport::useTokenModel(Token::class);
        Passport::useRefreshTokenModel(RefreshToken::class);
        Passport::useAuthCodeModel(AuthCode::class);
        Passport::useClientModel(Client::class);
    }

    /**
     * Update
     * @param Request $request
     * @return void
     */
    public function update(Request $request)
    {
        $data = $request->except('_method', '_token', 'current_route');
        $route = Route::getRoutes()->getByName($request->current_route)?->action;
        $moduleConfigKey = null;

        if (isset($route['config_key']) && $route['config_key']) {
            $moduleConfigKey = $route['config_key'];
        }

        $data = transformConfigRequest($data);

        foreach ($data as $key => $value) {

            $configKey = empty($moduleConfigKey) ? $key : "$moduleConfigKey.$key";

            $this->settingRepository->add($configKey, $value);
        }

        //Set a pid to reset cache
        file_put_contents($this->pid(), time());
    }

    /**
     * Validate cache system from request
     * @param Request $request
     * @return void
     */
    public function validateCacheSystemFromRequest(Request $request): void
    {
        if ($request->has('database.redis')) {
            foreach (['default', 'cache'] as $type) {
                $this->checkRedisConnection(
                    $request->input("database.redis.$type.host"),
                    $request->input("database.redis.$type.port", 6379),
                    $request->input("database.redis.$type.password"),
                    $request->input("database.redis.$type.database", 0),
                    $type
                );
            }
        }
    }

    /**
     * Check redis connection
     * @param mixed $host
     * @param mixed $port
     * @param mixed $password
     * @param mixed $database
     * @param mixed $type
     * @throws ReportError
     * @return void
     */
    protected function checkRedisConnection($host, $port, $password, $database, $type): void
    {
        $client = new \Redis();

        $context = [
            'type' => $type,
            'host' => $host,
            'port' => (int) $port,
            'database' => (int) $database,
        ];

        try {
            // 1. Conexión
            if (!$client->connect($host, (int) $port, 2)) {
                Log::error("Redis connection failed", $context);

                throw new ReportError(
                    "Redis connection failed [$type] → {$host}:{$port}",
                    403
                );
            }

            // 2. Auth
            if (!empty($password)) {
                if (!$client->auth($password)) {
                    Log::error("Redis authentication failed", $context);

                    throw new ReportError(
                        "Redis authentication failed [$type] → {$host}:{$port}",
                        403
                    );
                }
            }

            // 3. Selection DB
            if (!$client->select((int) $database)) {
                Log::error("Redis database selection failed", $context);

                throw new ReportError(
                    "Redis database selection failed [$type] → DB: {$database}",
                    403
                );
            }

            if (!$client->ping()) {
                Log::error("Redis ping failed", $context);

                throw new ReportError(
                    "Redis ping failed [$type] → {$host}:{$port}",
                    403
                );
            }
        } catch (\Throwable $e) {
            Log::error("Redis exception", array_merge($context, [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]));

            throw new ReportError(
                "Redis error [$type] → " . $e->getMessage(),
                403
            );
        } finally {
            try {
                $client->close();
            } catch (\Throwable $e) {
                Log::warning("Redis close failed", $context);
            }
        }
    }

    /**
     * Delete a module and all children.
     * @param string $key
     * @return void
     */
    public function deleteKeys(string $key)
    {
        $this->settingRepository->deleteKeys($key);
    }

    /**
     * Migrate old config to file from db
     * @return void
     */
    public function migrateConfig()
    {
        $this->settingRepository->importOldConfig();
    }
}
