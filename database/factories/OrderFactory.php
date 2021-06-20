<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker =  $this->faker;
        return [
            'orderinvoice' => "KPC000000000",
           // 'orderinvoiceindex' => $number++,
            'orderboardid' => $faker->numberBetween(1,10),
            'ordertype' => 'DINEIN',
            'ordercustname' => $faker->firstNameMale(),
            'orderdate' => now(),
            'orderprice' => $faker->randomElement($array = array ('20000', '40000', '60000')),
            'orderstatus' => "PROCEED",
            'orderactive' => '1',
            'ordercreatedat' => now(),
            'ordercreatedby' => 1
        ];
    }
}