<?php

namespace App\Fields;

use Log1x\AcfComposer\Field;
use StoutLogic\AcfBuilder\FieldsBuilder;

class Request extends Field
{
    /**
     * The field group.
     *
     * @return array
     */
    public function fields()
    {
        $request = new FieldsBuilder('request', [
            'position' => 'acf_after_title',
        ]);

        $request
            ->setLocation('post_type', '==', 'request');

        $request
            ->addText('first_name')
                ->setWidth('50')
            ->addText('last_name')
                ->setWidth('50')
            ->addText('business_name')
                ->setWidth('50')
            ->addText('address')
            ->addText('city')
                ->setWidth('33')
            ->addText('state')
                ->setWidth('33')
            ->addText('zipcode')
                ->setWidth('33')
            ->addText('email')
                ->setWidth('50')
            ->addText('phone_number')
                ->setWidth('50')
            ->addText('request_type')
                ->setWidth('33')
            ->addText('service_type')
                ->setWidth('33')
            ->addText('price')
                ->setWidth('33')
            ->addText('part')
                ->setWidth('50')
            ->addText('part_name')
                ->setWidth('50')
            ->addText('serial')
                ->setWidth('50')
            ->addTextarea('repair_reason', [
                'rows' => '4'
            ])
            ->addText('warranty')
            ->addText('shipping_url')
            ->addTextarea('shipengine_response')
            ->addTextarea('location')
            ->addText('notify_email')
            ->addGallery('files')
            ;


        return $request->build();
    }
}
