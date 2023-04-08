<?php


namespace Database\Seeders;


use App\Models\Stock;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    public function run()
    {
        Stock::create([
            'qty_beaf'          => 20,
            'qty_cheese'          => 5,
            'qty_onion'          => 1,
        ]);
    }

}
