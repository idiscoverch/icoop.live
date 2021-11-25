<!DOCTYPE html>
<html>
<head>
  <script src="https://unpkg.com/ag-grid-enterprise/dist/ag-grid-enterprise.min.noStyle.js"></script>
  <link rel="stylesheet" href="https://unpkg.com/ag-grid-community/dist/styles/ag-grid.css">
  <link rel="stylesheet" href="https://unpkg.com/ag-grid-community/dist/styles/ag-theme-balham.css">
</head>
<body>
  <h1>Hello from ag-grid!</h1>
  <div id="myGrid" style="height: 600px;width:100%;" class="ag-theme-balham"></div>

  <script type="text/javascript" charset="utf-8">
	// specify the columns
    var columnDefs = [
      {headerName: "Name", field: "name", sortable: true },
      {headerName: "ID Mobconticker", field: "id_mobconticker", sortable: true },
      {headerName: "ID Plantation", field: "id_plantation", sortable: true },
      {headerName: "Field Name", field: "field_name", sortable: true },
      {headerName: "Field Value", field: "field_value", sortable: true },
      {headerName: "Ticker Time", field: "ticker_time", sortable: true },
      {headerName: "Coordx", field: "coordx", sortable: true },
      {headerName: "Coordy", field: "coordy", sortable: true },
      {headerName: "Agent name", field: "agentname", sortable: true },
      {headerName: "ID Town", field: "id_town", sortable: true },
      {headerName: "Town Name", field: "town_name", sortable: true },
      {headerName: "Town Coordx", field: "town_coordx", sortable: true },
      {headerName: "Town Coordy", field: "town_coordy", sortable: true },
      {headerName: "ID Project", field: "id_project", sortable: true },
      {headerName: "ID task", field: "id_task", sortable: true },
      {headerName: "Field Table", field: "field_table", sortable: true }
    ];
    
    // specify the data
    var rowData = [];
	
	var resurl='listeslies.php?elemid=ag_grid';
    var xhr = getXhr();
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
			var data = leselect.split('@@');
			
			i=0;
			while(data[i]!='end'){
				var val = data[i].split('##');	
				rowData.push({name: val[0], id_mobconticker: val[1], id_plantation: val[2], field_name: val[3], field_value: val[4], ticker_time: val[5], coordx: val[6], coordy: val[7], agentname: val[8], id_town: val[9], town_name: val[10], town_coordx: val[11], town_coordy: val[12], id_project: val[13], id_task: val[14], field_table: val[15], sortable: true, filter: true });	
				i+=1;
			}
			
			agGrid.LicenseManager.setLicenseKey("Evaluation_License-_Not_For_Production_Valid_Until_15_June_2019__MTU2MDU1MzIwMDAwMA==4b46920f094749677e70a024c9dc9415");
			
			// let the grid know which columns and what data to use
			var gridOptions = {
			  columnDefs: columnDefs,
			  rowData: rowData
			};

		  // lookup the container we want the Grid to use
		  var eGridDiv = document.querySelector('#myGrid');

		  // create the grid passing in the div to use together with the columns & data we want to use
		  new agGrid.Grid(eGridDiv, gridOptions);
            
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null)
	
 
  
  function getXhr(){
	var xhr = null;
    if(window.XMLHttpRequest) {
		xhr = new XMLHttpRequest();

	} else if(window.ActiveXObject) {
		try {
			xhr = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			xhr = new ActiveXObject("Microsoft.XMLHTTP");
		}

    } else {
        alert("Votre navigateur ne supporte pas les objets XMLHTT");
        xhr = false;
    }

    return xhr;
}
  </script>
</body>
</html>