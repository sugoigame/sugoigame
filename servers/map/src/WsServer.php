<?php
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

/**
 * Created by PhpStorm.
 * User: ivan.miranda
 * Date: 18/10/2017
 * Time: 17:38
 */
class WsServer implements MessageComponentInterface {
    /**
     * @var SplObjectStorage
     */
    protected $clients;

    /**
     * @var mywrap_con
     */
    private $connection;

    /**
     * @var EventBroker
     */
    private $eventBroker;

    /**
     * @var Navigation
     */
    private $navigation;

    public function __construct(mywrap_con $connection, Navigation $navigation) {
        $this->clients = new \SplObjectStorage();
        $this->connection = $connection;
        $this->eventBroker = new EventBroker($this->clients, $navigation, $connection);
        $this->navigation = $navigation;

        echo "Server Started!\n";
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $msg = json_decode($msg);

        $this->navigation->update();

        if ($msg && method_exists($this->eventBroker, $msg->event)) {
            $event = $msg->event;

            if (isset($from->details)) {
                unset($from->details->tripulacao);
                unset($from->details->combate_pvp);
                unset($from->details->combate_bot);
                unset($from->details->combate_pve);
                unset($from->details->in_combate);
            }
            $this->eventBroker->$event($from, $msg);
        } else {
            echo "An invalid event has be received\n";
        }
    }

    public function onClose(ConnectionInterface $conn) {
        if (isset($conn->details) && $conn->details) {
            $conn->details->destroy();
        }
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}