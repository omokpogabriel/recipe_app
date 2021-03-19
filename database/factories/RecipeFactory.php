<?php

namespace Database\Factories;

use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RecipeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Recipe::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'recipe_name' => $this->faker->name,
            'title' => $this->faker->sentence,
            'description' =>$this->faker->realText(),
            'recipe_picture' => $this->faker->imageUrl(),
            'ingredients' => $this->faker->sentence(),
            'nutritional_value' => $this->faker->realText(20),
            'cost' => $this->faker->numberBetween(100,10000),
            'primary_ingredients' => $this->faker->sentence(5),
            'main_ingredients' => $this->faker->sentence(5),
            'meal' => 'breakfast',
            'user_id' => 1,
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->dateTime()
        ];
    }
}
