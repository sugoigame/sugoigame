cont_rota = 0;
$(function() {
	headerAppendFunctions();
	verifica_nova_msg();

	verificaSeTemNav();

	$(document).on('click', '.personagem:not(.personagem-selecionavel)', function() {
		if ($(document).width() < 1000) {
			var cod = $(this).data('cod');
			$('.personagem-info:not(#personagem-info-' + cod + ')').addClass('hidden');
			$('#personagem-info-' + cod)
				.css('top', 0)
				.css('left', 0)
				.toggleClass('hidden');
		}
	});
});
xatual = myCoord.x;
yatual = myCoord.y;
quadro = 0;

function headerAppendFunctions() {
	$(document).on('click', '#div_icon_progress > a', function() {
		$('#div_icon_progress img')
			.css('animation', 'none')
			.css('-webkit-animation', 'none');

		var title = $(this).data('title');
		var description = $(this).data('description');
		var xpReward = $(this).data('xp');
		var berriesReward = $(this).data('berries');
		var finished = $(this).data('finished');

		$('#user-progress-title').html(title);
		$('#user-progress-description').html(description)
		$('#user-progress-rewards').html((xpReward || berriesReward ? 'Recompensas: <ul class="text-left">' : '') +
			(xpReward ? '<li>' + xpReward + ' pontos de experiência</li>' : '') +
			(berriesReward ? '<li><img src="Imagens/Icones/Berries.png" /> ' + berriesReward + '</li>' : '') +
			'</ul>');

		if (finished) {
			$('#user-progress-finish').css('display', 'inline-block');
			$('#user-progress-back').css('display', 'none');
		} else {
			$('#user-progress-finish').css('display', 'none');
			$('#user-progress-back').css('display', 'inline-block');
		}

		$('#modal-user-progress').modal();
	});

	// DenDen
	$(document).on("click", "#div_icon_denden", function() {
		$("#denden_mushi")
			.popover('hide')
			.attr("src", "Imagens/Icones/Denden_1.png");
		$("#alerta_denden").fadeOut(100);
		pauseAudio('toque_nova_msg', true);
		$.ajax({
			type: 'get',
			url: 'Scripts/Denden/mensagens_listar.php',
			cache: false,
			success: function(retorno) {
				retorno = retorno.trim();
				if (retorno.substr(0, 1) == "#") {
					bancandoEspertinho(retorno.substr(1, retorno.length - 1));
				} else {
					$("#mensagens").html(retorno);
					$(".mensagem_ler").click(function() {
						var cod = $(this).data('cod');
						$.ajax({
							type: 'get',
							url: 'Scripts/Denden/mensagem_ler.php',
							data: 'cod=' + cod,
							cache: false,
							success: function(retorno) {
								retorno = retorno.trim();
								if (retorno.substr(0, 1) == "#") {
									bancandoEspertinho(retorno.substr(1, retorno.length - 1));
								} else {
									$("#mensagens").html(retorno);
								}
							}
						});
					});
				}
			}
		});
	});
	$(document).on("click", "#bt_msg_listar", function() {
		$("#mensagens").html("");
		$("#div_icon_denden").click();
	});

	function loadNewMsgForm(callback) {
		$.ajax({
			type: 'get',
			url: 'Scripts/Denden/nova_msg_form.php',
			cache: false,
			success: function(retorno) {
				retorno = retorno.trim();
				if (retorno.substr(0, 1) == "#") {
					bancandoEspertinho(retorno.substr(1, retorno.length - 1));
				} else {
					$("#mensagens").html(retorno);
					if (callback) {
						callback();
					}
				}
			}
		});
	}

	$(document).on("click", "#bt_nova_msg", function() {
		loadNewMsgForm();
	});
	$(document).on("click", "#bt_nova_msg_governo", function() {
		$('#modal-send-message').modal();
	});
	$(document).on("click", "#bt_msg_responder", function() {
		var assunto = $(this).data('assunto');
		var remetente = $(this).data('remetente');
		loadNewMsgForm(function() {
			$('#nmsg_destinatario').val(remetente);
			$('#nmsg_assunto').val('R: ' + assunto);
		});
	});
	$(document).on("click", "#bt_enviar_nmsg", function() {
		var data = {
			assunto: $("#nmsg_assunto").val(),
			texto: $("#nmsg_texto").val(),
			destino: $("#nmsg_destinatario").val()
		};
		if (!data.destino) {
			bootbox.alert('Por favor informe um destinatario');
			return;
		}
		if (!data.assunto) {
			bootbox.alert('Por favor informe um assunto');
			return;
		}
		if (!data.texto) {
			bootbox.alert('Por favor escreva uma mensagem');
			return;
		}

		sendForm("Denden/enviar_mensagem", data, function() {
			$("#mensagens").html("");
			$("#div_icon_denden").click();
		});
	});

	$(document).on("click", "#bt_apaga_msgs", function() {
		bootbox.confirm('Apagar todas as mensagens recebidas?', function(result) {
			if (result) {
				sendGet('Denden/apaga_mensagens.php?', function() {
					$("#mensagens").html("");
					$("#div_icon_denden").click();
				});
			}
		});
	});
	$(document).on("click", "#bt_msg_apagar", function() {
		var cod = $(this).data('cod');
		bootbox.confirm('Apagar essa mensagem?', function(result) {
			if (result) {
				sendGet('Denden/apaga_uma_mensagem.php?cod=' + cod, function() {
					$("#mensagens").html("");
					$("#div_icon_denden").click();
				});
			}
		});
	});

	$(document).on("click", "#bt_msg_enviadas", function() {
		$.ajax({
			type: 'get',
			url: 'Scripts/Denden/mensagens_listar_enviados.php',
			cache: false,
			success: function(retorno) {
				if (retorno.substr(0, 1) == "#") {
					bancandoEspertinho(retorno.substr(1, retorno.length - 1));
				} else {
					$("#mensagens").html(retorno);
					$(".mensagem_ler").click(function() {
						var cod = $(this).data('cod');
						$.ajax({
							type: 'get',
							url: 'Scripts/Denden/mensagem_ler_enviada.php',
							data: 'cod=' + cod,
							cache: false,
							success: function(retorno) {
								if (retorno.substr(0, 1) == "#") {
									bancandoEspertinho(retorno.substr(1, retorno.length - 1));
								} else {
									$("#mensagens").html(retorno);
								}
							}
						});
					});
				}
			}
		});
	});


	//Iventario
	$(document).on("click", "#div_icon_inventario", function() {
		$.ajax({
			type: 'get',
			url: 'Scripts/Inventario/inventario.php',
			cache: false,
			success: function(retorno) {
				if (retorno.substr(0, 1) == "#") {
					bancandoEspertinho(retorno.substr(1, retorno.length));
				} else {
					$('#div_icon_inventario .badge').remove();
					$("#inventario").html(retorno);
				}
			}
		});
	});
	$(document).on("click", ".x_descart", function() {
		var data = this.id;
		bootbox.confirm('Descartar este item?', function(result) {
			if (result) {
				callbackdescarte(data);
			}
		});
	});
	$(document).on("click", ".x_descart_tudo", function() {
		var data = this.id;
		bootbox.confirm('Descartar todas unidades desse item?', function(result) {
			if (result) {
				callbackdescarte(data);
			}
		});
	});

	function callbackdescarte(data) {
		sendGet('Inventario/descartar_item.php?' + data, function() {
			$("#div_icon_inventario a").click();
		});
	}

	function darComida(comida) {
		$.ajax({
			type: 'get',
			url: 'Scripts/Inventario/tb_darcomida.php',
			data: comida,
			cache: false,
			success: function(retorno) {
				if (retorno.substr(0, 1) == "#") {
					bancandoEspertinho(retorno.substr(1, retorno.length));
				} else {
					$("#dar_comida").html(retorno);
					$('#modal-dar-comida').modal('show');
					$(".com_fome").click(function() {
						var cod = this.id;
						bootbox.confirm('Dar o item a este personagem?', function(result) {
							if (result) {
								$.ajax({
									type: 'get',
									url: 'Scripts/Inventario/dar_comida.php',
									data: comida + cod,
									cache: false,
									success: function(retorno) {
										if (retorno.substr(0, 1) == "#") {
											bancandoEspertinho(retorno.substr(1, retorno.length));
										} else {
											darComida(comida);
										}
									}
								});
							}
						});
					});
				}
			}
		});
	}

	$(document).on("click", ".link_dar_comida", function() {
		var comida = this.id;
		darComida(comida);
	});

	//Cartografo
	$(document).on("click", "#div_icon_cartografo", function() {
		$.ajax({
			type: 'get',
			url: 'Scripts/Cartografo/div_cartografo.php',
			cache: false,
			success: function(retorno) {
				if (retorno.substr(0, 1) == "#") {
					bancandoEspertinho(retorno.substr(1, retorno.length));
				} else {
					$("#mapa_cartografo").html(retorno);
					$("#bt_remove_rota").click(function() {
						remover_rota();
					});
					$(".select_oceano").click(function() {
						xatual = myCoord.x;
						yatual = myCoord.y;
						quadro = 0;
						$("#text_coor").val("");
                        console.log($(this).parent())

						loadMapaCartografo(this.id, $("#meu_mapa").val())
					});
				}
			}
		});
	});

	// presentes diarios
	$(document).on("click", "#div_icon_daily_gift", function() {
		$.ajax({
			type: 'get',
			url: 'Scripts/DailyGift/visualizar.php',
			cache: false,
			success: function(retorno) {
				if (retorno.substr(0, 1) == "#") {
					bancandoEspertinho(retorno.substr(1, retorno.length));
				} else {
					$('#div_icon_daily_gift .badge').remove();
					$("#modal-daily-gift-content").html(retorno);
				}
			}
		});
	});

	$(document).on("submit", "#form_rota", function(e) {
		e.preventDefault();
		var pagina = "Mapa/mapa_navegar";

		var obj = {};
		for (var x = 0; x < 25; x++) {
			obj["r_" + x] = $("#tracar_rota_c_" + x).val();
		}
		obj.vem = 'cart';
		sendForm(pagina, obj);
		if (pagina_atual == "oceano") {
			reloadPagina();
		}
		$("#modal-cartografo").modal('hide');
		cont_rota = 0;
		iniciaNav();
	});

    $(document).on('click', "#chat-button", function () {
        window.localStorage.setItem('sg_c', $('#sg_c').val());
        window.localStorage.setItem('sg_k', $('#sg_k').val());
        window.open('Chat/index.html', 'Sugoi Game - Chat', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=400,height=430');
    });
}

function loadMapaCartografo(marId, mapId) {
    $.ajax({
        type: 'get',
        url: 'Scripts/Cartografo/mapa_cartografo.php',
        data: {
            mar: marId,
            cod: mapId
        },
        cache: false,
        beforeSend: function() {
            $('.menu-cartografo li.active').removeClass('active')
            $('.menu-cartografo li a#' + marId).parent().addClass('active');

            $("#mapa_cartografo_oceano")
                .css("background", 'url("Imagens/Mapa/Mapa_Cartografo/carregando.jpg") center center')
                .css('height', (marId > 4 ? 800 : 500) + 'px')
				.css('width', '100%')
                .html('');
        },
        success: function(retorno) {
            if (retorno.substr(0, 1) == "#") {
                bancandoEspertinho(retorno.substr(1, retorno.length));
            } else {
                $("#mapa_cartografo_oceano")
                    .css("background", 'url("Imagens/Mapa/Mapa_Cartografo/' + marId + '.jpg")')
                    .css('height', 'auto')
					.css('width', '1000px')
                    .html(retorno);

                $('[data-toggle="tooltip"]').tooltip();
            }
        }
    });
}

function verificaSeTemNav() {
	var tmp = $("#destino_sec").html();
	if (typeof tmp !== 'undefined' && tmp.length) {
		iniciaNav();
	}
}

var navTimeout = null;

function iniciaNav() {
	if (!navTimeout) {
		navTimeout = setTimeout(function() {
			navTimeoutFunc();
		}, 1000);
	}
}

function navTimeoutFunc() {
	var tmp = parseInt($("#destino_sec").html(), 10);
	tmp -= 1;
	$("#destino").html(transforma_tempo(tmp));
	$("#destino_sec").html(tmp);
	if (tmp <= 0) {
		finalizaNav();
		navTimeout = null;
		document.title = gameTitle;
	} else {
		navTimeout = setTimeout(function() {
			navTimeoutFunc();
		}, 1000);
		document.title = '[' + transforma_tempo(tmp) + '] ' + gameTitle;
	}
}

function finalizaNav() {
	$.ajax({
		type: 'get',
		url: 'Scripts/Mapa/verifica_nav.php',
		cache: false,
		success: function(retorno) {
			retorno = JSON.parse(retorno);
			if (retorno.redirect) {
				loadPagina(retorno.redirect);
			} else if (retorno.error) {
				bancandoEspertinho(retorno.error);
			} else {
				if (retorno.navegacao) {
					$("#destino").html(transforma_tempo(retorno.navegacao));
					$("#destino_sec").html(retorno.navegacao);
					iniciaNav();
				} else {
					$("#destino").html(" ");
					enviarNotificacao('Você chegou ao seu destino!', {
						body: 'Se navio concluiu a rota designada.',
						icon: 'https://sugoigame.com.br/Imagens/favicon.png'
					});
				}

				if (pagina_atual.startsWith("oceano") || $("#destino_ilha").html() != retorno.ilha) {
					reloadPagina();
				}

				background(retorno.mapa.ilha);

				$("#destino_ilha").html(retorno.ilha);
				$("#destino_mar").html(retorno.mar);
				$("#location").html(retorno.coord);
			}
		}
	});
}

function tracar_rota(x, y) {
	xatual = parseInt(xatual);
	yatual = parseInt(yatual);
	var td = "tracar_rota_c_";
	td += quadro;
	var coor = "coor_" + x + "_" + y;
	if (quadro < 25) {
		if (x == (xatual + 1) || x == (xatual - 1)) {
			if (y == yatual || y == (yatual + 1) || y == (yatual - 1)) {
				document.getElementById(td).value = x + "_" + y;
				xatual = x;
				yatual = y;
				quadro += 1;
				document.getElementById(coor).style.background = "#ff0000";
				document.getElementById(coor).style.opacity = "0.5";
				var l = x;
				var n = y;
				n = 101 - n;
				n -= 50;
				if (l > 100) {
					l -= 200;
				}
				text = document.getElementById("text_coor").value;
				text += '=>' + l + 'ºL, ' + n + 'ºN ';
				document.getElementById("text_coor").value = text;
				document.getElementById("erro").innerHTML = ""
			} else {
				document.getElementById("erro").innerHTML = 'Voce so pode se movimentar um quadro por vez';
			}
		} else if (x == xatual) {
			if (y == (yatual + 1) || y == (yatual - 1)) {
				document.getElementById(td).value = x + "_" + y;
				xatual = x;
				yatual = y;
				quadro += 1;
				document.getElementById(coor).style.background = "#ff0000";
				document.getElementById(coor).style.opacity = "0.5";
				var l = x;
				var n = y;
				n = 101 - n;
				n -= 50;
				if (l > 100) {
					l -= 200
				}
				text = document.getElementById("text_coor").value;
				text += '=>' + l + 'ºL, ' + n + 'ºN ';
				document.getElementById("text_coor").value = text;
				document.getElementById("erro").innerHTML = ""
			} else {
				document.getElementById("erro").innerHTML = 'Voce so pode se movimentar um quadro por vez'
			}
		} else {
			document.getElementById("erro").innerHTML = 'Voce so pode se movimentar um quadro por vez'
		}
	} else {
		document.getElementById("erro").innerHTML = 'A rota deve ser de no máximo 25 quadros';
	}
}

function remover_rota() {
	if (quadro > 0) {
		var td = "tracar_rota_c_";
		td += (quadro - 1);
		var id = document.getElementById(td).value;
		var coor = "coor_";
		coor += id;
		document.getElementById(td).value = "";
		document.getElementById(coor).style.background = "transparent";
		document.getElementById(coor).style.opacity = "1";
		quadro -= 1;
		td = "tracar_rota_c_";
		td += (quadro - 1);
		var id = document.getElementById(td).value;
		text = document.getElementById("text_coor").value;
		i = 1;
		cont = 0;
		for (x = (text.length - 1); x > 0; x--) {
			if (text.substr(x, 1) != '=') {
				i += 1;
				var newtext = text.substr(0, (text.length - i));
			} else {
				cont += 1;
				if (cont == 1) {
					x = 0;
				}
			}
		}
		document.getElementById("text_coor").value = newtext;
		if (id == '') {
			coordx = myCoord.x;
			coordy = myCoord.y;
		} else {
			cod = 0;
			for (x = 0; x < id.length; x++) {
				if (id.substr(x, 1) == "_") {
					cod = 1;
				} else {
					if (cod == 0) {
						var coordx = id.substr(x, 1);
						cod = 2;
					} else if (cod == 2) {
						coordx += id.substr(x, 1);
					} else if (cod == 1) {
						var coordy = id.substr(x, 1);
						cod = 3;
					} else if (cod == 3) {
						coordy += id.substr(x, 1);
					}
				}
			}
		}
		xatual = coordx;
		yatual = coordy;
	}
}

popupAmigavel = false;

function verifica_nova_msg() {
	setTimeout("verifica_nova_msg()", 10000);
	$.ajax({
		type: 'get',
		url: 'Scripts/Denden/verifica_nova_msg.php',
		cache: false,
		success: function(retorno) {
			retorno = JSON.parse(retorno);
			if (!retorno.strlen)
				return;

			if (retorno.inCombate) {
				if (pagina_atual != "combate") {
					loadPagina("combate");
				}
			}
			if (!retorno.msgBoxClear) {
				setTimeout(function() {
					n_puru(retorno.inCombate);
				}, 1000);
			}

			if (retorno.amigavel) {
				if (!popupAmigavel) {
					popupAmigavel = true;
					bootbox.confirm("A tripulação " + retorno.amigavel + " está te desafiando para uma disputa amigável, Deseja aceitar?", callbackDesafio);
				}
			}

			if (retorno.coliseu) {
				if (pagina_atual != "coliseu" && pagina_atual != 'localizadorCasual' && pagina_atual != 'localizadorCompetitivo') {
					enviarNotificacao('Um adversário foi encontrado no Coliseu!', {
						body: 'Prepare-se para batalha!',
						icon: 'https://sugoigame.com.br/Imagens/favicon.png',
						sound: 'Sons/tada.mp3'
					});
					loadPagina("coliseu");
				}
			}
			if (retorno.torneio) {
				sendGet('Mapa/mapa_atacar.php?id=' + retorno.torneio + '&tipo=' + 8);
			}
		}
	});
}

function callbackDesafio(v) {
	popupAmigavel = false;
	if (v) {
		$.ajax({
			type: 'get',
			url: 'Scripts/Batalha/aceitar_desafio.php',
			cache: false,
			success: function(retorno) {
				if (retorno.substr(0, 1) == "#") {
					bancandoEspertinho(retorno.substr(1, retorno.length));
				} else {
					loadPagina("combate");
				}
			}
		});
	} else {
		$.ajax({
			type: 'get',
			url: 'Scripts/Batalha/recusar_desafio.php',
			cache: false
		});
	}
}

function n_puru(hidePopover) {
	$("#denden_mushi")
		.attr("src", "Imagens/Icones/Denden_0.png")
		.popover({
			html: true,
			title: 'Puru Puru Puru Puru...',
			content: '<div style="width: 145px;" class="text-center">Mensagem não lida!</div>',
			placement: 'bottom',
			animation: true,
		});

	if (!hidePopover) {
		$("#denden_mushi")
			.popover('show');
	}

	playAudio('toque_nova_msg', true);
}