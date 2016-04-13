function loadNew()
{
    var editor;

	$.get("/data/template.txt?r="+ Math.random(), function(data)
	{
	    $("#editor").text(data);
	    setTimeout(function(){
	    	editor = ace.edit("editor");
		    editor.setTheme("ace/theme/monokai");
		    editor.getSession().setMode("ace/mode/php");
	    },200);
	});
}

function getMyLevels()
{
	$.get("/backend.php?a=create&ad=l", function(data)
	{
	    var o = JSON.parse(data);
	    $.each(o.levels,function(key,value){
	    	$("#controls").append('<a href="javascript:void(0)" class="btn btn-default">Default</a>');
	    })
	    console.log(o);
	});
}