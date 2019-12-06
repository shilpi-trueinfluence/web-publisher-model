/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if ('chart_most_popular' == data.data_type) {
    var post_titles = [],
        post_comment_count = [];

    jQuery(data.post_data).each(function () {
        post_titles.push(this.post_title);
        post_comment_count.push(parseInt(this.comment_count));
    });

    jQuery('#chart-stats').highcharts({
        chart: {
            type: data.chart_type
        },
        title: {
            text: 'Most Popular Posts (by number of comments)'
        },
        xAxis: {
            categories: post_titles
        },
        yAxis: {
            title: {
                text: 'Number of Comments'
            }
        },
        series: [
            {
                name: 'Comments Count',
                data: post_comment_count
            }
        ]
    });
} else if ('chart_top_cat' == data.data_type) {

    var cat_titles = [],
            cat_count = [];

    jQuery(data.post_data).each(function () {

        cat_titles.push(this.name);
        cat_count.push(parseInt(this.count));

    });

    $('#chart-stats').highcharts({
        chart: {
            type: data.chart_type
        },
        title: {
            text: 'Top 5 Categories by Posts'
        },
        xAxis: {
            categories: cat_titles
        },
        yAxis: {
            title: {
                text: 'Number of Posts'
            },
            tickInterval: 5
        },
        series: [
            {
                name: 'Post Count',
                data: cat_count
            }
        ]
    });
}