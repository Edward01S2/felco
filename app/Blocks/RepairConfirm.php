<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class RepairConfirm extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Repair Confirm';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple Repair Confirm block.';

    /**
     * The block category.
     *
     * @var string
     */
    public $category = 'acf';

    /**
     * The block icon.
     *
     * @var string|array
     */
    public $icon = 'editor-ul';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = [];

    /**
     * The block post type allow list.
     *
     * @var array
     */
    public $post_types = [];

    /**
     * The parent block type allow list.
     *
     * @var array
     */
    public $parent = [];

    /**
     * The default block mode.
     *
     * @var string
     */
    public $mode = 'edit';

    /**
     * The default block alignment.
     *
     * @var string
     */
    public $align = 'center';

    /**
     * The default block text alignment.
     *
     * @var string
     */
    public $align_text = '';

    /**
     * The default block content alignment.
     *
     * @var string
     */
    public $align_content = '';

    /**
     * The supported block features.
     *
     * @var array
     */
    public $supports = [
        'align' => true,
        'align_text' => false,
        'align_content' => false,
        'full_height' => false,
        'anchor' => false,
        'mode' => false,
        'multiple' => true,
        'jsx' => true,
    ];

    /**
     * The block styles.
     *
     * @var array
     */
    public $styles = [
        [
            'name' => 'light',
            'label' => 'Light',
            'isDefault' => true,
        ],
        [
            'name' => 'dark',
            'label' => 'Dark',
        ]
    ];

    /**
     * The block preview example data.
     *
     * @var array
     */
    public $example = [
        'items' => [
            ['item' => 'Item one'],
            ['item' => 'Item two'],
            ['item' => 'Item three'],
        ],
    ];

    /**
     * Data to be passed to the block before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'items' => $this->items(),
            'part' => do_shortcode('[eid tag="{Which part needs repair?:1}" /]'),
            'loc' => $this->getLoc(),
        ];
    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        $repairConfirm = new FieldsBuilder('repair_confirm');

        $repairConfirm
            ->addRepeater('items')
                ->addText('name')
                ->addWysiwyg('content')
                ->addImage('image')
            ->endRepeater();

        return $repairConfirm->build();
    }

    /**
     * Return the items field.
     *
     * @return array
     */
    public function items()
    {
        return get_field('items') ?: $this->example['items'];
    }

    /**
     * Assets to be enqueued when rendering the block.
     *
     * @return void
     */
    public function enqueue()
    {
        //
    }

    public function getLoc() {
        $args = array(
            'post_type' => 'location',
            'post_status' => 'publish',
            'posts_per_page' => '1',
        );

        $posts = new \WP_Query($args);

        //$id = $posts->posts[0]->ID;
        
        $post_data = [];
        while($posts->have_posts()): $posts->the_post();
        
            $id = get_the_ID();

            $post_data[] = [
                'title' => get_the_title(),
                'loc' => get_field('map', $id)
            ];

        endwhile;
        wp_reset_query();

        return $post_data;
    }
}