<div class="panel-heading">
    Arena de Batalha
</div>

<script type="text/javascript">
    <?php include "JS/vue.js"; ?>
</script>
<script type="text/javascript">
    <?php include "JS/uiv.min.js"; ?>
</script>
<script type="text/javascript">
    <?php include "JS/vue-drag-drop.browser.js"; ?>
</script>

<style type="text/css">
    <?php include "CSS/arenaTeste.css"; ?>
</style>

<script id="skill-component-template" type="text/x-template">
    <popover placement="bottom">
        <template slot="default">
            <img v-bind:src="'Imagens/Skils/' + skill.img + '.jpg'"
                 v-bind:width="width"/>
        </template>
        <template slot="popover">
            <div style="min-width: 250px;">
                <ul>
                    <li>Dano: {{skill.dano}}</li>
                    <li>Alcance: {{skill.alcance}}</li>
                    <li>Área de efeito: {{skill.area}}</li>
                    <li>Consumo: {{skill.consumo}}</li>
                    <li>Espera: {{skill.espera}}</li>
                </ul>
            </div>
        </template>
    </popover>
</script>

<script id="modifier-component-template" type="text/x-template">
    <img class="modifier"
         v-bind:width="width"
         v-bind:src="'Imagens/Icones/'+modifier.icon"
         v-popover.bottom="{content: modifier.description}"/>
</script>

