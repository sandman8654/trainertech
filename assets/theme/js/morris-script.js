var Script = function () {

    //morris chart

    $(function () {

      Morris.Bar({
        element: 'hero-bar',
        data: [
          {device: 'Dumbell Chest Files', geekbench: 300},
          {device: 'Barbell Bench Press', geekbench: 400},
          {device: 'Push Press', geekbench: 350},
          {device: 'Squats', geekbench: 160},
          {device: 'Lungess', geekbench: 400},
          {device: 'Push Press', geekbench: 350},
          {device: 'Barbell Bench Press', geekbench: 400}
        ],
        xkey: 'device',
        ykeys: ['geekbench'],
        labels: ['Geekbench'],
        barRatio: 5,
        hideHover: 'auto',
        barColors: ['#425053']
      });

      $('.code-example').each(function (index, el) {
        eval($(el).text());
      });
    });

}();




