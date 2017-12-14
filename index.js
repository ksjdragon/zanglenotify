var assignments;

document.getElementById("button").onclick = function() {
	var container = document.getElementById("container");
    assignments = getData();
    assignments = processData(assignments);
}

function processData(data) {
    var el = document.createElement("html");
    el.innerHTML = data.replace(/(src|href)="[^"]*"/gmi, "");;
    var organized = [];
    var classes = el.getElementsByClassName("displaytbl");
    for(var i = 0; i < classes.length; i++) {
        var eachClass = {};
        var teacher = classes[i].children[0].children[0].children[1].childNodes[2].nodeValue.replace(/\s/g,"").split(",");
        var grade = classes[i].children[0].children[1].children[0].childNodes[2].nodeValue.replace(/\s/g,"");

        eachClass["name"] = classes[i].children[0].children[0].children[0].children[1].innerText.match(/[a-z].*(?= \()/gi)[0];
        eachClass["teacher"] = [teacher[1], teacher[0]];
        eachClass["grade"] = grade.replace(/\(.+\)/g, "");
        eachClass["percent"] = parseFloat(grade.match(/[0-9][^%\)]*/g)) || "Unknown";

        var works = classes[i].children[1].children;
        var orgWork = [];
        for(var j = 0; j < works.length; j++) {
            var eachWork = {};
            var tabLen = works[j].children.length;

            eachWork["date"] = new Date(works[j].children[1].innerText);
            eachWork["workName"] = works[j].children[3].innerText;
            eachWork["totalPoints"] = parseFloat(works[j].children[4].innerText);
            eachWork["earnedPoints"] = parseFloat(works[j].children[5].innerText);
            if(works[j].children.length === 10) {
                eachWork["percent"] = Math.round(1000*eachWork["earnedPoints"]/eachWork["totalPoints"])/10;
            } else {
                eachWork["percent"] = parseFloat(works[j].children[6].innerText.replace("%",""));  
            }
            eachWork["scoreComments"] = works[j].children[tabLen-4].innerText;
            eachWork["extraCredit"] = works[j].children[tabLen-3].innerText;
            eachWork["notGraded"] = works[j].children[tabLen-2].innerText;
            eachWork["comments"] = works[j].children[tabLen-1].innerText;
            orgWork.push(eachWork);
        }
        eachClass["work"] = orgWork;
        organized.push(eachClass);
    }
    return organized;
}

function getData() {
	var username = document.getElementById("username").value;
	var password = document.getElementById("password").value;
	if(username === "" || password === "") {
		alert("Please fill out all forms!");
		return;
	}

	var xhr = new XMLHttpRequest();
	var data;
	var send = "Pin=" + username + "&Password=" + password;
	xhr.open("POST", "http://68.41.125.11:5000/ZangleAccess/index.php", false);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.onreadystatechange = function() {
		if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
			data = xhr.responseText;
		}
	}
	xhr.send(send);
	return data;
}