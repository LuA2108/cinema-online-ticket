// Escucha el envío del formulario de login
document.getElementById("loginForm").addEventListener("submit", async (e) => {
    e.preventDefault(); // Evita recarga de la página

    // Obtener datos del formulario
    const email = e.target.email.value;
    const contrasena = e.target.contrasena.value;

    // Enviar petición al backend para login
    const res = await fetch("http://localhost/cinema-online-ticket/api/login", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email, contrasena })
    });

    const data = await res.json();

    // Si no hay token, login incorrecto
    if (!data.token) {
        alert("Login incorrecto");
        return;
    }

    console.log(data.token);

    // Guardar token en localStorage
    localStorage.setItem("token", data.token);

    // Decodificar JWT para obtener datos del usuario
    const payload = parseJwt(data.token);
    console.log(payload);

    // Guardar información del usuario en localStorage
    localStorage.setItem("usuario", JSON.stringify({
        id: payload.id,
        nombre: payload.nombre,
        email: payload.email,
        rol: payload.rol
    }));

    // Redirigir según el rol del usuario
    if (payload.rol === 1) {
        location.href = "../../admin.html"; // Admin
    } else {
        location.href = "./perfil.html"; // Usuario normal
    }
});

// Función para decodificar JWT de forma segura
function parseJwt(token) {
    return JSON.parse(
        decodeURIComponent(
            atob(token.split('.')[1])
                .split('')
                .map(c => '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2))
                .join('')
        )
    );
}