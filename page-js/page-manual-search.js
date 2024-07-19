const sform = document.getElementById('search-form');

sform.addEventListener('submit', (event) => {
    event.preventDefault();
    var query = sform.elements['query'].value;
    window.location.href = window.location.href.split('&query=')[0] + "&query=" + encodeURI(query);
});

