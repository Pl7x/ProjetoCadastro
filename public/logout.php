<?php
require_once __DIR__ . '/../app/bootstrap.php';

use App\Providers\AuthProvider;

AuthProvider::logout();
redirect('index.php');