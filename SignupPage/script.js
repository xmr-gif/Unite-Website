var myInput = document.getElementById("password");
var myInput2 = document.getElementById("password2");
var changeColor = document.getElementsByClassName("js-changeColor") ;

changeColor.onfocus = function(){
    changeColor.innerHTML.style.color = "red" ;
}
myInput.onfocus = function () {
    document.getElementById("message").style.display = "block" ;
    // document.getElementById("message").classList.toggle('hidden');
}
myInput2.onfocus = function () {
    document.getElementById("message2").style.display = "block" ;
    // document.getElementById("message").classList.toggle('hidden');
}

myInput.onblur = function () {
    document.getElementById("message").style.display = "none" ;
    // document.getElementById("message").classList.toggle('hidden');
}

myInput2.onblur = function () {
    document.getElementById("message2").style.display = "none" ;
    // document.getElementById("message").classList.toggle('hidden');
}

var signupProfessor = document.getElementById("signupProfessor") ;
var signupStudent = document.getElementById("signupStudent") ;

signupStudent.onclick = function(){
    document.getElementById("thirdSection").classList.remove("hidden") ;
    document.getElementById("firstSection").classList.add("hidden") ;
    document.getElementById("loginButton").classList.add("hidden") ;

}

signupProfessor.onclick = function(){
    document.getElementById("secondSection").classList.remove("hidden") ;
    document.getElementById("firstSection").classList.add("hidden") ;
    document.getElementById("loginButton").classList.add("hidden") ;

}

var closeButton = document.getElementById("closeButton") ;
var closeButton2 = document.getElementById("closeButton2") ;

closeButton.onclick =()=> {
    // alert(1);
    document.getElementById("firstSection").classList.remove("hidden") ;
    document.getElementById("secondSection").classList.add("hidden") ;
    document.getElementById("thirdSection").classList.add("hidden") ;
    document.getElementById("loginButton").classList.remove("hidden") ;

}

closeButton2.onclick =()=> {
    // alert(1);
    document.getElementById("firstSection").classList.remove("hidden") ;
    document.getElementById("secondSection").classList.add("hidden") ;
    document.getElementById("thirdSection").classList.add("hidden") ;

    document.getElementById("loginButton").classList.remove("hidden") ;

}
