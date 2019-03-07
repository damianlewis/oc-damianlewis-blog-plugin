<?php

namespace DamianLewis\Blog\Updates;

use DamianLewis\Blog\Models\Status;
use Seeder;

class SeedPostStatuses extends Seeder
{

    public function run()
    {
        Status::create(['name' => 'Draft']);
        Status::create(['name' => 'Published']);
        Status::create(['name' => 'Archived']);
    }
}
