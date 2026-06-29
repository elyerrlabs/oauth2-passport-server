<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\DirectiveServiceProvider::class,
    App\Providers\BroadcastServiceProvider::class,
    App\Providers\CoreServiceProvider::class,
    App\Providers\FortifyServiceProvider::class,
    App\Providers\HorizonServiceProvider::class,
    App\Providers\MorphServiceProvider::class,
    App\Providers\RateLimitProvider::class,
    App\Providers\ThirdPartyServiceProvider::class,
    OpenIDConnect\Laravel\PassportServiceProvider::class,
];
