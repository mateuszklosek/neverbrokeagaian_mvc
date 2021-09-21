window.onload = function () {
	//drawPieChart();
	calculateTotalBalance()
}

$('select').change(function(){
    if (this.value == "customPeriod"){
		document.getElementById("modalCloseBtn").addEventListener("click", function(){
			//drawPieChart();
			calculateTotalBalance();
		});
    }
	else {
		//drawPieChart();
		calculateTotalBalance();
}});


/* function drawPieChart() {

	var table = document.getElementById("table3");
    var category = [];
	var amount = [];
	var dataPoint = [];
	var totalExpense = calculateTotalExpense();;
	
	
	for (var i = 1 ; i < table.rows.length; i++) {

		var row = "";
		
		var dataPoints = ""

		for (var j = 0; j < table.rows[i].cells.length; j++) {

			row = table.rows[i].cells[j].innerHTML;
			if(j%2==0) { category[i-1]= row; }
			else if (j%2==1) { amount[i-1] = row; }
		}
		dataPoint.push({ 
			y: (amount[i-1]/totalExpense),
			label: category[i-1]
		});

		}

	var chart = new CanvasJS.Chart("chartContainer", {
		theme: "light4",
		animationEnabled: true,
		backgroundColor: "transparent",
		title: {
			text: "Twoje wydatki z wybranego okresu",
			fontColor: "white",

		},
		subtitles: [{
			text: "",
			fontSize: 16,
			fontColor: "white",
		}],
		data: [{
			type: "pie",
			indexLabelFontSize: 18,
			indexLabelFontColor: "white",
			radius: 80,
			indexLabel: "{label} - #percent%",
			  yValueFormatString: "####.00%",
			click: explodePie,
			dataPoints: dataPoint
		}]
	});
	chart.render();

	function explodePie(e) {
		for(var i = 0; i < e.dataSeries.dataPoints.length; i++) {
			if(i !== e.dataPointIndex)
				e.dataSeries.dataPoints[i].exploded = false;
		}
	};
		
}
*/

function calculateTotalBalance() {
	
	var totalIncome = calculateTotalIncome();
	var totalExpense = calculateTotalExpense();
	var totalBalance = Math.round((totalIncome - totalExpense)*100)/100
	var userMessage = "";

	if(totalBalance > 0) { userMessage = "Gratulacje. Jesteś na dobrej drodze by osiągnąć wynaczone cele!"; }
	else if(totalBalance < 0) {userMessage = "Uwaga! Oddalasz się od osiągnięcia zamierzonych celów.";} 
	else { userMessage = ""; }
	
	$("#totalBalance").html("Bilans : <b>" + totalBalance + "</b>");
	$("#userInfo").html(userMessage);
	
	if(totalBalance > 0) { $("#userInfo").css({'color': '#83d9f1','font-weight':'bold'}); }
	else { $("#userInfo").css({'color': '#fe89a1','font-weight':'bold'}); }
};

function calculateTotalIncome() {
	
	var table2 = document.getElementById("table2");	
	var totalIncome = 0;
	
		for (var i = 1 ; i < tableOfIncomes.rows.length; i++) {
		for (var j = 0; j < tableOfIncomes.rows[i].cells.length; j++) {
			 if (j%2==1) { totalIncome+=parseFloat(tableOfIncomes.rows[i].cells[j].innerHTML); }
		}		
	}
	
	return totalIncome;
};

function calculateTotalExpense() {
	
	var table3 = document.getElementById("table3");
	var totalExpense = 0;
	
	for (var i = 1 ; i < tableOfExpenses.rows.length; i++) {
		for (var j = 0; j < tableOfExpenses.rows[i].cells.length; j++) {
			 if (j%2==1) { totalExpense+=parseFloat(tableOfExpenses.rows[i].cells[j].innerHTML); }
		}		
	}
	
	return totalExpense;
};
	
	