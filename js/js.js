function spoiler(id)
{
var obj = "";
// Проверить совместимость браузера
if(document.getElementById)
obj = document.getElementById(id).style;
else if(document.all)
obj = document.all[id];
else if(document.layers)
obj = document.layers[id];
else
return 1;
// Пошла магия
if(obj.display == "")
obj.display = "inline";
else if(obj.display != "none")
obj.display = "none";
else
obj.display = "inline";
}

$(document).ready(function() {
var wbbOpt = {
  buttons: "bold,italic,underline,|,img,link,|,code,quote,myquote",
  allButtons: {
    code: {
      hotkey: "ctrl+shift+3", //Добавление горячей клавиши
      transform: {
        '<div class="mycode"><div class="codetop">Это код программы:</div><div class="codemain">{SELTEXT}</div></div>':'[code]{SELTEXT}[/code]'
      }
    },
    myquote: {
      title: 'Вставка раздела страницы',
      buttonText: 'END',
      transform: {
        '<hr>':'[end][/end]'
      }
    }
  }
}
$("#editor").wysibb(wbbOpt);
});