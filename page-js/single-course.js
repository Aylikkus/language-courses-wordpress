var tab;

function changeSelectedTab(newChapter) {
    existing = document.getElementsByClassName('selected-link');
    for (var i = 0; i < existing.length; i++) {
        existing[i].removeAttribute('class');
    }
    tabs = document.querySelectorAll('.tab-row a');
    for (var i = 0; i < tabs.length; i++) {
        if (tabs[i].textContent == tab) {
            tabs[i].classList.add('selected-link');
        }
    }
}

function setDefaultTab() {
    tabs = document.querySelectorAll('.tab-row a');
    if (tabs[0]) {
        tabs[0].classList.add('selected-link');
        changeContent(tabs[0].id);
    }
}

function changeContent(divId) {
    var contents = document.getElementsByClassName('active-content');

    for (var i = 0; i < contents.length; i++) {
        contents[i].className = contents[i].className.replace(" active-content", "");
    }

    var content = document.getElementById('content=' + divId);
    if (content) content.className += " active-content";
}

function onHashChange() {
    tab = undefined;

    const fragment = decodeURI(window.location.hash).slice(1);
    tab = fragment;

    if (tab == undefined) {
        setDefaultTab();
    }
    else {
        changeSelectedTab(tab);
        changeContent(tab);
    }
}

onHashChange();

window.addEventListener('hashchange', onHashChange);
