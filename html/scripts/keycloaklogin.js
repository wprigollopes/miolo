function initKeycloak(url, realm, clientId, userInfoUrl) {
    sessionStorage.url = url;
    sessionStorage.realm = realm;
    sessionStorage.clientId = clientId;
    sessionStorage.userInfoUrl = userInfoUrl;
    var keycloak = new Keycloak({
        url: url,
        realm: realm,
        clientId: clientId
    });
    keycloak.init({onLoad: "login-required"}).then(function(authenticated) {
        sessionStorage.clear();
        document.cookie = "userInfoUrl=" + userInfoUrl + ";path=/";
        document.getElementById("keycloakToken").value = keycloak.token;
        document.getElementById("btnLogin").click();
    }).catch(function() {
        sessionStorage.clear();
        console.log("failed to initialize");
    });
}
if (sessionStorage.url != null) {
    initKeycloak(sessionStorage.url, sessionStorage.realm, sessionStorage.clientId, sessionStorage.userInfoUrl);
}
