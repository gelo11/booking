<?php
return array(
    'module'   => array(
        'class' => 'application.modules.booking.BookingModule',
    ),
    'import'    => array(
        'application.modules.booking.models.*',
    ),
    'component' => array(),
    'rules'     => array(
        '/booking/<alias>' => 'booking/booking/show',
        '/booking/variants/<alias>' => 'booking/booking/variants',
        '/booking/variant/<alias>' => 'booking/booking/variant',
        '/reservation/<alias>' => 'booking/reservation/show',
        '/reservation/variants/<alias>' => 'booking/reservation/variants',
        '/reservation/variant/<alias>' => 'booking/reservation/variant',
    ),
);