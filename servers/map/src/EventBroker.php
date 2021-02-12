<?php

/**
 * Created by PhpStorm.
 * User: ivan.miranda
 * Date: 18/10/2017
 * Time: 17:42
 */
class EventBroker {
    /**
     * @var SplObjectStorage
     */
    protected $clients;

    /**
     * @var mywrap_con
     */
    private $connection;

    /**
     * @var Navigation
     */
    private $navigation;

    public function __construct(\SplObjectStorage &$clients, Navigation $navigation, mywrap_con $connection) {
        $this->connection = $connection;
        $this->clients = $clients;
        $this->navigation = $navigation;
    }

    private function broadcast($event, $data, $validate_field_of_view = NULL, $except_sender = NULL) {
        $message = $this->encode($event, $data);
        foreach ($this->clients as $client) {
            if (!$except_sender || $client->resourceId != $except_sender) {
                if (!$validate_field_of_view
                    || (isset($client->details)
                        && isset($client->details->tripulacao)
                        && $validate_field_of_view->in_field_of_view($client->details->tripulacao))
                ) {
                    $client->send($message);
                }
            }
        }
    }

    private function send_to($id, $event, $data) {
        $message = $this->encode($event, $data);
        foreach ($this->clients as $client) {
            if (isset($client->details)
                && isset($client->details->tripulacao)
                && $client->details->tripulacao["id"] == $id) {
                $client->send($message);
            }
        }
    }

    public function auth($from, $data) {
        if (isset($data->sg_c) && isset($data->sg_k)) {
            $from->details = new MapServerUserDetails($this->connection, $data->sg_c, $data->sg_k);

            if ($from->details->tripulacao) {
                foreach ($this->clients as $client) {
                    if ($client->resourceId != $from->resourceId
                        && $from->details->tripulacao["mar_visivel"]
                        && isset($client->details)
                        && isset($client->details->tripulacao)
                        && $from->details->in_field_of_view($client->details->tripulacao)
                    ) {
                        $client->send($this->encode("add_user", $from->details->get_public_data()));
                    }
                }
                $from->send($this->encode("field_of_view", $from->details->get_field_of_view($this->navigation)));
            }
        }
    }

    public function get_field_of_view($from) {
        if ($from->details && $from->details->tripulacao) {
            $from->send($this->encode("field_of_view", $from->details->get_field_of_view($this->navigation)));
        }
    }

    public function set_destination($from, $data) {
        if ($from->details && $from->details->tripulacao
            && isset($data->destination)
            && isset($data->destination->x)
            && isset($data->destination->y)
        ) {
            if ($from->details->travel([
                "x" => $data->destination->x,
                "y" => $data->destination->y
            ], $this->navigation)) {
                $this->broadcast("muda_status", $from->details->get_public_data(), $from->details);
            }
        }
    }

    public function update_position($from) {
        if ($from->details && $from->details->tripulacao && $from->details->tripulacao["navegacao_destino"]) {
            $from->details->update_position($this->navigation);
            if ($from->details->tripulacao["mar_visivel"]) {
                $this->broadcast("muda_status", $from->details->get_public_data(), $from->details, $from->resourceId);
            } else {
                $this->broadcast("remove_user", $from->details->get_public_data(), $from->details, $from->resourceId);
            }
            if ($from->details->combate_pve) {
                $from->send($this->encode("redirect", "combate"));
            } else {
                $this->get_field_of_view($from);
            }
        }
    }

    public function disparar($from, $data) {
        if ($from->details && $from->details->tripulacao && isset($data->destination) && isset($data->destination->x) && isset($data->destination->y)) {
            $damages = $from->details->dispara($data->destination, $this->navigation);
            if ($damages) {
                foreach ($damages as $damage) {
                    $this->broadcast("reduz_hp", $damage, $from->details, $from->resourceId);
                }
            }
            $this->broadcast("disparo", array(
                "origin" => $from->details->get_public_data(),
                "destination" => $data->destination,
                "targets" => $damages ? count($damages) : 0
            ));

            $this->get_field_of_view($from);
        }
    }

    public function disparar_alvo($from, $data) {
        if ($from->details && $from->details->tripulacao && isset($data->alvo)) {
            $result = $from->details->dispara_target($data->alvo, $this->navigation);
            if ($result) {
                if ($result["damages"]) {
                    foreach ($result["damages"] as $damage) {
                        $this->broadcast("reduz_hp", $damage, $from->details, $from->resourceId);
                    }
                }
                $this->broadcast("disparo", array(
                    "origin" => $from->details->get_public_data(),
                    "destination" => $result["destination"],
                    "targets" => $result["damages"] ? count($result["damages"]) : 0
                ));

                $this->get_field_of_view($from);
            }
        }
    }

    public function curar($from) {
        if ($from->details && $from->details->tripulacao) {
            $amount = $from->details->heal_ship();
            if ($amount) {
                $data = $from->details->get_public_data();
                $data["hp_curada"] = $amount;
                $from->send($this->encode("cura_hp", $data));
            }
        }
    }

    public function coup_de_burst($from) {
        if ($from->details && $from->details->tripulacao) {
            if ($from->details->ativa_coup_de_burst()) {
                $this->get_field_of_view($from);
                if ($from->details->tripulacao["mar_visivel"]) {
                    $this->broadcast("muda_status", $from->details->get_public_data(), $from->details, $from->resourceId);
                }
            }
        }
    }

   public function atacar($from, $data) {
       if ($from->details && $from->details->tripulacao && isset($data->alvo) && isset($data->type)) {
           $result = $from->details->attack_target($data->alvo, $data->type, $this->navigation);
           if ($result) {
               $from->send($this->encode("redirect", "combate"));
               $this->send_to($data->alvo, "redirect", "combate");
           }
       }
   }

    public function atacar_nps($from, $data) {
        if ($from->details && $from->details->tripulacao && isset($data->destination) && isset($data->destination->x) && isset($data->destination->y)) {
            if ($from->details->atacar_nps($data->destination, $this->navigation)) {
                $from->send($this->encode("redirect", "combate"));
            } else {
                $this->get_field_of_view($from);
            }
        }
    }

    public function toggle_kairouseki($from) {
        if ($from->details && $from->details->tripulacao) {
            $from->details->toggle_kairouseki();
            $this->get_field_of_view($from);
        }
    }

    private function encode($event, $data) {
        return json_encode(array(
            "event" => $event,
            "data" => $data
        ));
    }

}