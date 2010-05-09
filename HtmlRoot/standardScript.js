
function addParameter(uri, key, value)
{
	var separator = "?";

	if(uri.indexOf("?") >= 0)
	    separator = "&";
	return uri + separator + key + "=" + escape(value);
}


function createDataPacket(parameters) {
	var dataPacket = "";
	for(var i=0; i<parameters.length; i++) {
		var param = parameters[i] + ""; // ensure parameter is a string
		dataPacket += param.length + "\r" + param + "\r";
	}

	return dataPacket;
}

function makeXmlCallback(uri, callbackId) {
	return callback;

	function callback() {
		// Copy arguments to the function into a temp array
		var argsCalledWith = new Array(arguments.length);
		for(var i=0; i<arguments.length; i++)
			argsCalledWith[i] = arguments[i];

		var customHandler = null;
		// If last arg passed in is a function then it is the results handler function
		if (argsCalledWith.length > 0) {
			if (typeof argsCalledWith[argsCalledWith.length-1] == "function")
				customHandler = argsCalledWith.pop();
		}

		// create a closure to send to liveUpdater to use
		function helper() {
			return addParameter(uri, callbackId, createDataPacket(argsCalledWith));
		};

		function xmlProcessCallbackResults(response) {
			var serverResult = new Object();
			for(i=0; i < response.documentElement.childNodes.length; i++) {
				var child = response.documentElement.childNodes[i];
				if (child.tagName == "response")
					serverResult.text = child.firstChild.data;
				else
					xmlLiveProcessOne(child);
			}
			if (customHandler) customHandler(serverResult);
		}

		xmlLiveUpdater(helper, xmlProcessCallbackResults)();
	};
}

function xmlInstallCallback(id, callbackName, uri, callbackId) {
	var element = document.getElementById(id);
	if (element == null) {
		window.setTimeout("xmlInstallCallback(''" + id + "'', ''" + callbackName + "'', ''" + uri + "','" + callbackId + "'')", 250);
		return;
	}
	element[callbackName] = makeXmlCallback(uri, callbackId);
}




// Show the debug window
function showDebug() {
  window.top.debugWindow =
      window.open("",
                  "Debug",
                  "left=0,top=0,width=300,height=700,scrollbars=yes,"
                  +"status=yes,resizable=yes");
  window.top.debugWindow.opener = self;
  // open the document for writing
  window.top.debugWindow.document.open();
  window.top.debugWindow.document.write(
      "<HTML><HEAD><TITLE>Debug Window</TITLE></HEAD><BODY><PRE>\n");
}
// If the debug window exists, then write to it
function debug(text) {
  if (window.top.debugWindow && ! window.top.debugWindow.closed) {
    window.top.debugWindow.document.write(text+"\n");
  }
}
// If the debug window exists, then close it
function hideDebug() {
  if (window.top.debugWindow && ! window.top.debugWindow.closed) {
    window.top.debugWindow.close();
    window.top.debugWindow = null;
  }
}

function xmlAsString(element){
	return (new XMLSerializer()).serializeToString(element);
}

function liveUpdateDOM(target, template) {
	var childrenFound = false;
	for(var child = template.firstChild; child; child = child.nextSibling) {

		if (child.nodeType == 1) {

			childrenFound = true;
			if (!liveUpdateDOM(target[child.tagName], child))
	
			target[child.tagName] = child.firstChild.data;
		}

	
}

	return childrenFound;
}

function xmlLiveProcessOne(child) {

	/* 
	** This script came with some added functionality that 
	** I don't understand. 
	** It looks like it might be able to update
	** visual properties of the page but I am only
	** intrested (at the moment) in updateing the innerHtml
	** Leaving the functionality in for future reference
	*/
	
	if (child.tagName == "dom") {
		var elementId = child.getAttribute("id");
		var element = document.getElementById(elementId);
		liveUpdateDOM(element, child);
	}
	else if(child.tagName == "script") {
		if(child.textContent){
			eval(child.textContent);
		}
		else if(child.text){
			eval(child.text);
		}
		else if(child.innerText){
			eval(child.innerText);
		}else if(child.childNodes[1].data){
			//Safari!
			eval(child.childNodes[1].data);
		}
	}else /*if (child.tagName == "innerHtml")*/ {
		var elementId = child.getAttribute("id");
		var element = document.getElementById(elementId);
		if(child.firstChild.data){
			element.innerHTML = child.firstChild.data;
		}else{
			element.innerHTML = xmlAsString(child);
		}
	}
	
}



function xmlProcessResults(response) {
	
	for(i=0; i < response.documentElement.childNodes.length; i++) {
		
		var child = response.documentElement.childNodes[i];
		xmlLiveProcessOne(child);

	}

}


function xmlLiveUpdaterUri(uri) {
	/*
	** I have to encode all the attribute values on a page
	** in order for the page to be parsable XML so edencode 
	** The &amp; s 
	*/
	uri = uri.replace("&amp;","&");
	
    return xmlLiveUpdater(
function() { return uri; }, xmlProcessResults);

}



function xmlLiveUpdater(uriFunc, processResultsFunc)
{
    var request = false;

    if (window.XMLHttpRequest) {
       	request = new XMLHttpRequest();
    }

    update();
    function update()
    {
		
       if(request && request.readyState < 4)

            request.abort();

            
        if(!window.XMLHttpRequest)
            request = new ActiveXObject("Microsoft.XMLHTTP");
        
        request.onreadystatechange = processRequestChange;
	   var uri = addParameter(uriFunc(), "timestamp", (new Date()).getTime().toString());

        request.open("POST", uri.split("?")[0]);
	   request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

	   window.status = "Sending commands...";

        request.send(uri.split("?")[1]);

        return false;

    }
    
    function processRequestChange() {
		if(request.readyState == 4) {

		     window.status = "Getting new instructions...";
			
			if(request && request.responseXML && request.responseXML.documentElement) {
				
				processResultsFunc(request.responseXML);

				window.status = "Done";

			} else {
				
				document.location.reload();

			}

		}

    }

    return update;

}
