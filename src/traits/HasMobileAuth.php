<?php

namespace SevenSparky\LaravelMobileAuth\traits;

trait HasMobileAuth
{
    public function initializeHasMobileAuth()
    {
        $this->fillable[] = 'phone';
        $this->fillable[] = 'attempts_left';
        $this->fillable[] = 'must_login_with_otp';
    }
}
