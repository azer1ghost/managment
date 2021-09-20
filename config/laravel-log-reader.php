<?php

return [
    'api_route_path'   => 'module/api/log-reader',
    'view_route_path'  => 'module/log-reader',
    'admin_panel_path' => '/dashboard',
    'middleware'       => ['web', 'auth', 'verified_phone']
];