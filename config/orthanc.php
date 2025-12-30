<?php

return [
    'url'  => env('ORTHANC_URL') ?: 'http://127.0.0.1:8042',
    'user' => env('ORTHANC_USER', 'orthanc'),
    'pass' => env('ORTHANC_PASS', 'orthanc'),
    'viewer_path' => env('ORTHANC_VIEWER_PATH', '/web-viewer/app/webviewer.html'),
];