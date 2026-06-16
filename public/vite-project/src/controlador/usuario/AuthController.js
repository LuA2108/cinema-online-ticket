class AuthController {
    constructor(vista) {
        this.vista = vista;
    }

    iniciar() {
        const token = localStorage.getItem("token");

        if (token) {
            const botonLogout = this.vista.renderUsuarioLogueado();

            botonLogout.addEventListener("click", () => {
                this.cerrarSesion();
            });

        } else {
            this.vista.renderInvitado();
        }
    }

    cerrarSesion() {
        localStorage.removeItem("token");
        window.location.href = "../index.html";
    }
}

export default AuthController;