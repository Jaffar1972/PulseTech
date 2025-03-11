document.addEventListener("DOMContentLoaded", function() {
    const searchContainer = document.querySelector(".search-container");
    const searchButton = document.querySelector("#searchButton");

    if (searchButton) {
        searchButton.addEventListener("click", function() {
            searchContainer.classList.toggle("visible");
        });
    }
});


