<?php
namespace App\Libs;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class KapeWs implements MessageComponentInterface {
  protected $clients;
  protected $sabskreb = array();

  public function __construct() {
      $this->clients = new \SplObjectStorage;
  }

  public function onSubscribe(ConnectionInterface $conn, $topic) 
  {
    $this->sabskreb = $topic;
  }

  public function onUnSubscribe(ConnectionInterface $conn, $topic) 
  {

  }

  public function onOpen(ConnectionInterface $conn) {
      $this->clients->attach($conn);
  }

  public function onMessage(ConnectionInterface $from, $msg) {
      foreach ($this->clients as $client) {
          if ($from != $client) {
              $client->send($msg);
          }
      }
  }

  public function onClose(ConnectionInterface $conn) {
      $this->clients->detach($conn);
  }

  public function onError(ConnectionInterface $conn, \Exception $e) {
      $conn->close();
  }
}