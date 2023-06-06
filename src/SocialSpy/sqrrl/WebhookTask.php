<?php

namespace SocialSpy\sqrrl;

use pocketmine\scheduler\AsyncTask;

class WebhookTask extends AsyncTask{

    private string $webhook_url, $webhook_content;

    public function __construct($webhook_url, $webhook_content){
        $this->webhook_url = $webhook_url;
        $this->webhook_content = $webhook_content;
    }

    public function onRun(): void {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->webhook_url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(unserialize($this->webhook_content)));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_exec($curl);
    }
}