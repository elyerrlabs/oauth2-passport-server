<?php

namespace Core\User\Services;

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

use Core\User\Repositories\UserRepository;
use DateInterval;
use DateTime;
use App\Notifications\Member\MemberCreatedAccount;
use Illuminate\Support\Facades\DB;
use Core\User\Repositories\GroupRepository;
use Core\User\Notification\UserReactivateAccount;
use Exception;
use Core\User\Notification\UserDisableAccount;
use App\Repositories\OAuth\Server\Grant\OAuthSessionTokenRepository;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Core\User\Notification\UserUpdatedEmail;
use Core\User\Notification\UserCreatedAccount;
use App\Support\CacheKeys;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Elyerr\ApiResponse\Assets\Asset;
use Illuminate\Http\Request;

class UserService
{
    use Asset;

    /**
     * User repository
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Oauth2 session repository
     * @var OAuthSessionTokenRepository
     */
    private $oauthSessionRepository;

    /**
     * Group repository
     * @var GroupRepository
     */
    private $groupRepository;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->userRepository = app(UserRepository::class);
        $this->oauthSessionRepository = app(OAuthSessionTokenRepository::class);
        $this->groupRepository = app(GroupRepository::class);
    }

    /**
     * Search data
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<\Core\User\Model\User>
     */
    public function search(Request $request)
    {
        // Prepare query
        $query = $this->userRepository->query();

        // Add users trashed
        $query->orderByDesc('updated_at');

        if ($request->filled('name')) {
            $query->whereRaw("LOWER(name) like ?", ["%" . strtolower($request->name) . "%"]);
        }

        if ($request->filled('email')) {
            $query->whereRaw("LOWER(email) like ?", ["%" . strtolower($request->email) . "%"]);
        }

        if ($request->filled('country')) {
            $query->whereRaw("LOWER(country) like ?", ["%" . strtolower($request->country) . "%"]);
        }

        return $query;
    }


    /**
     * Create new user
     * @param array $data
     * @return \Core\User\Model\User|\Core\User\Repositories\TModel|\Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        // Create password
        $temp_password = $this->passwordTempGenerate(32);

        $user = $this->userRepository->create([
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($temp_password),
            'country' => $data['country'] ?? null,
            'city' => $data['city'] ?? null,
            'address' => $data['address'] ?? null,
            'dial_code' => $data['dial_code'] ?? null,
            'phone' => $data['phone'] ?? null,
            'birthday' => $data['birthday'] ?? null,
            'email_verified_at' => $data['email_verified_at'] ? now() : null,
            'accept_terms' => $data['accept_terms'] ?? true,
            'accept_cookies' => $data['accept_cookies'] ?? true,
        ]);

        Cache::put(CacheKeys::user($user->id), $user);

        $user->notify(new UserCreatedAccount($temp_password));

        return $user;
    }

    /**
     * Find user
     * @param string $id
     */
    public function find(string $id)
    {
        $cacheKey = CacheKeys::user($id);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $model = $this->userRepository->find($id);

        if (!empty($model)) {
            Cache::put($cacheKey, $model, now()->addDays(intval(config('cache.expires', 90))));
        }

        return $model;
    }

    /**
     * Search user by email
     * @param string $email
     * @return \Core\User\Repositories\TModel|\Core\User\Repositories\TValue|null
     */
    public function findByEmail(string $email)
    {
        return $this->userRepository->findByEmail($email);
    }

    /**
     * Show detail
     * @param string $user_id
     */
    public function details(string $user_id)
    {
        // Search by cache
        $cacheKey = CacheKeys::user($user_id);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        return $this->userRepository->find($user_id);
    }

    /**
     * Update user
     * @param string $id
     * @param array $data
     * @return \Core\User\Repositories\TModel|\Core\User\Repositories\TValue|null
     */
    public function update(string $id, array $data)
    {
        $cacheKey = CacheKeys::user($id);

        Cache::forget($cacheKey);
        Cache::forget(CacheKeys::userAuth($id));

        $model = $this->userRepository->update($id, [
            "name" => $data['name'],
            "last_name" => $data['last_name'],
            "country" => $data['country'] ?? null,
            "dial_code" => $data['dial_code'] ?? null,
            "phone" => $data['phone'] ?? null,
            "city" => $data['city'] ?? null,
            "address" => $data['address'] ?? null,
            "birthday" => $data['birthday'] ?? null,
            "lang" => $data['lang'] ?? 'en',
        ]);

        // Update cache
        Cache::put(
            CacheKeys::user($id),
            $model,
            now()->addDays(intval(config('cache.expires', 90)))
        );

        return $model;
    }

    /**
     * Update user email
     * @param string $id
     * @param string $email
     * @return \Core\User\Repositories\TModel|\Core\User\Repositories\TValue|null
     */
    public function updateEmail(string $id, string $email)
    {
        // Cache key id
        $cacheKey = CacheKeys::user($id);

        // Clean cache
        Cache::forget($cacheKey);
        Cache::forget(CacheKeys::userAuth($id));

        $model = $this->userRepository->find($id);

        // Update email
        if (strtolower($model->email) == strtolower($email)) {

            $model->email = $email;
            $model->push(); // Update email

            // Update cache
            Cache::put(
                CacheKeys::user($id),
                $model,
                now()->addDays(intval(config('cache.expires', 90)))
            );

            // Send email to the user
            $model->notify(new UserUpdatedEmail());
        }

        return $model;
    }

    /**
     * Disable users  and destroy all sessions
     * @param string $id
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return \Core\User\Repositories\TModel|\Core\User\Repositories\TValue|null
     */
    public function disable(string $id)
    {

        if (auth()->user()->id == $id) {
            throw new ReportError(__('Disabling your own account is not allowed.'), 403);
        }

        // Retrieve cache key
        $cacheKey = CacheKeys::user($id);

        // Clean cache for this user
        Cache::forget($cacheKey);
        Cache::forget(CacheKeys::userAuth($id));
        Cache::forget(CacheKeys::userScopes($id));

        // Find user
        $user = $this->userRepository->find($id);

        // Retrieve token for this users
        $tokens = $user->tokens;

        // Revoke all tokens and sessions
        foreach ($tokens as $token) {

            if ($token->oauthSessionToken->session_id ?? false) {
                $this->oauthSessionRepository->destroyTokenSession($token->oauthSessionToken->session_id);
            }

            $token->revoke();
        }

        // soft delete to disable user
        $user->delete();

        // Update cache key
        Cache::put(
            $cacheKey,
            $user,
            now()->addDays(intval(config('cache.expires', 90)))
        );

        // Sent notification to the user
        $user->notify(new UserDisableAccount());

        return $user;
    }

    /**
     * Enable disabled users
     * @param string $id
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function enable(string $id)
    {
        try {
            // Cache Key
            $cacheKey = CacheKeys::user($id);

            Cache::forget($cacheKey);
            Cache::forget(CacheKeys::userAuth($id));

            // Find user
            $user = $this->userRepository->find($id);

            // Restore user account
            $user->restore();

            // Update Cache Key
            Cache::put(
                $cacheKey,
                $user,
                now()->addDays(intval(config('cache.expires', 90)))
            );

            $user->notify(new UserReactivateAccount());

            return $user;

        } catch (Exception $e) {
            throw new ReportError(__("The server cannot find the requested resource"), 404);
        }
    }

    /**
     * Search the all scopes available for the user
     * @param string $user_id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<\Core\User\Model\UserScope>
     */
    public function searchScopesForUser(string $user_id)
    {
        // Create query
        $query = $this->userRepository->scopeQuery();

        // Search by user id
        $query->where('user_id', $user_id);

        $query->where(function ($query) {
            // Filter by expiration date
            $query->whereNull('end_date')
                // Add Scopes without expiration
                ->orWhere('end_date', '>', now());
        });

        $query->whereHas(
            'scope',
            function ($query) {
                $query->where('active', true);
            }
        );

        return $query;
    }

    /**
     * Retrieve the all groups for the user
     * @param string $user_id
     */
    public function searchGroupsForUser(string $user_id)
    {
        return Cache::remember(
            CacheKeys::userGroups($user_id),
            now()->addDays(intval(config('cache.expires', 90))),
            function ($user_id) {

                $groups = $this->groupRepository->query();

                $groups->whereHas('users', function ($query) use ($user_id) {
                    $query->where('id', $user_id);
                });

                return $groups;
            }
        );
    }

    /**
     * Assign scope for the user
     * @param string $user_id
     * @param array $data
     * @return void
     */
    public function assignScopeForUser(string $user_id, array $data)
    {
        // Clean cache keys
        Cache::forget(CacheKeys::userScopes($user_id));
        Cache::forget(CacheKeys::userGroups($user_id));
        Cache::forget(CacheKeys::userAdmin($user_id));
        //   Cache::forget(CacheKeys::userScopesApiKey($user_id));
        Cache::forget(CacheKeys::userScopeList($user_id));
        Cache::forget(CacheKeys::userScopeList($user_id));
        Cache::forget(CacheKeys::userAuth($user_id));


        foreach ($data['scopes'] as $id) {

            // Create query
            $query = $this->userRepository->scopeQuery();

            // Filter by user id
            $query->where('user_id', $user_id);

            // Filter by scope id
            $query->where('scope_id', $id);

            // Add where the package_id is null
            $query->whereNull('package_id');

            // Filter by expiration date
            $query->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>', now());
            });

            // Update or create the scope for the user
            $us = $query->updateOrCreate([
                'scope_id' => $id,
                'user_id' => $user_id,
                'end_date' => $data['end_date'] ?? null
            ]);

            // Sync groups by scopes for the user
            $this->userRepository->find($user_id)->groups()->sync($us->scope->service->group->id);
        }
    }

    /**
     *  Assign groups to the user
     * @param string $user_id
     * @param array $data
     * @return void
     */
    public function assignGroupForUser(string $user_id, array $data)
    {
        // Clean cache key
        Cache::forget(CacheKeys::userGroups($user_id));

        // Find user
        $user = $this->userRepository->find($user_id);

        // Sync groups
        $user->groups()->syncWithoutDetaching($data['groups']);

    }

    /**
     * Revoke scopes to the user
     * @param string $user_id
     * @param string $id
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return void
     */
    public function revokeScopeForUser(string $user_id, string $id)
    {
        // Search user scope
        $scope = $this->userRepository->scopeQuery()
            ->where('id', $id)  // by id
            ->where('user_id', $user_id)  //by user id
            ->first();

        // Deny when the package is null
        if (!empty($scope->package_id)) {
            throw new ReportError(
                __('This scope cannot be deleted because it is associated with a paid plan. Please contact support if you believe this is an error.'),
                403
            );
        }

        // Deny delete scope for own permissions
        if ($user_id == auth()->user()->id) {
            throw new ReportError(
                __('You cannot remove your own permissions. Please contact an administrator if you require changes to your access.'),
                403
            );
        }

        // Set expiration date for the scopes
        if (empty($scope->end_date) || $scope->end_date >= now()) {

            // add expiration date
            $scope->end_date = now();
            $scope->push();

            // Deleted cache keys
            Cache::forget(CacheKeys::userScopes($user_id));
            Cache::forget(CacheKeys::userGroups($user_id));
            Cache::forget(CacheKeys::userAdmin($user_id));
            //  Cache::forget(CacheKeys::userScopesApiKey($user_id));
            Cache::forget(CacheKeys::userScopeList($user_id));
            Cache::forget(CacheKeys::userAuth($user_id));
        }
    }

    /**
     * Revoke group for the user
     * @param mixed $user_id
     * @param string $group_id
     * @return void
     */
    public function revokeGroupForUser($user_id, string $group_id)
    {
        // find user
        $user = $this->userRepository->find($user_id);

        // Detach groups
        $user->groups()->detach($group_id);

        // Clean Cache key
        Cache::forget(CacheKeys::userGroups($user_id));
    }

    /**
     * Show the history the all scopes assigner for the user
     * @param string $user_id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<\Core\User\Model\UserScope>
     */
    public function searchScopeHistoryForUser(string $user_id)
    {
        $scopes = $this->userRepository->scopeQuery();

        $scopes->where('user_id', $user_id);

        $scopes->orderBy('end_date');

        return $scopes;
    }

    /**
     * Update personal password for the user
     * @param array $data
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function updatePassword(string $user_id, array $data)
    {
        $user = $this->userRepository->find($user_id);

        $user->password = Hash::make($data['password']);
        $user->push();

        return $user;
    }

    /**
     *  Register new users (customers)
     * @param array $data
     */
    public function registerCustomer(array $data)
    {
        $group = $this->groupRepository->findBySlug('member');

        if (empty($group)) {
            return back()->with('error', __('The registration could not be completed successfully. Our team has been notified of the issue and is working to resolve it. We appreciate your patience and encourage you to try again later'));
        }

        return DB::transaction(function () use ($data, $group) {

            // Create user
            $user = $this->userRepository->create([
                'name' => $data['name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'birthday' => $data['birthday'] ?? null,
                'email_verified_at' => config('system.registration.email.verification', false) ? null : now(), // if it the email verification is true, so the field is null otherwise is now()
                'accept_terms' => $data['accept_terms'],
                'accept_cookies' => $data['accept_cookies']
            ]);

            // Check for referral code 
            if (!empty($data['referral_code']) && class_exists(\Core\Partner\Model\Partner::class)) {

                $partner = \Core\Partner\Model\Partner::where('code', $data['referral_code'])->first();

                if ($partner) {
                    $user->partner_id = $partner->id;
                    $user->push();
                }
            }

            $user->groups()->attach($group->id);

            Cache::put(
                CacheKeys::user($user->id),
                $user,
                now()->addDays(intval(config('cache.expires', 90)))
            );

            return $user;
        });
    }

    /**
     * Verify user accounts
     * @param array $data
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return mixed|\Illuminate\Http\RedirectResponse
     */
    public function verifyUserAccount(array $data)
    {
        try {

            // Verify the auth user and incoming use are the same
            if (auth()->check() && auth()->user()->email !== $data['email']) {
                // Destroy token
                DB::table('password_resets')->where('email', '=', $data['email'])->delete();
                // Logout user
                auth()->logout();
            }

            // Verify the user has activated account
            if (auth()->check() && auth()->user()->email_verified_at) {
                return redirectToHome();
            }

            // Retrieve the token
            $token = DB::table('password_resets')->where([
                'token' => $data['token'],
                'email' => $data['email'],
            ])->first();

            // Calculate time valid token
            $now = new DateTime($token->created_at);
            $now->add(new DateInterval("PT" . config("system.verify_account_time", 5) . "M"));
            $date = $now->format("Y-m-d H:i:s");

            // Destroy current token
            //  DB::table('password_resets')->where('email', '=', $token->email)->delete();

            // Retrieve the user object
            $user = $this->userRepository->findByEmail($token->email);

            // Validate expiration token
            if (date('Y-m-d H:i:s', strtotime(now())) > $date) {
                return redirect()->route('login')
                    ->with(
                        'error',
                        __("Time's up to activate the account, please login and try again.")
                    );
            }

            $user->email_verified_at = now();
            $user->save();

            // Authenticate the user
            if (!auth()->check()) {
                auth()->login($user);
            }

            return redirect()->route('user.verified.account')
                ->with(
                    [
                        'status' =>
                            __('Your account has been activated.'),
                        'token' => uniqid()
                    ]
                );

        } catch (Exception $e) {

            if (auth()->check()) {
                auth()->logout();
            }

            return redirect()->route('login')->with(
                'error',
                __("Something is wrong, please login and tray again")
            );
        }
    }
}
