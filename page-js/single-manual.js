var curChapter;
var curSubchapter;

function changeSelectedChapter(newChapter) {
    existing = document.getElementById('selected-chapter');
    if (existing) existing.removeAttribute('id');
    chapterLinks = document.getElementsByClassName('chapter');
    for (var i = 0; i < chapterLinks.length; i++) {
        if (chapterLinks[i].textContent == newChapter) {
            chapterLinks[i].id = 'selected-chapter';
        }
    }
}

function setDefaultChapter() {
    chapters = document.getElementsByClassName('chapter');
    subchapters = document.getElementsByClassName('subchapter');
    if (chapters[0] && subchapters[0]) {
        chapters[0].id = 'selected-chapter';
        subchapters[0].id = 'selected-subchapter';
        changeContent("subchapter=" + subchapters[0].textContent);
    }
}

function removeSubchapter() {
    existing = document.getElementById('selected-subchapter');
    if (existing) existing.removeAttribute('id');
}

function changeSelectedSubchapter(newSubchapter) {
    existing = document.getElementById('selected-subchapter');
    if (existing) existing.removeAttribute('id');
    subchapterLinks = document.getElementsByClassName('subchapter');
    for (var i = 0; i < subchapterLinks.length; i++) {
        if (subchapterLinks[i].textContent == newSubchapter) {
            subchapterLinks[i].id = 'selected-subchapter';
        }
    }
}

function onHashChange() {
    curChapter = undefined;
    curSubchapter = undefined;

    const fragment = decodeURI(window.location.hash).slice(1);
    var divId;

    var pairs = fragment.split('&').forEach((item) => {
        keyValue = item.split('=');
        key = keyValue[0];
        value = keyValue[1];

        if (key == 'chapter') {
            curChapter = value;
        }

        if (key == 'subchapter') {
            curSubchapter = value;
        }
    });

    if (curChapter == undefined) {
        setDefaultChapter();
    }
    else {
        changeSelectedChapter(curChapter);
    }

    if (curSubchapter == undefined) {
        setDefaultChapter();
    }
    else {
        changeSelectedSubchapter(value);
        divId = 'subchapter=' + curSubchapter;
    }

    if (divId) changeContent(divId);
}

function changeContent(divId) {
    var contents = document.getElementsByClassName('active-content');

    for (var i = 0; i < contents.length; i++) {
        contents[i].className = contents[i].className.replace(" active-content", "");
    }

    var content = document.getElementById(divId);
    if (content) content.className += " active-content";
}

onHashChange();

window.addEventListener('hashchange', onHashChange);
