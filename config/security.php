<?php

return [
    'allowed_ips' => array_filter([
        env('MY_IP'),
        '103.87.251.207',
    ]),
];
