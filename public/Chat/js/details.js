/**
 * Created by Luiz Eduardi on 15/05/2017.
 */

// sandbox disable popups
if (window.self !== window.top && window.name != "view1") {

    window.alert = function () {/*disable alert*/
    };
    window.confirm = function () {/*disable confirm*/
    };
    window.prompt = function () {/*disable prompt*/
    };
    window.open = function () {/*disable open*/
    };
}

// prevent href=# click jump
document.addEventListener("DOMContentLoaded", function () {
    var links = document.getElementsByTagName("A");
    for (var i = 0; i < links.length; i++) {
        if (links[i].href.indexOf('#') != -1) {
            links[i].addEventListener("click", function (e) {
                console.debug("prevent href=# click");
                if (this.hash) {
                    if (this.hash == "#") {
                        e.preventDefault();
                        return false;
                    }
                    else {
                        /*
                         var el = document.getElementById(this.hash.replace(/#/, ""));
                         if (el) {
                         el.scrollIntoView(true);
                         }
                         */
                    }
                }
                return false;
            })
        }
    }
}, false);