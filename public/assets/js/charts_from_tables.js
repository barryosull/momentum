(function(){

	function render_table_chart($table, type)
	{
   		units = $table.find('th:first').text();
   		var  options = get_default_chart_options_for(type);
	  
	  	options.chart.renderTo = $table.attr('data-chart-id');
	    options.yAxis.title.text = units;
	         
    	Highcharts.render_table($table, options);
	}

	function get_default_chart_options_for(type)
	{
		var default_options = {
	        chart: {
	            type: ''
	        },
	        title: {
	            text: ''
	        },
	        xAxis: {
	        },
	        yAxis: {
	            title: {
	                text: ''
	            }
	        },
	        tooltip: {
	            formatter: function() {
	                return '<b>'+ this.series.name +'</b><br/>'+
	                    this.y +' ('+ this.x+')';
	            }
	        }
	    };

		if(type=='column'){
			default_options.chart.type = type;
		}else if(type=='line'){
			default_options.chart.type = type;
		}else if(type=='stacked'){
			default_options.chart.type = 'column';
			default_options.plotOptions = {
                column: {
                    stacking: 'normal'
                }
            };
		}else{
			return alert('Chart type of "'+type+'" is unknown');
		}

		return default_options;
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

	    $('table.view_as_stacked_bar_chart').each(function() 
	   	{
	   		render_table_chart($(this), 'stacked');	
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