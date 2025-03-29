var myInput = document.getElementById("password");
var changeColor = document.getElementsByClassName("js-changeColor") ;

changeColor.onfocus = function(){
    changeColor.innerHTML.style.color = "red" ;
}
myInput.onfocus = function () {
    document.getElementById("message").style.display = "block" ;
    // document.getElementById("message").classList.toggle('hidden');
}

myInput.onblur = function () {
    document.getElementById("message").style.display = "none" ;
    // document.getElementById("message").classList.toggle('hidden');
}

var signupProfessor = document.getElementById("signupProfessor") ;
var firstSection = document.getElementById("firstSection") ;
var secondSection = document.getElementById("secondSection") ;

signupProfessor.onclick = function(){
    firstSection.classList.toggle = ('hidden') ;
    //secondSection.classList.toggle = ('hidden') ;
    secondSection.classList.toggle = ('block') ;

}
