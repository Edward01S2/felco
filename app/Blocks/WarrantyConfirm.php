<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

use App\Fields\Partials\GForm;

class WarrantyConfirm extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Warranty Confirm';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple Warranty Confirm block.';

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
            'label' => do_shortcode('[eid tag="{Shipping URL:16}" /]'),
            'warranty' => do_shortcode('[eid tag="{Warranty Valid:15}" /]'),
            'loc' => $this->getLoc(),
            'nowarranty' => get_field('warranty'),
            'form' => get_field('gravity'),
            'subform' => get_field('subform'),
        ];
    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        $warrantyConfirm = new FieldsBuilder('warranty_confirm');

        $warrantyConfirm
            ->addRepeater('items')
                ->addText('name')
                ->addWysiwyg('content')
                ->addImage('image')
            ->endRepeater()
            ->addWysiwyg('warranty')
            ->addFields($this->get(GForm::class))
            ->addWysiwyg('subform');

        return $warrantyConfirm->build();
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
        
        $state = $_GET['state'];

        $args = array(
            'post_type' => 'location',
            'post_status' => 'publish',
            'posts_per_page' => '1',
            'meta_query' => array(
                array(
                    'key' => 'service_states',
                    'value' => '"'.$state.'"',
                    'compare' => 'LIKE'
                )
            )
        );

        $posts = new \WP_Query($args);

        //$id = $posts->posts[0]->ID;
        
        $post_data = [];
        while($posts->have_posts()): $posts->the_post();
        
            $id = get_the_ID();

            $post_data[] = [
                'title' => get_the_title(),
                'loc' => get_field('map', $id),
                'phone' => get_field('phone', $id),
                'email' => get_field('email', $id),
            ];

        endwhile;
        wp_reset_query();

        return $post_data;
    }
}
