<?php

namespace SocialSpy\sqrrl;

use pocketmine\scheduler\AsyncTask;
use pocketmine\utils\Internet;
use pocketmine\Server;

use function igbinary_unserialize;

class WebhookTask extends AsyncTask{

    public function __construct(private string $webhook_url, private string $webhook_content) {}

    public function onRun(): void {
        $webhookError = null;
        Internet::postURL($this->webhook_url, json_encode(igbinary_unserialize($this->webhook_content)), 10, ['Content-Type: application/json'], $err);
        $this->setResult($webhookError);
    }
    
    public function onCompletion(): void {
        $webhookError = $this->getResult();
        if ($webhookError !== null) {
          Server::getInstance()->getLogger()->error("[SocialSpy] An error occurred while posting webhook: $webhookError");
        }
    }
}