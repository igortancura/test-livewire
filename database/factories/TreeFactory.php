<?php

namespace Database\Factories;

use App\Models\Tree;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tree>
 */
class TreeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tmp = Tree::where(['is_parent' => 1])->first();
        if(empty($tmp)){
          $tmp=  Tree::create([
                'name' => $this->faker->name(),
                'is_parent' => 1,
            ]);
        }
        return [
            'name' => $this->faker->name(),
            'is_parent' => 0,
            'parent_id' => $this->faker->randomElement([null, $tmp->id]),
        ];
    }
}
