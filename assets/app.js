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
    let jeu = $("#jeu-id").val()
    if(jeu !== "1") {
        $("#submit-form").addClass("hidden")
    }
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
    const checkRoute = Routing.generate('check_jeux', { id: idJeu })
    let equipe = $('input[name="equipesRadio"]:checked').val()
    let proposition = $('input[name="list-proposition"]:checked')
    let propositionList = $(proposition).parent("li")
    let propositionValue = $(proposition).val()
    if (!equipe || !propositionValue) {
        $("#toast-error").fadeIn()
        setTimeout(function () {
            $("#toast-error").fadeOut()
        }, 1500)
    } else {
        $.ajax({
            url: checkRoute,
            data: { 'equipe': equipe, 'proposition': propositionValue },
            type: 'POST',
            success: function (response) {
                $(propositionList).children('input[name="list-proposition"]').prop("disabled", true)
                if (response.validation === true) {
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

$("#question_theme").on("change", function () {
    let theme = $("option:selected", this).text()
    let questionDifficulte = $("#question_difficulte")
    if (theme === "Mystère") {
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

$("#next-question").on("click", function () {
    let currentQuestion = $(".current-question")
    let nextQuestion = $(currentQuestion).next(".question")
    $(currentQuestion).removeClass("current-question")
    $(currentQuestion).addClass("hidden")
    $(nextQuestion).removeClass("hidden")
    $(nextQuestion).addClass("current-question")

})

$(".div-icons").on("click", function () {
    let idTheme = $(this).data("theme")
    let idJeu = $("#jeu-id").val()
    let equipe = $('input[name="equipesRadio"]:checked').val()
    $("#theme-id").text(idTheme)
    const checkRoute = Routing.generate('check_jeux', { id: idJeu })
    if (!equipe) {
        $("#toast-body").text("Veuillez sélectionner une équipe")
        $("#toast-error").fadeIn()
        setTimeout(function () {
            $("#toast-error").fadeOut()
        }, 1500)
    } else {
        $.ajax({
            url: checkRoute,
            data: { 'idTheme': idTheme },
            type: 'GET',
            success: function (response) {
                let lengthTabDifficulte = response.difficulte.length
                let tabDifficulte = response.difficulte
                let tabQuestion = response.questions
                if (lengthTabDifficulte > 1) {
                    $(tabQuestion).each(function (idx, obj) {

                    })
                } else {
                    $("#level-difficulte").text("3")
                    $("#question-difficulte").text(tabQuestion.intitule)
                    $("#id-question").text(tabQuestion.id)
                    setTimeout(function () {
                        $("#question-difficulte").fadeIn()
                    }, 0)
                    $("#modal-animateurs-title").text("Question Mystère")
                    $("#list-difficulte").addClass("hidden")
                    $("#reponse-question").fadeIn()
                }
            },
            error: function (error) {

            },
            complete: function () {
                setTimeout(function () {
                    $("#modal-animateurs").modal("show")
                }, 250)
            }
        })
    }
})

$('input[name="select-difficulte"]').on("click", function () {
    let selectedDifficulte = $('input[name="select-difficulte"]:checked').data("id")
    let theme = $("#theme-id").text()
    setTimeout(function() {
        $("#level-difficulte").text(selectedDifficulte)
    }, 0)
    const urlQuestions = Routing.generate('check_difficulte', { difficulte: selectedDifficulte })

    $.ajax({
        url: urlQuestions,
        type: 'GET',
        data: {'theme': theme},
        success: function(response) {
            console.log(response.question)
        },
        error: function(error) {

        }
    })
    setTimeout(function () {
        $("#list-difficulte").fadeOut()
        $("#question-difficulte").fadeIn()
        $("#reponse-question").fadeIn()
    }, 250)
})

$("#reponse-question").on("click", function () {
    let idQuestion = $("#id-question").text()
    idQuestion = parseInt(idQuestion, 10)
    const checkReponse = Routing.generate('check_reponse', { id: idQuestion })
    $.ajax({
        type: 'GET',
        url: checkReponse,
        data: { 'idQuestion': idQuestion },
        success: function (data) {
            $("#reponse-intitule").val(data.reponseQuestion)
            $("#reponse-question").fadeOut()
            setTimeout(function () {
                $("#reponse-intitule").fadeIn()
                $("#bonne-rep").removeClass("hidden")
                $("#mauvaise-rep").removeClass("hidden")
            }, 200)
        },
        error: function (error) {

        }
    })
})

$("#bonne-rep").on("click", function() {
    let equipe = $('input[name="equipesRadio"]:checked').val()

})

function resetList() {
    let list = $(".li-prop")
    $(list).each(function (idx, el) {
        $(el).hasClass("prop-selected") ? $(el).removeClass("prop-selected") : ""
    })
}