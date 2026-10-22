<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    // Intentionally avoid RefreshDatabase here because this app uses the live Jamin MySQL schema.
}
