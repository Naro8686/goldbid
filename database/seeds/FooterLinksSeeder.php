<?php

use App\Footer;
use App\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FooterLinksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $social = [
            ['name' => 'Вконтакте', 'link' => 'https://vk.com/publicgoldbid', 'icon' => 'site/img/settings/icon/footer/vk.png', 'position' => 1, 'social' => true],
            ['name' => 'Instagram', 'link' => 'https://www.instagram.com/onlinegoldbid/', 'icon' => 'site/img/settings/icon/footer/instagram.png', 'position' => 2, 'social' => true],
            ['name' => 'Одноклассники', 'link' => 'https://ok.ru/group/54955567481041', 'icon' => 'site/img/settings/icon/footer/ok.png', 'position' => 3, 'social' => true],
            ['name' => 'YouTube', 'link' => 'https://www.youtube.com/channel/UCZaXpR8ZVzNuYN_ZB46br_w?disable_polymer=true', 'icon' => 'site/img/settings/icon/footer/youtube.png', 'position' => 4, 'social' => true]
        ];
        $left = [
            ['link' => '/regulations', 'position' => 1, 'float' => 'left'],
            ['link' => '/terms-of-use', 'position' => 2, 'float' => 'left'],
            ['link' => '/offer', 'position' => 3, 'float' => 'left'],
            ['link' => '/privacy-policy', 'position' => 4, 'float' => 'left'],
            ['link' => '/personal-data', 'position' => 5, 'float' => 'left'],
            ['link' => '/cookie-terms-of-use', 'position' => 6, 'float' => 'left'],
        ];
        $right = [
            ['link' => '/payment-methods', 'position' => 1, 'float' => 'right'],
            ['link' => '/delivery', 'position' => 2, 'float' => 'right'],
            ['link' => '/return-of-goods-and-payment', 'position' => 3, 'float' => 'right'],
            ['link' => '/guarantee', 'position' => 4, 'float' => 'right'],
            ['link' => '/requisite', 'position' => 5, 'float' => 'right'],
        ];
        foreach ($social as $link) {
            Footer::query()->create($link);
        }
        foreach ($left as $footer) {
            $footer['link'] = Str::slug($footer['link']);
            $page = Page::whereSlug($footer['link'])->first();
            $footer['page_id'] = $page->id;
            $footer['name'] = $page->title;
            Footer::query()->create($footer);
        }
        foreach ($right as $footer) {
            $footer['link'] = Str::slug($footer['link']);
            $page = Page::whereSlug($footer['link'])->first();
            $footer['page_id'] = $page->id;
            $footer['name'] = $page->title;
            Footer::query()->create($footer);
        }

    }
}
