<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class Route_Test extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
     public function test_root()
     {
         $this->visit('/')
              ->see('Sublist Manager');
     }
}
