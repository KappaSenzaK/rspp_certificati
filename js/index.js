
const URL = "http://localhost/rssp_certificati";

let btn = document.getElementById("login");
let password = document.getElementById("password");
let email = document.getElementById("email");

btn.onclick = async function (e) {
    e.preventDefault();
    console.log(password.value, email.value);
    await login(email.value, password.value);
}

async function login(email, password){
    let response = await fetch(`${URL}/auth/login.php`, {
        method: "POST",
        body: JSON.stringify({email: email, password: password})
    })
    let data = await response.json();
    console.log(data)
}
