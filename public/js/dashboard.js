$(document).ready(function () {
    let fromDatePicker = $('input[name="fromDate"]');
    let toDatePicker = $('input[name="toDate"]');
    let container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
    let options = {
        format: 'yyyy-mm-dd',
        container: container,
        todayHighlight: true,
        autoclose: true,
    };
    toDatePicker.datepicker(options);
    fromDatePicker.datepicker(options);

});

function message(id, content, type, target) {
    $('<div class="alert alert-' + type + '" id="' + id + '">' + content + '</div>').hide().insertBefore(target).slideDown('500', function () {
        setTimeout(function () {
            $('#' + id).slideUp(500, function () {
                $(this).remove();
            });
        }, 5000);
    });
}

function getLastMonth() {
    let today = new Date().toISOString().slice(0, 10);
    let lastMonth = subtractDays(30);

    fetchData(lastMonth, today);

}

function subtractDays(days) {
    let result = new Date();
    result.setDate(result.getDate() - days);
    return result.toISOString().slice(0, 10);
}

function fetchData(fromDate, toDate) {
    let labels = [];
    let orders = [];
    let customers = [];
    let orderItems = [];
    $.ajax({
        url: "/dashboard.php?fromDate=" + fromDate + "&toDate=" + toDate,
        type: "GET",
        success: function (result) {
            labels = result.map(a => a.purchaseDate);
            orders = result.map(a => a.orderCount);
            customers = result.map(a => a.customerCount);
            orderItems = result.map(a => a.totalRevenue);

            addDataToChart(labels, orders, customers, orderItems);

        },
        error: function (xhr, status, error) {
            let errorObject = JSON.parse(xhr);
            let errorMessage =  errorObject["errorMessage"];
            message("error", errorMessage, "danger", '#fromDateLabel');
        }
    });
}

$('#submit').on('click', function () {
    let fromDate = $('#fromDate').val();
    let toDate = $('#toDate').val();

    if (fromDate === '' || toDate === '') {
        message("error", "Fill in from/to date", "danger", '#fromDateLabel');
        return;
    }

    if (new Date(fromDate) > new Date(toDate)) {
        message("error", "From date greater than to date", "danger", '#fromDateLabel');
        return;
    }

    fetchData(fromDate, toDate);
});

function addDataToChart(labels, orders, customers, orderItems) {
    $('#myChart').remove();
    $('#chartRow').append('<canvas id="myChart"><canvas>');
    const ctx = document.getElementById("myChart");
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Orders',
                    data: orders,
                    backgroundColor: 'rgba(255, 99, 132, 1)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Customers',
                    data: customers,
                    backgroundColor: 'rgba(33,220,16,0.5)',
                    borderColor: 'rgba(33,220,16,0.5)',
                    borderWidth: 1
                },
                {
                    label: 'Total Revenue',
                    data: orderItems,
                    backgroundColor: 'rgba(24,117,146,0.5)',
                    borderColor: 'rgba(24,117,146, 0.5)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
}