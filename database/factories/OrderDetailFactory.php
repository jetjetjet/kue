<?php

namespace Database\Factories;

use App\Models\OrderDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderDetailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderDetail::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker =  $this->faker;
        return [
            //'odorderid' => "KPC000000000",
            'odmenuid' => 1,
            'odqty' => 1,
            'odprice' => 20000,
            'odtotalprice' => 20000,
            'oddelivered' => '1',
            //'odindex' => "KPC000000000",
            'odactive' => "1",
            'odcreatedat' => now(),
            'odcreatedby' => 1,
        ];
    }
}