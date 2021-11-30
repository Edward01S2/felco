<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

use Log1x\Navi\Facades\Navi;

class Footer extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'partials.footer',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'copyright' => get_field('copyright', 'options'),
            'footer' => $this->navigation('footer_navigation'),
        ];
    }

    public function navigation($name)
    {
        if (Navi::build()->isEmpty()) {
            return;
        }

        return Navi::build($name)->toArray();
    }

}
