class AuthView {
    constructor() {
        this.submenu = document.getElementById("submenu");
    }

    limpiar() {
        this.submenu.replaceChildren();
    }

    renderUsuarioLogueado() {
        this.limpiar();

        const liPerfil = document.createElement("li");

        const enlacePerfil = document.createElement("a");
        enlacePerfil.href = "./perfil.html";
        enlacePerfil.textContent = "Perfil";

        liPerfil.appendChild(enlacePerfil);

        const liCerrarSesion = document.createElement("li");

        const botonLogout = document.createElement("button");
        botonLogout.id = "logoutBtn";
        botonLogout.className =
            "btn border-0 bg-transparent fs-6 p-1 fw-semibold";
        botonLogout.textContent = "Cerrar sesión";

        liCerrarSesion.appendChild(botonLogout);

        this.submenu.append(liPerfil, liCerrarSesion);

        return botonLogout;
    }

    renderInvitado() {
        this.limpiar();

        const liLogin = document.createElement("li");

        const enlaceLogin = document.createElement("a");
        enlaceLogin.href = "../login.html";
        enlaceLogin.textContent = "Iniciar sesión";

        liLogin.appendChild(enlaceLogin);

        const liRegistro = document.createElement("li");

        const enlaceRegistro = document.createElement("a");
        enlaceRegistro.href = "../registro.html";
        enlaceRegistro.textContent = "Registrarse";

        liRegistro.appendChild(enlaceRegistro);

        this.submenu.append(liLogin, liRegistro);
    }
}

export default AuthView;