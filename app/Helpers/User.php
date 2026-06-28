<?php

if (!function_exists('canAccessMenu')) {
    /**
     * Determine whether the authenticated user has access to a menu
     * based on the specified service scope.
     *
     * The scope may be provided in either of the following formats:
     *
     * - Partial scope: settings:elymod-app
     * - Exact scope:   settings:elymod-app:view
     *
     * Partial scopes match any action belonging to the specified service,
     * while exact scopes require an exact permission match.
     *
     * @param string $scope Service scope to evaluate.
     * @return bool True if the authenticated user is authorized; otherwise false.
     */
    function canAccessMenu(string $scope): bool
    {
        return auth()->user()->canAccessMenu($scope);
    }
}