<?php
/**
 * Copyright (c) 2017. IOWEB TECHNOLOGIES
 */

    /**
     * Created by IntelliJ IDEA.
     * User: gabtz
     * Date: 11/10/2017
     * Time: 12:53 μμ
     */

    class Modules_Iowebsubscriptionrenewals_CustomButtons extends pm_Hook_CustomButtons
    {
        public function getButtons()
        {
            return [
                [
                    'place' => self::PLACE_ADMIN_NAVIGATION,
                    'section' => 'hosting',
                    'order' => 5,
                    'title' => 'Expirations Overview',
                    'description' => 'Shows a list of subscriptions sorted by renewal date',
                    'link' => pm_Context::getActionUrl('index', 'index'),
                ]
            ];
        }

    }