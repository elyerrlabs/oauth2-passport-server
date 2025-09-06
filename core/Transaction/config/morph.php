<?php

return [
    (new \Core\Transaction\Model\Plan())->tag => \Core\Transaction\Model\Plan::class,
    (new \Core\Transaction\Model\Package())->tag => \Core\Transaction\Model\Package::class,
    (new \Core\Transaction\Model\Checkout())->tag => \Core\Transaction\Model\Checkout::class
];