<?php

use App\Models\FacilityType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('facilities')->insert([
            // [

            //     'name' => '',
            //     'description' => '',
            //     'price' => 0,
            //     'image_path' => null,
            //     'area_id' => 2,
            //     'type' => FacilityType::ATTRACTION,
            //     'latitude' => 0,
            //     'longitude' => 0,
            //     'use_pass' => false,
            //     'for_child' => false,
            //     'is_indoor' => false,
            //     'capacity' => '',
            //     'age_limit' => '',
            //     'physical_limit' => '',
            //     'require_time' => 0,
            //     'enable' => true,
            //     'url' => '',
            // ],
            [
                'name' => 'ev-グランプリ',
                'description' => '全長約1,100メートル！電気で走る次世代型ゴーカート',
                'price' => 1000,
                'image_path' => '/assets/img/facilities/web_parts_attraction_03.jpg',
                'area_id' => 1,
                'type' => FacilityType::ATTRACTION,
                'latitude' => 35.6225,
                'longitude' => 139.5190,
                'use_pass' => false,
                'for_child' => false,
                'is_indoor' => false,
                'capacity' => '1台につき2名',
                'age_limit' => '【運転席】10歳以上 【助手席】3歳以上 ※助手席が5歳未満の場合は運転者は中学生以上',
                'physical_limit' => '【運転席】身長130cm～190cm 【助手席】身長90cm～190cm',
                'require_time' => 7,
                'enable' => false,
                'url' => 'http://www.yomiuriland.com/attraction/ev-grandprix.html',
            ],
            [
                'name' => 'マイレーシング',
                'description' => '【日本初！】自分でデザインしたオリジナルカーでカーレースを楽しもう！',
                'price' => 400,
                'image_path' => '/assets/img/facilities/index_im28.jpg',
                'area_id' => 1,
                'type' => FacilityType::ATTRACTION,
                'latitude' => 35.6225,
                'longitude' => 139.5190,
                'use_pass' => true,
                'for_child' => false,
                'is_indoor' => true,
                'capacity' => '1台につき2名',
                'age_limit' => null,
                'physical_limit' => '【運転席】身長110cm以上 【助手席】身長90cm以上',
                'require_time' => 9,
                'enable' => true,
                'url' => 'http://www.yomiuriland.com/attraction/myracing.html',
            ],
            [
                'name' => 'カスタムガレージ',
                'description' => '自動車の部品を取り付け、試験走行を楽しもう！',
                'price' => 1000,
                'image_path' => '/assets/img/facilities/index_im29.jpg',
                'area_id' => 1,
                'type' => FacilityType::ATTRACTION,
                'latitude' => 35.6225,
                'longitude' => 139.5190,
                'use_pass' => true,
                'for_child' => false,
                'is_indoor' => true,
                'capacity' => '1台につき4名',
                'age_limit' => '小学生未満要付添',
                'physical_limit' => '身長90cm以上　110cm未満要付添',
                'require_time' => 6,
                'enable' => true,
                'url' => 'http://www.yomiuriland.com/attraction/customgarage.html',
            ],
            [
                'name' => 'スピンドライブ',
                'description' => 'お子さまも楽しめる自動車型アトラクション！ ドリフト体験が楽しめます',
                'price' => 300,
                'image_path' => '/assets/img/facilities/index_im31.jpg',
                'area_id' => 1,
                'type' => FacilityType::ATTRACTION,
                'latitude' => 35.6227,
                'longitude' => 139.5192,
                'use_pass' => true,
                'for_child' => true,
                'is_indoor' => false,
                'capacity' => '16名（2人乗り×8台）',
                'age_limit' => '2歳以上 2歳は要付添',
                'physical_limit' => '身長90cm未満要付添',
                'require_time' => 3,
                'enable' => true,
                'url' => '',
            ],
            [
                'name' => 'キャンパスチャレンジ',
                'description' => '「キャンパスノート」の製造工程をモチーフにした7つのゲームを楽しもう！',
                'price' => 800,
                'image_path' => '/assets/img/facilities/index_im32.jpg',
                'area_id' => 1,
                'type' => FacilityType::ATTRACTION,
                'latitude' => 35.6238,
                'longitude' => 139.5184,
                'use_pass' => true,
                'for_child' => false,
                'is_indoor' => true,
                'capacity' => '3名',
                'age_limit' => '5歳以上',
                'physical_limit' => null,
                'require_time' => 10,
                'enable' => false,
                'url' => 'http://www.yomiuriland.com/attraction/campuschallenge.html',
            ],
            [
                'name' => 'ひらめキッズ',
                'description' => '「コクヨのえほん」をテーマにしたお子さま向けの遊び場',
                'price' => 0,
                'image_path' => '/assets/img/facilities/index_im33.jpg',
                'area_id' => 1,
                'type' => FacilityType::ATTRACTION,
                'latitude' => 35.6238,
                'longitude' => 139.5184,
                'use_pass' => true,
                'for_child' => true,
                'is_indoor' => true,
                'capacity' => '45人',
                'age_limit' => '小学生以下 ※小学生未満要付添',
                'physical_limit' => null,
                'require_time' => null,
                'enable' => true,
                'url' => 'http://www.yomiuriland.com/attraction/hiramekids.html',
            ],
            [
                'name' => 'ちえくらべ「たまゴロー」',
                'description' => '【日本初！】見ても参加しても楽しい！ボールコースター型アトラクション',
                'price' => 400,
                'image_path' => '/assets/img/facilities/index_im34.jpg',
                'area_id' => 1,
                'type' => FacilityType::ATTRACTION,
                'latitude' => 35.6231,
                'longitude' => 139.5189,
                'use_pass' => true,
                'for_child' => false,
                'is_indoor' => false,
                'capacity' => '70名',
                'age_limit' => '3歳以上、6歳未満要付添',
                'physical_limit' => null,
                'require_time' => 20,
                'enable' => true,
                'url' => 'http://www.yomiuriland.com/attraction/tamagoro.html',
            ],
            [
                'name' => 'カドケシとろっこ',
                'description' => 'コクヨの人気商品「カドケシ」をモチーフにしたトロッコ型アトラクション',
                'price' => 300,
                'image_path' => '/assets/img/facilities/index_im35.jpg',
                'area_id' => 1,
                'type' => FacilityType::ATTRACTION,
                'latitude' => 35.6230,
                'longitude' => 139.5189,
                'use_pass' => true,
                'for_child' => true,
                'is_indoor' => false,
                'capacity' => '1台につき2名',
                'age_limit' => '2歳以上、6歳未満要付添',
                'physical_limit' => null,
                'require_time' => 3,
                'enable' => true,
                'url' => 'http://www.yomiuriland.com/attraction/kadokeshitorokko.html',
            ],
            [
                'name' => 'えんぴつタワー',
                'description' => 'シャープペンシルと定規をモチーフにしたタワー型アトラクション',
                'price' => 400,
                'image_path' => '/assets/img/facilities/index_im36.jpg',
                'area_id' => 1,
                'type' => FacilityType::ATTRACTION,
                'latitude' => 35.6230,
                'longitude' => 139.5189,
                'use_pass' => true,
                'for_child' => true,
                'is_indoor' => false,
                'capacity' => '8名（2人乗り×4台）',
                'age_limit' => null,
                'physical_limit' => '身長110cm以上、120cm未満要付添',
                'require_time' => 3,
                'enable' => true,
                'url' => 'http://www.yomiuriland.com/attraction/enpitutower.html',
            ],
            [
                'name' => 'くるくるコンパス',
                'description' => 'コンパスと色鉛筆をモチーフにした回転型アトラクション',
                'price' => 400,
                'image_path' => '/assets/img/facilities/index_im37.jpg',
                'area_id' => 1,
                'type' => FacilityType::ATTRACTION,
                'latitude' => 35.6229,
                'longitude' => 139.5190,
                'use_pass' => true,
                'for_child' => true,
                'is_indoor' => false,
                'capacity' => '12人（2人乗り×6台）',
                'age_limit' => null,
                'physical_limit' => '身長90cm以上、120cm未満要付添',
                'require_time' => 3,
                'enable' => true,
                'url' => 'http://www.yomiuriland.com/attraction/kurukurucompass.html',
            ],
            [
                'name' => 'スプラッシュU.F.O.',
                'description' => '【日本初！！】「日清焼そばU.F.O.」になれる！映像ゲーム機能付きボートライド',
                'price' => 1000,
                'image_path' => '/assets/img/facilities/index_im38.jpg',
                'area_id' => 1,
                'type' => FacilityType::ATTRACTION,
                'latitude' => 35.6230,
                'longitude' => 139.5185,
                'use_pass' => true,
                'for_child' => false,
                'is_indoor' => true,
                'capacity' => '	1台につき4名',
                'age_limit' => null,
                'physical_limit' => '身長110cm以上、130cm未満要付添',
                'require_time' => 4,
                'enable' => true,
                'url' => 'http://www.yomiuriland.com/attraction/splashufo.html',
            ],
            [
                'name' => 'U.F.O.バンプ！',
                'description' => '「日清焼そばU.F.O.」をイメージした10台のライドがぶつかり合います！',
                'price' => 400,
                'image_path' => '/assets/img/facilities/index_im39.jpg',
                'area_id' => 1,
                'type' => FacilityType::ATTRACTION,
                'latitude' => 35.6230,
                'longitude' => 139.5185,
                'use_pass' => true,
                'for_child' => false,
                'is_indoor' => true,
                'capacity' => '10名（1人乗り×10台）',
                'age_limit' => null,
                'physical_limit' => '身長115cm以上',
                'require_time' => 3,
                'enable' => true,
                'url' => 'http://www.yomiuriland.com/attraction/ufobump.html',
            ],
            [
                'name' => 'スピンランウェイ',
                'description' => '日本初となる、らせん状のスパイラルリフトを採用した屋内型コースター！',
                'price' => 1000,
                'image_path' => '/assets/img/facilities/index_im40.jpg',
                'area_id' => 1,
                'type' => FacilityType::ATTRACTION,
                'latitude' => 35.6234,
                'longitude' => 139.5181,
                'use_pass' => true,
                'for_child' => false,
                'is_indoor' => true,
                'capacity' => '1台につき4名	',
                'age_limit' => '4歳以上',
                'physical_limit' => '身長110cm以上、130cm未満要付添',
                'require_time' => 3,
                'enable' => true,
                'url' => 'http://www.yomiuriland.com/attraction/spinrunway.html',
            ],
            [
                'name' => 'マイニット',
                'description' => '体を動かしてオリジナルの編み物をつくろう！',
                'price' => 300,
                'image_path' => '/assets/img/facilities/index_im41.jpg',
                'area_id' => 1,
                'type' => FacilityType::ATTRACTION,
                'latitude' => 35.6234,
                'longitude' => 139.5181,
                'use_pass' => false,
                'for_child' => false,
                'is_indoor' => false,
                'capacity' => '1台につき2名',
                'age_limit' => '小学生未満要付添',
                'physical_limit' => null,
                'require_time' => 3,
                'enable' => true,
                'url' => 'http://www.yomiuriland.com/attraction/myknit.html',
            ],
            [
                'name' => 'SKYパト',
                'description' => '映像ゲーム付自転車型ライドで「グッジョバ!!」内をパトロール',
                'price' => 600,
                'image_path' => '/assets/img/facilities/index_im42.jpg',
                'area_id' => 1,
                'type' => FacilityType::ATTRACTION,
                'latitude' => 35.6229,
                'longitude' => 139.5194,
                'use_pass' => true,
                'for_child' => false,
                'is_indoor' => false,
                'capacity' => '1台につき2名',
                'age_limit' => '（運転席）6歳以上 （助手席）2歳以上',
                'physical_limit' => '（運転席）身長130cm以上',
                'require_time' => 6,
                'enable' => true,
                'url' => 'http://www.yomiuriland.com/attraction/skypat.html',
            ],
            // 以下、適当登録
            [
                'name' => 'フロッグホッパー',
                'description' => 'みんなで楽しめるホッピング型アトラクション！',
                'price' => 300,
                'image_path' => '/assets/img/facilities/index_im38.jpg',
                'area_id' => 2,
                'type' => FacilityType::ATTRACTION,
                'latitude' => 35.6355,
                'longitude' => 139.5174,
                'use_pass' => true,
                'for_child' => true,
                'is_indoor' => false,
                'capacity' => '',
                'age_limit' => '',
                'physical_limit' => '',
                'require_time' => 5,
                'enable' => true,
                'url' => 'http://www.yomiuriland.com/attraction/frog.html',
            ],
            [
                'name' => 'ぐるぐるドライブ',
                'description' => 'お子さま一人で乗れる自動車型アトラクション',
                'price' => 300,
                'image_path' => null,
                'area_id' => 2,
                'type' => FacilityType::ATTRACTION,
                'latitude' => 35.6355,
                'longitude' => 139.5174,
                'use_pass' => true,
                'for_child' => true,
                'is_indoor' => false,
                'capacity' => '',
                'age_limit' => '',
                'physical_limit' => '',
                'require_time' => 5,
                'enable' => true,
                'url' => 'http://www.yomiuriland.com/attraction/post.html',
            ],
            // メリーゴーランドドック
            [
                'name' => 'ハシビロGO！',
                'description' => '「ディスク・オー」の超大型サイズが日本で初登場！',
                'price' => 600,
                'image_path' => '/assets/img/facilities/da56978b7eba85bbdf8187280c564984.jpg',
                'area_id' => 3,
                'type' => FacilityType::ATTRACTION,
                'latitude' => 35.6248,
                'longitude' => 139.5213,
                'use_pass' => true,
                'for_child' => false,
                'is_indoor' => false,
                'capacity' => '',
                'age_limit' => '',
                'physical_limit' => '',
                'require_time' => 5,
                'enable' => true,
                'url' => 'http://www.yomiuriland.com/attraction/hashibiro-go.html',
            ],
            [
                'name' => 'クレージーヒュー・ストン',
                'description' => '叫ばずにはいられない！',
                'price' => 600,
                'image_path' => '/assets/img/facilities/index_im04.jpg',
                'area_id' => 3,
                'type' => FacilityType::ATTRACTION,
                'latitude' => 35.6248,
                'longitude' => 139.5216,
                'use_pass' => true,
                'for_child' => false,
                'is_indoor' => false,
                'capacity' => '',
                'age_limit' => '',
                'physical_limit' => '',
                'require_time' => 5,
                'enable' => true,
                'url' => 'http://www.yomiuriland.com/attraction/houston.html',
            ],
            [
                'name' => 'バンジージャンプ',
                'description' => '迷わず飛べ！高さ22メートルからのジャンプ！',
                'price' => 1200,
                'image_path' => '/assets/img/facilities/index_im05.jpg',
                'area_id' => 3,
                'type' => FacilityType::ATTRACTION,
                'latitude' => 35.6252,
                'longitude' => 139.5216,
                'use_pass' => false,
                'for_child' => false,
                'is_indoor' => false,
                'capacity' => '',
                'age_limit' => '12才以上',
                'physical_limit' => '',
                'require_time' => 5,
                'enable' => true,
                'url' => 'https://www.yomiuriland.com/attraction/bungee.html',
            ],
            [
                'name' => 'ジャイアントスカイリバー',
                'description' => '長さ386メートル！スカッと爽快！急流下り！',
                'price' => 700,
                'image_path' => '/assets/img/facilities/index_im09.jpg',
                'area_id' => 3,
                'type' => FacilityType::ATTRACTION,
                'latitude' => 35.6245,
                'longitude' => 139.5208,
                'use_pass' => true,
                'for_child' => false,
                'is_indoor' => false,
                'capacity' => '',
                'age_limit' => '',
                'physical_limit' => '',
                'require_time' => 5,
                'enable' => true,
                'url' => 'http://www.yomiuriland.com/attraction/skyriver.html',
            ],
            [
                'name' => 'ミルキーウェイ',
                'description' => 'まるで大空を飛んでいるような気分になれる！',
                'price' => 400,
                'image_path' => '/assets/img/facilities/cbadd5ee1609bb66b3d73ffaad6a7335.jpg',
                'area_id' => 3,
                'type' => FacilityType::ATTRACTION,
                'latitude' => 35.6245,
                'longitude' => 139.5202,
                'use_pass' => true,
                'for_child' => false,
                'is_indoor' => false,
                'capacity' => '',
                'age_limit' => '',
                'physical_limit' => '120cm以上',
                'require_time' => 5,
                'enable' => true,
                'url' => 'https://www.yomiuriland.com/attraction/swinger.html',
            ],
            // アシカショー
            [
                'name' => 'ループコースターMOMOnGA',
                'description' => '日本で最初の立ち乗り回転コースター！',
                'price' => 600,
                'image_path' => '/assets/img/facilities/27a48afce7dd99d46bc7571f34c5b76c.jpg',
                'area_id' => 4,
                'type' => FacilityType::ATTRACTION,
                'latitude' => 35.6244,
                'longitude' => 139.5196,
                'use_pass' => true,
                'for_child' => false,
                'is_indoor' => false,
                'capacity' => '',
                'age_limit' => '',
                'physical_limit' => '',
                'require_time' => 5,
                'enable' => true,
                'url' => 'http://www.yomiuriland.com/attraction/momonga.html',
            ],
            [
                'name' => 'お化け屋敷ひゅ～どろ',
                'description' => 'イベントごとに怖さが変わるお化け屋敷！',
                'price' => 400,
                'image_path' => '/assets/img/facilities/d47356d82b8f2650e6e52fd695b2a90d.jpg',
                'area_id' => 4,
                'type' => FacilityType::ATTRACTION,
                'latitude' => 35.6251,
                'longitude' => 139.5185,
                'use_pass' => true,
                'for_child' => true,
                'is_indoor' => true,
                'capacity' => '',
                'age_limit' => '',
                'physical_limit' => '',
                'require_time' => 5,
                'enable' => true,
                'url' => 'http://www.yomiuriland.com/attraction/obake.html',
            ],
            // ヒーロートレーニングセンター
            // ミラクルわんルーム
            [
                'name' => 'わんわんコースターわんデット',
                'description' => 'ランドドッグがコースターに！',
                'price' => 400,
                'image_path' => '/assets/img/facilities/index_im13.jpg',
                'area_id' => 4,
                'type' => FacilityType::ATTRACTION,
                'latitude' => 35.6350,
                'longitude' => 139.5183,
                'use_pass' => true,
                'for_child' => true,
                'is_indoor' => false,
                'capacity' => '',
                'age_limit' => '',
                'physical_limit' => '',
                'require_time' => 5,
                'enable' => true,
                'url' => 'http://www.yomiuriland.com/attraction/wandit.html',
            ],
            [
                'name' => 'ルーピングスターシップ',
                'description' => '360度宙返りのスリル！',
                'price' => 600,
                'image_path' => '/assets/img/facilities/index_im14.jpg',
                'area_id' => 4,
                'type' => FacilityType::ATTRACTION,
                'latitude' => 35.6246,
                'longitude' => 139.5197,
                'use_pass' => true,
                'for_child' => false,
                'is_indoor' => false,
                'capacity' => '',
                'age_limit' => '',
                'physical_limit' => '',
                'require_time' => 5,
                'enable' => true,
                'url' => 'https://www.yomiuriland.com/attraction/starship.html',
            ],
            // プテラサイクル
            // ジュラシックカー
            // ゴーカート ファミリーコース
            // ゴーカート ハイウェイコース
            // プレイパーク
            // SHATEKI
            // スイーツカップ
            [
                'name' => '大観覧車',
                'description' => '遊園地の代表アトラクション！',
                'price' => 800,
                'image_path' => '/assets/img/facilities/index_im18.jpg',
                'area_id' => 5,
                'type' => FacilityType::ATTRACTION,
                'latitude' => 35.6256,
                'longitude' => 139.5164,
                'use_pass' => true,
                'for_child' => true,
                'is_indoor' => true,
                'capacity' => '',
                'age_limit' => '',
                'physical_limit' => '',
                'require_time' => 13,
                'enable' => true,
                'url' => 'http://www.yomiuriland.com/attraction/wheel.html',
            ],
            // ちびっこ消防車
            [
                'name' => 'アニマルコースター',
                'description' => 'みんなで楽しめる！かわいい動物たちのコースター！！',
                'price' => 300,
                'image_path' => null,
                'area_id' => 5,
                'type' => FacilityType::ATTRACTION,
                'latitude' => 35.6258,
                'longitude' => 139.5170,
                'use_pass' => false,
                'for_child' => false,
                'is_indoor' => false,
                'capacity' => '',
                'age_limit' => '',
                'physical_limit' => '',
                'require_time' => 5,
                'enable' => true,
                'url' => 'http://www.yomiuriland.com/attraction/animalcoaster.html',
            ],
            // わんぱくなんとか
            [
                'name' => 'バンデット',
                'description' => 'よみうりランド不動の人気No.1コースター',
                'price' => 1200,
                'image_path' => '/assets/img/facilities/index_im01.jpg',
                'area_id' => 6,
                'type' => FacilityType::ATTRACTION,
                'latitude' => 35.6257,
                'longitude' => 139.5181,
                'use_pass' => true,
                'for_child' => false,
                'is_indoor' => false,
                'capacity' => '28人乗り',
                'age_limit' => '小学生以上',
                'physical_limit' => '身長120cm以上',
                'require_time' => 10,
                'enable' => true,
                'url' => 'http://www.yomiuriland.com/attraction/bandit.html',
            ],
            // アニマルレスキュー
            // レーザーアスレチック

            // 以下：レストラン
            [
                'name' => 'グッジョバ!!KITCHEN',
                'description' => 'グッジョバ！！エリア入口入ってすぐのおしゃれな屋内型レストラン！「ロイヤルホスト」や「シズラー」を手掛けるロイヤルグループの直営店です。1日3組、ご予約限定でお誕生月やご結婚記念日など、ささやかなお祝いができます！',
                'price' => 0,
                'image_path' => null,
                'area_id' => 1,
                'type' => FacilityType::RESTAURANT,
                'latitude' => 35.62239,
                'longitude' => 139.5183,
                'use_pass' => false,
                'for_child' => false,
                'is_indoor' => true,
                'capacity' => '',
                'age_limit' => '',
                'physical_limit' => '',
                'require_time' => 45,
                'enable' => true,
                'url' => 'http://www.yomiuriland.com/restaurant_shop/kitchen.html',
            ],
            [
                'name' => 'Goodday（グッディ）',
                'description' => '洋食を中心とした幅広いメニューがお楽しみいただけるレストラン。',
                'price' => 0,
                'image_path' => null,
                'area_id' => 2,
                'type' => FacilityType::RESTAURANT,
                'latitude' => 35.6251,
                'longitude' => 139.5171,
                'use_pass' => false,
                'for_child' => false,
                'is_indoor' => true,
                'capacity' => '',
                'age_limit' => '',
                'physical_limit' => '',
                'require_time' => 45,
                'enable' => true,
                'url' => 'http://www.yomiuriland.com/restaurant_shop/goodday.html',
            ],
            // その他いろいろは必要に応じて追加
        ]);
    }
}
