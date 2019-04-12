"use strict";

document.addEventListener("DOMContentLoaded", onLoad);

function onLoad(e) {
    document.querySelector("body").addEventListener("click", bodyClicked);
    let searchBars = document.querySelectorAll(".searchBar");

    for(let i=0; i< searchBars.length; i++) {
        searchBars[i].addEventListener("click", searchClicked);
        searchBars[i].addEventListener("input", findSearchResult);
    }

    let forms = document.querySelectorAll("form");

    for(let i=0; i<forms.length; i++) {
        forms[i].addEventListener("submit", onFormSubmit)
    }
}

function bodyClicked(e) {
    console.log("body clicked");

    let searchBars = document.querySelectorAll(".searchBar");

    for(let i=0; i< searchBars.length; i++) {
        document.querySelector(`#${searchBars[i].id}-result`).style.display = "none";
    }
}

function searchClicked(e) {
    e.preventDefault();
    e.stopImmediatePropagation();

    console.log("searchBar clicked");

    document.querySelector(`#${this.id}-result`).style.display = "block";
}

function findSearchResult(e) {
    e.preventDefault();

    let id = this.id;
    let searchValue = this.value;
    let searchResultObj = document.querySelector(`#${id}-result`);
    console.log(searchValue);
    searchValue = encodeURI(searchValue);

    if(searchValue.length === 0) {
        console.log("Nothing to be searched");
        searchResultObj.innerHTML = "";
        return;
    }

    fetch(`assets/php/request.php?request=search&value=${searchValue}`, {
        method: 'GET'
    })
    .then(function(res) {
        if(res.ok === true)
            return res.json();
    })
    .then(function(text) {
        let result = text;
        console.log("Got them result", result);
        handleSearchItems(searchResultObj, result);
    })
    .catch(function(err) {
        console.log("Error 404: Could not connect to the server - Find", err.message);
    });
}

function handleSearchItems(searchResultObj, searchObj) {
    // TODO
    searchResultObj.innerHTML = "";

    if(searchObj.length <= 0) {
        searchResultObj.innerHTML += '<li>No username found</li>';
    }
    else {
        for (let i = 0; i < searchObj.length; i++) {
            console.log(searchObj[i]);
            searchResultObj.innerHTML += '<li id="' + searchResultObj.id.split("-")[0] + '-' + i + '">' + searchObj[i] + '</li>';
        }

        let li = document.querySelectorAll(`#${searchResultObj.id} li`);

        for (let i = 0; i < li.length; i++) {
            li[i].addEventListener("click", selectSearchResult);
        }
    }
}

function selectSearchResult(e) {
    e.preventDefault();
    e.stopImmediatePropagation();

    let id = this.id.split("-")[0];

    document.querySelector(`#${id}`).value = this.innerText;
    console.log("done");
}