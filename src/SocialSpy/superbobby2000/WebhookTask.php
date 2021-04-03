<?php

namespace SocialSpy\superbobby2000;

use pocketmine\scheduler\AsyncTask;

class WebhookTask extends AsyncTask{

    private $webhook_url, $webhook_content;

    public function __construct($webhook_url, $webhook_content){
        $this->webhook_url = $webhook_url;
        $this->webhook_content = $webhook_content;
    }

    public function onRun() {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->webhook_url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(unserialize($this->webhook_content)));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_exec($curl);
    }
}