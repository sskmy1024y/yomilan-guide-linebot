<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('area')->insert([
            [
                'id' => 1,
                'name' => 'グッジョバエリア',
            ],
            [
                'id' => 2,
                'name' => '太陽の広場',
            ],
            [
                'id' => 3,
                'name' => 'らんらんエリア',
            ],
            [
                'id' => 4,
                'name' => 'フラッグストリート',
            ],
            [
                'id' => 5,
                'name' => 'ファミリーエリア',
            ],
            [
                'id' => 6,
                'name' => 'バンデットエリア',
            ],
            [
                'id' => 7,
                'name' => '屋外',
            ],
            [
                'id' => 8,
                'name' => 'その他',
            ],
        ]);
    }
}