<script type="text/javascript">
    var STEPS = {
        IMG: 'IMG',
        NAME: 'NAME',
        CLASSE: 'CLASSE',
        PROFISSAO: 'PROFISSAO',
        HAKI: 'HAKI',
        ATRIBUTOS: 'ATRIBUTOS'
    };

    Vue.component('skill-icon', {
        props: ['skill', 'width'],
        template: '#skill-component-template'
    });

    Vue.component('modifier-icon', {
        props: ['modifier', 'width'],
        template: '#modifier-component-template'
    });

    var app = new Vue({
        el: '#character-creation-app',
        data: {
            step: STEPS.IMG,
            lastStep: STEPS.IMG,
            skillHover: false,
            level: 1,
            levelXP: 0,
            levelXPMax: 100,
            chars: [],
            char: null,
            charChoiceParams: {
                charactersCount: <?= PERSONAGENS_MAX ?>,
                availableChars: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
            },
            nameChoiceParams: {
                nameValid: false,
                nameErrorMessage: ''
            },
            classeChoiceParams: {
                skills: {
                    1: [{
                        id: 1,
                        img: 128,
                        alcance: 5,
                        area: 4,
                        dano: 500,
                        espera: 5,
                        consumo: 50
                    }, {
                        id: 2,
                        img: 17,
                        alcance: 1,
                        area: 1,
                        dano: 1100,
                        espera: 4,
                        consumo: 50
                    }, {
                        id: 3,
                        img: 134,
                        alcance: 1,
                        area: 3,
                        dano: 700,
                        espera: 5,
                        consumo: 50
                    }, {
                        id: 4,
                        img: 508,
                        alcance: 6,
                        area: 1,
                        dano: 1000,
                        espera: 7,
                        consumo: 50
                    }, {
                        id: 5,
                        img: 505,
                        alcance: 1,
                        area: 1,
                        dano: 1100,
                        espera: 4,
                        consumo: 50
                    }, {
                        id: 6,
                        img: 504,
                        alcance: 1,
                        area: 1,
                        dano: 1100,
                        espera: 4,
                        consumo: 50
                    }, {
                        id: 7,
                        img: 510,
                        alcance: 7,
                        area: 4,
                        dano: 300,
                        espera: 4,
                        consumo: 50
                    }, {
                        id: 8,
                        img: 503,
                        alcance: 7,
                        area: 4,
                        dano: 300,
                        espera: 4,
                        consumo: 50
                    }, {
                        id: 9,
                        img: 511,
                        alcance: 1,
                        area: 5,
                        dano: 500,
                        espera: 6,
                        consumo: 50
                    }, {
                        id: 10,
                        img: 16,
                        alcance: 6,
                        area: 3,
                        dano: 500,
                        espera: 4,
                        consumo: 50
                    }],
                    2: [{
                        id: 1,
                        img: 18,
                        alcance: 3,
                        area: 4,
                        dano: 500,
                        espera: 5,
                        consumo: 50
                    }, {
                        id: 2,
                        img: 471,
                        alcance: 3,
                        area: 4,
                        dano: 500,
                        espera: 5,
                        consumo: 50
                    }, {
                        id: 3,
                        img: 472,
                        alcance: 3,
                        area: 4,
                        dano: 500,
                        espera: 5,
                        consumo: 50
                    }, {
                        id: 4,
                        img: 483,
                        alcance: 6,
                        area: 1,
                        dano: 700,
                        espera: 3,
                        consumo: 50
                    }, {
                        id: 5,
                        img: 25,
                        alcance: 1,
                        area: 1,
                        dano: 1000,
                        espera: 5,
                        consumo: 50
                    }, {
                        id: 6,
                        img: 24,
                        alcance: 1,
                        area: 1,
                        dano: 1000,
                        espera: 5,
                        consumo: 50
                    }, {
                        id: 7,
                        img: 19,
                        alcance: 7,
                        area: 4,
                        dano: 100,
                        espera: 4,
                        consumo: 50
                    }, {
                        id: 8,
                        img: 215,
                        alcance: 7,
                        area: 4,
                        dano: 100,
                        espera: 5,
                        consumo: 50
                    }, {
                        id: 9,
                        img: 20,
                        alcance: 1,
                        area: 5,
                        dano: 300,
                        espera: 6,
                        consumo: 50
                    }, {
                        id: 10,
                        img: 21,
                        alcance: 3,
                        area: 3,
                        dano: 900,
                        espera: 6,
                        consumo: 50
                    }],
                    3: [{
                        id: 1,
                        img: 254,
                        alcance: 10,
                        area: 1,
                        dano: 1000,
                        espera: 5,
                        consumo: 50
                    }, {
                        id: 2,
                        img: 255,
                        alcance: 10,
                        area: 1,
                        dano: 1000,
                        espera: 5,
                        consumo: 50
                    }, {
                        id: 3,
                        img: 256,
                        alcance: 10,
                        area: 5,
                        dano: 500,
                        espera: 7,
                        consumo: 50
                    }, {
                        id: 4,
                        img: 253,
                        alcance: 7,
                        area: 4,
                        dano: 200,
                        espera: 3,
                        consumo: 50
                    }, {
                        id: 5,
                        img: 260,
                        alcance: 7,
                        area: 4,
                        dano: 200,
                        espera: 3,
                        consumo: 50
                    }, {
                        id: 6,
                        img: 176,
                        alcance: 1,
                        area: 1,
                        dano: 1000,
                        espera: 4,
                        consumo: 50
                    }, {
                        id: 7,
                        img: 264,
                        alcance: 1,
                        area: 2,
                        dano: 1000,
                        espera: 6,
                        consumo: 50
                    }, {
                        id: 8,
                        img: 263,
                        alcance: 3,
                        area: 5,
                        dano: 300,
                        espera: 4,
                        consumo: 50
                    }, {
                        id: 9,
                        img: 258,
                        alcance: 3,
                        area: 5,
                        dano: 300,
                        espera: 4,
                        consumo: 30
                    }, {
                        id: 10,
                        img: 292,
                        alcance: 6,
                        area: 4,
                        dano: 500,
                        espera: 4,
                        consumo: 30
                    }]
                },
                showDetails: false
            },
            profChoiceParams: {
                showDetails: false,
                modifiers: {
                    1: [{
                        icon: 'bonus_0.jpg',
                        description: 'A habilidade ganha 1 quadro de área adicional, porém o dano da habilidade é reduzido pela metade.'
                    }, {
                        icon: 'bonus_1.jpg',
                        description: 'Aplica o efeito "Machucado no joelho" no alvo da habilidade.'
                    }, {
                        icon: 'bonus_2.jpg',
                        description: 'Aplica o efeito "Ponto fraco exposto" no alvo da habilidade.'
                    }, {
                        icon: 'bonus_3.jpg',
                        description: 'Aplica o efeito "Sangramento" no alvo da habilidade.'
                    }, {
                        icon: 'bonus_4.jpg',
                        description: 'Aplica o efeito "Veneno" no alvo da habilidade.'
                    }],
                    2: [{
                        icon: 'bonus_0.jpg',
                        description: 'Transforma a habilidade em uma habilidade de cura que restaura 5000 pontos de vida com área de efeito 1, alcance 1, consumo 500 e espera 5. Obs: a espera de todas as habilidades de cura de todos os tripulantes é compartilhada.'
                    }, {
                        icon: 'bonus_1.jpg',
                        description: 'Transforma a habilidade em uma habilidade de cura que restaura 500 pontos de energia com área de efeito 1, alcance 1, consumo 500 e espera 5. Obs: a espera de todas as habilidades de cura de todos os tripulantes é compartilhada.'
                    }, {
                        icon: 'bonus_2.jpg',
                        description: 'Transforma a habilidade em uma habilidade de cura que restaura 3000 pontos de vida com área de efeito 1, alcance 1, consumo 300 e espera 5. Obs: a espera de todas as habilidades de cura de todos os tripulantes é compartilhada.'
                    }, {
                        icon: 'bonus_3.jpg',
                        description: 'Transforma a habilidade em uma habilidade de cura que restaura 3000 pontos de vida e 300 pontos de energia com área de efeito 1, alcance 1, consumo 600 e espera 5. Obs: a espera de todas as habilidades de cura de todos os tripulantes é compartilhada.'
                    }, {
                        icon: 'bonus_4.jpg',
                        description: 'Transforma a habilidade em uma habilidade de cura que restaura 3000 pontos de vida com área de efeito 2, alcance 1, consumo 600 e espera 5. Obs: a espera de todas as habilidades de cura de todos os tripulantes é compartilhada.'
                    }],
                    3: [{
                        icon: 'atk.png',
                        description: 'Todos os alvos que receberem dano da habilidade recebem um debuff de -50 pontos de ataque por 2 turnos, porém o cosumo da habilidade passa a ser de 100 pontos de energia.'
                    }, {
                        icon: 'atk.png',
                        description: 'A habilidade se transforma em um buff de +100 pontos de ataque por 5 turnos com alcance 1, área 1 e consumo 100.'
                    }, {
                        icon: 'def.png',
                        description: 'Todos os alvos que receberem dano da habilidade recebem um debuff de -50 pontos de defesa por 2 turnos, porém o cosumo da habilidade passa a ser de 100 pontos de energia.'
                    }, {
                        icon: 'def.png',
                        description: 'A habilidade se transforma em um buff de +100 pontos de defesa por 5 turnos com alcance 1, área 1 e consumo 100.'
                    }, {
                        icon: 'agl.png',
                        description: 'Todos os alvos que receberem dano da habilidade recebem um debuff de -50 pontos de agilidade por 2 turnos, porém o cosumo da habilidade passa a ser de 100 pontos de energia.'
                    }, {
                        icon: 'agl.png',
                        description: 'A habilidade se transforma em um buff de +100 pontos de agilidade por 5 turnos com alcance 1, área 1 e consumo 100.'
                    }, {
                        icon: 'res.png',
                        description: 'Todos os alvos que receberem dano da habilidade recebem um debuff de -50 pontos de resistência por 2 turnos, porém o cosumo da habilidade passa a ser de 100 pontos de energia.'
                    }, {
                        icon: 'res.png',
                        description: 'A habilidade se transforma em um buff de +100 pontos de resistência por 5 turnos com alcance 1, área 1 e consumo 100.'
                    }, {
                        icon: 'pre.png',
                        description: 'Todos os alvos que receberem dano da habilidade recebem um debuff de -50 pontos de precisão por 2 turnos, porém o cosumo da habilidade passa a ser de 100 pontos de energia.'
                    }, {
                        icon: 'pre.png',
                        description: 'A habilidade se transforma em um buff de +100 pontos de precisão por 5 turnos com alcance 1, área 1 e consumo 100.'
                    }, {
                        icon: 'dex.png',
                        description: 'Todos os alvos que receberem dano da habilidade recebem um debuff de -50 pontos de destreza por 2 turnos, porém o cosumo da habilidade passa a ser de 100 pontos de energia.'
                    }, {
                        icon: 'dex.png',
                        description: 'A habilidade se transforma em um buff de +100 pontos de destreza por 5 turnos com alcance 1, área 1 e consumo 100.'
                    }, {
                        icon: 'per.png',
                        description: 'Todos os alvos que receberem dano da habilidade recebem um debuff de -50 pontos de percepção por 2 turnos, porém o cosumo da habilidade passa a ser de 100 pontos de energia.'
                    }, {
                        icon: 'per.png',
                        description: 'A habilidade se transforma em um buff de +100 pontos de percepção por 5 turnos com alcance 1, área 1 e consumo 100.'
                    }]
                }
            },
            hakiChoiceParams: {
                showDetails: false
            },
            atrChoiceParams: {
                selectedMode: 'SIMPLES',
                choices: {
                    primario: null,
                    secundario: null,
                    terciario: null
                },
                showDetails: false
            }
        },
        methods: {
            winBattle: function () {
                this.levelXP += 100;

                if (this.levelXP >= this.levelXPMax) {
                    this.levelXP -= this.levelXPMax;
                    this.levelXPMax += 10;
                    this.level += 1;
                }

                var msg = 'Você ganhou 100 pontos de EXP e está no nível ' + this.level + ' da Arena!';
                if (this.level === 2) {
                    msg += '<br/>Você já pode escolher as profissões da sua tripulação!';
                } else if (this.level === 4) {
                    msg += '<br/>Você já pode escolher os modificadores das suas profissões!';
                } else if (this.level === 6) {
                    msg += '<br/>Você já pode escolher as habilidades das suas classes!';
                } else if (this.level === 8) {
                    msg += '<br/>Você já pode escolher o Haki da sua tripulação!';
                } else if (this.level === 10) {
                    msg += '<br/>Você já pode utilizar o criador de builds simplificado!';
                } else if (this.level === 12) {
                    msg += '<br/>Você já pode utilizar o criador de builds intermediário!';
                } else if (this.level === 15) {
                    msg += '<br/>Você já pode utilizar o criador de builds avançado!';
                }
                bootbox.alert(msg);
            },
            selectChar: function (index) {
                if (!this.chars[index]) {
                    this.chars[index] = {
                        index: index,
                        img: 0,
                        nome: '',
                        classe: 0,
                        profissao: 0,
                        skills: [],
                        hakiPoints: 25,
                        hdr: 0,
                        mantra: 0,
                        armamento: 0,
                        atrPoints: 450,
                        attributes: {
                            atk: 1,
                            def: 1,
                            agl: 1,
                            res: 1,
                            pre: 1,
                            dex: 1,
                            per: 1,
                            vit: 1
                        },
                        step: STEPS.IMG,
                        lastStep: STEPS.IMG
                    };
                }
                if (this.char) {
                    this.char.step = this.step;
                    this.char.lastStep = this.lastStep;
                }
                this.step = this.chars[index].step;
                this.lastStep = this.chars[index].lastStep;
                this.char = this.chars[index];
            },
            isStep: function (step) {
                return this.step === step;
            },
            getBigSkinUrl: function (number, skin) {
                return this.getSkinUrl('Big', number, skin || 0);
            },
            getIconSkinUrl: function (number, skin) {
                return this.getSkinUrl('Icons', number, skin || 0);
            },
            getSkinUrl: function (type, number, skin) {
                return 'Imagens/Personagens/' + type + '/' + pad(number, 4) + '(' + skin + ').jpg';
            },
            selectImg: function (number) {
                if (!this.isImgAvailable(number)) {
                    return;
                }
                this.char.img = number;
                this.changeStep(STEPS.IMG, STEPS.NAME);
            },
            isImgAvailable: function (number) {
                var used = !!this.chars.find(function (char) {
                    return char && char.img === number
                });
                return !used && this.charChoiceParams.availableChars.indexOf(number) > -1;
            },
            backTo: function (step) {
                if (this.isStepActive(step)) {
                    this.step = step;
                }
            },
            validateName: function () {
                var self = this;
                setTimeout(function () {
                    self.nameChoiceParams.nameValid = true;
                    self.nameChoiceParams.nameErrorMessage = '';
                    if (self.char.nome.length < 4) {
                        self.nameChoiceParams.nameValid = false;
                        self.nameChoiceParams.nameErrorMessage += '<p>O nome do personagem precisa ter no mínimo 4 caracteres<p/>';
                    }
                    if (self.char.nome.length > 15) {
                        self.nameChoiceParams.nameValid = false;
                        self.nameChoiceParams.nameErrorMessage += '<p>O nome do personagem precisa ter no máximo 15 caracteres<p/>';
                    }
                }, 100);
            },
            selectName: function () {
                this.changeStep(STEPS.NAME, STEPS.CLASSE);
            },
            isStepActive: function (step) {
                var keys = Object.keys(STEPS);
                return keys.indexOf(step) <= keys.indexOf(this.lastStep);
            },
            selectClass: function (classe) {
                this.char.classe = classe;
                this.char.skills = this.getDefaultSkills();

                this.buildPadraoClasse();
            },
            changeStep: function (currentStep, nextStep) {
                if (this.lastStep === currentStep) {
                    this.step = nextStep;
                    this.lastStep = nextStep;
                } else {
                    this.step = this.lastStep;
                }
            },
            getClasseName: function (classe) {
                switch (classe) {
                    case 1:
                        return 'Espadachim';
                    case 2:
                        return 'Lutador';
                    case 3:
                        return 'Atirador';
                    default:
                        return '';
                }
            },
            getClasseDescription: function (classe) {
                switch (classe) {
                    case 1:
                        return 'Espadachins confiam sua força em suas espadas. Sua função é a de causar muito dano apesar de não serem muito resistentes. Suas builds são focadas em dano elevado e altas chances de acerto crítico.';
                    case 2:
                        return 'Lutadores confiam no poder de seus punhos e pés. Sua função é a de dar suporte para o resto da triputalação, por isso precisam ser capazes de sobreviver por longos períodos de tempo. Suas builds são focadas em defesa e em altas chances de esquiva.';
                    case 3:
                        return 'Atiradores confiam no poder de suas armas de longo alcance. Sua função é a de descobrir e explorar as fraquezas do adversário. Suas builds são focadas em causar dano e reduzir as chances dos adversários se esquivarem ou bloquearem os ataques.';
                    default:
                        return '';
                }
            },
            getClasseIconUrl: function (classe) {
                return 'Imagens/Icones/' + this.getClasseName(classe).toLowerCase() + '.png';
            },
            selectSkills: function () {
                this.changeStep(STEPS.CLASSE, STEPS.PROFISSAO);
            },
            getProfName: function (prof) {
                switch (prof) {
                    case 1:
                        return 'Combatente';
                    case 2:
                        return 'Médico';
                    case 3:
                        return 'Músico';
                    default:
                        return '';
                }
            },
            getProfDescription: function (prof) {
                switch (prof) {
                    case 1:
                        return 'Combatentes passam a maior parte de sua vida treinando artes marciais e técnicas de combate, por isso são capazes de causar mais dano em seus adversários. Suas habilidades recebem bônus ofensivos.';
                    case 2:
                        return 'Médicos aprendem a usar medicamentos e aplicar curativos, isso permite que eles possam curar sua tripulação no meio de um combate. Suas habilidades se transformarão em habilidades de cura e restauração.';
                    case 3:
                        return 'Músicos são capazes de usar melodias para motivar seus aliados, tornando-os mais fortes e também intimidar seus adversários, deixando-os mais fracos. Suas habilidades recebêm efeitos especiais como buffs e debuffs.';
                    default:
                        return '';
                }
            },
            getProfIconUrl: function (prof) {
                return 'Imagens/Icones/' + this.getProfName(prof).toLowerCase().replace('é', 'e').replace('ú', 'u') + '.png';
            },
            selectProf: function (prof) {
                this.char.profissao = prof;
                this.char.skills.forEach(function (skill) {
                    skill.modifier = null;
                });
            },
            selectModificadores: function () {
                this.changeStep(STEPS.PROFISSAO, STEPS.HAKI);
            },
            selectHaki: function () {
                this.changeStep(STEPS.HAKI, STEPS.ATRIBUTOS);
            },
            getDefaultSkills: function () {
                return this.char.classe
                    ? this.classeChoiceParams.skills[this.char.classe].slice(0, 5)
                    : [{empty: true}, {empty: true}, {empty: true}, {empty: true}, {empty: true}];
            },
            handleDropSkill: function (index, data) {
                if (!data) {
                    return;
                }
                this.char.skills = this.char.skills.map(function (skill, i) {
                    return (skill.id === data.id) ? {empty: true} : skill;
                });

                this.char.skills[index] = data;
                this.$forceUpdate();
            },
            handleDropModifier: function (index, data) {
                if (!data) {
                    return;
                }
                this.char.skills[index].modifier = data;
                this.$forceUpdate();
            },
            getHakiPointsAvailable: function () {
                return this.char.hakiPoints - this.char.hdr - this.char.mantra - this.char.armamento;
            },
            getHdrPointsAvailable: function () {
                return Math.min(12, this.char.hakiPoints - this.char.mantra - this.char.armamento);
            },
            getMantraPointsAvailable: function () {
                return Math.min(20, this.char.hakiPoints - this.char.hdr - this.char.armamento);
            },
            getArmamentoPointsAvailable: function () {
                return Math.min(20, this.char.hakiPoints - this.char.hdr - this.char.mantra);
            },
            getMaxAttribute: function () {
                var self = this;
                return Math.max.apply(Math, Object.keys(this.char.attributes).map(function (atrKey) {
                    return self.char.attributes[atrKey];
                }));
            },
            getSumAttributes: function () {
                var self = this;
                return Object.keys(this.char.attributes).map(function (atrKey) {
                    return self.char.attributes[atrKey] - 1;
                }).reduce(function (acc, value) {
                    return acc + parseInt(value, 10);
                }, 0);
            },
            buildPadraoClasse: function () {
                switch (this.char.classe) {
                    case 1:
                        this.build3Atributos('atk', 'dex', 'pre');
                        break;
                    case 2:
                        this.build3Atributos('agl', 'def', 'atk');
                        break;
                    case 3:
                        this.build3Atributos('pre', 'atk', 'dex');
                        break;
                    default:
                        break;
                }
            },
            build3Atributos: function (atr1, atr2, atr3) {
                var self = this;
                Object.keys(this.char.attributes).forEach(function (key) {
                    self.char.attributes[key] = 1;
                    if (key === atr1) {
                        self.char.attributes[key] += 225;
                    }
                    if (key === atr2) {
                        self.char.attributes[key] += 150;
                    }
                    if (key === atr3) {
                        self.char.attributes[key] += 75;
                    }
                });
            },
            buildAutomatica: function (atrKey) {
                switch (this.char.classe) {
                    case 1:
                        this.build4Atributos(atrKey, 'atk', 'dex', 'pre');
                        break;
                    case 2:
                        this.build4Atributos(atrKey, 'agl', 'def', 'atk');
                        break;
                    case 3:
                        this.build4Atributos(atrKey, 'pre', 'atk', 'dex');
                        break;
                    default:
                        break;
                }
            },
            build4Atributos: function (atr1, atr2, atr3, atr4) {
                var self = this;
                Object.keys(this.char.attributes).forEach(function (key) {
                    self.char.attributes[key] = 1;
                    if (key === atr1) {
                        self.char.attributes[key] += 150;
                    }
                    if (key === atr2) {
                        self.char.attributes[key] += 140;
                    }
                    if (key === atr3) {
                        self.char.attributes[key] += 100;
                    }
                    if (key === atr4) {
                        self.char.attributes[key] += 60;
                    }
                });
            },
            gerarBuildIntermediaria: function () {
                var choices = this.atrChoiceParams.choices;
                this.build3Atributos(choices.primario, choices.secundario, choices.terciario);
            },
            getNomeAtributo: function (atr) {
                switch (atr) {
                    case 'atk':
                        return 'Ataque';
                    case 'def':
                        return 'Defesa';
                    case 'agl':
                        return 'Agilidade';
                    case 'res':
                        return 'Resistência';
                    case 'pre':
                        return 'Precisão';
                    case 'dex':
                        return 'Destreza';
                    case 'per':
                        return 'Percepção';
                    case 'vit':
                        return 'Vitalidade';
                    default:
                        return '';
                }
            },
            getDescricaoAtributo: function (atr) {
                switch (atr) {
                    case 'atk':
                        return 'Cada ponto aumenta o dano causado pelo personagem em 10.';
                    case 'def':
                        return 'Cada ponto diminui o dano sofrido pelo personagem em 10. obs: A defesa só absorve dano causado por pontos de ataque, dano de habilidade passam despercebidos pela defesa';
                    case 'agl':
                        return 'Cada ponto aumenta sua chance de se esquivar do ataque inimigo em 1%. obs: A porcentagem de chance máxima de se esquivar é de 50%.';
                    case 'res':
                        return 'Cada ponto aumenta sua chance de bloquear o ataque inimgo em 1% e a quantidade de dano absorvido em 1%. obs:A porcentagem de chance máxima de bloqueio é de 50%, e a porcentagem máxima de dano absorvido é de 90%.';
                    case 'pre':
                        return 'Cada ponto reduz a chance do inimigo se esquivar ou bloquear seu ataque em 1%';
                    case 'dex':
                        return 'Cada ponto aumenta sua chance de acertar um ataque crítico em 1% e o dano causado por ataques críticos em 1%. obs: A porcentagem de chance máxima de acertar um ataque crítico é de 50%, e o dano máximo causado por ataque crítico é de 90%.';
                    case 'per':
                        return 'Cada ponto reduz a chance do inimigo te acertar um ataque crítico em 1% e o dano causado por ataques críticos em 1%.';
                    case 'vit':
                        return 'Cada ponto aumenta seu HP em 30 pontos e sua Energia em 7 pontos.';
                    default:
                        return '';
                }
            }
        },
        watch: {
            'char.nome': function (newName, oldName) {
                this.validateName();
            }
        }
    });

    function pad(n, width, z) {
        z = z || '0';
        n = n + '';
        return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
    }
