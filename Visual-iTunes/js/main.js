window.onload = function(){

}

function move(w) {
  var elem = document.getElementById("myBar");   
  var width = 0;
  var id = setInterval(frame, 10);
  function frame() {
    if (width == w) {
      clearInterval(id);
    } else {
      width++; 
      elem.style.width = width + '%'; 
    }
  }
}

Array.prototype.contains = function(v) {
    for(var i = 0; i < this.length; i++) {
        if(this[i] === v) return true;
    }
    return false;
};

Array.prototype.unique = function() {
    var arr = [];
    for(var i = 0; i < this.length; i++) {
        if(!arr.contains(this[i])) {
            arr.push(this[i]);
        }
    }
    return arr; 
}


function displayYearGraph(arr){
	document.getElementById("yearGraph").innerHTML = "From which year, do you have the most songs?";
	var len = arr.length;
	var uniques = arr.unique();
	var counts = Array.apply(null, Array(uniques.length)).map(Number.prototype.valueOf,0);
	var index;
	for (i in arr){
		index = uniques.indexOf(arr[i]);
		counts[index] = counts[index]+1;
	}
	var data = [{
		x: uniques,
		y: counts,
		type: 'bar'
	}];

	Plotly.newPlot('displayYearGraph', data);
}

function displayGenreGraph(arr){
	document.getElementById("genreGraph").innerHTML = "Your favorite genre";
	//alert(arr);
	var len = arr.length;
	var uniques = arr.unique();
	//alert(uniques);
	var counts = Array.apply(null, Array(uniques.length)).map(Number.prototype.valueOf,0);
	var index;
	for (i in arr){
		// alert(arr[i]);
		index = uniques.indexOf(arr[i]);
		// alert(index);
		counts[index] = counts[index]+1;
	}
	//alert(counts);
	var data = [{
		x: uniques,
		y: counts,
		type: 'bar'
	}];

	Plotly.newPlot('displayGenreGraph', data);
}

function displayArtistGraph(arr, songsTotal){
	document.getElementById("artistGraph").innerHTML = "Most songs by artists";
	// alert(arr);
	// alert(songsTotal);
	var len = arr.length;
	var uniques = arr.unique();
	//alert(uniques);
	var counts = Array.apply(null, Array(uniques.length)).map(Number.prototype.valueOf,0);
	var index;
	var percents = [];
	for (i in arr){
		//alert(arr[i]);
		index = uniques.indexOf(arr[i]);
		//alert(index);
		counts[index] = counts[index]+1;
		percents[index] = (counts[index]/songsTotal)*100;
	}
	var data = [{
		values: percents,
		labels: uniques,
		type: 'pie'
	}];

	var layout = {
	};

	Plotly.newPlot('displayArtistGraph', data, layout);
}

function findMax(playCount){
	var max = 0;
	for (i=0;i<playCount.length;i++){
		if (playCount[i] > max){
			max = playCount[i];
		}
	}
	return max;
}

function displayTopSong(playCount,nameOfSongs, artistName){
	var len = playCount.length;
	var max = findMax(playCount);
	// alert(playCount);
	// alert(nameOfSongs);
	// alert(max);
	var index = playCount.indexOf(max);
	var topSong = nameOfSongs[index];
	var topSongArtist = artistName[index];
	document.getElementById("topSong").innerHTML = "Your favorite song is "+topSong.toString()+". By: "+topSongArtist.toString()+". You have played it "+max.toString()+" number of times!";
	// alert(topSong);
}

function checkFile() {

	var file = document.getElementById('fileupload').files[0];
	if (file.name.split('.').pop() != "xml") {
		alert("Please submit an iTunes library XML file.");
		document.getElementById("submit-button").disabled = true;
	}
	else {

		document.getElementById("submit-button").disabled = false;
	}

}

