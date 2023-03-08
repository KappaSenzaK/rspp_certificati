
const URL = "http://localhost/rspp_certificati/";

let btn = document.getElementById("login")

btn.onclick = function (e) {
    login('ettore.franchi', '1234')
}

function login(email, password){
    fetch(`${URL}/login.php`, {
        method: "POST",
        body: JSON.stringify({email: email, password: password})
    })
        .then(response => response.json())
        .then(data => console.log(data))
}
