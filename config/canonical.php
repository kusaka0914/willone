<?php

return [
    // /woa/new/1 -> /woa/new
    'SHAVE_FIRST_PAGE_ROUTE_NAME_LIST' => [
        # /woa/new/{page?}
        'NewList',
        # /woa/area/{pref}/new/{page?}
        'AreaNewList',
        # /woa/job/{type}/new/{page?}
        'JobNewList',
        # /woa/area/{pref}/{state}/{page?}
        'AreaStateSelect',
        # /woa/job/{id}/{pref}/{page?}
        'JobAreaSelect',
        # /woa/job/{id}/{pref}/{state}/{page?}
        'JobAreaStateSelect',
        # /woa/taikendan/list/{page?}
        'TensyokuList',
        # /woa/taikendan/category/{category}/{page?}
        'TensyokuCategory'
    ],
];
