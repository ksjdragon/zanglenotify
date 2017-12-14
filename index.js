document.getElementById("button").onclick = function() {
	var container = document.getElementById("container");
    container.innerHTML = getData();
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