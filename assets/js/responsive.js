"use strict";

document.addEventListener("DOMContentLoaded", onLoad);

function onLoad(e) {
    document.querySelector(".spike").addEventListener('click', doNothing);
    document.querySelector("#accountIcon").addEventListener('click', toggleChecked);
    document.querySelector("body").addEventListener('click', removeCheckedAll);
}

function toggleChecked(e) {
    e.stopPropagation();
    e.target.classList.toggle("checked");
}

function doNothing(e) {
    e.stopPropagation();
}

function removeCheckedAll(e) {
    let checkedItems = document.querySelectorAll(".checked");

    for(let i=0; i<checkedItems.length; i++) {
        checkedItems[i].classList.remove("checked");
    }
}
