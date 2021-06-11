<?php

use App\Enums\HuntedAnimalPurposes;
use App\Enums\UserRoles;

return [

    HuntedAnimalPurposes::class => [
        HuntedAnimalPurposes::Sale => 'Skup',
        HuntedAnimalPurposes::OwnUse => 'Użytek własny',
        HuntedAnimalPurposes::OwnNeedsHuntingClub => 'Potrzeby własne koła',
        HuntedAnimalPurposes::Utilization => 'Utylizacja',
        HuntedAnimalPurposes::ASF => 'ASF - odstrzał',
    ],

    UserRoles::class => [
        UserRoles::User => 'Użytkownik',
        UserRoles::Admin => 'Administrator',
    ],

];
