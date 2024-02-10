/**
 * Esse arquivo tem todo o codigo de inicializacao do jogo. Quando o index.php é carregado
 * esse arquivo main.js aplica todos os bindings globais, configura o estado inicial do jogo,
 * configura notificações, audio e tudo aquilo que precisa ser executado no momento em que
 * o jogo é aberto pela primeira vez.
 */

$(function () {
  if (window.location.hostname == "sugoigame.com.br") {
    if ("serviceWorker" in navigator) {
      navigator.serviceWorker.register("/service-worker.js");
    }
  }

  var queryParams = getQueryParams();
  if (queryParams["erro"] === "1") {
    bootbox.alert({
      className: "modal-danger",
      title: "Bancando o espertinho?",
      message:
        '<img src="Imagens/erro.jpg" /><br /><br />Login e/ou Senha inválidos!',
    });
    loadPagina("recuperarSenha");
  }

  if (queryParams["msg"]) {
    bootbox.alert({
      className: "modal-danger",
      title: "Bancando o espertinho?",
      message:
        '<img src="Imagens/erro.jpg" /><br /><br />' +
        escapeString(urldecode(queryParams["msg"])),
    });
  }

  if (queryParams["msg2"]) {
    bootbox.alert({
      message: escapeString(urldecode(queryParams["msg2"])),
    });
  }

  background(0);

  appendLinks();

  loadAudioConfig();

  if ("Notification" in window) {
    Notification.requestPermission();
  }

  $(document).on("click", ".big_info", function (e) {
    e.preventDefault();
    var cod = $(this).attr("href");
    window.open(
      "Scripts/big_info.php?cod=" + cod,
      "Sugoi Game - Informação de jogador",
      "toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=270,height=220"
    );
  });

  if (queryParams["ses"]) {
    var query = queryParams
      .filter(function (index) {
        return index !== "ses";
      })
      .map(function (index) {
        return index + "=" + queryParams[index];
      })
      .join("&");

    loadPagina(queryParams["ses"] + "&" + query);
  } else {
    loadPagina("home");
  }

  $(document).on("click", ".noHref", function (e) {
    e.preventDefault();
  });
  $(document).on("click", "#unstuck-acc", function (e) {
    e.preventDefault();
    bootbox.confirm({
      title: "Deseja destravar sua tripulação?",
      message:
        "Todas as ações por tempo como mergulho, expedição, missões, etc serão canceladas.",
      buttons: {
        confirm: {
          label: "Sim",
          className: "btn-success",
        },
        cancel: {
          label: "Não",
          className: "btn-danger",
        },
      },
      callback: function (result) {
        if (result) {
          sendGet("Geral/unstuck.php");
        }
      },
    });
  });

  $(window).scroll(function () {
    if ($(this).scrollTop()) {
      $("#to-top").fadeIn();
    } else {
      $("#to-top").fadeOut();
    }
  });

  $("#to-top").click(function () {
    $("html, body").animate({ scrollTop: 0 }, 200);
  });

  window.onpopstate = function () {
    loadingIn();
    loadPagina(location.search.replace("?ses=", ""), null, true);
  };

  $("#form-aprender-skill").submit(function (e) {
    var img = $("#aprender-skill-input-img").val();
    if (!img.length || img == 0) {
      e.preventDefault();
      bootbox.alert("Selecione uma imagem para sua habilidade.");
    }
  });

  screen.orientation.lock("landscape").catch(function () {
    // the device not support orientation
  });

  if (window.outerWidth <= 768) {
    var body = document.documentElement;
    if (body.requestFullscreen) {
      body.requestFullscreen();
    } else if (body.webkitrequestFullscreen) {
      body.webkitrequestFullscreen();
    } else if (body.mozrequestFullscreen) {
      body.mozrequestFullscreen();
    } else if (body.msrequestFullscreen) {
      body.msrequestFullscreen();
    }
  }
});

