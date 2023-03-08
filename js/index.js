
const URL = "http://localhost/rspp_certificati";

let btn = document.getElementById("login")

btn.onclick = async function (e) {
    await login('ettore.franchi', '1234')
}

async function login(email, password){
    let response = await fetch(`${URL}/auth/login.php`, {
        method: "POST",
        body: JSON.stringify({email: email, password: password})
    })
    let data = await response.json();
    console.log(data)
}
