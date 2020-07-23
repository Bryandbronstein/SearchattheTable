function searchText(){
    let dropdown = document.getElementById("search_type");
    let season = dropdown.options[dropdown.selectedIndex].value;

    let keyword =  document.getElementById('search_bar').value;

    let httpReq = new XMLHttpRequest();
    httpReq.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            document.getElementById("fountainG").style.display = "none";
            //console.log(this.responseText);
            document.getElementById("output").innerHTML = this.responseText;
        }else{
            document.getElementById("fountainG").style.display = "block";
        }
    };
    httpReq.open("GET", "functions.php?season=" + season + "&keyword=" + keyword);
    httpReq.send(null);
}