</script>

<div class="panel-body">
    <div id="character-creation-app">
        <div class="list-group">
            <div class="list-group-item">
                <h4>
                    {{level}}º Nível
                </h4>
                <div class="progress">
                    <div class="progress-bar progress-bar-success"
                         v-bind:style="{width: (levelXP/levelXPMax*100)+'%'}">
                        {{levelXP}}/{{levelXPMax}}
                    </div>
                </div>
                <p>Você receberá uma recompensa surpresa por subir de nível.</p>
                <button class="btn btn-primary" v-on:click="winBattle()">
                    Ganhar EXP
                </button>
                <button class="btn btn-success" disabled>
                    Jogar Partida
                </button>
            </div>
        </div>
        <div>
            <ul class="nav nav-pills nav-justified">
                <li v-for="index in 6" v-bind:class="{active: char && index === char.index}" class="personagem-pill"
                    v-on:click="selectChar(index)">
                    <a href="#" class="noHref">
                        <img v-if="chars[index] && chars[index].img" v-bind:src="getIconSkinUrl(chars[index].img)"/>
                        <img v-else src="Imagens/Icones/no-avatar.jpg" class="disabled" style="max-width: 100%"/>
                        <img v-if="chars[index] && chars[index].classe"
                             v-bind:src="getClasseIconUrl(chars[index].classe)" width="30px"/>
                        <img v-if="chars[index] && chars[index].profissao"
                             v-bind:src="getProfIconUrl(chars[index].profissao)" width="30px"/>
                    </a>
                </li>
            </ul>
        </div>
        <div v-if="char">
            <!--- Escolha de personagem -->
            <div class="img-choice" v-if="isStep('IMG')">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Escolha um tripulante
                    </div>
                    <div class="panel-body">
                        <div class="img-list">
                            <img v-for="number in charChoiceParams.charactersCount"
                                 class="icon-img" width="70px"
                                 v-bind:title="!isImgAvailable(number) ? 'Você precisa desbloquear este personagem' : ''"
                                 v-on:click="selectImg(number)"
                                 v-bind:class="{active: number === char.img, disabled: !isImgAvailable(number)}"
                                 v-bind:src="getIconSkinUrl(number)"/>
                        </div>
                    </div>
                </div>
            </div>
            <!--- Escolha de nome -->
            <div class="name-choice" v-else-if="isStep('NAME')">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Escolha um nome para o tripulante
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="status col-xs-3">
                                <img class="big-img mb"
                                     v-bind:src="getBigSkinUrl(char.img)"/>
                                <button class="btn btn-primary btn-sm" v-on:click="backTo('IMG')">
                                    Trocar personagem
                                </button>
                            </div>
                            <div class="creation col-xs-9">
                                <input type="text" minlength="4" maxlength="15"
                                       class="form-control mb"
                                       v-model="char.nome"
                                       placeholder="Digite um nome para o tripulante aqui!">
                                <span class="text-danger" v-if="!nameChoiceParams.nameValid"
                                      v-html="nameChoiceParams.nameErrorMessage"></span>
                                <button class="btn btn-success"
                                        v-on:click="selectName()"
                                        v-bind:disabled="!nameChoiceParams.nameValid">
                                    Avançar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--- Criacao de build -->
            <div class="build-creation" v-else>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <p class="character-name">Construção de build: {{char.nome}}</p>
                        <button class="btn btn-primary btn-sm" v-on:click="backTo('NAME')">
                            Trocar nome
                        </button>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <!--- Status do personagem -->
                            <div class="status col-xs-3">
                                <!--- imagem de corpo -->
                                <div class="mb">
                                    <img class="big-img mb"
                                         v-bind:src="getBigSkinUrl(char.img)"/>
                                    <button class="btn btn-primary btn-sm" v-on:click="backTo('IMG')">
                                        Trocar personagem
                                    </button>
                                </div>
                                <!--- Classe -->
                                <div v-if="char.classe" class="mb text-left">
                                    <img v-bind:src="getClasseIconUrl(char.classe)" width="30px"/>
                                    {{getClasseName(char.classe)}}
                                </div>
                                <!--- Profissão -->
                                <div v-if="char.profissao" class="mb text-left">
                                    <img v-bind:src="getProfIconUrl(char.profissao)" width="30px"/>
                                    {{getProfName(char.profissao)}}
                                </div>
                                <!--- Habilidades -->
                                <div v-if="char.classe" class="mb text-left">
                                    <div class="skill" v-for="(element, i) in char.skills">
                                        <div v-if="!element.empty">
                                            <skill-icon v-bind:skill="element" width="30px"></skill-icon>
                                            <div v-if="element.modifier">
                                                <modifier-icon v-bind:modifier="element.modifier">
                                                </modifier-icon>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--- Haki -->
                                <div v-if="char.hdr || char.mantra || char.armamento" class="mb text-left">
                                    <div>Haki:</div>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-primary"
                                             v-bind:style="{width: (100*char.hdr/char.hakiPoints)+'%'}">
                                            <span>{{char.hdr}}</span>
                                        </div>
                                        <div class="progress-bar progress-bar-success"
                                             v-bind:style="{width: (100*char.mantra/char.hakiPoints)+'%'}">
                                            <span>{{char.mantra}}</span>
                                        </div>
                                        <div class="progress-bar progress-bar-danger"
                                             v-bind:style="{width: (100*char.armamento/char.hakiPoints)+'%'}">
                                            <span>{{char.armamento}}</span>
                                        </div>
                                    </div>
                                </div>
                                <!--- Atributos -->
                                <div>
                                    <div class="row">
                                        <div v-for="atrKey in Object.keys(char.attributes)"
                                             class="col-xs-3 mb attribute-box">
                                            <div class="attribute-progress-bar"
                                                 v-bind:style="{height: (100*char.attributes[atrKey]/getMaxAttribute()) + '%' }">
                                            </div>
                                            <h4>{{char.attributes[atrKey]}}</h4>
                                            <img v-bind:src="'Imagens/Icones/'+atrKey+'.png'" width="30px"
                                                 style="max-width: 100%"/>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--- Build Step by Step -->
                            <div class="creation col-xs-9">
                                <div class="panel-group">
                                    <!--- Classe -->
                                    <div class="panel panel-default">
                                        <div class="panel-heading" v-on:click="backTo('CLASSE')">
                                            <h4 class="panel-title text-left">
                                                <a class="noHref" href="#">
                                                    1. Classe
                                                </a>
                                            </h4>
                                        </div>
                                        <div class="panel-collapse collapse" v-bind:class="{in: isStep('CLASSE')}">
                                            <div class="panel-body">
                                                <h4 class="text-left">
                                                    Escolha uma classe:
                                                    <button class="btn btn-primary btn-sm"
                                                            v-on:click="classeChoiceParams.showDetails = !classeChoiceParams.showDetails">
                                                        Detalhes
                                                    </button>
                                                </h4>
                                                <div class="row">
                                                    <div v-for="classe in 3" class="col-xs-4 choice"
                                                         v-bind:class="{choiced: char.classe === classe}">
                                                        <a class="noHref mb" href="#" v-on:click="selectClass(classe)">
                                                            <img v-bind:src="getClasseIconUrl(classe)"/><br/>
                                                            {{getClasseName(classe)}}
                                                        </a>
                                                        <p v-if="classeChoiceParams.showDetails">
                                                            {{getClasseDescription(classe)}}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div v-if="char.classe && level >= 6">
                                                    <h4 class="text-left">Escolha suas habilidades:</h4>
                                                    <h5 class="text-left">
                                                        Arraste suas habilidades selecionadas aqui:
                                                    </h5>
                                                    <div class="text-left">
                                                        <drop class="drop skill"
                                                              @drop="handleDropSkill(i, ...arguments)"
                                                              v-for="(element, i) in char.skills">
                                                            <template scope="props">
                                                                <div v-if="skillHover" class="hover-here">
                                                                    Arraste Aqui
                                                                </div>
                                                                <div v-if="element.empty">
                                                                    <img src="Imagens/Skils/add.jpg"/>
                                                                </div>
                                                                <div v-else>
                                                                    <skill-icon v-bind:skill="element"></skill-icon>
                                                                </div>
                                                            </template>
                                                        </drop>
                                                    </div>
                                                    <h5 class="text-left">
                                                        Habilidades disponíveis:
                                                    </h5>
                                                    <div class="text-left mb">
                                                        <drag class="skill drag"
                                                              v-for="(element, index) in classeChoiceParams.skills[char.classe]"
                                                              :key="index" :transfer-data="element">
                                                            <div v-on:mouseenter="skillHover = true"
                                                                 v-on:mouseleave="skillHover = false">
                                                                <skill-icon v-bind:skill="element"></skill-icon>
                                                            </div>
                                                        </drag>
                                                    </div>
                                                </div>

                                                <button v-if="char.classe && level >= 2" class="btn btn-success"
                                                        v-on:click="selectSkills()">
                                                    Avançar
                                                </button>
                                                <button v-else-if="char.classe" class="btn btn-success"
                                                        v-on:click="winBattle()">
                                                    Jogar Partida
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <!--- Profissão -->
                                    <div class="panel panel-default"
                                         v-bind:class="{disabled: !isStepActive('PROFISSAO')}">
                                        <div class="panel-heading" v-on:click="backTo('PROFISSAO')">
                                            <h4 class="panel-title text-left">
                                                <a class="noHref" href="#">
                                                    2. Profissão
                                                </a>
                                            </h4>
                                        </div>
                                        <div class="panel-collapse collapse" v-bind:class="{in: isStep('PROFISSAO')}">
                                            <div class="panel-body">
                                                <h4 class="text-left">
                                                    Escolha uma profissão:
                                                    <button class="btn btn-primary btn-sm"
                                                            v-on:click="profChoiceParams.showDetails = !profChoiceParams.showDetails">
                                                        Detalhes
                                                    </button>
                                                </h4>
                                                <div class="row">
                                                    <div v-for="prof in 3" class="col-xs-4 choice"
                                                         v-bind:class="{choiced: char.profissao === prof}">
                                                        <a class="noHref" href="#"
                                                           v-on:click="selectProf(prof)">
                                                            <img v-bind:src="getProfIconUrl(prof)"/><br/>
                                                            {{getProfName(prof)}}
                                                        </a>
                                                        <p v-if="profChoiceParams.showDetails">
                                                            {{getProfDescription(prof)}}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div v-if="char.profissao && level >= 4">
                                                    <h4 class="text-left">Escolha os modificadores da profissão:</h4>
                                                    <h5 class="text-left">
                                                        Arraste seus Modificadores selecionados Aqui:
                                                    </h5>
                                                    <div class="text-left">
                                                        <drop class="drop skill"
                                                              @drop="handleDropModifier(i, ...arguments)"
                                                              v-for="(element, i) in char.skills">
                                                            <template scope="props">
                                                                <div v-if="!element.empty">
                                                                    <div v-if="skillHover" class="hover-here">
                                                                        Arraste Aqui
                                                                    </div>
                                                                    <div v-if="element.modifier">
                                                                        <skill-icon v-bind:skill="element"></skill-icon>
                                                                        <modifier-icon
                                                                                v-bind:modifier="element.modifier">
                                                                        </modifier-icon>
                                                                    </div>
                                                                    <div v-else>
                                                                        <skill-icon v-bind:skill="element"></skill-icon>
                                                                    </div>
                                                                </div>
                                                            </template>
                                                        </drop>
                                                    </div>
                                                    <h5 class="text-left">
                                                        Modificadores disponíveis:
                                                    </h5>
                                                    <div class="text-left mb">
                                                        <drag class="skill-modifier drag"
                                                              v-for="(element, index) in profChoiceParams.modifiers[char.profissao]"
                                                              :key="index" :transfer-data="element">
                                                            <div v-on:mouseenter="skillHover = true"
                                                                 v-on:mouseleave="skillHover = false">
                                                                <modifier-icon v-bind:modifier="element" width="40px">
                                                                </modifier-icon>
                                                            </div>
                                                        </drag>
                                                    </div>
                                                </div>
                                                <button v-if="char.profissao && level >= 8" class="btn btn-success"
                                                        v-on:click="selectModificadores()">
                                                    Avançar
                                                </button>
                                                <button v-else-if="char.profissao" class="btn btn-success"
                                                        v-on:click="winBattle()">
                                                    Jogar Partida
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <!--- Haki -->
                                    <div class="panel panel-default"
                                         v-bind:class="{disabled: !isStepActive('HAKI')}">
                                        <div class="panel-heading" v-on:click="backTo('HAKI')">
                                            <h4 class="panel-title text-left">
                                                <a class="noHref" href="#">
                                                    3. Haki
                                                </a>
                                            </h4>
                                        </div>
                                        <div class="panel-collapse collapse" v-bind:class="{in: isStep('HAKI')}">
                                            <div class="panel-body">
                                                <h4 class="text-left">
                                                    Você possui {{getHakiPointsAvailable()}} pontos de Haki para
                                                    distribuir:
                                                    <button class="btn btn-primary btn-sm"
                                                            v-on:click="hakiChoiceParams.showDetails = !hakiChoiceParams.showDetails">
                                                        Detalhes
                                                    </button>
                                                </h4>
                                                <div class="row">
                                                    <div class="col-xs-4" v-if="char.index === 1">
                                                        <h5>Haki do Rei: {{char.hdr}}</h5>
                                                        <div class="mb">
                                                            0
                                                            <input v-model="char.hdr" class="haki-input"
                                                                   type="range" step="1" min="0"
                                                                   v-bind:max="getHdrPointsAvailable()">
                                                            {{getHdrPointsAvailable()}}
                                                        </div>
                                                        <p v-if="hakiChoiceParams.showDetails">
                                                            o Haki do Rei é uma habilidade especial única do seu
                                                            capitão. Cada ponto aplicado no Haki do Rei aumenta sua área
                                                            de
                                                            efeito em 1 quadro.
                                                        </p>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <h5>Mantra: {{char.mantra}}</h5>
                                                        <div class="mb">
                                                            0
                                                            <input v-model="char.mantra"
                                                                   class="haki-input input-success"
                                                                   type="range" step="1" min="0"
                                                                   v-bind:max="getMantraPointsAvailable()">
                                                            {{getMantraPointsAvailable()}}
                                                        </div>
                                                        <p v-if="hakiChoiceParams.showDetails">
                                                            Cada ponto em Mantra aumenta em 1% a chance do personagem se
                                                            esquivar de um ataque. Cada ponto em mantra também anula 1
                                                            ponto
                                                            do efeito do Mantra adversário.
                                                        </p>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <h5>Armamento: {{char.armamento}}</h5>
                                                        <div class="mb">
                                                            0
                                                            <input v-model="char.armamento"
                                                                   class="haki-input input-danger"
                                                                   type="range" step="1" min="0"
                                                                   v-bind:max="getArmamentoPointsAvailable()">
                                                            {{getArmamentoPointsAvailable()}}
                                                        </div>
                                                        <p v-if="hakiChoiceParams.showDetails">
                                                            Cada ponto em Armamento aumenta em 1% a chance do personagem
                                                            bloquear um ataque e em 1% a chance do personagem acertar um
                                                            ataque crítico. Cada ponto em Armamento também anula 1 ponto
                                                            do
                                                            efeito do Armamento adversário.
                                                        </p>
                                                    </div>
                                                </div>
                                                <button v-if="level >= 10" class="btn btn-success"
                                                        v-on:click="selectHaki()">
                                                    Avançar
                                                </button>
                                                <button v-else class="btn btn-success" v-on:click="winBattle()">
                                                    Jogar Partida
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <!--- Atributos -->
                                    <div class="panel panel-default"
                                         v-bind:class="{disabled: !isStepActive('ATRIBUTOS')}">
                                        <div class="panel-heading" v-on:click="backTo('ATRIBUTOS')">
                                            <h4 class="panel-title text-left">
                                                <a class="noHref" href="#">
                                                    4. Atributos
                                                </a>
                                            </h4>
                                        </div>
                                        <div class="panel-collapse collapse" v-bind:class="{in: isStep('ATRIBUTOS')}">
                                            <div class="panel-body">
                                                <h4 class="text-left">
                                                    Escolha os atributos do seu tripulante:
                                                    <button class="btn btn-primary btn-sm"
                                                            v-on:click="atrChoiceParams.showDetails = !atrChoiceParams.showDetails">
                                                        Detalhes
                                                    </button>
                                                </h4>
                                                <div v-if="atrChoiceParams.showDetails" class="mb">
                                                    <ul class="text-left">
                                                        <li v-for="atrKey in Object.keys(char.attributes)">
                                                            <img v-bind:src="'Imagens/Icones/'+atrKey+'.png'"
                                                                 width="30px"/>
                                                            <strong>{{getNomeAtributo(atrKey)}}:</strong>
                                                            {{getDescricaoAtributo(atrKey)}}
                                                        </li>
                                                    </ul>
                                                </div>

                                                <!--- Menu de modo de criacao de atributos -->
                                                <ul class="nav nav-pills nav-justified mb">
                                                    <li v-bind:class="{active: atrChoiceParams.selectedMode==='SIMPLES'}">
                                                        <a v-on:click="atrChoiceParams.selectedMode='SIMPLES'"
                                                           class="noHref" href="#">Simples</a>
                                                    </li>
                                                    <li v-bind:class="{active: atrChoiceParams.selectedMode==='INTERMEDIARIO'}">
                                                        <a v-if="level >= 12"
                                                           v-on:click="atrChoiceParams.selectedMode='INTERMEDIARIO'"
                                                           class="noHref" href="#">Intermediário</a>
                                                        <a v-else class="noHref">Intermediário</a>
                                                    </li>
                                                    <li v-bind:class="{active: atrChoiceParams.selectedMode==='AVANCADO'}">
                                                        <a v-if="level >= 15"
                                                           v-on:click=" atrChoiceParams.selectedMode='AVANCADO'"
                                                           class="noHref" href="#">Avançado</a>
                                                        <a v-else class="noHref">Avançado</a>
                                                    </li>
                                                </ul>

                                                <!--- Simples -->
                                                <div v-if="atrChoiceParams.selectedMode==='SIMPLES'">
                                                    <h4 class="text-left">
                                                        No modo simples você pode escolher uma das seguintes builds:
                                                    </h4>
                                                    <div class="text-left">
                                                        <a class="noHref" href="#" v-on:click="buildPadraoClasse()">
                                                            <img v-bind:src="getClasseIconUrl(char.classe)"
                                                                 width="30px"/>
                                                            Usar build padrão da classe
                                                        </a>
                                                    </div>
                                                    <div class="text-left"
                                                         v-for="atrKey in Object.keys(char.attributes)">
                                                        <a class="noHref" href="#" v-on:click="buildAutomatica(atrKey)">
                                                            <img v-bind:src="'Imagens/Icones/'+atrKey+'.png'"
                                                                 width="30px"
                                                                 style="max-width: 100%"/>
                                                            Usar build baseada em {{getNomeAtributo(atrKey)}}
                                                        </a>
                                                    </div>
                                                </div>

                                                <!--- Intermediário -->
                                                <div v-else-if="atrChoiceParams.selectedMode==='INTERMEDIARIO'">
                                                    <h4 class="text-left">
                                                        No modo intermediário você pode escolher seus três atributos
                                                        preferidos e a build será criada automaticamente:
                                                    </h4>
                                                    <h5 class="text-left">
                                                        Selecione o atributo primário:
                                                    </h5>
                                                    <div class="mb">
                                                        <div class="build-intermediaria-atr"
                                                             v-for="atrKey in Object.keys(char.attributes)"
                                                             v-on:click="atrChoiceParams.choices.primario = atrKey"
                                                             v-bind:class="{active: atrChoiceParams.choices.primario === atrKey }">
                                                            <img v-bind:src="'Imagens/Icones/'+atrKey+'.png'"
                                                                 width="30px"/>
                                                        </div>
                                                    </div>
                                                    <h5 class="text-left">
                                                        Selecione o atributo secundário:
                                                    </h5>
                                                    <div class="mb">
                                                        <div class="build-intermediaria-atr"
                                                             v-for="atrKey in Object.keys(char.attributes)"
                                                             v-on:click="atrChoiceParams.choices.secundario = atrKey"
                                                             v-bind:class="{active: atrChoiceParams.choices.secundario === atrKey }">
                                                            <img v-bind:src="'Imagens/Icones/'+atrKey+'.png'"
                                                                 width="30px"/>
                                                        </div>
                                                    </div>
                                                    <h5 class="text-left">
                                                        Selecione o atributo terciário:
                                                    </h5>
                                                    <div class="mb">
                                                        <div class="build-intermediaria-atr"
                                                             v-for="atrKey in Object.keys(char.attributes)"
                                                             v-on:click="atrChoiceParams.choices.terciario = atrKey"
                                                             v-bind:class="{active: atrChoiceParams.choices.terciario === atrKey }">
                                                            <img v-bind:src="'Imagens/Icones/'+atrKey+'.png'"
                                                                 width="30px"/>
                                                        </div>
                                                    </div>

                                                    <button class="btn btn-success"
                                                            v-on:click="gerarBuildIntermediaria()">
                                                        Gerar Build
                                                    </button>
                                                </div>

                                                <!--- Avançado -->
                                                <div v-else>
                                                    <h4 class="text-left">
                                                        No modo avançado você tem liberdade total para escolher seus
                                                        atributos:
                                                    </h4>
                                                    <h5 class="text-left">
                                                        Você tem {{char.atrPoints - getSumAttributes()}} pontos de
                                                        atributos
                                                        para distribuir:
                                                    </h5>
                                                    <div v-for="atrKey in Object.keys(char.attributes)">
                                                        <img v-bind:src="'Imagens/Icones/'+atrKey+'.png'" width="30px"/>
                                                        <input v-model="char.attributes[atrKey]" class="atr-input"
                                                               type="range" step="1" min="1"
                                                               v-bind:max="char.atrPoints - getSumAttributes() + parseInt(char.attributes[atrKey], 10)">
                                                    </div>
                                                </div>


                                                <button class="btn btn-success"
                                                        v-on:click="winBattle()">
                                                    Jogar Partida
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <p>Selecione um espaço na tripulação para criar seu tripulante.</p>
        </div>
    </div>
</div>