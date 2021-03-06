(function () {
    var animations = [];

    $.ajax({
        url: 'Imagens/Skils/Animacoes/Animations.json',
        success: function (response) {
            animations = response;
        }
    });

    window.Animation = function (name) {
        this.name = name;
        this.animation = null;

        for (var i = 1; i < animations.length; i++) {
            if (animations[i].name == name) {
                this.animation = animations[i];
                break;
            }
        }
        if (!this.animation) {
            return;
        }

        this.background1 = 'Imagens/Skils/Animacoes/' + this.animation.animation1Name + '.png';
        this.background2 = 'Imagens/Skils/Animacoes/' + this.animation.animation2Name + '.png';

        var self = this;
        if (this.animation.animation1Name) {
            this.img1 = new Image();
            this.img1.src = this.background1;
            this.img1Load = false;
            this.img1.onload = function () {
                self.img1Load = true;
                self.requestPlayFrame();
            };
        } else {
            this.img1Load = true;
        }
        if (this.animation.animation2Name) {
            this.img2 = new Image();
            this.img2.src = this.background2;
            this.img2Load = false;
            this.img2.onload = function () {
                self.img2Load = true;
                self.requestPlayFrame();
            };
        } else {
            this.img2Load = true;
        }

        this.playing = false;
        this.playRequested = false;
    };

    Animation.prototype.play = function (options) {
        if (!this.animation) {
            return;
        }
        options = options || {};

        this.top = options.top || 100;
        this.left = options.left || 100;
        this.delay = options.delay || 70;
        this.scale = options.scale || 0.5;
        this.callback = options.callback;
        this.frame = 0;

        this.$elem = $('<DIV>')
            .css('position', 'relative');

        this.$conteiner = $('<DIV>')
            .css('position', options.fixed ? 'fixed' : 'absolute')
            .css('top', this.top - (192 / 2) * this.scale)
            .css('left', this.left - (192 / 2) * this.scale)
            .css('-moz-transform', 'scale(' + this.scale + ')')
            .css('-o-transform', 'scale(' + this.scale + ')')
            .css('-webkit-transform', 'scale(' + this.scale + ')')
            .css('transform', 'scale(' + this.scale + ')')
            .append(this.$elem);

        $('body').append(this.$conteiner);

        this.playRequested = true;
        this.requestPlayFrame();
    };

    Animation.prototype.requestPlayFrame = function () {
        if (this.playRequested && this.img1Load && this.img2Load && !this.playing) {
            this.playFrame();
        }
    };


    Animation.prototype.playFrame = function () {
        this.playing = true;
        this.$elem.empty();

        var frame = this.animation.frames[this.frame];

        for (var i = 0; i < frame.length; i++) {
            var item = frame[i];
            if (item[0] >= 0) {
                var tile = item[0];
                var background = tile > 99 ? this.background2 : this.background1;
                if (tile > 99) {
                    tile -= 100;
                }
                var displaceLeft = (tile % 5) * 192;
                var displaceTop = Math.floor(tile / 5) * 192;

                this.$elem.append(
                    $('<DIV>')
                        .css('display', 'block')
                        .css('position', 'absolute')
                        .css('width', '192px')
                        .css('height', '192px')
                        .css('background', 'url("' + background + '")')
                        .css('background-position', -displaceLeft + 'px ' + -displaceTop + 'px')
                        .css('opacity', item[6] / 255)
                        .css('left', item[1] / 2)
                        .css('top', item[2] / 2)
                        .css('-moz-transform', 'scale(' + (item[3] / 100) + ')')
                        .css('-o-transform', 'scale(' + (item[3] / 100) + ')')
                        .css('-webkit-transform', 'scale(' + (item[3] / 100) + ')')
                        .css('transform', 'scale(' + (item[3] / 100) + ')')
                        .css('-moz-transform', 'rotate(' + item[4] + 'deg)')
                        .css('-o-transform', 'rotate(' + item[4] + 'deg)')
                        .css('-webkit-transform', 'rotate(' + item[4] + 'deg)')
                        .css('transform', 'rotate(' + item[4] + 'deg)')
                );
            }
        }

        for (i = 0; i < this.animation.timings.length; i++) {
            var timing = this.animation.timings[i];
            if (timing.frame == this.frame && timing.se) {
                playAudio('Sons/se/' + timing.se.name + '.ogg');
            }
        }

        this.frame++;
        var self = this;
        if (this.animation.frames[this.frame]) {
            setTimeout(function () {
                self.playFrame();
            }, this.delay);
        } else {
            setTimeout(function () {
                self.$conteiner.remove();
                self.playing = false;
                if (self.callback) {
                    self.callback();
                }
            }, this.delay);
        }
    };
}());
