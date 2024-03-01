// import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.scss'
import '../node_modules/bootstrap/dist/js/bootstrap'
import Routing from '../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');
const routes = require('./js/fos_js_routes.json');

Routing.setRoutingData(routes);



// console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉')

$(function () {
    let questionTheme = $("#question_theme")
    let questionDifficulte = $("#question_difficulte")
    $(questionTheme).parent(".mb-3").addClass("hidden")
    $(questionDifficulte).parent(".mb-3").addClass("hidden")
    $(questionTheme).val(null)
    $(questionDifficulte).val(null)
})

$('[name="list-proposition"]').on("change", function (e) {
    resetList()
    let propositionList = $(this).parent("li")
    $(propositionList).addClass("prop-selected")
})

$("#jeu-form").on("submit", function (e) {
    e.preventDefault()
    let idJeu = $("#jeu-id").val()
    let equipe = $('input[name="equipesRadio"]:checked').val()
    let proposition = $('input[name="list-proposition"]:checked')
    let propositionList = $(proposition).parent("li")
    let propositionValue = $(proposition).val()
    const checkRoute = Routing.generate('check_jeux', { id: idJeu })
    console.log(equipe)
    console.log(propositionValue)
    if (!equipe || !propositionValue) {
        $("#toast-error").fadeIn()
        setTimeout(function() {
            $("#toast-error").fadeOut()
        }, 1500)
    } else {
        $.ajax({
            url: checkRoute,
            data: { 'equipe': equipe, 'proposition': propositionValue },
            type: 'POST',
            success: function (response) {
                $(propositionList).children('input[name="list-proposition"]').prop("disabled", true)
                if(response.validation === true) {
                    $(propositionList).addClass("success")
                } else {
                    $(propositionList).addClass("error")
                    $("#next-question").removeClass("hidden")
                }
                proposition.prop('checked', false)
                $("#score-equipe-" + equipe).text(response.scoreEquipe)
            },
            error: function (error) {

            }
        })
    }
})

$(".list-proposition").on("click", function (event) {
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

$("#question_theme").on("change", function() {
    let theme = $("option:selected", this).text()
    let questionDifficulte = $("#question_difficulte")
    if(theme === "Mystère") {
        $(questionDifficulte).parent(".mb-3").addClass("hidden")
        $(questionDifficulte).val(3)
    } else {
        $(questionDifficulte).parent(".mb-3").removeClass("hidden")
        $(questionDifficulte).val(0)
    }
})

$("#question_jeu").on("change", function (event) {

    let jeuSelected = $("option:selected", this).text()
    let questionTheme = $("#question_theme")
    let questionDifficulte = $("#question_difficulte")
    switch (jeuSelected) {
        case 'Les 12 coups de midi':
        case 'Blind Test':
            $(questionTheme).parent(".mb-3").addClass("hidden")
            $(questionTheme).val(null)
            $(questionDifficulte).parent(".mb-3").addClass("hidden")
            $(questionDifficulte).val(null)
            break;
        default:
            if ($(questionTheme).parent(".mb-3").hasClass("hidden")) {
                $(questionTheme).parent(".mb-3").removeClass("hidden")
                $(questionTheme).val("1")
            }
            if ($(questionDifficulte).parent(".mb-3").hasClass("hidden")) {
                $(questionDifficulte).parent(".mb-3").removeClass("hidden")
                $(questionDifficulte).val("1")
            }
    }
})

$(".list-proposition").on("click", function (event) {
    let currentId = $(this).data("id")

})

$("#next-question").on("click", function () {
    let currentQuestion = $(".current-question")
    let nextQuestion = $(currentQuestion).next(".question")
    $(currentQuestion).removeClass("current-question")
    $(currentQuestion).addClass("hidden")
    $(nextQuestion).removeClass("hidden")
    $(nextQuestion).addClass("current-question")

})

function resetList() {
    let list = $(".li-prop")
    $(list).each(function (idx, el) {
        $(el).hasClass("prop-selected") ? $(el).removeClass("prop-selected") : ""
    })
}