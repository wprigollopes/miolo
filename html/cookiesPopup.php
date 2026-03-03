<style>
    .cookieConsentContainer {
        z-index: 999;
        width: 700px;
        min-height: 20px;
        box-sizing: border-box;
        padding: 30px 30px 30px 30px;
        background: #232323;
        overflow: hidden;
        position: fixed;
        bottom: 0;
        right: 0;
        display: none;
        text-align: left;
        z-index: 1000;
    }

    .cookieConsentContainer .cookieTitle a {
        font-family: OpenSans, arial, sans-serif;
        color: #fff;
        font-size: 22px;
        line-height: 20px;
        display: block
    }

    .cookieConsentContainer .cookieDesc p {
        margin: 0;
        padding: 0;
        font-family: OpenSans, arial, sans-serif;
        color: #fff;
        font-size: 13px;
        line-height: 20px;
        display: block;
        margin-top: 10px
    }

    .cookieConsentContainer .cookieDesc a {
        font-family: OpenSans, arial, sans-serif;
        color: #fff;
        text-decoration: underline
    }

    .cookieConsentContainer .cookieButton a {
        display: inline-block;
        font-family: OpenSans, arial, sans-serif;
        color: #fff;
        font-size: 14px;
        font-weight: 700;
        margin-top: 14px;
        background: #000;
        box-sizing: border-box;
        padding: 15px 24px;
        text-align: center;
        transition: background .3s
    }

    .cookieConsentContainer .cookieButton a:hover {
        cursor: pointer;
        background: #1F72BF;
    }

    @media (max-width:980px) {
        .cookieConsentContainer {
            bottom: 0!important;
            left: 0!important;
            width: 100%!important
        }
    }
</style>

<script>
    var purecookieTitle = "",
        purecookieDesc = "",
        purecookieButton = "Aceito";

    function pureFadeIn(e, o) {
        var i = document.getElementById(e);
        i.style.opacity = 0, i.style.display = o || "block",
            function e() {
                var o = parseFloat(i.style.opacity);
                (o += .02) > 1 || (i.style.opacity = o, requestAnimationFrame(e))
            }()
    }

    function pureFadeOut(e) {
        var o = document.getElementById(e);
        o.style.opacity = 1,
            function e() {
                (o.style.opacity -= .02) < 0 ? o.style.display = "none" : requestAnimationFrame(e)
            }()
    }

    function cookieConsent() {
        if (!localStorage.getItem("cookiesAcceptedSolisGe")) {
            (document.body.innerHTML += '<div class="cookieConsentContainer" id="cookieConsentContainer"><div class="cookieTitle"><a>Política de Privacidade e Cookies</a></div><div class="cookieDesc"><p>Usamos cookies para fornecer os recursos e serviços oferecidos em nosso sistema para melhorar a experiência do usuário. Ao continuar navegando neste site, você concorda com o uso destes cookies. Leia nossa <a href="/politica" target="_blank">Política de Privacidade e Cookies</a> para saber mais.</p></div><div class="cookieButton"><a onClick="purecookieDismiss();">' + purecookieButton + "</a></div></div>", pureFadeIn("cookieConsentContainer"))
        }        
    }

    function purecookieDismiss() {
        localStorage.setItem("cookiesAcceptedSolisGe", "true");
        pureFadeOut("cookieConsentContainer")
    }

    cookieConsent();
</script>