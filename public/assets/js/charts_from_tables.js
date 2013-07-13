(function(){

	function render_table_chart($table)
	{
   		units = $table.find('th:first').text();
   		var  options = get_default_chart_options();
	  
	  	options.chart.renderTo = $table.attr('data-chart-id');
	    options.yAxis.title.text = units;
	         
    	Highcharts.render_table($table, options);
	}

	function get_default_chart_options()
	{
		var default_options = {
	        chart: {
	            type: 'column'
	        },
            plotOptions: {
                column: {
                    stacking: 'normal'
                }
            },
	        title: {
	            text: ''
	        },
            tooltip: {
                formatter: function() {
                    return '<b>'+ this.series.name +'</b><br/>'+
                        Helpers.Time.mins_to_string(this.y)
                         +' ('+ this.x+')';
                }
            },
	        xAxis: {},
	        yAxis: {
                labels: {
                    formatter: function() {
                        var text = Helpers.Time.mins_to_string(this.value);
                        if (text == '0mins') {
                            return '';
                        }
                        return text;
                   }
                },
                tickInterval: 120,  
	            title: {
	                text: ''
	            }
	        }
	    };
		return default_options;
	}

	$(function()
	{
	    $('table.view_as_stacked_bar_chart').each(function() {
	   		render_table_chart($(this));	
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