window.onload = function(){
    let likeButtons = document.querySelectorAll(".like-container button");

    for(const likeButton of likeButtons){
        likeButton.addEventListener("click", like);
    }

    function like(event) {
        const id = event.target.parentNode.getAttribute("data-id");
        const http = new XMLHttpRequest();

        http.open("POST", `/articles/${id}/likes`, true);
        http.setRequestHeader("Content-type", "application/json");
        http.send();

        http.onreadystatechange = function () {
            if (http.readyState === 4) {
                if (http.status === 200) {
                    document.querySelector(`.like-container[data-id="${id}"] p`).innerHTML++;
                }
            }
        }
    }
}