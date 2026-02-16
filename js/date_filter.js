(function ($, window) {
  "use strict";

  $(function () {
    var $input = $('#dateFilter');
    if (!$input.length) return; // nothing to initialize

    var $canned = $('#canned_date');
    var $dtf = $('#dtf');
    var $dtt = $('#dtt');

    // Get translations or use English defaults
    var translations = window.dateRangeTranslations || {
      'Today': 'Today',
      'Yesterday': 'Yesterday',
      'This Week': 'This Week',
      'Last Week': 'Last Week',
      'This Month': 'This Month',
      'Last Month': 'Last Month',
      'This Year': 'This Year',
      'Last Year': 'Last Year',
      'All Time': 'All Time'
    };

    // Default to "All Time" if nothing provided
    var hasValues =
      ($dtf.val() && $dtt.val()) ||
      ($canned.val() && $canned.val() !== '');

    if (!hasValues) {
      $canned.val('alltime');
      $dtf.val('1970-01-01');
      $dtt.val('2099-12-31');
    }

    var initialStart = moment($dtf.val(), "YYYY-MM-DD");
    var initialEnd = moment($dtt.val(), "YYYY-MM-DD");

    function setDisplay(start, end, label) {
      // Special display for All Time
      if (
        label === translations['All Time'] || label === 'All Time' ||
        (start.format('YYYY-MM-DD') === '1970-01-01' &&
         end.format('YYYY-MM-DD') === '2099-12-31')
      ) {
        $input.val(translations['All Time']);
      } else {
        $input.val(start.format('YYYY-MM-DD') + " — " + end.format('YYYY-MM-DD'));
      }
    }

    // Map translated labels to canned date values
    var cannedMap = {};
    cannedMap[translations['Today']] = "today";
    cannedMap[translations['Yesterday']] = "yesterday";
    cannedMap[translations['This Week']] = "thisweek";
    cannedMap[translations['Last Week']] = "lastweek";
    cannedMap[translations['This Month']] = "thismonth";
    cannedMap[translations['Last Month']] = "lastmonth";
    cannedMap[translations['This Year']] = "thisyear";
    cannedMap[translations['Last Year']] = "lastyear";
    cannedMap[translations['All Time']] = "alltime";

    // Build ranges object with translated labels
    var ranges = {};
    ranges[translations['Today']] = [moment(), moment()];
    ranges[translations['Yesterday']] = [moment().subtract(1, 'day'), moment().subtract(1, 'day')];
    ranges[translations['This Week']] = [moment().startOf('isoWeek'), moment()];
    ranges[translations['Last Week']] = [
      moment().subtract(1, 'week').startOf('isoWeek'),
      moment().subtract(1, 'week').endOf('isoWeek')
    ];
    ranges[translations['This Month']] = [moment().startOf('month'), moment()];
    ranges[translations['Last Month']] = [
      moment().subtract(1, 'month').startOf('month'),
      moment().subtract(1, 'month').endOf('month')
    ];
    ranges[translations['This Year']] = [moment().startOf('year'), moment()];
    ranges[translations['Last Year']] = [
      moment().subtract(1, 'year').startOf('year'),
      moment().subtract(1, 'year').endOf('year')
    ];
    ranges[translations['All Time']] = [
      moment('1970-01-01', 'YYYY-MM-DD'),
      moment('2099-12-31', 'YYYY-MM-DD')
    ];

    $input.daterangepicker({
      startDate: initialStart,
      endDate: initialEnd,
      autoUpdateInput: true,
      opens: 'left',
      locale: {
        format: 'YYYY-MM-DD',
        firstDay: 1
      },
      ranges: ranges
    }, setDisplay);

    // Show initial label
    setDisplay(initialStart, initialEnd);

    $input.on('apply.daterangepicker', function (ev, picker) {
      var label = picker.chosenLabel || '';
      var canned = cannedMap[label];

      if (canned) {
        $canned.val(canned);
        $dtf.val('');
        $dtt.val('');
      } else {
        $canned.val('custom');
        $dtf.val(picker.startDate.format('YYYY-MM-DD'));
        $dtt.val(picker.endDate.format('YYYY-MM-DD'));
      }

      setDisplay(picker.startDate, picker.endDate, label);

      // Auto-submit form
      this.form.submit();
    });
  });
})(jQuery, window);