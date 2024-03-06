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
    let idJeu = $("#jeu-id").val()
    if (idJeu !== "1") {
        $("#submit-form").addClass("hidden")
        const routeGetScores = Routing.generate('get_score', { jeu: idJeu })
        $.ajax({
            url: routeGetScores,
            type: 'GET',
            success: function(response) {
                let tabScores = response.tableauScores
                $(tabScores).each(function(idx, val) {
                    $("#score-equipe-"+val.equipe).text(val.score)
                })
            }
        })
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
                if (lengthTabDifficulte === 1) {
                    //mystère
                    $("#level-difficulte").text("3")
                    $("#question-difficulte").text(tabQuestion.intitule)
                    $("#id-question").text(tabQuestion.id)
                    setTimeout(function () {
                        $("#question-difficulte").fadeIn()
                    }, 0)
                    $("#modal-animateurs-title").text("Question Mystère")
                    $("#list-difficulte").fadeOut()
                    $("#reponse-question").fadeIn()
                } else {
                    //autres questions
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
    getQuestionsReponses()
})

$("#reponse-question").on("click", function () {
    $(this).fadeOut()
    getQuestionsReponses(true)
    setTimeout(function () {
        $("#reponse-intitule").fadeIn()
        $("#bonne-rep").removeClass("hidden")
        $("#mauvaise-rep").removeClass("hidden")
    }, 200)
})

$("#bonne-rep").on("click", function () {
    let equipe = $('input[name="equipesRadio"]:checked').val()
    let difficulte = $('input[name="select-difficulte"]:checked').data("id") ? $('input[name="select-difficulte"]:checked').data("id") : "3"
    let jeu = $("#jeu-id").val()
    equipe = parseInt(equipe, 10)
    difficulte = parseInt(difficulte, 10)
    jeu = parseInt(jeu, 10)
    addScore(equipe, jeu, difficulte)
})

$(".close-modal-reponse").on("click", function() {
    let theme = $("#theme-id").text()
    let question = $("#id-question").text()
    $('input[name="equipesRadio"]').prop("checked", false)
    $('input[name="select-difficulte"]').prop("checked", false)
    $("#modal-animateurs-title").text("Sélectionnez la difficulté")
    $("#list-difficulte").fadeIn()

    $("#question-difficulte").fadeOut()
    $("#reponse-intitule").fadeOut()
    $("#theme-id").addClass("hidden")
    $("#level-difficulte").addClass("hidden")
    $("#id-question").addClass("hidden")

    $("#bonne-rep").addClass("hidden")
    $("#mauvaise-rep").addClass("hidden")
    
    verrouillageTheme(theme, question)

})

function verrouillageTheme(idTheme, idQuestion) {
    const urlVerrouillage = Routing.generate('verouillage_theme', { id: idTheme })
    $.ajax({
        url: urlVerrouillage,
        type: 'GET',
        data: {'question': idQuestion},
        success: function(response) {
            console.log(response)
        }, 
        error: function(error) {

        }
    })
}

function getQuestionsReponses(mystere = false) {
    let selectedDifficulte = $('input[name="select-difficulte"]:checked').data("id") ? $('input[name="select-difficulte"]:checked').data("id") : "3"
    let theme = $("#theme-id").text()
    setTimeout(function () {
        $("#level-difficulte").text(selectedDifficulte)
    }, 0)
    const urlQuestions = Routing.generate('check_difficulte', { difficulte: selectedDifficulte })
    $.ajax({
        url: urlQuestions,
        type: 'GET',
        data: { 'theme': theme },
        success: function (response) {
            $("#modal-animateurs-title").text("Thème : " + response.question.theme)
            $("#level-difficulte").text(selectedDifficulte)
            $("#question-difficulte").text(response.question.intitule)
            $("#reponse-intitule").val(response.question.reponseValide)
        },
        error: function (error) {

        }
    })
    if(mystere !== true) {
        setTimeout(function () {
            $("#list-difficulte").fadeOut()
            $("#question-difficulte").fadeIn()
            $("#reponse-question").fadeIn()
        }, 250)
    }
}


function resetList() {
    let list = $(".li-prop")
    $(list).each(function (idx, el) {
        $(el).hasClass("prop-selected") ? $(el).removeClass("prop-selected") : ""
    })
}

function addScore(equipe, idJeu, score) {
    const routeSetScore = Routing.generate('set_score', { jeu: idJeu })
    $.ajax({
        url: routeSetScore,
        type: 'POST',
        data: {'equipe': equipe, 'score': score},
        success: function(response) {
            $("#score-equipe-" + equipe).text(response.score)
        },
        error: function(error) {

        }
    })
}