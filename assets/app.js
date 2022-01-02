/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
import './styles/series.scss';
import './styles/fonts.scss';
import './styles/_footer.scss';
import 'bootstrap-icons/font/bootstrap-icons.css';
import './styles/_navbar.scss';
import './styles/home.scss';
import './styles/login.scss';

// start the Stimulus application
import './bootstrap';

const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');

// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');
/* 
$(document).ready(() => {
    $('[data-toggle="popover"]').popover();
}); */

// AJAX seriesWatchlist
document.querySelector("#watchlist").addEventListener('click', addToSeriesWatchlist);

function addToSeriesWatchlist(event) {
    event.preventDefault();
    
    // Get the link object you click in the DOM
    let watchlistLink = event.currentTarget;
    let link = watchlistLink.href;
    // Send an HTTP request with fetch to the URI defined in the href
    fetch(link)
    // Extract the JSON from the response
        .then(res => res.json())
    // Then update the icon
        .then(function(res) {
            let watchlistIcon = watchlistLink.firstElementChild;
            if (res.isInWatchlist) {
                watchlistIcon.classList.remove('bi-bookmark-plus'); // Remove the .bi-heart (empty heart) from classes in <i> element
                watchlistIcon.classList.add('bi-bookmark-plus-fill'); // Add the .bi-heart-fill (full heart) from classes in <i> element
            } else {
                watchlistIcon.classList.remove('bi-bookmark-plus-fill'); // Remove the .bi-heart-fill (full heart) from classes in <i> element
                watchlistIcon.classList.add('bi-bookmark-plus'); // Add the .bi-heart (empty heart) from classes in <i> element
            }
        });
}

// AJAX favoriteSeries
document.querySelector("#favoriteSeries").addEventListener('click', addToFavoriteSeries);

function addToFavoriteSeries(event) {
    event.preventDefault();
    
    // Get the link object you click in the DOM
    let favoriteLink = event.currentTarget;
    let link = favoriteLink.href;
    // Send an HTTP request with fetch to the URI defined in the href
    fetch(link)
    // Extract the JSON from the response
        .then(res => res.json())
    // Then update the icon
        .then(function(res) {
            let favoriteIcon = favoriteLink.firstElementChild;
            if (res.isInFavoriteSeries) {
                favoriteIcon.classList.remove('bi-heart');
                favoriteIcon.classList.add('bi-heart-fill');
            } else {
                favoriteIcon.classList.remove('bi-heart-fill');
                favoriteIcon.classList.add('bi-heart');
            }
        });
}

// AJAX seenSeries
document.querySelector("#seenSeries").addEventListener('click', addToSeenSeries);

function addToSeenSeries(event) {
    event.preventDefault();
    
    // Get the link object you click in the DOM
    let seenLink = event.currentTarget;
    let link = seenLink.href;
    // Send an HTTP request with fetch to the URI defined in the href
    fetch(link)
    // Extract the JSON from the response
        .then(res => res.json())
    // Then update the icon
        .then(function(res) {
            let seenIcon = seenLink.firstElementChild;
            if (res.isInSeenSeries) {
                seenIcon.classList.remove('bi-eye');
                seenIcon.classList.add('bi-eye-fill');
            } else {
                seenIcon.classList.remove('bi-eye-fill');
                seenIcon.classList.add('bi-eye');
            }
        });
}