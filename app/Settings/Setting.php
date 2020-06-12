<?php


namespace App\Settings;


use App\Footer;
use App\Page;
use Illuminate\Support\Str;
use stdClass;

class Setting
{
    protected $page;

    public function __construct(string $slug)
    {
        $this->page = new stdClass;

        $this->page->footer = new stdClass;
        $this->page->meta = Page::whereSlug($slug)->first();
        $this->page->footer->social = Footer::query()
            ->where('show',true)
            ->where('social',true)
            ->orderBy('position')
            ->get();
        $this->page->footer->left = Footer::query()
            ->where('show',true)
            ->where('social',false)
            ->where('float','left')
            ->with('page')
            ->orderBy('position')
            ->get();
        $this->page->footer->right = Footer::query()
            ->where('show',true)
            ->where('social',false)
            ->where('float','right')
            ->with('page')
            ->orderBy('position')
            ->get();

    }

    public function page()
    {
        return $this->page;
    }

    /**
     * @return stdClass
     */
    public function content(){
        $this->page->content = $this->page->meta->content;
        return $this->page;
    }

    /**
     * @param string $slug
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public static function dynamicURL(string $slug){
        $slug = Str::slug($slug);
        $page = Page::whereSlug($slug)->with('footer')->first();
        return url($page->footer->link);
    }

}
