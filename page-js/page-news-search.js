const sform = document.getElementById('search-form');

const cform = document.getElementById('categories');

function search() {
    var query = sform.elements['query'].value;
    var totalURL = window.location.href.split('&query=')[0] + "&query=" + encodeURI(query);

    var category = cform.elements['category'].value;
    if (category) totalURL += '&category=' + category;
    window.location.href = totalURL;
}

sform.addEventListener('submit', (event) => {
    event.preventDefault();
    search();
});
