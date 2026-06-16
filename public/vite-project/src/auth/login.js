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

    const payload = JSON.parse(atob(data.token.split(".")[1]));

    if (payload.rol === 1) {
        location.href = "../../admin.html";
    } else {
        location.href = "./perfil.html";
    }
});