function parse() {

	var graphs = document.getElementsByClassName("graph");
	for (i = 0; i < graphs.length; i++) {
		graphs[i].style.display = "block";
	}

	document.getElementById("someElement").innerHTML = ""; // clear

	var file = document.getElementById('fileupload').files[0];
	if (file) {
        document.getElementsByClassName("submit-result")[0].innerHTML = "&nbsp;" + file.name + " has been submitted.";
	}

	var fr = new FileReader();

	fr.readAsText(file);

	var years = [];
	var genres = [];
	var artists = [];
	var totalSongs = 0;
	var playCounts = [];
	var names = [];

	fr.onload = function(e) {
    // e.target.result contains text of file
		var fileText = e.target.result;

		if (window.DOMParser) {

			parser = new DOMParser();

			// If the three lines below are outside the if loop, code stops working unless there is an alert(dataString) before this line, and outside this function
			xmlDoc = parser.parseFromString(fileText,"text/xml"); // parse

			var tracksDict = xmlDoc.getElementsByTagName("dict")[1]; // dict associated with key "Tracks"

			var tracksDictChildren = tracksDict.childNodes;

			var trackLibrary = [];

			var bar = 15;
			move(bar);
			for (i = 0; i < tracksDictChildren.length && i < 10000*4; i+=1) { // starts from 1, every odd number after one (eg 1, 3, 5, 7...)
				
				//document.getElementById("someElement").innerHTML += tracksDictChildren[i*2+1].textContent;
				//document.getElementById("someElement").innerHTML += "<br/>";
				var doNotAdd = false;
				trackChildren = tracksDictChildren[i].childNodes; // attributes of each track
				totalSongs = totalSongs+1;

				if ((i-1)/2 % 2 == 1) { // every other child (track dicts only)
					var track = {};
					// var genres = [];

					for (j = 0; j < trackChildren.length; j++) {

						if (trackChildren[j].textContent == "Artist") {
							//alerttrackChildren[j+1].textContent;
							track["Artist"] = trackChildren[j+1].textContent;
						}
						else if (trackChildren[j].textContent == "Album") {
							//alerttrackChildren[j+1].textContent;
							track["Album"] = trackChildren[j+1].textContent;
						}
						else if (trackChildren[j].textContent == "Name") {
							//alerttrackChildren[j+1].textContent;
							track["Name"] = trackChildren[j+1].textContent;
						}
						else if (trackChildren[j].textContent == "Year") {
							//alerttrackChildren[j+1].textContent;
							track["Year"] = trackChildren[j+1].textContent;
						}
						else if (trackChildren[j].textContent == "Play Count") {
							//alerttrackChildren[j+1].textContent;
							track["Play Count"] = trackChildren[j+1].textContent;
						}
						else if (trackChildren[j].textContent == "Genre") {
							//alerttrackChildren[j+1].textContent;
							var a = trackChildren[j+1].textContent;
							track["Genre"] = a;
							// genres.push(a);
						}
						else if (trackChildren[j].textContent == "Podcast") { // has a key "Podcast"
							if (!document.getElementById("includePodcasts").checked) { // include podcasts checkbox is checked
								doNotAdd = true;
								break;
							}
						}
					}

					if (!doNotAdd)
						trackLibrary.push(track);
				}
			}
			var done = true;
			while (done){
				if ((bar+10) < 100){
						bar = bar + 10;
						move(bar);
					}
					else{
						move(100);
						done = false;
					}
			}
			// print all elements
			for(i = 1; i < trackLibrary.length; i+=1) {

			    var curTrack = trackLibrary[i];

			    // document.getElementById("someElement").innerHTML += i + ".<br/>";

				for(var key in curTrack) {
				    // document.getElementById("someElement").innerHTML += key + ": " + curTrack[key] + "<br/>";
				    if (key = "Year"){
				    	years.push(curTrack[key]);
				    }
				    if (key = "Genre"){
				    	genres.push(curTrack[key]);
				    }
				    if (key = "Artist"){
				    	artists.push(curTrack[key]);
				    }
				    if (key = "Play Count"){
				    	playCounts.push(curTrack[key]);
				    }
				    if (key = "Name"){
				    	names.push(curTrack[key]);
				    }
				}

				// document.getElementById("someElement").innerHTML += "<br/>";
			}
		}
		displayYearGraph(years);
		displayGenreGraph(genres);
		displayArtistGraph(artists, totalSongs);
		displayTopSong(playCounts,names,artists);

		// show loading bar
		document.getElementById("myProgress").style.display = "block";
		document.getElementById("myBar").style.display = "block";
	};
}