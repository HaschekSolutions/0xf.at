$(function() {
  stillalive();
});

//to keep sessions from resetting while on site
function stillalive()
{
    $.get("/backend.php?a=stillalive", function(data)
    {
        
    });

    setTimeout("stillalive()", 25000);
}

function loginViaStorage()
{
    if(supports_html5_storage())
    {
        if(localStorage.sid && localStorage.sid!="" && localStorage.sid.length > 10)
        {
            $.get("/backend.php?a=sid&sid="+escape(localStorage.sid), function(data)
            {
            	console.log(data);
            	var o = JSON.parse(data);
            	console.log(o);
                if(o.status=='OK')
                {
                    window.location.href="/login/bysid/"+localStorage.sid+"/";
                }
                else
                {
                    localStorage.sid = "";
                }
            });
        }
        else
        	localStorage.sid="";
    }
}

function supports_html5_storage()
{
  try {
    return 'localStorage' in window && window['localStorage'] !== null;
  } catch (e) {
    return false;
  }
}