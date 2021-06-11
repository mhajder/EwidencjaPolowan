<?php

use App\Enums\HuntedAnimalPurposes;
use App\Enums\UserRoles;

return [

    HuntedAnimalPurposes::class => [
        HuntedAnimalPurposes::Sale => 'Sale',
        HuntedAnimalPurposes::OwnUse => 'Own use',
        HuntedAnimalPurposes::OwnNeedsHuntingClub => 'Own needs of the hunting club',
        HuntedAnimalPurposes::Utilization => 'Utilization',
        HuntedAnimalPurposes::ASF => 'ASF',
    ],

    UserRoles::class => [
        UserRoles::User => 'User',
        UserRoles::Admin => 'Administrator',
    ],

];
