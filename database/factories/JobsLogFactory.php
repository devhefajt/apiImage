<?php

namespace Database\Factories;

use App\Models\JobsLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobsLogFactory extends Factory
{
    protected $model = JobsLog::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'description' => $this->faker->sentence(),
            'last_id' => null, // Do not generate any fake data for this column
        ];
    }
}
