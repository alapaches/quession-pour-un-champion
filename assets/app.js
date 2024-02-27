// import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.scss'
import '../node_modules/bootstrap/dist/js/bootstrap'
require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');

// console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉')

$(function() {
    let questionTheme = $("#question_theme")
    let questionDifficulte = $("#question_difficulte")
    $(questionTheme).parent(".mb-3").addClass("hidden")
    $(questionDifficulte).parent(".mb-3").addClass("hidden")
    $(questionTheme).val(null)
    $(questionDifficulte).val(null)
})

$(".list-proposition").on("click", function(event) {
    $(this).hasClass("prop-selected") ? $(this).removeClass("prop-selected") : $(this).addClass("prop-selected")
    
})

$("#search-question").on("keyup", function () {
    let value = $(this).val().toLowerCase();
    $("#question-table tr").filter(function () {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
});

$("#search-proposition").on("keyup", function () {
    let value = $(this).val().toLowerCase();
    $("#proposition-table tr").filter(function () {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
});

$("#question_jeu").on("change", function(event) {
    
    let jeuSelected = $("option:selected", this).text()
    let questionTheme = $("#question_theme")
    let questionDifficulte = $("#question_difficulte")
    switch(jeuSelected) {
        case 'Les 12 coups de midi':
        case 'Blind Test':
            $(questionTheme).parent(".mb-3").addClass("hidden")
            $(questionTheme).val(null)
            $(questionDifficulte).parent(".mb-3").addClass("hidden")
            $(questionDifficulte).val(null)
        break;
        default:
            if($(questionTheme).parent(".mb-3").hasClass("hidden")) {
                $(questionTheme).parent(".mb-3").removeClass("hidden")
                $(questionTheme).val("1")
            }
            if($(questionDifficulte).parent(".mb-3").hasClass("hidden")) {
                $(questionDifficulte).parent(".mb-3").removeClass("hidden")
                $(questionDifficulte).val("1")
            }
    }
})

$(".list-proposition").on("click", function(event) {
    let currentId = $(this).data("id")
    
})