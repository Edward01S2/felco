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
            ->addGoogleMap('map');


        return $locations->build();
    }
}
