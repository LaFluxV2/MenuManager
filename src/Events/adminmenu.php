<?php

namespace ExtensionsValley\Menumanager\Events;

\Event::listen('admin.menu.groups', function ($collection) {
    $collection->put('extensionsvalley.menumanager', [
        'menu_text' => 'Menu Panel'
        , 'menu_icon' => '<i class="fa fa-bars"></i>'
        , 'acl_key' => 'extensionsvalley.menumanager.menupanel'
        , 'main_menu_key' => 'menu.manager'
        , 'level' => '0'
        , 'sub_menu' => [
            '0' => [
                'link' => '#'
                , 'menu_text' => 'Menu Manager'
                , 'menu_icon' => '<i class="fa fa-tasks"></i>'
                , 'acl_key' => 'extensionsvalley.menumanager.menumanagement'
                , 'sub_menu_key' => 'menu.manager'
                , 'level' => '1'
                , 'sub_sub_menu' => [
                    '0' => [
                        'link' => '/admin/list/menutypes'
                        , 'menu_text' => 'Menu Types'
                        , 'menu_icon' => '<i class="fa fa-tasks"></i>'
                        , 'acl_key' => 'extensionsvalley.menumanager.menutypes'
                        , 'sub_sub_menu_key' => 'menu.manager'
                        , 'level' => '2'
                        , 'vendor' => 'ExtensionsValley'
                        , 'namespace' => 'ExtensionsValley\Menumanager'
                        , 'model' => 'Menutypes'
                    ],
                    '1' => [
                        'link' => '/admin/list/menuitems'
                        , 'menu_text' => 'Menu Items'
                        , 'menu_icon' => '<i class="fa fa-tasks"></i>'
                        , 'acl_key' => 'extensionsvalley.menumanager.menuitems'
                        , 'sub_sub_menu_key' => 'menu.manager'
                        , 'level' => '2'
                        , 'vendor' => 'ExtensionsValley'
                        , 'namespace' => 'ExtensionsValley\Menumanager'
                        , 'model' => 'Menuitems'
                    ],
                ],
            ],
        ],
    ]);
});
