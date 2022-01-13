<?php

namespace App\Fields;

use Log1x\AcfComposer\Field;
use StoutLogic\AcfBuilder\FieldsBuilder;

class Locations extends Field
{
    /**
     * The field group.
     *
     * @return array
     */
    public function fields()
    {
        $locations = new FieldsBuilder('locations');

        $locations
            ->setLocation('post_type', '==', 'location');

        $locations
            // ->addText('address')
            // ->addText('city')
            // ->addText('state')
            // ->addText('postal')
            ->addText('phone')
            ->addText('email')
            ->addGoogleMap('map');


        return $locations->build();
    }
}
