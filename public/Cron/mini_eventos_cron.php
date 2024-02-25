<?php
function cron_update_mini_eventos()
{
    global $connection;

    function spawn_rdms($event)
    {
        for ($i = 0; $i < $event["quant"]; $i++) {
            spawn_rdm_in_random_coord(
                $event["mares"][array_rand($event["mares"])],
                $event["zonas"][array_rand($event["zonas"])]
            );
        }
    }

    $events_details = DataLoader::load("mini_eventos");
    $events = $connection->run(
        "SELECT * FROM tb_mini_eventos WHERE fim < NOW()"
    )->fetch_all_array();

    foreach ($events as $event) {
        $event_detail = $events_details[$event["id"]];

        $connection->run(
            "UPDATE tb_mini_eventos
            SET fim = ADDTIME(current_timestamp, ?), inicio = current_timestamp, pack_recompensa = ?
            WHERE id = ?",
            "sii", array($event_detail["duracao"], array_rand($event_detail["recompensas"]), $event["id"]));

        $connection->run("DELETE FROM tb_mapa_rdm WHERE rdm_id IN (" . implode(",", $event_detail["zonas"]) . ")");
        spawn_rdms($event_detail);
        $connection->run("DELETE FROM tb_mini_eventos_concluidos WHERE mini_evento_id = ?", "i", array($event["id"]));
    }

    $ids = array_keys($events_details);
    $result = $connection->run("SELECT * FROM tb_mini_eventos WHERE id IN (" . implode(",", $ids) . ")");
    if (count($events_details) < $result->count()) {
        $events_in_db = $result->fetch_all_array();
        foreach ($events_details as $id => $event) {
            $event_in_db = array_find($events_in_db, ["id" => $id]);
            if (! $event_in_db) {
                $connection->run(
                    "INSERT INTO tb_mini_eventos (id, fim, pack_recompensa)
                    VALUE (?, ADDTIME(current_timestamp, ?), ?)",
                    "isi", array($id, $event["duracao"], array_rand($event["recompensas"])));

                $connection->run("DELETE FROM tb_mapa_rdm WHERE rdm_id IN (" . implode(",", $event["zonas"]) . ")");
                spawn_rdms($event);
            }
        }
    }
}
// essa rotina pode rodar em qualquer requisicao de qualquer jogador
cron_update_mini_eventos();
