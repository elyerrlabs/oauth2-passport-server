<?php

namespace App\Services\Settings;

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

use App\Models\OAuth\Token;
use Illuminate\Support\Str;
use App\Models\OAuth\Client;
use App\Models\OAuth\AuthCode;
use Laravel\Passport\Passport;
use App\Models\OAuth\RefreshToken;
use Core\User\Model\Scope;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\QueryException;
use PhpParser\Node\Stmt\TryCatch;

class Setting
{

    /**
     * Set default values 
     * @return void
     */
    public static function getDefaultSetting()
    {
        try {
            $settings = \App\Models\Setting\Setting::all(['key', 'value']);
            foreach ($settings as $key => $item) {
                Config::set($item->key, $item->value);
            }
        } catch (\Throwable $th) {
        }

        Setting::getPassportSetting();

        if (config('system.schema_mode', 'https') == 'https') {
            URL::forceScheme('https');
        }
    }


    /**
     * Add default setting into the system
     * @return void
     */
    public static function setDefaultKeys()
    {
        //App name
        settingLoad('app.name', 'Oauth2 Server');
        settingLoad('app.org_name', 'Server org');

        //expires time for reset password
        settingLoad('auth.passwords.users.expire', 10);
        //expires time to try another request 
        settingLoad('auth.passwords.users.throttle', 10);

        //------------------------REDIS CONFIGURATION-------------------//
        //redis default settings
        settingLoad('database.redis.default.url', null);
        settingLoad('database.redis.default.host', '127.0.0.1');
        settingLoad('database.redis.default.username', null);
        settingLoad('database.redis.default.password', null);
        settingLoad('database.redis.default.port', '6379');
        settingLoad('database.redis.default.database', 0);

        //redis cache settings
        settingLoad('database.redis.cache.url', null);
        settingLoad('database.redis.cache.host', '127.0.0.1');
        settingLoad('database.redis.cache.username', null);
        settingLoad('database.redis.cache.password', null);
        settingLoad('database.redis.cache.port', '6379');
        settingLoad('database.redis.cache.database', 1);
        //------------------------END REDIS CONFIGURATION-------------------//

        //------------------------CACHE CONFIGURATION-------------------//
        settingLoad('cache.default', 'file');
        settingLoad('cache.expires', 30);
        settingLoad('cache.prefix', Str::slug(config('app.name', 'oauth2_server'), '_') . '_cache_');

        settingLoad('cache.stores.database.connection', null);
        settingLoad('cache.stores.database.table', 'cache');

        settingLoad('cache.stores.redis.connection', 'cache');
        settingLoad('cache.stores.redis.lock_connection', 'default');

        settingLoad('cache.stores.memcached.persistent_id', null);
        settingLoad('cache.stores.memcached.sasl.username', null);
        settingLoad('cache.stores.memcached.sasl.password', null);
        settingLoad('cache.stores.memcached.servers.0.host', '127.0.0.1');
        settingLoad('cache.stores.memcached.servers.0.port', 11211);
        settingLoad('cache.stores.memcached.servers.0.weight', 100);

        settingLoad('cache.stores.dynamodb.key', null);
        settingLoad('cache.stores.dynamodb.secret', null);
        settingLoad('cache.stores.dynamodb.region', 'us-east-1');
        settingLoad('cache.stores.dynamodb.table', 'cache');
        settingLoad('cache.stores.dynamodb.endpoint', null);
        //------------------------END CACHE CONFIGURATION-------------------//


        //---------------------QUEUES CONFIG--------------------///
        //default queues
        settingLoad('queue.default', 'database');

        //Sync setting
        settingLoad('queue.connections.sync.driver', 'sync');

        //Database settings
        settingLoad('queue.connections.database.driver', 'database');
        settingLoad('queue.connections.database.table', 'jobs');
        settingLoad('queue.connections.database.queue', 'default');
        settingLoad('queue.connections.database.retry_after', 90);
        settingLoad('queue.connections.database.after_commit', false);

        //beanstalkd Settings
        settingLoad('queue.connections.beanstalkd.driver', 'beanstalkd');
        settingLoad('queue.connections.beanstalkd.host', 'localhost');
        settingLoad('queue.connections.beanstalkd.queue', 'default');
        settingLoad('queue.connections.beanstalkd.retry_after', 90);
        settingLoad('queue.connections.beanstalkd.block_for', 0);
        settingLoad('queue.connections.beanstalkd.after_commit', false);

        //AWS settings
        settingLoad('queue.connections.sqs.driver', 'sqs');
        settingLoad('queue.connections.sqs.key', null);
        settingLoad('queue.connections.sqs.secret', null);
        settingLoad('queue.connections.sqs.prefix', 'https://sqs.us-east-1.amazonaws.com/your-account-id');
        settingLoad('queue.connections.sqs.queue', 'default');
        settingLoad('queue.connections.sqs.suffix', null);
        settingLoad('queue.connections.sqs.region', 'us-east-1');
        settingLoad('queue.connections.sqs.after_commit', false);

        //Redis Settings
        settingLoad('queue.connections.redis.driver', 'redis');
        settingLoad('queue.connections.redis.connection', 'default');
        settingLoad('queue.connections.redis.queue', 'default');
        settingLoad('queue.connections.redis.retry_after', 90);
        settingLoad('queue.connections.redis.block_for', null);
        settingLoad('queue.connections.redis.after_commit', false);

        //Fail queue settings
        settingLoad('queue.failed.driver', 'database-uuids');
        settingLoad('queue.failed.database', 'mysql');
        settingLoad('queue.failed.table', 'failed_jobs');
        //---------------------END QUEUES CONFIG--------------------///

        //----------FILESYSTEM SETTINGS------------------------------------------
        settingLoad('filesystems.default', 'local');
        settingLoad('filesystems.disks.local.driver', 'local');
        settingLoad('filesystems.disks.local.root', storage_path('app'));
        settingLoad('filesystems.disks.local.throw', false);

        settingLoad('filesystems.disks.public.driver', 'local');
        settingLoad('filesystems.disks.public.root', storage_path('app/public'));
        settingLoad('filesystems.disks.public.url', config('app.url', null) . '/storage');
        settingLoad('filesystems.disks.public.visibility', 'public');
        settingLoad('filesystems.disks.public.throw', false);

        settingLoad('filesystems.disks.s3.driver', 's3');
        settingLoad('filesystems.disks.s3.key', null);
        settingLoad('filesystems.disks.s3.secret', null);
        settingLoad('filesystems.disks.s3.region', null);
        settingLoad('filesystems.disks.s3.bucket', null);
        settingLoad('filesystems.disks.s3.url', null);
        settingLoad('filesystems.disks.s3.endpoint', null);
        settingLoad('filesystems.disks.s3.use_path_style_endpoint', false);
        settingLoad('filesystems.disks.s3.throw', false);

        settingLoad('filesystems.links.public', public_path('storage'));
        settingLoad('filesystems.links.storage', storage_path('app/public'));


        //-------EMAIL SETTINGS -------------------------
        settingLoad('mail.default', 'smtp');

        settingLoad('mail.mailers.smtp.transport', 'smtp');
        settingLoad('mail.mailers.smtp.host', 'smtp.mailgun.org');
        settingLoad('mail.mailers.smtp.port', 587);
        settingLoad('mail.mailers.smtp.encryption', 'tls');
        settingLoad('mail.mailers.smtp.username', null);
        settingLoad('mail.mailers.smtp.password', null);
        settingLoad('mail.mailers.smtp.timeout', null);
        settingLoad('mail.mailers.smtp.local_domain', null);

        settingLoad('mail.mailers.ses.transport', 'ses');
        settingLoad('mail.mailers.mailgun.transport', 'mailgun');
        settingLoad('mail.mailers.postmark.transport', 'postmark');

        settingLoad('mail.mailers.sendmail.transport', 'sendmail');

        settingLoad('mail.mailers.log.transport', 'log');
        settingLoad('mail.mailers.log.channel', 'MAIL_LOG_CHANNEL');

        settingLoad('mail.mailers.array.transport', 'array');

        settingLoad('mail.mailers.failover.transport', 'failover');
        //settingLoad('mail.mailers.failover.mailers', ['smtp', 'log']);

        settingLoad('mail.from.address', 'hello@example.com');
        settingLoad('mail.from.name', 'Example');

        //---------Setting services ---------------
        settingLoad('services.mailgun.domain', null);
        settingLoad('services.mailgun.secret', null);
        settingLoad('services.mailgun.endpoint', 'api.mailgun.net');
        settingLoad('services.mailgun.scheme', 'https');

        settingLoad('services.passport.token', null);

        settingLoad('services.ses.key', null);
        settingLoad('services.ses.secret', null);
        settingLoad('services.ses.region', null);

        settingLoad('services.captcha.driver', "hcaptcha");
        settingLoad('services.captcha.enabled', false);

        settingLoad('services.captcha.providers.turnstile.api', 'https://challenges.cloudflare.com/turnstile/v0/siteverify');
        settingLoad('services.captcha.providers.turnstile.secret', null);
        settingLoad('services.captcha.providers.turnstile.sitekey', null);

        settingLoad('services.captcha.providers.hcaptcha.api', 'https://hcaptcha.com/siteverify');
        settingLoad('services.captcha.providers.hcaptcha.secret', null);
        settingLoad('services.captcha.providers.hcaptcha.sitekey', null);

        //Payment settings 
        settingLoad('billing.methods.stripe.name', 'Credit Card (Stripe)');
        settingLoad('billing.methods.stripe.icon', 'mdi-credit-card-outline');
        settingLoad('billing.methods.stripe.enable', true);
        settingLoad('services.stripe.secret', null);
        settingLoad('services.stripe.key', null);
        settingLoad('services.stripe.webhook_secret', null);

        settingLoad('billing.methods.offline.name', 'Offline');
        settingLoad('billing.methods.offline.icon', 'mdi-cash-register');
        settingLoad('billing.methods.offline.enable', true);

        SettingLoad('billing.renew.enable', false);
        SettingLoad('billing.renew.hours_before', 10);
        SettingLoad('billing.renew.bonus_enabled', false);
        SettingLoad('billing.renew.grace_period_days', 5);

        //System settings 
        settingLoad('system.home_page', "/");
        settingLoad('system.cookie_name', "oauth2_server");
        settingLoad('system.passport_token_services', null);
        settingLoad('system.verify_account_time', 5);
        settingLoad('system.disable_create_user_by_command', false);
        settingLoad('system.destroy_user_after', 30);
        settingLoad('system.code_2fa_email_expires', 5);
        settingLoad('system.csp_enabled', false);
        settingLoad('system.redirect_to', "/account");
        settingLoad('system.privacy_url', null);
        settingLoad('system.terms_url', null);
        settingLoad('system.policy_cookies', null);
        settingLoad('system.birthday.active', false);
        settingLoad('system.birthday.limit', 18);
        settingLoad('system.demo.enabled', false);
        settingLoad('system.demo.email', null);
        settingLoad('system.demo.password', null);
        settingLoad('system.legal.terms_and_condition', null);
        settingLoad('system.legal.policies_of_privacy', null);
        settingLoad('system.legal.policies_of_cookies', null);



        //Session settings
        //settingLoad('session.driver', 'database');
        settingLoad('session.lifetime', 7200);
        settingLoad('session.expire_on_close', false);
        settingLoad('session.encrypt', false);
        settingLoad('session.table', 'sessions');
        settingLoad('session.cookie', 'oauth2_session');
        settingLoad('session.xcsrf-token', 'oauth2_csrf');
        settingLoad('session.path', '/');
        settingLoad('session.secure', false);
        settingLoad('session.http_only', true);
        settingLoad('session.partitioned', false);

        // Settings routes
        settingLoad('routes.users.developers', false);
        settingLoad('routes.users.api', false);
        settingLoad('routes.users.clients', false);
        settingLoad('routes.guest.register', true);

        // Setting por rate limit 
        $rateLimits = config('rate_limit');

        foreach ($rateLimits as $module => $items) {
            foreach ($items as $key => $value) {
                settingLoad("rate_limit.{$module}.{$key}.limit", 60);
                settingLoad("rate_limit.{$module}.{$key}.block_time", 60);
            }
        }
    }


    /**
     * Setting for laravel passport
     * @return void
     */
    public static function getPassportSetting()
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
        }

        /**
         * Custom models for laravel passport
         */
        Passport::useTokenModel(Token::class);
        Passport::useRefreshTokenModel(RefreshToken::class);
        Passport::useAuthCodeModel(AuthCode::class);
        Passport::useClientModel(Client::class);
    }
}
