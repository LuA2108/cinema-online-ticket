class Perfil {

    mostrarUsuario(usuario) {

        document.getElementById("titulo-perfil").replaceChildren("Hola ", usuario.nombre);

        document.getElementById("perfilNombre").textContent = usuario.nombre ?? " - ";
        document.getElementById("perfilEmail").textContent = usuario.email ?? " -";
        document.getElementById("perfilCiudad").textContent = usuario.ciudad ?? " - ";
        document.getElementById("perfilProvincia").textContent = usuario.provincia ?? " - ";
        document.getElementById("perfilRol").textContent = usuario.rol_id == 2 ? "Usuario" : " - ";
        document.getElementById("perfilFechaRegistro").textContent = usuario.fecha_registro ?? " - ";
    }

    rellenarFormulario(usuario) {
        document.getElementById("nombre").value = usuario.nombre ?? " - ";
        document.getElementById("email").value = usuario.email ?? " - ";
        document.getElementById("ciudad").value = usuario.ciudad ?? " - ";
        document.getElementById("provincia").value = usuario.provincia ?? " - ";
    }
}

export default Perfil;