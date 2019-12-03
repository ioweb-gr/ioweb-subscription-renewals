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
//            $response->saveXML(__DIR__ . '/iowebdata.xml');
//            echo "<pre>" . htmlspecialchars($response->asXML()) . "</pre>";
            $info = [];

            foreach($subscriptions as $subscription){
                $name = $subscription[0]->data->gen_info->name[0];
                $expiration = '';
                $limits = $subscription[0]->data->limits;
                $id = $subscription[0]->id;
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
                    'id' => $id,
                    'name' => $name,
                    'expiration' => $expiration,
                    'link' => "/admin/subscription/overview/id/$id",
                    'edit' => "/admin/subscription/edit/id/$id"
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
