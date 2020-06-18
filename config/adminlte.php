<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | The default title of your admin panel, this goes into the title tag
    | of your page. You can override it per page with the title section.
    | You can optionally also specify a title prefix and/or postfix.
    |
    */

    'title' => 'HRM',

    'title_prefix' => '',

    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | This logo is displayed at the upper left corner of your admin panel.
    | You can use basic HTML here if you want. The logo has also a mini
    | variant, used for the mini side bar. Make it 3 letters or so
    |
    */

    'logo' => '<b>HRM</b>',

    'logo_mini' => '<b>HRM</b>',

    /*
    |--------------------------------------------------------------------------
    | Skin Color
    |--------------------------------------------------------------------------
    |
    | Choose a skin color for your admin panel. The available skin colors:
    | blue, black, purple, yellow, red, and green. Each skin also has a
    | ligth variant: blue-light, purple-light, purple-light, etc.
    |
    */

    'skin' => 'purple-light',

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Choose a layout for your admin panel. The available layout options:
    | null, 'boxed', 'fixed', 'top-nav'. null is the default, top-nav
    | removes the sidebar and places your menu in the top navbar
    |
    */

    'layout' => '',

    /*
    |--------------------------------------------------------------------------
    | Collapse Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we choose and option to be able to start with a collapsed side
    | bar. To adjust your sidebar layout simply set this  either true
    | this is compatible with layouts except top-nav layout option
    |
    */

    'collapse_sidebar' => true,

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Register here your dashboard, logout, login and register URLs. The
    | logout URL automatically sends a POST request in Laravel 5.3 or higher.
    | You can set the request to a GET or POST with logout_method.
    | Set register_url to null if you don't want a register link.
    |
    */

    'dashboard_url' => 'dashboard',

    'logout_url' => 'logout',

    'logout_method' => null,

    'login_url' => 'login',

    'register_url' => 'register',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Specify your menu items to display in the left sidebar. Each menu item
    | should have a text and and a URL. You can also specify an icon from
    | Font Awesome. A string instead of an array represents a header in sidebar
    | layout. The 'can' is a filter on Laravel's built in Gate functionality.
    |
    */

    'menu' => [
        [
            'text'        => 'Dashboard',
            'url'         => 'dashboard',
            'icon'        => 'dashboard',
            'submenu' => [
               [
                   'text' => 'Dashboard',
                   'url'  => 'dashboard',
                   'icon' => 'dashboard',
               ],
             ]
        ],


        [
               'text'        => 'Lead',
               'icon'        => 'users',
               'submenu' => [
                   [
                       'text' => 'Add Lead',
                       'url'  => 'lead/create',
                       'icon' => 'plus',
                   ],
                   [
                       'text' => 'List Lead',
                       'url'  => 'lead',
                       'icon' => 'list',
                   ],
                   [
                       'text' => 'Cancelled Leads',
                       'url'  => 'lead/cancel',
                       'icon' => 'list',
                   ]
               ],
           ],

            [
               'text'        => 'Clients',
               'icon'        => 'users',
               'submenu' => [
                   [
                       'text' => 'Add Client',
                       'url'  => 'client/create',
                       'icon' => 'plus',
                   ],
                   [
                       'text' => 'List Clients',
                       'url'  => 'client',
                       'icon' => 'list',
                   ],
                   [
                       'text' => 'Forbidden Clients',
                       'url'  => 'client-list/Forbid',
                       'icon' => 'list',
                   ]/*,
                   [
                       'text' => 'Import Clients',
                       'url'  => 'client/importExport',
                       'icon' => 'list',
                   ]*/
                ],
            ],
            
            [
                'text'        => 'Job Openings',
                'icon'        => 'folder-open',
                'submenu' => [
                    [
                        'text' => 'Create Job Openings',
                        'url'  => 'jobs/create',
                        'icon'  => 'plus',
                    ],
                    [
                        'text' => 'List Job Openings',
                        'url'  => 'jobs',
                        'icon' => 'list',
                    ],
                    [
                        'text' => 'List of Closed Job',
                        'url'  => 'job/close',
                        'icon' => 'list',
                    ]
                ],
            ],

            [
                'text'        => 'Candidates',
                'icon'        => 'user',
                'submenu' => [
                    [
                        'text' => 'Add Candidate',
                        'url'  => 'candidate/create',
                        'icon' => 'plus',
                    ],
                    [
                        'text' => 'Associated | Candidates',
                        'url'  => 'candidate',
                        'icon' => 'list',
                    ],
                    [
                        'text' => 'Candidate Applicant Data',
                        'url'  => 'applicant-candidate',
                        'icon' => 'list',
                    ],
                    /*[
                        'text' => 'Import Candidates',
                        'url'  => 'candidate/importExport',
                        'icon' => 'list',
                    ],
                    [
                        'text' => 'Extract From Resume',
                        'url'  => 'candidate/resume',
                        'icon' => 'list',
                    ]*/
                ],
            ],
           [
            'text'        => 'Interview',
            'icon'        => ' fa-phone-square',
            'submenu' => [
                [
                    'text' => 'Add Interview',
                    'url'  => 'interview/create',
                    'icon' => 'plus',
                ],
                [
                    'text' => 'List Interview',
                    'url'  => 'interview',
                    'icon' => 'list',
                ]
            ]
         ],
            [
                'text'        => 'Bills',
                'icon'        => 'money',
                'submenu' => [
                    [
                        'text' => 'Create Forecasting',
                        'url'  => 'forecasting/create',
                        'icon' => 'plus',
                    ],
                    [
                        'text' => 'View Forecasting List',
                        'url'  => 'forecasting',
                        'icon' => 'list',
                    ],
                    [
                        'text' => 'View Recovery List',
                        'url'  => 'recovery',
                        'icon' => 'list',
                    ],
                    [
                        'text' => 'Cancel Forecasting',
                        'url'  => 'forecasting/cancel',
                        'icon' => 'list',
                    ],
                    [
                        'text' => 'Cancel Recovery',
                        'url'  => 'recovery/cancel',
                        'icon' => 'list',
                    ],

                ],
            ],
             [
            'text'        => "To-Dos",
            'icon'        => 'tasks',
            'submenu' => [
                [
                    'text' => "Add To-Dos",
                    'url'  => 'todos/create',
                    'icon' => 'plus',
                ],
                [
                    'text' => "List To-Dos",
                    'url'  => 'todos',
                    'icon' => 'list',
                ],
                [
                    'text' => "List Completed To-Dos",
                    'url'  => 'todos/complete',
                    'icon' => 'list',
                ],
                [
                    'text' => "My To-Dos",
                    'url'  => 'todos/mytask',
                    'icon' => 'list',
                ]
            ]
         ],
          
            [
                'text'        => 'Attendance',
                'url'         => '/userattendance',
                'icon'        => 'signal',
                'submenu' => [
                  [
                    'text' => "Attendance",
                    'url'  => '/userattendance',
                    'icon' => 'signal',
                  ],
                ],
            ],

        [
           'text'        => 'Report',
           'icon'        => 'file',
           'submenu' => [
              /* [
                   'text' => 'Selection report',
                   'url'  => 'selectionreport',
                   'icon' => 'circle-o',
               ],
               [
                   'text' => 'Recovery report',
                   'url'  => 'recoveryreport',
                   'icon' => 'circle-o',
               ],
               [
                   'text' => 'Userwise report',
                   'url'  => 'userreport',
                   'icon' => 'circle-o',
               ],*/
               [
                   'text' => 'Daily report',
                   'url'  => 'daily-report',
                   'icon' => 'circle-o',
               ],
               [
                   'text' => 'Weekly Report',
                   'url'  => 'weekly-report',
                   'icon' => 'circle-o',
               ],
               [
                   'text' => 'Monthly report',
                   'url'  => 'userwise-monthly-report',
                   'icon' => 'circle-o',
               ],
               [
                   'text' => 'Productivity Report',
                   'url'  => 'productivity-report',
                   'icon' => 'circle-o',
               ],
               [
                   'text' => 'Person-wise report',
                   'url'  => 'personwise-report',
                   'icon' => 'circle-o',
               ],
               [
                   'text' => 'Month-wise report',
                   'url'  => 'monthwise-report',
                   'icon' => 'circle-o',
               ],
               [
                   'text' => 'Eligibility report',
                   'url'  => 'eligibility-report',
                   'icon' => 'circle-o',
               ],
               [
                   'text' => 'Client-wise report',
                   'url'  => 'clientwise-report',
                   'icon' => 'circle-o',
               ],
           ],
        ],

        [
           'text'        => 'Training Material',
           'icon'        => 'graduation-cap',
           'submenu' => [
               [
                   'text' => 'Create New Training Material',
                   'url'  => 'training/create',
                   'icon' => 'plus',
               ],
               [
                   'text' => 'List of Training Material',
                   'url'  => 'training',
                   'icon' => 'list',
               ]
           ],
       ],
       [
           'text'        => 'Process Manual',
           'icon'        => 'eye',
           'submenu' => [
               [
                   'text' => 'Add Process Manual',
                   'url'  => 'process/create',
                   'icon' => 'plus',
               ],
               [
                   'text' => 'List Process Manual',
                   'url'  => 'process',
                   'icon' => 'list',
               ]
           ],
       ],
       /*[
           'text'        => 'Customer Support',
           'icon'        => 'user-md',
           'submenu' => [
               [
                   'text' => 'Add Customer Support',
                   'url'  => 'customer-support/create',
                   'icon' => 'plus',
               ],
               [
                   'text' => 'List Customer Support',
                   'url'  => 'customer-support',
                   'icon' => 'list',
               ]
           ],
       ],*/
       /*[
           'text'        => 'Finance',
           'icon'        => 'money',
           'submenu' => [
               [
                   'text' => 'Expense',
                   'url'  => 'expense',
                   'icon' => 'circle-o',
               ],
               [
                   'text' => 'Receipt',
                   'icon' => 'circle-o',
                   'submenu' => [
                      [
                         'text' => 'Receipt Talent',
                         'url'  => 'receipt/talent',
                         'icon' => 'circle-o',
                      ],
                      [
                         'text' => 'Receipt Temp',
                         'url'  => 'receipt/temp',
                         'icon' => 'circle-o',
                      ],
                      [
                         'text' => 'Receipt Other',
                         'url'  => 'receipt/other',
                         'icon' => 'circle-o',
                      ],
                   ],
               ],
           ],
       ],*/
        [
            'text'        => 'Admin',
            'icon'        => 'user-secret',
            'submenu' => [
                [
                    'text' => 'Companies',
                    'url'  => 'companies',
                    'icon' => 'circle-o',
                ],
                [
                    'text' => 'Users',
                    'url'  => 'users',
                    'icon' => 'circle-o',
                ],
                [
                    'text' => 'Roles',
                    'url'  => 'roles',
                    'icon' => 'circle-o',
                ],
                [
                    'text' => 'Attendance',
                    'url'  => 'home',
                    'icon' => 'circle-o',
                ],
                [
                    'text' => 'Accounting Head',
                    'url'  => 'accounting',
                    'icon' => 'circle-o',
                ],
                [
                    'text' => 'Vendor',
                    'url'  => 'vendors',
                    'icon' => 'circle-o',
                ],
                /*[
                    'text' => 'Expense',
                    'url'  => 'expense',
                    'icon' => 'circle-o',
                ],*/
                [
                    'text' => 'Holiday',
                    'url'  => 'holidays',
                    'icon' => 'circle-o',
                ],
                [
                    'text' => 'Module',
                    'url'  => 'module',
                    'icon' => 'circle-o',
                ],
                [
                    'text' => 'Module Visibility',
                    'url'  => 'modulevisible',
                    'icon' => 'circle-o',
                ],
                [
                    'text' => 'Client Heirarchy',
                    'url'  => 'client-heirarchy',
                    'icon' => 'circle-o',
                ],
                [
                    'text' => 'Client Remarks List',
                    'url'  => 'client-remarks',
                    'icon' => 'circle-o',
                ],
                [
                    'text' => 'Email Template',
                    'url'  => 'email-template',
                    'icon' => 'circle-o',
                ],
                [
                    'text' => 'User Bench Mark',
                    'url'  => 'user-bench-mark',
                    'icon' => 'circle-o',
                ]
                    /* [
                         'text' => 'Permissions',
                         'url'  => 'permissions',
                         'icon' => 'circle-o',
                     ],*/
                    ]
            ],



        /*[
            'text'        => 'Candidates',
            'icon'        => 'user',
            'submenu' => [
                [
                    'text' => 'Add Candidate',
                    'url'  => 'candidate/create',
                    'icon' => 'plus',
                ],
                [
                    'text' => 'List Candidates',
                    'url'  => 'candidate',
                    'icon' => 'list',
                ],
                [
                    'text' => 'Extract From Resume',
                    'url'  => 'candidate/resume',
                    'icon' => 'list',
                ]
            ],
        ],
        [
            'text'        => 'Clients',
            'icon'        => 'users',
            'submenu' => [
                [
                    'text' => 'Add Client',
                    'url'  => 'client/create',
                    'icon' => 'plus',
                ],
                [
                    'text' => 'List Clients',
                    'url'  => 'client',
                    'icon' => 'list',
                ],
                [
                    'text' => 'Import Clients',
                    'url'  => 'client/importExport',
                    'icon' => 'list',
                ]
            ],
        ],
        [
            'text'        => 'Reports',
            'icon'        => ' fa-line-chart',
            'submenu' => [
                [
                    'text' => 'Daily Report',
                    'url'  => 'dailyreport',
                    'icon' => 'plus',
                    ]
                ]
         ],
        [
            'text'        => 'Interview',
            'icon'        => ' fa-phone-square',
            'submenu' => [
                [
                    'text' => 'Add Interview',
                    'url'  => 'interview/create',
                    'icon' => 'plus',
                ],
                [
                    'text' => 'List Interview',
                    'url'  => 'interview',
                    'icon' => 'list',
                ]
            ]
         ],
       ,
        [
            'text'        => 'Admin',
            'icon'        => 'user-secret',
            'submenu' => [
                [
                    'text' => 'Companies',
                    'url'  => 'companies',
                    'icon' => 'circle-o',
                ],
                [
                    'text' => 'Users',
                    'url'  => 'users',
                    'icon' => 'circle-o',
                ],
                [
                    'text' => 'Teams',
                    'url'  => 'team',
                    'icon' => 'circle-o',
                ],
                [
                    'text' => 'Roles',
                    'url'  => 'roles',
                    'icon' => 'circle-o',
                ],
                [
                    'text' => 'Permissions',
                    'url'  => 'permissions',
                    'icon' => 'circle-o',
                ],
                [
                    'text' => 'Industry',
                    'url'  => 'industry',
                    'icon' => 'circle-o',
                ],
                [
                    'text' => 'Candidate Source',
                    'url'  => 'candidateSource',
                    'icon' => 'circle-o',
                ],
                [
                    'text' => 'Candidate Status',
                    'url'  => 'candidateStatus',
                    'icon' => 'circle-o',
                ]
            ],*/
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Choose what filters you want to include for rendering the menu.
    | You can add your own filters to this array after you've created them.
    | You can comment out the GateFilter if you don't want to use Laravel's
    | built in Gate functionality
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SubmenuFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Choose which JavaScript plugins should be included. At this moment,
    | only DataTables is supported as a plugin. Set the value to true
    | to include the JavaScript file from a CDN via a script tag.
    |
    */

    'plugins' => [
        'datatables' => true,
    ],
];
