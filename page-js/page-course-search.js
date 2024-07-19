const dform = document.getElementById('difficulty-radio');
const sform = document.getElementById('search-form');
const asform = document.getElementById('additional-search-form');

const hform = document.getElementById('hours');
const pform = document.getElementById('people');
const lform = document.getElementById('lections');

const rform = document.getElementById('rating');
const prform = document.getElementById('price');

const cform = document.getElementById('categories');

function search() {
    var query = sform.elements['query'].value;
    if (query == '') {
        query = asform.elements['query'].value;
    }
    var difficulty = dform.elements['level'].value;
    var totalURL = window.location.href.split('&query=')[0] + "&query=" + encodeURI(query);
    if (difficulty) totalURL += '&level=' + difficulty;

    var hours = hform.elements['hours-count'].value;
    var hoursComp = hform.elements['hours-compare'].value;
    if (hours && hoursComp) totalURL += '&hours_count=' + hours + '&hours_compare=' + hoursComp;

    var people = pform.elements['people-count'].value;
    var peopleComp = pform.elements['people-compare'].value;
    if (people && peopleComp) totalURL += '&people_count=' + people + '&people_compare=' + peopleComp;

    var lections = lform.elements['lections-count'].value;
    var lectionsComp = lform.elements['lections-compare'].value;
    if (lections && lectionsComp) totalURL += '&lections_count=' + lections + '&lections_compare=' + lectionsComp;

    var rating = rform.elements['rating'].value;
    var ratingComp = rform.elements['rating-compare'].value;
    if (rating && ratingComp) totalURL += '&rating=' + rating + '&rating_compare=' + ratingComp;

    var price = prform.elements['price'].value;
    var priceComp = prform.elements['price-compare'].value;
    if (price && priceComp) totalURL += '&price=' + price + '&price_compare=' + priceComp;

    var category = cform.elements['category'].value;
    if (category) totalURL += '&category=' + category;
    window.location.href = totalURL;
}

sform.addEventListener('submit', (event) => {
    event.preventDefault();
    search();
});

asform.addEventListener('submit', (event) => {
    event.preventDefault();
    search();
});
