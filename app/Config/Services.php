<?php

namespace Config;

use App\Services\AuthLogger;
use CodeIgniter\Config\BaseService;

class Services extends BaseService
{
    public static function authLogger($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('authLogger');
        }

        return new AuthLogger(\Config\Services::request());
    }
}