function appendLinks() {
  $(document).on("click", ".link_content", function (e) {
    e.preventDefault();
    var id = $(this).attr("href");
    var locale = id.substr(7, id.length - 7);
    if (locale != pagina_atual || paginas_visualizadas == 0) {
      loadingIn();
      loadPagina(locale);
    }
  });
  $(document).on("click", ".link_content2", function (e) {
    e.preventDefault();
    var id = $(this).attr("href");
    var locale = id.substr(5, id.length - 5);
    if (locale != pagina_atual || paginas_visualizadas == 0) {
      loadingIn();
      loadPagina(locale);
    }
  });

  $(document).on("click", ".link_redirect", function () {
    var locale = this.id;
    locale = locale.substr(5, locale.length - 5);
    locale = locale + ".php";
    clearAllTimeouts();
    closeWebsockets();
    destroyGames();
    location.href = locale;
  });
  $(document).on("click", ".link_send", function (e) {
    e.preventDefault();
    $(this).blur();
    var locale = $(this).attr("href");
    locale = locale.substr(5, locale.length - 5);
    sendGet(locale);
  });
  $(document).on("click", ".link_sends", function (e) {
    e.preventDefault();
    $(this).blur();
    var locale = $(this).attr("href");
    sendGet(locale);
  });
  $(document).on("click", ".link_confirm", function (e) {
    e.preventDefault();
    $(this).blur();
    var locale = $(this).attr("href");
    var question = $(this).data("question");
    var method = $(this).data("method") || "get";
    var postData = $(this).data("post-data") || {};
    bootbox.confirm($(this).data("question"), function (result) {
      if (result) {
        if (method.toLocaleLowerCase() == "post") {
          sendForm(locale, postData);
        } else {
          sendGet(locale);
        }
      }
    });
  });

  $(document).on("submit", ".ajax_form", function (e) {
    e.preventDefault();
    $(this).blur();
    var action = $(this).attr("action");
    var method = $(this).attr("method");
    var question = $(this).data("question");
    var values = getFormData($(this));

    if (question) {
      bootbox.confirm(question, function (result) {
        if (result && method.toLowerCase() == "post") {
          sendForm(action, values);
        }
      });
    } else if (method.toLowerCase() == "post") {
      sendForm(action, values);
    }
  });

  $(document).on("click", "#audio-toggle", function (e) {
    toggleAudioEnable();
    setAudioEnableButtonAparence();
  });

  $(document).on("click", ".play-effect", function (e) {
    var effect = $(this).data("effect");
    var animation = new Animation(effect);
    animation.play({
      fixed: true,
      left: $(window).width() / 2,
      top: $(window).height() / 2,
    });
  });
}

$(function () {
  headerAppendFunctions();
  verifica_nova_msg();

  verificaSeTemNav();

  $(document).on(
    "click",
    ".personagem:not(.personagem-selecionavel)",
    function () {
      if ($(document).width() < 1000) {
        var cod = $(this).data("cod");
        $(".personagem-info:not(#personagem-info-" + cod + ")").addClass(
          "hidden"
        );
        $("#personagem-info-" + cod)
          .css("top", 0)
          .css("left", 0)
          .toggleClass("hidden");
      }
    }
  );
});

