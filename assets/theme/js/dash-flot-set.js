$(function() {

        var d1 = [[0, 150], [4, 300], [7, 100], [13, 350]],
            d2 = [[0, 50], [1, 100], [2, 150], [3, 100], [4, 150], [5, 200], [6, 150], [7, 100], [8, 200], [9, 250], [10, 200], [11, 150], [12, 250], [13, 300]];;

        var plot = $.plot("#placeholder", [
            { 
                data: d1, 
                color: "#ffffff", 
                lines: { show: true }},
            { 
                data: d2, 
                color: "#425053", 
                lines: { show: true },
                points: { show: true }
            }]);
    });