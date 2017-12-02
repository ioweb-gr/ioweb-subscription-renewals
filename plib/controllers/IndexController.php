<?php

    class IndexController extends pm_Controller_Action
    {
        public function indexAction()
        {
            $data = [];

            $request = <<<EOF
<webspace>
   <get>
        <filter>
        </filter>
        <dataset>
            <limits></limits>
            <!--<hosting></hosting>-->
            <gen_info></gen_info>
        </dataset>
   </get>
</webspace>
EOF;

            /**
             * @var $response SimpleXMLElement
             */
            $response = pm_ApiRpc::getService()
                ->call($request)
            ;
            $this->view->name = pm_Session::getClient()
                ->getProperty('pname')
            ;

            /**
             * @var $subscriptions SimpleXMLElement
             */
            $subscriptions = $response->webspace->get->result;
            $response->saveXML(__DIR__ . '/iowebdata.xml');
            $info = [];

            foreach($subscriptions as $subscription){
                $name = $subscription[0]->data->gen_info->name[0];
                $expiration = '';
                $limits = $subscription[0]->data->limits;
                /**
                 * @var $limits SimpleXMLElement
                 */
                foreach($limits as $limit){
                    /**
                     * @var $limit SimpleXMLElement
                     */
                    $expirationNode = $limit->xpath('limit[name="expiration"]');
                    $expiration = $expirationNode[0]->value;
                    ($expiration>0) ? $expiration = $this->convertToDate($expiration) : $expiration = 'N/A';
                }

                $info[] = [
                    'name' => $name,
                    'expiration' => $expiration
                ];
            }

            $this->view->data = $data;
            $this->view->info = $info;
        }

        private function convertToDate($timestamp){
            $date = new DateTime();
            $date->setTimestamp((int) $timestamp);
            return $date->format('Y-m-d');
        }

    }
