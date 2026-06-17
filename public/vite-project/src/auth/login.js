document.getElementById("loginForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    const email = e.target.email.value;
    const contrasena = e.target.contrasena.value;

    const res = await fetch("http://localhost/cinema-online-ticket/api/login", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email, contrasena })
    });

    const data = await res.json();

    if (!data.token) {
        alert("Login incorrecto");
        return;
    }
    console.log(data.token);

    localStorage.setItem("token", data.token);

    const payload = parseJwt(data.token);
    console.log(payload);

    localStorage.setItem("usuario", JSON.stringify({
        id: payload.id,
        nombre: payload.nombre,
        email: payload.email,
        rol: payload.rol
    })
    );

    if (payload.rol === 1) {
        location.href = "../../admin.html";
    } else {
        location.href = "./perfil.html";
    }
});

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