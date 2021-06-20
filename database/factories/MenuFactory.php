<?php

namespace Database\Factories;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Factories\Factory;

class MenuFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Menu::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker =  $this->faker;
        return [
            'menuname' => $faker->name(),
            'menutype' => $faker->randomElement($array = array ('Makanan', 'Minuman', 'Paket')),
            'menuprice' => $faker->randomElement($array = array ('20000', '40000', '60000')),
            'menuimg' => $faker->randomElement($array = array ('/images/23.png','/images/22.jpg','/images/21.jpg','/images/3.png')),
            'menuactive' => '1',
            'menuavaible' => '1',
            'menucreatedat' => now(),
            'menucreatedby' => 1,
        ];
    }
}
