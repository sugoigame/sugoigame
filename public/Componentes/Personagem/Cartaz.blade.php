{{-- $pers, $faccao --}}
@php
    $faccao_nome = $faccao == FACCAO_PIRATA ? 'pirate' : 'marine';
@endphp
<div class="cartaz_procurado tripulante_quadro {{ $faccao_nome }}">
    @component('Personagem.Rosto', ['pers' => $pers, 'class' => "tripulante_quadro_img $faccao_nome"])
    @endcomponent
    <div class="recompensa_text {{ $faccao_nome }}">
        <?php if ($faccao == FACCAO_MARINHA) : ?>
        <div class="recompensa_stars">
            {{ get_cross_guild_stars(calc_recompensa($pers['fama_ameaca'])) }}
        </div>
        <?php endif; ?>
        <div></div>
        <div class="recompensa_name">
            <?= $pers['nome'] ?>
        </div>
        <div class="recompensa_value">
            {{ mascara_berries(calc_recompensa($pers['fama_ameaca'])) }}
        </div>
    </div>
</div>
