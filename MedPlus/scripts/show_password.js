document.getElementById("pwd_btn_1").addEventListener("mousedown", function() {
document.getElementById("password").type = "text";
document.getElementById("pwd_btn_1").value = "Nascondi Password";
});


document.getElementById("pwd_btn_1").addEventListener("mouseup", function() {
document.getElementById("password").type = "password";
document.getElementById("pwd_btn_1").value = "Mostra Password";
});

document.getElementById("pwd_btn_2").addEventListener("mousedown", function() {
document.getElementById("password_2").type = "text";
document.getElementById("pwd_btn_2").value = "Nascondi Password";
});


document.getElementById("pwd_btn_2").addEventListener("mouseup", function() {
document.getElementById("password_2").type = "password";
document.getElementById("pwd_btn_2").value = "Mostra Password";
});

