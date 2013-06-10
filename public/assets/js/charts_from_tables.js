(function(){

	function render_table_chart($table, type)
	{
   		units = $table.find('th:first').text();
   		var  options = {
	        chart: {
	            renderTo: $table.attr('data-chart-id'),
	            type: type
	        },
	        title: {
	            text: ''
	        },
	        xAxis: {
	        },
	        yAxis: {
	            title: {
	                text: units
	            }
	        },
	        tooltip: {
	            formatter: function() {
	                return '<b>'+ this.series.name +'</b><br/>'+
	                    this.y +' ('+ this.x+')';
	            }
	        }
	    };

    	Highcharts.render_table($table, options);
	}

	$(function()
	{
	   	$('table.view_as_bar_chart').each(function() 
	   	{
	   		render_table_chart($(this), 'column');	
	    }); 

	    $('table.view_as_line_chart').each(function() 
	   	{ 
	   		render_table_chart($(this), 'line');	
	    });
	});

})();

Highcharts.render_table = function($table, options) 
{
    // the categories
    options.xAxis.categories = [];
    $table.find('tbody th').each( function(i) {
        options.xAxis.categories.push(this.innerHTML);
    });

    // the data series
    options.series = [];
    $table.find('tr').each( function(i) {
        var tr = this;
        $('th, td', tr).each( function(j) {
            if (j > 0) { // skip first column
                if (i == 0) { // get the name and init the series
                    options.series[j - 1] = {
                        name: this.innerHTML,
                        data: []
                    };
                } else { // add values
                    options.series[j - 1].data.push(parseFloat(this.innerHTML));
                }
            }
        });
    });
    var chart = new Highcharts.Chart(options);

    $table.hide();
}