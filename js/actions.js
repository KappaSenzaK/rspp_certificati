
function sendmail(){

}

function userProfile(element) {
  let id = element.id;
  let mail = document.getElementById(`${id}mail`).innerHTML;
  window.location.href = `userProfile.php?mail=${mail}`;
}