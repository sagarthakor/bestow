<?php
/**
 * Created by PhpStorm.
 * User: ketan
 * Date: 24/8/21
 * Time: 10:49 AM
 */

namespace App\Library\UserWebsite;


use App\Models\UserWebsite;

class Manager
{
    private $website;

    public function setWebsite(?UserWebsite $userWebsite)
    {
        $this->website = $userWebsite;
        return $this;
    }

    public function getWebsite()
    {
        return $this->website;
    }

    public function loadWebsite($identifier): bool
    {
        if (!$userWebsite = UserWebsite::wherePrefix($identifier)->active()->with(['user'])->first())
            return false;

        $this->setWebsite($userWebsite);
        return true;
    }
}