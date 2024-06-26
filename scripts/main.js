document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.nav-button');

    buttons.forEach(button => {
        button.addEventListener('click', function() {
            // Xóa lớp active khỏi tất cả các button
            buttons.forEach(btn => btn.classList.remove('active'));
            // Thêm lớp active vào button được nhấn
            button.classList.add('active');
        });
    });
});

function openForm() {
    document.getElementById("voucherForm").style.display = "block";
}

function closeForm() {
    document.getElementById("voucherForm").style.display = "none";
}

function navigateTo(url) {
    window.location.href = url;
}

function loadHTML(element, url) {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        if (this.status === 200) {
            element.innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", url, true);
    xhttp.send();
}

document.addEventListener("DOMContentLoaded", function() {
    loadHTML(document.querySelector("header"), "header.html");
    loadHTML(document.querySelector("footer"), "footer.html");
});