function headerAppendFunctions() {
  $(document).on("click", "#div_icon_progress > a", function () {
    $("#div_icon_progress img")
      .css("animation", "none")
      .css("-webkit-animation", "none");

    var title = $(this).data("title");
    var description = $(this).data("description");
    var xpReward = $(this).data("xp");
    var berriesReward = $(this).data("berries");
    var finished = $(this).data("finished");

    $("#user-progress-title").html(title);
    $("#user-progress-description").html(description);
    $("#user-progress-rewards").html(
      (xpReward || berriesReward ? 'Recompensas: <ul class="text-left">' : "") +
        (xpReward ? "<li>" + xpReward + " pontos de experiência</li>" : "") +
        (berriesReward
          ? '<li><img src="Imagens/Icones/Berries.png" /> ' +
            berriesReward +
            "</li>"
          : "") +
        "</ul>"
    );

    if (finished) {
      $("#user-progress-finish").css("display", "inline-block");
      $("#user-progress-back").css("display", "none");
    } else {
      $("#user-progress-finish").css("display", "none");
      $("#user-progress-back").css("display", "inline-block");
    }

    $("#modal-user-progress").modal();
  });

  // DenDen
  $(document).on("click", "#div_icon_denden", function () {
    $("#denden_mushi")
      .popover("hide")
      .attr("src", "Imagens/Icones/Denden_1.png");
    $("#alerta_denden").fadeOut(100);
    pauseAudio("toque_nova_msg", true);
    $.ajax({
      type: "get",
      url: "Scripts/Denden/mensagens_listar.php",
      cache: false,
      success: function (retorno) {
        retorno = retorno.trim();
        if (retorno.substr(0, 1) == "#") {
          bancandoEspertinho(retorno.substr(1, retorno.length - 1));
        } else {
          $("#mensagens").html(retorno);
          $(".mensagem_ler").click(function () {
            var cod = $(this).data("cod");
            $.ajax({
              type: "get",
              url: "Scripts/Denden/mensagem_ler.php",
              data: "cod=" + cod,
              cache: false,
              success: function (retorno) {
                retorno = retorno.trim();
                if (retorno.substr(0, 1) == "#") {
                  bancandoEspertinho(retorno.substr(1, retorno.length - 1));
                } else {
                  $("#mensagens").html(retorno);
                }
              },
            });
          });
        }
      },
    });
  });
  $(document).on("click", "#bt_msg_listar", function () {
    $("#mensagens").html("");
    $("#div_icon_denden").click();
  });

  function loadNewMsgForm(callback) {
    $.ajax({
      type: "get",
      url: "Scripts/Denden/nova_msg_form.php",
      cache: false,
      success: function (retorno) {
        retorno = retorno.trim();
        if (retorno.substr(0, 1) == "#") {
          bancandoEspertinho(retorno.substr(1, retorno.length - 1));
        } else {
          $("#mensagens").html(retorno);
          if (callback) {
            callback();
          }
        }
      },
    });
  }

  $(document).on("click", "#bt_nova_msg", function () {
    loadNewMsgForm();
  });
  $(document).on("click", "#bt_nova_msg_governo", function () {
    $("#modal-send-message").modal();
  });
  $(document).on("click", "#bt_msg_responder", function () {
    var assunto = $(this).data("assunto");
    var remetente = $(this).data("remetente");
    loadNewMsgForm(function () {
      $("#nmsg_destinatario").val(remetente);
      $("#nmsg_assunto").val("R: " + assunto);
    });
  });
  $(document).on("click", "#bt_enviar_nmsg", function () {
    var data = {
      assunto: $("#nmsg_assunto").val(),
      texto: $("#nmsg_texto").val(),
      destino: $("#nmsg_destinatario").val(),
    };
    if (!data.destino) {
      bootbox.alert("Por favor informe um destinatario");
      return;
    }
    if (!data.assunto) {
      bootbox.alert("Por favor informe um assunto");
      return;
    }
    if (!data.texto) {
      bootbox.alert("Por favor escreva uma mensagem");
      return;
    }

    sendForm("Denden/enviar_mensagem", data, function () {
      $("#mensagens").html("");
      $("#div_icon_denden").click();
    });
  });

  $(document).on("click", "#bt_apaga_msgs", function () {
    bootbox.confirm("Apagar todas as mensagens recebidas?", function (result) {
      if (result) {
        sendGet("Denden/apaga_mensagens.php?", function () {
          $("#mensagens").html("");
          $("#div_icon_denden").click();
        });
      }
    });
  });
  $(document).on("click", "#bt_msg_apagar", function () {
    var cod = $(this).data("cod");
    bootbox.confirm("Apagar essa mensagem?", function (result) {
      if (result) {
        sendGet("Denden/apaga_uma_mensagem.php?cod=" + cod, function () {
          $("#mensagens").html("");
          $("#div_icon_denden").click();
        });
      }
    });
  });

  $(document).on("click", "#bt_msg_enviadas", function () {
    $.ajax({
      type: "get",
      url: "Scripts/Denden/mensagens_listar_enviados.php",
      cache: false,
      success: function (retorno) {
        if (retorno.substr(0, 1) == "#") {
          bancandoEspertinho(retorno.substr(1, retorno.length - 1));
        } else {
          $("#mensagens").html(retorno);
          $(".mensagem_ler").click(function () {
            var cod = $(this).data("cod");
            $.ajax({
              type: "get",
              url: "Scripts/Denden/mensagem_ler_enviada.php",
              data: "cod=" + cod,
              cache: false,
              success: function (retorno) {
                if (retorno.substr(0, 1) == "#") {
                  bancandoEspertinho(retorno.substr(1, retorno.length - 1));
                } else {
                  $("#mensagens").html(retorno);
                }
              },
            });
          });
        }
      },
    });
  });

  //Iventario
  $(document).on("click", "#div_icon_inventario", function () {
    $.ajax({
      type: "get",
      url: "Scripts/Inventario/inventario.php",
      cache: false,
      success: function (retorno) {
        if (retorno.substr(0, 1) == "#") {
          bancandoEspertinho(retorno.substr(1, retorno.length));
        } else {
          $("#div_icon_inventario .badge").remove();
          $("#inventario").html(retorno);
        }
      },
    });
  });
  $(document).on("click", ".x_descart", function () {
    var data = this.id;
    bootbox.confirm("Descartar este item?", function (result) {
      if (result) {
        callbackdescarte(data);
      }
    });
  });
  $(document).on("click", ".x_descart_tudo", function () {
    var data = this.id;
    bootbox.confirm("Descartar todas unidades desse item?", function (result) {
      if (result) {
        callbackdescarte(data);
      }
    });
  });

  function callbackdescarte(data) {
    sendGet("Inventario/descartar_item.php?" + data, function () {
      $("#div_icon_inventario a").click();
    });
  }

  function darComida(comida) {
    $.ajax({
      type: "get",
      url: "Scripts/Inventario/tb_darcomida.php",
      data: comida,
      cache: false,
      success: function (retorno) {
        if (retorno.substr(0, 1) == "#") {
          bancandoEspertinho(retorno.substr(1, retorno.length));
        } else {
          $("#dar_comida").html(retorno);
          $("#modal-dar-comida").modal("show");
          $(".com_fome").click(function () {
            var cod = this.id;
            bootbox.confirm("Dar o item a este personagem?", function (result) {
              if (result) {
                $.ajax({
                  type: "get",
                  url: "Scripts/Inventario/dar_comida.php",
                  data: comida + cod,
                  cache: false,
                  success: function (retorno) {
                    if (retorno.substr(0, 1) == "#") {
                      bancandoEspertinho(retorno.substr(1, retorno.length));
                    } else {
                      darComida(comida);
                    }
                  },
                });
              }
            });
          });
        }
      },
    });
  }

  $(document).on("click", ".link_dar_comida", function () {
    var comida = this.id;
    darComida(comida);
  });

  //Cartografo
  $(document).on("click", "#div_icon_cartografo", function () {
    $.ajax({
      type: "get",
      url: "Scripts/Cartografo/div_cartografo.php",
      cache: false,
      success: function (retorno) {
        if (retorno.substr(0, 1) == "#") {
          bancandoEspertinho(retorno.substr(1, retorno.length));
        } else {
          $("#mapa_cartografo").html(retorno);
          $("#bt_remove_rota").click(function () {
            remover_rota();
          });
          $(".select_oceano").click(function () {
            xatual = myCoord.x;
            yatual = myCoord.y;
            quadro = 0;
            $("#text_coor").val("");
            console.log($(this).parent());

            loadMapaCartografo(this.id, $("#meu_mapa").val());
          });
        }
      },
    });
  });

  // presentes diarios
  $(document).on("click", "#div_icon_daily_gift", function () {
    $.ajax({
      type: "get",
      url: "Scripts/DailyGift/visualizar.php",
      cache: false,
      success: function (retorno) {
        if (retorno.substr(0, 1) == "#") {
          bancandoEspertinho(retorno.substr(1, retorno.length));
        } else {
          $("#div_icon_daily_gift .badge").remove();
          $("#modal-daily-gift-content").html(retorno);
        }
      },
    });
  });

  $(document).on("submit", "#form_rota", function (e) {
    e.preventDefault();
    var pagina = "Mapa/mapa_navegar";

    var obj = {};
    for (var x = 0; x < 25; x++) {
      obj["r_" + x] = $("#tracar_rota_c_" + x).val();
    }
    obj.vem = "cart";
    sendForm(pagina, obj);
    if (pagina_atual == "oceano") {
      reloadPagina();
    }
    $("#modal-cartografo").modal("hide");
    cont_rota = 0;
    iniciaNav();
  });

  $(document).on("click", "#chat-button", function () {
    window.localStorage.setItem("sg_c", $("#sg_c").val());
    window.localStorage.setItem("sg_k", $("#sg_k").val());
    window.open(
      "Chat/index.html",
      "Sugoi Game - Chat",
      "toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=400,height=430"
    );
  });
}
