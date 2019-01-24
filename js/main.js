var fancyItems = document.querySelectorAll(".album-item");

function addData() {
    fancyItems.forEach(function (item) {
        if (item.getAttribute("data-fancybox") !== "gallery") {
            item.setAttribute("data-fancybox", "gallery");
        }
    });
}

[].forEach.call(fancyItems, function (item) {
    item.addEventListener("click", addData);
});

