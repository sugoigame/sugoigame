var timeOuts = [];
var websockets = {};
var games = {};

function getQueryParams() {
	var vars = [], hash;
	var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
	for (var i = 0; i < hashes.length; i++) {
		if (hashes[i].length) {
			hash = hashes[i].split('=');
			vars.push(hash[0]);
			vars[hash[0]] = hash[1];
		}
	}
	return vars;
}

function urldecode(url) {
  return decodeURIComponent(url.replace(/\+/g, ' '));
}

function escapeString(value) {
	var entityMap = {
		'&': '&amp;',
		'<': '&lt;',
		'>': '&gt;',
		'"': '&quot;',
		"'": '&#39;',
		'/': '&#x2F;',
		'`': '&#x60;',
		'=': '&#x3D;'
	};
	return String(value).replace(/[&<>"'`=\/]/g, function (s) {
		return entityMap[s];
	});
}

function mascaraBerries(price) {
	return price.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.")
}

myCoord = {
	x: parseInt($('#coord_x_navio').val(), 10),
	y: parseInt($('#coord_y_navio').val(), 10)
};

$(function () {
	var queryParams = getQueryParams();
	if (queryParams['erro'] === "1") {
		bootbox.alert({
			className: 'modal-danger',
			title: 'Bancando o espertinho?',
			message: '<img src="Imagens/erro.jpg" /><br /><br />Login e/ou Senha inválidos!'
		});
		loadPagina("recuperarSenha");
	}

	if (queryParams['msg']) {
		bootbox.alert({
			className: 'modal-danger',
			title: 'Bancando o espertinho?',
			message: '<img src="Imagens/erro.jpg" /><br /><br />' + escapeString(urldecode(queryParams['msg']))
		});
	}

	if (queryParams['msg2']) {
		bootbox.alert({
			message: escapeString(urldecode(queryParams['msg2']))
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
		window.open('Scripts/big_info.php?cod=' + cod, 'Sugoi Game - Informação de jogador', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=270,height=220');
	});


	if (queryParams['ses']) {
		var query = queryParams.filter(function (index) {
			return index !== 'ses';
		}).map(function (index) {
			return index + '=' + queryParams[index];
		}).join('&');

		loadPagina(queryParams['ses'] + '&' + query);
	} else {
		loadPagina("home");
	}

	$(document).on("click", ".noHref", function (e) {
		e.preventDefault();
	});
	$(document).on("click", "#unstuck-acc", function (e) {
		e.preventDefault();
		bootbox.confirm({
			title: 'Deseja destravar sua tripulação?',
			message: 'Todas as ações por tempo como mergulho, expedição, missões, etc serão canceladas.',
			buttons: {
				confirm: {
					label: 'Sim',
					className: 'btn-success'
				},
				cancel: {
					label: 'Não',
					className: 'btn-danger'
				}
			},
			callback: function (result) {
				if (result) {
					sendGet("Geral/unstuck.php");
				}
			}
		});
	});

	$(window).scroll(function () {
		if ($(this).scrollTop()) {
			$('#to-top').fadeIn();
		} else {
			$('#to-top').fadeOut();
		}
	});

	$("#to-top").click(function () {
		$("html, body").animate({scrollTop: 0}, 200);
	});

	window.onpopstate = function () {
		loadingIn();
		loadPagina(location.search.replace('?ses=', ''), null, true)
	};
});

function background(ilha, pasta) {
	if (!pasta) {
		var hours = new Date().getHours();
		pasta = hours >= 18 || hours <= 7 ? 'noite' : 'dia';
	}

	var repet = (ilha == 0) ? "repeat-x" : "no-repeat";
	$("body").css("background", "#" + cor_bg(ilha, pasta) + " url(Imagens/" + pasta + "/" + img_bg(ilha) + ".jpg) " + repet + " top center fixed");

	if (ilha != 0) {
		$("body").css('background-size', 'cover');
	}
}

function clearAllTimeouts() {
	for (key in timeOuts) {
		clearTimeout(timeOuts[key]);
	}
}

function closeWebsockets() {
	for (var key in websockets) {
		websockets[key].close();
		delete websockets[key];
	}
}

function destroyGames() {
	for (var key in games) {
		games[key].destroy();
		delete games[key];
	}
}

function appendLinks() {
	$(document).on("click", ".link_content", function (e) {
		e.preventDefault();
		var id = $(this).attr("href");
		var locale = id.substr(7, (id.length - 7));
		if (locale != pagina_atual || paginas_visualizadas == 0) {
			loadingIn();
			loadPagina(locale);
		}
	});
	$(document).on("click", ".link_content2", function (e) {
		e.preventDefault();
		var id = $(this).attr("href");
		var locale = id.substr(5, (id.length - 5));
		if (locale != pagina_atual || paginas_visualizadas == 0) {
			loadingIn();
			loadPagina(locale);
		}
	});

	$(document).on("click", ".link_redirect", function () {
		var locale = this.id;
		locale = locale.substr(5, (locale.length - 5));
		locale = locale + '.php';
		clearAllTimeouts();
		closeWebsockets();
		destroyGames();
		location.href = locale;
	});
	$(document).on("click", ".link_send", function (e) {
		e.preventDefault();
		$(this).blur();
		var locale = $(this).attr("href");
		locale = locale.substr(5, (locale.length - 5));
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
		var question = $(this).data('question');
		var method = $(this).data('method') || 'get';
		var postData = $(this).data('post-data') || {};
		bootbox.confirm($(this).data("question"), function (result) {
			if (result) {
				if (method.toLocaleLowerCase() == 'post') {
					sendForm(locale, postData);
				} else {
					sendGet(locale);
				}
			}
		});
	});

	$(document).on('submit', '.ajax_form', function (e) {
		e.preventDefault();
		$(this).blur();
		var action = $(this).attr('action');
		var method = $(this).attr('method');
		var question = $(this).data('question');
		var values = getFormData($(this));

		if (question) {
			bootbox.confirm(question, function (result) {
				if (result && method.toLowerCase() == 'post') {
					sendForm(action, values);
				}
			});
		} else if (method.toLowerCase() == 'post') {
			sendForm(action, values);
		}
	});

	$(document).on("click", "#audio-toggle", function (e) {
		toggleAudioEnable();
		setAudioEnableButtonAparence();
	});


	$(document).on("click", ".play-effect", function (e) {
		var effect = $(this).data('effect');
		var animation = new Animation(effect);
		animation.play({
			fixed: true,
			left: $(window).width() / 2,
			top: $(window).height() / 2
		});
	});
}

function getFormData($form) {
	var values = [];
	$.each($form.serializeArray(), function (i, field) {
		values.push(field.name + '=' + encodeURIComponent(field.value));
	});
	return values.join('&');
}

function ajaxError() {
	bootbox.alert({
		className: 'modal-danger',
		title: 'Ocorreu algum erro ao tentar se conectar com o servidor.',
		message: 'Por favor atualize a página e tente novamente.'
	});
}

function proccessResponseAlert(retorno) {
	retorno = retorno.trim();

	if (retorno.substr(0, 1) == "#") {
		bancandoEspertinho(retorno.substr(1, (retorno.length - 1)));
		return false;
	} else if (retorno.substr(0, 1) == "!") {
		location.href = "./?ses=" + retorno.substr(1, (retorno.length - 1));
		return false;
	} else if (retorno.substr(0, 1) == "%") {
		loadPagina(retorno.substr(1, (retorno.length - 1)));
		return false;
	} else if (retorno.substr(0, 1) == "@") {
		reloadPagina();
		responseAlert(retorno.substr(1, (retorno.length)));
		return false;
	} else if (retorno.substr(0, 1) == "|") {
		//reloadPagina();
		responseAlert(retorno.substr(1, (retorno.length)));
		return false;
	} else if (retorno.substr(0, 1) == "?") {
		//reloadPagina();
		responseAlert(retorno.substr(1, (retorno.length)));
		return false;
	} else if (retorno.substr(0, 1) == "-") {
		responseAlert(retorno.substr(1, (retorno.length)));
		//reloadPagina();
		return false;
	} else if (retorno.substr(0, 1) == ":") {
		//reloadPagina();
		return false;
	} else {
		return true;
	}
}

function responseAlert(msg, callback) {
	bootbox.alert({
		size: 'large',
		message: msg,
		callback: callback
	});
}

var paginas_visualizadas = 0;
var pagina_atual = "home";

function setQueryParam(param, value) {
	var queryParams = getQueryParams();
	queryParams[param] = value;

	var paramsFromPaginaAtual = pagina_atual.split('&');

	var isset = false;
	for (var i = 1; i < paramsFromPaginaAtual.length; i++) {
		var subParams = paramsFromPaginaAtual[i].split('=');

		if (subParams[0] == param) {
			paramsFromPaginaAtual[i] = param + '=' + value;
			isset = true;
			break;
		}
	}
	if (!isset) {
		paramsFromPaginaAtual.push(param + '=' + value);
	}

	pagina_atual = paramsFromPaginaAtual.join('&');

	window.history.pushState({status: 'ok'}, 'One Piece Sugoi Game - ' + paramsFromPaginaAtual[0], '?ses=' + pagina_atual);
}

function loadPagina(pagina, callback, preventPushState) {
	pagina_atual = pagina;
	paginas_visualizadas++;
	$.ajax({
		type: 'get',
		url: 'pagina.php',
		data: 'sessao=' + pagina,
		cache: false,
		error: ajaxError,
		success: function (retorno) {
			retorno = retorno.trim();
			clearAllTimeouts();
			closeWebsockets();
			destroyGames();

			loadingOut();
			if (proccessResponseAlert(retorno)) {
				// document.title = 'Sugoi Game';
				$('#tudo').html(retorno);

				$('[data-toggle="tooltip"]').tooltip();
				$('[data-toggle="popover"]').popover({});
				$('.selectpicker').selectpicker({
					noneSelectedText: 'Selecione...'
				});
				if (!preventPushState) {
					window.history.pushState({status: 'ok'}, 'One Piece Sugoi Game - ' + pagina, '?ses=' + pagina);
				}
				myCoord = {
					x: parseInt($('#coord_x_navio').val(), 10),
					y: parseInt($('#coord_y_navio').val(), 10)
				};

				background(parseInt($('#ilha_atual').val(), 10));

				setAudioEnableButtonAparence();
			} else if (paginas_visualizadas == 1) {
				loadPagina('home');
				return;
			}
			verificaSeTemNav();

			if (callback) {
				callback(retorno);
			}
		}
	});
}

function reloadPagina(callback) {
	loadPagina(pagina_atual, callback);
}

function sendForm(pagina, obj, callback) {
	$('#icon_carregando').fadeIn();
	var data = '';

	if (typeof obj === 'string') {
		data = obj;
	} else {
		var items = [];
		for (var i in obj) {
			items.push(i + "=" + obj[i]);
			data = items.join('&');
		}
	}

	$.ajax({
		type: 'post',
		url: 'Scripts/' + pagina + ".php",
		data: data,
		cache: false,
		error: ajaxError,
		success: function (retorno) {
			retorno = retorno.trim();
			$('#icon_carregando').fadeOut();

			if (proccessResponseAlert(retorno) && retorno.length) {
				responseAlert(retorno, function () {
					if (callback) {
						callback(retorno);
					}
				});
			} else if (callback) {
				callback(retorno);
			}
			reloadPagina();
		}
	});
}

function loadSubSession(pagina, destination, callback) {
	$.ajax({
		type: 'get',
		url: 'Scripts/' + pagina,
		cache: false,
		error: ajaxError,
		success: function (retorno) {
			retorno = retorno.trim();
			clearAllTimeouts();
			closeWebsockets();
			destroyGames();

			loadingOut();
			if (proccessResponseAlert(retorno)) {
				// document.title = 'Sugoi Game';
				$(destination).html(retorno);

				$('[data-toggle="tooltip"]').tooltip();
				$('[data-toggle="popover"]').popover({});
				$('.selectpicker').selectpicker({
					noneSelectedText: 'Selecione...'
				});
			} else if (paginas_visualizadas == 1) {
				loadPagina('home');
				return;
			}

			if (callback) {
				callback(retorno);
			}
		}
	});
}

getDisponivel = true;

function sendGet(locale, callback) {
	if (getDisponivel) {
		$('#icon_carregando').fadeIn();
		getDisponivel = false;
		$.ajax({
			type: 'get',
			url: 'Scripts/' + locale,
			cache: false,
			error: function () {
				getDisponivel = true;
				ajaxError();
			},
			success: function (retorno) {
				retorno = retorno.trim();
				$('#icon_carregando').fadeOut();
				getDisponivel = true;

				if (retorno.length && proccessResponseAlert(retorno)) {
					responseAlert(retorno, function () {
						if (callback) {
							callback(retorno);
						}
					});
				} else if (callback) {
					callback(retorno);
				}
				reloadPagina();
			}
		});
	}
}

berriesDisponivel = true;

function atualizaBerries(callback) {
	if (berriesDisponivel) {
		berriesDisponivel = false;
		$.ajax({
			type: 'get',
			url: 'berries.php',
			cache: false,
			error: function () {
				berriesDisponivel = true;
				ajaxError();
			},
			success: function (retorno) {
				berriesDisponivel = true;
				if (retorno.substr(0, 1) == "#") {
					bancandoEspertinho(retorno.substr(1, (retorno.length - 1)));
				}
				else {
					$("#span_berries").html(retorno);
				}

				if (callback) {
					callback(retorno);
				}
			}
		});
	}
}

goldDisponivel = true;

function atualizaGold(callback) {
	if (goldDisponivel) {
		goldDisponivel = false;
		$.ajax({
			type: 'get',
			url: 'gold.php',
			cache: false,
			error: function () {
				goldDisponivel = true;
				ajaxError();
			},
			success: function (retorno) {
				goldDisponivel = true;
				if (retorno.substr(0, 1) == "#") {
					bancandoEspertinho(retorno.substr(1, (retorno.length - 1)));
				}
				else {
					$("#span_gold").html(retorno);
				}

				if (callback) {
					callback(retorno);
				}
			}
		});
	}
}

function bancandoEspertinho(msg) {
	bootbox.alert({
		className: 'modal-danger',
		title: 'Bancando o espertinho?',
		message: '<img src="Imagens/erro.jpg" /><br /><br />' + msg + '<br /><br />Estamos de olho em você!'
	});
}

function mostraimgs(id) {
	var imgs = document.getElementById(id);
	if (imgs.style.display == "none") {
		imgs.style.display = "block";
	}
	else {
		imgs.style.display = "none";
	}
}

function geraImgsSkill(list, input, img, totalImg) {
	var listElem = $('#' + list),
		inputElem = $('#' + input),
		imgElem = $('#' + img);

	listElem.toggle();

	if (!listElem.children().length) {
		var imgs = [];
		for (var x = 1; x <= totalImg; x++) {
			imgs.push(x);
		}
		imgs.forEach(function (x) {
			listElem.append(
				$('<IMG>').attr('src', 'Imagens/Skils/' + x + '.jpg').on('click', function () {
					imgElem.attr('src', $(this).attr('src'));
					inputElem.val(x);
					listElem.toggle();
				})
			);
		});
	}
}

function selectimg(img, campoimg, inputimg, imgs) {
	document.getElementById(inputimg).value = img;
	document.getElementById(campoimg).src = 'Imagens/Skils/' + img + '.jpg';
	document.getElementById(imgs).style.display = "none";
}

var loading = false;

function loadingIn() {
	if (!loading) {
		loading = true;
		$('#icon_carregando').fadeIn();
		$('#icon_carregando .progress-bar')
			.width(0)
			.animate({
				width: '100%'
			}, 5000);
	}
}

function loadingOut() {
	$('#icon_carregando .progress-bar')
		.stop()
		.animate({
			width: '100%'
		}, 100, function () {
			$('#icon_carregando').fadeOut(100);
			loading = false;
		});
}

var audioEnable = true;

function loadAudioConfig() {
	if (window.localStorage) {
		audioEnable = JSON.parse(window.localStorage.getItem('audioEnable'));
		if (audioEnable === null) {
			audioEnable = true;
		}
	}
}

function setAudioEnable(value) {
	audioEnable = value;
	if (window.localStorage) {
		window.localStorage.setItem('audioEnable', value);
	}
}

function toggleAudioEnable() {
	setAudioEnable(!audioEnable);
}

function playAudio(audio, element) {
	if (!audioEnable) {
		return;
	}
	if (element) {
		document.getElementById(audio).play();
	} else {
		var sound = new Audio(audio);
		sound.play();
	}
}

function pauseAudio(elementId) {
	if (!audioEnable) {
		return;
	}
	document.getElementById(elementId).pause();
}

function setAudioEnableButtonAparence() {
	var content = audioEnable
		? '<i class="glyphicon glyphicon-volume-up fa-fw"></i> Som Ligado'
		: '<i class="glyphicon glyphicon-volume-off fa-fw"></i> Som Desligado';
	$('#audio-toggle').html(content);
}

function enviarNotificacao(title, config) {
	if (config.sound) {
		playAudio(config.sound);
	}

	if (!config.vibrate) {
		config.vibrate = [200, 100, 200];
	}

	try {
		if (window.Notification) {
			if (Notification.permission === "granted") {
				// If it's okay let's create a notification
				var notification = new Notification(title, config);

				notification.onclick = function () {
					window.focus();
					this.close();
				};
			} else if (Notification.permission !== 'denied') {
				Notification.requestPermission(function (permission) {
					if (permission === "granted") {
						enviarNotificacao(title, config);
					}
				});
			}
		}
	} catch (e) {
		console.log("Este navegador não suporta notificacoes");
	}
}
