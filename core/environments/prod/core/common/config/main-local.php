<?php
return [
    'components' => [
        'db' => require('conf.d/db.php'),
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
    ],
];
