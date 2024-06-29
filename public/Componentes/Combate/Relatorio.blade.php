{{-- $relatorio, $id_azul, $avancado = false --}}

<div class="relatorio-meta-data"
    data-log='{{ str_replace("'", '&apos;', json_encode($relatorio[0], JSON_NUMERIC_CHECK)) }}'></div>
<ul class="list-group">
    @foreach ($relatorio as $index => $log)
        @if ($index <= 5)
            <li class="list-group-item">
                <h4>
                    @if (isset($log['personagem']))
                        @component('Combate.Relatorio.IconePersonagem', ['personagem' => $log['personagem'], 'id_azul' => $id_azul])
                        @endcomponent
                    @elseif (isset($log['tripulacao']))
                        {{ $log['tripulacao']['nome'] }}
                    @endif
                    {{ $log['nome'] }}
                    @if ($log['tipo'] == 'movimento')
                        se movimentou
                    @elseif ($log['tipo'] == 'passe')
                        passou a vez
                    @elseif ($log['tipo'] == 'perder_vez')
                        perdeu a vez
                    @else
                        usou: <img src="Imagens/Skils/{{ $log['habilidade']['icone'] }}.jpg" />
                        {{ $log['habilidade']['nome'] }}
                    @endif
                </h4>

                @if ($log['tipo'] == 'ataque')
                    <p>
                        {{ $log['habilidade']['descricao'] }}
                    </p>

                    @foreach ($log['consequencias'] as $consequencia)
                        <p>
                            @if (!$consequencia['alvo'])
                                Acertou um quadrado vazio
                            @else
                                @component('Combate.Relatorio.IconePersonagem', [
                                    'personagem' => $consequencia['alvo'],
                                    'id_azul' => $id_azul,
                                    'height' => '32px',
                                ])
                                @endcomponent
                                {{ $consequencia['alvo']['nome'] }}
                                @if (isset($consequencia['dano']))
                                    @if ($consequencia['dano']['esquivou'])
                                        <span class="esquiva">Se esquivou</span>
                                        @if ($avancado)
                                            <span class="text-success">Rolou no dado
                                                {{ $consequencia['dano']['dado_esquivou'] }}/100 com chance de
                                                {{ $consequencia['dano']['chance_esquiva'] }}%
                                            </span>
                                        @endif
                                    @else
                                        @if ($avancado)
                                            <span>Tentou esquivar, mas rolou no dado
                                                {{ $consequencia['dano']['dado_esquivou'] }}/100 com chance de
                                                {{ $consequencia['dano']['chance_esquiva'] }}%
                                            </span>
                                        @endif
                                        @if ($consequencia['dano']['bloqueou'])
                                            <span class="bloqueio">Bloqueou</span>
                                            @if ($avancado)
                                                <span class="text-info">Rolou no dado
                                                    {{ $consequencia['dano']['dado_bloqueou'] }}/100 com chance de
                                                    {{ $consequencia['dano']['chance_bloqueio'] }}%
                                                </span>
                                            @endif
                                        @else
                                            @if ($avancado)
                                                <span>Tentou bloquear mas rolou no dado
                                                    {{ $consequencia['dano']['dado_bloqueou'] }}/100 com chance de
                                                    {{ $consequencia['dano']['chance_bloqueio'] }}%
                                                </span>
                                            @endif
                                        @endif
                                        perdeu
                                        <strong>{{ $consequencia['dano']['dano'] }}</strong>
                                        pontos de vida
                                        @if ($consequencia['dano']['critou'])
                                            <span class="critico">Ataque crítico</span>
                                            @if ($avancado)
                                                <span class="text-danger">Rolou no dado
                                                    {{ $consequencia['dano']['dado_critou'] }}/100 com chance de
                                                    {{ $consequencia['dano']['chance_critico'] }}%
                                                </span>
                                            @endif
                                        @else
                                            @if ($avancado)
                                                <span>O ataque crítico falhou pois rolou no dado
                                                    {{ $consequencia['dano']['dado_critou'] }}/100 com chance de
                                                    {{ $consequencia['dano']['chance_critico'] }}%
                                                </span>
                                            @endif
                                        @endif
                                        @if ($consequencia['dano']['nova_hp'] <= 0)
                                            <span class="derrotado">e foi derrotado</span>
                                        @endif
                                    @endif
                                @elseif (isset($consequencia['cura']))
                                    recebeu
                                    <strong>{{ $consequencia['cura'] }}</strong>
                                    pontos de vida
                                @endif
                            @endif
                        </p>
                    @endforeach
                @endif
            </li>
        @endif
    @endforeach
</ul>
