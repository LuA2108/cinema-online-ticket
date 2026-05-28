const token = localStorage.getItem("token");

if (!token) {
    window.location.href = "../../index.html";
}

fetch("http://localhost/cinema-online-ticket/api/admin", {
    headers: {
        Authorization: "Bearer " + token
    }
})
.then(res => res.json())
.then(data => {
    console.log(data);
});

document.getElementById("logoutBtn").addEventListener("click", () => {
    localStorage.removeItem("token");

    // redirigir a login
    window.location.href = "../../index.html";
});