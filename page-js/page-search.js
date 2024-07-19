const sform = document.getElementById('search-form');

function search() {
    var query = sform.elements['query'].value;
    var totalURL = window.location.href.split('&query=')[0] + "&query=" + encodeURI(query);

    window.location.href = totalURL;
}

sform.addEventListener('submit', (event) => {
    event.preventDefault();
    search();
});
